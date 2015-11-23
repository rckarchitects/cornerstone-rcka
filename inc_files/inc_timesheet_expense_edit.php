<?php

print "<h1>Expenses</h1>";
print "<p class=\"menu_bar\"><a href=\"index2.php?page=timesheet_expense_mileage_edit\" class=\"menu_tab\">New Mileage Expenses</a><a href=\"index2.php?page=timesheet_expense_printing_edit\" class=\"menu_tab\">New Printing Expenses</a></p>";

if ($_GET[ts_expense_id] > 0) {
$ts_expense_id = CleanNumber($_GET[ts_expense_id]);
}

$settings_vat;

echo "<p class=\"submenu_bar\">";

if ($_GET[startid] != NULL) {
print "<a href=\"popup_expenses.php?startid=$_GET[startid]\" class=\"submenu_bar\">Return to Quick Expenses</a>";
}

if ($_POST[ts_expense_id] == $id_num) { $ts_expense_id = NULL; }
elseif ($_POST[ts_expense_id] != NULL) { $ts_expense_id = CleanNumber($_POST[ts_expense_id]); }

if ($_GET[status] == "edit" AND $_POST[ts_expense_id] == NULL) {
	$sql = "SELECT * FROM intranet_timesheet_expense WHERE ts_expense_id = $_GET[ts_expense_id] LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	
		$ts_expense_project = $array['ts_expense_project'];
		$ts_expense_value = NumberFormat($array['ts_expense_value']);
		$ts_expense_date = $array['ts_expense_date'];
		$ts_expense_desc = html_entity_decode($array['ts_expense_desc']);
		$ts_expense_user = UserDetails($array['ts_expense_user']);
		$ts_expense_verified = $array['ts_expense_verified'];
		$ts_expense_vat = $array['ts_expense_vat'];
		$ts_expense_id = $array['ts_expense_id'];
		$ts_expense_invoiced = $array['ts_expense_invoiced'];
		$ts_expense_receipt = $array['ts_expense_receipt'];
		$ts_expense_reimburse = $array['ts_expense_reimburse'];
		$ts_expense_notes = $array['ts_expense_notes'];
		$ts_expense_category = $array['ts_expense_category'];
		$ts_expense_disbursement = $array['ts_expense_disbursement'];
		$ts_expense_p11d = $array['ts_expense_p11d'];
		
		$expense_date_day = date("d",$ts_expense_date);
		$expense_date_month = date("m",$ts_expense_date);
		$expense_date_year = date("Y",$ts_expense_date);
		
		if ($user_usertpe_current > 3) {
			$ts_expense_verified = $_POST[ts_expense_verified];
			$ts_expense_invoiced = $_POST[ts_expense_invoiced];
		}
		
		print "<h2>Edit Expenses Claim</h2>";
		print "<form action=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id&amp;startid=$_GET[startid]\" method=\"post\">";
		
} else {

		$ts_expense_project = CleanNumber($_POST[ts_expense_project]);
		$ts_expense_value = NumberFormat($_POST[ts_expense_value]);
		$ts_expense_date = CleanNumber($_POST[ts_expense_date]);
		$ts_expense_desc = CleanUp($_POST[ts_expense_desc]);
		$ts_expense_user = CleanNumber($_POST[ts_expense_user]);
		$ts_expense_verified = $_POST[ts_expense_verified];
		$ts_expense_vat = $_POST[ts_expense_vat];
		$ts_expense_invoiced = $_POST[ts_expense_invoiced];
		$ts_expense_receipt = $_POST[ts_expense_receipt];
		$ts_expense_reimburse = $_POST[ts_expense_reimburse];
		$ts_expense_notes = CleanUp($_POST[ts_expense_notes]);
		$ts_expense_category = CleanUp($_POST[ts_expense_category]);
		$ts_expense_disbursement = $_POST[ts_expense_disbursement];
		$ts_expense_p11d = $_POST[ts_expense_p11d];
		
		$expense_date_day = CleanNumber($_POST[ts_expense_day]);
		$expense_date_month = CleanNumber($_POST[ts_expense_month]);
		$expense_date_year = CleanNumber($_POST[ts_expense_year]);
		
		if ($_GET[proj_id] != NULL) { $proj_id_page = $_GET[proj_id]; }
		
		print "<h2>Add Expenses Claim</h2>";
		print "<form action=\"index2.php?page=timesheet_expense_view\" method=\"post\">";

}
print "<input type=\"hidden\" name=\"ts_expense_id\" value=\"$ts_expense_id\" />";

// Begin the invoice entry system

	$nowtime = time();
	
	if ($expense_date_day > 0) { $nowtime_day = $expense_date_day;} else {$nowtime_day = date("d",$nowtime); }
	if ($expense_date_month > 0) { $nowtime_month = $expense_date_month; } else { $nowtime_month = date("m",$nowtime); }
	if ($expense_date_year > 0) { $nowtime_year = $expense_date_year; } else { $nowtime_year = date("Y",$nowtime); }
	
	// Project list

	print "<p>Project<br />";

		print "<select name=\"ts_expense_project\">";
		$sql = "SELECT * FROM intranet_projects order by proj_num";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		while ($array = mysql_fetch_array($result)) {
				$proj_num = $array['proj_num'];
				$proj_name = $array['proj_name'];
				$proj_id = $array['proj_id'];
				print "<option value=\"$proj_id\" class=\"inputbox\"";
				if ($ts_expense_project == $proj_id) { print " selected";}
				elseif ($proj_id == $proj_id_page) { print " selected";}
				print ">$proj_num $proj_name</option>";
		}
		print "</select></p>";
		
	// Text field

		print "<p>Description<br /><textarea name=\"ts_expense_desc\" rows=\"6\" cols=\"38\">$ts_expense_desc</textarea></p>";

	print "<p>Value<br />&pound;<input type=\"text\" name=\"ts_expense_value\" size=\"24\" value=\"";
		if ($ts_expense_vat > $ts_expense_value) { print NumberFormat($ts_expense_vat); } else { print NumberFormat($ts_expense_value); }
	print "\" /></p>";
	
	if ($ts_expense_value > 0) { $vat_check = $ts_expense_vat / $ts_expense_value; } else { $vat_check = 0; }
	
	$vat_old = $vat_check * 1000;
	$vat_old = round($vat_old / 5) * 5;
	$vat_old = ($vat_old / 10) - 100;
	
	if ($ts_expense_vat > 0) { $vat_now = $vat_old; } else { $vat_now = $settings_vat; }
	
	print "<p><input type=\"radio\" name=\"ts_expense_vat_check\" value=\"included\"";
		if ($ts_expense_value != $ts_expense_vat) { print "checked=\"checked\""; }
	echo "/>&nbsp;Include VAT at 
	<input type=\"text\" name=\"vat_value_included\" value=\"$vat_now\" size=\"2\" maxlength=\"5\" />%";
	
	
		print "&nbsp;&nbsp;<input type=\"radio\" name=\"ts_expense_vat_check\" value=\"add\" />&nbsp;Add VAT at
	<input type=\"text\" name=\"vat_value_add\" value=\"$settings_vat\" size=\"2\" maxlength=\"5\" />%";
	
	
		print "&nbsp;&nbsp;<input type=\"radio\" name=\"ts_expense_vat_check\" value=\"none\"";
		if ($ts_expense_vat == $ts_expense_value) { print "checked=\"checked\""; }
	print "/>&nbsp;VAT exempt";
	print "</p>";
	
	print "<p><input type=\"checkbox\" name=\"ts_expense_receipt\" value=\"1\"";
		if ($ts_expense_receipt == "1") { print " checked"; }
	print " />&nbsp;Receipt Available?</p>";
	
	print "<p><input type=\"checkbox\" name=\"ts_expense_reimburse\" value=\"1\"";
		if ($ts_expense_reimburse == "1") { print " checked"; }
	print " />&nbsp;Reimbursable Expense?</p>";
	
	print "<p>Disbursement?
	<br />
	<input type=\"radio\" name=\"ts_expense_disbursement\" value=\"\"";
		if ($ts_expense_disbursement != "1") { print " checked"; }
	print " />&nbsp; Yes <span class=\"minitext\">[eg. postage, train travel]</span>
	&nbsp;&nbsp;
	<input type=\"radio\" name=\"ts_expense_disbursement\" value=\"1\"";
		if ($ts_expense_disbursement == "1") { print " checked"; }
	print " />&nbsp; No <span class=\"minitext\">[eg. internal printing, mileage]</span>";
	echo "</p>";
	
	if ($user_usertype_current > 2) {
	if ($ts_expense_p11d == "1") { $checked = " checked=\"checked\""; } else { $checked = ""; }
	echo "<p><input type=\"checkbox\" name=\"ts_expense_p11d\" value=\"1\" $checked />&nbsp;Personal Item (P11d)?</p>";
	}
	
	print "<p>Invoice Number<br />";
	
	$days_invoice_expired = 7;
	
	$time_invoice_expired = $days_invoice_expired * 86400;
	
		print "<select name=\"ts_expense_invoiced\">";
		$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id AND invoice_date > ".(time() - $time_invoice_expired)." OR invoice_project = proj_id AND invoice_id = '$ts_expense_invoiced' order by invoice_ref";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		print "<option value=\"\">-- None --</option>";
		while ($array = mysql_fetch_array($result)) {
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$invoice_ref = $array['invoice_ref'];
		$invoice_id = $array['invoice_id'];
		print "<option value=\"$invoice_id\" class=\"inputbox\"";
		if ($ts_expense_invoiced == $invoice_id) { print " selected";}
		print ">$proj_num $proj_name - $invoice_ref</option>";
		} print "</select>";
		
	print "</p>";
	
	// Establish the correct category

	print "<p>Category<br />";	
		print "<select name=\"ts_expense_category\">";
		$sql = "SELECT * FROM intranet_timesheet_expense_category WHERE expense_cat_clearance <= '$user_usertype_current' ORDER BY expense_cat_name";
		$result = mysql_query($sql, $conn) or die(mysql_error());

		while ($array = mysql_fetch_array($result)) {
		$expense_cat_id = $array['expense_cat_id'];
		$expense_cat_name = $array['expense_cat_name'];
		$expense_cat_clearance = $array['expense_cat_clearance'];
		print "<option value=\"$expense_cat_id\"";
		if ($ts_expense_category == $expense_cat_id) { print " selected";}
		print ">$expense_cat_name</option>";
		} print "</select>";
	print "</p>";

// Add the owner of this item if we are editing the expense at this stage
	
if ($ts_expense_id > 0 OR $user_usertype_current > 3) {

	// Work out which user to select
	if ($ts_expense_user == NULL) { $user_to_select = $_COOKIE[user]; } else { $user_to_select = $ts_expense_user; }

	print "<p>User<br />";	
		print "<select name=\"ts_expense_user\">";
		$sql_user = "SELECT * FROM intranet_user_details ORDER BY user_name_second";
		$result_user = mysql_query($sql_user, $conn) or die(mysql_error());
		echo "<option value=\"\">-- None --</option>";
		while ($array_user = mysql_fetch_array($result_user)) {
		$user_id = $array_user['user_id'];
		$user_name_first = $array_user['user_name_first'];
		$user_name_second = $array_user['user_name_second'];
		print "<option value=\"$user_id\"";
		if ($user_id == $user_to_select) { print " selected"; }
		print ">$user_name_first $user_name_second</option>";
		} print "</select>";
	print "</p>";
	
} else { echo "<input type=\"hidden\" name=\"ts_expense_user\" value=\"$_COOKIE[user]\" />"; }
	
	print "<p>Date<br /><font class=\"minitext\">(dd/mm/yyyy)</font><br /><input type=\"text\" name=\"ts_expense_day\" class=\"inputbox\" size=\"6\" value=\"$nowtime_day\" />&nbsp;<input type=\"text\" name=\"ts_expense_month\" value=\"$nowtime_month\" size=\"6\" class=\"inputbox\" />&nbsp;<input type=\"text\" name=\"ts_expense_year\" value=\"$nowtime_year\" size=\"10\" class=\"inputbox\" /></p>";

		print "<p>Notes<br /><textarea name=\"ts_expense_notes\" rows=\"6\" cols=\"38\">$ts_expense_notes</textarea></p>";

	// Close the table

	print "<p><input type=\"submit\" value=\"Submit\" class=\"inputsubmit\" /></p>";
	print "<input type=\"hidden\" name=\"action\" value=\"expense_edit\" />";
	print "</form>";
	
	if ($user_usertype_current > 2 AND $ts_expense_verified == 0 AND $ts_expense_id != NULL) {
		
		echo "<h2>Other Actions</h2>";
		echo "	<form action=\"index2.php\" method=\"post\">
				<p>
				<input type=\"hidden\" name=\"action\" value=\"expense_delete\" />
				<input type=\"hidden\" name=\"ts_expense_id\" value=\"$ts_expense_id\" />
				<input type=\"hidden\" name=\"ts_expense_user\" value=\"$ts_expense_user\" />
				<input type=\"hidden\" name=\"ts_expense_date\" value=\"$ts_expense_date\" />
				<input type=\"hidden\" name=\"ts_expense_project\" value=\"$ts_expense_project\" />
				<input type=\"submit\" value=\"Delete This Entry\" onClick=\"javascript:return confirm('Are you sure you want to delete this expense?')\" /></p>";
		echo "</form>";
	
		}


?>
