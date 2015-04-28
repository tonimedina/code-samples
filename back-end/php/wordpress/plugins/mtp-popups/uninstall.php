<?php

if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

delete_option( 'mtp_popups_main_settings' );
delete_option( 'mtp_popups_css_settings' );

?>