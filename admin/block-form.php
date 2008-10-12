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

