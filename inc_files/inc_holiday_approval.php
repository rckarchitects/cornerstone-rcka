<?php

if ($_GET[year] != NULL) { $year = $_GET[year]; } else { $year = date("Y",time()); }

echo "<h1>Holiday Calendar</h1>";

echo "<h2>" .  $year . "</h2>";

echo "<p class=\"menu_bar\">";

echo "<a href=\"index2.php?page=holiday_approval&amp;year=" . ( $year - 1 ) . "\" class=\"menu_tab\">< " . ( $year - 1 ) . "</a>";


echo "<a href=\"index2.php?page=holiday_approval&amp;year=" . ( $year + 1 ) . "\" class=\"menu_tab\">" . ( $year + 1 ) . " ></a>";

echo "</p>";

// Create a calendar showing the whole year

$beginnning_of_this_year = mktime(12,0,0,1,1,$year);
$beginnning_of_next_year = mktime(12,0,0,1,1,($year + 1));

$working_days = 0;

$beginnning_of_next_year =  BeginWeek( $beginnning_of_this_year + (60 * 60 * 24 * 7 * 53) );

$monday = BeginWeek($beginnning_of_this_year) - 43200;

$counter_time = $monday  ;

$this_year = $year;

	if ($user_usertype_current > 3) {
		echo "<form method=\"post\" action=\"index2.php?page=holiday_approval&amp;year=$year\"><input type=\"hidden\" value=\"$_COOKIE[user]\" name=\"holiday_approved\" /><input type=\"hidden\" value=\"holiday_approve\" name=\"action\" />";
	}

echo "<table>";

$background = " style=\"background: rgba(200,200,200,0); height: 40px; color: #999\"";

echo "<tr><td style=\"width: 10%;\">Week</td><td  style=\"width: 18%;\">Monday</td><td style=\"width: 18%;\">Tuesday</td><td style=\"width: 18%;\">Wednesday</td><td style=\"width: 18%;\">Thursday</td><td style=\"width: 18%;\">Friday</td></tr>";

echo "<tr><td $background>";
if (date ("W", BeginWeek($counter_time + 604800)) < 2) {
	echo date ("W", BeginWeek($counter_time + 604800));
}
echo "</td>";

while ($counter_time < $beginnning_of_next_year) {
	
	$counter_date = date("j",$counter_time);
	$counter_month = date("n",$counter_time);
	$counter_year = date("Y",$counter_time);
	
	$this_week_begin = BeginWeek(time());
	$this_week_end = $this_week_begin + (60*60*24*7);
	
	$sql_bankholidays = "SELECT bankholidays_description FROM intranet_user_holidays_bank WHERE bankholidays_day = $counter_date AND bankholidays_month = $counter_month AND bankholidays_year = $counter_year LIMIT 1";
	$result_bankholidays = mysql_query($sql_bankholidays, $conn);
	$array_bankholidays = mysql_fetch_array($result_bankholidays);
	$bankholidays_description = $array_bankholidays['bankholidays_description'];
	
	
	if (date("z",$counter_time) == date("z",time()) && date("Y",$counter_time) == date("Y",time()) ) {
	$background = " style=\"background: rgba(200,200,0,1); height: 40px; color: #999\"";
	} elseif ($counter_year != $this_year) {
	$background = " style=\"background: rgba(200,200,200,0); height: 40px; color: #999\"";
	} elseif ( $bankholidays_description) {
	$background = " style=\"background: rgba(200,200,200,0.5); height: 40px; color: #999\"";
	} elseif ( $counter_time > $this_week_begin && $counter_time < $this_week_end) {
	$background = " style=\"background: rgba(200,200,0,0.7); height: 40px; color: #999\"";
	} elseif ($counter_month == 1 OR $counter_month == 3 OR $counter_month == 5 OR $counter_month == 7 OR $counter_month == 9 OR $counter_month == 11) {
	$background = " style=\"background: rgba(200,0,200,0.25); height: 40px; color: #999\"";
	} else {
	$background = " style=\"background: rgba(0,200,200,0.25); height: 40px; color: #999\"";
	}
	
	
	
	$sql_holiday_list = "SELECT user_id, user_initials, holiday_approved, holiday_id, holiday_length, holiday_paid, holiday_assigned, holiday_year FROM intranet_user_holidays, intranet_user_details WHERE user_id = holiday_user AND holiday_date = $counter_date AND holiday_month = $counter_month AND holiday_year = $counter_year ORDER BY user_initials";
	$result_holiday_list = mysql_query($sql_holiday_list, $conn);
	

		if (date("w", $counter_time) > 0 AND date("w", $counter_time) < 6) {
		echo "<td $background><span class=\"minitext\">" . TimeFormat($counter_time) . "<br />$bankholidays_description</span>";
		
		if (mysql_num_rows($result_holiday_list) > 0) { echo "<br />"; }
		
			while ($array_holiday_list = mysql_fetch_array($result_holiday_list)) {
				$user_initials = $array_holiday_list['user_initials'];
				$holiday_approved = $array_holiday_list['holiday_approved'];
				$holiday_id = $array_holiday_list['holiday_id'];
				$holiday_length = $array_holiday_list['holiday_length'];
				$holiday_paid = $array_holiday_list['holiday_paid'];
				$holiday_year = $array_holiday_list['holiday_year'];
				$holiday_assigned = $array_holiday_list['holiday_assigned'];
				
				if ($holiday_year != $holiday_assigned) { $assignment = "<span class=\"HideThis\">&nbsp;(Assigned to&nbsp;" . $holiday_assigned . ")</span>"; } else { unset($assignment); }
				
				$user_id = $array_holiday_list['user_id'];
				
				if ($holiday_paid == 0) { $user_initials = $user_initials . "<span class=\"HideThis\">: <i>Unpaid Leave</i></span>"; }
				elseif ($holiday_paid == 2) { $user_initials = $user_initials . "<span class=\"HideThis\">: <i>Study Leave</i></span>"; }
				elseif ($holiday_paid == 3) { $user_initials = $user_initials . "<span class=\"HideThis\">: <i>Jury Service</i></span>"; }
				elseif ($holiday_paid == 4) { $user_initials = $user_initials . "<span class=\"HideThis\">: <i>TOIL</i></span>"; }
				elseif ($holiday_paid == 5) { $user_initials = $user_initials . "<span class=\"HideThis\">: <i>Discretionary Leave</i></span>"; }
				elseif ($holiday_paid == 6) { $user_initials = $user_initials . "<span class=\"HideThis\">: <i>Maternity / Paternity Leave</i></span>"; }
				
				if ($holiday_length == 0.5) { $user_initials = $user_initials . "<span class=\"HideThis\"> (half day)</span>"; }
				
				$user_initials = $user_initials . $assignment;
				
				if ($user_usertype_current > 3)  {
						$action = "&nbsp;<input type=\"checkbox\" name=\"holiday_id[]\" value=\"$holiday_id\" class=\"HideThis\" />&nbsp;";
				} else { unset($action); }
				
				if ($holiday_approved != NULL) { 
				echo "<span style=\"color: #000;\">" . $action  . $user_initials;
				} else {
				echo "<span style=\"color: #f00;\">". $action . $user_initials;
				}
				
				
				echo "<br />";
				
				echo "</span>";
			}
		
		echo "</td>";
		}

	

	// This checks that an additional line is not added if the last day of the year falls on a Saturday
		if (date("w", $counter_time) == 6 && date("Y", $counter_time + 172800) == $year) {
		$background = " style=\"background: rgba(200,200,200,0); height: 40px; color: #999\"";
		echo "</tr><tr id=\"Week" . date("W", $counter_time + 604800) . "\"><td $background>" . date("W", $counter_time + 604800). "</td>";
		}
		
		$counter_time = $counter_time + 86400;
}

echo "</tr></table>";


if ($user_usertype_current > 3) { ChangeHolidays($year); }
		
		
		echo "</form>";
	
	
// Holiday calculations

$working_days = WorkingDays($year);

if ($user_usertype_current > 3) { HolidaySchedule($year,$user_usertype_current,$working_days,$beginnning_of_this_year,$beginnning_of_next_year); }
	

