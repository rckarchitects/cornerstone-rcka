<?php

function UserAccessType($selectname,$user_usertype,$currentlevel,$maxlevel) {
	

	echo "<select name=\"$selectname\">";
		
			echo "<option value=\"1\"";
				if ($currentlevel == 1) { echo " selected=\"selected\" "; }
			echo ">Guest</option>";
			
			echo "<option value=\"2\"";
				if ($currentlevel == 2) { echo " selected=\"selected\" "; }
			echo ">Basic User</option>";
			
			echo "<option value=\"3\"";
				if ($currentlevel == 3) { echo " selected=\"selected\" "; }
			echo ">Standard User</option>";
			
			echo "<option value=\"4\"";
				if ($currentlevel == 4) { echo " selected=\"selected\" "; }
			echo ">Power User</option>";
			
			echo "<option value=\"5\"";
				if ($currentlevel > 4) { echo " selected=\"selected\" "; }
			echo ">Administrator</option>";
		
	
		echo "</select>";
		
}

function UsersList($active) {
	
	GLOBAL $conn;
	
			echo "<h2>Users</h2>";
	
			if ($active == 0) {
				$showactive = " WHERE user_active = 1 ";
			
			} else {
								
				unset($showactive);
			}

			$sql = "SELECT * FROM intranet_user_details LEFT JOIN intranet_team ON team_id = user_team " . $showactive . "  ORDER BY user_active DESC, user_name_second";
			$result = mysql_query($sql, $conn);
			
			
			echo "<table><tr><th>Name</th><th>Initials</th><th>Team</th><th>Date Started</th><th>Date Ended</th><th>Mobile</th><th>Email</th><th colspan=\"2\">User Type</th><th style=\"text-align: right;\">Hourly Rate (Cost)</th><th style=\"text-align: right;\">Weekly Hours</th><th style=\"text-align: right;\">Target Fee-Earning Hours</tr><tr><th colspan=\"11\" style=\"text-align: right;\"><span class=\"minitext\">Equivalent Hourly Rate<br />Total Weekly Rate</span></th></tr>";
			
			$cost_per_hour_total = 0;
			$cost_per_week_total = 0;
			$total_hours_week = 0;
			$total_hourly_worked = 0;
			$total_people = 0;
			$total_hourly_cost = 0;
			
			while ($array = mysql_fetch_array($result)) {
				
					$user_id = $array['user_id'];
					$user_name_first = $array['user_name_first'];
					$user_name_second = $array['user_name_second'];
					$user_initials = $array['user_initials'];
					$user_num_mob = $array['user_num_mob'];
					$user_email = $array['user_email'];
					$user_active = $array['user_active'];
					$user_usertype = $array['user_usertype'];
					$user_timesheet_hours = $array['user_timesheet_hours'];
					$user_prop_target = $array['user_prop_target'];
					if ($array['user_user_added'] > 0) { $user_user_added = TimeFormatDay($array['user_user_added']); } else { $user_user_added = "-"; }
					if ($array['user_user_ended'] > 0) { $user_user_ended = TimeFormatDay($array['user_user_ended']); } else { $user_user_ended = "-"; }
					$user_user_rate = "&pound;" . number_format($array['user_user_rate'],2);
					
					if ($user_active != 1) { $user_timesheet_hours = 0; }
					
					$fee_earning_hours_per_week = intval((1 - $user_prop_target) * $user_timesheet_hours);
					
					$cost_per_hour = $fee_earning_hours_per_week * $array['user_user_rate'] / $user_timesheet_hours;
					
					if ($cost_per_hour > 0) { $total_people++ ; $total_hourly_cost = $total_hourly_cost + $cost_per_hour; }
					
					$cost_per_week = $cost_per_hour * $user_timesheet_hours;
					
					$cost_per_hour_total = $cost_per_hour_total + $cost_per_hour;
					$cost_per_week_total = $cost_per_week_total + $cost_per_week;
					$total_hours_week = $total_hours_week + $fee_earning_hours_per_week;
					
					if ($user_usertype == 1) { $user_usertype = "(1)</td><td>Guest"; }
					elseif ($user_usertype == 2) { $user_usertype = "(2)</td><td>Basic User"; }
					elseif ($user_usertype == 3) { $user_usertype = "(3)</td><td>Standard User"; }
					elseif ($user_usertype == 4) { $user_usertype = "(4)</td><td>Power User"; }
					elseif ($user_usertype == 5) { $user_usertype = "(5)</td><td>Administrator"; }
					
					if ($user_active == "1") { $user_active_print = "Active Users"; } else { $user_active_print = "Inactive Users"; }
					
					if ($current_active != $user_active) { echo "<tr><td colspan=\"12\"><strong>" . $user_active_print . "</strong></td></tr>"; $current_active = $user_active;  }
					
					echo "<tr><td><a href=\"index2.php?page=user_view&amp;user_id=" . $user_id . "\">" . $user_name_first . " " . $user_name_second . "</a>&nbsp;<a href=\"index2.php?page=user_edit&amp;status=edit&user_id=" . $user_id . "\"><img src=\"images/button_edit.png\" class=\"button\" alt=\"Edit\" /></a></td><td>" . $user_initials . "</td><td>" . $array['team_name'] . "</td><td>" . $user_user_added . "</td><td>" . $user_user_ended . "</td><td>" . $user_num_mob . "</td><td>" . $user_email . "</td><td>" . $user_usertype . "</td><td style=\"text-align: right;\">" . $user_user_rate . "</td><td style=\"text-align: right;\">" . $user_timesheet_hours . "</td><td style=\"text-align: right;\">" . $fee_earning_hours_per_week . "<span class=\"minitext\"><br />&pound;" . number_format($cost_per_hour,2) . "<br />&pound;" . number_format($cost_per_week,2) . "</span>
					</td></tr>";

								
			}
			
			echo "<tr><td>Total Fee Hours</td><td colspan=\"11\" style=\"text-align: right;\">" . number_format ( $total_hours_week ) . "</td><td></td></tr>";
			echo "<tr><td>Total Hourly Fee</td><td colspan=\"11\" style=\"text-align: right;\">" . MoneyFormat($cost_per_hour_total) . "</td></tr>";
			echo "<tr><td>Total Weekly Fee</td><td colspan=\"11\" style=\"text-align: right;\">" . MoneyFormat($cost_per_week_total) . "</td></tr>";
			echo "<tr><td>Total Fee Earners</td><td colspan=\"11\" style=\"text-align: right;\">" . number_format ($total_people) . "</td></tr>";
			echo "<tr><td>Average Hourly Fee</td><td colspan=\"11\" style=\"text-align: right;\">" . MoneyFormat($total_hourly_cost / $total_people) . "</td></tr>";
			echo "<tr><td>Average Weekly Cost</td><td colspan=\"11\" style=\"text-align: right;\">" . MoneyFormat(($total_hourly_cost / $total_people) * 40) . "</td></tr>";
			echo "</table>";
		
						
}

function UserChangePasswordForm($user_id) {
	
	$user_id = intval($user_id);
	
	global $conn;
	
		echo "<div>";
		echo "<form method=\"post\" action=\"http://intranet.rcka.co/index2.php?page=user_view&amp;user_id=" . $user_id . "\">";
		echo "<h3>Enter new password</h3><p><input type=\"password\" name=\"user_password1\" value=\"\" required=\"required\" /></p>";
		echo "<h3>Repeat new password</h3><p><input type=\"password\" name=\"user_password2\" value=\"\" required=\"required\" /></p>";
		echo "<input type=\"hidden\" value=\"" . $user_id . "\" name=\"user_id\" />";
		echo "<input type=\"hidden\" value=\"user_change_password\" name=\"action\" />";
		if ($user_id == $_COOKIE[user]) {
			echo "<p>Please note that if you are changing your own password you will be automatically logged out and will need to login again using your new password.</p>";
		} else {
			echo "<p>Changing a user's password will automatically log them out of the system and they will be required to login again using their new password.</p>";
		}
		echo "<p><input type=\"submit\" />";
		echo "</form>";
		echo "</div>";
	
}

function GetUserName($user_id) {
	
	GLOBAL $conn;
	GLOBAL $user_usertype_current;
	$user_id = intval($user_id);
	$sql = "SELECT * FROM intranet_user_details WHERE user_id = '$user_id' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];
	
	if ($user_id > 0 && $user_usertype_current > 3) { 
		echo "<h2>" . $user_name_first . "&nbsp;" . $user_name_second . "</h2>";
	} elseif ($user_id == 0 && $user_usertype_current > 3) { 
		echo "<h2>Add New User</h2>";
	} else {
		echo "<h2>Error</h2>";
	}
}

function GetUserNameOnly($user_id) {
	
	GLOBAL $conn;
	GLOBAL $user_usertype_current;
	$user_id = intval($user_id);
	$sql = "SELECT * FROM intranet_user_details WHERE user_id = '$user_id' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];
	
	$username = $user_name_first . " " . $user_name_second;
	
	return $username;
}

function UserForm ($user_id) {
	
	GLOBAL $user_usertype_current;
	GLOBAL $conn;
	
	$sql = "SELECT * FROM intranet_user_details WHERE user_id = '$user_id' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$user_address_county = $array['user_address_county'];
	$user_address_postcode = $array['user_address_postcode'];
	$user_address_town = $array['user_address_town'];
	$user_address_3 = $array['user_address_3'];
	$user_address_2 = $array['user_address_2'];
	$user_address_1 = $array['user_address_1'];
	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];
	$user_num_extension = $array['user_num_extension'];
	$user_num_mob = $array['user_num_mob'];
	$user_num_home = $array['user_num_home'];
	$user_email = $array['user_email'];
	$user_usertype = intval ( $array['user_usertype'] );
	$user_active = $array['user_active'];
	$user_username = $array['user_username'];
	$user_user_rate = $array['user_user_rate'];
	$user_user_added = $array['user_user_added'];
	$user_user_ended = $array['user_user_ended'];
	$user_user_timesheet = $array['user_user_timesheet'];
	$user_holidays = $array['user_holidays'];
	$user_initials = $array['user_initials'];
	$user_prop_target = $array['user_prop_target'];
	$user_timesheet_hours = $array['user_timesheet_hours'];
	$user_notes = $array['user_notes'];
	$user_team = $array['user_team'];
	
	echo "<form method=\"post\" action=\"index2.php?page=user_list\" autocomplete=\"off\">";
	
	echo "<div><h3>Name</h3>";
	
		echo "<p>First Name<br /><input type=\"text\" name=\"user_name_first\" value=\"" . $user_name_first . "\" maxlength=\"50\" size=\"32\" required=\"required\" /></p>";
		echo "<p>Surname<br /><input type=\"text\" name=\"user_name_second\" value=\"$user_name_second\" maxlength=\"50\" size=\"32\" required=\"required\" /></p>";
		if ($user_usertype_current > 2) {
		echo "<p>Username<br /><input type=\"text\" name=\"user_username\" value=\"$user_username\" maxlength=\"50\" size=\"32\" required=\"required\" /></p>";
		} else {
		echo "<p>Username</p><p><span style=\"margin: 2px; padding: 2px; background: #fff;\">$user_username</span> (Cannot be changed)</p>";
		}
		echo "<p>Initials<br /><input type=\"text\" name=\"user_initials\" value=\"$user_initials\" maxlength=\"12\" size=\"32\" /></p>";
		echo "<p>Email<br /><input type=\"text\" name=\"user_email\" value=\"$user_email\" maxlength=\"50\" size=\"32\" type=\"email\" /></p>";
		
		echo "<p>Team<br />"; UserTeamSelect('user_team',$user_team); echo "</p>";
		
	echo "</div>";
	
	
	echo "<div><h3>Home Address</h3>";
	
		echo "<p>Address<br /><input type=\"text\" name=\"user_address_1\" value=\"$user_address_1\" maxlength=\"50\" size=\"32\" /><br />";
		echo "<input type=\"text\" name=\"user_address_2\" value=\"$user_address_2\" maxlength=\"50\" size=\"32\" /><br />";
		echo "<input type=\"text\" name=\"user_address_3\" value=\"$user_address_3\" maxlength=\"50\" size=\"32\" /></p>";
		
		echo "<p>Town / City<br /><input type=\"text\" name=\"user_address_town\" value=\"$user_address_town\" maxlength=\"50\" size=\"32\" /></p>";
		echo "<p>County<br /><input type=\"text\" name=\"user_address_county\" value=\"$user_address_county\" maxlength=\"50\" size=\"32\" /></p>";
		echo "<p>Postcode<br /><input type=\"text\" name=\"user_address_postcode\" value=\"$user_address_postcode\" maxlength=\"50\" size=\"32\" /></p>";
		
	echo "</div>";
	
	echo "<div><h3>Telephone</h3>";
	
		echo "<p>Extension<br /><input type=\"text\" name=\"user_num_extension\" value=\"$user_num_extension\" maxlength=\"50\" size=\"32\" /></p>";
		echo "<p>Mobile<br /><input type=\"text\" name=\"user_num_mob\" value=\"$user_num_mob\" maxlength=\"50\" size=\"32\" /></p>";
		echo "<p>Home<br /><input type=\"text\" name=\"user_num_home\" value=\"$user_num_home\" maxlength=\"50\" size=\"32\" /></p>";
		
	echo "</h3>";
	
	
	echo "<div><h3>Notes</h3>";
	
		echo "<textarea name=\"user_notes\" style=\"width: 95%; height: 150px;\">$user_notes</textarea>";
		
	echo "</div>";
	
	if ($user_usertype_current > 3) {
	
		echo "<div><h3>Details</h3>";
		
		
		echo "<p>User Type<br />";
		
		UserAccessType("user_usertype",0,$user_usertype,0);
		
		echo "<p><input type=\"checkbox\" name=\"user_active\" value=\"1\"";
		if ($user_active == 1 OR $user_id == NULL) { echo "checked=checked "; }
		echo "/>&nbsp;User Active</p>";
		echo "<p>Holiday Allowance<br /><input type=\"text\" name=\"user_holidays\" value=\"$user_holidays\" maxlength=\"6\" size=\"32\" type=\"number\" /></p>";
		echo "<p>Hourly Rate (excluding overheads)<br /><input name=\"user_user_rate\" value=\"$user_user_rate\" maxlength=\"12\" size=\"32\" type=\"number\" /></p>";
		echo "<p>Hours per Week<br /><input type=\"number\" name=\"user_timesheet_hours\" value=\"$user_timesheet_hours\" size=\"32\"  /></p>";
		echo "<p><input type=\"checkbox\" name=\"user_user_timesheet\" value=\"1\"";
		if ($user_user_timesheet == 1 OR $user_id == NULL) { echo "checked=checked "; }
		echo "/>&nbsp;Require Timesheets</p>";
		echo "<p>Non-Fee Earning Time Allowance<br />";
		echo "<select name=\"user_prop_target\">";
		echo "<option value=\"0\" "; if ($user_prop_target == 0) { echo "selected=\"selected\""; } ; echo ">None</option>";
		echo "<option value=\"0.05\" "; if ($user_prop_target == 0.05) { echo "selected=\"selected\""; } ; echo ">%5</option>";
		echo "<option value=\"0.1\" "; if ($user_prop_target == 0.1) { echo "selected=\"selected\""; } ; echo ">10%</option>";
		echo "<option value=\"0.15\" "; if ($user_prop_target == 0.15) { echo "selected=\"selected\""; } ; echo ">15%</option>";
		echo "<option value=\"0.2\" "; if ($user_prop_target == 0.2) { echo "selected=\"selected\""; } ; echo ">20%</option>";
		echo "<option value=\"0.25\" "; if ($user_prop_target == 0.25) { echo "selected=\"selected\""; } ; echo ">25%</option>";
		echo "<option value=\"0.3\" "; if ($user_prop_target == 0.3) { echo "selected=\"selected\""; } ; echo ">30%</option>";
		echo "<option value=\"0.35\" "; if ($user_prop_target == 0.35) { echo "selected=\"selected\""; } ; echo ">35%</option>";
		echo "<option value=\"0.4\" "; if ($user_prop_target == 0.4) { echo "selected=\"selected\""; } ; echo ">40%</option>";
		echo "<option value=\"0.45\" "; if ($user_prop_target == 0.45) { echo "selected=\"selected\""; } ; echo ">45%</option>";
		echo "<option value=\"0.5\" "; if ($user_prop_target == 0.5) { echo "selected=\"selected\""; } ; echo ">50%</option>";
		echo "<option value=\"0.55\" "; if ($user_prop_target == 0.55) { echo "selected=\"selected\""; } ; echo ">55%</option>";
		echo "<option value=\"0.60\" "; if ($user_prop_target == 0.6) { echo "selected=\"selected\""; } ; echo ">60%</option>";
		echo "<option value=\"0.65\" "; if ($user_prop_target == 0.65) { echo "selected=\"selected\""; } ; echo ">65%</option>";
		echo "<option value=\"0.70\" "; if ($user_prop_target == 0.7) { echo "selected=\"selected\""; } ; echo ">70%</option>";
		echo "<option value=\"0.75\" "; if ($user_prop_target == 0.75) { echo "selected=\"selected\""; } ; echo ">75%</option>";
		echo "<option value=\"0.80\" "; if ($user_prop_target == 0.8) { echo "selected=\"selected\""; } ; echo ">80%</option>";
		echo "<option value=\"0.85\" "; if ($user_prop_target == 0.85) { echo "selected=\"selected\""; } ; echo ">85%</option>";
		echo "<option value=\"0.9\" "; if ($user_prop_target == 0.9) { echo "selected=\"selected\""; } ; echo ">90%</option>";
		echo "<option value=\"0.95\" "; if ($user_prop_target == 0.95) { echo "selected=\"selected\""; } ; echo ">95%</option>";
		echo "<option value=\"1\" "; if ($user_prop_target == 1) { echo "selected=\"selected\""; } ; echo ">100%</option>";
		echo "</select></p>";
		echo "</div>";
	

	
	echo "<div><h3>Dates</h3>";
		
		if ($user_user_added > 0) {
			$user_user_added_print = date("Y",$user_user_added) . "-" . date("m",$user_user_added) . "-" . date("d",$user_user_added);
		} elseif ($user_id == NULL) {
			$user_user_added_print = date("Y",time()) . "-" . date("m",time()) . "-" . date("d",time());
		} else { unset($user_user_added); }
		
		if ($user_user_ended > 0) {
			$user_user_ended_print = date("Y",$user_user_ended) . "-" . date("m",$user_user_ended) . "-" . date("d",$user_user_ended);
		} else { unset($user_user_ended); }
	
		echo "<p>Date Started<br /><input type=\"date\" name=\"user_user_added\" value=\"$user_user_added_print\" /></p>";
		
		echo "<p>Date Ended<br /><input type=\"date\" name=\"user_user_ended\" value=\"$user_user_ended_print\" /></p>";
		
	echo "</div>";
	

	
	}
	
	if ($user_id > NULL) {
	echo "<input type=\"hidden\" name=\"action\" value=\"user_update\" />";
	echo "<input type=\"hidden\" name=\"user_id\" value=\"$user_id\" />";
	echo "<input type=\"submit\" value=\"Update\" />";
	} else {
	echo "<input type=\"submit\" value=\"Submit\" />";
	echo "<input type=\"hidden\" name=\"action\" value=\"user_update\" />";
	}
	
	echo "</form></p>";
	
	
	
	
}

function UserDetails($user) {
	
	global $conn;
	
	$user = intval($user);
	
	$sql = "SELECT user_name_first, user_name_second FROM intranet_user_details WHERE user_id = $user LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		$array = mysql_fetch_array($result);
		$name = "<a href=\"index2.php?page=user_view&amp;user_id=" . $user . "\">" . $array['user_name_first'] . " " . $array['user_name_second'] . "</a>";
	}
	
	return $name;
}

function UserDropdown($input_user,$input_name,$class) {
	
if (!$input_name) { $input_name = "user_id"; }
$input_user = intval($input_user);

GLOBAL $conn;

	$sql = "SELECT user_id, user_active, user_name_first, user_name_second FROM intranet_user_details ORDER BY user_active DESC, user_name_second";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	if ($class) { $class = "class=\"" . $class . "\""; } else { $class = "class=\"inputbox\""; }

	echo "<select " . $class . " name=\"" . $input_name . "\">";

	unset($user_active_test);
	
	while ($array = mysql_fetch_array($result)) {


		$user_id = $array['user_id'];
		$user_active = $array['user_active'];
		$user_name_first = $array['user_name_first'];
		$user_name_second = $array['user_name_second'];
		
		if ( $user_active != $user_active_test) {
				if ($user_active == 0) { echo "<option disabled></option><option disabled><i>Inactive Users</i></option><option disabled>------------------------</option>"; } else { echo "<option disabled><i>Active Users</i></option><option disabled>------------------------</option>"; } 
		$user_active_test = $user_active; }
		
            echo "<option value=\"$user_id\"";
            if ($user_id == $input_user) { echo "selected=\"selected\""; }
            echo ">".$user_name_first."&nbsp;".$user_name_second."</option>";
		}

	echo "</select>";
	
}

function UserGetTeam($user_id) {
	
	global $conn;
	$sql = "SELECT user_team FROM intranet_user_details WHERE user_id = " . intval($user_id) . " LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	
	return $array['user_team'];
	
}

function UserHolidays($user_id,$text,$year) {

	GLOBAL $database_location;
	GLOBAL $database_username;
	GLOBAL $database_password;
	GLOBAL $database_name;
	GLOBAL $settings_timesheetstart;
	
	if (!$year) { $year = date("Y",time()); }
	

	$conn = mysql_connect("$database_location", "$database_username", "$database_password");
	mysql_select_db("$database_name", $conn);
	
	// Establish the beginning of the year
		
	$this_year = date("Y",time());
	$next_year = $this_year + 1;
	$beginning_of_year = mktime(0,0,0,1,1,$this_year);
	$end_of_year = mktime(0,0,0,1,1,$next_year);
	
	$holiday_datum = mktime(0,0,0,1,1,2012);
	
	$sql_user_details = "SELECT user_user_added, user_user_ended, user_holidays FROM intranet_user_details WHERE user_id = $user_id";
	$result_user_details = mysql_query($sql_user_details, $conn) or die(mysql_error());
	$array_user_details = mysql_fetch_array($result_user_details);
	$user_user_added = $array_user_details['user_user_added'];
	if ($user_user_added == NULL OR $user_user_added < $holiday_datum ) { $user_user_added = $holiday_datum; }
	$user_user_ended = $array_user_details['user_user_ended'];
	$user_holidays = $array_user_details['user_holidays'];
	
	$sql_user_holidays = "SELECT SUM(holiday_length) FROM intranet_user_holidays WHERE holiday_user = $user_id AND holiday_paid = 1 AND holiday_timestamp < $end_of_year AND holiday_timestamp > $user_user_added";
	$result_user_holidays = mysql_query($sql_user_holidays, $conn) or die(mysql_error());
	$array_user_holidays = mysql_fetch_array($result_user_holidays);
	$user_holidays_taken = $array_user_holidays['SUM(holiday_length)'];
	
	//if ($user_user_added == NULL OR $user_user_added == 0) { $user_user_added = $settings_timesheetstart; }
	$begin_count = $user_user_added;
	
	if ($end_of_year > $user_user_ended AND $user_user_ended > 0) { $end_of_year = $user_user_ended; $ended = " (your employment ended on " . TimeFormat($user_user_ended) . ") "; }

	$seconds_to_end_of_year = $end_of_year - $begin_count;
	
	$years_total = $seconds_to_end_of_year / (365 * 60 * 60 * 24);
	
	$total_holidays_allowed = round($user_holidays * $years_total) - $user_holidays_taken;
	
	//$years_to_now = $seconds_to_end_of_year / (60 * 60 * 24 * 365);
	//$total_holidays_allowed =  ( round ( $user_holidays * $years_to_now ) ) - $user_holidays_taken;
	
	
	
	if ($text != NULL) {
	
		$workingdays = WorkingDays($year);
		
		$user_holiday_array = UserHolidaysArray($user_id,$year,$workingdays);
		//$array = array($length,$user_holidays,$holiday_allowance,$holiday_allowance_thisyear,$holiday_paid_total,$holiday_unpaid_total,$study_leave_total,$jury_service_total,$toil_service_total,$holiday_year_remaining,$listadd,$listend,$user_name_first, $user_name_second);
	
	echo "<p>Your annual holiday allowance is <strong>" . $user_holiday_array[1] . "</strong> days.</p><p>You are entitled to <strong>" . $user_holiday_array[9] . " days</strong> before the end of " . $year . "</p>";
	}
	
	return $total_holidays_allowed;
	
}

function UserHolidaysArray($user_id,$year,$working_days) {
	
	GLOBAL $conn;

			$sql_user = "SELECT user_user_added, user_user_ended, user_holidays, user_name_first, user_name_second FROM intranet_user_details WHERE user_id = " . intval($user_id) . " LIMIT 1";
			$result_user = mysql_query($sql_user, $conn);
			$array_user = mysql_fetch_array($result_user);
			$user_user_added = $array_user['user_user_added'];
			$user_user_ended = $array_user['user_user_ended'];
			$user_name_first = $array_user['user_name_first'];
			$user_name_second = $array_user['user_name_second'];
			
			$user_holidays = $array_user['user_holidays'];
			
			$holiday_datum = mktime(0,0,0,1,1,2012);
			
			$nextyear = $year + 1;
			
			if ($user_user_added == NULL OR $user_user_added < $holiday_datum ) { $user_user_added = $holiday_datum; $listadd = "-"; } else { $listadd = date ( "d M Y", $user_user_added ); }
			
			if ($user_user_ended == NULL OR $user_user_ended == 0) { $user_user_ended = mktime(0,0,0,1,1,$nextyear); $listend = "-"; } else { $listend = date ( "d M Y", $user_user_ended ); }
			
	
							$sql_count = "SELECT * FROM intranet_user_holidays WHERE holiday_user = " . intval($user_id) . " AND holiday_assigned = " . $year . " ORDER BY holiday_timestamp";
							$result_count = mysql_query($sql_count, $conn);
							while ($array_count = mysql_fetch_array($result_count)) {
							

								$holiday_year = $array_count['holiday_year'];
								$holiday_length = $array_count['holiday_length'];
								$holiday_paid = $array_count['holiday_paid'];
								
								$holiday_allowance = $user_user_ended - $user_user_added;
							$yearlength = 365.242 * 24 * 60 * 60;
							$holiday_allowance = ( $holiday_allowance / $yearlength ) * $user_holidays;
							$holiday_allowance = round($holiday_allowance);
							
							$holiday_allowance_thisyear = $user_user_ended - mktime(0,0,0,1,1,$year);
							if ($user_user_added > mktime(0,0,0,1,1,$year)) { $holiday_allowance_thisyear = $holiday_allowance_thisyear - ($user_user_added - mktime(0,0,0,1,1,$year)); }
							
							
							
							$holiday_allowance_thisyear = $holiday_allowance_thisyear / (365.242 * 24 * 60 * 60) ;
							
							if ($holiday_allowance < $user_holidays) { $year_allowance = $holiday_allowance; } else { $year_allowance = $user_holidays; }
							
					
							$holiday_allowance_thisyear = round ($user_holidays * $holiday_allowance_thisyear);
								
											
											if ($holiday_paid == 1) { $holiday_paid_total = $holiday_paid_total + $holiday_length; }
											elseif ($holiday_paid == 2) { $study_leave_total = $study_leave_total + $holiday_length; }
											elseif ($holiday_paid == 3) { $jury_service_total = $jury_service_total + $holiday_length; }
											elseif ($holiday_paid == 4) { $toil_service_total = $toil_service_total + $holiday_length; $holiday_paid_total = $holiday_paid_total - $holiday_length;  }
											elseif ($holiday_paid == 5) {   }
											elseif ($holiday_paid == 6) {   }
											elseif ($holiday_paid == 7) {   }
											else { $holiday_unpaid_total = $holiday_unpaid_total + $holiday_length; }
											
											

											if ($holiday_paid == 1) { $holiday_total = $holiday_total + $holiday_length; }
											
								
								}
								
							// Calculate any adjustments for unpaid holiday	
								
							$unpaid_adjustment = ($working_days - $holiday_unpaid_total) / $working_days;

							$holiday_allowance_thisyear = ceil ($unpaid_adjustment * $holiday_allowance_thisyear);
							
							$length = round ((($user_user_ended - $user_user_added) / 31556908.8), 2);
							
							$holiday_allowance = (ceil($length * $user_holidays * 2) / 2);
							
							// Temporary
							// if ($length > 1) {
							// $holiday_allowance_thisyear = $user_holidays;
							// } else {
							// $holiday_allowance_thisyear = ceil ($length * $user_holidays * 2) / 2;
							// }
							// End Temporary
							
							$holiday_year_remaining = $holiday_allowance_thisyear - $holiday_paid_total;
							
							$array = array($length,$user_holidays,$holiday_allowance,$holiday_allowance_thisyear,$holiday_paid_total,$holiday_unpaid_total,$study_leave_total,$jury_service_total,$toil_service_total,$holiday_year_remaining,$listadd,$listend,$user_name_first, $user_name_second,$unpaid_adjustment);
	
							return $array;
	
}

function UserTeamSelect($field_name, $team_id) {
	
	global $conn;
	
	$sql = "SELECT * FROM intranet_team WHERE team_active = 1 ORDER BY team_name";
	$result = mysql_query($sql, $conn);
	
	if (mysql_num_rows($result) > 0) {
		
		echo "<select name=\"" . $field_name . "\">";
		
		echo "<option value=\"\">- None -</option>";
	
			while ($array = mysql_fetch_array($result)) {
				
				if ($team_id == $array['team_id']) {
					
					echo "<option value=\"" . $array['team_id'] . "\" selected=\"selected\">" . $array['team_name'] . "</option>";
					
				} else {
					
					echo "<option value=\"" . $array['team_id'] . "\">" . $array['team_name'] . "</option>";
					
				}
				
			}
		
		echo "</select>";
	
	} else { echo "<p>No teams found.</p>"; }
	
}

function UserLocationAlertBox($user_id) {
	
	global $conn;
	
	$alert = 0;
	
	$sql = "SELECT location_id FROM intranet_user_location WHERE location_user = " . intval($user_id) . " AND location_date = '" . date("Y-m-d",time()) . "' LIMIT 1";
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	
	if (intval($array['location_id']) == 0) {
		
		$sql = "SELECT holiday_id FROM intranet_user_holidays WHERE holidays_user = " . intval($user_id) . " AND holiday_datestamp = '" . date("Y-m-d",time()) . "' LIMIT 1";
		$result = mysql_query($sql, $conn);
		$array = mysql_fetch_array($result);
		
		
			if (intval($array['holiday_id']) == 0) {
				
				$sql = "SELECT bankholidays_id FROM intranet_user_holidays_bank WHERE bankholidays_datestamp = '" . date("Y-m-d",time()) . "' LIMIT 1";
				$result = mysql_query($sql, $conn);
				$array = mysql_fetch_array($result);
				
				if (intval($array['bankholidays_id']) == 0) {
					
					$alert = 1;
					
				}
				
			}
		
	}
	
	if ($alert == 1) { echo 
	
			"\n\n<script type=\"text/javascript\">
				function LocationAlertBox() {
					alert(\"Please confirm your location!\");
				}
			</script>";
			
			return $alert;
	
	}
	
}

function UserLocationHolidayArray() {
	
	global $conn;
	
	$sql = "SELECT holiday_user FROM intranet_user_holidays WHERE holiday_datestamp = '" . date("Y-m-d",time()) . "'";
	$result = mysql_query($sql, $conn);
	
	$holiday_array = array();
	
	while ($array = mysql_fetch_array($result)) {
		
		$holiday_array[] = $array['holiday_user'];
		
	}
	
	return $holiday_array;
	
}

function UserLocationCalc($count, $total) {
	
	$output = "<li style=\"margin-right: 8px; width: auto; display: inline; white-space: pre; padding: 1px 5px 1px 5px; line-height: 32px; border-radius: 5px; background: #fff; border: 2px #ccc solid; \">" . $count . " / " . number_format(100*($count/$total),0) . "%</li>";
	
	return $output;
	
}

function UserLocationList($prefs_nonworking) {
	
	if (!in_array(date("N",time()),$prefs_nonworking)) {
	
		global $conn;
		
		$holiday_array = UserLocationHolidayArray();
		
		$sql = "SELECT * FROM intranet_user_details LEFT JOIN intranet_user_location ON location_user = user_id AND location_date = '" . date("Y-m-d",time()) . "' WHERE user_active = 1 AND user_user_added < " . time() . " AND (user_user_ended > " . time() . " OR user_user_ended = 0) ORDER BY location_type DESC, user_name_second, user_name_first";
			
		$result = mysql_query($sql, $conn);
		
		$total = mysql_num_rows($result);
		
		$current_type = NULL;
		
		echo "<h3>Where are people working today?</h3>";
		
		$count = 1;
		
		echo "<div>";
			
		while ($array = mysql_fetch_array($result)) {
			
			
			$location_category = UserLocationCategory($array['location_type']);
			
			
			if (!in_array($array['user_id'],$holiday_array)) {
				
				if ($location_category == NULL) { $location_type = "Not confirmed"; $background = "rgba(255,0,0,0.25)"; } else { $location_type = $location_category; $background = "rgba(173,226,227,0.5)"; }
				
				if ($current_type == NULL) { echo "<div><h4>" . $location_type . "</h4><ul style=\"list-style-type: none;\">"; $current_type = $location_type; $count = 1; }
				elseif ($current_type != $location_type) { echo UserLocationCalc($count, $total) . "</ul><h4>" . $location_type . "</h4><ul style=\"list-style-type: none;\">"; $current_type = $location_type; $count = 1; } else { $count++; }
			
				echo "<li style=\"margin-right: 8px; width: auto; display: inline; white-space: pre; padding: 1px 5px 1px 5px; line-height: 32px; border-radius: 5px; background: " . $background . "; \">" . $array['user_name_first'] . " " . $array['user_name_second'] . "</li>";
				
				if ($array['user_id'] == $_COOKIE['user']) { $confirmed = $array['location_type']; }
			
			}
		
		}
		
		echo UserLocationCalc($count, $total) . "</ul>";
		
		echo "</div>";
		
		echo "<div>";
		UserLocationConfirm($array['user_id'],$confirmed);
		echo "</div>";
	
	} else {
		echo GetNextDay();
	}
}

function UserLocationConfirm($user_id,$confirmed) {
	
	$array_types = array("Working from home","Working elsewhere","In the studio","Self-isolating","Sick","Furloughed","Compassionate leave");
	
	sort($array_types);
	
	
		echo "<div><form action=\"index2.php\" method=\"post\"><p><strong>Today (" . date("l", time()) . "), I am...</strong><br />";
		
		
		foreach ($array_types AS $type) {
		
			echo "<span style=\"\white-space: pre; float: left; padding: 8px 15px 8px 10px; margin: 8px 8px 0 0; background: #eee; border-radius: 15px;\"><input type=\"radio\" value=\"" . $type . "\" name=\"location_type\"";
				if ($confirmed == $type) { echo " checked=\"checked\" "; }
			echo "onclick=\"this.form.submit()\" id=\"" . $type . "\" />&nbsp;";
			echo "<label for=\"" . $type . "\">" . $type . "</label></span>";
		
		}
		
		if (CheckAnyNextDay($_COOKIE['user']) == 1) { $color = "#aaa"; } else { $color = "rgba(255,0,0,0.5)"; }
			
		echo "<span style=\"\white-space: pre; float: left; padding: 8px 15px 8px 10px; margin: 8px 8px 0 0; background: " . $color . "; border-radius: 15px;\"><input type=\"checkbox\" value=\"1\" name=\"location_nextday\" id=\"location_nextday\" /><label for=\"location_nextday\">&nbsp;Next Working Day?</label></span>";
		echo "<input type=\"hidden\" value=\"user_location_add\" name=\"action\" />";
		echo "</form></div>" . GetNextDay() . "</div>";
		
	
}

function UserLocationCategory($location_type) {
	
	if ($location_type == "Working from home") { return "Available - At Home"; }
	elseif ($location_type == "Working elsewhere") { return "Available - Working Elsewhere"; }
	elseif ($location_type == "In the studio") { return "Available - In the Studio"; }
	elseif ($location_type == "Self-isolating") { return "Available - At Home"; }
	elseif ($location_type == "Sick") { return "Unavailable"; }
	elseif ($location_type == "Furloughed") { return "Unavailable"; }
	elseif ($location_type == "Compassionate leave") { return "Unavailable"; }
	else { return ""; }
	
}

function UserListTeams($team_id) {
	
	global $conn;
	
	$team_array = array();
	
	$sql = "SELECT * FROM intranet_team LEFT JOIN intranet_user_details ON user_team = team_id WHERE team_id = " . intval($team_id) . " ORDER BY user_name_second";
	
	$result = mysql_query($sql, $conn);
	
		if (mysql_num_rows($result) > 0) {
		
		while ($array = mysql_fetch_array($result)) {
			
			$team_array[] = "<a href=\"index2.php?page=user_view&amp;user_id=" . $array['user_id'] . "\">" . $array['user_name_first'] . " " . $array['user_name_second'] . "</a>";
			
		}
		
		echo "Team Members: " . implode(", ",$team_array);
	
	}
	
}
