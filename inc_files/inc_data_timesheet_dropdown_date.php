<?php

print "<select name=\"timesheet_add_date\" class=\"inputbox\">";

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

for ($counter = 1; $counter<=$repeats; $counter++) {

						$showtime = mktime(12,0,0,date("n",$showtime),date("j",$showtime),date("Y",$showtime));

						$daytime = date("D j M y", $showtime);
						
							if ( $showtime < $user_user_ended ) {

									print "<option value=\"$showtime\"";

									$time == time();

										if ( date("z",$day_select) == date("z",$showtime)) { print " selected"; }

									print ">".$daytime;

									print "</option>";
									
							}

						$showtime = $showtime + 86400;



}

print "</select>";

?>
