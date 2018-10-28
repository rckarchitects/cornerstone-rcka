<?php

// Begin to clean up the $_POST submissions

	$message_id = CleanNumber($_POST[message_id]);
	$message_from_id = CleanNumber($_POST[message_from_id]);
	$message_from_name = CleanUpNames($_POST[message_from_name]);
	$message_from_company = CleanUpNames($_POST[message_from_company]);
	$message_from_number = CleanUp($_POST[message_from_number]);
	$message_for_user = CleanNumber($_POST[message_for_user]);
	$message_text = CleanUp($_POST[message_text]);
	$message_viewed = CleanNumber($_POST[message_viewed]);
	$message_date = time();
	$message_project = CleanNumber($_POST[message_project]);
	$message_taken = CleanNumber($_POST[message_taken]);

// Check that the required values have been entered, and alter the page to show if these values are invalid

if ($_POST[message_from_id] == "" AND $_POST[message_from_name] == "") { $alertmessage = "You have not entered the name of the caller."; $page_redirect = "phonemessage_edit"; }

elseif ($_POST[message_text] == "") { $alertmessage = "The message was left empty."; $page_redirect = "timesheet_expense_edit"; }

else {

// Construct the MySQL instruction to add these entries to the database

		if ($message_id > 0) {

				$sql_edit = "UPDATE intranet_phonemessage SET
				message_from_id = '$message_from_id',
				message_from_name = '$message_from_name',
				message_from_company = '$message_from_company',
				message_from_number = '$message_from_number',
				message_for_user = '$message_for_user',
				message_text = '$message_text',
				message_viewed = '$message_viewed',
				message_date = '$message_date',
				message_project = '$message_project',
				message_taken = '$message_taken'
				WHERE message_id = '$message_id'
				LIMIT 1";
				
				$result = mysql_query($sql_edit, $conn) or die(mysql_error());
				$actionmessage = "Telephone message updated successfully.";
				$techmessage = $sql_edit;		
				
		} else {

				$sql_add = "INSERT INTO intranet_phonemessage (
				message_id,
				message_from_id,
				message_from_name,
				message_from_company,
				message_from_number,
				message_for_user,
				message_text,
				message_viewed,
				message_date,
				message_project,
				message_taken
				) values (
				'NULL',
				'$message_from_id',
				'$message_from_name',
				'$message_from_company',
				'$message_from_number',
				'$message_for_user',
				'$message_text',
				'$message_viewed',
				'$message_date',
				'$message_project',
				'$message_taken'
				)";
				
				
				$result = mysql_query($sql_add, $conn) or die(mysql_error());
				$actionmessage = "Telephone message added successfully.";
				$techmessage = $sql_add;
		}






}
