(function($) {

	$(document).ready(function() {
		$('#sera_verify_d').autotab({ target: '#sera_verify_m' });
		$('#sera_verify_m').autotab({ target: '#sera_verify_y', previous: '#sera_verify_d' });
		$('#sera_verify_y').autotab({ previous: '#sera_verify_m' });
	});

})(jQuery);