=== Plugin Name ===
Contributors: vio
Donate link: 
Tags: theme, block
Requires at least: 2.6.2
Tested up to: 2.6.2
Stable tag: 0.6.0

== Description ==

wp-blocks manage small pieces of text and/or markup from Wordpress Admin. You can insert them in Posts/Pages 
or add them directly to theme


== Installation ==
 
1. Upload and unzip plugin in `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress Admin
3. add <!--wpb BLOCK_NAME--> in page/post content or wpb_get_block( 'block_name' ) in template 


== History

* Version 0.6
	- updated admin panel
	- validation for block name 
	- auto transform function for block name
	- prefix all functions with plugin name and changed constants names
