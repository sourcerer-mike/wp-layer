<?php
/*
Plugin Name: Pretzlaw // Comfort
Version: 2.4.0
Description: More comfort and security for your site.
Author: Mike Pretzlaw
Author URI: http://mike-pretzlaw.de
Plugin URI: http://mike-pretzlaw.de
Text Domain: wp-comfort
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( is_file( ABSPATH . '/vendor/autoload.php' ) ) {
	require_once ABSPATH . '/vendor/autoload.php';
}

define( 'WP_LAYER_BASE_DIR', __DIR__ );
define( 'WP_LAYER_BASE_FILE', __FILE__ );
define( 'WP_LAYER_BASE_TEXTDOMAIN', basename( __DIR__ ) );

add_action(
	'plugins_loaded',
	function () {
		load_plugin_textdomain(
			WP_LAYER_BASE_TEXTDOMAIN,
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}
);

require_once __DIR__ . '/includes/setup/activate.php';

// Fetch all.
require_once 'includes/index.php';

foreach ( glob( __DIR__ . '/includes/*' ) as $file_node ) {
	$basename = basename( $file_node, '.php' );

	if ( is_dir( $file_node ) ) {
		continue;
	}

	if ( basename( $file_node ) != $basename ) {
		// Is php file: use it.
		require_once $file_node;
	}
}
