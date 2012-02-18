<?php
/**
 * Single Post Template
 * 
 * @package: WBootStrap
 * @file: single.php
 * @author Ohad Raz 
 * @since 0.1
 */
 ?>
<?php get_header(); ?>
		<div class="row"><!-- main container -->
			<div class="<?php echo apply_filters('content_row_span_class','span8'); ?>"><!-- content container -->
			
			
			<?php
			   /** Run the loop to output the page.
			    * If you want to overload this in a child theme then include a file
			    * called loop-single.php and that will be used instead.
			    */
			   
			   get_template_part( 'loop', 'single' );
			?>
			
			</div><!-- end content container -->
			
			<?php get_sidebar('blog'); ?>
			
		</div><!-- end main container -->
		
<?php get_footer(); ?>