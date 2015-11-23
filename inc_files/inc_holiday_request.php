<?php

if ($_GET[year] != NULL) { $this_year = $_GET[year]; } else { $this_year =  date("Y",time()); }

if ($_POST[user_id] != NULL) { $user_id = $_POST[user_id]; } elseif ($_GET[user_id] != NULL) { $user_id = $_GET[user_id]; } else { $user_id = $_COOKIE[user]; }

$sql_user = "SELECT user_name_first, user_name_second FROM intranet_user_details WHERE user_id = $user_id";
$result_user = mysql_query($sql_user, $conn);
$array_user = mysql_fetch_array($result_user);
$user_name_first = $array_user['user_name_first'];
$user_name_second = $array_user['user_name_second'];

if ($_POST[paid] == 1) { $paid = 1; } else { $paid = 0; }

echo "<h1>Holidays - $user_name_first $user_name_second</h1>";

echo "<h2>Bank Holidays</h2>";

$sql_bankholidays = "SELECT bankholiday_timestamp FROM intranet_user_holidays_bank WHERE bankholiday_timestamp > " . time() . " ORDER BY bankholiday_timestamp LIMIT 1";
$result_bankholidays = mysql_query($sql_bankholidays, $conn);
$array_bankholidays = mysql_fetch_array($result_bankholidays);
$bankholiday_timestamp  = $array_bankholidays['bankholiday_timestamp'];

echo "<p>The next Bank Holiday is <a href=\"index2.php?page=datebook_view_day&amp;time=$bankholiday_timestamp\">" . TimeFormat($bankholiday_timestamp) . ".</a></p>";



$sql_holiday = "SELECT user_holidays, user_user_added, user_user_ended FROM intranet_user_details WHERE user_id = $user_id AND holiday_paid = 1 LIMIT 1";
$result_holiday = mysql_query($sql_holiday, $conn);
$array_holiday = mysql_fetch_array($result_holiday);
$user_holidays = $array_holiday['user_holidays'];
$user_user_added = $array_holiday['user_user_added'];
$user_user_ended = $array_holiday['user_user_ended'];

$beginning_of_year = mktime(0,0,0,1,1,$this_year);

$end_of_year = mktime(0,0,0,1,1,($this_year+1));

echo "<h2>Holiday Request</h2>";

$holiday_remaining = UserHolidays($user_id,"yes");

if ($_POST[assess] == 1) {
echo "<fieldset><legend>Confirm Holiday Request</legend>";

$holiday_day_start = AssessDays ( $_POST[holiday_day_start] );
$holiday_day_back = AssessDays ( $_POST[holiday_day_back] );

$holiday_count = CheckHolidays($holiday_day_start,$holiday_day_back,"no",$user_id, $_POST[holiday_length],$paid);

$holiday_remaining = $holiday_remaining - $holiday_count;

echo "<p>This will leave you with " . $holiday_remaining . " remaining holidays this year.</p>";


		echo "<p><form action=\"index2.php?page=holiday_request\" method=\"post\"><input type=\"hidden\" name=\"action\" value=\"holiday_request\" /><input type=\"hidden\" name=\"assess\" value=\"2\" /><input type=\"hidden\" value=\"$time_begin\" name=\"holiday_begin\" /><input type=\"hidden\" value=\"$_POST[holiday_length]\" name=\"holiday_length\" /><input type=\"hidden\" value=\"$time_back\" name=\"holiday_back\" /><input type=\"hidden\" value=\"$user_id\" name=\"user_id\" /><input type=\"hidden\" value=\"$paid\" name=\"paid\" /><input type=\"submit\" value=\"Confirm\" /></p>";


echo "</fieldset>";
}

if ($_POST[assess] == 2) {
echo "<fieldset><legend>Holiday Request Confirmed</legend>";
$holiday_count = CheckHolidays($_POST[holiday_begin],$_POST[holiday_back],"yes",$user_id, $_POST[holiday_length],$paid);
$holiday_remaining = $user_holidays - $holiday_count;
echo "</fieldset>";
}

if ($_POST[assess] < 1) {

			echo "<fieldset><legend>Make Holiday Request</legend>";

			echo "<form action=\"index2.php?page=holiday_request\" method=\"post\">";

			echo "<p>First Day Out of Office<br /><input name=\"holiday_day_start\" value=\"$_POST[holiday_day_start]\" type=\"date\" required /></p>";

			echo "<p>First Day Back in Office<br /><input name=\"holiday_day_back\" value=\"$_POST[holiday_day_back]\"  type=\"date\" required /></p>";
			
			echo "<p><input type=\"radio\" value=\"0.5\" name=\"holiday_length\" /> Half Day<br /><input type=\"radio\" value=\"1\" name=\"holiday_length\" checked=\"checked\" /> Full Day<br /><input type=\"checkbox\" value=\"1\" name=\"paid\" checked=\"checked\" />&nbsp;Paid Holiday Request</p>";

			echo "<p><input type=\"hidden\" name=\"user_id\" value=\"$user_id\" /><input type=\"hidden\" name=\"action\" value=\"holiday_request\" />";

			if ($_POST[assess] == 1) { echo "<input type=\"hidden\" name=\"assess\" value=\"2\" />"; }
				else { echo "<input type=\"hidden\" name=\"assess\" value=\"1\" />"; }
				
			if ($user_usertype_current > 3) { echo "<p>"; UserDropdown($user_id); echo "</p>"; }
			else { echo "<input type=\"hidden\" name=\"user_id\" value=\"$user_id\" />"; }

			echo "<input type=\"submit\" value=\"Submit Request\" /></p>\n\n";

			echo "</form>";


			echo "</fieldset>";

}

$sql_holiday_list = "SELECT * FROM intranet_user_holidays WHERE holiday_user = '$user_id' AND holiday_timestamp > $beginning_of_year AND holiday_year = $this_year ORDER BY holiday_timestamp";
$result_holiday_list = mysql_query($sql_holiday_list, $conn);

if (mysql_num_rows($result_holiday_list) > 0) {
	$holiday_total = 0;
	echo "<h2>Your Holidays for " . date("Y",time()) . "</h2><table>";
	while ($array_holiday_list = mysql_fetch_array($result_holiday_list)) {
		$holiday_timestamp = $array_holiday_list['holiday_timestamp'];
		$holiday_approved = $array_holiday_list['holiday_approved'];
		$holiday_length = $array_holiday_list['holiday_length'];
		$holiday_paid = $array_holiday_list['holiday_paid'];
		if ($holiday_length == 0.5) { $holiday_length_print = "Half Day"; } else { $holiday_length_print = "Full Day"; }
		
		if ($holiday_paid != 1) { $holiday_length = 0; $holiday_length_print = $holiday_length_print . " (Unpaid)";  }
		$holiday_total = $holiday_total + $holiday_length;
		
		if ($holiday_approved != NULL) { $holiday_approved = "Approved"; } else { $holiday_approved = "Pending Approval"; }
		echo "<tr><td>" . TimeFormat($holiday_timestamp) . "</td><td>" . $holiday_approved . "</td><td>" . $holiday_length_print . "</td><td>" . $holiday_total . "</td></tr>";
		
	}
	
	echo "<tr><th colspan=\"3\">Total</th><th>$holiday_total</th></tr>";
	echo "</table>";
} else {
	echo "<p>No holidays found</p>";
}

echo "<h2>Calendar Address</h2>";
echo "<p>You can add the following calendar location to Outlook:</p>";
echo "<blockquote>http://intranet.rcka.co.uk/calendars/holidays.ics</bloackquote>";













?>