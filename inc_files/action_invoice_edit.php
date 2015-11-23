<?php

// Begin to clean up the $_POST submissions
$invoice_date_day = CleanNumber($_POST[invoice_date_day]);
$invoice_date_month = CleanNumber($_POST[invoice_date_month]);
$invoice_date_year = CleanNumber($_POST[invoice_date_year]);
$invoice_due_day = CleanNumber($_POST[invoice_due_day]);
$invoice_due_month = CleanNumber($_POST[invoice_due_month]);
$invoice_due_year = CleanNumber($_POST[invoice_due_year]);
$invoice_paid_day = CleanNumber($_POST[invoice_paid_day]);
$invoice_paid_month = CleanNumber($_POST[invoice_paid_month]);
$invoice_paid_year = CleanNumber($_POST[invoice_paid_year]);
$invoice_project = $_POST[invoice_project];
$invoice_ref = CleanUp($_POST[invoice_ref]);
$invoice_notes = CleanUp($_POST[invoice_notes]);
$invoice_text = CleanUp($_POST[invoice_text]);
$invoice_account = CleanNumber($_POST[invoice_account]);
$invoice_baddebt = CleanUp($_POST[invoice_baddebt]);
$invoice_client = CleanNumber($_POST[invoice_client]);
$invoice_purchase_order = CleanNumber($_POST[invoice_purchase_order]);

$proj_id = $invoice_project;

// Check the date input


if (checkdate($invoice_date_month, $invoice_date_day, $invoice_date_year) != TRUE) {
	$alertmessage = "The date entered is invalid."; $page = "timesheet_invoice_edit"; }

elseif (checkdate($invoice_due_month, $invoice_due_day, $invoice_due_year) != TRUE AND $_POST[invoice_due_type] == "manual") {
	$alertmessage = "The date entered is invalid."; $page = "timesheet_invoice_edit"; }
	
// elseif ($invoice_due <= $invoice_date AND $_POST[invoice_due_type] != "auto") { $alertmessage = "The Invoice due date is before the issue date of the invoice."; $page = "timesheet_invoice_edit"; }

elseif ($invoice_paid < $invoice_date) { $alertmessage = "The paid date is before the issue date of the invoice."; $page = "timesheet_invoice_edit"; }

// Check that the required values have been entered, and alter the page to show if these values are invalid

elseif ($invoice_ref == "") { $alertmessage = "The invoice reference was left empty."; $page = "timesheet_invoice_edit"; }

else {

// Convert the date to a time

	$invoice_date = mktime ( 12, 0, 0, $invoice_date_month, $invoice_date_day, $invoice_date_year );
	if ($invoice_paid_month != NULL AND $invoice_paid_day != NULL AND $invoice_paid_year != NULL) {
	$invoice_paid = mktime ( 12, 0, 0, $invoice_paid_month, $invoice_paid_day, $invoice_paid_year );}
	else { $invoice_paid = NULL; }

	if ($_POST[invoice_due_type] == "manual") { $invoice_due = mktime ( 12, 0, 0, $invoice_due_month, $invoice_due_day, $invoice_due_year ); } 		else { 	$invoice_due = $invoice_date + ($_POST[invoice_due_auto] * 86400);	}

if ($_POST[invoice_id] != NULL) {

						$sql_edit = "UPDATE intranet_timesheet_invoice SET
						invoice_date = '$invoice_date',
						invoice_due = '$invoice_due',
						invoice_paid = '$invoice_paid',
						invoice_project = '$invoice_project',
						invoice_ref = '$invoice_ref',
						invoice_notes = '$invoice_notes',
						invoice_text = '$invoice_text',
						invoice_account = '$invoice_account',
						invoice_baddebt = '$invoice_baddebt',
						invoice_purchase_order = '$invoice_purchase_order',
						invoice_client = '$invoice_client'
						WHERE invoice_id = '$_POST[invoice_id]' LIMIT 1";

			$result = mysql_query($sql_edit, $conn) or die(mysql_error());
			$actionmessage = "Invoice $invoice_ref updated successfully.";
			$techmessage = $sql_edit;

} else {

		// Construct the MySQL instruction to add these entries to the database

						$sql_add = "INSERT INTO intranet_timesheet_invoice (
						invoice_id,
						invoice_date,
						invoice_due,
						invoice_paid,
						invoice_project,
						invoice_ref,
						invoice_notes,
						invoice_text,
						invoice_account,
						invoice_baddebt,
						invoice_purchase_order,
						invoice_client
						) values (
						'NULL',
						'$invoice_date',
						'$invoice_due',
						'$invoice_paid',
						'$invoice_project',
						'$invoice_ref',
						'$invoice_notes',
						'$invoice_text',
						'$invoice_account',
						'',
						'$invoice_purchase_order',
						'$invoice_client'
						)";
			
			$result = mysql_query($sql_add, $conn) or die(mysql_error());
			$actionmessage = "Invoice $invoice_ref added successfully.";
			$techmessage = $sql_add;
									
		}

}

?>
