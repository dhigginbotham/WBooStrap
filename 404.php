<?php
/**
 * 404 Page Template
 * 
 * @package: WBootStrap
 * @file: 404.php
 * @author Ohad Raz 
 * @since 0.1
 */
 ?>
<?php get_header(); ?>
		<div class="row"><!-- main container -->
			<div class="<?php echo apply_filters('404_content_row_span_class','span8'); ?>"><!-- content container -->
				<header class="jumbotron subhead" id="overview">
        			<h1><?php _e( 'This is Embarrassing', 'WBootStrap' ); ?></h1>
        			<p class="lead"><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'WBootStrap' ); ?></p>
     			</header>
			</div><!-- end content container -->
			<div class="well">
					<?php get_search_form(); ?>
			</div><!--/.well -->
			<div class="row">
				<div class="span4">
					<h2>All Pages</h2>
					<?php wp_page_menu(); ?>
				</div><!--/.span4 -->
				<div class="span4">
					<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>
					<h2><?php _e( 'Most Used Categories', 'WBootStrap' ); ?></h2>
					<ul>
						<?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10 ) ); ?>
					</ul>
				</div><!--/.span4 -->
			</div><!--/.row -->
			
			<?php get_sidebar('blog'); ?>
		</div><!-- end main container -->
<?php get_footer(); ?>