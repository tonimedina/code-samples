jQuery(document).ready(function($)
{
	$('.newsletter_overlayer').fadeIn(1000);

	$('.newsletter_close, .clickable_background').bind('click', function()
	{
		$('.newsletter_overlayer').fadeOut();
		return false;
	});

	$('form.newsletter_form').submit(function(){
		var email = $(this).find(".newsletter_email").val();
		if(IsEmail(email))
		{
			return true;
			
		} else {
			var msg = PopupOpt.error_txt;
			$(this).parent().find(".widget_error").html('<span class="error">' + msg + '</span>').effect( "shake" );
			return false;
		}
	});
});