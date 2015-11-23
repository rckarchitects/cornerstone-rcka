<?php

if ($user_timesheet_hours > 0) { $weekly_hours_required = $user_timesheet_hours; } else { $weekly_hours_required = 40; }


if ($TSFormat == "popup") { $TSPage = "popup_timesheet.php?"; } else { $TSPage = "index2.php?page=timesheet_edit&amp;"; }

	$ts_list_total = 0;
	
	$ts_cost_total = 0;
	
	$week_complete_check = 1;
	
// Any functions?

	function PresentCost($input) { 
		$output = "&pound;" . numberformat($input, 2);
		return $output;
	}	
	
// Begin the daily loop

$ts_day_begin = $ts_weekbegin;
$ts_day_end = $ts_day_begin+86400;

print "<table summary=\"Timesheet for week beginning".TimeFormat($_GET[week])."\">";

print "<tr><th style=\"width: 30%;\"><strong>Project</strong></th><th style=\"width: 15%;\"><strong>Day</strong></th><th><strong>Description</strong></th><th><strong>Hours</strong></th>";

if ($user_usertype_current > 3) { echo "<th>Cost</th>"; }

echo "</tr>";
	  
$color = 1;
	
for($weekcount=0; $weekcount<=6;$weekcount++) {
	
	if ($weekcount == 0) {
		$dayname = "Monday";
	} elseif ($weekcount == 1) {
		$dayname = "Tuesday";
	} elseif ($weekcount == 2) {
		$dayname = "Wednesday";
	} elseif ($weekcount == 3) {
		$dayname = "Thursday";
	} elseif ($weekcount == 4) {
		$dayname = "Friday";
	} elseif ($weekcount == 5) {
		$dayname = "Saturday";
	} elseif ($weekcount == 6) {
		$dayname = "Sunday";
	}
	
	$ts_day_total = 0;
	
	if ($_GET[user_view] != NULL) { $user_view = $_GET[user_view]; } else { $user_view = $_COOKIE[user]; }
	
	$sql_ts = "SELECT * FROM intranet_timesheet inner join intranet_projects on intranet_timesheet.ts_project = intranet_projects.proj_id WHERE intranet_timesheet.ts_user = '$user_view' AND intranet_timesheet.ts_entry >= '$ts_day_begin' AND intranet_timesheet.ts_entry <= '$ts_day_end' order by ts_entry, intranet_projects.proj_num ";
	$result_list_ts = mysql_query($sql_ts, $conn) or die(mysql_error());

	
	$ts_list_results = mysql_num_rows($result_list_ts);
	
	if ($ts_list_results > 0) {
	
		while ($array = mysql_fetch_array($result_list_ts)) {
		$ts_list_id = $array['ts_id'];
		$ts_list_project = $array['ts_project'];
		$ts_list_entry = $array['ts_entry'];
		$ts_list_hours = $array['ts_hours'];
		$ts_list_desc = $array['ts_desc'];
		$ts_list_datestamp = $array['ts_datestamp'];
		$ts_list_stage = $array['ts_stage_fee'];
		
		$ts_cost_factored = $array['ts_cost_factored'];
		
		$ts_list_day_complete = $array['ts_day_complete'];
		
		$ts_list_rate = $array['ts_rate'];
		$ts_list_overhead = $array['ts_overhead'];
		$ts_list_projectrate = $array['ts_projectrate']; 
		
		$ts_list_unitcost = ($ts_list_rate + $ts_list_overhead + $ts_list_projectrate) * $ts_list_hours;
		$ts_cost_total = $ts_cost_total + $ts_list_unitcost;
		
		$ts_list_project_num = $array['proj_num'];
		$ts_list_project_name = $array['proj_name'];
		$ts_list_project_id = $array['id'];
	
		$ts_list_date = date("D jS M Y",$ts_list_entry);
		
		 if ($ts_item_new > 0 AND $ts_item_new == $ts_list_id) { $bg = " style=\"bgcolor: red;\" "; } else { unset($bg); }
		
		print "<tr $bg>";
		
		if ( time() - $ts_list_datestamp < 86400 AND $_GET[editref] == NULL)  {
			$editbutton = 1;
		} elseif (time() - $ts_list_entry < 86400 AND $_GET[editref] == NULL) {
			$editbutton = 1;
		} elseif ($user_usertype_current > 2 AND $_GET[editref] == NULL) {
			$editbutton = 1;
		} else { 
		    $editbutton = 0;
		}
		
		 if ($ts_list_day_complete != 1) { $style = "color: #999;\""; $week_complete_check = 0; } else { unset($style); }
		
			print "<td style=\"width: 30%; " . $style . "\"><a href=\"index2.php?page=project_view&amp;proj_id=$ts_list_project\" <td style=\"" . $style . "\">$ts_list_project_num $ts_list_project_name</a>";
			
			if ($ts_list_stage != 0) {
				$sql_fee = "SELECT ts_fee_text, riba_desc, riba_letter FROM intranet_timesheet_fees LEFT JOIN riba_stages ON riba_id = ts_fee_stage WHERE ts_fee_id = $ts_list_stage LIMIT 1";
				$result_fee = mysql_query($sql_fee, $conn) or die(mysql_error());
				$array_fee = mysql_fetch_array($result_fee);
				$riba_desc = $array_fee['riba_desc'];
				$riba_letter = $array_fee['riba_letter'];
				if ($riba_desc != NULL) { $fee_stage = $riba_letter.": ".$riba_desc; } else { $fee_stage = $array_fee['ts_fee_text']; }
				echo "&nbsp;<span class=\"minitext\">(". $fee_stage .")</span>";
			}
			
			echo "</td>";
			echo "<td style=\"" . $style . "\">" . $ts_list_date . "</td>";
			echo "<td style=\"" . $style . "\">" . $ts_list_desc;
			if ($editbutton == 1) {
				print "&nbsp;<a href=\"".$TSPage."week=$ts_weekbegin&amp;ts_id=$ts_list_id&amp;user_view=$user_view\"><img src=\"images/button_edit.png\" alt=\"Edit this entry\" /></a>"; }
			print "</td><td style=\"text-align: right;" . $style . "\">";
	
			print $ts_list_hours;
		  
		unset($editbutton);
		
		print "</td>";
		
		if ($user_usertype_current > 3 && $ts_cost_factored == 0) {
			echo "<td style=\"text-align: right;" . $style . "\">" . PresentCost($ts_list_unitcost) . "</td>";
		} elseif ($user_usertype_current > 3 && $ts_cost_factored > 0) {
			echo "<td style=\"text-align: right; text-decoration: italic;" . $style . "\">" . PresentCost($ts_cost_factored) . "</td>";
		}
		
		echo "</tr>";
		
		// Add this entry to the total for the day and week
		
		$ts_day_total = $ts_day_total + $ts_list_hours;
		$ts_list_total = $ts_list_total + $ts_list_hours;
		
		$ts_day_total_factored = $ts_cost_factored + $ts_day_total_factored;
		$ts_week_total_factored = $ts_cost_factored + $ts_week_total_factored;

		}
			

		if ($ts_day_total < 8 && date("w",$ts_list_date) != 0 && date("w",$ts_list_date) != 6) { $background = "background-color: red;"; } else { $background = "background-color: white;"; }
		
		// Update the ts_day_complete variable if there has been a change to the figures for this day
		
		if ($ts_day_total >= 8 && $_POST[ts_project] != NULL) {
				$sql_update_day = "UPDATE intranet_timesheet SET ts_day_complete = 1 WHERE ts_entry = $ts_list_entry AND ts_user = $user_view";
				mysql_query($sql_update_day, $conn);
				// $background = "background-color: cyan;";
		} elseif ($ts_day_total < 8 && $_POST[ts_project] != NULL) {
		$sql_update_day = "UPDATE intranet_timesheet SET ts_day_complete = 0 WHERE ts_entry = $ts_list_entry AND ts_user = $user_view";
				mysql_query($sql_update_day, $conn);
				$background = "background-color: green;";	
		}


		print "<tr><td colspan=\"2\" style=\"font-weight: bold; text-align: right; $background\"><u>Total Hours for $dayname</u></td><td style=\"font-weight: bold; text-align: right; $background\" colspan=\"2\"><u>$ts_day_total</u></td>";
		
		if ($user_usertype_current > 3) { echo "<td style=\"font-weight: bold; text-decoration: underline; text-align: right; $background\">" . MoneyFormat($ts_day_total_factored) . "</td>"; }
		
		$ts_day_total_factored = 0;
	
		
		echo "</tr>";
		


	}
	
$ts_day_begin = $ts_day_begin+86400;
$ts_day_end = $ts_day_end+86400;

$color++; if ($color > 2) {$color = 1;}
	
}

if ($ts_list_total > 0) {

	if ($week_complete_check == 0) { $background = "background-color: red;"; } else { $background = "background-color: white;"; }

echo "<tr><td colspan=\"2\" style=\"text-align: right; $background\"><strong>Total Hours for Week</strong></td><td colspan=\"2\" style=\"text-align: right; $background\"><strong>$ts_list_total</strong></td>";

if ($user_usertype_current > 3) { echo "<td style=\"text-align: right; $background\"><strong>" . MoneyFormat($ts_week_total_factored) . "</strong></td>"; }

echo "</tr>";

// Add a row to show the percentage of timesheet completed

// First, establish the timesheet datum...either the standard datum figure, or the day the user started work.

if ($user_datum > $settings_timesheetstart) { $timesheet_datum = $user_datum; } else { $timesheet_datum = $settings_timesheetstart; }




} else {
	
print "<tr><td colspan=\"4\">There have been no entries added for this week.</td>";

if ($user_usertype_current > 3) { echo "<td style=\"text-align: right; $background\"></td>"; }

echo "</tr>";

}
		

		print "</table>";
		

// Now update the user's factored values based on the total number of hours this week
		
//if ($_POST[ts_hours] != NULL) {

		$user_rate_standard = $ts_list_rate + $ts_list_overhead + $ts_list_projectrate;

		$user_hourly_factor = round (($weekly_hours_required / $ts_list_total) * $user_rate_standard, 2);
		
		if (($weekly_hours_required / $ts_list_total) > 1) { $user_hourly_factor = $user_rate_standard; }
		
		$ts_weekend = $ts_weekbegin + 604800;
		
		//if ($_POST[ts_project] != NULL) {
		if ($ts_list_total >= $weekly_hours_required	) {
		$sql_update_factor = "UPDATE intranet_timesheet SET ts_cost_factored = ( ts_hours * $user_hourly_factor) WHERE ts_entry > $ts_weekbegin AND ts_entry < $ts_weekend AND ts_user = $user_view";
		$result_update_factor = mysql_query($sql_update_factor, $conn) or die(mysql_error());
		} else {
		$sql_update_factor = "UPDATE intranet_timesheet SET ts_cost_factored = (ts_hours * ts_rate) WHERE ts_entry > $ts_weekbegin AND ts_entry < $ts_weekend AND ts_user = $user_view";
		$result_update_factor = mysql_query($sql_update_factor, $conn) or die(mysql_error());
		}
		
		echo "<p>" . $sql_update_factor . "</p>";
		
		if ($user_usertype_current > 3) { 
		echo "<p>User factored rate for week = &pound;" . $user_hourly_factor . "<br />User standard rate for week: &pound;" . $user_rate_standard . "<br />Weekly cost for user: " . MoneyFormat($ts_week_total_factored) .  "<br />User hours required: " . $weekly_hours_required . "</p>";
		}
		
		

//}
	
?>
