<?php

$ts_expense_verified = CleanUp($_GET[time]);

if ($user_usertype_current <= 3) { print "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; } else {

print "<h1>View Verified Expenses</h1>";

// Determine the date a week ago

$sql = "SELECT * FROM intranet_timesheet_expense, intranet_projects WHERE ts_expense_project = proj_id AND ts_expense_verified = '$ts_expense_verified' order by proj_num, ts_expense_date";
$result = mysql_query($sql, $conn) or die(mysql_error());

if (mysql_num_rows($result) > 0) {

$counter = 1;

$proj_id_current == NULL;
$expense_total = 0;

$p11d_total = 0;

print "<table summary=\"List of expenses verified\">";
print "<tr><td colspan=\"2\"><strong>Project</strong></td><td><strong>Date</strong></td><td><strong>Description</strong></td><td><strong>User</strong></td><td><strong>Value</strong></td><td><strong>Verified</strong></td><td><strong>Invoiced</strong></td></tr>";

while ($array = mysql_fetch_array($result)) {
  
		$ts_expense_project = $array['ts_expense_project'];
		$ts_expense_value = $array['ts_expense_value'];
		$ts_expense_date = $array['ts_expense_date'];
		$ts_expense_desc = htmlspecialchars($array['ts_expense_desc']);
		$ts_expense_user = UserDetails($array['ts_expense_user']);
		$ts_expense_verified = $array['ts_expense_verified'];
		$ts_expense_vat = $array['ts_expense_vat'];
		$ts_expense_id = $array['ts_expense_id'];
		$ts_expense_invoiced = $array['ts_expense_invoiced'];
		$ts_expense_reimburse = $array['ts_expense_reimburse'];
		$ts_expense_p11d = $array['ts_expense_p11d'];
		$proj_id = $array['proj_id'];
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$proj_fee_track = $array['proj_fee_track'];
		
		
		if ($ts_expense_reimburse > 0) { $ts_expense_vat_print = "<strong>".MoneyFormat($ts_expense_vat)."</strong>"; } else { $ts_expense_vat_print = MoneyFormat($ts_expense_vat); }
		
	$sql_user = "SELECT user_id, user_initials FROM intranet_user_details WHERE user_id = '$ts_expense_user' LIMIT 1";
	$result_user = mysql_query($sql_user, $conn) or die(mysql_error());
	$array_user = mysql_fetch_array($result_user);
	$user_id = $array_user['user_id'];
	$user_initials = $array_user['user_initials'];
	$input = "<a href=\"\">".$array['user_initials']."</a>";
	
	if ($proj_fee_track == 1) { $proj_h2 = "<a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">".$proj_num."</a></td><td>".$proj_name; } else {  $proj_h2 = $proj_num."</td><td>".$proj_name; }
		
		if ($proj_id_current != $proj_id) {	print "<tr><td>".$proj_h2."</td>"; } else { $proj_id_current = $proj_id; }
		
		if ($ts_expense_p11d == 1) { $p11d_total = $p11d_total + $ts_expense_vat; $ts_expense_desc = "<i>$ts_expense_desc</i>"; }

		print "<td>".TimeFormatBrief($ts_expense_date)."</td><td><a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\">".$ts_expense_desc."</a>&nbsp;<a href=\"index2.php?page=timesheet_expense_edit&amp;status=edit&amp;ts_expense_id=$ts_expense_id\"><img src=\"images/button_edit\" /></a></td><td><a href=\"index2.php?page=user_view&amp;user_id=$user_id\">$user_initials</a></td><td style=\"width: 10%; text-align: right;\">".$ts_expense_vat_print."</td>";
		
		$expense_total = $expense_total + $ts_expense_vat;
		
		if ($user_usertype_current > 3 AND $ts_expense_verified == 0) { print "<td style=\"width: 5%\"><input type=\"checkbox\" value=\"$ts_expense_id\" name=\"ts_expense_verified[]\" /></td>"; } elseif ( $ts_expense_verified == 0 ) { print "<td style=\"width: 5%\">No</td>"; } else { print "<td style=\"width: 5%\">".TimeFormatBrief($ts_expense_verified)."</td>"; }
		
		print "<td style=\"width: 5%\">";
		
		if ($user_usertype_current > 3 AND $ts_expense_invoiced > 1) {
					$sql_invoice = "SELECT invoice_id, invoice_ref FROM intranet_timesheet_invoice WHERE invoice_id = $ts_expense_invoiced LIMIT 1";
					$result_invoice = mysql_query($sql_invoice, $conn) or die(mysql_error());
					$array_invoice = mysql_fetch_array($result_invoice);
					$invoice_id = $array_invoice['invoice_id'];
					$invoice_ref = $array_invoice['invoice_ref'];
					print "<a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">$invoice_ref</a>";
					}
				elseif ($ts_expense_p11d == 1) { echo "Personal";
					}
				else { print "No";
					}
		
		print "<input type=\"hidden\" name=\"ts_expense_id[]\" value=\"$ts_expense_id\" /><input type=\"hidden\" name=\"ts_expense_desc[]\" value=\"$ts_expense_desc\" /></td></tr>";
		
	}

	print "<tr><td colspan=\"5\"><strong>TOTAL</strong></td><td colspan=\"3\" style=\"text-align:right;\"><strong>".MoneyFormat($expense_total)."</strong></td></tr>";
	
	$total_non_p11d = $expense_total - $p11d_total;
	
	echo "<tr><td colspan=\"8\"><i>Of which, " .MoneyFormat($p11d_total). " are personal expenses, the remaining ".MoneyFormat($total_non_p11d). " are office expenses.</td></tr>";
	

} else {

	print "<p>There are no expenses to verify.</p>";

}


print "</table>";




}

?>
