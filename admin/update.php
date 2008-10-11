<?php 

/*
	Insert/Update block on WP db
	@todo messages
	@todo redirect
*/

global $wpdb;

$_name			= $_POST["block_name"];
$_description	= $_POST["block_description"];
$_content		= $_POST["block_content"];

if( $_POST["block_id"] != 0 ) :
	//update mode
	
	$_id			= $_POST["block_id"];

	$_update = $wpdb->query( "UPDATE " . $wpdb->prefix . "blocks SET 
		 block_name = \"$_name\",
		block_description = \"$_description\",
		block_content = \"$_content\" 
		 WHERE block_id = $_id
		" );

	$_heading = "Edit block" ;
	if( $_update ) :
		$_message = "Block was updated!";
	else :
		$_message = "Error";
		print_r($wpdb);
	endif;

else :
	//insert mode

	$_insert = $wpdb->query( " INSERT INTO " . $wpdb->prefix . "blocks (block_name, block_description, block_content ) values(\"$_name\", \"$_description\", \"$_content\")" );	

	$_heading = "Add block" ;
	if( $_insert ) :
		$_message = "Block was added to database!";
	else :
		$_message = "Error";
	endif;

endif;

?>

<div id="wpbody">
	<div class="wrap">
		<h2><?php echo $_heading ?></h2>
		<br class="clear" />
		<p><?php echo $_message ?></p>	
		<a href="<?php echo WTBADMIN?>/index.php">Go back</a> 
	</div>
</div>
