<?php

/*
	ADD/EDIT block 
	@todo - implement the same JS validation as on WP
*/


if($_GET["block_id"]) : 
	// edit mode
		$_heading = "Edit block";
		global $wpdb;
		$_id = $_GET["block_id"];
		$_block = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "blocks WHERE block_id=" . $_id );

		$_name = $_block->block_name;
		$_description = $_block->block_description;
		$_content = $_block->block_content;
	
	else :
		// add mode
		$_heading = "Add block";
		$_id = 0;
		$_name = "";
		$_description = "";
		$_content = "";

endif;

?>

<div id="wpbody">
	<div class="wrap">
		<h2><?php echo $_heading ?> ( <a href="<?php echo WTBADMIN ?>/index.php">go back</a>)</h2>

		<br class="clear" />

		<form action="<?php echo WTBADMIN?>/update.php" method="post" class="validate">
			<table class="form-table">
				
				<tr class="form-field form-required">
					<th scope="row"><label>Block Name</label></th>
					<td>
						<input type="text" name="block_name" area-required="true" value="<?php echo $_name?>" size="40" />
						<br />
						Used to interogate/display a particulary block
					</td>
				</tr>
				
				<tr class="form-field">
					<th scope="row">
						<label>Block Description</label>
					</th>
					<td>
						<textarea name="block_description" rows="3" cols="40"><?php echo $_description?></textarea>
						<br />
						A short description for this block
					</td>
				</tr>

				<tr class="form-field">
					<th scope="row">
						<label>Block Content</label>
					</th>
					<td>
						<textarea name="block_content" rows="10" cols="75"><?php echo $_content?></textarea>
						<br />
						Block content could be plain text or (x)html
					</td>
				</tr>
			</table>
			
			<p class="submit">
				<input type="hidden" name="block_id" value="<?php echo $_id?>" />
				<input type="submit" value="Add block" name="submit" class="button"/>
			</p>

		</form>
	</div>
</div>
