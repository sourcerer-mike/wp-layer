<?php

require_once ABSPATH . 'wp-admin/includes/upgrade.php';

global $wpdb;

$wp_current_db_version = get_option( 'db_version' );

if ( $wp_current_db_version > 33055 ) {
	if ( is_multisite() ) {
		$tables = $wpdb->tables( 'blog' );
	} else {
		$tables = $wpdb->tables( 'all' );
		if ( ! wp_should_upgrade_global_tables() ) {
			$global_tables = $wpdb->tables( 'global' );
			$tables = array_diff_assoc( $tables, $global_tables );
		}
	}

	foreach ( $tables as $table ) {
		$results = $wpdb->get_results( "SHOW FULL COLUMNS FROM `$table`" );
		if ( ! $results ) {
			continue;
		}

		foreach ( $results as $column ) {
			if ( $column->Collation ) {
				list( $charset ) = explode( '_', $column->Collation );
				$charset = strtolower( $charset );
				if ( 'utf8' !== $charset && 'utf8mb4' !== $charset ) {
					// Don't upgrade tables that have non-utf8 columns.
					continue(2);
				}
			}
		}

		$table_details = $wpdb->get_row( "SHOW TABLE STATUS LIKE '$table'" );
		if ( ! $table_details ) {
			continue;
		}

		list( $table_charset ) = explode( '_', $table_details->Collation );
		$table_charset = strtolower( $table_charset );

		if ( 'utf8' === $table_charset ) {
			continue;
		}

		$wpdb->query(
			"ALTER TABLE $table
			CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci"
		);
	}
}
