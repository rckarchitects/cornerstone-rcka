<?php

if ($_POST[date_type] == "invoice_paid") { $date_type = "invoice_paid"; }
elseif ($_POST[date_type] == "invoice_due") { $date_type = "invoice_due"; }
else { $date_type = "invoice_date"; }

include "inc_files/inc_checkcookie.php";

if ($user_usertype_current <= 2) { header ("Location: index2.php"); } else {


	echo "<table>";
		
		// Column headings

		echo "<tr><td>ID</td><td>Account</td><td>Project</td><td>Invoice Number</td><td>Invoice Date</td><td>Invoice Due</td><td>Invoice Paid</td><td>Fee (Net)</td><td>Fee (Gross)</td><td>Expense / Disbursement (Gross)</td><td>Invoice Paid (Gross)</td><td>Invoice Oustanding (Gross)</td><td>Invoice Balance (Gross)</td></tr>";
		
// Get the relevant infomation from the Invoice Database

	$invoice_outstanding_total = 0;

	$sql_invoice = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id ORDER BY $date_type, invoice_ref";
	$result_invoice = mysql_query($sql_invoice, $conn) or die(mysql_error());
	while ($array_invoice = mysql_fetch_array($result_invoice)) {
	
		$invoice_id = $array_invoice['invoice_id'];
		$invoice_ref = $array_invoice['invoice_ref'];
		$invoice_date = $array_invoice['invoice_date'];
		$invoice_due = $array_invoice['invoice_due'];
		$invoice_project = $array_invoice['invoice_project'];
		$invoice_paid = $array_invoice['invoice_paid'];
		$invoice_account = $array_invoice['invoice_account'];
		$invoice_baddebt = $array_invoice['invoice_baddebt'];
		$proj_name = $array_invoice['proj_name'];
		
		$invoice_month = date("n",$invoice_date);
		$invoice_year = date("Y",$invoice_date);
		
		$invoice_id_print = $invoice_id;
		$invoice_ref_print = strtoupper($invoice_ref);
		$invoice_date_print = TimeFormat($invoice_date);
		$invoice_due_print = TimeFormat($invoice_due);
		
		if ($invoice_paid > 0) { $invoice_paid_print = TimeFormat($invoice_paid); } else { $invoice_paid_print = "-"; }
		
		
		// Get the expenses from the schedule
		
			$sql_expense = "SELECT * FROM intranet_timesheet_expense WHERE ts_expense_invoiced = $invoice_id";
			$result_expense = mysql_query($sql_expense, $conn) or die(mysql_error());
			$expense_total = 0;
			while ($array_expense = mysql_fetch_array($result_expense)) {
			$ts_expense_vat = $array_expense['ts_expense_vat'];
			$expense_total = $expense_total + $ts_expense_vat;
			}
			
			$expense_total = round($expense_total,2);
		
		
		
// Set the correct font
	
	echo "<tr><td>$invoice_id_print</td><td>$invoice_account</td><td>$proj_name</td><td>$invoice_ref_print</td><td>$invoice_date_print</td><td>$invoice_due_print</td><td>$invoice_paid_print</td>";
	
	
// Get the invoice values for the invoice

	$invoice_item_novat = 0;
	$invoice_item_vat = 0;

	
	$sql_values = "SELECT invoice_item_novat, invoice_item_vat FROM intranet_timesheet_invoice_item WHERE invoice_item_invoice = '$invoice_id'";
	$result_values = mysql_query($sql_values, $conn) or die(mysql_error());
	
	while ($array_values = mysql_fetch_array($result_values)) {
		$invoice_item_novat = $array_values['invoice_item_novat'] + $invoice_item_novat;
		$invoice_item_vat = $array_values['invoice_item_vat'] + $invoice_item_vat;
	}
		$invoice_item_novat_print = $invoice_item_novat;
		$invoice_item_vat_print = $invoice_item_vat;
		
		$invoice_master_total = $expense_total + $invoice_item_vat;
		
		$invoice_outstanding_total = $invoice_outstanding_total + $invoice_master_total;
		
		echo "<td>$invoice_item_novat_print</td><td>$invoice_item_vat_print</td><td>$expense_total</td>";
		
		if ($invoice_paid > 0) { echo "<td>$invoice_master_total</td><td>0.00</td>"; }
		else { echo "<td>0.00</td><td>$invoice_master_total</td>";  }
		
		if ($invoice_paid > 0 AND $invoice_baddebt == NULL) { $invoice_outstanding_total = $invoice_outstanding_total - $invoice_master_total; }
		
		// Now add up where we are with outstanding money
		
		echo "<td>$invoice_outstanding_total<td></tr>";
		
}

echo "</table>";

}
?>
