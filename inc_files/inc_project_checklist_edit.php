<?php

if ($_GET[item] > 0 && $user_usertype_current > 2) { $item = $_GET[item]; } else { unset($item); }

if ($_POST[item_id] > 0 && $_POST[item_notes] != NULL OR $_POST[item_stage] != NULL) {
	$item_id = $_POST[item_id];
	$item_notes = trim ( addslashes ($_POST[item_notes]) );
	$item_stage = $_POST[item_stage];
	$sql_update = "UPDATE intranet_project_checklist_items SET item_notes = \"" . $item_notes . "\", item_stage = $item_stage WHERE item_id = $item_id LIMIT 1";
	$result_update = mysql_query($sql_update, $conn) or die(mysql_error());

	
}
if ($_GET[action] == "checklist_duplicate_item") {
		
		$checklist_item = intval ( $_GET[item_id] );
		$checklist_project = intval ( $_GET[proj_id] );
		$checklist_user = intval ( $_COOKIE[user]);
		$checklist_timestamp = time();
		$sql_duplicate = "INSERT INTO intranet_project_checklist (checklist_id, checklist_item, checklist_timestamp, checklist_project, checklist_required, checklist_date, checklist_comment, checklist_user, checklist_link) VALUES (NULL,$checklist_item,$checklist_timestamp,$checklist_project,0,0,NULL,$checklist_user,NULL)";
		$result_update = mysql_query($sql_duplicate, $conn) or die(mysql_error());
	
}
if ($_GET[action] == "checklist_delete_item") {
		
		$checklist_id = intval ( $_GET[checklist_id] );
		$checklist_project = intval ( $_GET[proj_id] );
		$sql_delete = "DELETE FROM intranet_project_checklist WHERE checklist_id = $checklist_id AND checklist_project = $checklist_project LIMIT 1";
		echo "<p>Entry deleted.</p>";
		$result_delete = mysql_query($sql_delete, $conn) or die(mysql_error());
	
}


$proj_id = $_GET[proj_id];
echo "<h2>Project Checklist</h2>";
echo "<p class=\"menu_bar\"><a href=\"index2.php?page=project_checklist&amp;proj_id=$proj_id\" class=\"menu_tab\">Back to list</a></p>";

// First include a series of tabs with the project stages



if (!$_GET[group_id]) { $group_id = 1; } else { $group_id = intval($_GET[group_id]); }

StageTabs($group_id, $proj_id, "index2.php?page=project_checklist_edit&amp;proj_id=$proj_id&amp;", "edit");

$sql_checklist = "SELECT * FROM intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id AND checklist_project = $proj_id LEFT JOIN intranet_timesheet_group ON group_id = item_stage WHERE ((group_id = '$group_id') OR (item_stage IS NULL)) ORDER BY item_group, item_order, checklist_date, item_name";

$result_checklist = mysql_query($sql_checklist, $conn) or die(mysql_error());


echo "
<script type=\"text/javascript\">
	function hideRow(row, hideVal) {
    if (document.getElementById(row)) {
      var displayStyle = (hideVal!=true)? '' : 'none' ;
      document.getElementById(row).style.display = displayStyle;
    }
  }
	
</script>
	
";



if (mysql_num_rows($result_checklist) > 0) {

	if (!$item) {
	echo "<form action=\"index2.php?page=project_checklist_edit&amp;group_id=$group_id&amp;proj_id=$proj_id\" method=\"post\" enctype=\"multipart/form-data\">";
	echo "<input type=\"hidden\" name=\"action\" value=\"checklist_update\" />";
	
	}
	echo "<table>";
	echo "<tr><th>Item</th><th>Stage</th><th>Required</th><th>Date Completed / Deadline</th><th>Comment</th><th>Link to File</th><th colspan=\"2\">File Upload</th></tr>";


	$current_item = 0;
	$item_counter = 0;

	$group = NULL;
	
	while ($array_checklist = mysql_fetch_array($result_checklist)) {
	$item_id = $array_checklist['item_id'];
	$item_name = $array_checklist['item_name'];
	$item_date = $array_checklist['item_date'];
	$item_group = $array_checklist['item_group'];
	$item_required = $array_checklist['item_required'];
	$item_notes = $array_checklist['item_notes'];
	$item_order = $array_checklist['item_order'];
	$item_stage = $array_checklist['item_stage'];
	$item_deadline = $array_checklist['item_deadline'];
	
	$group_code = $array_checklist['group_code'];
	
	$checklist_id = $array_checklist['checklist_id'];
	$checklist_required = $array_checklist['checklist_required'];
	$checklist_date	= $array_checklist['checklist_date'];
	$checklist_deadline	= $array_checklist['checklist_deadline'];
	$checklist_comment = htmlentities ( $array_checklist['checklist_comment']);
	$checklist_user = $_COOKIE[user];
	$checklist_link	= $array_checklist['checklist_link'];
	$checklist_item	= $array_checklist['checklist_item'];
	$checklist_timestamp = time();
	$checklist_project = $_GET[proj_id];

	
	if ($checklist_required == 2 && ( $checklist_date == "0000-00-00" OR $checklist_date == NULL ) ) { $bg =  "style=\"background: rgba(255,0,0, 0.4); \""; }
		elseif ($checklist_required == 2 && ( $checklist_date != "0000-00-00" OR $checklist_date != NULL ) ) { $bg =  "style=\"background: rgba(0,255,0,0.4); \""; }
		elseif ($checklist_required == 1) { $bg =  "style=\"background: rgba(200,200,200, 0.4); \""; }
		else { $bg =  "style=\"background: rgba(255,220,0, 0.4); \""; }
	
	if ($item_group != $group) { echo "<tr><td colspan=\"8\"><strong>$item_group</strong></td></tr>"; }
	
	
	echo "<tr><td $bg>";
	
	echo $item_name;
	
	if ($checklist_id > 0) {
		echo "&nbsp;<span class=\"minitext\">&nbsp;<a href=\"index2.php?page=project_checklist_edit&amp;proj_id=$proj_id&amp;action=checklist_duplicate_item&amp;item_id=$item_id \" onclick=\"return confirm('Are you sure you want to duplicate the checklist entry for \'$item_name\'?')\">[+]</a></span>";
	}
	
	// Exclude the delete button if this is the second time it appears
	if ($current_item == $item_id) {
		echo "<span class=\"minitext\">&nbsp;<a href=\"index2.php?page=project_checklist_edit&amp;proj_id=$proj_id&amp;action=checklist_delete_item&amp;checklist_id=$checklist_id \" onclick=\"return confirm('Are you sure you want to delete the checklist entry for \'$item_name\'?')\"><img src=\"images/button_delete.png\" alt=\"Delete Entry\" /></a></span>";
	}
	//}
	$item_name_current = $item_name;
	echo "</td>";
	
	echo "<td $bg>$group_code</td>";
	
	echo "<td $bg>";
	
	if (!$item) {
	
	echo "
		<input type=\"hidden\" name=\"item_counter[]\" value=\"$item_counter\" />
		<input type=\"hidden\" name=\"item_id[]\" value=\"$item_id\" />
		<input type=\"hidden\" name=\"checklist_id[]\" value=\"" . intval($checklist_id) . "\" />
		<input type=\"hidden\" name=\"checklist_user[]\" value=\"$checklist_user\" />
		<input type=\"hidden\" name=\"checklist_timestamp[]\" value=\"$checklist_timestamp\" />
		<input type=\"hidden\" name=\"checklist_project[]\" value=\"$checklist_project\" />
		";
	
		echo "<select name=\"checklist_required[]\" $bg>";
		if ($checklist_required == NULL) { $checked = "selected=\"selected\""; } else { unset($checked); }
		echo "<option value=\"0\" $checked>-</option>";
		if ($checklist_required == 1) { $checked = "selected=\"selected\""; } else { unset($checked); }
		echo "<option value=\"1\" $checked>No</option>";
		if ($checklist_required == 2) { $checked = "selected=\"selected\""; } else { unset($checked); }
		echo "<option value=\"2\" $checked>Yes</option>";
		echo "</select>";
	
	} else {
		
		if ($checklist_required == 1) { echo "Not Required"; }
		elseif ($checklist_required == 2) { echo "Required"; }
		else { echo "-"; }
	
	}
	
	echo "</td>";
	if (!$item) {
		if ($item_deadline == 1) {
			echo "<td $bg><input name=\"checklist_date[]\" type=\"date\" value=\"$checklist_date\" $bg /><br /><input name=\"checklist_deadline[]\" type=\"date\" value=\"$checklist_deadline\" $bg /></td>";
		} else {
			echo "<td $bg><input name=\"checklist_date[]\" type=\"date\" value=\"$checklist_date\" $bg /><input name=\"checklist_deadline[]\" type=\"hidden\" value=\"\"/></td>";
		}
		echo "<td $bg><input name=\"checklist_comment[]\" value=\"$checklist_comment\" $bg /></td>";
		echo "<td $bg><input name=\"checklist_link[]\" value=\"$checklist_link\" $bg /></td>";
		echo "<td $bg>";
			if ($checklist_link) { echo "<a href=\"$checklist_link\"><img src=\"images/button_internet.png\" /></a>"; }
		echo "</td>";
	} else {	
		if ($checklist_date == 0) { $checklist_date = "-";}
		echo "<td $bg>$checklist_date</td>";
		echo "<td $bg>$checklist_comment</td>";
		if ($checklist_link && $_GET[item] != $item) {
			echo "<td colspan=\"2\" $bg><a href=\"$checklist_link\"><img src=\"images/button_internet.png\" /></a></td>";
		} elseif ($_GET[item] == $item_id && $restrict_row != 1) {
			$restrict_row = 1;
			echo "<td colspan=\"3\" $bg>-</td>";
		} elseif ($_GET[item] == $item_id) {
			echo "<td colspan=\"2\" $bg>-</td>";
		} else {
			echo "<td colspan=\"2\" $bg>-</td>";
		}
	}

	
	
	//echo "<td $bg>";
	
	
	//echo "<input type=\"file\" name=\"fileToUpload[$item_id]\" id=\"$item_id\" $bg>";
	
	
	//echo "</td>";
	
	if ($item_notes != NULL) {
	
		if ($_GET[item] != $item_id AND $_POST[item] != $item_id) { $hidden = "none"; } else { unset($hidden); }
	
		if (!$item) {
			echo "<td $bg><a href=\"javascript:void(0);\" onclick=\"hideRow($item_id, false);\">Help</a></td>";
		} else {
			echo "<td $bg></td>";
		}
		echo "</tr>";
		
		if ($_GET[item] == $item_id) { TextAreaEdit(); $item_notes = "<form action=\"index2.php?page=project_checklist_edit&amp;proj_id=$proj_id&amp;group_id=$group_id\" method=\"post\"><textarea style=\"width: 99%;height: 500px;\" name=\"item_notes\">$item_notes</textarea>"; }
	
		echo "<tr id=\"$item_id\" style=\"display: $hidden;\"><td colspan=\"7\" $bg>" . $item_notes;

		if ($_GET[item] == $item_id) { echo "<br />"; SelectStage($item_stage,$bg); }	
		
		
		echo "</td><td $bg>";
		if ($_GET[item] == $item_id) { 
			echo "<input type=\"hidden\" name=\"item_id\" value=\"$item_id\" /><input type=\"submit\" value=\"Update\" /></form>";
		} elseif ( $user_usertype_current > 3 ) {
			echo "<a href=\"index2.php?page=project_checklist_edit&amp;proj_id=$proj_id&amp;item=$item_id\"><img src=\"images/button_edit.png\" /></a>";
		}
		echo "</td></tr>";
		

		
	} elseif ($item_id > 0 && $_GET[item] == $item_id && $current_item != $item_id) {
		
		TextAreaEdit(); echo "<form action=\"index2.php?page=project_checklist_edit&amp;proj_id=$proj_id&amp;group_id=$group_id\" method=\"post\"><tr><td colspan=\"7\" $bg><textarea style=\"width: 99%;height: 500px;\" name=\"item_notes\">$item_notes</textarea> <br />";
		
		SelectStage($item_stage,$bg);
		
		echo "</td><td $bg><input type=\"hidden\" name=\"item_id\" value=\"$item_id\" /><input type=\"submit\" value=\"Update\" /></td></tr>";
		
		echo "</form>";
	
	} else { 
	
		echo "<td $bg>";
		if (!$_GET[item]) { echo "<a href=\"index2.php?page=project_checklist_edit&amp;proj_id=$proj_id&amp;item=$item_id\">+</a>"; }
		echo "</td>";
		echo "</tr>\n";
	
	
	}
	
	
		$group = $item_group;
		
		$current_item = $item_id;
		
		$item_counter++;
		
	
	}
	
	
	
	echo "</table>";
	
	
	
	if (!$_GET[item]) {
		echo "<input type=\"hidden\" value=\"$item_counter\" name=\"rows\" />";
		
		echo "<input type=\"submit\" />";
	}
	
	echo "</form>";
	
}


