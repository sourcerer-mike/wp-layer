<?php

/**
 * Add styles relative to the current screen or post-type.
 *
 * If you are looking at post-type "contact_person"
 * then the file "public/css/admin/contact-person.css" will be used.
 *
 * The file "public/css/admin.css" is always loaded in the backend.
 */
add_action(
	'admin_enqueue_scripts',
	function () {
		global $screen,
			   $typenow;

		$path = array( 'admin' );

		$myscreen = $screen;

		if ( $myscreen instanceof WP_Screen ) {
			$path[] = sanitize_file_name( $myscreen->id );
		}

		if ( $typenow ) {
			$path[] = sanitize_file_name( $typenow );
		}

		$path = array_map(
			function ( $value ) {
				return str_replace( '_', '-', $value );
			},
			$path
		);

		$style_name = 'comfort';
		$css_path = 'public/css/nop';
		foreach ( $path as $segment ) {
			$style_name .= '_' . $segment;
			$css_path = substr( $css_path, 0, -4 ) . '/' . $segment . '.css';

			wp_register_style(
				$style_name,
				plugins_url( 'public/css/admin.css', WP_LAYER_BASE_FILE ),
				false,
				'1.0.0'
			);

			wp_enqueue_style( $style_name );
		}
	}
);
