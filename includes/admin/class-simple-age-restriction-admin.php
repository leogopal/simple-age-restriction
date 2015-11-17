<?php
/**
 * Define the admin class
 * 
 * @since 0.0.1
 * 
 * @package Simple_Age_Restriction\Admin
 */

// Don't allow this file to be accessed directly.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * The admin class.
 * 
 * @since 0.0.1
 */
final class Simple_Age_Restriction_Admin {
	
	/**
	 * The only instance of this class.
	 * 
	 * @since 0.0.1
	 * @access protected
	 */
	protected static $instance = null;
	
	/**
	 * Get the only instance of this class.
	 * 
	 * @since 0.0.1
	 * 
	 * @return object $instance The only instance of this class.
	 */
	public static function get_instance() {
		
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}

	/**
	 * Prevent cloning of this class.
	 *
	 * @since 0.0.1
	 * 
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', Simple_Age_Restriction::SLUG ), Simple_Age_Restriction::VERSION );
	}

	/**
	 * Prevent unserializing of this class.
	 *
	 * @since 0.0.1
	 * 
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', Simple_Age_Restriction::SLUG ), Simple_Age_Restriction::VERSION );
	}
	
	/**
	 * Construct the class!
	 *
	 * @since 0.0.1
	 * 
	 * @return void
	 */
	public function __construct() {
		
		/**
		 * The settings callbacks.
		 */
		require( plugin_dir_path( __FILE__ ) . 'settings.php' );
		
		// Add the settings page.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		
		// Add and register the settings sections and fields.
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		
		// Add the "Settings" link to the plugin row.
		add_filter( 'plugin_action_links_simple-age-restriction/simple-age-restriction.php', array( $this, 'add_settings_link' ), 10 );
		
		// Enqueue the script.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		// Only load with post-specific stuff if enabled.
		if ( 'content' == get_option( '_sera_require_for' ) ) {
			
			// Add a "restrict" checkbox to individual posts/pages.
			add_action( 'post_submitbox_misc_actions', array( $this, 'add_submitbox_checkbox' ) );
			
			// Save the "restrict" checkbox value.
			add_action( 'save_post', array( $this, 'save_post' ) );
			
		}
	}
	
	/**
	 * Add to the settings page.
	 *
	 * @since 0.0.1
	 * 
	 * @return void
	 */
	public function add_settings_page() {
	
		add_menu_page (
			__( 'Simple Age Restriction', Simple_Age_Restriction::SLUG ),
			__( 'Age Restriction', Simple_Age_Restriction::SLUG ),
			'manage_options',
			'simple-age-restriction',
			'sera_settings_page',
			'dashicons-lock'
		);
	}
	
	/**
	 * Add and register the settings sections and fields.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function register_settings() {
		
		/* General Section */
		add_settings_section( 
			'sera_settings_general', 
			null, 
			'sera_settings_callback_section_general', 
			'simple-age-restriction' 
		);
	 	
	 	// What to protect (entire site or specific content)
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
	 	
	 	// Who to verify (logged in or all)
		add_settings_field( 
			'_sera_always_verify', 
			__( 'Verify the age of', 'sera' ), 
			'sera_settings_callback_always_verify_field', 
			'simple-age-restriction', 
			'sera_settings_general' 
		);
	 	
	 	register_setting  ( 
	 		'simple-age-restriction', 
	 		'_sera_always_verify', 
	 		'esc_attr' 
	 	);
	 	
	 	// Minimum Age
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
	 	
	 	// Memory Length
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
	 	
	 	/* Display Section */
	 	add_settings_section( 
	 		'sera_settings_display', 
	 		__( 'Display Options', 'sera' ), 
	 		'sera_settings_callback_section_display', 
	 		'simple-age-restriction' 
	 	);
	 	
	 	// Title
	 	add_settings_field( 
	 		'_sera_title', 
	 		'<label for="_sera_title">' . __( 'Overlay Title', 'sera' ) . '</label>', 
	 		'sera_settings_callback_title_field', 
	 		'simple-age-restriction', 
	 		'sera_settings_display' 
	 	);
	 	
	 	register_setting  ( 
	 		'simple-age-restriction', 
	 		'_sera_title', 
	 		'esc_attr' 
	 	);

	 	// Sub Title
	 	add_settings_field( 
	 		'_sera_sub_title', 
	 		'<label for="_sera_sub_title">' . __( 'Overlay Sub-Title', 'sera' ) . '</label>', 
	 		'sera_settings_callback_sub_title_field', 
	 		'simple-age-restriction', 
	 		'sera_settings_display' 
	 	);
	 	
	 	register_setting  ( 
	 		'simple-age-restriction', 
	 		'_sera_sub_title', 
	 		'esc_attr' 
	 	);

	 	// Heading
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
	 	
	 	// Description
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

	 	// Button Text
	 	add_settings_field( 
	 		'_sera_button_text', 
	 		'<label for="_sera_button_text">' . __( 'Button Text', 'sera' ) . '</label>', 
	 		'sera_settings_callback_button_text_field', 
	 		'simple-age-restriction', 
	 		'sera_settings_display'
	 	);
	 	
	 	register_setting  ( 
	 		'simple-age-restriction', 
	 		'_sera_button_text', 
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

	 	// Enable Remember Me Options
	 	add_settings_field( 
	 		'_sera_remember_display', 
	 		__( 'Enable Remember me', 'sera' ), 
	 		'sera_settings_callback_remember_display_field', 
	 		'simple-age-restriction', 
	 		'sera_settings_display' 
	 	);
	 	
	 	register_setting  ( 
	 		'simple-age-restriction', 
	 		'_sera_remember_display', 
	 		'intval' 
	 	);

	 	// Enable ARA Notice
	 	add_settings_field( 
	 		'_sera_ara_notice', 
	 		__( 'Enable ARA Notice', 'sera' ), 
	 		'sera_settings_callback_ara_notice_field', 
	 		'simple-age-restriction', 
	 		'sera_settings_display' 
	 	);
	 	
	 	register_setting  ( 
	 		'simple-age-restriction', 
	 		'_sera_ara_notice', 
	 		'intval' 
	 	);
	 	
	 	// Enable CSS
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
	 	
	 	// Overlay Color
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
	 		array( $this, 'validate_color' ) 
	 	);
	 	
	 	// Background Color
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
	 		array( $this, 'validate_color' ) 
	 	);
		
		do_action( 'sera_register_settings' );
	}
	
	/**
	 * Add a direct link to the Simple Age Restriction settings page from the plugins page.
	 *
	 * @since 0.0.1
	 * 
	 * @param array  $actions The links beneath the plugin's name.
	 * @param string $file    The plugin filename.
	 * @return string
	 */
	public function add_settings_link( $actions ) {
		
		$settings_link = '<a href="' . esc_url( add_query_arg( 'page', 'simple-age-restriction', admin_url( 'options-general.php' ) ) ) . '">';
			$settings_link .= __( 'Settings', Simple_Age_Restriction::SLUG );
		$settings_link .='</a>';
		
		array_unshift( $actions, $settings_link );
		
		return $actions;
	}
	
	/**
	 * Validates the color inputs from the settings.
	 *
	 * @since 0.0.1
	 * 
	 * @param  string $color A color hex.
	 * @return string $color The validated color hex.
	 */
	public function validate_color( $color ) {
		
		$color = preg_replace( '/[^0-9a-fA-F]/', '', $color );
		
		if ( strlen( $color ) == 6 || strlen( $color ) == 3 ) {
			$color = $color;
		} else {
			$color = '';
		}
		
		return $color;
	}
	
	/**
	 * Enqueue the scripts.
	 *
	 * @since 0.0.1
	 * 
	 * @param string $page The current admin page.
	 * @return void
	 */
	public function enqueue_scripts( $page ) {
		
		if ( 'settings_page_simple-age-restriction' != $page ) {
			return;
		}
		
		wp_enqueue_style( 'wp-color-picker' );
		
		wp_enqueue_script( 'sera-admin-scripts', plugin_dir_url( __FILE__ ) . 'assets/scripts.js', array(
			'jquery',
			'wp-color-picker'
		) );
	}
	
	/**
	 * Add a "restrict" checkbox to individual posts/pages.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function add_submitbox_checkbox() { ?>
		
		<div class="misc-pub-section verify-age">
			
			<?php wp_nonce_field( 'sera_save_post', 'sera_nonce' ); ?>
			
			<input type="checkbox" name="_sera_needs_verify" id="_sera_needs_verify" value="1" <?php checked( 1, get_post_meta( get_the_ID(), '_sera_needs_verify', true ) ); ?> />
			<label for="_sera_needs_verify" class="selectit">
				<?php esc_html_e( 'Require age verification for this content', Simple_Age_Restriction::SLUG ); ?>
			</label>
			
		</div><!-- .misc-pub-section -->
		
	<?php }
	
	/**
	 * Save the "restrict" checkbox value.
	 *
	 * @since 0.0.1
	 * 
	 * @param int $post_id The current post ID.
	 * @return void
	 */
	public function save_post( $post_id ) {
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		$nonce = ( isset( $_POST['sera_nonce'] ) ) ? $_POST['sera_nonce'] : '';
		
		if ( ! wp_verify_nonce( $nonce, 'sera_save_post' ) ) {
			return;
		}
		
		$needs_verify = ( isset( $_POST['_sera_needs_verify'] ) ) ? (int) $_POST['_sera_needs_verify'] : 0;
		
		update_post_meta( $post_id, '_sera_needs_verify', $needs_verify );
	}
}
