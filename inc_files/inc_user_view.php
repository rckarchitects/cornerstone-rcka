<?php

function UserDetailRow($name,$entry) {
	
	if ($entry) {
		echo "<tr><td style=\"width: 20%;\">$name</td><td>$entry</td></tr>";
	}
	
}

function UserRatesList($user_id) {

	global $conn;
	$user_id = intval($user_id);
	$sql = "SELECT ts_rate, COUNT(ts_rate) FROM `intranet_timesheet` WHERE ts_user = $user_id GROUP BY ts_rate ORDER BY ts_rate";
	$result = mysql_query($sql, $conn);
	$counter = 0;
	echo "<fieldset><legend>Hourly Rates from Timesheets</legend><table>";
	echo "<th>Rate</th><th style=\"text-align: right;\">Timesheet Entries</th></tr>";
	while ($array = mysql_fetch_array($result)) {
			
			echo "<tr><td>" . MoneyFormat($array['ts_rate']) . "</td><td style=\"text-align: right;\">" . number_format($array['COUNT(ts_rate)']) . "</td></tr>";
			$counter = $counter + $array['COUNT(ts_rate)'];
	}
	echo "<tr><td><u>Total</u></td><td style=\"text-align: right;\"><u>" . number_format ($counter)  . "</u></td></tr>";
	echo "</table></fieldset>";

}

	$user_id = intval($_GET[user_id]);


$sql = "SELECT * FROM intranet_user_details WHERE user_id = $user_id LIMIT 1";
$result = mysql_query($sql, $conn);
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
	$user_usertype = $array['user_usertype'];
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
	
	$full_name = $user_name_first . "&nbsp;" . $user_name_second;
	
	if ($user_initials) { $full_name = $full_name . " (" . $user_initials . ")" ;}

echo "<h1>".$full_name."</h1>";

	if ($user_address_1) { $user_address_full = $user_address_full . $user_address_1; }
	if ($user_address_2) { $user_address_full = $user_address_full . "<br />" . $user_address_2; }
	if ($user_address_3) { $user_address_full = $user_address_full . "<br />" . $user_address_3; }
	if ($user_address_town) { $user_address_full = $user_address_full . "<br />" . $user_address_town; }
	if ($user_address_county) { $user_address_full = $user_address_full . "<br />" . $user_address_county; }
	if ($user_address_postcode) { $user_address_full = $user_address_full . "<br />" . $user_address_postcode; }
	
	if ($user_active == 1) { $user_active = "Yes"; }
	if ($user_user_added > 0) { $user_user_added = TimeFormat($user_user_added); }
	if ($user_user_ended > 0) { $user_user_ended = TimeFormat($user_user_ended); }
	
	if ($user_holidays > 0) { $user_holidays = $user_holidays . " days"; }
	if ($user_timesheet_hours > 0) { $user_timesheet_hours = $user_timesheet_hours . " hours"; }
	
	if ($user_prop_target) { $hours_per_week = ( ( 1 - $user_prop_target) * 100 ) . "%"; }

// Project Page Menu



echo "<p class=\"menu_bar\">";

	echo "<a href=\"index2.php?page=phonemessage_edit&amp;status=new&amp;user_id=$user_id\" class=\"menu_tab\">New Telephone Message</a>";

	if ($user_usertype_current > 3 OR $user_id_current == $user_id) {
		echo "<a href=\"index2.php?page=user_edit&amp;status=edit&amp;user_id=$user_id\" class=\"menu_tab\">Edit&nbsp;<img src=\"images/button_edit.png\" alt=\"Edit User\" /></a>";
	}
	
	
	
echo "</p>";

echo "<fieldset><legend>Contact Details</legend>";

	echo 	"<table>";
	
			UserDetailRow("Email",$user_email);
			UserDetailRow("Mobile",$user_num_mob);
			
			if ($user_usertype_current > 3 OR $user_id_current == $user_id) {
				
				UserDetailRow("Home Telephone",$user_num_home);
				UserDetailRow("Home Address",$user_address_full);

				
			}
			
	echo "</table>";

echo "</fieldset>";

if ($user_usertype_current > 3) {

			$weekly_cost_rate =  MoneyFormat ( ($user_user_rate * ( (1 - $user_prop_target) * $user_timesheet_hours) ) ) ;
			
			$user_prop_target_print = (100 * $user_prop_target) . "%";
	
			echo "<fieldset><legend>Technical Information</legend>";

				echo 	"<table>";
				
						UserDetailRow("Username",$user_username);
						UserDetailRow("Initials",$user_initials);
						UserDetailRow("Active",$user_active);
						UserDetailRow("Hourly Rate",$user_rate);
						UserDetailRow("Date Started",$user_user_added);
						UserDetailRow("Date Ended",$user_user_ended);
						UserDetailRow("Require Timesheets?",$user_user_timesheet);
						UserDetailRow("Annual Holiday Allowance",$user_holidays);
						UserDetailRow("Allocated Non Fee-Earning Hours",$user_prop_target_print);
						UserDetailRow("Weekly Timesheet Hours",$hours_per_week);
						UserDetailRow("Hourly Cost Rate",MoneyFormat($user_user_rate));
						UserDetailRow("Weekly Cost Rate",$weekly_cost_rate);
						
				echo "</table>";

			echo "</fieldset>";
			
			UserRatesList($user_id);

}


if ($user_notes) {

	echo "<fieldset><legend>Notes</legend>";
	echo 	$user_notes;
	echo "</fieldset>";

}


?>



