<?php
/**
 * A theme updater for GitHub hosted Wordpress themes.  
 * This Wordpress class is to be included in a theme to automatically checks GitHub for theme updates
 * and enables automatic install.  
 * 
 * This class is a fork of the Theme Updater plugin by Douglas Beck https://github.com/UCF/Theme-Updater.
 * 
 * Usage: 
 * 
 * 1. Edit your theme's style.css and add Github Theme URI: with the github repository url ex:
 *
  		Theme Name: Example  
		Theme URI: http://example.com/  
		Github Theme URI: https://github.com/username/repo
		Description: My Example Theme
		Author: person
		Version: v1.0.0
 * 2. in your themes functions.php include this file ex:
 * include_once('updater/updater.php');
 * 
 * 3. Just make sure you do have a github repository and to use git tag and push when ever you create a new version.
  	$ git tag v1.0.0
	$ git push origin v1.0.0
 * 
 */
if (is_admin() && current_user_can('update_themes')){
	if (!class_exists('theme_updater_github')){
		class theme_updater_github{
			/**
			 * Updater Constructor
			 * @author Ohad Raz
			 * @since 0.1
			 */
			public function __construct(){
				if (is_admin()){
					require_once('assets.php');
					add_filter('site_transient_update_themes', array($this,'transient_update_themes_filter'));
					add_filter('upgrader_source_selection', array($this,'upgrader_source_selection_filter', 10, 3));
				}
			}

			/**
			 * Add Github Theme Updater to return $data and hook into admin
			 * 
			 * @author Ohad Raz
			 * @since 0.1
			 * @param  [type] $data [description]
			 * @return [type]
			 */
			function transient_update_themes_filter($data){
				
				$installed_themes = get_themes( );
				foreach ( (array) $installed_themes as $theme_title => $theme ) {
					
					// Get Theme's URI
					if(isset($theme['Stylesheet Files'][0]) && is_readable($theme['Stylesheet Files'][0])){
						$theme_file = $theme['Stylesheet Dir'] . '/style.css';
						$default_headers = array('UpdateURI' => 'Github Theme URI');
						$theme_data = get_file_data( $theme_file, $default_headers, 'theme' );
						if(empty($theme_data['UpdateURI'])){
							continue;
						}
						$theme['UpdateURI'] = $theme_data['UpdateURI'];
						$theme_key = $theme['Stylesheet'];
					}
					
					
					// Add Github Theme Updater to return $data and hook into admin
					remove_action( "after_theme_row_" . $theme['Stylesheet'], 'wp_theme_update_row');
					add_action( "after_theme_row_" . $theme['Stylesheet'], 'github_theme_update_row', 11, 2 );
					
					// Grab Github Tags
					preg_match(
						'/http(s)?:\/\/github.com\/(?<username>[\w-]+)\/(?<repo>[\w-]+)$/',
						$theme['UpdateURI'],
						$matches);
					if(!isset($matches['username']) or !isset($matches['repo'])){
						$data->response[$theme_key]['error'] = 'Incorrect github project url.  Format should be (no trailing slash): <code style="background:#FFFBE4;">https://github.com/&lt;username&gt;/&lt;repo&gt;</code>';
						continue;
					}
					$url = 'https://github.com/api/v2/json/repos/show/' . 
							$matches['username'] . '/' . $matches['repo'] .
							'/tags';
					
					$response = get_transient(md5($url)); // Note: WP transients fail if key is long than 45 characters
					if(empty($response)){
						$raw_response = wp_remote_get($url, array('sslverify' => false, 'timeout' => 10));
						if ( is_wp_error( $raw_response ) ){
							$data->response[$theme_key]['error'] = "Error response from " . $url;
							continue;
						}
						$response = json_decode($raw_response['body']);
						if(isset($response->error)){
							if(is_array($response->error)){
								$errors = '';
								foreach ( $response->error as $error) {
									$errors .= ' ' . $error;
								}
							} else {
								$errors = print_r($response->error, true);
							}
							$data->response[$theme_key]['error'] = sprintf('While <a href="%s">fetching tags</a> api error</a>: <span class="error">%s</span>', $url, $errors);
							continue;
						}
						
						if(!isset($response->tags) or count(get_object_vars($response->tags)) < 1){
							$data->response[$theme_key]['error'] = "Github theme does not have any tags";
							continue;
						}
						
						//set cache, just 60 seconds
						set_transient(md5($url), $response, 30);
					}
					
					// Sort and get latest tag
					$tags = array_keys(get_object_vars($response->tags));
					usort($tags, "version_compare");
					
					
					// check for rollback
					if(isset($_GET['rollback'])){
						$data->response[$theme_key]['package'] = $theme['UpdateURI'] . '/zipball/' . urlencode($_GET['rollback']);
						continue;
					}
					
					
					// check and generate download link
					$newest_tag = array_pop($tags);
					if(version_compare($theme['Version'],  $newest_tag, '>=')){
						// up-to-date!
						$data->up_to_date[$theme_key]['rollback'] = $tags;
						continue;
					}
					
					
					// new update available, add to $data
					$download_link = $theme['UpdateURI'] . '/zipball/' . $newest_tag;
					$update = array();
					$update['new_version'] = $newest_tag;
					$update['url']         = $theme['UpdateURI'];
					$update['package']     = $download_link;
					$data->response[$theme_key] = $update;
					
				}
				
				return $data;
			}



			/**
			 * Change upgrade source to github
			 * 
			 * Github delivers zip files as <Username>-<TagName>-<Hash>.zip
			 * must rename this zip file to the accurate theme folder
			 * 
			 * @author Ohad Raz
			 * @since 0.1
			 * @param  [type] $source             [description]
			 * @param  [type] $remote_source=NULL [description]
			 * @param  [type] $upgrader=NULL      [description]
			 * @return [type]
			 */
			function upgrader_source_selection_filter($source, $remote_source=NULL, $upgrader=NULL){
				/*
					
				*/
				if(isset($source, $remote_source, $upgrader->skin->theme)){
					$corrected_source = $remote_source . '/' . $upgrader->skin->theme . '/';
					if(@rename($source, $corrected_source)){
						return $corrected_source;
					} else {
						$upgrader->skin->feedback("Unable to rename downloaded theme.");
						return new WP_Error();
					}
				}
				return $source;
			}

		}//end class
	}//end if
	$git_hub_updater = new theme_updater_github();
}