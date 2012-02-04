<?php
/*
 * @package: WBootStrap
 * @file: functions.php
 * @since WBootStrap 0.1
 */
 ?>
<?php

//Add Thumbnail support

add_theme_support('post-thumbnails');
// Thumbnail sizes
add_image_size( 'wpbs-featured', 580, 300, true );
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


//comments 
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