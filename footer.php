<?php
/*
 * @package: WBootStrap
 * @file: footer.php
 */
 ?>
		<footer role="contentinfo">
			<div id="inner-footer" class="clearfix">
				<nav class="clearfix">
					<?php // Adjust using Menus in Wordpress Admin ?>
				</nav>

				<p class="pull-right"><a href="#">Back to top</a></p>
				<p class="attribution">&copy; <?php the_time('Y') ?> <?php bloginfo('name'); ?>  <?php _e("is brought to you by", "WBootStrap"); ?> <a href="http://en.bainternet.info" title="WordPress Services">Bainternet WordPress Services</a>, <a href="http://wordpress.org/" title="WordPress">WordPress</a> <span class="amp">&</span> <a href="http://twitter.github.com/bootstrap/" title="Twitter Bootstrap">Twitter Bootstrap</a>.</p>
			</div> <!-- end #inner-footer -->
				
		</footer> <!-- end footer -->
		
	</div> <!-- end #container -->
		
	<!-- scripts are now optimized via Modernizr.load -->	
	<script src="<?php echo get_template_directory_uri(); ?>/library/js/scripts.js"></script>
		
	<!--[if lt IE 7 ]>
  		<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
  		<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->
		
	<?php wp_footer(); // js scripts are inserted using this function ?>

	</body>
</html>