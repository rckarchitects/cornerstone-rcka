<?php

// Item Sub Menu
print "<p class=\"submenu_bar\">";

		print "<a href=\"index2.php?page=timesheet_expense_edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add Expenses</a>";

print "</p>";

print "<h2>Expenses</h2>";

$sql = "SELECT * FROM intranet_timesheet_expense WHERE ts_expense_project = '$proj_id' order by ts_expense_date";
$result = mysql_query($sql, $conn) or die(mysql_error());

if (mysql_num_rows($result) > 0) {

print "<table summary=\"List of expenses for $proj_num $proj_name\">";

print "<tr><td style=\"width: 25%;\"><strong>Date</strong></td><td><strong>Description</strong></td><td><strong>Verified</strong></td><td><strong>Invoiced</strong></td><td><strong>Value</strong></td></tr>";

$ts_expense_value_invoiced = 0;
$ts_expense_value_total = 0;

while ($array = mysql_fetch_array($result)) {
  
		$ts_expense_project = $array['ts_expense_project'];
		$ts_expense_value = $array['ts_expense_value'];
		$ts_expense_date = $array['ts_expense_date'];
		$ts_expense_desc = PresentText($array['ts_expense_desc']);
		$ts_expense_user = UserDetails($array['ts_expense_user']);
		$ts_expense_verified = $array['ts_expense_verified'];
		$ts_expense_vat = $array['ts_expense_vat'];
		$ts_expense_id = $array['ts_expense_id'];
		$ts_expense_invoiced = $array['ts_expense_invoiced'];
		
		if ( $ts_expense_verified == NULL) { $ts_expense_verified = 0; }
	
print "<tr>";	
print "<td>".TimeFormat($ts_expense_date)."</td>";

print "<td><a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\">".$ts_expense_desc."</a>";
	if (($user_usertype_current > 3 AND $ts_expense_verified == 0) OR ($ts_expense_user == $user_id_current AND $ts_expense_verified == 0) OR $proj_rep_black == $_COOKIE[user] AND $ts_expense_verified == 0) { print "&nbsp;<a href=\"index2.php?page=timesheet_expense_edit&amp;status=edit&amp;ts_expense_id=$ts_expense_id\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a>"; }
print "</td>";
	
	print "<td>";
	if ($ts_expense_verified > 0) { print "<a href=\"index2.php?page=timesheet_expense_list_verified&amp;time=$ts_expense_verified\">".TimeFormat($ts_expense_verified)."</a>"; } else { print "No"; }
	print "</td>";

// Invoice Details

	$sql2 = "SELECT invoice_id, invoice_ref FROM intranet_timesheet_invoice WHERE invoice_id = '$ts_expense_invoiced' LIMIT 1";
	$result2 = mysql_query($sql2, $conn) or die(mysql_error());
	$array2 = mysql_fetch_array($result2);
	$invoice_id = $array2['invoice_id'];
	$invoice_ref = $array2['invoice_ref'];
	print "<td>";
	if (mysql_num_rows($result2) > 0) { $ts_invoice_show = "<a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">$invoice_ref</a>"; } else { $ts_invoice_show = "No"; }
	if ($user_usertype_current > 2) { print $ts_invoice_show; } else { print $ts_invoice_ref; }
	print "</td>";
	
	print "<td style=\"text-align: right\">".MoneyFormat($ts_expense_vat)."</td></tr>";




$ts_expense_value_total = $ts_expense_value_total + $ts_expense_vat;
if ($ts_expense_invoiced > 1) { $ts_expense_value_invoiced = $ts_expense_value_invoiced + $ts_expense_vat; }

}

print "<tr><td colspan=\"4\"><strong>Invoiced</strong></td><td style=\"text-align: right\"><strong>".MoneyFormat($ts_expense_value_invoiced)."</strong></td></tr>";

$ts_expense_remaining = $ts_expense_value_total - $ts_expense_value_invoiced;

print "<tr><td colspan=\"4\"><strong>Outstanding</strong></td><td style=\"text-align: right\"><strong>".MoneyFormat($ts_expense_remaining)."</strong></td></tr>";

print "<tr><td colspan=\"4\"><strong>TOTAL</strong></td><td style=\"text-align: right\"><strong>".MoneyFormat($ts_expense_value_total)."</strong></td></tr>";


print "</table>";

} else {

print "<p>There are no expenses on the system for this project.</p>";

}

?>