<?php
/**
 * Define the main plugin class
 * 
 * @since 0.0.1
 * 
 * @package Simple_Age_Restriction
 */

// Don't allow this file to be accessed directly.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * The main class.
 * 
 * @since 0.1.0
 */
final class Simple_Age_Restriction {
	
	/**
	 * The plugin version.
	 * 
	 * @since 0.0.1
	 */
	const VERSION = '0.0.1';
	
	/**
	 * The plugin slug.
	 * 
	 * @since 0.0.1
	 */
	const SLUG = 'simple_age_restriction';
	
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
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', self::SLUG ), self::VERSION );
	}

	/**
	 * Prevent unserializing of this class.
	 *
	 * @since 0.0.1
	 * 
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', self::SLUG ), self::VERSION );
	}
	
	/**
	 * Construct the class!
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function __construct() {
		
		/**
		 * Require the necessary files.
		 */
		$this->require_files();
		
		/**
		 * Add the necessary action hooks.
		 */
		$this->add_actions();
	}
	
	/**
	 * Require the necessary files.
	 * 
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	private function require_files() {
		
		/**
		 * The helper functions.
		 */
		require( plugin_dir_path( __FILE__ ) . 'functions.php' );
	}
	
	/**
	 * Add the necessary action hooks.
	 * 
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	private function add_actions() {
		
		// Load the text domain for i18n.
		add_action( 'init', array( $this, 'load_textdomain' ) );
		
		// If checked in the settings, load the default and custom styles.
		if ( get_option( '_sera_styling', 1 ) == 1 ) {
			
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			
			add_action( 'wp_head', array( $this, 'custom_styles' ) );
			
		}
		
		// Maybe display the overlay.
		add_action( 'wp_footer', array( $this, 'verify_overlay' ) );
		
		// Maybe hide the content of a restricted content type.
		add_action( 'the_content', array( $this, 'restrict_content' ) );
		
		// Verify the visitor's input.
		add_action( 'template_redirect', array( $this, 'verify' ) );

		// Load Scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		// If checked in the settings, add to the registration form.
		if ( sera_confirmation_required() ) {
			
			add_action( 'register_form', 'sera_register_form' );
			
			add_action( 'register_post', 'sera_register_check', 10, 3 );
			
		}
	}
	
	/**
	 * Load the text domain.
	 *
	 * Based on the bbPress implementation.
	 *
	 * @since 0.1.0
	 * 
	 * @return The textdomain or false on failure.
	 */
	public function load_textdomain() {
		
		$locale = get_locale();
		$locale = apply_filters( 'plugin_locale',  $locale, 'sera' );
		$mofile = sprintf( 'age_verify-%s.mo', $locale );

		$mofile_local  = plugin_dir_path( dirname( __FILE__ ) ) . 'languages/' . $mofile;
		$mofile_global = WP_LANG_DIR . '/simple-age-restriction/' . $mofile;

		if ( file_exists( $mofile_local ) )
			return load_textdomain( 'sera', $mofile_local );
			
		if ( file_exists( $mofile_global ) )
			return load_textdomain( 'sera', $mofile_global );
		
		load_plugin_textdomain( 'sera' );
		
		return false;
	}
	
	/**
	 * Enqueue the styles.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function enqueue_styles() {
		
		wp_enqueue_style( 'sera-styles', plugin_dir_url( __FILE__ ) . 'assets/styles.css' );
	}

	/**
	 * Enqueue the scripts.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'sera-autotab', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.autotab.js', array(
			'jquery'
		) );
		wp_enqueue_script( 'sera-scripts', plugin_dir_url( __FILE__ ) . 'assets/js/scripts.js', array(
			'jquery',
		) );
	}
	
	/**
	 * Print the custom colors, as defined in the admin.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function custom_styles() { ?>
		
		<style type="text/css">
			
			#sera-overlay-wrap, #sera-overlay-wrap #sera-overlay-inner { 
				/*background: #<?php echo esc_attr( sera_get_background_color() ); ?>;*/
			}
			
			#sera-overlay {
				/*background: #<?php echo esc_attr( sera_get_overlay_color() ); ?>;*/
			}
			
		</style>
		
		<?php
		/**
		* Trigger action after setting the custom color styles.
		*/
		do_action( 'sera_custom_styles' );
	}
	
	/**
	 * Print the actual overlay if the visitor needs verification.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function verify_overlay() {
		
		if ( ! sera_needs_verification() ) {
			return;
		}
		
		// Disable page caching by W3 Total Cache.
		define( 'DONOTCACHEPAGE', true ); ?>
		
		<div id="sera-overlay-wrap">
			
			<div id="sera-overlay-inner">


				<?php do_action( 'sera_before_modal' ); ?>
				
				<h1 class="title"><?php esc_html_e( sera_get_the_title() ); ?></h1>
				<h1 class="sub-title"><?php esc_html_e( sera_get_the_sub_title() ); ?></h1>
				
				<div id="sera-overlay">
					
					<h2><?php esc_html_e( sera_get_the_heading() ); ?></h2>
					
					<?php if ( sera_get_the_desc() )
						echo '<p>' . esc_html( sera_get_the_desc() ). '</p>'; ?>
					
					<?php do_action( 'sera_before_form' ); ?>
					
					<?php sera_verify_form(); ?>
						
					<?php do_action( 'sera_after_form' ); ?>
					
				</div>
				
				<?php do_action( 'sera_after_modal' ); ?>
				
		
    
			</div>
			<?php if (get_option( '_sera_ara_notice', 1 ) == 1) { ?>
			   <div id="araFooter">
			        <p>
			            <a href="http://www.ara.co.za/" target="_blank" rel="nofollow">
			                <img src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/ara-logo.png" alt="Please Drink Responsibly" class="hide-on-mobile" height="55" width="80">
							Not for sale to persons under the age of 18. Please Drink Responsibly.
			                <img src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/ara_noUnderAge.gif" alt="No Under 18's Allowed" class="hide-on-mobile">
			            </a>
			        </p>
			    </div>
			<?php } ?>
			<div class="bg-image"></div>
		</div>
	<?php }
	
	/**
	 * Hide the content if it is age restricted.
	 *
	 * @since 0.2.0
	 * 
	 * @param  string $content The object content.
	 * @return string $content The object content or an age-restricted message if needed.
	 */
	 public function restrict_content( $content ) {
		
		if ( ! sera_only_content_restricted() ) {
			return $content;
		}
		
		if ( is_singular() ) {
			return $content;
		}
		
		if ( ! sera_content_is_restricted() ) {
			return $content;
		}
		
		return sprintf( apply_filters( 'sera_restricted_content_message', __( 'You must be %1s years old to view this content.', 'sera' ) . ' <a href="%2s">' . __( 'Please verify your age', 'sera' ) . '</a>.' ),
			esc_html( sera_get_minimum_age() ),
			esc_url( get_permalink( get_the_ID() ) )
		);
	 }
	
	/**
	 * Verify the visitor if the form was submitted.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function verify() {
		
		if ( empty( $_POST ) || ! wp_verify_nonce( $_POST['sera-nonce'], 'verify-age' ) )
			return;
		
		$redirect_url = remove_query_arg( array( 'age-verified', 'verify-error' ), wp_get_referer() );
		
		$is_verified  = false;
		
		$error = 1; // Catch-all in case something goes wrong
		
		$input_type   = sera_get_input_type();
		
		switch ( $input_type ) {
			
			
			case 'checkbox' :
				
				if ( isset( $_POST['sera_verify_confirm'] ) && (int) $_POST['sera_verify_confirm'] == 1 )
					$is_verified = true;
				else
					$error = 2; // Didn't check the box
				
				break;

			case 'yearcheck' :
				
				if ( isset( $_POST['sera_verify_confirm'] ) && (int) $_POST['sera_verify_confirm'] == 1 && checkdate( 01, 01, (int) $_POST['sera_verify_y'] ) ) :
					$age = sera_get_visitor_age_year( $_POST['sera_verify_y'] );

					if ( $age >= sera_get_minimum_age() )
						$is_verified = true;
					else
						$error = 3; // Not old enough

				else :
					$error = 5; // Didn't check the box
				
				endif;
				break;

			case 'tjgcustom' :
				
				if ( checkdate( (int) $_POST['sera_verify_m_hidden'], (int) $_POST['sera_verify_d_hidden'], (int) $_POST['sera_verify_y_hidden'] ) ) :
					
					$age = sera_get_visitor_age( $_POST['sera_verify_y_hidden'], $_POST['sera_verify_m_hidden'], $_POST['sera_verify_d_hidden'] );
					
				    if ( $age >= sera_get_minimum_age() )
						$is_verified = true;
					else
						$error = 3; // Not old enough
						
				else :
					
					$error = 4; // Invalid date
					
				endif;
				
				break;

			
			default :
				
				if ( checkdate( (int) $_POST['sera_verify_m'], (int) $_POST['sera_verify_d'], (int) $_POST['sera_verify_y'] ) ) :
					
					$age = sera_get_visitor_age( $_POST['sera_verify_y'], $_POST['sera_verify_m'], $_POST['sera_verify_d'] );
					
				    if ( $age >= sera_get_minimum_age() )
						$is_verified = true;
					else
						$error = 3; // Not old enough
						
				else :
					
					$error = 4; // Invalid date
					
				endif;
				
				break;
		}
		
		$is_verified = apply_filters( 'sera_passed_verify', $is_verified );
		
		if ( $is_verified == true ) :
			
			do_action( 'sera_was_verified' );
			
			if ( isset( $_POST['sera_verify_remember'] ) )
				$cookie_duration = time() +  ( sera_get_cookie_duration() * 60 );
			else
				$cookie_duration = 0;
			
			setcookie( 'age-verified', 1, $cookie_duration, COOKIEPATH, COOKIE_DOMAIN, false );
			
			wp_redirect( esc_url_raw( $redirect_url ) . '?age-verified=' . wp_create_nonce( 'age-verified' ) );
			exit;
			
		else :
			
			do_action( 'sera_was_not_verified' );
			
			wp_redirect( esc_url_raw( add_query_arg( 'verify-error', $error, $redirect_url ) ) );
			exit;
			
		endif;
	}
}
