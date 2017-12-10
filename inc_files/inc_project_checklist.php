<?php

if ($_GET[item] > 0 && $user_usertype_current > 2) { $item = $_GET[item]; } else { unset($item); }

$proj_id = $_GET[proj_id];
$showhidden = $_GET[showhidden];

$sql_project = "SELECT proj_id, proj_num, proj_name FROM intranet_projects WHERE proj_id = $proj_id";
$result_project = mysql_query($sql_project, $conn) or die(mysql_error());
$array_project = mysql_fetch_array($result_project);
$proj_id = $array_project['proj_id'];
$proj_num = $array_project['proj_num'];
$proj_name = $array_project['proj_name'];

echo "<h2>Project Checklist</h2>";

if (!$_GET[group_id]) { $group_id = 1; } else { $group_id = intval($_GET[group_id]); }

echo "<div class=\"menu_bar\"><a href=\"pdf_project_checklist.php?proj_id=$proj_id\" class=\"menu_tab\">Checklist <img src=\"images/button_pdf.png\" /></a><a href=\"pdf_project_checklist_stages.php?proj_id=$proj_id\" class=\"menu_tab\">Stages <img src=\"images/button_pdf.png\" /></a><a href=\"index2.php?page=project_checklist_edit&amp;proj_id=$proj_id&amp;group_id=$group_id\" class=\"menu_tab\">Edit <img src=\"images/button_edit.png\" /></a>";

if ($showhidden == "yes") {
	echo "<a href=\"index2.php?page=project_checklist&amp;showhidden=no&amp;proj_id=$proj_id\" class=\"menu_tab\">Hide Hidden Items</a>";
} else {
	echo "<a href=\"index2.php?page=project_checklist&amp;showhidden=yes&amp;proj_id=$proj_id\" class=\"menu_tab\">Show Hidden Items</a>";
}

echo "</div>";



ProjectSwitcher ("project_checklist",$proj_id);



StageTabs($group_id, $proj_id, "index2.php?page=project_checklist&amp;proj_id=$proj_id&amp;");

	
	if ($showhidden != "yes") { $sqlhidden = " AND checklist_required != 1 "; } else { unset($sqlhidden); }

	//$sql_checklist = "SELECT * FROM intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id AND checklist_project = $proj_id LEFT JOIN intranet_timesheet_group ON group_id = item_stage  $sqlhidden  ORDER BY item_group, item_order, checklist_date, item_name";
	
	$sql_checklist = "SELECT * FROM intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id AND checklist_project = $proj_id LEFT JOIN intranet_timesheet_group ON group_id = item_stage WHERE ((group_id = '$group_id') OR (item_stage IS NULL)) $sqlhidden ORDER BY item_group, item_order, checklist_date, item_name";




//echo "<p>$sql_checklist</p>";

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



echo "<table>";
echo "<tr><th>Item</th><th>Stage</th><th>Required</th><th style=\"width: 15%;\">Date Completed</th><th colspan=\"4\">Comment</th></tr>";

$current_item = 0;

if (mysql_num_rows($result_checklist) > 0) {

	$group = NULL;

	while ($array_checklist = mysql_fetch_array($result_checklist)) {
	$item_id = $array_checklist['item_id'];
	$item_name = $array_checklist['item_name'];
	$item_date = $array_checklist['item_date'];
	$item_group = $array_checklist['item_group'];
	$item_required = $array_checklist['item_required'];
	$item_notes = $array_checklist['item_notes'];
	
	$group_code = $array_checklist['group_code'];
	
	$checklist_id = $array_checklist['checklist_id'];
	$checklist_required = $array_checklist['checklist_required'];
	$checklist_date	= $array_checklist['checklist_date'];
	$checklist_comment = htmlentities ( $array_checklist['checklist_comment']);
	$checklist_user = $_COOKIE[user];
	$checklist_link	= $array_checklist['checklist_link'];
	$checklist_item	= $array_checklist['checklist_item'];
	$checklist_timestamp = time();
	$checklist_deadline = $array_checklist['checklist_deadline'];
	//$checklist_project = $proj_id;
	
	if ($item_group != $group) { echo "<tr><td colspan=\"8\"><strong>$item_group</strong></td></tr>"; }
	
		// Change the background color depending on status
		if ($checklist_required == 2 && ( $checklist_date == "0000-00-00" OR $checklist_date == NULL ) ) { $bg =  "style=\"background: rgba(255,0,0, 0.4); \""; } // red
		elseif ($checklist_required == 2 && ( $checklist_date != "0000-00-00" OR $checklist_date != NULL ) ) { $bg =  "style=\"background: rgba(0,255,0,0.4); \""; } // green
		elseif ($checklist_required == 1) { $bg =  "style=\"background: rgba(200,200,200, 0.4); \""; } // grey
		else { $bg =  "style=\"background: rgba(255,220,0, 0.4); \""; } // grey
		
		
		
		
		if ($checklist_deadline != "0000-00-00" && $checklist_deadline != NULL) {
			$checklist_date = $checklist_date . "<br /><span class=\"minitext\">Deadline: $checklist_deadline</span>";
		}
	
	
	echo "<tr><td $bg>";
	//if ($item_name_current != $item_name) { 
	
	echo $item_name;

	$item_name_current = $item_name;
	echo "</td>";
	
	echo "<td $bg>$group_code</td>";
	
	echo "<td $bg>";
	
	if (!$item) {
	
		if ($checklist_required == 1) { echo "Not Required"; }
		elseif ($checklist_required == 2) { echo "Required"; }
		else { echo "?"; }
	
	}
	
	echo "</td>";

	if (!$item) {	
		if ($checklist_date == 0) { $checklist_date = "-";}
		echo "<td $bg>$checklist_date</td>";
		echo "<td $bg>$checklist_comment</td>";
		if ($checklist_link) {
			echo "<td colspan=\"2\" $bg><a href=\"$checklist_link\" target=\"_blank\"><img src=\"images/button_internet.png\" /></a></td>";
		} elseif ($_GET[item] == $item_id) {
			echo "<td colspan=\"3\"  $bg></td>";
		} else {
			echo "<td colspan=\"2\" $bg></td>";
		}
	}

	
	if ($item_notes != NULL) {
	
		if ($_GET[item] != $item_id AND $_POST[item] != $item_id) { $hidden = "none"; } else { unset($hidden); }
	
		if (!$item) {
			echo "<td $bg><a href=\"javascript:void(0);\" onclick=\"hideRow($item_id, false);\"><img src=\"images/button_help.png\" alt=\"Help\" /></a></td>";
		}
		
		echo "</tr>";
		
		echo "<tr id=\"$item_id\" style=\"display: $hidden;\"><td colspan=\"7\" style=\"padding: 12px; background: rgba(255,255,255,1);\">$item_notes</td>";
		
	} else { echo "<td $bg></td>"; }
	
		echo "</tr>";

	
	$group = $item_group;
	
	$current_item = $item_id;

	}








}


echo "</table>";









?>