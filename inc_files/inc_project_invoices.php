<?php

if ($_POST[proj_id] != NULL) { $proj_id = $_POST[proj_id]; } elseif ($_GET[proj_id] != NULL) { $proj_id = $_GET[proj_id]; }

// Item Sub Menu
print "<p class=\"submenu_bar\">";
	if ($user_usertype_current > 2 OR $user_id_current == $proj_rep_black) {
		print "<a href=\"index2.php?page=project_edit&amp;status=edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Edit</a>";
	}
	if ($user_usertype_current > 2) {
		print "<a href=\"index2.php?page=timesheet_invoice_edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add Invoice</a>";
	}
	if ($user_usertype_current > 1) {
		print "<a href=\"index2.php?page=timesheet_invoice_items_edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add Invoice Item</a>";
	}
	
print "</p>";

print "<h2>View Invoices</h2>";


$sql = "SELECT * FROM intranet_timesheet_invoice WHERE invoice_project = '$proj_id' order by invoice_ref";
$result = mysql_query($sql, $conn) or die(mysql_error());

if (mysql_num_rows($result) > 0) {

print "<table summary=\"List of invoices for $proj_num $proj_name\">";

print "<tr><td><strong>Invoice Number</strong></td><td><strong>Issued</strong></td><td><strong>Due</strong></td><td><strong>Paid</strong></td></tr>";

$invoice_total_sub = 0;
$invoice_total_paid = 0;
$invoice_total_all = 0;

while ($array = mysql_fetch_array($result)) {

		$invoice_item_total = 0;
  
		$invoice_id = $array['invoice_id'];
		$invoice_date = $array['invoice_date'];
		$invoice_due = $array['invoice_due'];
		$invoice_project = $array['invoice_project'];
		$invoice_ref = $array['invoice_ref'];
		$invoice_paid = $array['invoice_paid'];
		$invoice_notes = $array['invoice_notes'];
		$invoice_baddebt = $array['invoice_baddebt'];
		$rowspan = 3;
		
		if ($invoice_date < time()) {
		$confirm = "onClick=\"javascript:return confirm('This item has been invoiced - are you sure you want to edit it?')\""; }
		else { unset($confirm); }
		
				// Pull the corresponding results from the Invoice Item list
				$sql2 = "SELECT * FROM intranet_timesheet_invoice_item WHERE invoice_item_invoice = '$invoice_id'";
				$result2 = mysql_query($sql2, $conn) or die(mysql_error());
				if (mysql_num_rows($result2) > 0) { $rowspan++; }
				// Pull the corresponding results from the Expenses List
				$sql3 = "SELECT ts_expense_value, ts_expense_vat FROM intranet_timesheet_expense WHERE ts_expense_invoiced = '$invoice_id'";
				$result3 = mysql_query($sql3, $conn) or die(mysql_error());
				if (mysql_num_rows($result3) > 0) { $rowspan++; }
		
		if (time() > $invoice_due AND $invoice_paid < 1) { $highlight = " style=\"background-color: #$settings_alertcolor\" "; $highlight2 = "style=\"background-color: #$settings_alertcolor; text-align: right;\"";  } else { $highlight = ""; $highlight2 = "style=\"text-align: right;\""; }
		
if ($invoice_baddebt == "yes") { echo "<tr><td colspan=\"4\" $highlight><strong>Listed as a bad debt</strong></td></tr>"; }
	
print "<tr>";	
print "<td $highlight rowspan=\"$rowspan\" style=\"width: 25%;\"><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">".$invoice_ref."</a>";
if ($user_usertype_current > 3) {print "&nbsp;<a href=\"index2.php?page=timesheet_invoice_edit&amp;status=edit&amp;invoice_id=$invoice_id\" $confirm><img src=\"images/button_edit.png\" alt=\"Edit Invoice\" /></a>"; }
if ($invoice_notes != NULL) { echo "<br />".TextPresent($invoice_notes); }
print "</td>";
print "<td $highlight>".TimeFormat($invoice_date)."</td>";
print "<td $highlight>".TimeFormat($invoice_due)."</td>";
if ($invoice_paid > 0) { print "<td $highlight>".TimeFormat($invoice_paid)."</td>"; } else { print "<td $highlight></td>"; }
print "</tr>";


		// Output the Invoice Item details
		if (mysql_num_rows($result2) > 0) {
			while ($array2 = mysql_fetch_array($result2)) {
			$invoice_item_novat = $array2['invoice_item_novat'];
			$invoice_item_vat = $array2['invoice_item_vat'];
			if ($invoice_paid > 0) { $invoice_total_paid = $invoice_total_paid + $invoice_item_novat; }
			$invoice_item_vat_total = $invoice_item_vat_total + $invoice_item_vat;
			$invoice_item_total = $invoice_item_total + $invoice_item_novat;
			$invoice_total_all = $invoice_total_all + $invoice_item_novat;
			$invoice_total_sub = $invoice_total_sub + $invoice_item_novat;
		}
			print "<tr><td colspan=\"2\" $highlight>Fees</td><td $highlight2>".MoneyFormat($invoice_item_total)."</td></tr>";
		}
		
				// Output the Expenses details
		if (mysql_num_rows($result3) > 0) {
			$invoice_expense_total = 0;
			while ($array3 = mysql_fetch_array($result3)) {
			$ts_expense_novat = $array3['ts_expense_novat'];
			$ts_expense_vat = $array3['ts_expense_vat'];
			$invoice_expense_total = $invoice_expense_total + $ts_expense_value;
			$invoice_item_vat_total = $invoice_item_vat_total + $ts_expense_vat;
			}
			print "<tr><td colspan=\"2\" $highlight>Expenses</td><td $highlight2>".MoneyFormat($invoice_expense_total)."</td></tr>";
			//$invoice_total_all = $invoice_total_all + $invoice_expense_total;
			$invoice_total_sub = $invoice_total_sub + $invoice_expense_total;
			// if ($invoice_paid > 0) { $invoice_total_paid = $invoice_total_paid + $invoice_expense_total; }
			}
			
print "<tr><td colspan=\"2\" $highlight>Sub Total</td><td $highlight2>".MoneyFormat($invoice_total_sub)."</td></tr>";
print "<tr><td colspan=\"2\" $highlight><u>Invoice Total</u> (gross, including expenses)</td><td $highlight2><u>".MoneyFormat($invoice_item_vat_total)."</u></td></tr>";
		
		$invoice_total_sub = 0;
		$invoice_item_vat_total = 0;


}

print "<tr><td colspan=\"3\"><strong>Issued (net, excluding expenses)</strong><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_total_all)."</strong></td></tr>";

print "<tr><td colspan=\"3\"><strong>Paid (net)</strong><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_total_paid)."</strong></td></tr>";

$invoice_outstanding = $invoice_total_all - $invoice_total_paid;

print "<tr><td colspan=\"3\"><strong>Outstanding (net)</strong><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_outstanding)."</strong></td></tr>";

print "</table>";

} else {

print "<p>There are no invoices on the system for this project.</p>";

}

?>
