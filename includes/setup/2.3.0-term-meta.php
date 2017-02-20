<?php

$type       = 'term';
$table_name = null;

// Create table for term meta.
global $wpdb;

if ( null === $table_name ) {
	$table_name = $type . 'meta';
}

$table_name = $wpdb->prefix . $table_name;

if ( ! empty( $wpdb->charset ) ) {
	$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
}
if ( ! empty( $wpdb->collate ) ) {
	$charset_collate .= " COLLATE {$wpdb->collate}";
}

$sql
	= "CREATE TABLE IF NOT EXISTS {$table_name} (
        meta_id bigint(20) NOT NULL AUTO_INCREMENT,
        {$type}_id bigint(20) NOT NULL default 0,

        meta_key varchar(255) DEFAULT NULL,
        meta_value longtext DEFAULT NULL,

        UNIQUE KEY meta_id (meta_id)
    ) {$charset_collate};";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
