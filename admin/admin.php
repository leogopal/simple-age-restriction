<?php

/**
 * Handles the admin setup for the plugin.
 *
 * @package SimpleAgeRestriction
 * @subpackage Admin
 */

// Don't access this directly, please
if ( ! defined( 'ABSPATH' ) ) exit;

// Call the admin setup on init.
add_action( 'init', 'sera_admin_setup' );

/**
 * Sets up the admin.
 *
 * @since 0.1
 */
function sera_admin_setup() {
	
	add_action( 'admin_init',                  'sera_admin_register_settings' );
	
	add_action( 'admin_menu',                  'sera_admin_menu' );
	
	add_filter( 'plugin_action_links',         'sera_admin_add_settings_link', 10, 2 );

	add_action( 'admin_enqueue_scripts',       'sera_admin_enqueue_scripts' );
	
	// Only mess with post-specific stuff if enabled
	if ( get_option( '_sera_require_for' ) == 'content' ) :
		
		add_action( 'post_submitbox_misc_actions', 'sera_add_submitbox_checkbox' );
		
		add_action( 'save_post',                   'sera_save_post' );
		
	endif;
}
	
/**
 * Add the settings sections and individual settings.
 *
 * @since 0.1
 */
function sera_admin_register_settings() {
	
	/**
	 * General Settings Section
	 */
	add_settings_section( 
		'sera_settings_general', 
		null, 
		'sera_settings_callback_section_general', 
		'simple-age-restriction' 
	);
 	
 	/**
 	 * Do you want to protect the entire site or just specific pages
 	 */
	add_settings_field( 
		'_sera_require_for', 
		__( 'Require verification for', 'sera' ), 
		'sera_settings_callback_require_for_field', 
		'simple-age-restriction', 
		'sera_settings_general' 
	);
 	
 	register_setting  ( 
 		'simple-age-restriction', 
 		'_sera_require_for', 
 		'esc_attr' 
 	);
 	
 	/**
 	 * Who gets verified?
 	 * Just those that are not logged in, or everyone?
 	 */
	add_settings_field( 
		'_sera_always_verify', 
		__( 'Verify the age of', 'sera' ), '
		sera_settings_callback_always_verify_field', 
		'simple-age-restriction', 
		'sera_settings_general' 
	);

 	register_setting  ( 
 		'simple-age-restriction', 
 		'_sera_always_verify', 
 		'esc_attr' 
 	);
 	
 	/**
 	 * What is the minimum age to allow
 	 */
	add_settings_field( 
		'_sera_minimum_age', 
		'<label for="_sera_minimum_age">' . __( 'Visitors must be', 'sera' ) . '</label>', 
		'sera_settings_callback_minimum_age_field', 
		'simple-age-restriction', 
		'sera_settings_general' 
	);
 	register_setting  ( 
 		'simple-age-restriction', 
 		'_sera_minimum_age', 
 		'intval' 
 	);
 	
 	/**
 	 * How long would you like to remember a user for?
 	 * Cookie lifespan
 	 */
 	add_settings_field( 
 		'_sera_cookie_duration', 
 		'<label for="_sera_cookie_duration">' . __( 'Remember visitors for', 'sera' ) . '</label>', 
 		'sera_settings_callback_cookie_duration_field', 
 		'simple-age-restriction', 
 		'sera_settings_general' 
 	);
 	
 	register_setting  ( 
 		'simple-age-restriction', 
 		'_sera_cookie_duration', 
 		'intval' 
 	);
 	
 	add_settings_field( 
 		'_sera_membership', 
 		__( 'Membership', 'sera' ), 
 		'sera_settings_callback_membership_field', 
 		'simple-age-restriction', 
 		'sera_settings_general' 
 	);

 	register_setting  ( 
 		'simple-age-restriction', 
 		'_sera_membership', 
 		'intval' 
 	);
 	
 	/**
 	 * Display Options Section
 	 */
 	add_settings_section( 
 		'sera_settings_display', 
 		__( 'Display Options', 'sera' ), 
 		'sera_settings_callback_section_display', 
 		'simple-age-restriction' 
 	);
 	
 	/**
 	 * Heading Text
 	 */
 	add_settings_field( 
 		'_sera_heading', 
 		'<label for="_sera_heading">' . __( 'Overlay Heading', 'sera' ) . '</label>', 
 		'sera_settings_callback_heading_field', 
 		'simple-age-restriction', 
 		'sera_settings_display' 
 	);
 	
 	register_setting  ( 
 		'simple-age-restriction', 
 		'_sera_heading', 
 		'esc_attr'
 	);
 	
 	/**
 	 * Description Text
 	 */
 	add_settings_field( 
 		'_sera_description', 
 		'<label for="_sera_description">' . __( 'Overlay Description', 'sera' ) . '</label>', 
 		'sera_settings_callback_description_field', 
 		'simple-age-restriction', 
 		'sera_settings_display' 
 	);

 	register_setting  ( 
 		'simple-age-restriction', 
 		'_sera_description', 
 		'esc_attr' 
 	);
 	
 	// Input Type
 	add_settings_field( 
 		'_sera_input_type', 
 		'<label for="_sera_input_type">' . __( 'Verify ages using', 'sera' ) . '</label>', 
 		'sera_settings_callback_input_type_field', 
 		'simple-age-restriction', 
 		'sera_settings_display' 
 	);
 	
 	register_setting  ( 
 		'simple-age-restriction', 
 		'_sera_input_type', 
 		'esc_attr' 
 	);
 	
 	/**
 	 * Enable the use of built-in CSS or not
 	 */
 	add_settings_field( 
 		'_sera_styling', 
 		__( 'Styling', 'sera' ), 
 		'sera_settings_callback_styling_field', 
 		'simple-age-restriction', 
 		'sera_settings_display' 
 	);

 	register_setting  ( 
 		'simple-age-restriction', 
 		'_sera_styling', 
 		'intval' 
 	);
 	
 	/**
 	 * Overlay color options
 	 */
 	add_settings_field( 
 		'_sera_overlay_color', 
 		__( 'Overlay Color', 'sera' ), 
 		'sera_settings_callback_overlay_color_field', 
 		'simple-age-restriction', 
 		'sera_settings_display' 
 	);
 	
 	register_setting  ( 
 		'simple-age-restriction', 
 		'_sera_overlay_color', 
 		'sera_validate_color' 
 	);
 	
 	/**
 	 * Background Color Options
 	 */
 	add_settings_field( 
 		'_sera_bgcolor', 
 		__( 'Background Color', 'sera' ), 
 		'sera_settings_callback_bgcolor_field', 
 		'simple-age-restriction', 
 		'sera_settings_display' 
 	);
 	
 	register_setting  ( 
 		'simple-age-restriction', 
 		'_sera_bgcolor', 
 		'sera_validate_color' 
 	);
	
	do_action( 'sera_register_settings' );
}

/**
 * Validates the color inputs from the settings.
 *
 * @since 0.1
 * @access public
 * @return string
 */
function sera_validate_color( $color ) {
	
	$color = preg_replace( '/[^0-9a-fA-F]/', '', $color );
	
	if ( strlen( $color ) == 6 || strlen( $color ) == 3 )
		$color = $color;
	else
		$color = '';
	
	return $color;
}

/**
 * Add to the settings menu.
 *
 * @since 0.1
 * @access public
 */
function sera_admin_menu() {

	add_options_page ( 
		__( 'Simple Age Restriction',  'sera' ), 
		__( 'Simple Age Restriction',  'sera' ), 
		'manage_options', 
		'simple-age-restriction', 
		'sera_settings_page'
	);

}

/**
 * Add a direct link to the Simple Age Restriction settings page from the plugins page.
 *
 * @since 0.1
 * @access public
 * @return string
 */
function sera_admin_add_settings_link( $links, $file ) {
	global $simple_age_restriction;
	
	if ( $simple_age_restriction->basename == $file ) :
		
		$settings_link = '<a href="' . add_query_arg( 'page', 'simple-age-restriction', admin_url( 'options-general.php' ) ) . '">' . __( 'Settings', 'sera' ) . '</a>';
		array_unshift( $links, $settings_link );
		
	endif;
	
	return $links;
}

/**
 * Enqueue the scripts.
 *
 * @since 0.1
 */
function sera_admin_enqueue_scripts( $page ) {
	global $simple_age_restriction;
	
	if ( 'settings_page_simple-age-restriction' != $page )
		return;
	
	wp_enqueue_style('wp-color-picker');
	
	wp_enqueue_script( 
		'sera-admin-scripts', 
		$simple_age_restriction->admin_url . '/assets/scripts.js', 
		array( 'jquery', 'wp-color-picker' ) 
	);
	
}

/**
 * Adds the meta box for posts and pages.
 *
 * @since 0.2
 */
function sera_add_submitbox_checkbox() {
	global $post; ?>
	
	<div class="misc-pub-section verify-age">
		
		<?php wp_nonce_field( 'sera_save_post', 'sera_nonce' ); ?>
		
		<input type="checkbox" name="_sera_needs_verify" id="_sera_needs_verify" value="1" <?php checked( 1, get_post_meta( $post->ID, '_sera_needs_verify', true ) ); ?> />
		<label for="_sera_needs_verify" class="selectit"><?php esc_html_e( 'Require age verification for this content', 'sera' ); ?></label>
		
	</div><!-- .misc-pub-section -->
<?php }

/**
 * Saves the post|page meta
 *
 * @since 0.2
 */
function sera_save_post( $post_id ) {
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
	
	$nonce = ( isset( $_POST['sera_nonce'] ) ) ? $_POST['sera_nonce'] : '';
	
	if ( ! wp_verify_nonce( $nonce, 'sera_save_post' ) )
		return;
		
	$needs_verify = ( isset( $_POST['_sera_needs_verify'] ) ) ? (int) $_POST['_sera_needs_verify'] : 0;
	
	update_post_meta( $post_id, '_sera_needs_verify', $needs_verify );
	
	return $post_id;
}