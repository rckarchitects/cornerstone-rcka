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

echo "<h1>Project Checklist for <a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a></h1>";

echo "<p class=\"menu_bar\"><a href=\"pdf_project_checklist.php?proj_id=$proj_id\" class=\"menu_tab\">PDF <img src=\"images/button_pdf.png\" /></a><a href=\"index2.php?page=project_checklist_edit&amp;proj_id=$proj_id\" class=\"menu_tab\">Edit <img src=\"images/button_edit.png\" /></a>";

if ($showhidden == "yes") {
	echo "<a href=\"index2.php?page=project_checklist&amp;showhidden=no&amp;proj_id=$proj_id\" class=\"menu_tab\">Hide Hidden Items</a>";
} else {
	echo "<a href=\"index2.php?page=project_checklist&amp;showhidden=yes&amp;proj_id=$proj_id\" class=\"menu_tab\">Show Hidden Items</a>";
}

echo "</p>";


echo "<form action=\"index2.php\" method=\"get\">";
echo "<p><input type=\"hidden\" name=\"page\" value=\"project_checklist\" />";

$sql_switcher = "SELECT proj_id, proj_name, proj_num FROM intranet_projects WHERE proj_active > 0 ORDER BY proj_num DESC";
$result_switcher = mysql_query($sql_switcher, $conn) or die(mysql_error());
echo "<select onchange=\"this.form.submit()\" name=\"proj_id\">";
while ($array_switcher = mysql_fetch_array($result_switcher)) {
	$proj_id_switcher = $array_switcher['proj_id'];
	$proj_num_switcher = $array_switcher['proj_num'];
	$proj_name_switcher = $array_switcher['proj_name'];
	if ($_GET[proj_id] == $proj_id_switcher) { $select = " selected=\"selected\" "; } else { unset($select); }
	echo "<option value=\"$proj_id_switcher\" $select>$proj_num_switcher $proj_name_switcher</option>";
}
echo "</select>";
echo "</p>";


echo "</form>";






if ($_GET[display_order] == "stage") {
	
	if ($showhidden != "yes") { $sqlhidden = " AND checklist_required != 1 "; } else { unset($sqlhidden); }
	
	$sql_checklist = "SELECT * FROM intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id LEFT JOIN intranet_project_checklist_stages ON item_stage = stage_id AND checklist_project = $proj_id $sqlhidden ORDER BY stage_order, item_order, checklist_date";
	
	$sql_checklist = "SELECT * FROM intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id LEFT JOIN intranet_project_checklist_stages ON item_stage = stage_id AND checklist_project = $proj_id $sqlhidden ORDER BY stage_order, item_order, checklist_date";

} else {
	
	if ($showhidden != "yes") { $sqlhidden = " WHERE checklist_required != 1 "; } else { unset($sqlhidden); }

	$sql_checklist = "SELECT * FROM intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id AND checklist_project = $proj_id $sqlhidden ORDER BY item_group, item_order, checklist_date DESC, item_name";
	
}



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
echo "<tr><th>Item</th><th>Required</th><th style=\"width: 15%;\">Date Completed</th><th>Comment</th><th style=\"width: 15px;\"></th><th></th><th></th></tr>";

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
	
	$checklist_id = $array_checklist['checklist_id'];
	$checklist_required = $array_checklist['checklist_required'];
	$checklist_date	= $array_checklist['checklist_date'];
	$checklist_comment = htmlentities ( $array_checklist['checklist_comment']);
	$checklist_user = $_COOKIE[user];
	$checklist_link	= $array_checklist['checklist_link'];
	$checklist_item	= $array_checklist['checklist_item'];
	$checklist_timestamp = time();
	//$checklist_project = $proj_id;
	
	if ($item_group != $group) { echo "<tr><td colspan=\"7\"><strong>$item_group</strong></td></tr>"; }
	
		// Change the background color depending on status
		if ($checklist_required == 2 && ( $checklist_date == "0000-00-00" OR $checklist_date == NULL ) ) { $bg =  "style=\"background: rgba(255,0,0, 0.4); \""; } // red
		elseif ($checklist_required == 2 && ( $checklist_date != "0000-00-00" OR $checklist_date != NULL ) ) { $bg =  "style=\"background: rgba(0,255,0,0.4); \""; } // green
		elseif ($checklist_required == 1) { $bg =  "style=\"background: rgba(200,200,200, 0.4); \""; } // grey
		else { $bg =  "style=\"background: rgba(255,220,0, 0.4); \""; } // grey
	
	
	echo "<tr><td $bg>";
	//if ($item_name_current != $item_name) { 
	
	echo $item_name;

	$item_name_current = $item_name;
	echo "</td>";
	
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
			echo "<td><a href=\"javascript:void(0);\" onclick=\"hideRow($item_id, false);\">Help</a></td>";
		}
		
		echo "</tr>";
		
		echo "<tr id=\"$item_id\" style=\"display: $hidden;\"><td colspan=\"7\" style=\"padding: 12px; background: rgba(255,255,255,1);\">$item_notes</td>";
		
	} else { echo "<td></td>"; }
	
		echo "</tr>";

	
	$group = $item_group;
	
	$current_item = $item_id;

	}








}


echo "</table>";









?>