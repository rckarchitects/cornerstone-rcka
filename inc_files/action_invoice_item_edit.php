<?php

// Begin to clean up the $_POST submissions
$invoice_item_value_novat = CleanNumber($_POST[invoice_value_novat]);
$invoice_item_invoice = CleanNumber($_POST[invoice_item_invoice]);
$invoice_item_vat = CleanNumber($_POST[invoice_item_vat]);
$invoice_item_novat = CleanUp($_POST[invoice_item_novat]);
$invoice_item_stage = CleanNumber($_POST[invoice_item_stage]);
$invoice_item_desc = CleanUp($_POST[invoice_item_desc]);

$current_vat = ($settings_vat / 100) + 1;

if ($invoice_item_vat == 1) { $invoice_item_vat = $invoice_item_novat * $current_vat; } else { $invoice_item_vat = $invoice_item_novat; }

// Check the date input

if ($invoice_item_desc == NULL) { $alertmessage = "The description is empty."; $page = "timesheet_invoice_item_edit"; }

// Check that the required values have been entered, and alter the page to show if these values are invalid

else {

// Convert the date to a time

if ($_POST[invoice_item_id] != NULL) {

						$sql_edit = "UPDATE intranet_timesheet_invoice_item SET
						invoice_item_invoice = '$invoice_item_invoice',
						invoice_item_stage = '$invoice_item_stage',
						invoice_item_desc = '$invoice_item_desc',
						invoice_item_novat = '$invoice_item_novat',
						invoice_item_vat = '$invoice_item_vat'
						WHERE invoice_item_id = '$_POST[invoice_item_id]' LIMIT 1";

			$result = mysql_query($sql_edit, $conn) or die(mysql_error());
			$actionmessage = "Invoice $invoice_ref updated successfully.";
			$techmessage = $sql_edit;

} else {

		// Construct the MySQL instruction to add these entries to the database

						$sql_add = "INSERT INTO intranet_timesheet_invoice_item (
						invoice_item_id,
						invoice_item_invoice,
						invoice_item_stage,
						invoice_item_desc,
						invoice_item_novat,
						invoice_item_vat
						) values (
						'NULL',
						'$invoice_item_invoice',
						'$invoice_item_stage',
						'$invoice_item_desc',
						'$invoice_item_novat',
						'$invoice_item_vat'
						)";
			
			$result = mysql_query($sql_add, $conn) or die(mysql_error());
			$actionmessage = "Invoice $invoice_ref added successfully.";
			$techmessage = $sql_add;
									
		}

}

?>
