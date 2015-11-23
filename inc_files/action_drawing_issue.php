<?php

$issue_date_day = CleanUp($_POST[issue_date_day]);
$issue_date_month = CleanUp($_POST[issue_date_month]);
$issue_date_year = CleanUp($_POST[issue_date_year]);

if (checkdate($issue_date_month, $issue_date_day, $issue_date_year) != TRUE) {
	$alertmessage = "The date entered is invalid."; $page_redirect = "drawings_detailed";
} else {

$issue_date_second = 0;
$issue_date_minute = 0;
$issue_date_hour = 12;
$issue_date = mktime ( $issue_date_hour, $issue_date_minute, $issue_date_second, $issue_date_month, $issue_date_day, $issue_date_year);

$issue_set = time();





$total = 0;

// Establish the two arrays from the submission page

$array_contact_id = $_POST['contact_id'];
$array_company_id = $_POST['company_id'];
$array_issue_to = $_POST['issue_to'];
$array_drawing = $_POST['drawing_id'];
$array_revision = $_POST['revision_id'];
$array_issued = $_POST['drawing_issued'];

$issue_method = $_POST['issue_method'];
$issue_format = $_POST['issue_format'];
$issue_comment = $_POST['issue_comment'];
$issue_project = $_POST['issue_project'];

if ($_POST[issue_reason] == NULL) { $issue_reason = CleanUp($_POST[issue_revision_other]); } else { $issue_reason = $_POST[issue_reason]; }

$issue_timestamp = time();

// First add the actual drawing set to the database

$sql_set = "INSERT INTO intranet_drawings_issued_set (
					set_id,
					set_date,
					set_project,
					set_reason,
					set_user,
					set_comment,
					set_timestamp,
					set_method,
					set_format
					) values (
					'NULL',
					'$issue_date',
					'$issue_project',
					'$issue_reason',
					'$_COOKIE[user]',
					'$issue_comment',
					'$issue_timestamp',
					'$issue_method',
					'$issue_format'					
					)";
					
$result_set = mysql_query($sql_set, $conn) or die(mysql_error());

$issue_set = mysql_insert_id();

	// Loop through each of the contacts selected
	
			$count = 0;
	
			while ($count < count($array_contact_id)) {
		
				$issue_contact = $array_contact_id[$count];
				$issue_company = $array_company_id[$count];
				$issue_to = $array_issue_to[$count];
				
				//echo "<p>Contact: $issue_contact, Company: $issue_company</p>";

					// Loop through each of the drawings and add a database entry for each
					
					$counter_drawing = 0;

							while ($counter_drawing < count($array_drawing)) {

							$issue_drawing = $array_drawing[$counter_drawing];
							$issue_revision = $array_revision[$counter_drawing];
							$issue_confirmed = $array_issued[$counter_drawing];
							
									
							$sql_add = "INSERT INTO intranet_drawings_issued (
								issue_id,
								issue_drawing,
								issue_revision,
								issue_project,
								issue_contact,
								issue_set,
								issue_company
							) values (
								'NULL',
								'$issue_drawing',
								'$issue_revision',
								'$issue_project',
								'$issue_contact',
								'$issue_set',
								'$issue_company'
							)";
							
							
						

							if ($issue_confirmed == "yes" && $issue_to == "yes") {
							$result = mysql_query($sql_add, $conn) or die(mysql_error());
							//echo "Drawing ID: " . $issue_drawing . " (" . $counter_drawing . "), " . $sql_add . "<br / >";
							}


							$counter_drawing++;
							
					}
			
			$counter_drawing = 0;
			$count++;
					
		}
		

$actionmessage = "The drawing issue was added successfully.";

}

?>