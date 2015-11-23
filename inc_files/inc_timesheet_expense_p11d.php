<?php

if ($user_usertype_current <= 3 AND $_GET[user_id] != $_COOKIE[user]) { print "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; }

elseif ($user_usertype_current <= 3 AND $_GET[user_id] == NULL) { print "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; }

else {

	$sql_user = "SELECT * FROM intranet_user_details WHERE user_id = $_GET[user_id] LIMIT 1";
	$result_user = mysql_query($sql_user, $conn) or die(mysql_error());
	$array_user = mysql_fetch_array($result_user);
	$user_name_first = $array_user['user_name_first'];
	$user_name_second = $array_user['user_name_second'];

print "<h1>View P11d Expenses for $user_name_first $user_name_second</h1>";

// Determine the date a week ago

$date_lastweek = time() - 604800;

$sql = "SELECT * FROM intranet_timesheet_expense, intranet_user_details, intranet_projects WHERE ts_expense_user = '$_GET[user_id]' AND user_id = '$_GET[user_id]' AND ts_expense_invoiced = 0 AND proj_fee_track = 0 AND ts_expense_project = proj_id order by ts_expense_date";

$result = mysql_query($sql, $conn) or die(mysql_error());

if (mysql_num_rows($result) > 0) {

$counter = 0;

$proj_id_current == NULL;
$expense_total = 0;
$expense_total_verified = 0;

print "<form action=\"index2.php?page=timesheet_expense_p11d&amp;user_id=$_GET[user_id]\" method=\"post\">";

print "<table summary=\"List of P11d Items\">";
print "<tr><td colspan=\"2\" style=\"width: 25%;\"><strong>Date</strong></td><td><strong>Description</strong></td><td><strong>Value</strong></td><td colspan=\"2\"><strong>P11d</strong></td></tr>";

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
		$ts_expense_notes = $array['ts_expense_notes'];
		$ts_expense_p11d = $array['ts_expense_p11d'];
		$proj_id = $array['proj_id'];
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$proj_fee_track = $array['proj_fee_track'];
		
		if ($ts_expense_p11d > 0) { $bg = "style=\"background-color: #8EC799;\""; } else { $bg = NULL; }
		
		$ts_expense_vat_print = MoneyFormat($ts_expense_vat);
		
		print "<tr><td $bg>$counter</td><td $bg>".TimeFormat($ts_expense_date)."</td><td $bg><a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\">".$ts_expense_desc."&nbsp;[$ts_expense_id]</a>";
		
		if ($user_usertype_current > 3 AND $ts_expense_invoiced < 1) { print "&nbsp;<a href=\"index2.php?page=timesheet_expense_edit&amp;status=edit&amp;ts_expense_id=$ts_expense_id\"><img src=\"images/button_edit.png\" alt=\"Edit this entry\" /></a>"; }
		
		echo "</td><td $bg>$ts_expense_vat_print</td>";
		
		$expense_total = $expense_total + $ts_expense_vat;
		
		if ($ts_expense_p11d == 1) { $checked1 = "checked=\"checked\""; $checked2 = NULL; $expense_total_verified = $expense_total_verified + $ts_expense_vat;} else { $checked1 = NULL; $checked2 = "checked=\"checked\""; }
		
		print "<td $bg $rowspan><input type=\"radio\" value=\"1\" name=\"ts_expense_p11d[$counter]\" $checked1 /></td><td $bg><input type=\"radio\" value=\"0\" name=\"ts_expense_p11d[$counter]\" $checked2 />";
		
		print "<input type=\"hidden\" name=\"ts_expense_id[$counter]\" value=\"$ts_expense_id\" /></td></tr>";
		
		$counter++;
		
	}

		echo "<tr><td colspan=\"3\"><strong>P11d Total</strong></td><td colspan=\"3\" style=\"text-align:right;\"><strong>".MoneyFormat($expense_total_verified)."</strong></td></tr>";
	echo "<tr><td colspan=\"3\"><strong>TOTAL</strong></td><td colspan=\"3\" style=\"text-align:right;\"><strong>".MoneyFormat($expense_total)."</strong></td></tr>";
	

} else {

	print "<p>There are no P11d items for this user.</p>";

}

if ($user_usertype_current > 3) {
print "<tr><td colspan=\"8\"><input type=\"submit\" value=\"Amend P11d Items\" /></td></tr>";
}

print "</table>";

print "<input type=\"hidden\" name=\"ts_expense_user\" value=\"$_GET[user_id]\" /><input type=\"hidden\" name=\"action\" value=\"expense_p11d_verify\" />";

print "</form>";








}

?>
