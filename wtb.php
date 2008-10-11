<?php
/*
Plugin Name: WP Theme Blocks (WTB)
Plugin URI: http://semanticthoughts.com/wordpress/theme/blocks
Description: manage small pieces of text and/or markup 
Author: Viorel Cojocaru
Version: 0.0.2
Author URI: http://semanticthoughts.com/
*/


function wtb_install() {
	global $wpdb;

	// table name with WP prefix
	$table_name = $wpdb->prefix . "blocks";

	// check if table exists 
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) :

		// create blocks table
		$sql = "CREATE TABLE " . $table_name . " (
			  block_ID mediumint(9) NOT NULL AUTO_INCREMENT,	
			  block_name tinytext NOT NULL,
			  block_description text,
			  block_content text,
			  UNIQUE KEY block_ID ( block_ID )
			);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		// insert demo record
		$test_name = "test_block";
		$test_description = "Couple useful words about this block";
		$test_content = 'block content here';

		$sql_insert = "INSERT INTO " . $table_name .
			" (block_name, block_description, block_content) " .
			"VALUES (\"{$test_name}\", \"{$test_description}\",\"{$test_content}\" )";

		$results = $wpdb->query( $sql_insert );

	endif;
		
	}


function wtb_add_menu() {
	add_management_page('Blocks', 'Blocks', 5, WTBPATH . "admin/index.php" ); 
}

/*
	Show a particular block
	name  - block name as it is on db 
	show  - if is true will echo the result, else will return a string
*/

function wtb_get_block( $block_name , $show = true ) {
	global $wpdb;
	$_block = $wpdb->get_var( "SELECT block_content FROM " . $wpdb->prefix . "blocks WHERE block_name=\"$block_name\"" );

	if( $show ) :
		echo $_block;
	else :
		return $_block;
	endif;
}


// setup plugin path and urls
define( WTBPATH, ABSPATH . "/wp-content/plugins/wtb/" );
define( WTBADMIN, "/wp-admin/edit.php?page=wtb/admin" );

// install plugin
register_activation_hook(WTBPATH . "wtb.php", 'wtb_install' );

// add action to load pages
add_action( 'admin_menu', 'wtb_add_menu' );
	
?>
