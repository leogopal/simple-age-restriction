<?php
/**
 * The main plugin file.
 * 
 * This file loads the main plugin class and gets things running.
 *
 * @since 0.0.1
 * 
 * @package Simple_Age_Restriction
 */

/**
 * Plugin Name: Simple Age Restriction
 * Description: Age restrict a website in WordPress.
 * Author:      Leo Gopal
 * Author URI:  http://leogopal.com
 * Version:     0.0.1
 * Text Domain: sera
 * Domain Path: /languages/
 */

if ( ! defined( 'WPINC' ) ) { die(); }

/**
 * The main class definition.
 */
require( plugin_dir_path( __FILE__ ) . 'includes/class-simple-age-restriction.php' );


// Get the plugin running.
add_action( 'plugins_loaded', array( 'Simple_Age_Restriction', 'get_instance' ) );

// Check that the admin is loaded.
if ( is_admin() ) {
	
	/**
	 * The admin class definition.
	 */
	require( plugin_dir_path( __FILE__ ) . 'includes/admin/class-simple-age-restriction-admin.php' );
	
	// Get the plugin's admin running.
	add_action( 'plugins_loaded', array( 'Simple_Age_Restriction_Admin', 'get_instance' ) );
}
