<?php

// Begin to clean up the $_POST submissions

$ts_expense_id = $_POST[ts_expense_id];
$ts_expense_project = $_POST[ts_expense_project];
$ts_expense_value = round($_POST[ts_expense_value],2);
$ts_expense_date = $_POST[ts_expense_date];
$ts_expense_user = $_POST[ts_expense_user];

$ts_expense_day = $_POST[ts_expense_day];
$ts_expense_month = $_POST[ts_expense_month];
$ts_expense_year = $_POST[ts_expense_year];
$ts_expense_vat_check = $_POST[ts_expense_vat_check];
$vat_value_add = $_POST[vat_value_add];
$vat_value_included = $_POST[vat_value_included];


if ($ts_expense_vat_check == "add") {

				if ($vat_value_add > 0) { $current_vat = ($vat_value_add/100)+1; } else { $current_vat = ($settings_vat / 100) + 1; }

				$ts_expense_vat = $ts_expense_value * $current_vat;
	
} elseif ($ts_expense_vat_check == "included")  {
				if ($vat_value_included > 0) { $current_vat = ($vat_value_included/100)+1; } else { $current_vat = ($settings_vat / 100) + 1; }

				$ts_expense_vat = $ts_expense_value; $ts_expense_value = (1 / $current_vat) * $ts_expense_value;
	
} else {
	$ts_expense_vat = $ts_expense_value;
}

$ts_expense_vat = round($ts_expense_vat, 2);

$ts_expense_desc = CleanUp($_POST[ts_expense_desc]);
$ts_expense_verified = 0;
$ts_expense_invoiced = $_POST[ts_expense_invoiced];
$ts_expense_receipt = $_POST[ts_expense_receipt];
$ts_expense_reimburse = $_POST[ts_expense_reimburse];
$ts_expense_notes = CleanUp($_POST[ts_expense_notes]);
$ts_expense_category = CleanNumber($_POST[ts_expense_category]);

// Check the date input

if (checkdate($ts_expense_month, $ts_expense_day, $ts_expense_year) != TRUE) {
	$alertmessage = "The date entered is invalid."; $page_redirect = "timesheet_expense_edit";
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
		ts_expense_invoiced = '$ts_expense_invoiced',
		ts_expense_reimburse = '$ts_expense_reimburse',
		ts_expense_notes = '$ts_expense_notes',
		ts_expense_category = '$ts_expense_category',
		ts_expense_disbursement = '$ts_expense_disbursement',
		ts_expense_p11d = '$ts_expense_p11d'
		WHERE ts_expense_id = '$ts_expense_id' LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Expense edited successfully with ID <strong>$ts_expense_id</strong>. <a href=\"index2.php?page=timesheet_expense_edit&amp;status=edit&amp;ts_expense_id=$ts_expense_id\"><img src=\"images/button_edit.png\" alt=\"Edit\"></a>";
		$techmessage = $sql_edit;
		
} else {

// Determine user
if ($_POST[ts_expense_user] != NULL) { $ts_expense_user = $_POST[ts_expense_user]; } else { $ts_expense_user = $_COOKIE[user]; }

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
		ts_expense_receipt,
		ts_expense_reimburse,
		ts_expense_notes,
		ts_expense_category,
		ts_expense_disbursement,
		ts_expense_p11d
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
		'$ts_expense_receipt',
		'$ts_expense_reimburse',
		'$ts_expense_notes',
		'$ts_expense_category',
		'$ts_expense_disbursement',
		'$ts_expense_p11d'
		)";
		
		$result = mysql_query($sql_add, $conn) or die(mysql_error());
		$id_num = mysql_insert_id();
		$actionmessage = "Expense added successfully - with ID <strong>$id_num</strong>. <a href=\"index2.php?page=timesheet_expense_edit&status=edit&amp;ts_expense_id=$id_num\"><img src=\"images/button_edit.png\" alt=\"Edit\"></a>";
		$techmessage = $sql_add;
		
		$ts_expense_id = $id_num;
}

$page_redirect = "timesheet_expense_view";
$proj_id = $ts_expense_project;

}

?>
