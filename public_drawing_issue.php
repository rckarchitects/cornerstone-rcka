<?php

include("inc_files/inc_action_functions.php");

$database_read = file_get_contents("secure/database.inc");
$database_read_array = explode("\n", $database_read);
$database_location = $database_read_array[0];
$database_username = $database_read_array[1];
$database_password = $database_read_array[2];
$database_name = $database_read_array[3];
$settings_style = $database_read_array[6];



$conn = mysql_connect("$database_location", "$database_username", "$database_password");
mysql_select_db("$database_name", $conn);

$settings_name = "Drawing History";

include_once("inc_files/inc_header.php");

if ($_GET[drawing_id] != NULL) {

$sql = "SELECT * FROM intranet_drawings, intranet_drawings_scale, intranet_drawings_paper, intranet_projects, intranet_user_details WHERE drawing_id = '$_GET[drawing_id]' AND drawing_scale = scale_id AND drawing_paper = paper_id AND proj_id = drawing_project AND drawing_author = user_id LIMIT 1";
$result = mysql_query($sql, $conn) or die(mysql_error());


		if (mysql_num_rows($result) > 0) {
		
		$array = mysql_fetch_array($result);
		$drawing_number = $array['drawing_number'];
		$drawing_id = $array['drawing_id'];
		$scale_desc = $array['scale_desc'];
		$paper_size = $array['paper_size'];
		$drawing_title = $array['drawing_title'];
		$drawing_author = $array['drawing_author'];
		$drawing_date = $array['drawing_date'];
		$proj_id = $array['proj_id'];
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$drawing_author = $array['user_name_first']."&nbsp;".$array['user_name_second'];
		
		if (md5($drawing_number) == $_GET[hash]) {
		
		echo "<h1>Drawing Details for $drawing_number</h1>";

		echo "<table summary=\"Lists the details for drawing $drawing_number\">";
		
		echo "<tr><td style=\"width: 25%;\"><strong>Project</strong></td><td>$proj_num $proj_name</td></tr>";
		
		echo "<tr><td><strong>Drawing Number</strong></td><td>$drawing_number";
				if ($drawing_author == $_COOKIE[user] OR $user_usertype_current > 1) {
				echo "&nbsp;<a href=\"index2.php?page=drawings_edit&amp;drawing_id=$drawing_id&amp;proj_id=$proj_id&amp;drawing_edit=yes\"><img src=\"images/button_edit.png\" alt=\"Edit this drawing\" /></a>";
				}
		echo "</td></tr>";
		
		echo "<tr><td><strong>Title</strong></td><td>".nl2br($drawing_title)."</td></tr>";
		
		echo "<tr><td><strong>Scale</strong></td><td>$scale_desc</td></tr>";
		
		echo "<tr><td><strong>Paper</strong></td><td>$paper_size</td></tr>";
		
		echo "<tr><td><strong>Author</strong></td><td>$drawing_author</td></tr>";
		
		echo "<tr><td><strong>Date</strong></td><td>" . TimeFormat($drawing_date) . "</td></tr>";

		echo "</table>";
		
	
		echo "<h2>Revision History</h2>";
		
		
		$sql_rev = "SELECT * FROM intranet_drawings_revision, intranet_user_details WHERE revision_drawing = '$_GET[drawing_id]' AND revision_author = user_id ORDER BY revision_date DESC";
		$result_rev = mysql_query($sql_rev, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result_rev) > 0) {

		echo "<table desc=\"Revision list for drawing $drawing_number\">
<tr><td><strong>Rev.</strong></td><td><strong>Date</strong></td><td><strong>Description</strong></td><td><strong>Author</strong></td></tr>";
		
		while ($array_rev = mysql_fetch_array($result_rev)) {
		$revision_id = $array_rev['revision_id'];
		$revision_letter = strtoupper($array_rev['revision_letter']);
		$revision_desc = nl2br($array_rev['revision_desc']);
		$revision_time = $array_rev['revision_date'];
		$revision_date = TimeFormat($revision_time);
		$revision_author = $array_rev['revision_author'];
		$revision_author_name = $array_rev['user_name_first']."&nbsp;".$array_rev['user_name_second'];
		
		echo "<tr><td>$revision_letter";
		
		
		echo "</td><td>$revision_date</td><td>$revision_desc</td><td>$revision_author_name</td></tr>";
		
		}
		
		print "</table>";
		
		
		} else {
		
		echo "<p>There are no revisions for this drawing.</p>";
		
		}

		
		
		// Drawing Issues
		
		
		
		//$sql_issued = "SELECT * FROM intranet_drawings_issued, intranet_drawings_issued_set, intranet_drawings LEFT JOIN intranet_drawings_revision ON revision_drawing = drawing_id WHERE issue_drawing = $drawing_id AND set_id = issue_set AND drawing_id = issue_drawing ORDER BY set_date DESC";
		
		$sql_issued = "SELECT * FROM intranet_drawings_issued_set, intranet_drawings, intranet_drawings_issued LEFT JOIN intranet_drawings_revision ON revision_id = issue_revision WHERE drawing_id = $drawing_id AND set_id = issue_set AND drawing_id = issue_drawing ORDER BY set_date DESC";
		
		$result_issued = mysql_query($sql_issued, $conn) or die(mysql_error());
		
		echo "<table>";
		
			if (mysql_num_rows($result_issued) > 0) { echo "<h2>Drawing Issues</h2>"; }
			
			while ($array_issued = mysql_fetch_array($result_issued)) {
			
				$set_date = $array_issued['set_date'];
				$revision_letter = strtoupper($array_issued['revision_letter']);
				$issue_set = $array_issued['issue_set'];
				$set_reason = $array_issued['set_reason'];
				
				if ($revision_letter == NULL) { $revision_letter = "-"; }
				
					echo "<tr><td>$revision_letter</td><td>" . TimeFormat($set_date) . "</td><td>$set_reason</td></tr>";
			
					}
			
		echo "</table>";
		
		
		
		
		

		} else {

		echo "<h1 class=\"heading_alert\">Access denied.</h1><p>You do not have sufficient rights to access this information.</p>";

		}
		
		} else {
		
		echo "<p>This drawing does not exist.</p>";
		
		}
	
} else {

echo "<p>No project selected.</p>";

}


		
?>