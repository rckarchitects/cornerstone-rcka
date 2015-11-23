<?php

$invoice_days_default = 28;

print "<h1>Invoices</h1>";

// Determine whether we are adding a new invoice or editing an existing one

if ($_GET[status] == "edit") {
	$sql = "SELECT * FROM intranet_timesheet_invoice WHERE invoice_id = $_GET[invoice_id] LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
		$invoice_id = $array['invoice_id'];
		$invoice_date = $array['invoice_date'];
		$invoice_due = $array['invoice_due'];
		$invoice_project = $array['invoice_project'];
		$invoice_ref = $array['invoice_ref'];
		$invoice_paid = $array['invoice_paid'];
		$invoice_text = $array['invoice_text'];
		$invoice_notes = $array['invoice_notes'];
		$invoice_account = $array['invoice_account'];
		$invoice_baddebt = $array['invoice_baddebt'];
		$invoice_client = $array['invoice_client'];
		$invoice_purchase_order = $array['invoice_purchase_order'];
		
		$nowtime_day = date("d",$invoice_date);
		$nowtime_month = date("m",$invoice_date);
		$nowtime_year = date("Y",$invoice_date);
		
		$thentime_day = date("d",$invoice_due );
		$thentime_month = date("m",$invoice_due );
		$thentime_year = date("Y",$invoice_due );
		
		if ($invoice_paid > 0) { $invoice_paid_day = date("d",$invoice_paid ); } else { $invoice_paid_day = ""; }
		if ($invoice_paid > 0) { $invoice_paid_month = date("m",$invoice_paid ); } else { $invoice_paid_month = ""; }
		if ($invoice_paid > 0) { $invoice_paid_year = date("Y",$invoice_paid ); } else { $invoice_paid_year = ""; }

	print "<h2>Edit Invoice $invoice_ref</h2>";
	print "<form action=\"index2.php?page=timesheet_invoice_list\" method=\"post\">";
	print "<input type=\"hidden\" name=\"action\" value=\"invoice_edit\" />";
	print "<input type=\"hidden\" name=\"invoice_id\" value=\"$invoice_id\" />";
		
}	else	{

		$nowtime = time();
		$thentime = $nowtime + $invoice_time_by;

		if ($_POST[invoice_date_day] == NULL) { $nowtime_day = date("d",$nowtime); } else { $nowtime_day = $_POST[invoice_date_day]; }
		if ($_POST[invoice_date_month] == NULL) { $nowtime_month = date("m",$nowtime); } else { $nowtime_month = $_POST[invoice_date_month]; }
		if ($_POST[invoice_date_year] == NULL) { $nowtime_year = date("Y",$nowtime); } else { $nowtime_year = $_POST[invoice_date_year]; }
		
		if ($_POST[invoice_due_day] == NULL) { $thentime_day = date("d",$thentime); } else { $thentime_day = $_POST[invoice_due_day]; }
		if ($_POST[invoice_due_month] == NULL) { $thentime_month = date("m",$thentime); } else { $thentime_month = $_POST[invoice_due_month]; }
		if ($_POST[invoice_due_year] == NULL) { $thentime_year = date("Y",$thentime); } else { $thentime_year = $_POST[invoice_due_year]; }
		
		if ($_POST[invoice_paid_day] == NULL) { $invoice_paid_day = ""; } else { $invoice_paid_day = $_POST[invoice_paid_day]; }
		if ($_POST[invoice_paid_month] == NULL) { $invoice_paid_month = ""; } else { $invoice_paid_month = $_POST[invoice_paid_month]; }
		if ($_POST[invoice_paid_year] == NULL) { $invoice_paid_year = ""; } else { $invoice_paid_year = $_POST[invoice_paid_year]; }

		$invoice_date = $array['invoice_date'];
		$invoice_due = $array['invoice_due'];
		$invoice_project = $array['invoice_project'];
		$invoice_ref = $array['invoice_ref'];
		$invoice_paid = $array['invoice_paid'];
		$invoice_notes = $array['invoice_notes'];
		$invoice_text = $array['invoice_text'];
		$invoice_account = $array['invoice_account'];
		$invoice_baddebt = $array['invoice_baddebt'];
		$invoice_paid_type = $_POST['invoice_paid_type'];
		$invoice_client = $_POST['invoice_client'];
		$invoice_purchase_order = $_POST['invoice_purchase_order'];
		if ($invoice_due_by > 0 ) { $invoice_due_by = $_POST[invoice_due_by]; } else { $invoice_due_by = $invoice_days_default; }
		
		if ($_GET[proj_id] != NULL) { $proj_id_page = $_GET[proj_id]; }
	
	print "<h2>Add Invoice</h2>";
	print "<form action=\"index2.php?page=timesheet_invoice_list\" method=\"post\">";
	print "<input type=\"hidden\" name=\"action\" value=\"invoice_edit\" />";

}

// Determine the smallprint

$smallprint = file_get_contents("secure/invoice_text.inc");


if ($invoice_ref != NULL) { $smallprint = $invoice_text; }



	print "<table summary=\"Form to add a new invoice\">";
	print "<tr><td style=\"width: 25%;\">Invoice Number</td><td colspan=\"2\"><input type=\"text\" name=\"invoice_ref\" size=\"24\" value=\"$invoice_ref\" /></td></tr>";
	print "
	<tr>
	<td>Invoice Date<br /><span class=\"minitext\">(dd/mm/yyyy)</span></td>
	<td colspan=\"2\"><input type=\"text\" name=\"invoice_date_day\" size=\"3\" value=\"$nowtime_day\" maxlength=\"2\" />&nbsp;<input type=\"text\" name=\"invoice_date_month\" value=\"$nowtime_month\" size=\"3\" maxlength=\"2\" />&nbsp;<input type=\"text\" name=\"invoice_date_year\" value=\"$nowtime_year\" size=\"4\" maxlength=\"4\" />
	</td>
	</tr>";
	

	if ($invoice_paid_type != "auto") { $option2 = " checked "; $option1 = ""; } else { $option2 = ""; $option1 = " checked "; }
	
	//This needs sorting out!
	if ($invoice_due_by > 0) {} else { $invoice_due_by = 28; }
	
	
	print "
	<tr>
	<td rowspan=\"2\">Invoice Due<br /><span class=\"minitext\">(dd/mm/yyyy)</span></td>
	<td><input type=\"text\" name=\"invoice_due_day\" size=\"3\" value=\"$thentime_day\" maxlength=\"2\" />&nbsp;<input type=\"text\" name=\"invoice_due_month\" value=\"$thentime_month\" size=\"3\" maxlength=\"2\" />&nbsp;<input type=\"text\" name=\"invoice_due_year\" value=\"$thentime_year\" size=\"4\" maxlength=\"4\" /></td>
	<td><input type=\"radio\" name=\"invoice_due_type\" value=\"manual\" $option1 />&nbsp;Manually enter due date<br />(default: $invoice_due_by days from issued date)</td></tr>
	<tr><td><input type=\"text\" name=\"invoice_due_auto\" value=\"$invoice_due_by\" maxlength=\"3\" /></td><td><input type=\"radio\" name=\"invoice_due_type\" value=\"auto\" $option2 />&nbsp;Automatic due date<br />(default: $invoice_due_by days from issued date)</td></tr>
	";
	print "
	<tr>
	<td>Invoice Paid<br /><span class=\"minitext\">(dd/mm/yyyy)</span></td>
	<td colspan=\"2\"><input type=\"text\" name=\"invoice_paid_day\" size=\"3\" value=\"$invoice_paid_day\" maxlength=\"2\" />&nbsp;<input type=\"text\" name=\"invoice_paid_month\" value=\"$invoice_paid_month\" size=\"3\" maxlength=\"2\" />&nbsp;<input type=\"text\" name=\"invoice_paid_year\" value=\"$invoice_paid_year\" size=\"4\" maxlength=\"4\" /></td>
	</tr>
	";

	// Project list

	print "<tr><td>Project</td><td colspan=\"2\">";

		print "<select name=\"invoice_project\">";
		$sql = "SELECT * FROM intranet_projects LEFT JOIN contacts_contactlist ON proj_client_contact_id = contact_id order by proj_num";
		// $sql = "SELECT * FROM intranet_projects WHERE proj_fee_track = '1' order by proj_num";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		while ($array = mysql_fetch_array($result)) {
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$proj_id = $array['proj_id'];
		$proj_client_contact_id = $array['proj_client_contact_id'];
		
		if ($invoice_client > 0) { $invoice_client_id = $invoice_client;  }  elseif ($proj_id == $_GET[proj_id] OR $proj_id == $invoice_project) { $invoice_client_id = $proj_client_contact_id; }

		print "<option value=\"$proj_id\" class=\"inputbox\"";
		if ($invoice_project == $proj_id) { print " selected";}
		elseif ($proj_id_page == $proj_id) { print " selected";}
		print ">$proj_num $proj_name</option>";
		}
		print "</select></td></tr>";
		
		
// Client to invoice

	print "<tr><td>Client to invoice</td><td colspan=\"2\">";
	
		if ($invoice_project == NULL) { $invoice_project = $_GET[proj_id]; }

		print "<select name=\"invoice_client\">";
		$sql_client = "SELECT contact_id, contact_namefirst, contact_namesecond, company_name FROM contacts_contactlist LEFT JOIN contacts_companylist ON contact_company = company_id ORDER BY contact_namesecond";
		$result_client = mysql_query($sql_client, $conn) or die(mysql_error());
		while ($array_client = mysql_fetch_array($result_client)) {
		$client_namefirst = $array_client['contact_namefirst'];
		$client_namesecond = $array_client['contact_namesecond'];
		$array_client['contact_namesecond'];
		$client_id = $array_client['contact_id'];
		$company_name = $array_client['company_name'];
		
		$client_name = $client_namesecond . ", " . $client_namefirst;
		if ($company_name != NULL) { $client_name = $client_name . " (" . $company_name . ")"; }
		
		if ($client_namesecond != NULL) { 
			print "<option value=\"$client_id\" class=\"inputbox\"";
			if ($invoice_client_id == $client_id) { print " selected";}
			echo ">$client_name</option>";
			}
		}
		print "</select></td></tr>";
		
	print "
	<tr>
	<td>Client Purchase Order<br /><span class=\"minitext\">(if required)</span></td>
	<td colspan=\"2\"><input type=\"text\" name=\"invoice_purchase_order\" value=\"$invoice_purchase_order\" size=\"18\" maxlength=\"50\" />
	</td>
	</tr>";
		
// Account list

	print "<tr><td>Account</td><td colspan=\"2\">";

		print "<select name=\"invoice_account\">";
		$sql = "SELECT * FROM intranet_account order by account_name";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		while ($array = mysql_fetch_array($result)) {
		$account_name = $array['account_name'];
		$account_id = $array['account_id'];
		print "<option value=\"$account_id\"";
		if ($invoice_account == $account_id) { print " selected";}
		print ">$account_name</option>";
		}
		print "</select></td></tr>";
		
	print "<tr><td>Payment Instructions<br /><span class=\"minitext\">(Use the tag <strong>[due]</strong> to represent the invoice due period above)</span></td><td colspan=\"2\">";
	print "<textarea cols=\"42\" rows=\"12\" name=\"invoice_text\" />".$smallprint."</textarea></td>";
	print "</tr>";
	
	print "<tr><td>Notes<br /><span class=\"minitext\">(This text is not printed on invoices by default)</span></td><td colspan=\"2\">";
	print "<textarea type=\"text\" cols=\"42\" rows=\"6\" name=\"invoice_notes\" />".$invoice_notes."</textarea></td>";
	print "</tr>";
	
	if ($_GET[invoice_id] > 0) {
		echo "<tr><td>Bad debt</td><td colspan=\"2\"><input type=\"checkbox\" name=\"invoice_baddebt\" value=\"yes\"";
		if ($invoice_baddebt == "yes") { echo " checked=\"checked\""; }
		echo " /></td></tr>";
		}

		
	// Close the table

	print "</table>";
	print "<p><input type=\"submit\" value=\"Submit\" /></p>";
	print "</form>";

?>
