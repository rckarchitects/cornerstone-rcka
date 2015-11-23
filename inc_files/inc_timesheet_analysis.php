<?php

print "<h1>Timesheet Analysis</h1>";

print "<fieldset><legend>Summary Sheets (PDF)</legend>";

	print "<p>";
	print "<a href=\"timesheet_all_summary_pdf.php\">View Project Summary Sheet</a><br /><font class=\"minitext\">Please note that this may take a few seconds to generate</font>";
	print "</p>";
	
print "</fieldset>";



// Select project month to view

print "<fieldset><legend>View Monthly Project Sheet (PDF)</legend>";

print "<p>The following form allows you to output a PDF file which lists the activity for a specific project for a particular month.</p>";

print "<form method=\"post\" action=\"timesheet_project_month_pdf_redirect.php\">";

print "<p>Select Project<br />";

print "<select name=\"submit_project\" class=\"inputbox\">";

	$sql = "SELECT * FROM intranet_projects order by proj_num";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$proj_id = $array['proj_id'];

	print "<option value=\"$proj_id\" class=\"inputbox\">$proj_num $proj_name</option>";
	}
	print "</select></p>";
	
print "<p>Select Date<br />";
print "<select name=\"submit_month\" class=\"inputbox\">";
print "<option value=\"1\">January</option>";
print "<option value=\"2\">February</option>";
print "<option value=\"3\">March</option>";
print "<option value=\"4\">April</option>";
print "<option value=\"5\">May</option>";
print "<option value=\"6\">June</option>";
print "<option value=\"7\">July</option>";
print "<option value=\"8\">August</option>";
print "<option value=\"9\">September</option>";
print "<option value=\"10\">October</option>";
print "<option value=\"11\">November</option>";
print "<option value=\"12\">December</option>";
print "</select>";
print "&nbsp;";
print "<select name=\"submit_year\" class=\"inputbox\">";
print "<option value=\"2004\">2004</option>";
print "<option value=\"2005\">2005</option>";
print "<option value=\"2006\">2006</option>";
print "<option value=\"2006\">2007</option>";
print "<option value=\"2006\">2008</option>";
print "<option value=\"2006\">2009</option>";
print "<option value=\"2006\">2010</option>";
print "</select>";
print "</p>";
print "<p>";
print "<input type=\"submit\" value=\"Go\" class=\"inputsubmit\" />";
print "</p>";
print "</form>";

print "</fieldset>";


// Select period month to view

print "<fieldset><legend>View Project Sheets for Period (PDF)</legend>";

print "<p>The following form allows you to output a PDF file which lists the activity for a specific project for any given period.</p>";

print "<form method=\"post\" action=\"timesheet_pdf_2.php\">";

    print "<p>Choose Date<br /><select name=\"submit_project\" class=\"inputbox\">";

	$sql = "SELECT * FROM intranet_projects order by proj_num";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$proj_id = $array['proj_id'];

	print "<option value=\"$proj_id\" class=\"inputbox\"";
	if ($_POST[submit_project] == $proj_id) { print " selected";}
	print ">$proj_num $proj_name</option>";
	}
	print "</select></p>";

	// Array through recent dates of week ending

		$time_now = time();
	$time_now_day = date("w", $time_now);

	$time_to_weekbegin = $time_now_day - 1;
	$time_to_weekbegin = $time_to_weekbegin * 86400;
	$time_weekbegin = $time_now - $time_to_weekbegin;
	$date_weekbegin_date = date("j",$time_weekbegin);
	$date_weekbegin_month = date("n",$time_weekbegin);
	$date_weekbegin_year = date("Y",$time_weekbegin);

	$time_weekbegin = mktime(12,0,0,$date_weekbegin_month, $date_weekbegin_date, $date_weekbegin_year);
	$time_prev_begin = $time_weekbegin - 17539200;

	$currentweek = NULL;

	print "<p>Choose start of period<br />";

print "<select name=\"submit_begin\" class=\"inputbox\">";

	print "<option value=\"0\">-- All Time --</option>";
	// Array through the weeks
for ($counter = 1; $counter<=50; $counter++) {

	$date_prev_begin = date("l, jS F Y",$time_prev_begin);
	$time_prev_end = $time_prev_begin+388799;
	$date_prev_end = date("l, jS F Y",$time_prev_end);

print "<option value=\"$time_prev_end\">$date_prev_end</option>";

	$time_prev_begin = $time_prev_begin + 604800;
}

print "</select></p>";


	$time_now = time();
	$time_now_day = date("w", $time_now);

	$time_to_weekbegin = $time_now_day - 1;
	$time_to_weekbegin = $time_to_weekbegin * 86400;
	$time_weekbegin = $time_now - $time_to_weekbegin;
	$date_weekbegin_date = date("j",$time_weekbegin);
	$date_weekbegin_month = date("n",$time_weekbegin);
	$date_weekbegin_year = date("Y",$time_weekbegin);

	$time_weekbegin = mktime(12,0,0,$date_weekbegin_month, $date_weekbegin_date, $date_weekbegin_year);
	$time_prev_begin = $time_weekbegin - 9719100;

	$currentweek = NULL;

	print "<p>Choose end of period<br />";

print "<select name=\"submit_end\" class=\"inputbox\">";
	// Array through the weeks
for ($counter = 1; $counter<=17; $counter++) {

	$date_prev_begin = date("l, jS F Y",$time_prev_begin);
	$time_prev_end = $time_prev_begin+388799;
	$date_prev_end = date("l, jS F Y",$time_prev_end);

print "<option value=\"$time_prev_end\">$date_prev_end</option>";

	$time_prev_begin = $time_prev_begin + 604800;
}

print "<option value=\"$nowtime\" selected>Today</option>";

print "</select></p>";

echo "<p><input type=\"checkbox\" name=\"separate_pages\" value=\"1\" />&nbsp;Separate fee stages by page, remove costs</p>";
print "<p><input type=submit value=\"Go\" class=\"inputsubmit\" /></p>";

echo "<fieldset><legend>Fees by Country (Last 5 Years)</legend>";

	$date_begin = mktime(0,0,0,5,1,2008);
	$date_end = mktime(0,0,0,5,1,2009);

	$sql_fee_country = "
	SELECT invoice_item_novat,
	invoice_item_vat,
	country_name,
	country_id
	FROM intranet_timesheet_invoice_item,
	intranet_timesheet_invoice,
	intranet_projects,
	intranet_contacts_countrylist
	WHERE
	invoice_item_invoice = invoice_id
	AND proj_address_country = country_id
	AND proj_id = invoice_project
	AND invoice_date BETWEEN '$date_begin' AND '$date_end'
	ORDER BY country_name";
	
	echo "<table>";
	
	$country_id_repeat = NULL;
	$country_total = 
	$result_fee_country = mysql_query($sql_fee_country, $conn) or die(mysql_error());
	while ($array_fee_country = mysql_fetch_array($result_fee_country)) {
	$country_name = ucwords(strtolower($array_fee_country['country_name']));
	$country_id = $array_fee_country['country_id'];
	$invoice_item_novat = $array_fee_country['invoice_item_novat'];
	$invoice_item_vat = $array_fee_country['invoice_item_vat'];
	
	if ($country_id != $country_repeat AND $country_repeat > 0) { echo "<tr><td>".TextPresent(CashFormat($country_total))." (Net)</td><td>".TextPresent(CashFormat($country_total_gross))." (Gross)</td></tr>"; $country_total = 0; }
		
	if ($country_id != $country_repeat) { echo "<tr><td colspan=\"2\">$country_name</td></tr>"; $country_repeat = $country_id; $country_total = 0;  }
	$country_total = $country_total + $invoice_item_novat;
	$country_total_gross = $country_total_gross + $invoice_item_vat;
	}
	echo "<tr><td>".TextPresent(CashFormat($country_total))." (Net)</td><td>".TextPresent(CashFormat($country_total_gross))." (Gross)</td></tr>";
	
	
	echo "</table>";



?>