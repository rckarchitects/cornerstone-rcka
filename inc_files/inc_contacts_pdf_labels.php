<?php echo "<h1>Create Marketing Labels</h1>"; ?>

	<form action="pdf_contact_labels.php" method="post">
	<input type="hidden" name="action" value="output" />
		
		
		<table>
		
		<tr>
			<td colspan="2">This form will output sheets of labels of the entire contact database according to the criteria below</td></tr>
			<tr><td>Label Type</td>
			<td colspan="2">
				<select name="labeltype">
				
				<?php
					
						$label_file = "library/labels.txt";
					    if (file_exists($label_file)) {
						$label_file = file_get_contents($label_file);
						$label_array = explode("\n", $label_file);
						sort($label_array);
						}
					
						$counter = 0;
						while ($counter < count($label_array)) {
						$label_line = explode("|", $label_array[$counter]);
						print "<option value=\"$counter\">$label_line[0]</option>";
						$counter++;
						}
					
				?>
				
				</select>
			</td>
		</tr>
		<tr>
			<td width="20%">Font</td>
			<td colspan="3">
			
			<?php
			// Determine the font
				print "<input type=\"radio\" name=\"font\" value=\"arial\"";
				print "/>&nbsp;Helvetica<br />";
				print "<input type=\"radio\" name=\"font\" value=\"times\"";
				print "/>&nbsp;Times New Roman<br />";
				print "<input type=\"radio\" name=\"font\" value=\"century\"";
				echo " checked";
				print "/>&nbsp;Century Schoolbook<br />";
				print "<input type=\"radio\" name=\"font\" value=\"gillsans\"";
				print "/>&nbsp;Gill Sans<br />";
				print "<input type=\"radio\" name=\"font\" value=\"franklingothicbook\"";
				print "/>&nbsp;Franklin Gothic Book";
			?>
			
			</td>
		</tr>
		<tr>
			<td>Include label borders?</td>
			<td>
				<input type="checkbox" name="borders" value="1" />
			</td>
		</tr>
			<tr>
			<td>Include only marketing contacts?</td>
			<td>
				<select name="marketing">
				<option value="">All Entries</option>
				<option value="1">Email &amp; Hard Copy</option>
				<option value="2">Email Only</option>
				<option value="3">Hard Copy Only</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<input type="submit" value="Create Labels" />
			</td>
		</tr>
	</table>