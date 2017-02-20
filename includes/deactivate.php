<?php

register_deactivation_hook(
	WP_LAYER_BASE_FILE,
	function () {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

		check_admin_referer( "deactivate-plugin_{$plugin}" );

		$activated = get_option( 'active_plugins' );

		$wp_plugins = array();
		$plugins    = get_plugins();
		foreach ( $activated as $p ) {
			if ( ! isset( $plugins[ $p ] ) ) {
				// Not an installed plugin: ignore!
				continue;
			}

			if ( $p == $plugin ) {
				// It's this plugin itself: ignore!
				continue;
			}

			$append = $p;
			if ( isset( $plugins[ $p ]['Name'] ) ) {
				$append = $plugins[ $p ]['Name'];
			}
			$wp_plugins[] = $append;
		}

		sort( $wp_plugins );

		if ( $wp_plugins ) {
			wp_redirect(
				admin_url(
					'plugins.php?' . http_build_query(
						array(
							'plugin'       => $plugin,
							'dependencies' => $wp_plugins,
						)
					)
				)
			);

			exit;
		}
	}
);

add_action(
	'admin_notices',
	function () {
		global $pagenow;

		if ( 'plugins.php' != $pagenow ) {
			// Not the plugins page: ignore.
			return;
		}

		if ( ! isset( $_GET['dependencies'] ) || ! $_GET['dependencies'] ) {
			// Not an message about dependencies: ignore.
			return;
		}

		$all_plugins = get_plugins();
		$target      = '';

		if ( isset( $_GET['plugin'] ) ) {
			$target = $_GET['plugin'];
		}

		if ( isset( $all_plugins[ $target ] )
		     && isset( $all_plugins[ $target ]['Name'] )
		) {
			$target = $all_plugins[ $target ]['Name'];
		}

		require_once __DIR__
		             . DIRECTORY_SEPARATOR . 'deactivate-error-notice.phtml';
	}
);
