<?php

$condition_edit_id = intval ( $_POST[condition_id] );
$condition_edit_project = intval ( $_POST[condition_project] );
$condition_edit_ref = addslashes ( $_POST[condition_ref] );
$condition_edit_number = intval ( $_POST[condition_number] );
$condition_edit_decision_date = $_POST[condition_decision_date];
$condition_edit_type = $_POST[condition_type];
$condition_edit_text = strip_tags ( trim ( addslashes ( $_POST[condition_text] ) ) );
$condition_edit_responsibility = intval ( $_POST[condition_responsibility] );
$condition_edit_added_date = $_POST[condition_added_date];
$condition_edit_added_user = intval ( $_POST[condition_added_user] );
$condition_edit_note = strip_tags ( trim ( addslashes ( $_POST[condition_note] ) ) );
$condition_edit_submitted = $_POST[condition_submitted];
$condition_edit_approved = $_POST[condition_approved];
$condition_edit_link = trim ( addslashes ( $_POST[condition_link] ) );
$condition_edit_submitted_ref = trim ( addslashes ( $_POST[condition_submitted_ref]));




if ($condition_edit_id > 0) {

		$sql_edit = "UPDATE intranet_projects_planning SET
		condition_project = '$condition_edit_project',
		condition_ref = '$condition_edit_ref',
		condition_number = '$condition_edit_number',
		condition_decision_date = '$condition_edit_decision_date',
		condition_type = '$condition_edit_type',
		condition_text = '$condition_edit_text',
		condition_responsibility = '$condition_edit_responsibility',
		condition_added_date = '$condition_edit_added_date',
		condition_added_user = '$condition_edit_added_user',
		condition_note = '$condition_edit_note',
		condition_submitted = '$condition_edit_submitted',
		condition_approved = '$condition_edit_approved',
		condition_link = '$condition_edit_link',
		condition_submitted_ref = '$condition_edit_submitted_ref'
		WHERE condition_id = '$condition_edit_id'
		LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Drawing updated successfully.";
		$techmessage = $sql_edit;
		
		//echo "<p>$sql_edit</p>";

		$drawing_affected = mysql_affected_rows();		
		
} else {

		$sql_add = "INSERT INTO intranet_projects_planning (
		condition_id,
		condition_project,
		condition_ref,
		condition_number,
		condition_decision_date,
		condition_type,
		condition_text,
		condition_responsibility,
		condition_added_date,
		condition_added_user,
		condition_note,
		condition_submitted,
		condition_approved,
		condition_link,
		condition_submitted_ref
		) values (
		'NULL',
		'$condition_edit_project',
		'$condition_edit_ref',
		'$condition_edit_number',
		'$condition_edit_decision_date',
		'$condition_edit_type',
		'$condition_edit_text',
		'$condition_edit_responsibility',
		'$condition_edit_added_date',
		'$condition_edit_added_user',
		'$condition_edit_note',
		'$condition_edit_submitted',
		'$condition_edit_approved',
		'$condition_edit_link',
		'$condition_submitted_ref'
		)";
		
		//echo "<p>" . $sql_add . "</p>";
		$result = mysql_query($sql_add, $conn) or die(mysql_error());
		$actionmessage = "Planning condition added successfully.";
		$techmessage = $sql_add;
		
		$condition_edit_affected = mysql_affected_rows();
}







?>