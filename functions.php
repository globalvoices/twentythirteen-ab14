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
	x_add_metadata_group('gv_custom_metadata_posts', array('post'), array(
		'label' => 'Language Settings',
		'priority' => 'high',
	));
	/**
	 * Extra-wide switch, pages only
	 */
	x_add_metadata_field( 'gv-ltr', array('post'), array(
		'group' => 'gv_custom_metadata_posts',
		'label' => 'ENGLISH POST: Display this post as left-to-right (Assumes the site is RTL/right-to-left)',
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