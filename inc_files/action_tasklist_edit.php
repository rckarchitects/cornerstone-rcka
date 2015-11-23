<?php

// Check that the required values have been entered, and alter the page to show if these values are invalid

if ($_POST[tasklist_notes] == "") { $alertmessage = "The task description was left empty."; $status = "tasklist_edit"; $action = "add"; }

else {
	
		// Begin to clean up the $_POST submissions

		$tasklist_project = $_POST[tasklist_project];
		$tasklist_status = $_POST[tasklist_status];
		$tasklist_fee = $_POST[tasklist_fee];
		$tasklist_notes = CleanUp($_POST[tasklist_notes]);
		$tasklist_comment = CleanUp($_POST[tasklist_comment]);
		$tasklist_updated = time();
		$tasklist_added = time();
		$tasklist_completed = "";
		$tasklist_person = $_POST[tasklist_person];
		$tasklist_due = $_POST[tasklist_due];
		$tasklist_percentage = $_POST[tasklist_percentage];
		
	if ($_POST[tasklist_id] != NULL) {
	
		$sql_edit = "UPDATE intranet_tasklist SET
		tasklist_project = '$tasklist_project',
		tasklist_contact = '$tasklist_contact',
		tasklist_fee = '$tasklist_fee',
		tasklist_notes = '$tasklist_notes',
		tasklist_updated = '$tasklist_updated',
		tasklist_person = '$tasklist_person',
		tasklist_comment = '$tasklist_comment',
		tasklist_percentage = '$tasklist_percentage',
		tasklist_due = '$tasklist_due'
		WHERE tasklist_id = '$_POST[tasklist_id]' LIMIT 1
		";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Task updated successfully.";
		$techmessage = $sql_edit;
	
	
	} else {

		// Construct the MySQL instruction to add these entries to the database

		$sql_add = "INSERT INTO intranet_tasklist (
		tasklist_id,
		tasklist_project,
		tasklist_contact,
		tasklist_fee,
		tasklist_notes,
		tasklist_updated,
		tasklist_added,
		tasklist_completed,
		tasklist_person,
		tasklist_due,
		tasklist_comment,
		tasklist_percentage
		) values (
		'NULL',
		'$tasklist_project',
		'$tasklist_contact',
		'$tasklist_fee',
		'$tasklist_notes',
		'',
		'$tasklist_added',
		'$tasklist_completed',
		'$tasklist_person',
		'$tasklist_due',
		'$tasklist_comment',
		'$tasklist_percentage'
		)";
	
		$result = mysql_query($sql_add, $conn) or die(mysql_error());
		$actionmessage = "Task added successfully.";
		$techmessage = $sql_add;
		
	}

}

?>
