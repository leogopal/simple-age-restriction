<?php

// Don't access this directly, please
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define the settings page.
 *
 * @since 0.1
 */
function sera_settings_page() { ?>

	<div class="wrap">

		<?php screen_icon(); ?>

		<h2><?php esc_html_e( 'Simple Age Restriction Settings', 'sera' ) ?></h2>

		<form action="options.php" method="post">

			<?php settings_fields( 'simple-age-restriction' ); ?>

			<?php do_settings_sections( 'simple-age-restriction' ); ?>

			<?php submit_button(); ?>
			
		</form>
	</div>

<?php }


/**********************************************************/
/******************** General Settings ********************/
/**********************************************************/

/**
 * Prints the general settings section heading.
 *
 * @since 0.1
 */
function sera_settings_callback_section_general() {
	
	// Something should go here
}

/**
 * Prints the "require for" settings field.
 *
 * @since 0.2
 */
function sera_settings_callback_require_for_field() { ?>
	
	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Require verification for', 'sera' ); ?></span>
		</legend>
		<label>
			<input type="radio" name="_sera_require_for" value="site" <?php checked( 'site', get_option( '_sera_require_for', 'site' ) ); ?>/>
			 <?php esc_html_e( 'Entire site', 'sera' ); ?><br />
		</label>
		<br />
		<label>
			<input type="radio" name="_sera_require_for" value="content" <?php checked( 'content', get_option( '_sera_require_for', 'site' ) ); ?>/>
			 <?php esc_html_e( 'Specific content', 'sera' ); ?>
		</label>
	</fieldset>
	
<?php }

/**
 * Prints the "who to verify" settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_always_verify_field() { ?>
	
	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Verify the age of', 'sera' ); ?></span>
		</legend>
		<label>
			<input type="radio" name="_sera_always_verify" value="guests" <?php checked( 'guests', get_option( '_sera_always_verify', 'guests' ) ); ?>/>
			 <?php esc_html_e( 'Guests only', 'sera' ); ?> <span class="description"><?php esc_html_e( 'Logged-in users will not need to verify their age.', 'sera' ); ?></span><br />
		</label>
		<br />
		<label>
			<input type="radio" name="_sera_always_verify" value="all" <?php checked( 'all', get_option( '_sera_always_verify', 'guests' ) ); ?>/>
			 <?php esc_html_e( 'All visitors', 'sera' ); ?>
		</label>
	</fieldset>
	
<?php }

/**
 * Prints the minimum age settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_minimum_age_field() { ?>
	
	<input name="_sera_minimum_age" type="number" id="_sera_minimum_age" step="1" min="10" class="small-text" value="<?php echo esc_attr( get_option( '_sera_minimum_age', '21' ) ); ?>" /> <?php esc_html_e( 'years old or older to view this site', 'sera' ); ?>
	
<?php }

/**
 * Prints the cookie duration settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_cookie_duration_field() { ?>
	
	<input name="_sera_cookie_duration" type="number" id="_sera_cookie_duration" step="15" min="15" class="small-text" value="<?php echo esc_attr( get_option( '_sera_cookie_duration', '720' ) ); ?>" /> <?php esc_html_e( 'minutes (only applicable if the Remember me option is enabled, defaults to a single session)', 'sera' ); ?>
	
<?php }


/**********************************************************/
/******************** Display Settings ********************/
/**********************************************************/

/**
 * Prints the display settings section heading.
 *
 * @since 0.1
 */
function sera_settings_callback_section_display() {
	
	echo '<p>' . esc_html__( 'These settings change the look of your overlay. You can use <code>%s</code> to display the minimum age number from the setting above.', 'sera' ) . '</p>';
}

/**
 * Prints the modal title settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_title_field() { ?>
	
	<input name="_sera_title" type="text" id="_sera_title" value="<?php echo esc_attr( get_option( '_sera_title', __( 'Title', 'sera' ) ) ); ?>" class="regular-text" />
	
<?php }

/**
 * Prints the modal title settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_sub_title_field() { ?>
	
	<input name="_sera_sub_title" type="text" id="_sera_sub_title" value="<?php echo esc_attr( get_option( '_sera_sub_title', __( 'Title', 'sera' ) ) ); ?>" class="regular-text" />
	
<?php }

/**
 * Prints the modal heading settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_heading_field() { ?>
	
	<input name="_sera_heading" type="text" id="_sera_heading" value="<?php echo esc_attr( get_option( '_sera_heading', __( 'You must be %s years old to visit this site.', 'sera' ) ) ); ?>" class="regular-text" />
	
<?php }

/**
 * Prints the modal description settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_description_field() { ?>
	
	<input name="_sera_description" type="text" id="_sera_description" value="<?php echo esc_attr( get_option( '_sera_description', __( 'Please verify your age', 'sera' ) ) ); ?>" class="regular-text" />
	
<?php }

/**
 * Prints the modal button text settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_button_text_field() { ?>
	
	<input name="_sera_button_text" type="text" id="_sera_button_text" value="<?php echo esc_attr( get_option( '_sera_button_text', __( 'I am old enough!', 'sera' ) ) ); ?>" class="regular-text" />
	
<?php }

/**
 * Prints the input type settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_input_type_field() { ?>
	
	<select name="_sera_input_type" id="_sera_input_type">
		<option value="dropdowns" <?php selected( 'dropdowns', get_option( '_sera_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Date dropdowns', 'sera' ); ?></option>
		<option value="inputs" <?php selected( 'inputs', get_option( '_sera_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Inputs', 'sera' ); ?></option>
		<option value="checkbox" <?php selected( 'checkbox', get_option( '_sera_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Confirm checkbox', 'sera' ); ?></option>
		<option value="yearcheck" <?php selected( 'yearcheck', get_option( '_sera_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Year and confirm checkbox', 'sera' ); ?></option>
		<option value="tjgcustom" <?php selected( 'tjgcustom', get_option( '_sera_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'TJG Custom Agegate', 'sera' ); ?></option>
	</select>
	
<?php }

/**
 * Prints the Remember Me Display option settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_remember_display_field() { ?>
	
	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Enable Remember me', 'sera' ); ?></span>
		</legend>
		<label for="_sera_remember_display">
			<input name="_sera_remember_display" type="checkbox" id="_sera_remember_display" value="1" <?php checked( 1, get_option( '_sera_remember_display', 1 ) ); ?>/>
			 <?php esc_html_e( 'Enable the Remember Me option for longer cookies.', 'sera' ); ?>
		</label>
	</fieldset>
	
<?php }

/**
 * Prints the ARA Notice option settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_ara_notice_field() { ?>
	
	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Enable ARA Notice', 'sera' ); ?></span>
		</legend>
		<label for="_sera_ara_notice">
			<input name="_sera_ara_notice" type="checkbox" id="_sera_ara_notice" value="1" <?php checked( 1, get_option( '_sera_ara_notice', 1 ) ); ?>/>
			 <?php esc_html_e( 'This option activates the ARA sticky footer notice.', 'sera' ); ?>
		</label>
	</fieldset>
	
<?php }

/**
 * Prints the styling settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_styling_field() { ?>
	
	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Styling', 'sera' ); ?></span>
		</legend>
		<label for="_sera_styling">
			<input name="_sera_styling" type="checkbox" id="_sera_styling" value="1" <?php checked( 1, get_option( '_sera_styling', 1 ) ); ?>/>
			 <?php esc_html_e( 'Use built-in CSS on the front-end (recommended)', 'sera' ); ?>
		</label>
	</fieldset>
	
<?php }

/**
 * Prints the overlay color settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_overlay_color_field() { ?>
	
	<fieldset>
		
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Overlay Color', 'sera' ); ?></span>
		</legend>
		
		<?php $default_color = ' data-default-color="#fff"'; ?>
			
		<input type="text" name="_sera_overlay_color" id="_sera_overlay_color" value="#<?php echo esc_attr( sera_get_overlay_color() ); ?>"<?php echo $default_color ?> />
		
	</fieldset>
	
<?php }

/**
 * Prints the background color settings field.
 *
 * @since 0.1
 */
function sera_settings_callback_bgcolor_field() { ?>
	
	<fieldset>
		
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Background Color' ); ?></span>
		</legend>
		
		<?php $default_color = '';
		
		if ( current_theme_supports( 'custom-background', 'default-color' ) )
			$default_color = ' data-default-color="#' . esc_attr( get_theme_support( 'custom-background', 'default-color' ) ) . '"'; ?>
			
		<input type="text" name="_sera_bgcolor" id="_sera_bgcolor" value="#<?php echo esc_attr( sera_get_background_color() ); ?>"<?php echo $default_color ?> />
		
	</fieldset>
	
<?php }
