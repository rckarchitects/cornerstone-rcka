<?php

function DrawingsIssuedList($proj_id) {
	
	$proj_id = intval($proj_id);
	
	if ($proj_id > 0) {
		
		echo "<table>";
		echo "<tr><th>Set #</th><th>Date of Issue</th><th>Reason for Issue</th><th>Issue Method</th><th>Format</th><th>Issued By</th><th>Checked By</th><th>Comment</th></tr>";
	
		global $conn;
		
		$sql_issue_list = "SELECT * FROM intranet_drawings_issued_set WHERE set_project = $proj_id order by set_date DESC, set_timestamp DESC";
		
		$result_issue_list = mysql_query($sql_issue_list, $conn) or die(mysql_error());
		
			while ($array_issue_list = mysql_fetch_array($result_issue_list)) {
				
				echo "<tr><td>" . $array_issue_list['set_id'] . "</td><td><a href=\"index2.php?page=datebook_view_day&amp;timestamp=" . $array_issue_list['set_date'] . "\">" . TimeFormat($array_issue_list['set_date']) . "</a></td><td>" . $array_issue_list['set_reason'] . "</td><td>" . $array_issue_list['set_method'] . "</td><td>" . $array_issue_list['set_format'] . "</td><td>" . UserDetails($array_issue_list['set_user']) . "</td><td>" . UserDetails($array_issue_list['set_checked']) . "</td><td>" . $array_issue_list['set_comment'] . "</td></tr>";
				
			}
			
		echo "</table>";
		
	} else {
		
		echo "<p>No project selected.</p>";
		
	}
		
	
}

echo "<h2>Drawing Issues</h2>";

ProjectSubMenu($proj_id,$user_usertype_current,"project_drawings",1);
DrawingsIssuedList($proj_id);

