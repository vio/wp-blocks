<?php
/**
 * Plugin Name: Theme Blocks (wp-blocks)
 * Plugin URI: 
 * Description: manage small pieces of text and/or markup from Wordpress Admin
 * Author: Viorel Cojocaru
 * Version: 0.8
 * Author URI: http://semanticthoughts.com/
 * 
 * @package wp-blocks
 *
 * @global  string  WPB_EXP             Expression to look after on post/page content
 * @global  string  WPB_PATH            Plugin path; used to build absolute paths for inclusion
 * @global  string  WPB_ADMIN           Plugin admin url; used to build links for plugin admin area
 * @global  string  WPB_TABLE           Table name
 * @global  string  WPB_TABLE_VERSION   Table structure version
 * 
 * @todo    move identifier content into a variable and rename-it to WPB
 * @todo    internationalization
 * @todo    add WP JS validation
 * @todo    keep database version in WP options
 * @todo    add enabled/disabled field
 * @todo    add Rich Text Editor
 * @todo    build new/edit links 
 * @todo    block-list - list features
 * 	        - sort
 * 	        - multiple select
 * 	        - filter
 * 	        - pagination
*/

global $wpdb;

define( WPB_EXP,            'wpb' );
define( WPB_PATH,           dirname(__FILE__) );
define( WPB_ADMIN,          get_bloginfo( 'url') ."/wp-admin/edit.php?page=wp-blocks/admin/index.php" );
define( WPB_TABLE,          $wpdb->prefix . "blocks" );
define( WPB_TABLE_VERSION,  "1" );


/**
 * Get content of a particular block. Is used on theme files
 * 
 * @global  object    $wpdb
 * @param   string    $name   Block name to search in wp_blocks table
 * @param   boolean   $show   If is true will echo the result (default), else will return a string
 * @return  text              Content of the block name found in wp_blocks table
 *
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


/**
 * Search/replace  for blocks into the_content.
 *
 * @param   text    $content  WP content passed by filter
 * @return  text              WP content altered
 */
function wpb_the_content( $content ) {

	// perl reg exp find/replace 
	$content = preg_replace_callback ( 
		"/<!--" . WPB_EXP . " (\w+)-->/i", 
		create_function(
			'$_blocks',
			'return wpb_get_block( $_blocks[1], false );'
		),
		$content, -1, $count );
	
	echo $content;
}


/**
 * runs when activating WP-BLOCKS plugin
 */
function wpb_activate() {
  
	// writting table structure version to WP option
	add_option( 'wpb_table_version', WPB_TABLE_VERSION );
  
	// install SQL
	wpb_install_sql();
}


/**
 * Runs when deactivating WP-BLOCKS
 */
function wpb_deactivate() {
	remove_action( 'admin_menu', 'wpb_add_menu' );
	remove_filter( 'the_content', 'wpb_the_content' );
	delete_option( 'wpb_table_version' );
}


/**
 * Add WP-BLOCKS menu link to Wordpress Admin
 */
function wpb_add_menu() {
	add_posts_page('Blocks', 'Blocks', 5, "wp-blocks/admin/index.php" ); 
}


/**
 * Install required sql table
 */
function wpb_install_sql() {
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

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

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


/**
 * Register activation/deactivation plugin hook
 */
register_activation_hook( __FILE__, 'wpb_activate' );
register_deactivation_hook( __FILE__, 'wpb_deactivate' );

/**
 * Add actions & Filters
 */
add_action( 'admin_menu', 'wpb_add_menu' );
add_filter( 'the_content', 'wpb_the_content' );

?>
