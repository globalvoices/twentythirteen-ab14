<?php

/**
 * Functions.php file for twentythirteen-ab14
 */


/**
 * Filter post class to add .ltr if the 'gv-ltr' postmeta is true
 * 
 * Expects a metabox in the post editor to check for "LTR". 
 * 
 * @param string $classes Other classes that will be shown
 * @param string $class Classes specified in the post_class() call (NOT IMPORTANT)
 * @param integer $post_id 
 * @return string List of classes with ours added if necessary
 */
function gv_filter_post_classes_ltr ($classes, $class, $post_id) {

	$is_rtl = get_post_meta($post_id, 'gv-ltr', true);
	if ($is_rtl)
		$classes[] = 'ltr';
	return $classes;
}
add_filter('post_class', 'gv_filter_post_classes_ltr', 10, 3);

/**
 * Filter post class to add .extra-wide if the 'gv-extra-wide' postmeta is true
 * 
 * Expects a metabox in the post editor with checkbox for gv-extra-wide". 
 * Depends on CSS to use .extra-wide to remove max-width.
 * 
 * Only affects single.php/page.php to avoid mucking in main posts well of homepage 
 * 
 * @param string $classes Other classes that will be shown
 * @param string $class Classes specified in the post_class() call (NOT IMPORTANT)
 * @param integer $post_id 
 * @return string List of classes with ours added if necessary
 */
function gv_filter_post_classes_extra_wide ($classes, $class, $post_id) {

	/**
	 * Only add the class on single/page views and only for the main post
	 */
	if (!is_singular() OR ($post_id != get_queried_object_id()))
		return $classes;
	
	$is_rtl = get_post_meta($post_id, 'gv-extra-wide', true);
	if ($is_rtl)
		$classes[] = 'extra-wide';
	return $classes;
}
add_filter('post_class', 'gv_filter_post_classes_extra_wide', 10, 3);

/**
 * Filter 'sidebars_widgets' (currently active widgets) to clear out sidebar-2 if gv-extra-wide
 * 
 * Ensures that sidebar-2 (the actual "sidebar" of twentythirteen) is not displayed when the 
 * gv-extra-wide checkbox is ticked so that there's space for the extra-wide content.
 * 
 * @param array $sidebars_widgets All sidebars and the widgets they contain
 * @return type
 */
function gv_filter_sidebars_widgets_extra_wide($sidebars_widgets) {
	
	// Exit if sidebar-2 is already empty
	if (!isset($sidebars_widgets['sidebar-2']) OR !count($sidebars_widgets['sidebar-2']))
		return $sidebars_widgets;
	
	// Only continue if we're on single.php or page.php
	if (!is_singular())
		return $sidebars_widgets;
	
	// If gv-extra-wide postmeta is true dump all widgets from sidebar-2
	if (get_post_meta(get_queried_object_id(), 'gv-extra-wide', true))
		unset($sidebars_widgets['sidebar-2']);

	return $sidebars_widgets;
}
add_filter('sidebars_widgets', 'gv_filter_sidebars_widgets_extra_wide', 10, 3);

/**
 * Register sidebars for GV News Theme.
 * Runs during 'init' action
 */
function gv_register_sidebars() {
	/*
	 * Default widget stuff for reference
	 *
	 * 	'before_widget' => '<li id="%1$s" class="widget %2$s">',
	 *	'after_widget' => "</li>\n",
	 */

	/**
	 * Homepage intro - above content full-width
	 */
//	register_sidebar(array(
//		'name'=>'Homepage Intro',
//		'id' => 'homepage_intro',
//		'description' => 'This sidebar shows on the homepage above the recent posts. Widget(s) will be full-width.',
//		'before_widget' => '<div id="%1$s" class="widget %2$s">',
//		'after_widget' => "</div><!--.widget-contents-->\n</div><!--.div-->",
//		'before_title' => "<h2 class='widgettitle'>",
//		'after_title' => "</h2>\n<div class='widget-contents'>\n"
//	));
	

}
add_action('init', 'gv_register_sidebars', 1);

/**
 * Register 'featured_image' big thumbnails for use in slider.
 * See gv_get_featured_image() and gv_save_featured_image() to see how we use them in conjunction with the featured_image postmeta value
 */
//add_theme_support( 'post-thumbnails' );

/**
 * http://codex.wordpress.org/Custom_Headers
 */
//add_theme_support( 'custom-header', array(
//	'uploads'                => true,
//	'default-image'          => '',
//	
////	'random-default'         => false,
////	'width'                  => 0,
////	'height'                 => 0,
//	'flex-height'            => true,
//	'flex-width'             => true,
////	'default-text-color'     => '',
////	'header-text'            => true,
////	'wp-head-callback'       => '',
////	'admin-head-callback'    => '',
////	'admin-preview-callback' => '',
//) );

//add_image_size( 'featured_image', 400, 300, true ); // Permalink thumbnail size

/**
 * Register our editor stylesheet for TinyMCE to look like our styles
 */
//add_editor_style('editor.css');

/**
 * Add shortcode support to widgets
 */
//if (!is_admin())
//  add_filter('widget_text', 'do_shortcode', 11);

/**
 * Register custom postmeta fields with the Custom Medatata Manager plugin
 *
 * Convert to some other format if this ever stops working
 */
function gv_custom_metadata_manager_admin_init() {
	/**
	 * Exit if the plugin isn't present
	 */
	if(!function_exists( 'x_add_metadata_field' ) OR !function_exists( 'x_add_metadata_group' ) )
		return;
	/**
	 * Register a group for pages and posts
	 */
	x_add_metadata_group('gv_custom_metadata_posts', array('post', 'page'), array(
		'label' => 'Post Settings (Global Voices)',
		'priority' => 'high',
	));
	/**
	 * Extra-wide switch, pages only
	 */
	x_add_metadata_field( 'gv-ltr', array('post', 'page'), array(
		'group' => 'gv_custom_metadata_posts',
		'label' => 'ENGLISH/LTR: Check this box to display this post as left-to-right (Assumes the site is RTL/right-to-left)',
		'field_type' => 'checkbox',
	));
	/**
	 * Extra-wide switch, pages only
	 */
	x_add_metadata_field('gv-extra-wide', array('post', 'page'), array(
		'group' => 'gv_custom_metadata_posts',
		'label' => 'Full-width content (hide sidebar and fill space with content)',
		'field_type' => 'checkbox',
	));	
}
add_action( 'admin_init', 'gv_custom_metadata_manager_admin_init' );


// Add our settings to $gv if it exists
if (is_object($gv)) :
	/**
	 * Default plugins we always want on (will be activated automatically)
	 * see gv_activate_default_plugins()
	 */
	$gv->default_plugins = array (
		'wp-print/wp-print.php',
//		'akismet/akismet.php',
		'contact-form-7/wp-contact-form-7.php',
		'really-simple-captcha/really-simple-captcha.php',
		'google-analyticator/google-analyticator.php',
		'gv-plugin/gv-plugin.php',
		'collapsing_page_menu/collapsing_page_menu.php',
		'capsman/capsman.php',
		'limit-login-attempts/limit-login-attempts.php',		
		'wordpress-mobile-edition/wp-mobile.php',
		'custom-metadata/custom_metadata.php',
//		'wp-status-notifier/status-notifier.php'
	);

	// Activate Debug Bar automatically if the dev site constant was set
	if (defined('GV_IS_DEV_SITE') AND GV_IS_DEV_SITE)
		$gv->default_plugins[] = 'debug-bar/debug-bar.php';
	
	
endif; // is_object($gv)
?>