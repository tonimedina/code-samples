jQuery(document).ready(function($)
{
	//console.info($.cookie('newsletter_shown'));

	if(PopupOpt.cookie_expire == "0" || PopupOpt.external_call == "1" )
	{
		$('.newsletter_overlayer').fadeIn(1000);

		$('.newsletter_close, .clickable_background').bind('click', function()
		{
			$('.newsletter_overlayer').fadeOut();
			return false;
		});
	}
	else
	{
		if($.cookie('newsletter_shown') == null)
		{
			$('.newsletter_overlayer').fadeIn(1000);
		};

		if($('.thankyou').is(':visible')) 
		{
			$.cookie('newsletter_shown', 'suscribed', { expires: 730, path: '/' });
		}

		$('.newsletter_close, .clickable_background').bind('click', function()
		{
			$('.newsletter_overlayer').fadeOut();
			if($.cookie('newsletter_shown') != 'suscribed')
			{
				$.cookie('newsletter_shown', true, { expires: 1, path: '/' });
			}
			return false;
		});
	}

	$('form.newsletter_form').submit(function(){
		var email = $(this).find(".newsletter_email").val();
		if(IsEmail(email))
		{
			return true;
		}
		else
		{
			var msg = PopupOpt.error_txt;
			$(this).parent().find(".newsletter_message, .widget_error").html('<span class="error">' + msg + '</span>').effect( "shake" );
			return false;
		}
	});
});