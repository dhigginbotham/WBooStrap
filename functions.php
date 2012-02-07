<?php
/**
 * Theme Functions
 * 
 * @package: WBootStrap
 * @file: functions.php
 * @author Ohad Raz 
 * @since 0.1
 */
 ?>
<?php
define("theme_version", '0.1');

/** Thumbnail support **/

// Thumbnail sizes
add_image_size( 'WBootStrap', 580, 200, true );

// Add Thumbnails in Manage Posts/Pages List
if ( !function_exists('AddThumbColumn') && function_exists('add_theme_support') ) {
    //Add Thumbnail support for post and page
    add_theme_support('post-thumbnails', array( 'post', 'page' ) );
    if (is_admin()){
	    function AddThumbColumn($cols) {
	        $cols['thumbnail'] = __('Thumbnail');
	        return $cols;
	    }
	    function AddThumbValue($column_name, $post_id) {
	        $width = (int) 35;
	        $height = (int) 35;
	        if ( 'thumbnail' == $column_name ) {
	            // thumbnail of WP 2.9
	            $thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
	            // image from gallery
	            $attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
	            if ($thumbnail_id)
	                $thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
	            elseif ($attachments) {
	                foreach ( $attachments as $attachment_id => $attachment ) {
	                    $thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
	            	}
				}
	            if ( isset($thumb) && $thumb ) {
	            	echo $thumb;
				} else {
	            	echo __('None');
				}
			}
	    }
	    // for posts
	    add_filter( 'manage_posts_columns', 'AddThumbColumn' );
	    add_action( 'manage_posts_custom_column', 'AddThumbValue', 10, 2 );

	    // for pages
	    add_filter( 'manage_pages_columns', 'AddThumbColumn' );
	    add_action( 'manage_pages_custom_column', 'AddThumbValue', 10, 2 );
	}
}
/** End Thumbnail support **/


//menus
add_action('init', 'register_custom_menu');

/**
 * register_custom_menu
 * @author : Ohad Raz
 * @since : 0.1
 */
function register_custom_menu() {
	register_nav_menu('top_menu', __('Top Menu'));
	register_nav_menu('footer_menu', __('Footer Menu'));
}
/**
 * top_menu_fallback 
 * @author Ohad Raz
 * @since 0.1
 */
function top_menu_fallback(){
	$top_menu =	'
	<ul class="nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>
	';
	echo apply_filters('top_menu_fallback_filter',$top_menu);
}
/** End Menus support **/


/** sidebar Support **/

// Custom Admin Sidebar Switcher
function sidebar_switcher() {
	global $pagenow;
	if ($pagenow == 'widgets.php'){
		?>
		<script type="text/javascript">
		jQuery("document").ready(function(){
			var sidebars = new Array(); // Create array to hold our list of widget areas
			var selectorHTML, name; // Declaring variables isn't necessary in JavaScript, but it's good practice
		 
			jQuery('.widget-liquid-right .sidebar-name h3').each(function(index) {
				name = jQuery(this).html(); // Get the name of each widget area
				name = name.replace(/\s*<span>.*<\/span>/,''); // Remove extra <span> block from name
				sidebars.push(name); // Add the name to our array
			});
		 
			jQuery('.widget-liquid-right .widgets-holder-wrap').hide(); // Hide all the widget areas in list
			jQuery('.widget-liquid-right .widgets-holder-wrap:first').show(); // Show the first
		 
			// Start <select> block. Position to the right of the "Widgets" heading.
			selectorHTML = "<select id=\"sidebarSelector\" style=\"position: absolute; left: 400px; top: 68px;\">\n";
		 
			var count = 0;
			for ( var i in sidebars ) // Add option for each widgetized area
				selectorHTML = selectorHTML + "<option value=\"" + count++ + "\">" + sidebars[i] + "</option>\n"; // Store the index of the widget area in the 'value' attribute
		 
			selectorHTML = selectorHTML + "</select>"; // Close the <select> block
		 
			jQuery('div.wrap').append(selectorHTML); // Insert it into the DOM
		 
			jQuery('#sidebarSelector').change(function(){ // When the user selects something from the select box...
				index = jQuery(this).val(); // Figure out which one they chose
				jQuery('.widget-liquid-right .widgets-holder-wrap').hide(); // Hide all the widget areas
				jQuery('.widget-liquid-right .widgets-holder-wrap:eq(' + index + ')').show(); // And show only the corresponding one
			});
		});
		</script>
		<?php
	}
}
add_action('admin_footer', 'sidebar_switcher');




/**
  * WBootStrap_comment callback
  * 
  * Used as a callback by wp_list_comments() for displaying the comments.
  * To override this walker in a child theme without modifying the comments template
  * simply create your own WBootStrap_comment(), and that function will be used instead.
  * 
  * @author Ohad raz
  * @since 0.1
  * @param [type] $comment [description]
  * @param [type] $args    [description]
  * @param [type] $depth   [description]
  */
if ( ! function_exists( 'WBootStrap_comment' ) ) {
	function WBootStrap_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) {
			case 'pingback' :
			case 'trackback' :
				?>
				<li class="post pingback">
					<p><?php _e( 'Pingback:', 'WBootStrap' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'WBootStrap' ), ' ' ); ?></p>
				<?php
				break;
			default :
				?>
				<li <?php comment_class('well'); ?> id="li-comment-<?php comment_ID(); ?>">
					<article id="comment-<?php comment_ID(); ?>" class="comment">
						<footer>
							<div class="comment-author vcard">
								<?php echo get_avatar( $comment, 40 ); ?>
								<?php printf( __( '%s <span class="says">says:</span>', 'WBootStrap' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
							</div><!-- .comment-author .vcard -->
							<?php if ( $comment->comment_approved == '0' ) : ?>
								<em><?php _e( 'Your comment is awaiting moderation.', 'WBootStrap' ); ?></em>
								<br />
							<?php endif; ?>

							<div class="comment-meta commentmetadata">
								<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
								<?php
									/* translators: 1: date, 2: time */
									printf( __( '%1$s at %2$s', 'WBootStrap' ), get_comment_date(), get_comment_time() ); ?>
								</time></a>
								<?php edit_comment_link( __( '(Edit)', 'WBootStrap' ), ' ' );
								?>
							</div><!-- .comment-meta .commentmetadata -->
						</footer>

						<div class="comment-content"><?php comment_text(); ?></div>

						<div class="reply">
							<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						</div><!-- .reply -->
					</article><!-- #comment-## -->

				<?php
				break;
		}//end switch
	}//end function 
} // ends if

/**
 * WBootStrap_pagination 
 * 
 * Used for displaying Pagination nav links.
 * To override this walker in a child theme without modifying the template
 * simply create your own WBootStrap_pagination(), and that function will be used instead.
 * 
 * @uses WBootStrap_pagination_filter filter the output of the function
 * 
 * @author Ohad Raz
 * @since 0.1
 * @param  integer  $pages Max number of pages
 * @param  integer $range how many numbers to display
 */
if (!function_exists('WBootStrap_pagination')){
	function WBootStrap_pagination($pages = '', $range = 4){
	    $showitems = ($range * 2)+1; 
	    global $paged;
	    if(empty($paged)) 
	    	$paged = 1;
		if($pages == ''){
	    	global $wp_query;
			$pages = $wp_query->max_num_pages;
			if(!$pages)
	        	$pages = 1;
		}  
	    if(1 != $pages){
	        $retVal =  '<div class="pagination"><ul><li><a>'.__('Page','WBootStrap').' '. $paged.' '.__('of','WBootStrap').$pages.'</a></li>';
	        if($paged > 2 && $paged > $range+1 && $showitems < $pages) 
	        	$retVal .= '<li><a href="'.get_pagenum_link(1).'">'.__('&laquo; First','WBootStrap').'</a></li>';
	        if($paged > 1 && $showitems < $pages) 
	         	$retVal .= '<li><a href="'.get_pagenum_link($paged - 1).'">'.__('&lsaquo; Previous','WBootStrap').'</a></li>';
	        for ($i=1; $i <= $pages; $i++){
	            if ( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ){
					$retVal .= ($paged == $i)? '<li class="active"><a>'.$i.'</a></li>':'<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
	            }
	        }
	 
	        if ($paged < $pages && $showitems < $pages)
	        	$retVal .= '<a href="'.get_pagenum_link($paged + 1).'">'.__('Next &rsaquo;','WBootStrap').'</a>';
	        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) 
	        	$retVal .= '<a href="'.get_pagenum_link($pages).'">'.__('Last &raquo;','WBootStrap').'</a></li>';
	        $retVal .= '</ul></div>';

	        echo apply_filters('WBootStrap_pagination_filter',$retVal);
	    }
	}//end WBootStrap_pagination
}//end if

/**
 * WBootStrap_breadcrumb function
 * 
 * Used for displaying breadcrumb.
 * To override this walker in a child theme without modifying the template
 * simply create your own WBootStrap_breadcrumb(), and that function will be used instead.
 * 
 * @uses WBootStrap_breadcrumb_filter filter the output of the function
 * @author Ohad Raz
 * @since 0.1
 */
if (!function_exists('WBootStrap_breadcrumb')){
	function WBootStrap_breadcrumb() {
		 
		$delimiter = '<span class="divider">/</span>';
		$home = __('Home','WBootStrap'); // text for the 'Home' link
		$before = '<li class="active">'; // tag before the current crumb
		$after = '</li>'; // tag after the current crumb
		if ( !is_home() && !is_front_page() || is_paged() ) {
			$retVal =  '<div id="crumbs"><ul class="breadcrumb">';

		    global $post;
		    $homeLink = get_bloginfo('url');
		    $retVal .= '<li><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . '</li> ';

			if ( is_category() ) {
		     	global $wp_query;
		      	$cat_obj = $wp_query->get_queried_object();
		      	$thisCat = $cat_obj->term_id;
		      	$thisCat = get_category($thisCat);
		      	$parentCat = get_category($thisCat->parent);
		      	if ($thisCat->parent != 0)
		      		$retVal .= (get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
		      	$retVal .= $before . __('Archive by category','WBootStrap').' "' . single_cat_title('', false) . '"' . $after;
		    } elseif ( is_day() ) {
		      	$retVal .= '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . '</li> ';
		      	$retVal .= '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . '</li> ';
		      	$retVal .= $before . get_the_time('d') . $after;
		    } elseif ( is_month() ) {
		      	$retVal .= '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . '</li> ';
		      	$retVal .= $before . get_the_time('F') . $after;
		    } elseif ( is_year() ) {
				$retVal .= $before . get_the_time('Y') . $after;
		    } elseif ( is_single() && !is_attachment() ) {
		    	if ( get_post_type() != 'post' ) {
		        	$post_type = get_post_type_object(get_post_type());
		        	$slug = $post_type->rewrite;
		        	$retVal .= '<li><a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . '</li> ';
		        	$retVal .= $before . get_the_title() . $after;
		      	} else {
		        	$cat = get_the_category(); $cat = $cat[0];
		        	$retVal .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
		        	$retVal .= $before . get_the_title() . $after;
		     	}
		    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
		    	$post_type = get_post_type_object(get_post_type());
		      	$retVal .= $before . $post_type->labels->singular_name . $after;
		    } elseif ( is_attachment() ) {
		    	$parent = get_post($post->post_parent);
		      	$cat = get_the_category($parent->ID); $cat = $cat[0];
		      	$retVal .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
		      	$retVal .= '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . '</li> ';
		     	$retVal .= $before . get_the_title() . $after;
			} elseif ( is_page() && !$post->post_parent ) {
		    	$retVal .= $before . get_the_title() . $after;
		    } elseif ( is_page() && $post->post_parent ) {
				$parent_id  = $post->post_parent;
		    	$breadcrumbs = array();
		    	while ($parent_id) {
		        	$page = get_page($parent_id);
		        	$breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
		        	$parent_id  = $page->post_parent;
		     	}
		      	$breadcrumbs = array_reverse($breadcrumbs);
		      	foreach ($breadcrumbs as $crumb) $retVal .= $crumb . ' ' . $delimiter . ' ';
		      	$retVal .= $before . get_the_title() . $after;
		    } elseif ( is_search() ) {
		    	$retVal .= $before . __('Search results for','WBootStrap').' "' . get_search_query() . '"' . $after;
		    } elseif ( is_tag() ) {
				$retVal .= $before . __('Posts tagged','WBootStrap').' "' . single_tag_title('', false) . '"' . $after;
		    } elseif ( is_author() ) {
				global $author;
		    	$userdata = get_userdata($author);
				$retVal .= $before . __('Articles posted by ','WBootStrap') . $userdata->display_name . $after;
		    } elseif ( is_404() ) {
				$retVal .= $before . __('Error 404','WBootStrap') . $after;
		    }
		    if ( get_query_var('paged') ) {
		    	if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $retVal .= ' (';
					$retVal .= __('Page') . ' ' . get_query_var('paged');
		      	if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $retVal .= ')';
		    }
		    $retVal .= '</ul></div>';
		    echo apply_filters('WBootStrap_breadcrumb_filter',$retVal);
		}
	}//end WBootStrap_breadcrumb 
}//end if

/**
 * Register widgetized area and update sidebar with default widgets
 */
function bootstrapwp_widgets_init() {
	register_sidebar( array(
		'name' => 'Page Sidebar',
		'id' => 'sidebar-page',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	register_sidebar( array(
		'name' => 'Posts Sidebar',
		'id' => 'sidebar-posts',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

  	register_sidebar(array(
	    'name' => 'Home Left',
	    'id'   => 'home-left',
	    'description'   => 'Left textbox on homepage',
	    'before_widget' => '<div id="%1$s" class="widget %2$s">',
	    'after_widget'  => '</div>',
	    'before_title'  => '<h2>',
	    'after_title'   => '</h2>'
	));

    register_sidebar(array(
    'name' => 'Home Middle',
    'id'   => 'home-middle',
    'description'   => 'Middle textbox on homepage',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));

    register_sidebar(array(
    'name' => 'Home Right',
    'id'   => 'home-right',
    'description'   => 'Right textbox on homepage',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));

}
add_action( 'init', 'bootstrapwp_widgets_init' );
/** BootStrap Goodies **/

/**
 * WBootStrap_load_js description
 * 
 * This function loads all bootstrap JavaScript files.
 * 
 * 
 * 
 * @uses WBootStrap_js_enqueue_filter you can remove any of the files from the queue with the 
 * 
 * 
 * @author ohad raz
 * @since 0.1
 */
function WBootStrap_load_js(){
	$JS_Files = array(
		'prettify' => 'prettify.js',
		'transition' => 'bootstrap-transition.js',
		'alert' => 'bootstrap-transition.js',
		'modal' => 'bootstrap-modal.js',
		'dropdown' => 'bootstrap-dropdown.js',
		'scrollspy' => 'bootstrap-scrollspy.js',
		'tab' => 'bootstrap-tab.js',
		'tooltip' => 'bootstrap-tooltip.js',
		'popover' => 'bootstrap-popover.js',
		'button' => 'bootstrap-button.js',
		'collapse' => 'bootstrap-collapse.js',
		'carousel' => 'bootstrap-carousel.js',
		'typeahead' => 'bootstrap-typeahead.js',
		'tablesorter' => 'jquery.tablesorter.js',
	);
	$JS_Files = apply_filters('WBootStrap_js_enqueue_filter',$JS_Files);
	foreach ($JS_Files as $key => $value) {
		wp_enqueue_script($key, get_template_directory_uri().'/assets/js/'.$value, array('jquery'),theme_version, true );
	}
	
	/*
		//to load manually each script use:
	wp_enqueue_script('prettify', get_template_directory_uri().'/assets/js/prettify.js', array('jquery'),theme_version, true );
    wp_enqueue_script('transition', get_template_directory_uri().'/assets/js/bootstrap-transition.js', array('jquery'),theme_version, true );
    wp_enqueue_script('alert', get_template_directory_uri().'/assets/js/bootstrap-alert.js', array('jquery'),theme_version, true );
    wp_enqueue_script('modal', get_template_directory_uri().'/assets/js/bootstrap-modal.js', array('jquery'),theme_version, true );
    wp_enqueue_script('dropdown', get_template_directory_uri().'/assets/js/bootstrap-dropdown.js', array('jquery'),theme_version, true );
    wp_enqueue_script('scrollspy', get_template_directory_uri().'/assets/js/bootstrap-scrollspy.js', array('jquery'),theme_version, true );
    wp_enqueue_script('tab', get_template_directory_uri().'/assets/js/bootstrap-tab.js', array('jquery'),theme_version, true );
    wp_enqueue_script('tooltip', get_template_directory_uri().'/assets/js/bootstrap-tooltip.js', array('jquery'),theme_version, true );
    wp_enqueue_script('popover', get_template_directory_uri().'/assets/js/bootstrap-popover.js', array('tooltip.js'),theme_version, true );
    wp_enqueue_script('button', get_template_directory_uri().'/assets/js/bootstrap-button.js', array('jquery'),theme_version, true );
    wp_enqueue_script('collapse', get_template_directory_uri().'/assets/js/bootstrap-collapse.js', array('jquery'),theme_version, true );        
    wp_enqueue_script('carousel', get_template_directory_uri().'/assets/js/bootstrap-carousel.js', array('jquery'),theme_version, true );    
    wp_enqueue_script('typeahead', get_template_directory_uri().'/assets/js/bootstrap-typeahead.js', array('jquery'),theme_version, true );
    wp_enqueue_script('tablesorter', get_template_directory_uri().'/assets/js/jquery.tablesorter.js', array('jquery'),theme_version, true );
    */
}


if (!is_admin()){
	//load script if front end
	add_action('wp_enqueue_scripts', 'WBootStrap_load_js');

	//load shortcodes
	include_once('Bainternet-Framework/shortcodes.php')
}

/** End BootStrap Goodies **/