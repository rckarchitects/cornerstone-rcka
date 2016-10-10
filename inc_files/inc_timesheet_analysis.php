<?php

echo "<h1>Timesheet Analysis</h1>";

echo "<fieldset><legend>Summary Sheets (PDF)</legend>";

	echo "<p>";
	echo "<a href=\"timesheet_all_summary_pdf.php\">View Project Summary Sheet</a><br /><font class=\"minitext\">Please note that this may take a few seconds to generate</font>";
	echo "</p>";
	
echo "</fieldset>";



// Select project month to view

echo "<fieldset><legend>View Monthly Project Sheet (PDF)</legend>";

echo "<p>The following form allows you to output a PDF file which lists the activity for a specific project for a particular month.</p>";

echo "<form method=\"post\" action=\"timesheet_project_month_pdf_redirect.php\">";

echo "<p>Select Project<br />";

echo "<select name=\"submit_project\" class=\"inputbox\">";

	$sql = "SELECT * FROM intranet_projects order by proj_num";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$proj_id = $array['proj_id'];

	echo "<option value=\"$proj_id\" class=\"inputbox\">$proj_num $proj_name</option>";
	}
	echo "</select></p>";
	
echo "<p>Select Date<br />";
echo "<select name=\"submit_month\" class=\"inputbox\">";
echo "<option value=\"1\">January</option>";
echo "<option value=\"2\">February</option>";
echo "<option value=\"3\">March</option>";
echo "<option value=\"4\">April</option>";
echo "<option value=\"5\">May</option>";
echo "<option value=\"6\">June</option>";
echo "<option value=\"7\">July</option>";
echo "<option value=\"8\">August</option>";
echo "<option value=\"9\">September</option>";
echo "<option value=\"10\">October</option>";
echo "<option value=\"11\">November</option>";
echo "<option value=\"12\">December</option>";
echo "</select>";
echo "&nbsp;";
echo "<select name=\"submit_year\" class=\"inputbox\">";

$sql_select_year = "SELECT ts_year FROM intranet_timesheet ORDER BY ts_year LIMIT 1";
$result_select_year = mysql_query($sql_select_year, $conn) or die(mysql_error());
$array_select_year = mysql_fetch_array($result_select_year);
$year_start = $array_select_year['ts_year'];

while ($year_start <= date("Y",time())) {
	
	echo "<option value=\"$year_start\">$year_start</option>";
	$year_start++;
	
}

echo "</select></p>";
echo "<p>";
echo "<input type=\"submit\" value=\"Go\" class=\"inputsubmit\" />";
echo "</p>";
echo "</form>";

echo "</fieldset>";


// Select period month to view

echo "<fieldset><legend>View Project Sheets for Period (PDF)</legend>";

echo "<p>The following form allows you to output a PDF file which lists the activity for a specific project for any given period.</p>";

echo "<form method=\"post\" action=\"timesheet_pdf_2.php\">";

    echo "<p>Choose Date<br /><select name=\"submit_project\" class=\"inputbox\">";

	$sql = "SELECT * FROM intranet_projects order by proj_num";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$proj_id = $array['proj_id'];

	echo "<option value=\"$proj_id\" class=\"inputbox\"";
	if ($_POST[submit_project] == $proj_id) { echo " selected";}
	echo ">$proj_num $proj_name</option>";
	}
	echo "</select></p>";

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

	echo "<p>Choose start of period<br />";

echo "<select name=\"submit_begin\" class=\"inputbox\">";

	echo "<option value=\"0\">-- All Time --</option>";
	// Array through the weeks
for ($counter = 1; $counter<=50; $counter++) {

	$date_prev_begin = date("l, jS F Y",$time_prev_begin);
	$time_prev_end = $time_prev_begin+388799;
	$date_prev_end = date("l, jS F Y",$time_prev_end);

echo "<option value=\"$time_prev_end\">$date_prev_end</option>";

	$time_prev_begin = $time_prev_begin + 604800;
}

echo "</select></p>";


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

	echo "<p>Choose end of period<br />";

echo "<select name=\"submit_end\" class=\"inputbox\">";
	// Array through the weeks
for ($counter = 1; $counter<=17; $counter++) {

	$date_prev_begin = date("l, jS F Y",$time_prev_begin);
	$time_prev_end = $time_prev_begin+388799;
	$date_prev_end = date("l, jS F Y",$time_prev_end);

echo "<option value=\"$time_prev_end\">$date_prev_end</option>";

	$time_prev_begin = $time_prev_begin + 604800;
}

echo "<option value=\"$nowtime\" selected>Today</option>";

echo "</select></p>";

echo "<p><input type=\"checkbox\" name=\"separate_pages\" value=\"1\" />&nbsp;Separate fee stages by page, remove costs</p>";
echo "<p><input type=submit value=\"Go\" class=\"inputsubmit\" /></p>";

echo "</fieldset>";


?>
