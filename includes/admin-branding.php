<?php

// Login screen.
add_action(
	'login_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'wp-comfort-base-admin-login',
			plugins_url( 'public/css/admin-login.css', WP_LAYER_BASE_FILE )
		);
	}
);

add_filter(
	'login_headertitle',
	function () {
		return 'Your custom login!';
	}
);

add_filter(
	'login_message',
	function () {
		require_once __DIR__ . '/admin/login.phtml';
	}
);

add_filter(
	'login_headerurl',
	function () {
		return 'http://mike-pretzlaw.de';
	}
);

add_action(
	'login_footer',
	function () {

		$query = http_build_query(
			array(
				'subject' => get_site_url(),
				'body'    => "Guten Tag,\n\n\n",
			)
		);

		$query = str_replace( '+', '%20', $query );

		?>
		<div class="login-center">
			<p>
				<a href="mailto:<?php antispambot( 'mail@mike-pretzlaw.de' ) ?>?<?php echo $query ?>">
					Hilfe anfordern
				</a>
			</p>
		</div>
	<?php
	}
);

// Admin footer.
add_filter(
	'admin_footer_text',
	function () {
		?>
		<span id="footer-thankyou">
				<a href="http://mike-pretzlaw.de" target="_blank">
					<nobr>Bei Fragen einfach an Mike Pretzlaw wenden.</nobr>
				</a>
			</span>
	<?php
	}
);


// Update.
add_filter(
	'update_footer',
	function ( $msg ) {
		if ( ! current_user_can( 'update_core' ) ) {
			return $msg;
		}

		$cur = get_preferred_from_update_core();
		if ( ! is_object( $cur ) ) {
			$cur = new stdClass;
		}

		if ( ! isset( $cur->current ) ) {
			$cur->current = '';
		}

		if ( ! isset( $cur->url ) ) {
			$cur->url = '';
		}

		if ( ! isset( $cur->response ) ) {
			$cur->response = '';
		}

		switch ( $cur->response ) {
			case 'upgrade' :
				return
					'<a href="http://mike-pretzlaw.de">F&uuml;r Ihr System sind Aktualisierungen verf&uuml;gbar! Auf Wunsch &uuml;bernehme ich das gerne f&uuml;r Sie</a><br/>'
					. '<a href="mailto:mail@mike-pretzlaw.de?subject=WordPress-Update">mail@mike-pretzlaw.de</a> oder '
					. '<a href="tel:+4952514171991">+49 5251 / 41 71 991</a>.';
			case 'latest' :
		}
	},
	11
);

function wp_layer_update_nag() {
	if ( is_multisite() && ! current_user_can( 'update_core' ) ) {
		return false;
	}

	global $pagenow;

	if ( 'update-core.php' == $pagenow ) {
		return;
	}

	$cur = get_preferred_from_update_core();

	if ( ! isset( $cur->response ) || $cur->response != 'upgrade' ) {
		return false;
	}

	echo '<div class="update-nag">'
	     . '<a href="http://mike-pretzlaw.de">F&uuml;r Ihr System sind Aktualisierungen verf&uuml;gbar! Auf Wunsch &uuml;bernehme ich das gerne f&uuml;r Sie</a><br/>'
		 . '<a href="mailto:mail@mike-pretzlaw.de?subject=WordPress-Update">mail@mike-pretzlaw.de</a> oder '
		 . '<a href="tel:+4952514171991">+49 5251 / 41 71 991</a>.'
	     . '</div>';
}

add_action(
	'admin_init',
	function () {
		remove_action( 'admin_notices', 'update_nag', 3 );
		remove_action( 'network_admin_notices', 'update_nag', 3 );
	}
);

remove_action( 'admin_notices', 'update_nag', 3 );
remove_action( 'network_admin_notices', 'update_nag', 3 );

add_action( 'admin_notices', 'wp_comfort_update_nag' );
add_action( 'network_admin_notices', 'wp_comfort_update_nag' );

add_filter(
	'contextual_help_list',
	function ($old_compat_help, \WP_Screen $screen) {
		$screen->set_help_sidebar(
			'<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
			'<p>Telefon:<br /><a href="tel:+4952514171991">+49 5251 / 41 71 991</a></p>'.
			'<p>Mail:<br /><a href="mailto:mail@mike-pretzlaw.de?subject=WordPress-Update">mail@mike-pretzlaw.de</a></p>'
		);
	},
	10,
	2
);
