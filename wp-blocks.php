<?php
/*
Plugin Name: Wordpress Theme Blocks ( wp-blocks )
Plugin URI: 
Description: wp-blocks manage small pieces of text and/or markup
Author: Viorel Cojocaru
Version: 0.6
Author URI: http://semanticthoughts.com/

@TODO- move identifier content into a variable and rename-it to WPB
@TODO- internationalization
@TODO- add WP JS validation
@TODO- plugin uninstall function
@TODO- keep database version in WP options
@TODO- add enabled/disabled field
@TODO- add Rich Text Editor
@TODO- build new/edit links 
@TODO- block-list - list features
	- sort
	- multiple select
	- filter
	- pagination


*/


// setup plugin's constants  
define( WPB_EXP, 'wpb' );													// expression to look after on post/page content	
define( WPB_PATH, ABSPATH . "/wp-content/plugins/wp-blocks/" );				// path were plugin is installed
define( WPB_ADMIN, "/wp-admin/edit.php?page=wp-blocks/admin/index.php" );	// url for admin pages
define( WPB_TABLE, $wpdb->prefix . "blocks" );								// table name


// Wordpress Plugin Install
function wpb_install() {
	global $wpdb;

	// check if table exists 
	if( $wpdb->get_var("SHOW TABLES LIKE '" . WPB_TABLE . "'" ) != WPB_TABLE ) :

		// create blocks table
		$sql = "CREATE TABLE " . WPB_TABLE . " (
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
		$sql_insert = "INSERT INTO " . WPB_TABLE .
			" (block_name, block_description, block_content) " .
			"VALUES (\"{$test_name}\", \"{$test_description}\",\"{$test_content}\" )";

		// run INSERT sql statement
		$results = $wpdb->query( $sql_insert );

	endif;
		
	}

// Add plugin admin page
function wpb_add_menu() {
	add_management_page('Blocks', 'Blocks', 5, WPB_PATH . "admin/index.php" ); 
}


/*
Show a particular block . Is used on theme files
	name  - block name as it is on db 
	show  - if is true will echo the result (default), else will return a string
*/

function wpb_get_block( $block_name , $show = true ) {
	global $wpdb;
	$_block = $wpdb->get_var( "SELECT block_content FROM " . WPB_TABLE . " WHERE block_name=\"$block_name\"" );

	if( $show ) :
		echo $_block;
	else :
		return $_block;
	endif;
}


/* 
Search/replace  for blocks into the_content.
*/
function wpb_the_content( $content ) {

	// perl reg exp find/replace 
	$content = preg_replace_callback ( 
		"/<!--" . WPB_EXP . " (\w+)-->/i", 
		create_function(
			'$_blocks',
			'return wpb_get_block( $_blocks[1], false );'
		),
		$content , -1, $count );
	// returning content
	echo $content;
}


// add actions and filters to WP

// install plugin
register_activation_hook(WPB_PATH . "wtb.php", 'wpb_install' );

// add action to load pages
add_action( 'admin_menu', 'wpb_add_menu' );

// check for blocks on content 
add_filter( "the_content", "wpb_the_content" );

?>
