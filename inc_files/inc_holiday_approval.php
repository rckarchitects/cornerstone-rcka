<?php

if ($_GET[year] != NULL) { $year = $_GET[year]; } else { $year = date("Y",time()); }

echo "<h1>Holiday Calendar $year</h1>";

echo "<p class=\"menu_bar\">";

echo "<a href=\"index2.php?page=holiday_approval&amp;year=" . ( $year - 1 ) . "\" class=\"menu_tab\">< " . ( $year - 1 ) . "</a>";


echo "<a href=\"index2.php?page=holiday_approval&amp;year=" . ( $year + 1 ) . "\" class=\"menu_tab\">" . ( $year + 1 ) . " ></a>";

echo "</p>";

// Create a calendar showing the whole year

$beginnning_of_this_year = mktime(12,0,0,1,1,$year);
$beginnning_of_next_year = mktime(12,0,0,1,1,($year + 1));

$beginnning_of_next_year =  BeginWeek( $beginnning_of_this_year + (60 * 60 * 24 * 7 * 53) );

$monday = BeginWeek($beginnning_of_this_year) - 43200;

$counter_time = $monday  ;

$this_year = $year;

	if ($user_usertype_current > 3) {
		echo "<form method=\"post\" action=\"index2.php?page=holiday_approval&amp;year=$year\"><input type=\"hidden\" value=\"$_COOKIE[user]\" name=\"holiday_approved\" /><input type=\"hidden\" value=\"holiday_approve\" name=\"action\" />";
	}

echo "<table>";

$background = " style=\"background: rgba(200,200,200,0); height: 40px; color: #999\"";

echo "<tr><td style=\"width: 10%;\">Week</td><td  style=\"width: 18%;\">Monday</td><td style=\"width: 18%;\">Tuesday</td><td style=\"width: 18%;\">Wednesday</td><td style=\"width: 18%;\">Thursday</td><td style=\"width: 18%;\">Friday</td></tr><tr><td $background>1</td>";

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
	
	
	if ($counter_year != $this_year) {
	$background = " style=\"background: rgba(200,200,200,0); height: 40px; color: #999\"";
	} elseif ( $bankholidays_description != NULL) {
	$background = " style=\"background: rgba(200,200,200,0.5); height: 40px; color: #999\"";
	} elseif ( $counter_time > $this_week_begin && $counter_time < $this_week_end) {
	$background = " style=\"background: rgba(200,200,0,0.7); height: 40px; color: #999\"";
	} elseif ($counter_month == 1 OR $counter_month == 3 OR $counter_month == 5 OR $counter_month == 7 OR $counter_month == 9 OR $counter_month == 11) {
	$background = " style=\"background: rgba(200,0,200,0.25); height: 40px; color: #999\"";
	} else {
	$background = " style=\"background: rgba(0,200,200,0.25); height: 40px; color: #999\"";
	}
	
	$sql_holiday_list = "SELECT user_id, user_initials, holiday_approved, holiday_id, holiday_length, holiday_paid FROM intranet_user_holidays, intranet_user_details WHERE user_id = holiday_user AND holiday_date = $counter_date AND holiday_month = $counter_month AND holiday_year = $counter_year ORDER BY user_initials";
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
				$user_id = $array_holiday_list['user_id'];
				
				if ($holiday_paid != 1) { $user_initials = "[" . $user_initials . "]"; }
				
				if ($holiday_length == 0.5) { $user_initials = $user_initials . " (half day)"; }
				
				if ($user_usertype_current > 3)  {
						$action = "&nbsp;<input type=\"checkbox\" name=\"holiday_id[]\" value=\"$holiday_id\" />&nbsp;";
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

	$counter_time = $counter_time + 86400;
	
		if (date("w", $counter_time) == 6) {
		$background = " style=\"background: rgba(200,200,200,0); height: 40px; color: #999\"";
		echo "</tr><tr><td $background>" . date("W", $counter_time). "</td>";
		}
}

echo "</tr></table>";


	if ($user_usertype_current > 3) {
		
		echo "<p>
		<input type=\"radio\" value=\"approve\" name=\"approve\" checked=\"checked\" />&nbsp;Approve<br />
		<input type=\"radio\" value=\"delete\" name=\"approve\" />&nbsp;Delete<br/ >
		<input type=\"radio\" value=\"to_paid\" name=\"approve\" />&nbsp;Make Paid Holiday<br />
		<input type=\"radio\" value=\"to_unpaid\" name=\"approve\" />&nbsp;Make Unpaid Holiday</p><p>
		<input type=\"hidden\" value=\"$_COOKIE[user]\" name=\"user_id\" />
		<input type=\"submit\" value=\"Submit\" /></p></form>";
	
	
// Holiday calculations	
	
	
$year = date ("Y", time());	
	
	

echo "<h2 id=\"holidaysthisyear\">Holidays in $year</h2>";

if ($user_usertype_current < 3) { $limit = "AND user_id = $user_id"; } else { unset( $limit );}

$sql_users = "SELECT * FROM intranet_user_details WHERE (
(user_user_added BETWEEN $beginnning_of_this_year AND $beginnning_of_next_year)
OR (user_user_ended BETWEEN $beginnning_of_this_year AND $beginnning_of_next_year)
OR (user_user_added < $beginnning_of_this_year AND (user_user_ended = 0 OR user_user_ended IS NULL))
) $limit ORDER BY user_name_second";


$result_users = mysql_query($sql_users, $conn);
echo "<table>";

echo "<tr><th colspan=\"6\">User Details</th><th colspan=\"4\">$year Only</th><th colspan=\"2\">All Time</th></tr>";
echo "<tr><th>Name</th><th>Date Started</th><th>Until</th><th>Years</th><th>Annual Allowance</th><th>Total Allowance</th><th>Allowance</th><th>Paid Holiday</th><th>Unpaid Holiday</th><th>Year Total</th><th>Holiday Taken</th><th>Holiday Remaining to end of $year</th></tr>";

while ($array_users = mysql_fetch_array($result_users)) {


	$holiday_datum = mktime(0,0,0,1,1,2012);

	$nextyear = $year + 1;

	$user_id = $array_users['user_id'];
	$user_name_first = $array_users['user_name_first'];
	$user_name_second = $array_users['user_name_second'];
	$user_holidays = $array_users['user_holidays'];
	$user_user_added = $array_users['user_user_added'];
	if ($user_user_added == NULL OR $user_user_added < $holiday_datum ) { $user_user_added = $holiday_datum; }
	$user_user_ended = $array_users['user_user_ended'];
	if ($user_user_ended == NULL OR $user_user_ended == 0) { $user_user_ended = mktime(0,0,0,1,1,$nextyear); }
	
	$holiday_allowance = $user_user_ended - $user_user_added;
	$yearlength = 365.242 * 24 * 60 * 60;
	$holiday_allowance = ( $holiday_allowance / $yearlength ) * $user_holidays;
	$holiday_allowance = round($holiday_allowance);
	
	if ($holiday_allowance < $user_holidays) { $year_allowance = $holiday_allowance; } else { $year_allowance = $user_holidays; }
	
	
	$holiday_paid_total = 0;
	$holiday_unpaid = 0;
	$holiday_total = 0;
	$holiday_total_year = 0;
	
	$sql_count = "SELECT * FROM intranet_user_holidays WHERE holiday_user = $user_id AND holiday_year <= $year AND holiday_timestamp > $user_user_added ORDER BY holiday_timestamp";
	$result_count = mysql_query($sql_count, $conn);
	while ($array_count = mysql_fetch_array($result_count)) {

		$holiday_year = $array_count['holiday_year'];
		$holiday_length = $array_count['holiday_length'];
		$holiday_paid = $array_count['holiday_paid'];
		
		$user_allowance = UserHolidays($user_id);
		
					if ($holiday_year == $year) {
					
								if ($holiday_paid == 1) { $holiday_paid_total = $holiday_paid_total + $holiday_length; }
								else { $holiday_unpaid = $holiday_unpaid + $holiday_length; }
								$holiday_total_year = $holiday_total_year + $holiday_length;

					} else {
								//if ($holiday_paid == 1) { $holiday_paid_total = $holiday_paid_total + $holiday_length; }
					
					}
					
					

					
					if ($holiday_paid == 1) { $holiday_total = $holiday_total + $holiday_length; }
					
		
		}
		
	$holiday_remaining = $holiday_allowance - $holiday_total;
	
	$length =  round((($user_user_ended - $user_user_added) / 31556908.8), 2); 
		
	echo "
	<tr>
	<td><a href=\"index2.php?page=holiday_approval&amp;showuser=$user_id&year=$_GET[year]#holidaysthisyear\">$user_name_first $user_name_second</a></td>
	<td>" . date ( "d M Y", $user_user_added ) . "</td>
	<td>" . date ( "d M Y", $user_user_ended ) . "</td>
	<td>$length</td>
	<td style=\"text-align:right;\">$user_holidays</td>
	<td style=\"text-align:right;\">$holiday_allowance</td>
	<td style=\"text-align:right;\">$year_allowance</td>
	<td style=\"text-align:right;\">$holiday_paid_total</td>
	<td style=\"text-align:right;\">$holiday_unpaid</td>
	<td style=\"text-align:right;\">$holiday_total_year</td>
	<td style=\"text-align:right;\">$holiday_total</td>
	<td style=\"text-align:right;\">$holiday_remaining</td>
	</tr>";
	
	if ($_GET[showuser] == $user_id) {
		
			$sql_totalhols = "SELECT holiday_timestamp, holiday_length, holiday_paid FROM intranet_user_holidays WHERE holiday_user = $user_id ORDER BY holiday_timestamp";
			$result_totalhols = mysql_query($sql_totalhols, $conn);

				if (mysql_num_rows($result_totalhols) > 0) {
					
					echo "<tr><th></th><th colspan=\"4\">Date</th><th>Paid Holiday</th><th>Unpaid Holiday</th><th colspan=\"6\"></th></tr>";
					
						$totalhols_count = 0;
						$totalholsup_count = 0;
					
						while ($array_totalhols = mysql_fetch_array($result_totalhols)) {
							
							if ($array_totalhols['holiday_paid'] > 0) {
								$totalhols_count = $totalhols_count + $array_totalhols['holiday_length'];
								echo "<tr><td></td><td colspan=\"4\">" . date ( "l, j F Y", $array_totalhols['holiday_timestamp'] ) . "</td><td style=\"text-align: right;\">$totalhols_count</td><td></td><td colspan=\"5\"></td></tr>";
							} else {
								$totalholsup_count = $totalholsup_count + $array_totalhols['holiday_length'];
								echo "<tr><td></td><td colspan=\"4\"><i>" . date ( "l, j F Y", $array_totalhols['holiday_timestamp'] ) . "</i></td><td></td><td style=\"text-align: right;\"><i>$totalholsup_count</i></td><td colspan=\"5\"></td></tr>";
							}
						
						}
						
						echo "<tr><td></td><td colspan=\"4\"><strong>Total</strong></td><td style=\"text-align: right;\"><strong>$totalhols_count</strong></td><td style=\"text-align: right;\"><strong>$totalholsup_count</strong></td><td colspan=\"6\"></th></tr>";
					
					
				}
		
		
	}


}

echo "</table>";








 }














?>
