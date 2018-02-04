<?php

// Check that the required values have been entered, and alter the page to show if these values are invalid

		// Begin to clean up the $_POST submissions

		$tasklist_project = $_POST[tasklist_project];
		$tasklist_status = $_POST[tasklist_status];
		$tasklist_fee = $_POST[tasklist_fee];
		$tasklist_notes = addslashes($_POST[tasklist_notes]);
		$tasklist_comment = addslashes($_POST[tasklist_comment]);
		$tasklist_updated = time();
		$tasklist_added = time();
		if ($_POST[tasklist_percentage] == 100) { $tasklist_completed = time(); } else { $tasklist_completed = "NULL"; }
		$tasklist_person = $_POST[tasklist_person];
		$tasklist_due = $_POST[tasklist_due];
		$tasklist_percentage = $_POST[tasklist_percentage];
		$tasklist_access = intval ( $_POST[tasklist_access] );
		$tasklist_id = intval ( $_POST[tasklist_id] );
		

		
	if ($_POST[tasklist_id] > 0) {
	
		$sql_edit = "UPDATE intranet_tasklist SET
		tasklist_project = '$tasklist_project',
		tasklist_contact = '$tasklist_contact',
		tasklist_fee = '$tasklist_fee',
		tasklist_notes = '$tasklist_notes',
		tasklist_updated = '$tasklist_updated',
		tasklist_person = '$tasklist_person',
		tasklist_comment = '$tasklist_comment',
		tasklist_percentage = '$tasklist_percentage',
		tasklist_completed = $tasklist_completed,
		tasklist_due = '$tasklist_due',
		tasklist_access = '$tasklist_access'
		WHERE tasklist_id = $tasklist_id LIMIT 1
		";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$techmessage = $sql_edit;
		$actionmessage = "<p>Task \'<a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=$tasklist_id\">". $tasklist_notes ."</a>\' edited successfully.</p>";
		AlertBoxInsert($_COOKIE[user],"Task Updated",$actionmessage,$tasklist_id,0,0);
	
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
		tasklist_percentage,
		tasklist_access
		) values (
		'NULL',
		'$tasklist_project',
		'$tasklist_contact',
		'$tasklist_fee',
		'$tasklist_notes',
		'',
		'$tasklist_added',
		NULL,
		'$tasklist_person',
		'$tasklist_due',
		'$tasklist_comment',
		'$tasklist_percentage',
		'$tasklist_access'
		)";
	
		$result = mysql_query($sql_add, $conn) or die(mysql_error());
		$techmessage = $sql_add;
		$tasklist_id = mysql_insert_id();
		
		$actionmessage = "<p>Task \'<a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=$tasklist_id\">". $tasklist_notes ."</a>\' added successfully.</p>";
		AlertBoxInsert($_COOKIE[user],"Task Added",$actionmessage,$tasklist_id,0,0);
		
	}