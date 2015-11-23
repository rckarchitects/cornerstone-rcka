<?php

$list_length = 500;

if ($_GET[list_begin] == "") { $list_begin = 0; } else { $list_begin = $_GET[list_begin] ; }


if ($user_usertype_current <= 3 AND $_GET[user_id] != $_COOKIE[user]) { print "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; }

elseif ($user_usertype_current <= 3 AND $_GET[user_id] == NULL) { print "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; }

else {

print "<h1>View Expenses</h1>";

// Determine the date a week ago

$date_lastweek = time() - 604800;

if ($_GET[user_filter] > 0) { $user_filter = " AND ts_expense_user = '$user_filter' "; } else { $user_filter = NULL; }

if ($_GET[user_id] > 0) {

$sql = "SELECT * FROM intranet_projects, intranet_timesheet_expense LEFT JOIN intranet_timesheet_expense_category ON ts_expense_category = expense_cat_id WHERE ts_expense_project = proj_id AND ts_expense_user = '$_GET[user_id]' AND $user_usertype_current >= expense_cat_clearance AND ts_expense_verified = 0 ORDER BY proj_num, ts_expense_date LIMIT $list_begin, $list_length";

} else {

$sql = "SELECT * FROM intranet_projects, intranet_timesheet_expense LEFT JOIN intranet_timesheet_expense_category ON ts_expense_category = expense_cat_id  WHERE (ts_expense_project = proj_id AND ts_expense_verified = 0) OR (ts_expense_project = proj_id AND ts_expense_verified = NULL) OR (ts_expense_project = proj_id AND ts_expense_verified = '') $user_filter AND $user_usertype_current >= expense_cat_clearance ORDER BY proj_num, ts_expense_date LIMIT $list_begin, $list_length";

}

$result = mysql_query($sql, $conn) or die(mysql_error());



if (mysql_num_rows($result) > 0) {

		echo "<p>Viewing items ";
		if ($list_begin >= 20) { echo "<a href=\"index2.php?page=timesheet_expense_list&amp;list_begin=".($list_begin - $list_length)."\">(view previous $list_length)</a> "; }
		echo ($list_begin + 1)." to ";
		$list_limit = $list_length - 1;
		if (mysql_num_rows($result) > $list_limit) {
		echo ($list_begin + $list_length)." only ";
		echo "<a href=\"index2.php?page=timesheet_expense_list&amp;list_begin=".($list_begin + $list_length	)."\">(view next $list_length)</a>";
		} else {
		echo (mysql_num_rows($result) + $list_begin + 2).".";
		}
		echo "&nbsp;<a href=\"pdf_expense_claim.php?user_id=$user_id\"><img src=\"images/button_pdf.png\" alt=\"PDF expenses claim\" /></a></p>";

$counter = 1;

$proj_id_current == NULL;
$expense_total = 0;

print "<form action=\"index2.php?page=timesheet_expense_verify\" method=\"post\">";

print "<table summary=\"List of expenses for all projects\">";
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
		$ts_expense_notes = $array['ts_expense_notes'];
		$ts_expense_p11d = $array['ts_expense_p11d'];
		$proj_id = $array['proj_id'];
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$proj_fee_track = $array['proj_fee_track'];
		
		if ($ts_expense_reimburse > 0) { $ts_expense_vat_print = "<strong>".MoneyFormat($ts_expense_vat)."</strong>"; $bg = "style=\"background-color: white;\""; } else { $ts_expense_vat_print = MoneyFormat($ts_expense_vat); $bg = ""; }
		
	$sql_user = "SELECT user_id, user_initials FROM intranet_user_details WHERE user_id = '$ts_expense_user' LIMIT 1";
	$result_user = mysql_query($sql_user, $conn) or die(mysql_error());
	$array_user = mysql_fetch_array($result_user);
	$user_id = $array_user['user_id'];
	$user_initials = $array_user['user_initials'];
	$input = "<a href=\"\">".$array['user_initials']."</a>";
	
	if ($proj_fee_track == 1) { $proj_h2 = "<a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">".$proj_num."</a></td><td $bg>".$proj_name; } else {  $proj_h2 = $proj_num."</td><td $bg>".$proj_name; }
	
	if ($ts_expense_notes != NULL) { $rowspan = " rowspan=\"2\""; } else { $rowspan = ""; }
		
		if ($proj_id_current != $proj_id) {	print "<tr><td $rowspan $bg>".$proj_h2."</td>"; } else { $proj_id_current = $proj_id; }

		print "<td $bg>".TimeFormat($ts_expense_date)."</td><td $bg><a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\">".$ts_expense_desc."&nbsp;[$ts_expense_id]</a>";
		
		if ($user_usertype_current > 3 AND $ts_expense_invoiced < 1) { print "&nbsp;<a href=\"index2.php?page=timesheet_expense_edit&amp;status=edit&amp;ts_expense_id=$ts_expense_id\"><img src=\"images/button_edit.png\" alt=\"Edit this entry\" /></a>"; }
		
		print "</td><td $rowspan $bg><a href=\"index2.php?page=user_view&amp;user_id=$user_id\">$user_initials</a></td><td $rowspan $bg>".$ts_expense_vat_print."</td>";
		
		$expense_total = $expense_total + $ts_expense_vat;
		
		if ($user_usertype_current > 3 AND $ts_expense_verified == 0 AND $_GET[user_id] == "") { print "<td $bg $rowspan><input type=\"checkbox\" value=\"$ts_expense_id\" name=\"ts_expense_verified[]\" /></td>"; } elseif ( $ts_expense_verified == 0 ) { print "<td $rowspan $bg>No</td>"; } else { print "<td $rowspan $bg><a href=\"index2.php?page=datebook_view_day&amp;time=$ts_expense_verified\">".TimeFormat($ts_expense_verified)."</a></td>"; }
		
		print "<td $rowspan $bg>";
		
		if ($user_usertype_current > 3 AND $ts_expense_invoiced > 1) {
					$sql_invoice = "SELECT invoice_id, invoice_ref FROM intranet_timesheet_invoice WHERE invoice_id = $ts_expense_invoiced LIMIT 1";
					$result_invoice = mysql_query($sql_invoice, $conn) or die(mysql_error());
					$array_invoice = mysql_fetch_array($result_invoice);
					$invoice_id = $array_invoice['invoice_id'];
					$invoice_ref = $array_invoice['invoice_ref'];
					print "<a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">$invoice_ref</a>";
					}
					elseif ($ts_expense_p11d == 1) { echo "P11d"; }
				else { print "No";
					}
		
		print "<input type=\"hidden\" name=\"ts_expense_id[]\" value=\"$ts_expense_id\" /><input type=\"hidden\" name=\"ts_expense_desc[]\" value=\"$ts_expense_desc\" /></td></tr>";
		
		if ($ts_expense_notes != NULL) {
			print "<tr><td colspan=\"3\" $bg><span class=\"minitext\">Notes: ".PresentText($ts_expense_notes)."</span></td></tr>";
		}
		
	}

	print "<tr><td colspan=\"5\"><strong>TOTAL</strong></td><td colspan=\"3\" style=\"text-align:right;\"><strong>".MoneyFormat($expense_total)."</strong></td></tr>";
	

} else {

	if ($_GET[user_id] > 0) {

		print "<p>You have no outstanding expenses on the system.</p>";
	
	} else {
	
		print "<p>There are no expenses to verify.</p>";
	
	}
}

if ($user_usertype_current > 3 AND $_GET[user_id] == NULL) {
print "<tr><td colspan=\"8\"><input type=\"reset\" value=\"Clear\" />&nbsp;<input type=\"submit\" value=\"Verify expenses (".TimeFormat(time()).")\" /></td></tr>";
}

print "</table>";

print "</form>";








}

?>
