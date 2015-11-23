<?php

print "<h1>Invoices</h1>";

// Begin the invoice entry system

print "<h2>View Invoices</h2>";

print "<p>Please select a project from the following list</p>";

print "<form action=\"index2.php?page=timesheet_invoice_view_project\" method=\"post\">";



	$sql = "SELECT * FROM intranet_projects WHERE proj_fee_track = '1' order by proj_num";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	print "<p><select name=\"proj_id\">";
	while ($array = mysql_fetch_array($result)) {
	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$proj_id = $array['proj_id'];
	print "<option value=\"$proj_id\"";
	if ($timesheet_add_project == $proj_id) {
	print " selected";
	}
	print ">$proj_num&nbsp;$proj_name</option>";
	}
	print "</select></p>";
	
	print "<p><input type=\"hidden\" value=\"timesheet_invoice_view_project\" name=\"action\" />";

	print "<input type=\"submit\" value=\"View\" /></p>";
	
	

print "</form>";

print "<h2>Print Invoice Schedule <img src=\"images/button_pdf.png\" alt=\"PDF output of invoice schedule\" /></h2>";
	print "<p>This will output a complete schedule of all invoices on the system.</p>";
	print "<form action=\"pdf_invoice_schedule.php\" method=\"post\">";
	print "<p><input type=\"submit\" value=\"Submit\" /></p>";
	print "</form>";
	
	
print "<h2>CSV Invoice Schedule</h2>";
	print "<p>This will output a complete schedule of all invoices on the system in CSV format.</p>";
	print "<form action=\"csv_invoice_schedule.php\" method=\"post\">";
	print "<p><input type=\"submit\" value=\"Submit\" /></p>";
	print "</form>";
	
print "<h2>All Invoices <img src=\"images/button_pdf.png\" alt=\"PDF output of all invoices\" /></h2>";
	print "<p>This will output a single PDF file of all invoices according to the criteria below.</p>";
	print "<form action=\"pdf_invoice.php\" method=\"post\">";
	
	$sql_account = "SELECT * FROM intranet_account order by account_name";
	$result_account = mysql_query($sql_account, $conn) or die(mysql_error());
	echo "<p><select name=\"account_id\">";
	echo "<option value=\"\">-- All --</option>";
	while ($array_account = mysql_fetch_array($result_account)) {
	echo "<option value=\"".$array_account['account_id']."\">".$array_account['account_name']."</option>";
	}
	echo "</select></p>";
	
	print "<p>Order By<br /><select name=\"order_by\"><option value=\"invoice_id\">Invoice ID</option><option value=\"invoice_date\">Invoice Date</option><option value=\"invoice_paid\">Invoice Paid Date</option><option value=\"invoice_ref\">Invoice Number</option></select></p>";
	
	print "<p><input type=\"hidden\" value=\"yes\" name=\"viewall\" /><input type=\"submit\" value=\"Submit\" /></p>";
	print "</form>";


?>
