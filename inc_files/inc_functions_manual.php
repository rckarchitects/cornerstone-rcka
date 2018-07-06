<?php

function PersistentStorage($name,$content) {
	
	echo "
	
	<script>
	// Check browser support
	if (typeof(Storage) !== \"undefined\") {
		// Store
		localStorage.setItem(\"$name\", \"$content\");
		// Retrieve
		document.getElementById(\"result\").innerHTML = localStorage.getItem(\"$name\");
	} else {
		document.getElementById(\"result\").innerHTML = \"Sorry, your browser does not support Web Storage...\";
	}
	</script>

	
	";
}

function ManualChecklist($media_checklist) {
	
	global $conn;
	$sql = "SELECT * FROM `intranet_project_checklist_items`, `intranet_timesheet_group` WHERE item_stage = group_id ORDER BY group_order, item_order ";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		
		echo "<select name=\"manual_checklist\">";
		
		echo "<option value=\"\">-- None --</option>";
		
		while ($array = mysql_fetch_array($result)) {
			
			if ($current_group != $array['group_code']) { echo "<option value=\"\" disabled=\"disabled\" style=\"font-size: 75%;\">" . $array['group_code'] . ": " .  $array['group_description'] . "</option>"; $current_group = $array['group_code']; }
			
			if ($array['item_id'] == $media_checklist) { $selected = "selected=\"selected\""; } else { unset($selected) ; }
			
			echo "<option value=\"" . $array['item_id']  . "\"" . $selected . ">" . $array['item_name'] . "</option>";
			
		}
		
		echo "</select>";
		
	}
		
}

function ManualPageEdit($user, $manual_id, $user_usertype_current) {#

	global $conn;
	
	$manual_id = intval($manual_id);
	$user_id = intval($user_id);
	
	$sql = "SELECT * FROM intranet_stage_manual WHERE manual_id = $manual_id";
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	
	$manual_text = $array['manual_text'];
	$manual_title = $array['manual_title'];
	$manual_section = $array['manual_section'];
	$manual_stage = $array['manual_stage'];
	$manual_attachment = $array['manual_attachment'];
	$manual_checklist = $array['manual_checklist'];

	echo "<h2>Edit Page</h2>";
	
	echo "<form method=\"post\" action=\"index2.php?page=manual_page&amp;manual_id=$manual_id\">";
	TextAreaEdit("manual_text");
	echo "<p>Title<br /><input type=\"text\" name=\"manual_title\" maxlength=\"200\" style=\"width: 95%;\" required=\"required\" value=\"" . $manual_title . "\" /></p>";
	echo "<p>Content<br /><textarea name=\"manual_text\" id=\"manual_text\" style=\"width: 95%; min-height: 600px;\">" . $manual_text. "</textarea></p>";
	
	echo "<p>Fee Stage<br />";
	
	SelectProjectStage("manual_stage" , $manual_stage);
	
	echo "</p>";
	
	echo "<p>Section<br /><span class=\"minitext\">Entries will be grouped under this heading in the index.</span><br />";
	ManualSectionList($manual_section);
	echo "</p>";
	
	echo "<p>Attachment<br />";
	ManualAttachMedia($manual_attachment);
	echo "</p>";
	
	echo "<p>Checklist<br />";
	ManualChecklist($manual_checklist);
	echo "</p>";
	
	echo "<input type=\"hidden\" name=\"action\" value=\"manual_edit\" />";
	echo "<input type=\"hidden\" name=\"manual_id\" value=\"$manual_id\" />";
	echo "<input type=\"hidden\" name=\"manual_author\" value=\"" . $_COOKIE[user] . "\" />";
	echo "<input type=\"submit\" />";
	echo "</form>";
	
}

function ManualAttachMedia($media_attachment) {
	
	global $conn;
	
	$sql = "SELECT * FROM intranet_media WHERE media_type = 'pdf' ORDER BY media_title, media_timestamp DESC";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		
		echo "<select name=\"manual_attachment\">";
		echo "<option value=\"\">-- None --</option>";
		while ($array = mysql_fetch_array($result)) {
			
			if ($array['media_id'] == $media_attachment) { $select = "selected=\"selected\""; } else { unset($select); }
			
			echo "<option value=\"" . $array['media_id'] . "\" $select>" . $array['media_title'] . "</option>";
			
		}
		echo "</select>";
		
	}
	
}

function ManualPageAdd($user, $user_usertype_current, $manual_stage) {
	

	echo "<h2>Add Page</h2>";
	
	echo "<form method=\"post\" action=\"index2.php?page=manual_page\">";
	TextAreaEdit("manual_text");
	echo "<p>Title<br /><input type=\"text\" name=\"manual_title\" maxlength=\"200\" style=\"width: 95%;\" required=\"required\" /></p>";
	echo "<p>Content<br /><textarea name=\"manual_text\" id=\"manual_text\" style=\"width: 95%; min-height: 600px;\"></textarea></p>";
	
	echo "<p>Fee Stage<br />";
	
	SelectProjectStage("manual_stage" , $manual_stage);
	
	echo "</p>";
	
	echo "<p>Section<br /><span class=\"minitext\">Entries will be grouped under this heading in the index.</span><br />";
	ManualSectionList();
	echo "</p>";
	
	echo "<p>Checklist<br />";
	ManualChecklist($manual_checklist);
	echo "</p>";
	
	echo "<input type=\"hidden\" name=\"action\" value=\"manual_edit\" />";
	echo "<input type=\"hidden\" name=\"manual_author\" value=\"" . $_COOKIE[user] . "\" />";
	echo "<input type=\"submit\" />";
	echo "</form>";
	
}

function ManualPageView($manual_id) {
	
	global $conn;
	
	$manual_id = intval($manual_id);
	

	$sql = "SELECT * FROM intranet_stage_manual LEFT JOIN intranet_timesheet_group ON manual_stage = group_id LEFT JOIN intranet_media ON media_id = manual_attachment WHERE manual_id = $manual_id ORDER BY manual_stage, manual_section, manual_order";
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	echo "<h2>" . $array['manual_title'] . "&nbsp;<a href=\"index2.php?page=manual_page&amp;action=edit&manual_id=" . $manual_id . "\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a></h2>";
	echo "<h3>" . $array['group_code'] . "&nbsp;" . $array['group_description'] . "</h3>";
	echo "<div class=\"manual_page\">";
	
	if ($array['manual_attachment']) { echo "<div class=\"bodybox imagecontainer\" style=\"float: right; margin: 0 0 12px 12px;\"><p><span class=\"minitext\">Attachments</span><br /><a href=\"" . $array['media_path'] . $array['media_file'] . "\"><img src=\"images/icon_pdf.png\" style=\"width: 120px; margin: 12px;\" /><br />" . $array['media_title'] . "</a></p></div>"; }

	echo $array['manual_text'] . "</div>";
	echo "<p>Author: " . UserDetails($array['manual_author']) . "<br />Updated: " . TimeFormatDetailed($array['manual_updated']) . "</p>";
	
}

function ManualIndexView() {
	
	global $conn;
	
	echo "<h2>Index</h2>";
	
	$sql = "SELECT manual_id,manual_section, manual_title, group_id, group_code, group_description FROM intranet_stage_manual LEFT JOIN intranet_timesheet_group ON manual_stage = group_id ORDER BY manual_stage, manual_section, manual_order";
	$result = mysql_query($sql, $conn);
	$current_stage = NULL;
	$current_section = NULL;
	
	while ($array = mysql_fetch_array($result)) {
		if ($current_stage != $array['group_id']) { echo "<tr><td><h3>" . $array['group_code'] . "&nbsp;" . $array['group_description'] . "</h3></td></tr>"; $current_stage = $array['group_id']; }
		echo "<tr><td><p><a href=\"index2.php?page=manual_page&amp;manual_id=" . $array['manual_id'] . "\">" . $array['manual_title'] . "</a></p></td></tr>";
	}
	
	echo "</table>";
	
	
}

function ManualSectionList ($manual_section) {
	
	global $conn;
	
	echo "<input type=\"text\" name=\"manual_section\" list=\"manual_section\" value=\"" . $manual_section . "\" maxlength=\"200\" />";
	echo "<datalist id=\"manual_section\">";
	
	$sql = "SELECT DISTINCT manual_section FROM intranet_stage_manual GROUP BY manual_section ORDER BY manual_section";
	$result = mysql_query($sql, $conn);
	while ($array = mysql_fetch_array($result)) {
		echo "<option value=\"" . $array['manual_section'] . "\"></option>";
	}
	echo "</datalist>";
	
}
