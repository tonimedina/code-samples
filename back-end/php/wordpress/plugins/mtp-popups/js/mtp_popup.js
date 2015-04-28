jQuery(document).ready(function($){
	
	if( PopupOptCustom.cookie_expire == "0" ){
		$('.popup_overlayer').delay( parseInt(PopupOptCustom.fade_delay) ).fadeIn();
	}else{
		if($.cookie('popup_shown') != 'true')
			$('.popup_overlayer').delay( parseInt(PopupOptCustom.fade_delay) ).fadeIn();
	};

	$('.popup_overlayer_close').bind('click', function(){
		$('.popup_overlayer').fadeOut();
		if(PopupOptCustom.cookie_expire != "0"){
			$.cookie('popup_shown', true, { expires: parseInt(PopupOptCustom.cookie_expire), path: '/' });
		}
		return false;
	});
});