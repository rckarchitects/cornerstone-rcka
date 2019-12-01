<?php


function DrawingIssueAddNewSet() {
	
	global $conn;
	
			$set_date = AssessDays ($_POST['set_date'] );
			$issue_set = time();
			$set_user = intval($_COOKIE['user']);
		
			$total = 0;

			// Establish the two arrays from the submission page

			$set_method = addslashes($_POST['issue_method']);
			$set_format = addslashes($_POST['issue_format']);
			$set_comment = addslashes($_POST['issue_comment']);
			$set_project = intval ( $_POST['issue_project'] );
			$set_checked = intval($_POST['set_checked']);
			$set_timestamp = time();
			
			$count = 0;
			
			$array_issued_to_name = array();
			$array_issued_to_company = array();
			
			foreach ($_POST['contact_id'] AS $contact_issued_to) {
				
				if ($_POST['issue_to'][$count] == "yes") {
				
					$array_issued_to_name[] = intval($contact_issued_to);
					$array_issued_to_company[] = intval($_POST['company_id'][$count]);
					
					//echo "Person:" . intval($contact_issued_to) . ", Company: " . intval($_POST['company_id'][$count]) . "</p>";
				
				}
				
				$count++;
				
			}

			if ($_POST['issue_reason'] == NULL) { $set_reason = addslashes($_POST['issue_revision_other']); } else { $set_reason = addslashes($_POST['issue_reason']); }

			

			// First add the actual drawing set to the database

			$sql = "INSERT INTO intranet_drawings_issued_set (
								set_id,
								set_date,
								set_project,
								set_reason,
								set_user,
								set_comment,
								set_timestamp,
								set_method,
								set_format,
								set_checked
								) values (
								NULL,
								" . $set_date . ",
								" . $set_project . ",
								'" . $set_reason . "',
								" . $set_user . ",
								'" . $set_comment . "',
								" . $set_timestamp . ",
								'" . $set_method . "',
								'" . $set_format . "',
								" . $set_checked . "
								)";
								
			$result = mysql_query($sql, $conn) or die(mysql_error());
			
			//echo "<code>" . $sql . "</code>";

			$array_issued_set = array(mysql_insert_id());
			$output = array($array_issued_set,$array_issued_to_name, $array_issued_to_company);

			return $output;
			
}

$drawing_issue_set_array = DrawingIssueAddNewSet();

$set_id = $drawing_issue_set_array[0][0];
$set_issued_to_name = $drawing_issue_set_array[1];
$set_issued_to_company = $drawing_issue_set_array[2];
