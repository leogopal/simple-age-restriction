jQuery(document).ready(function($) {

	console.log(sera.needs_verification);

	$(document).ready(function() {
		$('#sera_verify_d').autotab({ target: '#sera_verify_m' });
		$('#sera_verify_m').autotab({ target: '#sera_verify_y', previous: '#sera_verify_d' });
		$('#sera_verify_y').autotab({ previous: '#sera_verify_m' });
	});


	if (sera.needs_verification == 1) {
		jQuery(".container").addClass('block-access');

		jQuery('#agegate-form').mobiscroll().date({
			theme: 'default',
			display: 'inline',
			startYear: 1900,
			endYear: 2020,
			dateOrder: "ddMyy",
			defaultValue: new Date("1989/04/02"),
			rows: 5,
			mode: "mixed",
			height: "30",
		});

		jQuery("#agegate-wrap").fadeIn();

		console.log('hello!');

		jQuery("#sera_verify").on("click", function(e) {
		e.preventDefault();
			var instance = jQuery('#agegate-form').mobiscroll('getInst');
			var values = instance.getValue();

			var day   = values[0]; //day
			var month = parseInt(values[1])+1; //caters for months (starting at Jan as index 0)
			var year  = values[2]; //year
			// Days Recorded
			console.log(day);
			$("#sera_verify_d_hidden").val(day);

			// Days Recorded
			console.log(month);
			$("#sera_verify_m_hidden").val(month);

			// Days Recorded
			console.log(year);
			$("#sera_verify_y_hidden").val(year);

			$("#sera_verify_form").submit();
		});

	} else {
		// alert('This is verified, I can do shit here.');
		if (cookie.enabled()) {
		   cookie.set( 'age-verified', 'verified', {
			   expires: 7,
			   secure: false
			});
		} else {

		}
}

});



// ---- START AGE GATE FUNCTIONS ---- //
function init_age_gate() {

	// jQuery(".container").addClass('block-access');

	// jQuery('#agegate-form').mobiscroll().date({
	// 	theme: 'default',
	// 	display: 'inline',
	// 	startYear: 1900,
	// 	endYear: 2020,
	// 	dateOrder: "ddMyy",
	// 	defaultValue: new Date("1989/04/02"),
	// 	rows: 5,
	// 	mode: "mixed",
	// 	height: "30",
	// });

	// // Enter Site Fade
	// jQuery("#agegate-enter").on("click", function(e) {
	// 	e.preventDefault();

	// 	var id = jQuery(this).attr('id');
	// 	var result = false;

	// 	if(is_user_legal()) {
	// 		result = true;
	// 	}

	// 	if(result) {

	// 		// set cookie (cookieMonster object in head)
	// 		cookieMonster.createCookie('age_gate', true, 60);
	// 		// redirect
	// 		var next = getParameterByName('next');

	// 		if(next) {
	// 			location.href = next;
	// 		} else {
	// 			// location.href = nona.homeUrl;
	// 			jQuery('html, body').scrollTop(1);
	// 			jQuery("#agegate-wrap").fadeOut();
	// 			jQuery(".container").removeClass('block-access');
	// 		}

	// 	} else {
	// 		location.href = "http://www.youdecide.org.za/";
	// 	}
	// });

	// jQuery("#agegate-wrap").fadeIn();

}


function is_user_legal() {

	var instance = jQuery('#agegate-form').mobiscroll('getInst');
	var values = instance.getValue();

	var day   = values[0]; //day
	var month = parseInt(values[1])+1; //caters for months (starting at Jan as index 0)
	var year  = values[2]; //year

	var today = new Date(); //existing JS function
	var birthDate = new Date(year + "/" + month + "/" + day);
	var age = today.getFullYear() - birthDate.getFullYear();
	var m = today.getMonth() - birthDate.getMonth();

	if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
		age--;
	}

	return (age >= 18); //returns boolean
}

// parse url and grab parameters
function getParameterByName(name) {
	name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
	var regexS = "[\\?&]" + name + "=([^&#]*)";
	var regex = new RegExp(regexS);
	var results = regex.exec(window.location.search);
	if (results === null) {
		return '';
	} else {
		return decodeURIComponent(results[1].replace(/\+/g, ' '));
	}

}
// ---- END AGE GATE FUNCTIONS ---- //
