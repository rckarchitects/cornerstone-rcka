<?php

print "<h1>Invoices</h1>";

if ($_POST[invoice_item_invoice] > 0) { $invoice_id = $_POST[invoice_item_invoice];
} elseif ($_GET[invoice_id] > 0) { $invoice_id = $_GET[invoice_id]; }

if ($_POST[invoice_ref_find] != NULL) {

$invoice_ref_find = CleanUp($invoice_ref_find);

$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id AND invoice_ref = '$invoice_ref_find' LIMIT 1";
$result = mysql_query($sql, $conn) or die(mysql_error());

} else {

$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id AND invoice_id = '$invoice_id' LIMIT 1";
$result = mysql_query($sql, $conn) or die(mysql_error());

}

if (mysql_num_rows($result) > 0) {


		$array = mysql_fetch_array($result);
  
		$invoice_id = $array['invoice_id'];
		$invoice_date = $array['invoice_date'];
		$invoice_due = $array['invoice_due'];
		$invoice_value_novat = $array['invoice_value_novat'];
		$invoice_value_vat = $array['invoice_value_vat'];
		$invoice_project = $array['invoice_project'];
		$invoice_ref = $array['invoice_ref'];
		$invoice_expenses = $array['invoice_expenses'];
		$invoice_expenses_description = TextPresent($array['invoice_expenses_description']);
		$invoice_paid = $array['invoice_paid'];
		$invoice_notes = TextPresent($array['invoice_notes']);
		$invoice_text = TextPresent($array['invoice_text']);
		$invoice_text = InvoiceDueDays($invoice_text, $invoice_due, $invoice_date);
		$invoice_account = $array['invoice_account'];
		$invoice_baddebt = $array['invoice_baddebt'];
		$invoice_purchase_order = $array['invoice_purchase_order'];
		$proj_id = $array['proj_id'];
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		
		$invoice_due_days = round(($array['invoice_due'] - $array['invoice_date']) / 86400);
		
		if ($invoice_paid < 1 AND $invoice_due < time()) { $option1 = " style=\"background-color: #$settings_alertcolor\""; }
		
		print "<p class=\"menu_bar\"><a href=\"index2.php?page=timesheet_invoice_edit\" class=\"menu_tab\">Add</a><a href=\"index2.php?page=timesheet_invoice_edit&amp;status=edit&amp;invoice_id=$invoice_id\" class=\"menu_tab\">Edit</a></p>";
		print "<p class=\"submenu_bar\">";
		if ($user_usertype_current > 1 AND $invoice_date > time()) {
			print "<a href=\"index2.php?page=timesheet_invoice_items_edit&amp;proj_id=$proj_id&amp;invoice_id=$invoice_id\" class=\"submenu_bar\"><img src=\"images/button_new.png\" alt=\"Add Invoice Item Button\" />&nbsp;&nbsp;Add Invoice Item</a>";
		}
		if ($user_usertype_current > 2) {
		print "<a href=\"pdf_invoice.php?invoice_id=$invoice_id\" target=\"_blank\" class=\"submenu_bar\"><img src=\"images/button_pdf.png\" alt=\"Print Invoice Button\" />&nbsp;&nbsp;Print</a>";
		}
		if ($user_usertype_current > 3 AND $invoice_date > time()) {
		print "<a href=\"index2.php?page=timesheet_invoice_view_project&amp;proj_id=$proj_id&amp;invoice_id=$invoice_id&amp;action=invoice_delete&amp;invoice_ref=$invoice_ref\" class=\"submenu_bar\" onClick=\"javascript:return confirm('Are you sure you want to delete this item?')\"><img src=\"images/button_delete.png\" alt=\"Delete Invoice Button\" />&nbsp;&nbsp;Delete Invoice</a>";
		}
	
print "</p>";
		print "<h2>Invoice $invoice_ref</h2>";
		print "<table summary=\"Invoice reference $invoice_ref\">";
		if ($invoice_baddebt == "yes") { echo "<tr><td colspan=\"2\"><strong>Listed as a bad debt</strong></td></tr>"; }
		print "<tr><td><strong>Project</strong></td><td><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">".$proj_num."&nbsp;".$proj_name."</a></td></tr>";
		print "<tr><td width=\"50%\"><strong>Invoice Issued</strong></td><td>".TimeFormat($invoice_date)."</td></tr>";
		print "<tr><td width=\"50%\"><strong>Client Purchase Order</strong></td><td>$invoice_purchase_order</td></tr>";
		print "<tr><td $option1><strong>Invoice Due</strong></td><td $option1>".TimeFormat($invoice_due)." (".$invoice_due_days." days)</td></tr>";
		print "<tr><td><strong>Invoice Paid</strong></td><td>";
		if ($invoice_paid > 0) { print TimeFormat($invoice_paid); } else { print "No"; }
		print "</td></tr>";

					// Pull the corresponding results from the Invoice Item list
					$sql2 = "SELECT * FROM intranet_timesheet_invoice_item WHERE invoice_item_invoice = '$invoice_id'";
					$result2 = mysql_query($sql2, $conn) or die(mysql_error());
					// Pull the corresponding results from the Expenses List
					$sql3 = "SELECT ts_expense_id, ts_expense_desc, ts_expense_vat, ts_expense_value FROM intranet_timesheet_expense WHERE ts_expense_invoiced = '$invoice_id'";
					$result3 = mysql_query($sql3, $conn) or die(mysql_error());
					$rowspan = mysql_num_rows($result2) + 2;		

					
					print "<tr><td colspan=\"2\"><strong>Invoice Details</strong></td></tr>";
		
					// Output the Invoice Item details
					if (mysql_num_rows($result2) > 0) {
						while ($array2 = mysql_fetch_array($result2)) {
						$invoice_item_id = $array2['invoice_item_id'];
						$invoice_item_desc = PresentText($array2['invoice_item_desc']);
						$invoice_item_novat = $array2['invoice_item_novat'];
						$invoice_item_vat = $array2['invoice_item_vat'];
						print "<tr><td>".$invoice_item_desc."";
						
						// Allow to edit if invoice hasn't been issued
						
						if ($invoice_date < time()) {
							$confirm = "onClick=\"javascript:return confirm('This item has been invoiced - are you sure you want to edit it?')\""; }
						
						if ($invoice_date > time() OR $user_usertype_current > 2 ) {
							print "&nbsp;<a href=\"index2.php?page=timesheet_invoice_items_edit&amp;invoice_item_id=$invoice_item_id&amp;proj_id=$proj_id\" $confirm><img src=\"images/button_edit.png\" alt=\"Edit Invoice\" /></a>";
						}
						print "</td><td style=\"text-align: right;\">".MoneyFormat($invoice_item_novat)."</td></tr>";
						$invoice_total_all = $invoice_total_all + $invoice_item_vat;
						$invoice_total_sub = $invoice_total_sub + $invoice_item_novat;
						}
					print "<tr><td><u>Fees Sub Total (excl. VAT)</u></td><td style=\"text-align: right;\"><u>".MoneyFormat($invoice_total_sub)."</u></td></tr>";
					}	

					print "<tr><td colspan=\"2\"><strong>Expenses Details</strong></td></tr>";
		
					// Output the Invoice Item details
					if (mysql_num_rows($result3) > 0) {
						while ($array3 = mysql_fetch_array($result3)) {
						$ts_expense_id = $array3['ts_expense_id'];
						$ts_expense_value = $array3['ts_expense_value'];
						$ts_expense_vat = $array3['ts_expense_vat'];
						$ts_expense_desc = TextPresent($array3['ts_expense_desc']);
						print "<tr><td><a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\">".$ts_expense_desc."</a>";
						if ($invoice_date > time()) {
							print "&nbsp;<a href=\"index2.php?page=timesheet_expense_edit&amp;status=edit&amp;ts_expense_id=$ts_expense_id\"><img src=\"images/button_edit.png\" alt=\"Edit Expense\" /></a>";
						}
						print "</td><td style=\"text-align: right;\">".MoneyFormat($ts_expense_value)."</td></tr>";
						$invoice_total_all = $invoice_total_all + $ts_expense_vat;
						$invoice_expense_total = $invoice_expense_total + $ts_expense_value;
						}						
					print "<tr><td><u>Expenses Sub Total (excl. VAT)</u></td><td style=\"text-align: right;\"><u>".MoneyFormat($invoice_expense_total)."</u></td></tr>";

					}	else { 				
					print "<tr><td>None</td><td style=\"text-align: right;\">--</td></tr>";
					}
					
					$vat_total = $invoice_total_all - ($invoice_expense_total + $invoice_total_sub);
		print "<tr><td><u>VAT</u></td><td style=\"text-align: right; \"><u>".MoneyFormat($vat_total)."</u></td></tr>";
		print "<tr><td><strong>INVOICE TOTAL (inc. VAT)</strong></td><td style=\"text-align: right; \"><strong>".MoneyFormat($invoice_total_all)."</strong></td></tr>";
		print "<tr><td><strong>Payment Instructions</strong></td><td>$invoice_text</td></tr>";
		print "<tr><td><strong>Notes</strong></td><td>$invoice_notes</td></tr>";
		print "</table>";

} else {

print "<p>The invoice you have requested does not exist.</p>";

}

?>