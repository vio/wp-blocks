		<?php 
		/* list all blocks */
		global $wpdb;
		$_blocks = $wpdb->get_results( "SELECT block_ID, block_name, block_description FROM " . $wpdb->prefix . "blocks ORDER BY block_ID DESC" );
		
		$_link_delete_js = "onclick=\"if(!confirm('Delete block ?')) { return false; }\"";
		$_link_edit = WTBADMIN . "&act=edit&block_id=";

		?>
		
		<table class="widefat">
			<thead>
				<tr>
					<th class="check-column" scope="col"><!--<input type="checkbox"/>--></th>
					<th scope="col">Name</th>
					<th scope="col">Description</th>
					<th scope="col" class="action-links">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($_blocks as $_block ) : ?>
				<?php
				// prepare delete link
				$_link_delete = "<a href=\"" . WTBADMIN. "&act=delete&block_id=" . $_block->block_ID . "\" " . $_link_delete_js. ">Delete</a>";
				?>

				<tr>
					<th class="check-column" scope="row"><!--<input type="checkbox" value="xx" name="delete[]"/>--></th>
					<td>
						<a href="<?php echo $_link_edit . $_block->block_ID?>" class="row-title"><?php echo $_block->block_name ?></a>
					</td>
					<td>
						<?php echo $_block->block_description; ?>
					</td>
					<td class="action-links">
						<?php echo $_link_delete ?>
					</td>
				</tr>

				<?php endforeach; ?>
			</tbody>
		</table>
