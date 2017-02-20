<?php

add_action( 'admin_menu', 'wp_comfort_add_options_admin_menu' );
add_action( 'admin_init', 'wp_comfort_options_settings_init' );


function wp_comfort_add_options_admin_menu() {

	add_options_page(
		__( 'WP Comfort settings', WP_LAYER_BASE_TEXTDOMAIN ),
		'WP Comfort',
		'manage_options',
		'wp_comfort_options',
		'wp_comfort_options_page'
	);
}

function wp_comfort_options_settings_init(  ) {

	register_setting( 'wp_comfort_settings_page', 'wp_comfort_settings' );

	// WordPress eMail sender
	add_settings_section( 'wp_comfort_wp_email_section', __( 'eMail sender', WP_LAYER_BASE_TEXTDOMAIN ), 'wp_comfort_render_wp_email_section', 'wp_comfort_settings_page' );
	add_settings_field( 'wp_comfort_wp_email_sender', __( 'eMail sender', WP_LAYER_BASE_TEXTDOMAIN ), 'wp_comfort_render_wp_email_sender', 'wp_comfort_settings_page', 'wp_comfort_wp_email_section' );
	add_settings_field( 'wp_comfort_wp_email_address', __( 'eMail address', WP_LAYER_BASE_TEXTDOMAIN ), 'wp_comfort_render_wp_email_address', 'wp_comfort_settings_page', 'wp_comfort_wp_email_section' );
}


// ***************************
// Content
// ***************************
// WordPress eMail sender
function wp_comfort_render_wp_email_sender(  ) {
	$options = get_option( 'wp_comfort_settings' );
	?>
	<input type='text' name='wp_comfort_settings[wp_comfort_wp_email_sender]' value='<?php echo $options['wp_comfort_wp_email_sender']; ?>' style='width: 400px;'>
	<?php
}

function wp_comfort_render_wp_email_address(  ) {
	$options = get_option( 'wp_comfort_settings' );
	?>
	<input type='text' name='wp_comfort_settings[wp_comfort_wp_email_address]' value='<?php echo $options['wp_comfort_wp_email_address']; ?>' style='width: 400px;'>
	<?php
}


// Description
function wp_comfort_render_wp_email_section( ) {
	echo __( 'Send WordPress eMails from a custom sender name and address.', WP_LAYER_BASE_TEXTDOMAIN );
}



function wp_comfort_options_page(  ) {
	?>
	<form action='options.php' method='post'>

		<?php
		settings_fields( 'wp_comfort_settings_page' );
		do_settings_sections( 'wp_comfort_settings_page' );
		submit_button();
		?>

	</form>
	<?php
}
