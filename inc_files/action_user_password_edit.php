<?php

// Begin to clean up the $_POST submissions

	$password_old = md5($_POST[user_password]);
	$password_new1 = md5($_POST[user_password_new1]);
	$password_new2 = md5($_POST[user_password_new2]);
	
// Get the password details from the database

$sql = "SELECT user_password FROM intranet_user_details WHERE user_id = $user_id_current LIMIT 1";
$result = mysql_query($sql, $conn) or die(mysql_error());
$array = mysql_fetch_array($result);
$user_password = $array['user_password'];

// Check that the required values have been entered, and alter the page to show if these values are invalid

if ($user_password != $password_old) {
$alertmessage = "Youhave entered your existing password incorrectly."; $page_redirect = "user_password";
} elseif ($password_new1 != $password_new2) {
$alertmessage = "Your new passwords do not match. Please try again."; $page_redirect = "user_password";
} elseif ($_POST[user_password_new1] == "") {
$alertmessage = "Your new password is blank."; $page_redirect = "user_password";
}

else {

// Construct the MySQL instruction to add these entries to the database

		$sql_edit = "UPDATE intranet_user_details SET
		user_password = '$password_new1'
		WHERE user_id = '$user_id_current'
		LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Password changed successfully.";
		$techmessage = $sql_edit;		
		
}

?>
