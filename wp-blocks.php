<?php
/*
Plugin Name: Wordpress Theme Blocks ( wp-blocks )
Plugin URI: http://semanticthoughts.com/wordpress/plugins/wp-blocks
Description: wp-blocks manage small pieces of text and/or markup
Author: Viorel Cojocaru
Version: 0.5.3
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

	// perl reg exp find/replace 
	$content = preg_replace_callback ( 
		"/<!-- wtb (\w+) -->/i", 
		create_function(
			'$_blocks',
			'return wtb_get_block($_blocks[1], false);'
		),
		$content , -1, $count);

	// returning content
	echo $content;
}


// setup plugin  
define( WTBPATH, ABSPATH . "/wp-content/plugins/wp-blocks/" );				// path were plugin is installed
define( WTBADMIN, "/wp-admin/edit.php?page=wp-blocks/admin/index.php" );		// url for admin pages
define( WTB_TABLE, $wpdb->prefix . "blocks" );							// wtb table name


// install plugin
register_activation_hook(WTBPATH . "wtb.php", 'wtb_install' );

// uninstall pluggin 


// add action to load pages
add_action( 'admin_menu', 'wtb_add_menu' );

// check for blocks on content 
add_filter( "the_content", "wtb_the_content" );

?>
