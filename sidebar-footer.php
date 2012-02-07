<?php
/**
 * Footer SideBar Template
 * 
 * @package: WBootStrap
 * @file: sidebar-footer.php
 * @author Ohad Raz 
 * @since 0.1
 */
 ?>
 <?php 
 /* The footer widget area is triggered if any of the areas
  * have widgets. So let's check that first.
  *
  * If none of the sidebars have widgets, then let's bail early.
  */
if (! is_active_sidebar( 'blog-footer'  ) && ! is_active_sidebar( 'page-footer'  ) && ! is_active_sidebar( 'home-footer' ))
	return;

// If we get this far, we have widgets. Let do this.
?>
<div class="row">
<?php
// Home page footer widgets
 if ((is_home() || is_front_page()) && is_active_sidebar( 'home-footer' )){
 	dynamic_sidebar('home-footer');
// page footer widgets
 }elseif (is_page() && is_active_sidebar('page-footer')) {
 	dynamic_sidebar('page-footer');
// everything else footer widgets
 }elseif(is_active_sidebar('blog-footer')){
 	dynamic_sidebar('blog-footer');
 }
 ?>
</div>