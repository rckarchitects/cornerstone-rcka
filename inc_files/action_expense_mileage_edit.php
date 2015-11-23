<?php

// Begin to clean up the $_POST submissions

$ts_expense_id = $_POST[ts_expense_id];
$ts_expense_project = $_POST[ts_expense_project];
$ts_expense_desc = CleanUp($_POST[ts_expense_desc]);
$ts_expense_date = $_POST[ts_expense_date];

$ts_expense_day = $_POST[ts_expense_day];
$ts_expense_month = $_POST[ts_expense_month];
$ts_expense_year = $_POST[ts_expense_year];

$ts_expense_vat = $ts_expense_value;


$ts_expense_user = $_COOKIE[user];
$ts_expense_verified = 0;
$ts_expense_invoiced = $_POST[ts_expense_invoiced];
$ts_expense_receipt = NULL;

// Check the date input

if (checkdate($ts_expense_month, $ts_expense_day, $ts_expense_year) != TRUE) {
	$alertmessage = "The date entered is invalid."; $page_redirect = "timesheet_expense_mileage_edit";
}

// Check that the required values have been entered, and alter the page to show if these values are invalid

elseif ($_POST[ts_expense_desc] == "") { $alertmessage = "The description was left empty."; $page_redirect = "timesheet_expense_edit"; }

elseif ($_POST[ts_expense_value] == "") { $alertmessage = "The expenses value was left empty."; $page_redirect = "timesheet_expense_edit"; }

else {

// Convert the date to a time

$ts_expense_date = mktime ( 12, 0, 0, $ts_expense_month, $ts_expense_day, $ts_expense_year );

// Construct the MySQL instruction to add these entries to the database

if ($ts_expense_id > 0) {

		$sql_edit = "UPDATE intranet_timesheet_expense SET
		ts_expense_project = '$ts_expense_project',
		ts_expense_value = '$ts_expense_value',
		ts_expense_date = '$ts_expense_date',
		ts_expense_desc = '$ts_expense_desc',
		ts_expense_user = '$ts_expense_user',
		ts_expense_vat = '$ts_expense_vat',
		ts_expense_receipt = '$ts_expense_receipt',
		ts_expense_invoiced = '$ts_expense_invoiced'
		WHERE ts_expense_id = '$ts_expense_id' LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Expense updated successfully.";
		$techmessage = $sql_edit;		
		
} else {

		$sql_add = "INSERT INTO intranet_timesheet_expense (
		ts_expense_id,
		ts_expense_project,
		ts_expense_value,
		ts_expense_date,
		ts_expense_desc,
		ts_expense_user,
		ts_expense_vat,
		ts_expense_invoiced,
		ts_expense_verified,
		ts_expense_receipt
		) values (
		'NULL',
		'$ts_expense_project',
		'$ts_expense_value',
		'$ts_expense_date',
		'$ts_expense_desc',
		'$ts_expense_user',
		'$ts_expense_vat',
		'$ts_expense_invoiced',
		'$ts_expense_verified',
		'$ts_expense_receipt'
		)";
		
		$result = mysql_query($sql_add, $conn) or die(mysql_error());
		$actionmessage = "Expenses added successfully.";
		$techmessage = $sql_add;
}






}

?>
