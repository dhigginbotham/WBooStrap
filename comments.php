<?php
/*
 * @package: WBootStrap
 * @file: comments.php
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

<?php if ( have_comments() ) : ?>
	
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
  
	<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
    	<!-- If comments are open, but there are no comments. -->

	<?php else : // comments are closed 
	?>
	
	<?php
		$suppress_comments_message = of_get_option('suppress_comments_message');

		if (is_page() && $suppress_comments_message) :
	?>
			
		<?php else : ?>
		
			<!-- If comments are closed. -->
			<p class="alert-message info"><?php _e('Comments are closed','WBootStrap'); ?>.</p>
			
		<?php endif; ?>

	<?php endif; ?>

<?php endif; ?>


<?php if ( comments_open() ) : ?>

<section id="respond" class="respond-form">

	<h3 id="comment-form-title"><?php comment_form_title( __('Leave a Reply','WBootStrap'), __('Leave a Reply to %s','WBootStrap') ); ?></h3>

	<div id="cancel-comment-reply">
		<p class="small"><?php cancel_comment_reply_link(); ?></p>
	</div>

	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
  	<div class="help">
  		<p><?php _e('You must be','WBootStrap'); ?> <a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e('logged in','WBootStrap'); ?></a> <?php _e('to post a comment.','WBootStrap'); ?></p>
  	</div>
	<?php else : ?>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" class="form-stacked" id="commentform">

		<?php if ( is_user_logged_in() ) : ?>
			<p class="comments-logged-in-as"><?php _e('Logged in as ','WBootStrap'); ?><a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?php _e('Log out &raquo;','WBootStrap'); ?></a></p>
		<?php else : ?>
			<ul id="comment-form-elements" class="clearfix">
				<li>
					<div class="clearfix">
					  <label for="author"><?php _e('Name','WBootStrap'); if ($req) _e('required','WBootStrap'); ?></label>
					  <div class="input">
						<input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" placeholder="<?php _e('Your Name','WBootStrap'); ?>" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
					  </div>
					</div>
				</li>
				<li>
					<div class="clearfix">
					  <label for="email"><?php _e('Mail','WBootStrap'); if ($req) _e('required','WBootStrap'); ?></label>
					  <div class="input">
						<input type="email" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" placeholder="<?php _e('Your Email','WBootStrap'); ?>" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
						<span class="help-block"><?php _e('(will not be published)','WBootStrap'); ?></span>
					  </div>
					</div>
				</li>
				<li>
					<div class="clearfix">
					  <label for="url"><?php _e('Website','WBootStrap'); ?></label>
					  <div class="input">
						<input type="url" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" placeholder="<?php _e('Your Website','WBootStrap'); ?>" tabindex="3" />
					  </div>
					</div>
				</li>
			</ul>

		<?php endif; ?>
	
		<div class="clearfix">
			<div class="input">
				<textarea name="comment" id="comment" placeholder="<?php _e('Your Comment Here...','WBootStrap'); ?>" tabindex="4"></textarea>
			</div>
		</div>
		
		<div class="actions">
		  <input class="btn primary" name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />
		  <?php comment_id_fields(); ?>
		</div>
		
		<?php do_action('comment_form', $post->ID); ?>
	</form>
	<?php endif; // If registration required and not logged in ?>
</section>

<?php endif; // if you delete this the sky will fall on your head ?>