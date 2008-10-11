<?php 

/* 
 * List blocks
 * @todo - build new/edit links 
 * @todo - list features
 *			- sort
 *			- multiple select
 *			- filter
 *			- pagination
 */


	global $wpdb;
	$_blocks = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "blocks ORDER BY block_ID DESC" );
?>

<div id="wpbody">
	<div class="wrap">
		<h2>Manage blocks (<a href="<?php echo WTBADMIN?>/edit.php">add new</a>)</h2>

		<br class="clear" />

		<table class="widefat">
			<thead>
				<tr>
					<th class="check-column" scope="col"><!--<input type="checkbox"/>--></th>
					<th scope="col">Name</th>
					<th scope="col" colspan="2">Description</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($_blocks as $_block ) : ?>
				<tr>
					<th class="check-column" scope="row"><!--<input type="checkbox" value="xx" name="delete[]"/>--></th>
					<td><a href="<?php echo WTBADMIN?>/edit.php&block_id=<?php echo $_block->block_ID?>" class="row-title"><?php echo $_block->block_name ?></a></td>
					<td><?php echo $_block->block_description ?></td>
					<td><a href="<?php echo WTBADMIN?>/delete.php&block_id=<?php echo $_block->block_ID?>" class="button-secondary delete">Delete</a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
	</div>
</div>
