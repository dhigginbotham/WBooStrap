<?php
/**
 * Archive Template
 * 
 * @package: WBootStrap
 * @file: archive.php
 * @author Ohad Raz 
 * @since 0.1
 */
 ?>
<?php get_header(); ?>
		<div class="row"><!-- main container -->
			<div class="<?php echo apply_filters('archive_content_row_span_class','span8'); ?>"><!-- content container -->
			<?php if (is_category()) { ?>
						<h1 class="archive_title h2">
							<span><?php _e("Posts Categorized:", "WBootStrap"); ?></span> <?php single_cat_title(); ?>
						</h1>
					<?php } elseif (is_tag()) { ?> 
						<h1 class="archive_title h2">
							<span><?php _e("Posts Tagged:", "WBootStrap"); ?></span> <?php single_tag_title(); ?>
						</h1>
					<?php } elseif (is_author()) { ?>
						<h1 class="archive_title h2">
							<span><?php _e("Posts By:", "WBootStrap"); ?></span> <?php get_the_author_meta('display_name'); ?>
						</h1>
					<?php } elseif (is_day()) { ?>
						<h1 class="archive_title h2">
							<span><?php _e("Daily Archives:", "WBootStrap"); ?></span> <?php the_time('l, F j, Y'); ?>
						</h1>
					<?php } elseif (is_month()) { ?>
					    <h1 class="archive_title h2">
					    	<span><?php _e("Monthly Archives:", "WBootStrap"); ?>:</span> <?php the_time('F Y'); ?>
					    </h1>
					<?php } elseif (is_year()) { ?>
					    <h1 class="archive_title h2">
					    	<span><?php _e("Yearly Archives:", "WBootStrap"); ?>:</span> <?php the_time('Y'); ?>
					    </h1>
					<?php } ?>

			<?php
			   /** Run the loop to output the page.
			    * If you want to overload this in a child theme then include a file
			    * called loop-single.php and that will be used instead.
			    */
			   
			   get_template_part( 'loop');
			?>
			
			</div><!-- end content container -->
			
			<?php get_sidebar('blog'); ?>
			
		</div><!-- end main container -->
		
<?php get_footer(); ?>