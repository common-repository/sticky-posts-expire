jQuery(document).ready(function($) {

	var previous = $('#mk-sep-expiration').val();

	$('#mk-sep-expiration').datepicker({
		dateFormat: 'yy-mm-dd'
	});

	$('#mk-sep-edit-expiration, .mk-sep-hide-expiration').click(function(e) {

		e.preventDefault();

		var date = $('#mk-sep-expiration').val();

		if( $(this).hasClass('cancel') ) {

			$('#mk-sep-expiration').val( previous );

		} else if( date ) {

			$('#mk-sep-expiration-label').text( $('#mk-sep-expiration').val() );

		}

		$('#mk-sep-expiration-field').slideToggle();

	});
});