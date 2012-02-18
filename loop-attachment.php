<?php
/**
 * The loop that displays a single attachment.
 *
 * @package: WBootStrap
 * @file: loop-attachment.php
 * @author Ohad Raz 
 * @since 0.1
*/
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						<header>
							<?php the_post_thumbnail( 'WBootStrap' ); ?>
							<h1 class="single-title" itemprop="headline"><?php the_title(); ?></h1>
							<p class="meta"><?php _e("Posted", "WBootStrap"); ?> <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_time('F jS, Y'); ?></time> <?php _e("by", "WBootStrap"); ?> <?php the_author_posts_link(); ?> <span class="amp">&</span> <?php _e("filed under", "WBootStrap"); ?> <?php the_category(', '); ?>.</p>
						</header> <!-- end article header -->
					
						<section class="post_content clearfix" itemprop="articleBody">
							<?php 
							echo apply_filters('before_post_content','');
							if ( wp_attachment_is_image() ) :
								$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
								foreach ( $attachments as $k => $attachment ) {
									if ( $attachment->ID == $post->ID )
										break;
								}
								$k++;
								// If there is more than 1 image attachment in a gallery
								if ( count( $attachments ) > 1 ) {
									if ( isset( $attachments[ $k ] ) )
										// get the URL of the next image attachment
										$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
									else
										// or get the URL of the first image attachment
										$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
								} else {
									// or, if there's only 1 image attachment, get the URL of the image
									$next_attachment_url = wp_get_attachment_url();
								}
								?>
								<p class="attachment"><a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
									$attachment_width = apply_filters( 'bootstrap_attachment_size', 900 );
									$attachment_height = apply_filters( 'bootstrap_attachment_height', 900 );
									echo wp_get_attachment_image( $post->ID, array( $attachment_width, $attachment_height ) ); // filterable image width with, essentially, no limit for image height.
								?></a></p>
								
								<div id="nav-below" class="navigation">
									<?php WBootStrap_next_prev_nav("nav-below"); ?>
								</div><!-- #nav-below -->

							<?php else : ?>
								<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a>
							<?php endif; ?>
							
							<?php
							the_content();
							echo apply_filters('after_post_content','');
							?>
						</section> <!-- end article section -->
						
						<footer>
							<?php the_tags('<p class="tags"><span class="tags-title">Tags:</span> ', ' ', '</p>'); ?>
							<?php do_action('WBootStrap_single_in_footer'); ?>
						</footer> <!-- end article footer -->
					</article> <!-- end article -->
					
				<?php // no comments on attachment pages comments_template(); 
				
			endwhile;

			 else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e('Not Found','WBootStrap'); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e('Sorry, but the requested resource was not found on this site.','WBootStrap'); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
			<?php endif; ?>