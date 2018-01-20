<?php
function TimeSheetDateDropdown($ts_weekbegin,$ts_entry) {
	




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

										if ( date("z",$day_select) == date("z",$showtime)) { echo " selected"; }

									echo ">".$daytime;

									echo "</option>";
									

						$showtime = $showtime + 86400;

				$counter++;

}


echo "</select>";

}

function TimeSheetEdit($ts_weekbegin, $ts_user) {

	$ts_user = intval($ts_user);
	$ts_weekbegin = intval($ts_weekbegin);
	
	global $conn;

			if ($_GET[ts_id] != NULL) { $ts_id = $_GET[ts_id]; } elseif ($_POST[ts_id] != NULL) { $ts_id = $_POST[ts_id]; }

			if ($ts_id != NULL AND $_POST[action] == NULL) {
				echo "<div class=\"submenu_bar\"><a href=\"index2.php?page=timesheet&amp;week=$ts_weekbegin\" class=\"submenu_bar\">Add New</a></div>";
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
				echo "<h2>Add Timesheet Entry</h2>";
			}

			echo "<form action=\"index2.php?page=timesheet&amp;week=$ts_weekbegin"."&amp;user_view=$viewuser"."\" method=\"post\">";

				echo "<p>Select Project<br />";
				
				TimeSheetProjectSelect($ts_project);
					
				echo "</p><p>Select Date<br />";


				TimeSheetDateDropdown($ts_weekbegin,$ts_entry);

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

				echo "<input type=\"text\" class=\"inputbox\" name=\"timesheet_add_hours\" size=\"12\" maxlength=\"6\" value=\"$ts_hours\" />";
				
				}

				echo "</p><p>Enter Description<br />";

			echo "<textarea class=\"inputbox\" name=\"timesheet_add_desc\" style=\"width: 97%;\" rows=\"2\">$ts_desc</textarea>";

				echo "</p><p>";

				if ($_GET[ts_id] > 0) {
					echo "<input type=\"submit\" value=\"Update\" class=\"inputsubmit\" />";
					echo "<input type=\"hidden\" value=\"timesheet_edit\" name=\"action\" />";
					echo "<input type=\"hidden\" name=\"ts_id\" value=\"$ts_id\" />";
				} else {
					echo "<input type=\"submit\" value=\"Add\" class=\"inputsubmit\" />";
					echo "<input type=\"hidden\" value=\"timesheet_add\" name=\"action\" />";
				}
				
				echo "<input type=\"hidden\" value=\"$ts_user\" name=\"timesheet_user\" />";
				
				echo "</p>";
				

			echo "</form>";

}

function TimeSheetHeader($ts_weekbegin, $viewuser,$user_usertype_current) {
	
						global $conn;
						
						$timesheetcomplete = TimeSheetHours($viewuser,"return");
						
						$viewuser = intval($viewuser);

					// Set the week beginning variable from either POST or GET

						if ($_POST[ts_weekbegin] != NULL) {
						$ts_weekbegin = $_POST[ts_weekbegin];
						} elseif ($_GET[week] != NULL) {
						$ts_weekbegin = $_GET[week];
						} else {
						$ts_weekbegin = BeginWeek(time());
						}
						
						
						// Now establish *this* user

						$sql_user = "SELECT user_id, user_user_added, user_timesheet_hours, user_user_ended FROM intranet_user_details WHERE user_id = $viewuser LIMIT 1";
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

					echo "<h1>Timesheet - Week Beginning " . TimeFormat($ts_weekbegin) . "</h1>";

					echo "<div class=\"menu_bar\"><a href=\"index2.php\" class=\"menu_tab\"><< back to intranet</a>";

								if ($user_view != NULL) { $user_filter = "&amp;user_view=" . $user_view; } else { $user_filter = NULL; }

								if ($link_lastmonth > $user_user_added) {
									echo "<a href=\"index2.php?page=timesheet&amp;user_view=$viewuser&amp;week=$link_lastmonth".$user_filter."\" class=\"menu_tab\"><< w/b ".date("j M Y",$link_lastmonth)."</a>";
								}

								if (($user_user_added - $link_lastweek) < 604800) {

								echo "<a href=\"index2.php?page=timesheet&amp;user_view=$viewuser&amp;week=$link_lastweek".$user_filter."\" class=\"menu_tab\">< w/b ".date("j M Y",$link_lastweek)."</a>";
								}

								echo "<a href=\"index2.php?page=timesheet&amp;user_view=$viewuser&amp;week=".BeginWeek(time()) . $user_filter."\" class=\"menu_tab\">This Week</a>";


								if ($link_nextweek < time() AND $link_nextweek < $user_user_ended) {
								echo "<a href=\"index2.php?page=timesheet&amp;user_view=$viewuser&amp;week=$link_nextweek".$user_filter."\" class=\"menu_tab\">w/b ".date("j M Y",$link_nextweek)." ></a>"; }

								if ($link_nextmonth < time() AND $link_nextmonth < $user_user_ended) {
								echo "<a href=\"index2.php?page=timesheet&amp;user_view=$viewuser&amp;week=$link_nextmonth".$user_filter."\" class=\"menu_tab\">w/b ".date("j M Y",$link_nextmonth)." >></a>"; }


					echo "</div>";

					if ($user_usertype_current > 3) {
						
						$weeks_expired = 6; // This controls how long we wait before people who have left the practice vanish from the top bar
						$weeks_expired = $weeks_expired * 604800;
						$weeks_expired = $ts_weekbegin - $weeks_expired;

						$sql_userlist = "SELECT user_initials, user_id, user_name_first, user_name_second FROM intranet_user_details WHERE (user_user_ended IS NULL OR user_user_ended = 0) AND (user_user_ended < $weeks_expired OR  user_user_ended IS NULL OR user_user_ended = 0) ORDER BY user_initials";
						$result_userlist = mysql_query($sql_userlist, $conn);
						
						echo "<div class=\"submenu_bar\">";
						while ($array_userlist = mysql_fetch_array($result_userlist)) {
						$user_id = $array_userlist['user_id'];
						$user_initials = $array_userlist['user_initials'];
						$user_user_added = $array_userlist['user_user_added'];
						
						if ($user_id == $viewuser) {
						$user_name = " for " . $array_userlist['user_name_first'] ." ". $array_userlist['user_name_second'];
						$user_datum = $user_user_added;
						}
						if ($user_id != $viewuser) {
							echo "<a class=\"submenu_bar\" href=\"index2.php?page=timesheet&amp;week=$ts_weekbegin&amp;user_view=$user_id\">$user_initials</a>";
						} else {
							echo "<a class=\"submenu_bar\" href=\"index2.php?page=timesheet&amp;week=$ts_weekbegin&amp;user_view=$user_id\"><strong>$user_initials</strong></a>";
						}
						
						
						
						}
						
						echo "</div>";

					}




					echo "<p>Timesheets " . $timesheetcomplete . "% complete"; if ($viewuser > 0) { echo " $user_name"; } echo " - Week " . $week_number . "</p>";

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
	while ($array = mysql_fetch_array($result)) {
	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$proj_id = $array['proj_id'];
	echo "<option value=\"$proj_id\"";
		if ($proj_id == $ts_project) { echo " selected "; }
	echo ">$proj_num $proj_name</option>";
	}
	

echo "</select>";

echo "<select name=\"ts_stage_fee\">";


if ($ts_fee_id > 0) {
echo "<option value=\"$ts_fee_id\">$ts_fee_text</option>";
} else {
echo "<option value=\"\">-- None --</option>";
}


echo "<script type=\"text/javascript\">";


echo "if (!assocArray) var assocArray = new Object();";


	$fee_repeat = NULL;
	$sql2 = "SELECT * FROM  intranet_projects, intranet_timesheet_fees LEFT JOIN intranet_timesheet_group ON ts_fee_group = group_id WHERE ts_fee_project = proj_id AND proj_active = 1 ORDER BY proj_num, ts_fee_time_begin";
	$result2 = mysql_query($sql2, $conn) or die(mysql_error());
	while ($array2 = mysql_fetch_array($result2)) {
	$ts_fee_text = $array2['ts_fee_text'];
	$ts_fee_id = $array2['ts_fee_id'];
	$ts_fee_stage = $array2['ts_fee_stage'];
	$group_code = $array2['group_code'];
		if ($ts_fee_stage > 0) {
				$sql3 = "SELECT riba_letter, riba_desc FROM riba_stages WHERE riba_id = '$ts_fee_stage' LIMIT 1";
				$result3 = mysql_query($sql3, $conn) or die(mysql_error());
				$array3 = mysql_fetch_array($result3);
				$ts_fee_text = $array3['riba_letter']." - ".$array3['riba_desc'];
		}
	if ($group_code) { $ts_fee_text = $group_code . ": " . $ts_fee_text; }
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