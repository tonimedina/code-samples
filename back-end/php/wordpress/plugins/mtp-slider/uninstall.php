<?php

if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

delete_option( 'mtp_slider_main_settings' );
delete_option( 'mtp_slider_usability_features_settings' );
delete_option( 'mtp_slider_primary_controls_settings' );
delete_option( 'mtp_slider_secondary_navigation_settings' );
delete_option( 'mtp_slider_special_properties_settings' );
delete_option( 'mtp_slider_carousel_settings' );
delete_option( 'mtp_slider_callback_api_settings' );
delete_option( 'mtp_slider_css_settings' );

?>