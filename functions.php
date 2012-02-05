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

//Add Thumbnail support

add_theme_support('post-thumbnails');
// Thumbnail sizes
add_image_size( 'WBootStrap', 580, 200, true );
add_image_size( 'bones-thumb-600', 600, 150, false );
add_image_size( 'bones-thumb-300', 300, 100, true );

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

/*
* top menu fallback
* @since WBootStrap 0.1
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
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
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
 * WBootStrap_breadcrumb function
 * 
 * Used for displaying breadcrumb.
 * To override this walker in a child theme without modifying the template
 * simply create your own WBootStrap_breadcrumb(), and that function will be used instead.
 * 
 * 
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
		      	if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
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
	}//end function 
}//end if

