<?php

add_filter( 'wp_mail_from', 'wp_layer_mail_from' );
add_filter( 'wp_mail_from_name', 'wp_layer_mail_from_name' );

function wp_layer_mail_from($old) {
	$wp_layer_comfort_settings = get_option( 'wp_layer_comfort_settings' );
	if ( ! empty( $wp_layer_comfort_settings['wp_layer_comfort_wp_email_address'] ) ) {
		return $wp_layer_comfort_settings['wp_layer_comfort_wp_email_address']; }
	return $old;
}

function wp_layer_mail_from_name($old) {
	$wp_layer_comfort_settings = get_option( 'wp_layer_comfort_settings' );
	if ( ! empty( $wp_layer_comfort_settings['wp_layer_comfort_wp_email_sender'] ) ) {
		return $wp_layer_comfort_settings['wp_layer_comfort_wp_email_sender']; }
	return $old;
}
