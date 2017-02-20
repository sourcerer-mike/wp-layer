<?php

if ( isset( $_GET['hide-admin-bar'] ) ) {
	add_filter( 'show_admin_bar', '__return_false' );
}
