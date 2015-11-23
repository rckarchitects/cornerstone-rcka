<?php

if ($_GET[month] == NULL) { $month = date("n", time()); } else { $month = CleanNumber($_GET[month]); }
if ($_GET[year] == NULL) { $year = date("Y", time()); } else { $year = CleanNumber($_GET[year]); }

if ($_GET[type] != NULL) { $type = $_GET[type]; } else { $type = "date"; }

function DateTitle($month,$year) {
	$input = mktime(12,0,0,$month,15,$year);
	$input = date("F Y", $input);
	return $input;
}

$month_next = $month + 1;
if ($month_next > 12) { $month_next = 1; $year_next = $year + 1; }
else { $year_next = $year; }

$year_before = $year - 1;

$month_prev = $month - 1;
if ($month_prev < 1) { $month_prev = 12; $year_prev = $year - 1; }
else { $year_prev = $year; }

$month_begin = mktime(0,0,0,$month,1,$year);
$month_end = mktime(0,0,0,$month+1,1,$year);

$year_after = $year + 1;

print "<h1>Invoices</h1>";

if ($type == "due" ) { $type_switch = "date"; $type_title = "Invoiced Due"; $type_tab = "Invoices Issued"; } else { $type_switch = "due"; $type_title = "Invoices Issued"; $type_tab = "Invoices Due"; }

		print "<p class=\"menu_bar\">";
		print "<a href=\"index2.php?page=timesheet_invoice_view_month&amp;month=$month&amp;year=$year&amp;type=$type_switch\" class=\"menu_tab\">$type_tab</a>";
		print "</p>";

$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id AND invoice_$type BETWEEN $month_begin AND $month_end order by invoice_due";
$result = mysql_query($sql, $conn) or die(mysql_error());
$result_page = mysql_query($sql, $conn) or die(mysql_error());

// Include a bar to navigate through the months

		print "<p class=\"submenu_bar\">";
		print "<a href=\"index2.php?page=timesheet_invoice_view_month&amp;month=$month_prev&amp;year=$year_before&amp;type=$type\" class=\"submenu_bar\"><<&nbsp;".DateTitle($month_prev,$year_before)."</a>";
		print "<a href=\"index2.php?page=timesheet_invoice_view_month&amp;month=$month_prev&amp;year=$year_prev&amp;type=$type\" class=\"submenu_bar\"><&nbsp;".DateTitle($month_prev,$year_prev)."</a>";
		print "<a href=\"index2.php?page=timesheet_invoice_view_month&amp;type=$type\" class=\"submenu_bar\">This Month</a>";
		print "<a href=\"index2.php?page=timesheet_invoice_view_month&amp;month=$month_next&amp;year=$year_next&amp;type=$type\" class=\"submenu_bar\">".DateTitle($month_next,$year_next)."&nbsp;></a>";
		print "<a href=\"index2.php?page=timesheet_invoice_view_month&amp;month=$month_next&amp;year=$year_after&amp;type=$type\" class=\"submenu_bar\">".DateTitle($month_next,$year_after)."&nbsp;>></a>";
		print "</p>";

		print "<h2>$type_title for ".DateTitle($month,$year)."</h2>";

$nowtime = time();

if (count($result) > 0) {

print "<table summary=\"List of Invoices for ".DateTitle($month,$year)."\">";

print "<tr><td><strong>Invoice Number</strong></td><td><strong>Project Number</strong></td><td><strong>Issued</strong></td><td><strong>Due</strong></td><td><strong>Paid</strong></td><td><strong>Value</strong><br /><span class=\"minitext\">(inc. VAT &amp; Expenses)</span></tr>";

$invoice_total_due = 0;
$invoice_total_thispage = 0;
$invoice_total = 0;
$invoice_notyetissued = 0;

$counter = 0;

while ($array = mysql_fetch_array($result)) {
  
		$invoice_id = $array['invoice_id'];
		$invoice_date = $array['invoice_date'];
		$invoice_due = $array['invoice_due'];
		$invoice_project = $array['invoice_project'];
		$invoice_ref = $array['invoice_ref'];
		$invoice_paid = $array['invoice_paid'];
		$proj_id = $array['proj_id'];
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		

		
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

		print "<tr>";	
		print "<td $highlight><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">".$invoice_ref."</a>";
		if ($user_usertype_current > 3) {print "&nbsp;<a href=\"index2.php?page=timesheet_invoice_edit&amp;status=edit&amp;invoice_id=$invoice_id\"><img src=\"images/button_edit.png\" alt=\"Edit Invoice\" /></a>&nbsp;<a href=\">pdf_invoice.php?invoice_id=$invoice_id\"><img src=\"images/button_pdf.png\" alt=\"Print Invoice\" /></a>"; }
		print "<br /><span class=\"minitext\">$proj_name</a></td>";
		print "<td $highlight><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a></td>";
		print "<td $highlight><a href=\"index2.php?page=datebook_view_day&amp;time=$invoice_date\">".TimeFormat($invoice_date)."</a></td>";
		print "<td $highlight><a href=\"index2.php?page=datebook_view_day&amp;time=$invoice_due\">".TimeFormat($invoice_due)."</a></td>";
		print "<td $highlight>";
		if ($invoice_paid > 0) { print "<a href=\"index2.php?page=datebook_view_day&amp;time=$invoice_paid\">".TimeFormat($invoice_paid)."</a>"; } else { print "No"; }
		print "</td>";
		print "<td style=\"text-align: right$highlight2\">".MoneyFormat($invoice_value)."</td></tr>";
		$invoice_total_thispage = $invoice_total_thispage + $invoice_value;

	
$counter++;

$invoice_total = $invoice_total + $invoice_value;

}

$invoice_total_overdue = $invoice_total - $invoice_total_due;

print "<tr><td colspan=\"5\"><strong>Total This Page</strong><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_total_thispage)."</strong></td></tr>";

print "<tr><td colspan=\"5\"><strong>Total Invoices</strong><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_total)."</strong></td></tr>";


print "</table>";

} else {

print "<p>There are no oustanding invoices on the system.</p>";

}

?>
