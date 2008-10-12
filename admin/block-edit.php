		<?php 
		global $wpdb;

		// check if wee have variables on POST
		$_id = $_POST["block_id"] ;
		if ( $_id ) :
			// getting variables from POST

			$_name			= $_POST[ "block_name" ];
			$_description	= $_POST[ "block_description" ];
			$_content		= $_POST[ "block_content" ]	;

		else :
			// getting variables from db
			
			$_id = $_GET["block_id"];
			$_block = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "blocks WHERE block_id=" . $_id );

			$_name = $_block->block_name;
			$_description = $_block->block_description;
			$_content = $_block->block_content;

		endif;


		?>
		
		<form action="<?php echo WTBADMIN?>&act=update" method="post" class="validate">
			
			<?php include( "block-form.php" ); ?>
			
			<p class="submit">
				<input type="hidden" name="block_id" value="<?php echo $_id?>" />
				<input type="submit" value="Save block" name="submit" class="button button-highlighted"/>
				or
				<a href="<?php echo WTBADMIN ?>" style="color: #777; font-weight: bold;">Cancel</a>
			</p>
		</form>
		<br class="clear" />
