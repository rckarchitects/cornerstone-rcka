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
		
		// Send an email to the user if requested
		
		if ($_POST[message_email] == "yes") {
		
					$sql = "SELECT user_id, user_name_first, user_name_second FROM intranet_user_details WHERE user_id != '$message_for_user' LIMIT 1";
		
					$contact_name = $_GET[contact_name];
					$contact_address = $_GET[contact_address];
					$contact_number = $_GET[contact_number];
					$contact_email = $_GET[contact_email];
					$contact_message = $_GET[contact_message];

					$to = "contact@rcka.co.uk";

					$subject = "Telephone message from RCKa Intranet";
					$mailtxt = "Contact from RCKa Website\nName: $contact_name\nAddress: $contact_address\nEmail: $contact_email\nTelephone: $contact_number\nMessage:\n$contact_message";
					$headers="From: RCKa Website <" + $to + ">";

					 // mail($to, $subject, $mailtxt, $headers);
		
		}
		
		$result = mysql_query($sql_add, $conn) or die(mysql_error());
		$actionmessage = "Telephone message added successfully.";
		$techmessage = $sql_add;
}






}

?>
