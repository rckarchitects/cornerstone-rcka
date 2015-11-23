<?php

if ($proj_date_start > 0) { $date_day = date("j",$proj_date_start); } else { $date_day = 0; }
if ($proj_date_start > 0) { $date_month = date("n",$proj_date_start); } else { $date_month = 0; }
if ($proj_date_start > 0) { $date_year = date("Y",$proj_date_start); } else { $date_year = 0; }

$count_day = 1;
$count_month = 1;
$count_year = 1;
		
		print "<p>Day&nbsp;<select name=\"proj_date_start_day\" class=\"inputbox\">";
		print "<option value=\"\">N/A";
		while($count_day<=31) {
		print "<option value=\"$count_day\"";
			if ($date_day == $count_day) { print " SELECTED"; }
		print ">".$count_day."</option>";	
		$count_day++;
		}
		
print "</select>&nbsp;&nbsp;";

		
		print "Month&nbsp;<select name=\"proj_date_start_month\" class=\"inputbox\">";
		
		print "<option value=\"0\">N/A";
		
		while($count_month<=12) {
			
			if ($count_month == "1") { $count_month_name = "January"; }
			if ($count_month == "2") { $count_month_name = "February"; }
			if ($count_month == "3") { $count_month_name = "March"; }
			if ($count_month == "4") { $count_month_name = "April"; }
			if ($count_month == "5") { $count_month_name = "May"; }
			if ($count_month == "6") { $count_month_name = "June"; }
			if ($count_month == "7") { $count_month_name = "July"; }
			if ($count_month == "8") { $count_month_name = "August"; }
			if ($count_month == "9") { $count_month_name = "September"; }
			if ($count_month == "10") { $count_month_name = "October"; }
			if ($count_month == "11") { $count_month_name = "November"; }
			if ($count_month == "12") { $count_month_name = "December"; }
			
		print "<option value=\"$count_month\"";
			if ($date_month == $count_month) { print " selected"; }
		print ">".$count_month_name;	
		$count_month++;
		}

print "</select>&nbsp;&nbsp;";

		$year_start = date("Y",time()) - 5;
		$year_end = 10;
				
		print "Year&nbsp;<select name=\"proj_date_start_year\" class=\"inputbox\">";
		print "<option value=\"0\">N/A";
		while($count_year<=$year_end) {
			$count_year_print = $count_year + $year_start;
		print "<option value=\"$count_year_print\"";
			if ($date_year == $count_year_print) { print " selected"; }
		print ">".$count_year_print;	
		$count_year++;
		}

print "</select>";



?>
