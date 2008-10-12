<?php
/*
Plugin Name: WP Theme Blocks (WTB)
Plugin URI: http://semanticthoughts.com/wordpress/theme/blocks
Description: WTB manage small pieces of text and/or markup from "Manage / Blocks" in Wordpress Admin 
Author: Viorel Cojocaru
Version: 0.5.2
Author URI: http://semanticthoughts.com/

@todo support for translated images
@todo support for post/page content included blocks
@todo add settings panel for plugin
	- uninstall function
	- uninstall with/without removing DB
@todo add enabled/disabled field
@todo block admin - build new/edit links 
@todo block-list - list features
	- sort
	- multiple select
	- filter
	- pagination

*/

function wtb_install() {
	global $wpdb;

	// check if table exists 
	if( $wpdb->get_var("SHOW TABLES LIKE '" . WTB_TABLE . "'" ) != WTB_TABLE ) :

		// create blocks table
		$sql = "CREATE TABLE " . WTB_TABLE . " (
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

		// INSERT insert demo record into WP db	
		$sql_insert = "INSERT INTO " . WTB_TABLE .
			" (block_name, block_description, block_content) " .
			"VALUES (\"{$test_name}\", \"{$test_description}\",\"{$test_content}\" )";

		// run INSERT sql statement
		$results = $wpdb->query( $sql_insert );

	endif;
		
	}


function wtb_add_menu() {
	add_management_page('Blocks', 'Blocks', 5, WTBPATH . "admin/index.php" ); 
}


/*
Show a particular block . Is used on theme files
	name  - block name as it is on db 
	show  - if is true will echo the result (default), else will return a string
*/

function wtb_get_block( $block_name , $show = true ) {
	global $wpdb;
	$_block = $wpdb->get_var( "SELECT block_content FROM " . WTB_TABLE . " WHERE block_name=\"$block_name\"" );

	if( $show ) :
		echo $_block;
	else :
		return $_block;
	endif;
}

/* 
Search/replace  for blocks into the_content . Now only first found will be replaced !!!
*/
function wtb_the_content( $content ) {

	$_start_tag = "<!-- wtb";
	$_end_tag	= "-->";

	// get first and last position of start and end
	$_fp = strpos( $content, $_start_tag);
	$_lp = strpos( $content, $_end_tag, $_fp) ; 
	
	// get string between start and end tag 
	$_block_name = trim(substr( $content, $_fp + strlen( $_start_tag ), $_lp - $_fp - strlen( $_start_tag ) ));

	// doing replace 
	if( $_block_name ) :
		$content = substr_replace( $content, wtb_get_block( $_block_name, false), $_fp, $_lp ); 
	endif;


	echo $content;
}


// setup plugin  
define( WTBPATH, ABSPATH . "/wp-content/plugins/wtb/" );				// path were plugin is installed
define( WTBADMIN, "/wp-admin/edit.php?page=wtb/admin/index.php" );		// url for admin pages
define( WTB_TABLE, $wpdb->prefix . "blocks" );							// wtb table name


// install plugin
register_activation_hook(WTBPATH . "wtb.php", 'wtb_install' );

// uninstall pluggin 


// add action to load pages
add_action( 'admin_menu', 'wtb_add_menu' );

// check for blocks on content 
add_filter( "the_content", "wtb_the_content" );
?>
