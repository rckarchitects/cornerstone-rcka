<?php

print "<h1>Expenses</h1>";
print "<p class=\"menu_bar\"><a href=\"index2.php?page=timesheet_expense_edit\" class=\"menu_tab\">New Expenses Claim</a></p>";

if ($_GET[status] == "edit" AND $_POST[ts_expense_id] == NULL) {
	$sql = "SELECT * FROM intranet_timesheet_expense WHERE ts_expense_id = $_GET[ts_expense_id] LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	
		$ts_expense_project = $array['ts_expense_project'];
		$ts_expense_value = NumberFormat($array['ts_expense_value']);
		$ts_expense_date = $array['ts_expense_date'];
		$ts_expense_desc = $array['ts_expense_desc'];
		$ts_expense_user = UserDetails($array['ts_expense_user']);
		$ts_expense_verified = $array['ts_expense_verified'];
		$ts_expense_vat = $array['ts_expense_vat'];
		$ts_expense_id = $array['ts_expense_id'];
		$ts_expense_invoiced = $array['ts_expense_invoiced'];
		$ts_expense_receipt = $array['ts_expense_receipt'];
		
		$expense_date_day = date("d",$ts_expense_date);
		$expense_date_month = date("m",$ts_expense_date);
		$expense_date_year = date("Y",$ts_expense_date);
		
		if ($user_usertpe_current > 3) {
			$ts_expense_verified = $_POST[ts_expense_verified];
			$ts_expense_invoiced = $_POST[ts_expense_invoiced];
		}
		
		print "<h2>Edit Mileage Claim</h2>";
		print "<form action=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\" method=\"post\">";
		
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
		
		$expense_date_day = CleanNumber($_POST[ts_expense_day]);
		$expense_date_month = CleanNumber($_POST[ts_expense_month]);
		$expense_date_year = CleanNumber($_POST[ts_expense_year]);
		
		if ($_GET[proj_id] != NULL) { $proj_id_page = $_GET[proj_id]; }
		
		print "<h2>Add Mileage Claim</h2>";
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

		print "<p>Description<br /><textarea name=\"ts_expense_desc\" rows=\"3\" cols=\"38\">$ts_expense_desc</textarea></p>";

	print "<p>Distance (Miles)<br /><input type=\"text\" name=\"ts_expense_value\" size=\"24\" value=\"";
	print $expense_mileage_miles;
	print "\" /></p>";
	
	print "<p>Current Mileage Rate: <strong>&pound;$settings_mileage</strong> per mile</p>";
	
	print "<p>Invoice Number<br />";
	
		print "<select name=\"ts_expense_invoiced\">";
		$sql = "SELECT * FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_project = proj_id order by invoice_ref";
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

	print "<p>Date<br /><font class=\"minitext\">(dd/mm/yyyy)</font><br /><input type=\"text\" name=\"ts_expense_day\" class=\"inputbox\" size=\"6\" value=\"$nowtime_day\" />&nbsp;<input type=\"text\" name=\"ts_expense_month\" value=\"$nowtime_month\" size=\"6\" class=\"inputbox\" />&nbsp;<input type=\"text\" name=\"ts_expense_year\" value=\"$nowtime_year\" size=\"10\" class=\"inputbox\" /></p>";


	// Close the table

	print "<p><input type=\"submit\" value=\"Submit\" class=\"inputsubmit\" /></p>";
	print "<input type=\"hidden\" name=\"action\" value=\"expense_mileage_edit\" />";
	print "</form>";


?>
