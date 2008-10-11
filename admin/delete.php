<?php 

/*
	Delete a block by id
*/

global $wpdb;

if ( $_GET["block_id"] != 0 ) :
	$_id = $_GET["block_id"];

	$_deleted = $wpdb->query( "DELETE FROM " . $wpdb->prefix. "blocks WHERE block_id=$_id");
	
	if( $_deleted ) :
		$_message = "Block deleted !";
	else :
		$_message = "Error !" ;
	endif;
	
else:
	$_message = "Nothing to delete ?!" ;
endif;
?>
	
<div id="wpbody">
	<div class="wrap">
		<h2>Delete a block</h2>
		<br class="clear" />
		<p><?php echo $_message ?></p>	
		<a href="<?php echo WTBADMIN?>/index.php">Go back</a> 
	</div>
</div>
