<?php

$set_id = intval($_GET[set_id]);

function DrawingsIssuedTo($set_id) {
	
				global $conn;
				$set_id = intval($set_id);

			$sql_contacts = "SELECT * FROM contacts_contactlist, intranet_contacts_project, intranet_drawings_issued
			LEFT JOIN contacts_companylist
			ON company_id = issue_company
			WHERE issue_set = $set_id
			AND issue_contact = contact_id
			ORDER BY company_name, contact_namesecond
			";

			$result_contacts = mysql_query($sql_contacts, $conn) or die(mysql_error());

			unset($current_contact);

			echo "<div><h3>Recipients</h3><table>";

			echo "<tr><th style=\"width: 25%\">Recipient</th><th>Company</th><th>Role</th></tr>";

			while ($array_contacts = mysql_fetch_array($result_contacts)) {

				$contact_name = $array_contacts['contact_namefirst'] . " " . $array_contacts['contact_namesecond'];
				$contact_id = $array_contacts['contact_id'];	
				$company_id = $array_contacts['company_id'];
				$company_name = $array_contacts['company_name'];
				$discipline_name = $array_contacts['discipline_name'];

					if ($current_contact != $contact_id) {
					
						echo "<tr><td><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">$contact_name</a></td><td><a href=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">$company_name</td><td>$discipline_name</td></tr>";
						
						$current_contact = $contact_id;
					
					}
				
				}
				
			echo "</table></div>";

}

function DrawingsIssued ($set_id) {
	
	global $conn;
	
	$set_id = intval($set_id);

$sql_drawings = "SELECT drawing_id,drawing_title,drawing_number,drawing_status,drawing_title,revision_letter,issue_id,issue_drawing,revision_date FROM intranet_drawings, intranet_drawings_issued LEFT JOIN intranet_drawings_revision ON revision_id = issue_revision WHERE drawing_id = issue_drawing AND drawing_project = issue_project AND issue_set = $set_id ORDER BY drawing_number";

$result_drawings = mysql_query($sql_drawings, $conn) or die(mysql_error());

		echo "<div><h3>Drawings</h3><table>";
		
		echo "<tr><th style=\"width:25%\">Number</th><th style=\"width:20%\">Rev.</th><th>Rev. Date</th><th>Status</th><th colspan=\"2\">Title</th></tr>";
		
		unset($current_drawing);
		
		unset($issue_id_prev);
		
		while ($array_drawings = mysql_fetch_array($result_drawings)) {

			$drawing_id = $array_drawings['drawing_id'];
			$drawing_title = $array_drawings['drawing_title'];
			$drawing_number = $array_drawings['drawing_number'];
			$drawing_status = $array_drawings['drawing_status'];
			$revision_letter = $array_drawings['revision_letter'];
			$issue_id = $array_drawings['issue_id'];
			$issue_drawing = $array_drawings['issue_drawing'];
			
			if (!$revision_letter) {
				$revision_letter = "-";
			}
			
			if (!$drawing_status) {
				$drawing_status = "-";
			}
			
			if ($revision_date > 0 ) {
			$revision_date = TimeFormat($array_drawings['revision_date']);
			} else {
			$revision_date = "-";
			}
			
			
			if ($current_drawing != $drawing_id) { 
				echo "<tr id=\"$issue_id\"><td><a href=\"index2.php?page=drawings_detailed&amp;drawing_id=$drawing_id&amp;proj_id=$proj_id\">$drawing_number</a><td>" . strtoupper($revision_letter) . "</td><td>$revision_date</td><td>$drawing_status</td><td>$drawing_title</td><td>" . $delete_button . "</td></tr>";
			}
			
			$current_drawing = $drawing_id;
			
			$issue_id_prev = $issue_id;
		
		}
		
		echo "</table></div>";
		
}

function DrawingsIssuedDetails($set_id) {
	
	global $conn;
	$set_id = intval($set_id);

// Drawing set details
		
		$sql_issued = "SELECT * FROM intranet_drawings_issued_set, intranet_user_details, intranet_projects WHERE set_id = $set_id AND set_user = user_id AND proj_id = set_project LIMIT 1";
		$result_issued = mysql_query($sql_issued, $conn) or die(mysql_error());
		$array_issued = mysql_fetch_array($result_issued);
		$set_id = $array_issued['set_id'];
		$set_date = $array_issued['set_date'];
		$print_date = TimeFormat($set_date);
		$set_reason = $array_issued['set_reason'];
		$set_method = $array_issued['set_method'];
		$set_format = $array_issued['set_format'];
		$set_comment = $array_issued['set_comment'];
		$user_name_first = $array_issued['user_name_first'];
		$user_name_second = $array_issued['user_name_second'];
		$user_usertype = $array_issued['user_usertype'];
		$proj_id = $array_issued['proj_id'];
		$proj_num = $array_issued['proj_num'];
		$proj_name = $array_issued['proj_name'];
		
	
		
		echo "<h2>Drawing Issue: $print_date (ID: $set_id)</h2>";
		
		ProjectSubMenu($proj_id,$user_usertype_current,"project_drawings",1);
		
		echo "<div class=\"menu_bar\"><a href=\"pdf_drawing_issue.php?issue_set=$set_id&amp;proj_id=$proj_id\" class=\"submenu_bar\"><img src=\"images/button_pdf.png\" alt=\"PDF Drawing Issue Sheet\" />&nbsp;Drawing Issue Sheet</a>";
		
		if (((time() - $set_date) < 172800) OR ($user_usertype > 3)) {
			echo "<a href=\"index2.php?page=drawings_list&amp;proj_id=$proj_id&amp;set_id=$set_id&amp;action=drawing_issue_delete\" class=\"submenu_bar\" onClick=\"javascript:return confirm('Are you sure you want to delete this drawing issue? Deleted drawings issues will be permanently deleted and cannot be recovered.')\">Delete Drawing Issue&nbsp;<img src=\"images/button_delete.png\" alt=\"Delete Drawing Issue\" /></a>";
		}
		
		
		echo "</div>";
		

			
			echo "<div class=\"page\"><h3>Project</h3>";
			echo "<a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a></fieldset>";
			
			echo "<div><h3>Details</h3>";
			
			echo "<table>";
			
			echo "<tr><th style=\"width: 20%;\">Date of issue</th><th style=\"width: 20%;\">Purpose</th><th style=\"width: 25%;\">Method</th><th style=\"width: 20%;\">Format</th><th style=\"width: 20%;\">Issued By</th></tr>";	
			echo "<tr><td><a href=\"index2.php?page=datebook_view_day&amp;time=$set_date\">$print_date</a></td><td>$set_reason</td><td>$set_method</td><td>$set_format</td><td>$user_name_first $user_name_second</td></tr>";
			
			if ($set_comment != NULL) {
					echo "<tr><th colspan=\"5\">Comment</th></tr>";
					echo "<tr><td colspan=\"5\">$set_comment</td></tr>";
			}

			
		echo "</table>";
		
		echo "</div>";
		
		return $proj_id;
		
}


if ($set_id > 0) {

	$proj_id = intval ( DrawingsIssuedDetails($set_id) );

	DrawingsIssued ($set_id);

	DrawingsIssuedTo($set_id);

} else {

	echo "<h1 class=\"alert\">Error</h1><p>This drawing issue does not exist.</p>";

}
