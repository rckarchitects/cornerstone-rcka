<?php


print "<h1>Invoices</h1>";

$nowtime = time();

if ($_GET[invoice_view] == "outstanding") {
$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id AND invoice_due < $nowtime AND invoice_paid = 0 order by invoice_date";
$invoice_view = "outstanding";
} elseif ($_GET[invoice_view] == "current") {
$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id AND invoice_date < $nowtime AND invoice_due > $nowtime AND invoice_paid = 0 order by invoice_date";
$invoice_view = "current";
} elseif ($_GET[invoice_view] == "future") {
$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id AND invoice_date > $nowtime order by invoice_date";
$invoice_view = "future";
} else {
$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id AND invoice_paid > 0 order by invoice_date";
$invoice_view = "paid";
}

$result = mysql_query($sql, $conn) or die(mysql_error());

// Include a bar to navigate through the pages

$page_title = ucfirst($invoice_view);

		print "<p class=\"submenu_bar\">";

		$items_to_view = 10;

		if ($_GET[limit] == NULL) {$limit = 0; } else { $limit = $_GET[limit]; }
		$total_items = mysql_num_rows($result);
		$page_prev = $limit - $items_to_view;
		$page_next = $limit + $items_to_view;
		
		if ($limit > 0) { print "<a href=\"index2.php?page=timesheet_invoice_view_list&amp;limit=$page_prev&amp;invoice_view=$invoice_view\" class=\"submenu_bar\">Previous Page</a>"; }
		if ($page_next < $total_items) { print "<a href=\"index2.php?page=timesheet_invoice_view_list&amp;limit=$page_next&amp;invoice_view=$invoice_view\" class=\"submenu_bar\">Next Page</a>"; }
		print "</p>";

		print "<h2>$page_title Invoices on ".TimeFormat(time()).",&nbsp;".($limit + 1)." to ";
		if ($page_next > $total_items) { print $total_items; } else { print $page_next; }
		print " of ".$total_items."</h2>";

$nowtime = time();

if (mysql_num_rows($result) > 0) {

print "<table summary=\"List of $page_title Invoices\">";

print "<tr><td><strong>Invoice Number</strong></td><td><strong>Project Number</strong></td><td><strong>Issued</strong></td><td><strong>Due</strong></td><td><strong>a/c</strong></td><td><strong>Value</strong><br /><span class=\"minitext\">(inc. VAT &amp; Expenses)</span></tr>";

$invoice_total_due = 0;
$invoice_total_thispage = 0;
$invoice_total = 0;

$counter = 0;

while ($array = mysql_fetch_array($result)) {
  
		$invoice_id = $array['invoice_id'];
		$invoice_date = $array['invoice_date'];
		$invoice_due = $array['invoice_due'];
		$invoice_project = $array['invoice_project'];
		$invoice_ref = $array['invoice_ref'];
		$invoice_paid = $array['invoice_paid'];
		$invoice_account = $array['invoice_account'];
		$proj_id = $array['proj_id'];
		$proj_num = $array['proj_num'];
		
										// Pull the corresponding results from the Invoice Item list
										$sql2 = "SELECT * FROM intranet_timesheet_invoice_item WHERE invoice_item_invoice = '$invoice_id'";
										$result2 = mysql_query($sql2, $conn) or die(mysql_error());
										// Pull the corresponding results from the Expenses List
										$sql3 = "SELECT ts_expense_vat FROM intranet_timesheet_expense WHERE ts_expense_invoiced = '$invoice_id'";
										$result3 = mysql_query($sql3, $conn) or die(mysql_error());
										
										$invoice_item_total = 0;
										$invoice_expense_total = 0;
										
										// Output the Invoice Item details
										if (mysql_num_rows($result2) > 0) {
											while ($array2 = mysql_fetch_array($result2)) {
											$invoice_item_vat = $array2['invoice_item_vat'];
											$invoice_item_total = $invoice_item_total + $invoice_item_vat;
											}
										}
										
										// Output the Expenses details
										if (mysql_num_rows($result3) > 0) {
											$invoice_expense_total = 0;
											while ($array3 = mysql_fetch_array($result3)) {
											$ts_expense_vat = $array3['ts_expense_vat'];
											$invoice_expense_total = $invoice_expense_total + $ts_expense_vat;
											}
										}
										
		$invoice_value = $invoice_item_total + $invoice_expense_total;
		
		if (time() > $invoice_due AND $invoice_paid < 1) {
			$highlight = " style=\"background-color: #$settings_alertcolor\" ";
			$highlight2 = "; background-color: #$settings_alertcolor";
			
		} else {
			unset($highlight);
			unset($highlight2);
			$invoice_total_due = $invoice_total_due + $invoice_value;
		}

if ($counter >= $limit AND $counter < $page_next) {
		print "<tr>";	
		print "<td $highlight><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">".$invoice_ref."</a>";
		if ($user_usertype_current > 3) { print "&nbsp;<a href=\"index2.php?page=timesheet_invoice_edit&amp;status=edit&amp;invoice_id=$invoice_id&amp;page_refer=timesheet_invoice_view_paid\"><img src=\"images/button_edit.png\" alt=\"Edit Invoice\" /></a>&nbsp;<a href=\"pdf_invoice.php?invoice_id=$invoice_id\"><img src=\"images/button_pdf.png\" alt=\"Print Invoice\" /></a>"; }
		print "</td>";
		print "<td $highlight><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a></td>";
		print "<td $highlight><a href=\"index2.php?page=datebook_view_day&amp;time=$invoice_date\">".TimeFormat($invoice_date)."</a></td>";
		print "<td $highlight><a href=\"index2.php?page=datebook_view_day&amp;time=$invoice_due\">".TimeFormat($invoice_due)."</a></td>";
		print "<td style=\"text-align: center$highlight2\">$invoice_account</td>";
		print "<td style=\"text-align: right$highlight2\">".MoneyFormat($invoice_value)."</td></tr>";
		$invoice_total_thispage = $invoice_total_thispage + $invoice_value;
	}
	
$counter++;

$invoice_total = $invoice_total + $invoice_value;

}

$invoice_total_overdue = $invoice_total - $invoice_total_due;

print "<tr><td colspan=\"5\"><strong>Total This Page</strong><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_total_thispage)."</strong></td></tr>";

print "<tr><td colspan=\"5\"><strong>Total $page_title</strong><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_total)."</strong></td></tr>";

$sql_acc = "SELECT * FROM intranet_account order by account_id";
$result_acc = mysql_query($sql_acc, $conn) or die(mysql_error());
if (mysql_num_rows($result_acc) > 0) {
	print "<tr><td colspan=\"6\"><p>Account Details:</p><p>";
	while ($array_acc = mysql_fetch_array($result_acc)) {
	$account_id = $array_acc['account_id'];
	$account_name = $array_acc['account_name'];
	print $account_id." - ".$account_name."<br />";
	}
	print "</p></td></tr>";
}

print "</table>";

} else {

print "<p>There are no oustanding invoices on the system.</p>";

}

?>