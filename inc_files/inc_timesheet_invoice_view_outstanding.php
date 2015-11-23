<?php

if ($_GET[status] == "future") {

	$page_title = "Future Invoices";
	$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id AND invoice_paid = 0 AND invoice_date > ".time()." order by invoice_due";

} elseif ($_GET[status] == "paid") {

	$page_title = "Paid Invoices";
	$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id AND invoice_paid > 0 order by invoice_paid DESC";

} elseif ($_GET[status] == "current") {

	$page_title = "Current Unpaid Invoices";
	$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id AND invoice_paid = 0 AND invoice_date < ".time()." AND invoice_due > ".time()." order by invoice_due DESC";

} else {

	$page_title = "Outstanding Invoices";
	$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_baddebt != 'yes' AND invoice_project = proj_id AND invoice_paid = 0 AND invoice_due < ".time()." order by invoice_due DESC";

}

print "<h1>Invoices</h1>";

$result = mysql_query($sql, $conn) or die(mysql_error());
$result_page = mysql_query($sql, $conn) or die(mysql_error());

// Include a bar to navigate through the pages

		print "<p class=\"submenu_bar\">";

		$items_to_view = 25;

		if ($_GET[limit] == NULL) {$limit = 0; } else { $limit = $_GET[limit]; }
		$total_items = mysql_num_rows($result);
		$page_prev = $limit - $items_to_view;
		$page_next = $limit + $items_to_view;
		
		if ($limit > 0) { print "<a href=\"index2.php?page=timesheet_invoice_view_outstanding&amp;status=" . $_GET[status] ."&amp;limit=$page_prev\" class=\"submenu_bar\">Previous Page</a>"; }
		if ($page_next < $total_items) { print "<a href=\"index2.php?page=timesheet_invoice_view_outstanding&amp;status=" . $_GET[status] ."&amp;limit=$page_next\" class=\"submenu_bar\">Next Page</a>"; }
		print "</p>";

		echo "<h2>$page_title on ".TimeFormat(time()).",&nbsp;".($limit + 1)." to ";
		if ($page_next > $total_items) { print $total_items; } else { print $page_next; }
		print " of ".$total_items."</h2>";

$nowtime = time();

if (count($result) > 0) {

print "<table summary=\"List of $page_title\">";

print "<tr><td><strong>Invoice Number</strong></td><td><strong>Project Number</strong></td><td><strong>Issued</strong></td><td><strong>Due</strong></td><td><strong>Net</strong></td><td><strong>Value</strong><br /><span class=\"minitext\">(inc. VAT &amp; Expenses)</span></td></tr>";

$invoice_total_due = 0;
$invoice_total_net_thispage = 0;
$invoice_total_thispage = 0;
$invoice_total = 0;
$invoice_net_total = 0;
$invoice_notyetissued = 0;

$counter = 0;

while ($array = mysql_fetch_array($result)) {
  
		$invoice_id = $array['invoice_id'];
		$invoice_date = $array['invoice_date'];
		$invoice_due = $array['invoice_due'];
		$invoice_project = $array['invoice_project'];
		$invoice_ref = $array['invoice_ref'];
		$invoice_paid = $array['invoice_paid'];
		$invoice_baddebt = $array['invoice_baddebt'];
		$proj_id = $array['proj_id'];
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		
				if ($invoice_date > time() AND $invoice_notyetissued == 0) {
					if ($invoice_total_thispage > 0) {
						print "<tr><td colspan=\"4\"><strong>Total Issued</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($invoice_total_thispage)."</strong></td></tr>";
						}
					print "<tr><td colspan=\"6\"><strong>Not Yet Issued</strong></td></tr>"; $invoice_notyetissued = 1;
				}
		
										// Pull the corresponding results from the Invoice Item list
										$sql2 = "SELECT * FROM intranet_timesheet_invoice_item WHERE invoice_item_invoice = '$invoice_id'";
										$result2 = mysql_query($sql2, $conn) or die(mysql_error());
										// Pull the corresponding results from the Expenses List
										$sql3 = "SELECT ts_expense_vat FROM intranet_timesheet_expense WHERE ts_expense_invoiced = '$invoice_id'";
										$result3 = mysql_query($sql3, $conn) or die(mysql_error());
									
										$invoice_item_novat = 0;
										$invoice_item_total = 0;
										$invoice_expense_total = 0;
										$invoice_item_net_total = 0;
										
										// Output the Invoice Item details
										if (mysql_num_rows($result2) > 0) {
											while ($array2 = mysql_fetch_array($result2)) {
											$invoice_item_novat = $array2['invoice_item_novat'];
											$invoice_item_vat = $array2['invoice_item_vat'];
											$invoice_item_net_total = $invoice_item_net_total + $invoice_item_novat;
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

		$invoice_net_value =  $invoice_item_net_total;
		
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
		print "<td $highlight><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">".$invoice_ref."</a><br /><span class=\"minitext\">$proj_name</span>";
		if ($user_usertype_current > 3) {print "<br /><a href=\"index2.php?page=timesheet_invoice_edit&amp;status=edit&amp;invoice_id=$invoice_id\"><img src=\"images/button_edit.png\" alt=\"Edit Invoice\" /></a>"; }
		print "</td>";
		print "<td $highlight><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a></td>";
		print "<td $highlight><a href=\"index2.php?page=datebook_view_day&amp;time=$invoice_date\">".TimeFormat($invoice_date)."</a></td>";
		print "<td $highlight><a href=\"index2.php?page=datebook_view_day&amp;time=$invoice_due\">".TimeFormat($invoice_due)."</a></td>";
		print "<td style=\"text-align: right$highlight2\">".MoneyFormat($invoice_net_value)."</td><td style=\"text-align: right$highlight2\">".MoneyFormat($invoice_value)."</td></tr>";
		$invoice_total_net_thispage = $invoice_total_net_thispage + $invoice_net_value;	
		$invoice_total_thispage = $invoice_total_thispage + $invoice_value;
	}
	
$counter++;

$invoice_net_total = $invoice_net_total + $invoice_net_value;
$invoice_total = $invoice_total + $invoice_value;

}

$invoice_total_overdue = $invoice_total - $invoice_total_due;

print "<tr><td colspan=\"4\"><strong>Total This Page</strong><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_total_net_thispage)."</strong></td><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_total_thispage)."</strong></td></tr>";

print "<tr><td colspan=\"4\"><strong>Total Issued</strong><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_net_total)."</strong></td><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_total)."</strong></td></tr>";


print "</table>";

} else {

print "<p>There are no oustanding invoices on the system.</p>";

}

?>
