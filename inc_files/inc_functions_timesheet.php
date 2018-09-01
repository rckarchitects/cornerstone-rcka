<?php
function TimeSheetDateDropdown($ts_weekbegin,$ts_entry, $ts_user) {

global $conn;
$ts_user = intval($ts_user);
if ($ts_user > 0) {
	$sql_holiday = "SELECT holiday_paid, holiday_datestamp FROM intranet_user_holidays WHERE holiday_user = $ts_user AND holiday_timestamp BETWEEN $ts_weekbegin AND " . ($ts_weekbegin + 604800) . "";
	//echo "<p>$sql_holiday</p>";
	$result_holiday = mysql_query($sql_holiday, $conn) or die(mysql_error());
	$array_holiday = mysql_fetch_array($result_holiday);
}

if ($ts_weekbegin != NULL AND $_GET[ts_id] == NULL) {
	$showtime = $ts_weekbegin;
	$repeats = 7; }
elseif ($ts_weekbegin != NULL AND $_GET[ts_id] != NULL) {
	$showtime = $ts_weekbegin - 604800;
	$repeats = 21;
} else {
	$showtime = time() - 604800;
	$repeats = 7;
}


// Work out which entry in the list needs to be highlighted

if ($ts_entry > 0) { $day_select = $ts_entry; }			// Select if the day matches the entry being edited
elseif ($_POST[timesheet_add_date] > 0) { $day_select = $_POST[timesheet_add_date]; }	// Select if the day is today
else { $day_select = time(); }	// Select if the day is today

// Determine the week to display by working out whether the $_GET[week] variable returns a value

$nowtime_check = $ts_weekbegin + 43200;

$counter = 1;

echo "<select name=\"timesheet_add_date\" class=\"inputbox\">";

while ($counter <= $repeats) {

						$showtime = mktime(12,0,0,date("n",$showtime),date("j",$showtime),date("Y",$showtime));

						$daytime = date("D j M y", $showtime);
						

									echo "<option value=\"$showtime\"";

									$time == time();
									
										$check_holiday = date("Y-m-d",$showtime);
										//$search_key = array_search($check_holiday,$array_holiday);
										//$search_row = array_column($array_holiday,'holiday_paid');
										//$daytime_hols = $search_row[$search_key];

										if ( date("z",$day_select) == date("z",$showtime)) { echo " selected"; }

									echo ">".$daytime; // . " - " . $daytime_hols;

									echo "</option>";
									

						$showtime = $showtime + 86400;

				$counter++;

}


echo "</select>";

}

function TimeSheetEdit($ts_weekbegin, $ts_user, $ts_id) {
	
	if (intval($ts_weekbegin) > 0 ) { $ts_weekbegin = BeginWeek(intval($ts_weekbegin)); } else { $ts_weekbegin = BeginWeek(time()); }
		
	$ts_user = intval($ts_user);
	$ts_id = intval($ts_id);
	
	global $conn;

			if ($ts_id != NULL AND $_POST[action] == NULL) {
				echo "<h2>Edit Existing Timesheet Entry</h2>";
				$sql_ts = "SELECT * FROM intranet_timesheet WHERE ts_id = '$ts_id' LIMIT 1";
				$result_ts = mysql_query($sql_ts, $conn) or die(mysql_error());
				$array_ts = mysql_fetch_array($result_ts);
				$ts_id = $array_ts['ts_id'];
				$ts_project = $array_ts['ts_project'];
				$ts_entry = $array_ts['ts_entry'];
				$ts_hours = $array_ts['ts_hours'];
				$ts_desc = $array_ts['ts_desc'];
				$ts_datestamp = $array_ts['ts_datestamp'];
				$ts_stage_fee = $array_ts['ts_stage_fee'];
				
				if ($ts_stage_fee > 0) {
					$sql_fee = "SELECT * FROM intranet_timesheet_fees WHERE ts_fee_id = '$ts_stage_fee' LIMIT 1";
					$result_fee = mysql_query($sql_fee, $conn) or die(mysql_error());
					$array_fee = mysql_fetch_array($result_fee);
					$ts_fee_id = $array_fee['ts_fee_id'];
					$ts_fee_text = $array_fee['ts_fee_text'];
					$ts_fee_stage = $array_fee['ts_fee_stage'];
					
							if ($ts_fee_stage > 0) {
								$sql_riba = "SELECT * FROM riba_stages WHERE riba_id = '$ts_fee_stage' LIMIT 1";
								$result_riba = mysql_query($sql_riba, $conn) or die(mysql_error());
								$array_riba = mysql_fetch_array($result_riba);
								$riba_letter = $array_riba['riba_letter'];
								$riba_desc = $array_riba['riba_desc'];
								$ts_fee_text = $riba_letter." - ".$riba_desc;
							}
				}
				
			} else {
				echo "<h3>Add Timesheet Entry</h3>";
			}

			echo "<form action=\"index2.php?page=timesheet&amp;week=$ts_weekbegin"."&amp;user_view=$ts_user"."\" method=\"post\">";

				echo "<p>Select Project<br />";
				
				TimeSheetProjectSelect($ts_project);
					
				echo "</p><p>Select Date<br />";


				TimeSheetDateDropdown($ts_weekbegin,$ts_entry, $ts_user);

				echo "</p><p>Enter Hours<br />";
				
				if ($ts_hours == NULL) {
				
				echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"8\" checked=\"checked\" />All Day (8h)&nbsp;";
				echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"0.5\" />0.5h&nbsp;";
				echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"1\" />1h&nbsp;";
				echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"2\" />2h&nbsp;";
				echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"3\" />3h&nbsp;";
				echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"4\" />4h&nbsp;";
				echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"5\" />5h&nbsp;";
				echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"6\" />6h&nbsp;";
				echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"7\" />7h&nbsp;";
				
				
				} else {

				echo "<input type=\"number\" class=\"inputbox\" name=\"timesheet_add_hours\" size=\"12\" maxlength=\"6\" value=\"$ts_hours\" />";
				
				}

				echo "</p><p>Enter Description<br />";

			echo "<textarea class=\"inputbox\" name=\"timesheet_add_desc\" style=\"width: 97%;\" rows=\"2\">$ts_desc</textarea>";

				echo "</p><p>";

				if ($ts_id > 0) {
					echo "<input type=\"submit\" value=\"Update\" class=\"inputsubmit\" />";
					echo "<input type=\"hidden\" value=\"timesheet_edit\" name=\"action\" />";
					echo "<input type=\"hidden\" name=\"ts_id\" value=\"$ts_id\" />";
				} else {
					echo "<input type=\"submit\" value=\"Add\" class=\"inputsubmit\" />";
					echo "<input type=\"hidden\" value=\"timesheet_add\" name=\"action\" />";
				}
				
				echo "<input type=\"hidden\" value=\"$ts_user\" name=\"ts_user\" />";
				
				echo "</p>";
				

			echo "</form>";

}

function TimeSheetHours($user_id,$display) {

// $display variable: if NULL, then checks the user_id and returns the percentage completed, if "list" then returns a formatted list showing incomplete days, and if "return", just returns the total percentage instead.

GLOBAL $database_location;
GLOBAL $database_username;
GLOBAL $database_password;
GLOBAL $database_name;
GLOBAL $settings_timesheetstart;

$conn = mysql_connect("$database_location", "$database_username", "$database_password");
mysql_select_db("$database_name", $conn);
$sql_user = "SELECT user_timesheet_hours, user_user_added, user_user_ended FROM intranet_user_details WHERE user_id = '$user_id' LIMIT 1";
$result_user = mysql_query($sql_user, $conn) or die(mysql_error());
$array_user = mysql_fetch_array($result_user);
$user_user_added = $array_user['user_user_added'];
$user_user_ended = $array_user['user_user_ended'];
$user_timesheet_hours = $array_user['user_timesheet_hours'];


if ($user_user_added > $settings_timesheetstart) { $timesheet_datum = $user_user_added; } else { $timesheet_datum = $settings_timesheetstart; }

if ($user_user_ended > 0) { $end_time = $user_user_ended; } else { $end_time = time(); }


		$startweek = BeginWeek($timesheet_datum);

		$this_week = BeginWeek(time());

		$sql4 = " SELECT ts_id, ts_user, ts_day, ts_month, ts_year, ts_entry FROM intranet_timesheet WHERE ts_entry > $startweek AND ts_entry < $end_time AND ts_entry < $this_week AND ts_day_complete = 1 AND ts_user = $user_id ORDER BY ts_entry";
		
		$current_day_check = 0;
		
		$day_complete_total = 0;
		
		if ($display == "list") { echo "<ul>"; }
		
		$result4 = mysql_query($sql4, $conn) or die(mysql_error());
		while ($array4 = mysql_fetch_array($result4)) {

		$ts_hours = $array4['ts_hours'];
		$ts_entry = BeginWeek($array4['ts_entry']);
		//$ts_beginweek = BeginWeek($array4['ts_entry']);
		$ts_check = $array4['ts_day'] . "-" .  $array4['ts_month'] . "-" . $array4['ts_year'];
		$ts_id = $array4['ts_id'];
		
		$dayofweek = date("w",$array4['ts_entry']);
		
		if	($ts_check != $current_day_check AND $dayofweek > 0 AND $dayofweek < 6 ) {
				
				$day_complete_total = $day_complete_total + 1;
				
				if ($display == "list") { echo "<li><a href=\"popup_timesheet.php?week=$ts_entry&amp;user_view=$user_id\">" .TimeFormat($array4['ts_entry']) . "</li>"; }
				
				
				$current_day_check = $ts_check;
				
			}
			
				
		
		
		
		}
		
		if ($display == "list") { echo "</ul>"; }
		
		
		// Now work out number of possible days since start
		
		$total_days = floor((5/7) * ((BeginWeek(time()) - BeginWeek($timesheet_datum)) / 86400));
		
		$timesheet_percentage_complete = round(100 * ($day_complete_total/$total_days));
		
		if ($display == NULL) { setcookie(timesheetcomplete, $timesheet_percentage_complete, time() + 86400); return $timesheet_percentage_complete; }
		
		if ($display == "return") { return $timesheet_percentage_complete; }
		
		$sql_update_completion = "UPDATE intranet_user_details SET user_timesheet_completion = $timesheet_percentage_complete WHERE user_id = $user_id LIMIT 1";
		mysql_query($sql_update_completion, $conn) or die(mysql_error());
		

}

function TimeSheetListIncomplete($user_id) {

GLOBAL $conn;
GLOBAL $settings_timesheetstart;
GLOBAL $user_user_added;

if ($user_user_added > $settings_timesheetstart) { $timesheet_datum = $user_user_added; } else { $timesheet_datum = $settings_timesheetstart; }


		$startweek = BeginWeek($timesheet_datum);

		$this_week = BeginWeek(time());

		$sql4 = " SELECT ts_id, ts_user, ts_day, ts_month, ts_year, ts_entry FROM intranet_timesheet WHERE ts_entry > $startweek AND ts_entry < $this_week AND ts_day_complete != 1 AND ts_user = $user_id ORDER BY ts_entry DESC";
		
		$current_day_check = 0;
		
		
		$result4 = mysql_query($sql4, $conn) or die(mysql_error());
		while ($array4 = mysql_fetch_array($result4)) {

		$ts_hours = $array4['ts_hours'];
		$ts_entry = BeginWeek($array4['ts_entry']);
		$ts_check = $array4['ts_day'] . "-" .  $array4['ts_month'] . "-" . $array4['ts_year'];
		$ts_id = $array4['ts_id'];
		
		$dayofweek = date("w",$array4['ts_entry']);
		
		if	($ts_check != $current_day_check AND $dayofweek > 0 AND $dayofweek < 6 ) {
				
				echo "<div style=\"float: left; width: 100px; height; 25px; display: block; margin: 0 3px 3px 0; border: 2px solid #ccc; padding: 5px;\"><a href=\"index2.php?page=timesheet&amp;week=$ts_entry&amp;user_view=$user_id\">" .TimeFormat($array4['ts_entry']) . "</div>";
				
				$current_day_check = $ts_check;
				
			}	
		
		
		}
	

}

function TimeSheetHeader($ts_weekbegin,$user_id) {
	
					global $conn;
					global $user_usertype_current;
					
					$user_id = intval($user_id);
					
					// Now establish *this* user

					$sql_user = "SELECT user_id, user_user_added, user_timesheet_hours, user_user_ended FROM intranet_user_details WHERE user_id = $user_id LIMIT 1";
					$result_user = mysql_query($sql_user, $conn);
					$array_user = mysql_fetch_array($result_user);
					$user_timesheet_hours = $array_user['user_timesheet_hours'];
					$user_user_added = $array_user['user_user_added'];
					$user_user_ended = $array_user['user_user_ended'];
					$user_timesheet_hours = $array_user['user_timesheet_hours'];
					
					if ($user_user_ended == 0) { $user_user_ended = time() + 86400; }

					// Titles
					$week_number = date("W", $ts_weekbegin);
					$week_begin = date("l, jS F Y",$ts_weekbegin);
					$time_week_end = $ts_weekbegin + 345600;
					$week_end = date("l, jS F Y",$time_week_end);

					$link_lastmonth = $ts_weekbegin - 3024000;
					$link_lastweek = $ts_weekbegin - 604800;
					$link_nextweek = $ts_weekbegin + 604800;
					$link_nextmonth = $ts_weekbegin + 3024000;

					// Header

					echo "<h1>Timesheets</h1>";
					echo "<h2>Week Beginning " . TimeFormat($ts_weekbegin) . "</h2>";
					
					ProjectSubMenu($proj_id,$user_usertype_current,"timesheet_admin",1);
					ProjectSubMenu($proj_id,$user_usertype_current,"timesheet_settings",2);

					echo "<div class=\"menu_bar\">";

								if ($user_view != NULL) { $user_filter = "&amp;user_view=" . $user_view; } else { $user_filter = NULL; }

								if ($link_lastmonth > $user_user_added) {
									echo "<a href=\"index2.php?page=timesheet&amp;user_view=$user_id&amp;week=$link_lastmonth".$user_filter."\" class=\"submenu_bar\"><< w/b ".date("j M Y",$link_lastmonth)."</a>";
								}

								if (($user_user_added - $link_lastweek) < 604800) {

								echo "<a href=\"index2.php?page=timesheet&amp;user_view=$user_id&amp;week=$link_lastweek".$user_filter."\" class=\"submenu_bar\">< w/b ".date("j M Y",$link_lastweek)."</a>";
								}

								echo "<a href=\"index2.php?page=timesheet&amp;user_view=$user_id&amp;week=".BeginWeek(time()) . $user_filter."\" class=\"submenu_bar\">This Week</a>";


								if ($link_nextweek < time() AND $link_nextweek < $user_user_ended) {
								echo "<a href=\"index2.php?page=timesheet&amp;user_view=$user_id&amp;week=$link_nextweek".$user_filter."\" class=\"submenu_bar\">w/b ".date("j M Y",$link_nextweek)." ></a>"; }

								if ($link_nextmonth < time() AND $link_nextmonth < $user_user_ended) {
								echo "<a href=\"index2.php?page=timesheet&amp;user_view=$user_id&amp;week=$link_nextmonth".$user_filter."\" class=\"submenu_bar\">w/b ".date("j M Y",$link_nextmonth)." >></a>"; }


					echo "</div>";

					TimeSheetMenuUsers($user_id,$ts_weekbegin);
					
					$timesheetcomplete = TimeSheetHours($user_id,"return");	
					echo "<p>Timesheets " . $timesheetcomplete . "% complete"; if ($user_id > 0) { echo " $user_name"; } echo " - Week " . $week_number . "</p>";

}

function TimeSheetProjectSelect($ts_project) {

global $conn;

echo "

<script type=\"text/javascript\">
<!--

function comboItemSelected(oList1,oList2){
	if (oList2!=null){
		clearComboOrList(oList2);
			if (oList1.selectedIndex == -1){
		oList2.options[oList2.options.length] = new Option('Please make a selection from the list', '');
		} else {
			fillCombobox(oList2, oList1.name + '=' + oList1.options[oList1.selectedIndex].value);
		}
	}
}

function clearComboOrList(oList){
	for (var i = oList.options.length - 1; i >= 0; i--){
		oList.options[i] = null;
	}
		oList.selectedIndex = -1;
	if (oList.onchange)	oList.onchange();
}

function fillCombobox(oList, vValue){

	if (vValue != '') {
		if (assocArray[vValue]){
			oList.options[0] = new Option('-- Current Stage --', '');
			var arrX = assocArray[vValue];
			for (var i = 0; i < arrX.length; i = i + 2){
				if (arrX[i] != 'EOF') oList.options[oList.options.length] = new Option(arrX[i + 1].split('&amp;').join('&'), arrX[i]);
			}
			if (oList.options.length == 1){
				oList.selectedIndex=0;
				if (oList.onchange) oList.onchange();
			}
		} else {
			oList.options[0] = new Option('-- None --', '');
		}
	}
}

//-->
</script>

";


echo "<select name=\"ts_project\"  onchange=\"comboItemSelected(this,this.form.ts_stage_fee);\">";

	if ($ts_project > 0) {
		$sql = "SELECT * FROM intranet_projects WHERE proj_active = '1' order by proj_num";
	} else {
		$sql = "SELECT * FROM intranet_projects WHERE proj_active = '1' order by proj_num";
	}
		$result = mysql_query($sql, $conn) or die(mysql_error());
	$test_first = 0;
	while ($array = mysql_fetch_array($result)) {
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$proj_id = $array['proj_id'];
		if ($test_first == 0) { $test_first = $proj_id; }
	echo "<option value=\"$proj_id\"";
		if ($proj_id == $ts_project) { echo " selected "; }
	echo ">$proj_num $proj_name</option>";
	}


echo "</select>&nbsp;";

// First check if we're editing an existing entry
	if (intval($_GET[ts_id]) > 0) {
			$sql_edit = "SELECT ts_project, ts_stage_fee FROM intranet_timesheet WHERE ts_id = " . intval($_GET[ts_id]) . " LIMIT 1";
			$result_edit = mysql_query($sql_edit, $conn) or die(mysql_error());
			$array_edit = mysql_fetch_array($result_edit);
			$test_first = $array_edit['ts_project'];
			$stage_first = $array_edit['ts_stage_fee'];

	}

echo "<select name=\"ts_stage_fee\">";
		echo "<option value=\"\">-- None --</option>";
		
		if ($test_first > 0) {
			$sql_first = "SELECT * FROM  intranet_timesheet_fees WHERE ts_fee_project = $test_first AND ts_fee_prospect = 100 ORDER BY ts_fee_text";
			
			
			$result_first = mysql_query($sql_first, $conn) or die(mysql_error());
			while ($array_first = mysql_fetch_array($result_first)) {
				if ($stage_first == $array_first['ts_fee_id']) { 
					echo "<option value=\"" . $array_first['ts_fee_id'] . "\" selected=\"selected\">" . $array_first['ts_fee_text'] . "</option>";
				} else {
					echo "<option value=\"" . $array_first['ts_fee_id'] . "\">" . $array_first['ts_fee_text'] .  "</option>";
				}
			}
		}

echo "<script type=\"text/javascript\">";


echo "if (!assocArray) var assocArray = new Object();";


	$fee_repeat = NULL;
	$sql2 = "SELECT * FROM  intranet_projects, intranet_timesheet_fees LEFT JOIN intranet_timesheet_group ON ts_fee_group = group_id WHERE ts_fee_project = proj_id AND proj_active = 1 AND ts_fee_prospect = 100 ORDER BY proj_num, ts_fee_commence";
	$result2 = mysql_query($sql2, $conn) or die(mysql_error());
	while ($array2 = mysql_fetch_array($result2)) {
	$ts_fee_text = $array2['ts_fee_text'];
	$ts_fee_id = $array2['ts_fee_id'];
	$ts_fee_stage = $array2['ts_fee_stage'];
	$group_code = $array2['group_code'];
		if ($ts_fee_stage > 0 && $ts_fee_text == NULL ) {
				$sql3 = "SELECT riba_letter, riba_desc FROM riba_stages WHERE riba_id = '$ts_fee_stage' LIMIT 1";
				$result3 = mysql_query($sql3, $conn) or die(mysql_error());
				$array3 = mysql_fetch_array($result3);
				$ts_fee_text = $array3['riba_letter']." - ".$array3['riba_desc'];
		}
	if ($group_code && $ts_fee_text == NULL) { $ts_fee_text = $group_code . ": " . $ts_fee_text; }
	$proj_id = $array2['proj_id'];
	$proj_num = $array2['proj_nume'];
	$proj_name = $array2['proj_name'];
		if ($fee_repeat != $proj_id AND $fee_repeat != NULL) { echo " \"EOF\"); \n"; }
		if ($fee_repeat != $proj_id) { echo "\nassocArray[\"ts_project=$proj_id\"] = new Array("; }
		echo "\"$ts_fee_id\",\"$ts_fee_text\",";
		$fee_repeat = $proj_id;
	}
	echo "	\"EOF\");	\n"; 
	
	echo "
</script>
</select>";

}

function TimeSheetList($user_id,$ts_weekbegin,$user_timesheet_hours) {
							
						global $conn;
						global $user_usertype_current;
						
						$user_id = intval($user_id);

						// Select the user details we need for the list
						
						$sql = "SELECT * FROM intranet_user_details WHERE user_id = $user_id LIMIT 1";
						$result = mysql_query($sql, $conn) or die(mysql_error());
						$array = mysql_fetch_array($result);
						$user_timesheet_hours = $array2['user_timesheet_hours'];

						if ($user_timesheet_hours > 0) {
							$weekly_hours_required = $user_timesheet_hours;
						} else {
							$weekly_hours_required = 40;
						}

						$ts_list_total = 0;
						$ts_cost_total = 0;
						$week_complete_check = 1;

						// Begin the daily loop

						$ts_day_begin = BeginWeek($ts_weekbegin);
						$ts_day_end = $ts_day_begin + 86400;

						echo "<table summary=\"Timesheet for week beginning".TimeFormat($ts_weekbegin)."\">";

						echo "<tr><th style=\"width: 20%;\">Project</th><th style=\"width: 10%;\">Day</th><th style=\"width: 35%;\">Description</th><th style=\"text-align: right;\">Hours</th>";

						if ($user_usertype_current > 3) { echo "<th style=\"text-align: right; width: 10%;\">Added</th><th style=\"text-align: right;\">Non-Fee Earning</th><th style=\"text-align: right;\">Hourly Rate</th><th style=\"text-align: right;\">Actual Cost</th><th style=\"text-align: right;\">Time Cost</th>"; }

						echo "</tr>";
							  
						$color = 1;
						
						$array_weekday = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
						
			for($weekcount=0; $weekcount<=6;$weekcount++) {

						$dayname = $array_weekday[$weekcount];
							
							
							
							$ts_day_total = 0;
							
							$sql_ts = "SELECT * FROM intranet_timesheet inner join intranet_projects on intranet_timesheet.ts_project = intranet_projects.proj_id WHERE intranet_timesheet.ts_user = '$user_id' AND intranet_timesheet.ts_entry >= '$ts_day_begin' AND intranet_timesheet.ts_entry <= '$ts_day_end' order by ts_entry, intranet_projects.proj_num ";
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
								$ts_list_project_fee_track = $array['proj_fee_track'];
								$ts_ = $array['proj_fee_track'];
								$ts_non_fee_earning = number_format (( 100 * $array['ts_non_fee_earning']),2);
								
								 if ($ts_item_new > 0 AND $ts_item_new == $ts_list_id) { $bg = " style=\"bgcolor: red;\" "; } else { unset($bg); }
								
								echo "<tr $bg>";
								
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
								
									echo "<td style=\"width: 20%; " . $style . "\"><a href=\"index2.php?page=project_view&amp;proj_id=$ts_list_project\" <td style=\"" . $style . "\">$ts_list_project_num $ts_list_project_name</a>";
									
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
										echo "&nbsp;<a href=\"index2.php?page=timesheet&amp;week=$ts_weekbegin&amp;ts_id=$ts_list_id&amp;user_view=$user_id\"><img src=\"images/button_edit.png\" alt=\"Edit this entry\" /></a>";
									}
									
									echo "</td><td style=\"text-align: right;" . $style . "\">";
									echo $ts_list_hours;
									unset($editbutton);
									echo "</td>";
									
								if ($user_usertype_current > 3) {
									if ($ts_list_project_fee_track != 1) { $style = $style . " color: #3fceb1; "; }
									echo "<td style=\"text-align: right; text-decoration: italic;" . $style . "\">" . TimeFormat($ts_list_datestamp) . "</td>";
									echo "<td style=\"text-align: right; text-decoration: italic;" . $style . "\">" . $ts_non_fee_earning . "%</td>";
									echo "<td style=\"text-align: right; text-decoration: italic;" . $style . "\">" . PresentCost($ts_list_rate) . "</td>";
									echo "<td style=\"text-align: right; text-decoration: italic;" . $style . "\">" . PresentCost($ts_cost_factored) . "</td>";
									echo "<td style=\"text-align: right; text-decoration: italic;" . $style . "\">" . PresentCost($ts_list_hours * $ts_list_rate) . "</td>";
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
										$sql_update_day = "UPDATE intranet_timesheet SET ts_day_complete = 1 WHERE ts_entry = $ts_list_entry AND ts_user = $user_id";
										mysql_query($sql_update_day, $conn);
										// $background = "background-color: cyan;";
								} elseif ($ts_day_total < 8 && $_POST[ts_project] != NULL) {
								$sql_update_day = "UPDATE intranet_timesheet SET ts_day_complete = 0 WHERE ts_entry = $ts_list_entry AND ts_user = $user_id";
										mysql_query($sql_update_day, $conn);
										$background = "background-color: green;";	
								}


								echo "<tr><td colspan=\"3\" style=\"font-weight: bold; $background\"><u>Total Hours for $dayname</u></td><td style=\"font-weight: bold; text-align: right; $background\"><u>$ts_day_total</u></td>";
								
								if ($user_usertype_current > 3) { echo "<td style=\"font-weight: bold; text-decoration: underline; text-align: right; $background\" colspan=\"4\">" . MoneyFormat($ts_day_total_factored) . "</td><td style=\"$background\"></td>"; }
								
								$ts_day_total_factored = 0;
							
								
								echo "</tr>";
								


							}
							
						$ts_day_begin = $ts_day_begin+86400;
						$ts_day_end = $ts_day_end+86400;

						$color++; if ($color > 2) {$color = 1;}
							
						}

						if ($ts_list_total > 0) {

							if ($week_complete_check == 0) { $background = "background-color: red;"; } else { $background = "background-color: white;"; }

						echo "<tr><td colspan=\"3\" style=\"$background\"><strong>Total Hours for Week</strong></td><td style=\"text-align: right; $background\"><strong>$ts_list_total</strong></td>";

						if ($user_usertype_current > 3) { echo "<td style=\"text-align: right; $background\" colspan=\"4\" ><strong>" . MoneyFormat($ts_week_total_factored) . "</strong></td><td style=\"$background\"></td>"; }

						echo "</tr>";

						// Add a row to show the percentage of timesheet completed

						// First, establish the timesheet datum...either the standard datum figure, or the day the user started work.

						if ($user_datum > $settings_timesheetstart) { $timesheet_datum = $user_datum; } else { $timesheet_datum = $settings_timesheetstart; }




						} else {
							
						echo "<tr><td colspan=\"4\">There have been no entries added for this week.</td>";

						if ($user_usertype_current > 3) { echo "<td style=\"text-align: right; $background\" colspan=\"4\"></td>"; }

						echo "</tr>";

						}
								

								echo "</table>";
								


								
}

function TimeSheetUserUpdates($user_id, $week_begin, $weeks_to_analyse) {

		global $conn;
		global $user_usertype_current;
		$weeks_to_analyse = intval($weeks_to_analyse);
		if ($weeks_to_analyse == 0) { $weeks_to_analyse = 26; }
		
		$week_begin = BeginWeek($week_begin);
		$week_end = $week_begin + 604800;
		
		$user_id = intval($user_id);
		
		$sql_user = "SELECT user_timesheet_hours, user_user_rate, user_prop_target, user_name_first, user_name_second FROM intranet_user_details WHERE user_id = $user_id LIMIT 1";
		$result_user = mysql_query($sql_user, $conn) or die(mysql_error());
		$array_user = mysql_fetch_array($result_user);
		$user_timesheet_hours = $array_user['user_timesheet_hours'];
		$user_user_rate = $array_user['user_user_rate'];
		$user_prop_target = $array_user['user_prop_target'];
		$user_name_first = $array_user['user_name_first'];
		$user_name_second = $array_user['user_name_second'];
		
		$sql_hours = "SELECT ts_hours, proj_fee_track, ts_rate FROM intranet_timesheet LEFT JOIN intranet_projects ON ts_project = proj_id WHERE ts_user = $user_id AND ts_entry > $week_begin AND ts_entry < $week_end";
		$result_hours = mysql_query($sql_hours, $conn) or die(mysql_error());
		
		$total_hours_fee = 0;
		$total_hours_non_fee = 0;
		while ($array_hours = mysql_fetch_array($result_hours)) {
			
			if ($array_hours['proj_fee_track'] == 1) { $total_hours_fee = $total_hours_fee + $array_hours['ts_hours']; }
			else { $total_hours_non_fee = $total_hours_non_fee + $array_hours['ts_hours']; }
			
			$ts_rate = $array_hours['ts_rate'];
			
		}
		
		if ($ts_rate != $user_user_rate) { $user_rate_thisweek = $ts_rate; } else { $user_rate_thisweek = $user_user_rate; }
		
		$total_hours = $total_hours_non_fee + $total_hours_fee;
		
		if ($total_hours > 0) {
			
			$percent_actual = TimeSheetCountHours($user_id,$week_begin,$weeks_to_analyse);
			
			$target = $user_timesheet_hours - ($user_timesheet_hours * $user_prop_target);
			$actual = $user_timesheet_hours - ($user_timesheet_hours * $percent_actual);
			
			$ts_adjustment_factor =  ($user_timesheet_hours * (1 - $user_prop_target)) / ($user_timesheet_hours * (1 - $percent_actual));
			
			if ($user_usertype_current > 3) { 
				echo "<blockquote><p><a href=\"index2.php?page=user_view&amp;user_id=162\">" . $user_name_first . "&nbsp;" . $user_name_second . "</a> is currently required to complete " . number_format($user_timesheet_hours) . " hours each week, with a currently hourly rate of <strong>&pound;" . number_format($user_user_rate,2) ."</strong>. " . $user_name_first . "'s weekly proportion of non-working hours is " . number_format(($user_prop_target * 100),2) . "%, ie. " . number_format(((1 - $user_prop_target) * $user_timesheet_hours),2) . " hours. Anticipated cost per week (based on current figures) is therefore <strong>" . MoneyFormat($user_user_rate * (1 - $user_prop_target) * $user_timesheet_hours) . "</strong>.</p><p>Over the previous " . $weeks_to_analyse . " weeks, " . $user_name_first . " has registered non-fee-earning time of " . number_format(($percent_actual*100),2) . "%, compared to this expected rate of " . number_format(($user_prop_target * 100),2) ."%. On this basis, " . $user_name_first . "'s weekly cost has been adjusted to <strong>" . MoneyFormat( ($user_timesheet_hours * (1 - $user_prop_target)) / ($user_timesheet_hours * (1 - $percent_actual)) * ($user_user_rate * (1 - $user_prop_target) * $user_timesheet_hours) ) . "</strong> across $user_timesheet_hours hours. This is an adjustment factor of " . number_format ((100 * $ts_adjustment_factor),2) . "%.</p>";
			}
			
			// (($user_user_rate * (1 - $user_prop_target) * $user_timesheet_hours)) / ($user_timesheet_hours * (1 - $percent_actual)
			

		
				if ($total_hours > $user_timesheet_hours && $user_timesheet_hours > 0) {
					
					$ts_adjustment_factor = $ts_adjustment_factor * ($user_timesheet_hours / $total_hours);
				
					if ($user_usertype_current > 3) {  echo "<p>In addition, " . $user_name_first . " has entered more hours than expected: " . $total_hours . ", rather than " . $user_timesheet_hours . ". Accordingly, " . $user_name_first . "'s hourly rate for this week has been adjusted with a further factor of " . number_format((100*($user_timesheet_hours / $total_hours)),2) . "%  giving a total hourly cost of <strong>" . MoneyFormat(($ts_adjustment_factor * ($user_user_rate * (1 - $user_prop_target) * $user_timesheet_hours)) / $user_timesheet_hours) . "</strong>.</p>"; }
				
				}
				
				$ts_adjustment_factor = $ts_adjustment_factor * (1 - $user_prop_target);
				
				if ($user_usertype_current > 3) { echo "<p>The total adjustment figure is " . number_format( ( 100 * $ts_adjustment_factor) ,2) . "%.</p></blockquote>"; }

				
		}
		
			
		
		// Now update the user's factored values based on the total number of hours this week
			

		if ($total_hours > 0 && $total_hours >= $user_timesheet_hours && $user_timesheet_hours > 0) {
			$sql_update_factor = "UPDATE intranet_timesheet SET ts_cost_factored = ( ts_hours * ts_rate * " . round( $ts_adjustment_factor,2) . ") WHERE ts_entry >= $week_begin AND ts_entry < $week_end AND ts_user = $user_id";
			//echo "<p>$sql_update_factor</p>";
			$result_update_factor = mysql_query($sql_update_factor, $conn) or die(mysql_error());
		} elseif ($user_usertype_current > 3) {
			
			echo "<blockquote><p>- Timesheets for this week incomplete -</p></blockquote>";
		}

}

function TimeSheetCountHours($user_id,$time_begin,$weeks) {
	
	global $conn;
	$user_id = intval($user_id);
	$weeks = intval($weeks);
	$time_end = BeginWeek(intval($time_begin));
	$time_begin = BeginWeek(intval($time_begin - ($weeks * 604800)));
	if ($weeks == 0) { $weeks = 12; }
	$fee_type_array = array(NULL,"AND proj_fee_track = 0");
	$output_array = array();
	
	foreach ($fee_type_array AS $filter) {
		$sql = "SELECT SUM(ts_hours) FROM intranet_timesheet, intranet_projects WHERE ts_project = proj_id AND ts_user = $user_id AND ts_entry BETWEEN $time_begin AND $time_end $filter";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		$output_array[] = $array['SUM(ts_hours)'];
	}
	
	$hours_total = $output_array[0];
	$hours_admin = $output_array[1];
	
	$hours_percentage = $hours_admin / $hours_total;
	
	return $hours_percentage;
	
}

function TimeSheetMenuUsers($user_id_current,$ts_weekbegin) {

			global $conn;
			global $user_usertype_current;
					

			echo "<div class=\"submenu_bar\">";
			echo "<a href=\"index2.php?page=timesheet&amp;week=$ts_weekbegin\" class=\"submenu_bar\"><img src=\"/images/button_new.png\" alt=\"Add New Timesheet Entry\" />&nbsp;Add New</a>";
			if ($user_usertype_current > 3) {
				$sql_userlist = "SELECT user_initials, user_id, user_name_first, user_name_second FROM intranet_user_details WHERE user_user_added < $ts_weekbegin AND (user_user_ended > $ts_weekbegin OR user_user_ended = 0) ORDER BY user_initials";
				$result_userlist = mysql_query($sql_userlist, $conn);
				while ($array_userlist = mysql_fetch_array($result_userlist)) {
					$user_id = $array_userlist['user_id'];
					$user_initials = $array_userlist['user_initials'];
					$user_user_added = $array_userlist['user_user_added'];
					
					if ($user_id != $user_id_current) {
						echo "<a class=\"submenu_bar\" href=\"index2.php?page=timesheet&amp;week=$ts_weekbegin&amp;user_view=$user_id\" onmouseover=\"bigImg(this)\">$user_initials</a>";
					} else {
						echo "<a class=\"submenu_bar\" href=\"index2.php?page=timesheet&amp;week=$ts_weekbegin&amp;user_view=$user_id\"><strong>$user_initials</strong></a>";
					}
				
				}
			
			}
			
			echo "</div>";

}

function TimesheetListZeroCost($user_id) {
	
	
		global $conn;
		
		if ($user_id) { $sql = "SELECT * FROM intranet_timesheet LEFT JOIN intranet_user_details ON ts_user = user_id WHERE ts_cost_factored = 0 AND user_timesheet_hours > 0 AND user_user_rate > 0 AND ts_user = $user_id ORDER BY ts_entry"; } else { $sql = "SELECT * FROM intranet_timesheet LEFT JOIN intranet_user_details ON ts_user = user_id WHERE ts_cost_factored = 0 AND user_timesheet_hours > 0 AND user_user_rate > 0 ORDER BY ts_entry"; }
		
				
				$result = mysql_query($sql, $conn);

				
				if (mysql_num_rows($result) > 0) {
				
					echo "<fieldset><legend>Timesheet Entries with Zero Cost</legend><table>";
					
					echo "<tr><th>Date</th><th>User</th><th>ID</th><th style=\"text-align: right;\">Hours</th><th style=\"text-align: right;\">Factored Cost (&pound;)</th><th style=\"text-align: right;\">Hours</th><th style=\"text-align: right;\">Hourly Cost (&pound;)</th><th style=\"text-align: right;\">Non Fee-Earning Time (%)</th></tr>";
					
					while ($array = mysql_fetch_array($result)) {
						
						echo "<tr><td>" . TimeFormat($array['ts_entry']) . "</td><td>" . $array['user_name_first'] . "&nbsp;" . $array['user_name_second'] . "</td><td>" . $array['ts_id'] . "&nbsp;<a href=\"index2.php?page=timesheet&amp;user_view=" . $array['ts_user'] . "&amp;week=" . $array['ts_entry'] . "\"><img src=\"images/button_edit.png\" /></a></td><td style=\"text-align: right;\">" . $array['ts_hours'] . "</td><td style=\"text-align: right;\">&pound;" . number_format($array['ts_cost_factored'],2) . "</td><td style=\"text-align: right;\">" . number_format($array['ts_hours'],2) . "</td><td style=\"text-align: right;\">&pound;" . number_format($array['ts_rate'],2) . "</td><td style=\"text-align: right;\">" . $array['ts_non_fee_earning'] . "</td></tr>";
						
					}
					
					echo "</table></fieldset>";
				
				}
	
}

function TimesheetListLowCost($user_id) {
	
	
		global $conn;
		
		$sql = "SELECT * FROM intranet_timesheet LEFT JOIN intranet_user_details ON ts_user = user_id WHERE ts_cost_factored < ((ts_hours * ts_rate * (1 - ts_non_fee_earning)) / 2) AND user_timesheet_hours > 0 AND user_user_rate > 0 AND ts_non_fee_earning < 1 ORDER BY ts_entry DESC";
		
				
				$result = mysql_query($sql, $conn);

				
				if (mysql_num_rows($result) > 0) {
				
					echo "<fieldset><legend>Timesheet Entries with Low Cost</legend><table>";
				
					echo "<p class=\"minitext\">The table below shows any timesheet entries which have a factored cost less than half of expected cost (ie. hourly rate x number of hours). This is useful for identifying any entries which may not have been factored correctly.</p>";
					echo "<tr><th>Date</th><th>User</th><th>ID</th><th style=\"text-align: right;\">Hours</th><th style=\"text-align: right;\">Factored Cost (&pound;)</th><th style=\"text-align: right;\">Hours</th><th style=\"text-align: right;\">Hourly Cost (&pound;)</th><th style=\"text-align: right;\">Non Fee-Earning Time (%)</th></tr>";
					
					while ($array = mysql_fetch_array($result)) {
						
						echo "<tr><td>" . TimeFormat($array['ts_entry']) . "</td><td>" . $array['user_name_first'] . "&nbsp;" . $array['user_name_second'] . "</td><td>" . $array['ts_id'] . "&nbsp;<a href=\"index2.php?page=timesheet&amp;user_view=" . $array['ts_user'] . "&amp;week=" . $array['ts_entry'] . "\"><img src=\"images/button_edit.png\" /></a></td><td style=\"text-align: right;\">" . $array['ts_hours'] . "</td><td style=\"text-align: right;\">&pound;" . number_format($array['ts_cost_factored'],2) . "</td><td style=\"text-align: right;\">" . number_format($array['ts_hours'],2) . "</td><td style=\"text-align: right;\">&pound;" . number_format($array['ts_rate'],2) . "</td><td style=\"text-align: right;\">" . $array['ts_non_fee_earning'] . "</td></tr>";
						
					}
					
					echo "</table></fieldset>";
				
				}
	
}

function TimesheetListFactored ($week) {
	
	echo "<h1>Factored Timesheets</h1>";

	global $conn;

	if (intval($week) > 0) {  $week_begin = BeginWeek($week); } else { $week_begin = BeginWeek(time() - 86400); }
	
	echo "<p>Calculates factored timesheets for each current member of staff, week beginning " . TimeFormat($week_begin) . ".</p>";

	$sql = "SELECT user_id, user_name_first, user_name_second FROM intranet_user_details WHERE user_active = 1 ORDER BY user_name_second";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
		
		echo "<h3><a href=\"index2.php?page=timesheet&week=" . $week . "&amp;user_view=" . $array['user_id'] . "\">" . $array['user_name_first'] . "&nbsp;" . $array['user_name_second'] . "</a></h3>";
		
		TimeSheetUserUpdates($array['user_id'], $week_begin, $weeks_to_analyse);
		
	}

}