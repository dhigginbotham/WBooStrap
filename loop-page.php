<?php
/**
 * The loop that displays a page.
 * @package: WBootStrap
 * @file: loop-page.php
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
			the_content();
			echo apply_filters('after_post_content','');
			?>
		</section> <!-- end article section -->
						
		<footer>
			<?php the_tags('<p class="tags"><span class="tags-title">Tags:</span> ', ' ', '</p>'); ?>
			<?php do_action('WBootStrap_single_in_footer'); ?>
		</footer> <!-- end article footer -->
	</article> <!-- end article -->
					
	<?php // no cpmments on pages comments_template(); 
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