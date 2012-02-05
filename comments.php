<?php
/**
 * Comments Template
 * 
 * @package: WBootStrap
 * @file: comments.php
 * @author Ohad Raz 
 * @since 0.1
 */
 ?>
 <?php
// Do not delete these lines
  if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('Please do not load this page directly. Thanks!');

  if ( post_password_required() ) { ?>
  	<div class="alert-message info">
    	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','WBootStrap'); ?></p>
  	</div>
  <?php
    return;
  }
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ){ ?>
	
	<h3 id="comments"><?php comments_number(__('<span>No</span> Responses','WBootStrap'), __('<span>One</span> Response','WBootStrap'), __('<span>%</span> Responses','WBootStrap') ); _e('to &#8220;','WBootStrap'); the_title(); _e('&#8221;','WBootStrap'); ?></h3>

	<nav id="comment-nav">
		<ul class="clearfix">
	  		<li><?php previous_comments_link(__('Older Comments','WBootStrap')) ?></li>
	  		<li><?php next_comments_link(__('Newer Comments','WBootStrap')) ?></li>
	 	</ul>
	</nav>
	
	<ol class="commentlist">
		<?php wp_list_comments(array(
			'callback' => 'WBootStrap_comment'
			)); 
		?>
	</ol>
	
	<nav id="comment-nav">
		<ul class="clearfix">
	  		<li><?php previous_comments_link(__('Older Comments','WBootStrap')) ?></li>
	  		<li><?php next_comments_link(__('Newer Comments','WBootStrap')) ?></li>
		</ul>
	</nav>
  
	<?php }elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) { ?>
		<!-- If comments are closed. -->
		<p class="alert-message info"><?php _e('Comments are closed','WBootStrap'); ?>.</p>
	<?php } 
	
	comment_form(); 