<?php

if ($_GET[proj_id] != NULL) { $proj_id = intval($_GET[proj_id]); }

if ($proj_id != NULL) {

echo "<h1 class=\"heading_side\">Drawing Actions</h1>";

echo "<ul class=\"button_left\">";

echo "<li><a href=\"index2.php?page=drawings_list&amp;proj_id=$_GET[proj_id]\">Drawing List</a></li>";

echo "<li><a href=\"index2.php?page=drawings_edit&amp;proj_id=$_GET[proj_id]\">Add New Drawing</a></li>";

echo "<li><a href=\"index2.php?page=drawings_issue&amp;proj_id=$_GET[proj_id]\">Drawing Issue</a></li>";

}

echo "</ul>";


if ($_GET[proj_id] != NULL) {

	echo "<h1 class=\"heading_side\">Drawing Issues</h1>";
	
	echo "<ul class=\"button_left\">";
	
	$sql_issue_list = "SELECT set_id, set_date, set_reason FROM intranet_drawings_issued_set WHERE set_project = $proj_id order by set_date DESC, set_timestamp DESC LIMIT 20";
	
	//echo "<p>$sql_issue_list</p>";
	$result_issue_list = mysql_query($sql_issue_list, $conn) or die(mysql_error());
		while ($array_issue_list = mysql_fetch_array($result_issue_list)) {
			$set_id = $array_issue_list['set_id'];
			$set_reason = $array_issue_list['set_reason'];
			$set_date = TimeFormat($array_issue_list['set_date']);
			
			if ($set_id != $_GET[set_id]) { 			
				echo "<li><a href=\"index2.php?page=drawings_issue_list&amp;set_id=$set_id&proj_id=$proj_id\">$set_date - $set_reason</a>";
			} else {
				echo "<li>$set_date - $set_reason";
			}
			
			echo "&nbsp;<a href=\"pdf_drawing_issue.php?issue_set=$set_id&amp;proj_id=$proj_id\"><img src=\"images/button_pdf.png\" alt=\"PDF Issue Sheet\" /></a></li>";
			
		}
		
	echo "</ul>";


if ($_GET[page] != "drawings_list" && $_GET[proj_id] != NULL) {

	echo "<h1 class=\"heading_side\">Drawing List</h1>";
	echo "<ul class=\"button_left\">";
	
	$sql_drawing_list = "SELECT drawing_number, drawing_project, drawing_id, revision_letter FROM intranet_drawings LEFT JOIN intranet_drawings_revision ON revision_drawing = drawing_id WHERE drawing_project = $proj_id order by drawing_number, revision_letter DESC";
	$result_drawing_list = mysql_query($sql_drawing_list, $conn) or die(mysql_error());
	
	$current_drawing = 0;
	
		while ($array_drawing_list = mysql_fetch_array($result_drawing_list)) {
			$drawing_id = $array_drawing_list['drawing_id'];
			$drawing_number = $array_drawing_list['drawing_number'];
			$drawing_project = $array_drawing_list['drawing_project'];
			$revision_letter = $array_drawing_list['revision_letter'];
			if ($revision_letter != NULL) { $revision_letter = " Rev. " . strtoupper($revision_letter); } else { unset($revision_letter); }
			
			if ($drawing_id != $current_drawing) { 
			
								if ($drawing_id != $_GET[drawing_id]) { 
									echo "<li><a href=\"index2.php?page=drawings_detailed&amp;drawing_id=$drawing_id&amp;proj_id=$drawing_project\">$drawing_number$revision_letter</a></li>";
								} else {
									echo "<li>$drawing_number$revision_letter</li>";
								}
								
			}
			
			$current_drawing =$drawing_id;
		
		}
	
	echo "</ul>";
	
	
}


}

?>
