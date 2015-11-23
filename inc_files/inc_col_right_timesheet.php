<?php

// Timesheet Settings & Analysis

if ($user_usertype_current > 2) {
	print "<h1 class=\"heading_side\">Timesheet Administration</h1>";
}
print "<ul class=\"button_left\">";
if ($user_usertype_current > 2) { print "<li><a href=\"index2.php?page=timesheet_analysis\">Analysis Sheets</a></li>"; }
if ($user_usertype_current > 3) { print "<li><a href=\"index2.php?page=timesheet_settings\">Timesheet Settings</a></li>"; }
if ($user_usertype_current > 3) { print "<li><a href=\"index2.php?page=timesheet_rates_hourly\">Hourly Rates</a></li>"; }
if ($user_usertype_current > 3) { print "<li><a href=\"index2.php?page=timesheet_rates_overhead\">Overhead Rates</a></li>"; }
if ($user_usertype_current > 3) { print "<li><a href=\"index2.php?page=timesheet_rates_project\">Project Rates</a></li>"; }
print "</ul>";

// Expenses
if ($user_usertype_current > 2) {
print "<h1 class=\"heading_side\">Fees</h1>";
}
print "<ul class=\"button_left\">";
if ($user_usertype_current > 3) { print "<li><a href=\"index2.php?page=timesheet_fees_edit\">Add Fees</a></li>"; }	
if ($user_usertype_current > 2) { print "<li><a href=\"index2.php?page=timesheet_fees_view\">View Fees</a></li>"; }			
print "</ul>";

include_once("inc_files/inc_menu_search.php");

// Invoices

if ($user_usertype_current > 3 && $module_invoices == 1) {
echo "<h1 class=\"heading_side\">Invoices</h1>";
echo "<ul class=\"button_left\"><li>Quick Search - Enter Invoice Number<br /><form action=\"index2.php?page=timesheet_invoice_view\" method=\"post\"><input name=\"invoice_ref_find\" type=\"text\" /><br /><input type=\"submit\" /></form></li></ul>"; }

print "<ul class=\"button_left\">";
if ($user_usertype_current > 3 && $module_invoices == 1) { print "<li><a href=\"index2.php?page=timesheet_invoice\">Invoices</a></li>"; }	
if ($user_usertype_current > 3 && $module_invoices == 1) { print "<li><a href=\"index2.php?page=timesheet_invoice_edit\">Add Invoice</a></li>"; }
if ($user_usertype_current > 3 && $module_invoices == 1) { print "<li>Invoices<br />-&nbsp;<a href=\"index2.php?page=timesheet_invoice_view_outstanding&status=paid\">Paid Invoices</a>"; }
if ($user_usertype_current > 3 && $module_invoices == 1) { print "<br />-&nbsp;<a href=\"index2.php?page=timesheet_invoice_view_outstanding\">Oustanding Invoices</a>"; }	
if ($user_usertype_current > 3 && $module_invoices == 1) { print "<br />-&nbsp;<a href=\"index2.php?page=timesheet_invoice_view_outstanding&amp;status=current\">Current Invoices</a>"; }
if ($user_usertype_current > 3 && $module_invoices == 1) { print "<br />-&nbsp;<a href=\"index2.php?page=timesheet_invoice_view_outstanding&amp;status=future\">Future Invoices</a></li>"; }
if ($user_usertype_current > 3 && $module_invoices == 1) { print "<li><a href=\"index2.php?page=timesheet_invoice_view_month\">Invoices By Month</a>";
$nowyear = date("Y") + 1;

$nowyear = $nowyear - 1;

print "<br /><span class=\"minitext\">
<a href=\"http://intranet.rcka.co.uk/index2.php?page=timesheet_invoice_view_month&amp;month=1&amp;year=$nowyear&amp;type=date\">&nbsp;J&nbsp;</a>.
<a href=\"http://intranet.rcka.co.uk/index2.php?page=timesheet_invoice_view_month&amp;month=2&amp;year=$nowyear&amp;type=date\">&nbsp;F&nbsp;</a>.
<a href=\"http://intranet.rcka.co.uk/index2.php?page=timesheet_invoice_view_month&amp;month=3&amp;year=$nowyear&amp;type=date\">&nbsp;M&nbsp;</a>.
<a href=\"http://intranet.rcka.co.uk/index2.php?page=timesheet_invoice_view_month&amp;month=4&amp;year=$nowyear&amp;type=date\">&nbsp;A&nbsp;</a>.
<a href=\"http://intranet.rcka.co.uk/index2.php?page=timesheet_invoice_view_month&amp;month=5&amp;year=$nowyear&amp;type=date\">&nbsp;M&nbsp;</a>.
<a href=\"http://intranet.rcka.co.uk/index2.php?page=timesheet_invoice_view_month&amp;month=6&amp;year=$nowyear&amp;type=date\">&nbsp;J&nbsp;</a>.
<a href=\"http://intranet.rcka.co.uk/index2.php?page=timesheet_invoice_view_month&amp;month=7&amp;year=$nowyear&amp;type=date\">&nbsp;J&nbsp;</a>.
<a href=\"http://intranet.rcka.co.uk/index2.php?page=timesheet_invoice_view_month&amp;month=8&amp;year=$nowyear&amp;type=date\">&nbsp;A&nbsp;</a>.
<a href=\"http://intranet.rcka.co.uk/index2.php?page=timesheet_invoice_view_month&amp;month=9&amp;year=$nowyear&amp;type=date\">&nbsp;S&nbsp;</a>.
<a href=\"http://intranet.rcka.co.uk/index2.php?page=timesheet_invoice_view_month&amp;month=10&amp;year=$nowyear&amp;type=date\">&nbsp;O&nbsp;</a>.
<a href=\"http://intranet.rcka.co.uk/index2.php?page=timesheet_invoice_view_month&amp;month=11&amp;year=$nowyear&amp;type=date\">&nbsp;N&nbsp;</a>.
<a href=\"http://intranet.rcka.co.uk/index2.php?page=timesheet_invoice_view_month&amp;month=12&amp;year=$nowyear&amp;type=date\">&nbsp;D&nbsp;</a>
</span>";
}	
print "</li></ul>";

// Expenses

print "<h1 class=\"heading_side\">Expenses</h1>";
print "<ul class=\"button_left\">";
print "<li><a href=\"index2.php?page=timesheet_expense_edit\">Add Expenses</a></li>";
if ($user_usertype_current > 3) {
	print "<li><a href=\"index2.php?page=timesheet_expense_list\">Validate Expenses</a></li>";
	print "<li><a href=\"index2.php?page=timesheet_expense_analysis\">Expenses Analysis</a></li>";
	print "<li><a href=\"index2.php?page=timesheet_expense_validated\">Validated Expenses by Date</a></li>";
}

			if ($user_usertype_current > 3) {

			$sql = "SELECT ts_expense_date FROM intranet_timesheet_expense WHERE ts_expense_date > 0 order by ts_expense_date LIMIT 1";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			$array = mysql_fetch_array($result);
			$year_start = date("Y",$array['ts_expense_date']);
			$year_now = date("Y",time()) + 1;
			while ($year_now >= $year_start) {
			echo "<li><a href=\"index2.php?page=timesheet_expense_annual&amp;year=$year_now\">Analysis for ".($year_now - 1)."-".($year_now)."</a></li>";
			$year_now--;
			}

}



print "</ul>";
		
?>

