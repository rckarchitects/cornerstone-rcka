<?php

echo "<h1>Timesheets</h1>";
echo "<h2>Analysis</h2>";

ProjectSubMenu($proj_id,$user_usertype_current,"timesheet_admin",1);

echo "<div><h3>Summary Sheets (PDF)</h3>";

	echo "<p>";
	echo "<a href=\"timesheet_all_summary_pdf.php\">View Project Summary Sheet</a><br /><font class=\"minitext\">Please note that this may take a few seconds to generate</font>";
	echo "</p>";
	
echo "</div>";



// Select project month to view

echo "<div><h3>View Monthly Project Sheet (PDF)</h3>";

echo "<p>The following form allows you to output a PDF file which lists the activity for a specific project for a particular month.</p>";

echo "<form method=\"post\" action=\"timesheet_project_month_pdf_redirect.php\">";

    echo "<p>Select Project<br />";
		ProjectSelect($proj_id,'submit_project',1);
	echo "</p>";


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

echo "</div>";


// Select period month to view

echo "<div><h3>View Project Sheets for Period (PDF)</h3>";

echo "<p>The following form allows you to output a PDF file which lists the activity for a specific project for any given period.</p>";

echo "<form method=\"post\" action=\"timesheet_pdf_2.php\">";

    echo "<p>Select Project<br />";
		ProjectSelect($proj_id,'submit_project',1);
	echo "</p>";

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

echo "<input type=\"date\" name=\"submit_begin\" class=\"inputbox\"></p>";


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

echo "<input type=\"date\" name=\"submit_end\" value=\"" . DisplayDay(time()) . "\" class=\"inputbox\"></p>";

echo "<p><input type=\"checkbox\" name=\"separate_pages\" value=\"1\" />&nbsp;Separate fee stages by page, remove costs</p>";
echo "<p><input type=submit value=\"Go\" class=\"inputsubmit\" /></p>";

echo "</form>";

echo "</div>";


echo "<div><h3>List Projects by Hours</h3>";

echo "<form action=\"index2.php?page=timesheet_analysis_output\" method=\"post\">";

	echo "<p>Select Beginning of Period</p>";

	echo "<p><input type=\"date\" name=\"period_date_start\" /></p>";

	echo "<p>Select End of Period</p>";

	echo "<p><input type=\"date\" name=\"period_date_end\" /></p>";
	
	echo "<p><input type=\"checkbox\" name=\"allprojects\" value=\"1\" />&nbsp;Show non fee-earning projects?</p>";

	echo "<p><input type=\"submit\" /><input type=\"hidden\" name=\"output\" value=\"ListProjectsbyHours\" /></p>";

echo "</form>";

echo "</div>";


echo "<div><h3>List Project Cost by User</h3>";

echo "<form action=\"index2.php?page=timesheet_analysis_output\" method=\"post\">";

	echo "<p>Select Project</p><p>";
	
	ProjectSelect($proj_id_select,"proj_id");

	echo "</p><p>Select Beginning of Period</p>";

	echo "<p><input type=\"date\" name=\"period_date_start\" /></p>";

	echo "<p>Select End of Period</p>";

	echo "<p><input type=\"date\" name=\"period_date_end\" /></p>";

	echo "<p><input type=\"submit\" /><input type=\"hidden\" name=\"output\" value=\"ListHoursByUser\" /></p>";

echo "</form>";

echo "</div>";

TimesheetListZeroCost();

TimesheetListLowCost();