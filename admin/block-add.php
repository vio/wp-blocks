		<?php 
		
		// setup variables from post
		$_id			= $_POST[ "block_id" ];
		$_name			= $_POST[ "block_name" ];
		$_description	= $_POST[ "block_description" ];
		$_content		= $_POST[ "block_content" ]	;
		
		?>
		
		<form action="<?php echo WTBADMIN?>&act=insert" method="post" class="add:the-list: validate">
			
			<?php include( "block-form.php" ); ?>
			
			<p class="submit">
				<input type="submit" value="Add block" name="submit" class="button button-highlighted"/>
				or
				<a href="<?php echo WTBADMIN ?>" style="color: #777; font-weight: bold;">Cancel</a>
			</p>
		</form>
		<br class="clear" />
