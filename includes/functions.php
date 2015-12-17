<?php
/**
 * Define the supporting functions.
 *
 * @since 0.0.1
 *
 * @package Simple_Age_Restriction\Functions
 */

// Don't allow this file to be accessed directly.
if ( ! defined( 'WPINC' ) ) { die(); }

/**
 * Prints the minimum age.
 *
 * @since 0.1.0
 * @see   sera_get_minimum_age();
 * 
 * @return void
 */
function sera_minimum_age() {
	
	echo sera_get_minimum_age();

}

/**
 * Get the minimum age to view restricted content.
 *
 * @since 0.1.0
 * 
 * @return int $minimum_age The minimum age to view restricted content.
 */
function sera_get_minimum_age() {
	
	$minimum_age = get_option( '_sera_minimum_age', 21 );
	
	/**
	 * Filter the minimum age.
	 * 
	 * @since 0.1.0
	 * 
	 * @param int $minimum_age The minimum age to view restricted content.
	 */
	$minimum_age = apply_filters( 'sera_minimum_age', $minimum_age );
	
	return (int) $minimum_age;
}

/**
 * Get the visitor's age based on input.
 *
 * @since 0.1.5
 *
 * @param string $year  The visitor's birth year.
 * @param string $month The visitor's birth month.
 * @param $day   $day   The visitor's birth day.
 * @return int $age The calculated age.
 */
function sera_get_visitor_age( $year, $month, $day ) {
	
	$age = 0;
	
	$birthday = new DateTime( $year . '-' . $month . '-' . $day );
	
	$phpversion = phpversion();
	
	if ( $phpversion >= '5.3' ) :
		
		$current  = new DateTime( current_time( 'mysql' ) );
		$age      = $birthday->diff( $current );
		$age      = $age->format( '%y' );
		
	else :
		
		list( $year, $month, $day ) = explode( '-', $birthday->format( 'Y-m-d' ) );
		
	    $year_diff  = date_i18n( 'Y' ) - $year;
	    $month_diff = date_i18n( 'm' ) - $month;
	    $day_diff   = date_i18n( 'd' ) - $day;
	    
	    if ( $month_diff < 0 )
	    	$year_diff--;
	    elseif ( ( $month_diff == 0 ) && ( $day_diff < 0 ) )
	    	$year_diff--;
	    
	    $age = $year_diff;
	    
    endif;
	
	return (int) $age;
}

/**
 * Get the visitor's age based on year input.
 *
 * @since 0.1.5
 *
 * @param string $year  The visitor's birth year.
 * @return int $age The calculated age.
 */
function sera_get_visitor_age_year( $year ) {
	
	$age = 0;
	
	$birthday = new DateTime( $year . '-' . '01' . '-' . '01' );
	
	$phpversion = phpversion();
	
	if ( $phpversion >= '5.3' ) :
		
		$current  = new DateTime( current_time( 'mysql' ) );
		$age      = $birthday->diff( $current );
		$age      = $age->format( '%y' );
		
	else :
		
		list( $year, $month, $day ) = explode( '-', $birthday->format( 'Y-m-d' ) );
		
	    $year_diff  = date_i18n( 'Y' ) - $year;
	    $month_diff = date_i18n( 'm' ) - $month;
	    $day_diff   = date_i18n( 'd' ) - $day;
	    
	    if ( $month_diff < 0 )
	    	$year_diff--;
	    elseif ( ( $month_diff == 0 ) && ( $day_diff < 0 ) )
	    	$year_diff--;
	    
	    $age = $year_diff;
	    
    endif;
	
	return (int) $age;
}

/**
 * Get the cookie duration.
 * 
 * This lets us know how long to keep a visitor's
 * verified cookie.
 *
 * @since 0.1.0
 * 
 * @return int $cookie_duration The cookie duration.
 */
function sera_get_cookie_duration() {
	
	$cookie_duration = 720;
	
	/**
	 * Filter the cookie duration.
	 * 
	 * @since 0.1.0
	 * 
	 * @param int $cookie_duration The cookie duration.
	 */
	$cookie_duration = apply_filters( 'sera_cookie_duration', $cookie_duration );
	
	return (int) $cookie_duration;
}

/**
 * Determines whether only certain content should be restricted.
 *
 * @since 0.2.0
 * 
 * @return bool $only_content_restricted Whether the restriction is content-specific or site-wide.
 */
function sera_only_content_restricted() {
	
	$only_content_restricted = ( 'content' == get_option( '_sera_require_for' ) ) ? true : false;
	
	/**
	 * Filter whether the restriction is content-specific or site-wide.
	 * 
	 * @since 0.2.0
	 * 
	 * @param bool $only_content_restricted
	 */
	$only_content_restricted = apply_filters( 'sera_only_content_restricted', $only_content_restricted );
	
	return (bool) $only_content_restricted;
}

/**
 * Determines if a certain piece of content is restricted.
 *
 * @since 0.2.0
 *
 * @return bool $is_restricted Whether a certain piece of content is restricted.
 */
function sera_content_is_restricted( $id = null ) {
	
	if ( is_null( $id ) ) {
		$id = get_the_ID();
	}
	
	$is_restricted = ( 1 == get_post_meta( $id, '_sera_needs_verify', true ) ) ? true : false;
	
	/**
	 * Filter whether this content should be restricted.
	 * 
	 * @since 0.0.1
	 * 
	 * @param bool $is_restricted Whether this content should be restricted.
	 * @param int  $id            The content's ID.
	 */
	$is_restricted = apply_filters( 'sera_is_restricted', $is_restricted, $id );
	
	return $is_restricted;
}

/**
 * This is the very important function that determines if a given visitor
 * needs to be verified before viewing the site. You can filter this if you like.
 *
 * @since 0.1
 * @return bool
 */
function sera_needs_verification() {
	
	// Assume the visitor needs to be verified
	$return = true;
	
	// If the site is restricted on a per-content basis, let 'em through
	if ( sera_only_content_restricted() ) :
		
		$return = false;
		
		// If the content being viewed is restricted, throw up the form
		if ( is_singular() && sera_content_is_restricted() )
			$return = true;
		
	endif;
	
	// Check that the form was at least submitted. This lets visitors through that have cookies disabled.
	$nonce = ( isset( $_REQUEST['age-verified'] ) ) ? $_REQUEST['age-verified'] : '';
	
	if ( wp_verify_nonce( $nonce, 'age-verified' ) )
		$return = false;
	
	// If logged in users are exempt, and the visitor is logged in, let 'em through
	if ( get_option( '_sera_always_verify', 'guests' ) == 'guests' && is_user_logged_in() )
		$return = false;
	
	// Or, if there is a valid cookie let 'em through
	if ( isset( $_COOKIE['age-verified'] ) )
		$return = false;
	
	return (bool) apply_filters( 'sera_needs_verification', $return );
}


/***********************************************************/
/******************** Display Functions ********************/
/***********************************************************/

/**
 * Echoes the overlay heading
 *
 * @since 0.1
 * @echo string
 */
function sera_the_heading() {

	echo sera_get_the_heading();

}

/**
 * Returns the overlay heading. You can filter this if you like.
 *
 * @since 0.1
 * @return string
 */
function sera_get_the_heading() {
	
	return sprintf( apply_filters( 'sera_heading', get_option( '_sera_heading', __( 'You must be %s years old to visit this site.', 'sera' ) ) ), sera_get_minimum_age() );
}

/**
 * Echoes the overlay title
 *
 * @since 0.1
 * @echo string
 */
function sera_the_title() {

	echo sera_get_the_title();

}

/**
 * Returns the overlay title. You can filter this if you like.
 *
 * @since 0.1
 * @return string
 */
function sera_get_the_title() {
	
	return sprintf( apply_filters( 'sera_title', get_option( '_sera_title', __( 'Title', 'sera' ) ) ) );
}

/**
 * Echoes the overlay title
 *
 * @since 0.1
 * @echo string
 */
function sera_the_sub_title() {

	echo sera_get_the_sub_title();

}

/**
 * Returns the overlay title. You can filter this if you like.
 *
 * @since 0.1
 * @return string
 */
function sera_get_the_sub_title() {
	
	return sprintf( apply_filters( 'sera_sub_title', get_option( '_sera_sub_title', __( 'SubTitle', 'sera' ) ) ) );
}

/**
 * Echoes the overlay description, which lives below the heading and above the form.
 *
 * @since 0.1
 * @echo string
 */
function sera_the_desc() {
	
	echo sera_get_the_desc();
}

/**
 * Returns the overlay description, which lives below the heading and above the form.
 * You can filter this if you like.
 *
 * @since 0.1
 * @return string|false
 */
function sera_get_the_desc() {
	
	$desc = apply_filters( 'sera_description', get_option( '_sera_description', __( 'Please verify your age', 'sera' ) ) );
	
	if ( ! empty( $desc ) )
		return $desc;
	else
		return false;
}

/**
 * Returns the form's input type, based on the settings.
 * You can filter this if you like.
 *
 * @since 0.1
 * @return string
 */
function sera_get_input_type() {
	
	return apply_filters( 'sera_input_type', get_option( '_sera_input_type', 'dropdowns' ) );
}

/**
 * Returns the overlay box's background color
 * You can filter this if you like.
 *
 * @since 0.1
 * @return string
 */
function sera_get_overlay_color() {
	
	if ( get_option( '_sera_overlay_color' ) )
		$color = get_option( '_sera_overlay_color' );
	else
		$color = 'fff';
	
	return apply_filters( 'sera_overlay_color', $color );
}

/**
 * Returns the overlay's background color
 * You can filter this if you like.
 *
 * @since 0.1
 * @return string
 */
function sera_get_background_color() {
	
	if ( current_theme_supports( 'custom-background' ) )
		$default = get_background_color();
	else
		$default = 'e6e6e6';
	
	if ( get_option( '_sera_bgcolor' ) )
		$color = get_option( '_sera_bgcolor' );
	else
		$color = $default;
	
	return apply_filters( 'sera_background_color', $color );
}

/**
 * Echoes the actual form
 *
 * @since 0.1
 * @echo string
 */
function sera_verify_form() {
	
	echo sera_get_verify_form();
}

/**
 * Returns the all-important verification form.
 * You can filter this if you like.
 *
 * @since 0.1
 * @return string
 */
function sera_get_verify_form() {
	
	$input_type = sera_get_input_type();
	
	$submit_button_label = apply_filters( 'sera_form_submit_label', get_option( '_sera_button_text', __( 'I am old enough!', 'sera' ) ) );
	
	$form = '';
	
	$form .= '<form id="sera_verify_form" action="' . esc_url( home_url( '/' ) ) . '" method="post">';
	
	
	/* Parse the errors, if any */
	$error = ( isset( $_GET['verify-error'] ) ) ? $_GET['verify-error'] : false;
	
	if ( $error ) :
		
		// Catch-all error
		$error_string = apply_filters( 'sera_error_text_general', __( 'Sorry, something must have gone wrong. Please try again', 'sera' ) );
		
		// Visitor didn't check the box (only for the simple checkbox form)
		if ( $error == 2 )
			$error_string = apply_filters( 'sera_error_text_not_checked', __( 'Check the box to confirm your age before continuing', 'sera' ) );
		
		// Visitor isn't old enough
		if ( $error == 3 )
			$error_string = apply_filters( 'sera_error_text_too_young', __( 'Sorry, it doesn\'t look like you\'re old enough', 'sera' ) );
		
		// Visitor entered an invalid date
		if ( $error == 4 )
			$error_string = apply_filters( 'sera_error_text_bad_date', __( 'Please enter a valid date', 'sera' ) );

		// Visitor entered an invalid date and/or didnt check the box
		if ( $error == 5 )
			$error_string = apply_filters( 'sera_error_text_bad_date', __( 'Please check that the year is valid and you checked the box', 'sera' ) );
		
		$form .= '<p class="error">' . esc_html( $error_string ) . '</p>';
		
	endif;
	
	do_action( 'sera_form_before_inputs' );
	
	// Add a sweet nonce. So sweet.
	$form .= wp_nonce_field( 'verify-age', 'sera-nonce' );
	
	switch ( $input_type ) {
		
		// If set to date dropdowns
		case 'dropdowns' :
			
			$form .= '<p><select name="sera_verify_m" id="sera_verify_m">';
				
				foreach ( range( 1, 12 ) as $month ) :
					
					$month_name = date( 'F', mktime( 0, 0, 0, $month, 1 ) );
					
					$form .= '<option value="' . $month . '">' . $month_name . '</option>';
					
				endforeach;
				
			$form .= '</select> - <select name="sera_verify_d" id="sera_verify_d">';
				
				foreach ( range( 1, 31 ) as $day ) :
					
					$form .= '<option value="' . $day . '">' . esc_html( zeroise( $day, 2 ) ) . '</option>';
					
				endforeach;
				
			$form .= '</select> - <select name="sera_verify_y" id="sera_verify_y">';
				
				foreach ( range( 1910, date( 'Y' ) ) as $year ) :
					
					$selected = ( $year == date( 'Y' ) ) ? 'selected="selected"' : '';
					
					$form .= '<option value="' . $year . '" ' . $selected . '>' . $year . '</option>';
					
				endforeach;
				
			$form .= '</select></p>';
			
			break;
		
		// If set to date inputs
		case 'inputs' :
			
			$form .= '<div class="date-inputs"><p>
			<input type="text" name="sera_verify_d" id="sera_verify_d" maxlength="2" value="" placeholder="DD" /> / 
			<input type="text" name="sera_verify_m" id="sera_verify_m" maxlength="2" value="" placeholder="MM" /> / 
			<input type="text" name="sera_verify_y" id="sera_verify_y" maxlength="4" value="" placeholder="YYYY" />
			</p></div>';
			
			break;
			
		// If just a simple checkbox
		case 'checkbox' :
			
			$form .= '<p><label for="sera_verify_confirm"><input type="checkbox" name="sera_verify_confirm" id="sera_verify_confirm" value="1" /> ';
			
			$form .= esc_html( sprintf( apply_filters( 'sera_confirm_text', __( 'I am at least %s years old', 'sera' ) ), sera_get_minimum_age() ) ) . '</label></p>';
			
			break;

				// If set to date inputs
		case 'yearcheck' :
			
			$form .= '<div class="date-inputs yearcheck"><p>
			<input type="text" name="sera_verify_y" id="sera_verify_y" maxlength="4" value="" placeholder="YYYY" />
			</p></div>';

			$form .= '<p><label for="sera_verify_confirm"><input type="checkbox" name="sera_verify_confirm" id="sera_verify_confirm" value="1" /> ';
			
			$form .= esc_html( sprintf( apply_filters( 'sera_confirm_text', __( 'I\'m over %s', 'sera' ) ), sera_get_minimum_age() ) ) . '</label></p>';
			
			break;
			
	};
	
	do_action( 'sera_form_after_inputs' );
	
	/**
	 * Remember me section
	 */
	
	if (get_option( '_sera_remember_display', 1 ) == 1) {
		$form .= '<p class="submit"><label for="sera_verify_remember"><input type="checkbox" name="sera_verify_remember" id="sera_verify_remember" value="1" /> ' . esc_html__( 'Remember me', 'sera' ) . '</label> ';
	}
	$form .= '<input type="submit" name="sera_verify" id="sera_verify" value="' . esc_attr( $submit_button_label ) . '" /></p>';

	$form .= '</form>';
	
	return apply_filters( 'sera_verify_form', $form );
}


/***********************************************************/
/*************** User Registration Functions ***************/
/***********************************************************/

/**
 * Determines whether or not users need to verify their age before
 * registering for the site. You can filter this if you like.
 *
 * @since 0.1
 * @return bool
 */
function sera_confirmation_required() {
	
	if ( get_option( '_sera_membership', 1 ) == 1 )
		$return = true;
	else
		$return = false;
		
	return (bool) apply_filters( 'sera_confirmation_required', $return );
}
