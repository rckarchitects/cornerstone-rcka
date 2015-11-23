<?php

print "<h1>Expenses</h1>";

if ($id_num > 0) { $ts_expense_id = $id_num; }
elseif ($_GET[ts_expense_id] > 0) { $ts_expense_id = $_GET[ts_expense_id]; }

$sql = "SELECT * FROM intranet_user_details, intranet_timesheet_expense LEFT JOIN intranet_timesheet_expense_category ON ts_expense_category = expense_cat_id WHERE ts_expense_id = '$ts_expense_id' AND ts_expense_user = user_id AND expense_cat_clearance <= $user_usertype_current LIMIT 1";
$result = mysql_query($sql, $conn) or die(mysql_error());

if (mysql_num_rows($result)) {


		$array = mysql_fetch_array($result);
  
		$ts_expense_id = $array['ts_expense_id'];
		$ts_expense_project = $array['ts_expense_project'];
		$ts_expense_value = $array['ts_expense_value'];
		$ts_expense_date = $array['ts_expense_date'];
		$ts_expense_desc = $array['ts_expense_desc'];
		$ts_expense_user = $array['ts_expense_user'];
		$ts_expense_verified = $array['ts_expense_verified'];
		$ts_expense_vat = $array['ts_expense_vat'];
		$ts_expense_invoiced = $array['ts_expense_invoiced'];
		$ts_expense_receipt = $array['ts_expense_receipt'];
		$ts_expense_reimburse = $array['ts_expense_reimburse'];
		$ts_expense_notes = $array['ts_expense_notes'];
		$ts_expense_p11d = $array['ts_expense_p11d'];
		$user_name_first = $array['user_name_first'];
		$user_name_second = $array['user_name_second'];
		$user_name_second = $array['user_name_second'];
		$user_id = $array['user_id'];
		$expense_cat_name = $array['expense_cat_name'];
		
		print "<p class=\"menu_bar\">";
		print "<a href=\"index2.php?page=timesheet_expense_edit\" class=\"menu_tab\">Add New</a>";
			if ($user_usertype_current > 3) {
		print "<a href=\"index2.php?page=timesheet_expense_edit&amp;status=edit&amp;ts_expense_id=$ts_expense_id\" class=\"menu_tab\">Edit</a>";
		print "<a href=\"index2.php?page=timesheet_expense_list\" class=\"menu_tab\">Verify Expenses</a>"; 
		}
		
		print "<p class=\"submenu_bar\">";
		if ($ts_expense_id > 1 AND $user_usertype_current > 3) { print "<a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=".($ts_expense_id - 1)."\" class=\"submenu_bar\"><< Previous</a>"; }
		if ($user_usertype_current > 3 AND $ts_expense_verified == 0 OR $ts_expense_user == $user_id_current AND $ts_expense_verified == 0) {
		print "<a href=\"index2.php?page=timesheet_expense_edit&amp;status=edit&amp;ts_expense_id=$ts_expense_id\" class=\"submenu_bar\">Edit</a>";
		print "<a href=\"index2.php?page=timesheet_expense_list&amp;action=expense_delete&amp;ts_expense_id=$ts_expense_id\" class=\"submenu_bar\" onClick=\"javascript:return confirm('Are you sure you want to delete this entry?')\">Delete</a>"; }
		if ($user_usertype_current > 3) {
		print "<a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=".($ts_expense_id + 1)."\" class=\"submenu_bar\">Next >></a>";
		}
		
		print "</p>";
		
		if ($user_id == $_COOKIE[user] OR $user_usertype_current > 3) {
		
		print "<h2>View Expenses - ID Ref. $ts_expense_id</h2>";
		print "<table summary=\"View expense entry\">";
		print "<tr><td><strong>Expense Date</strong></td><td style=\"width: 75%;\"><a href=\"index2.php?page=datebook_view_day&amp;time=$ts_expense_date\">".TimeFormat($ts_expense_date)."</a></td></tr>";
		if ($ts_expense_desc != NULL) { print "<tr><td colspan=\"2\">".nl2br($ts_expense_desc)."</td></tr>"; }
		if ($ts_expense_notes != "") { echo "<tr><td colspan=\"2\"><strong>Notes:&nbsp;</strong>".nl2br($ts_expense_notes)."</td></tr>"; }
		echo "<tr><td><strong>Category:</strong></td><td>";
		if ($expense_cat_name != NULL) { echo $expense_cat_name; } else { echo "-- None --"; }
		echo "</td></tr>";
		print "<tr><td><strong>User</strong></td><td>";
		if ($ts_expense_user > 0) { print $user_name_first."&nbsp;".$user_name_second; } else { print "n/a"; }
		print "</td></tr>";
		print "<tr><td><strong>Reimbursable Expense</strong></td><td>";
		if ($ts_expense_reimburse > 0) { print "Yes"; } else { print "No"; }
		print "</td></tr>";
		print "<tr><td><strong>P11d Item</strong></td><td>";
		if ($ts_expense_p11d == 1) { print "Yes"; } else { print "No"; }
		print "</td></tr>";
		print "<tr><td><strong>Expense Verified</strong></td><td>";
		if ($ts_expense_verified > 0) { print "<a href=\"index2.php?page=datebook_view_day&amp;time=$ts_expense_verified\">".TimeFormat($ts_expense_verified)."</a> <a href=\"index2.php?page=timesheet_expense_list_verified&amp;time=$ts_expense_verified\">[View Set]</a>"; } else { print "No"; }
		print "</td></tr>";
		print "<tr><td><strong>Expense Invoiced</strong></td><td>";
		if ($ts_expense_invoiced > 0) {
		
			$sql_invoice = "SELECT invoice_id, invoice_ref FROM intranet_timesheet_invoice WHERE invoice_id = '$ts_expense_invoiced' LIMIT 1";
			$result_invoice = mysql_query($sql_invoice, $conn) or die(mysql_error());
			$array_invoice = mysql_fetch_array($result_invoice);
			$invoice_id = $array_invoice['invoice_id'];
			$invoice_ref = $array_invoice['invoice_ref'];
			echo "<a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">$invoice_ref</a>";
		
		} else { print "No"; }
		print "</td></tr>";
		print "<tr><td><strong>Receipt Available?</strong></td><td>";
		if ($ts_expense_receipt == "1") { print "Yes"; } else { print "No"; }
		print "</td></tr>";
		print "<tr><td><strong>Expense Amount </strong>(exc. VAT)</td><td>".MoneyFormat($ts_expense_value)."</td></tr>";
		print "<tr><td><strong>Expense Amount </strong>(inc. VAT)</td><td>".MoneyFormat($ts_expense_vat)."</td></tr>";
		print "</table>";
		
		} else { print "<p><strong>You are not authorised to view this page.</strong></p>"; } 

} else {

print "<p><strong>The expenses record you have requested does not exist.</strong></p>";

if ($user_usertype_current > 3) {

	echo "<p>Try the ";
	if ($ts_expense_id > 1) { echo "<a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=".($ts_expense_id - 1)."\">previous</a> "; }
	echo "or <a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=".($ts_expense_id + 1)."\">next</a> ";
	echo "reference.</p>";

}

}

?>