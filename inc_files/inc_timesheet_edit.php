<?php

// Set the week beginning variable from either POST or GET

	if ($_POST[ts_weekbegin] != NULL) {
	$ts_weekbegin = $_POST[ts_weekbegin];
	} elseif ($_GET[week] != NULL) {
	$ts_weekbegin = $_GET[week];
	} else {
	$ts_weekbegin = BeginWeek(time());
	}
	
// Titles



$week_begin = date("l, jS F Y",$ts_weekbegin);
$time_week_end = $ts_weekbegin + 345600;
$week_end = date("l, jS F Y",$time_week_end);

$link_lastmonth = $ts_weekbegin - 3024000;
$link_lastweek = $ts_weekbegin - 604800;
$link_nextweek = $ts_weekbegin + 604800;
$link_nextmonth = $ts_weekbegin + 3024000;

print "<h1>Week Beginning ".TimeFormat($ts_weekbegin)."</h1>";

print "<p class=\"menu_bar\">";
print "
<a href=\"index2.php?page=timesheet_edit&amp;week=$link_lastmonth\" class=\"menu_tab\"><< w/b ".date("j/n/y",$link_lastmonth)."</a>
<a href=\"index2.php?page=timesheet_edit&amp;week=$link_lastweek\" class=\"menu_tab\">< w/b ".date("j/n/y",$link_lastweek)."</a>
<a href=\"index2.php?page=timesheet_edit&amp;week=".BeginWeek(time())."\" class=\"menu_tab\">This Week</a>
<a href=\"index2.php?page=timesheet_edit&amp;week=$link_nextweek\" class=\"menu_tab\">w/b ".date("j/n/y",$link_nextweek)." ></a>
<a href=\"index2.php?page=timesheet_edit&amp;week=$link_nextmonth\" class=\"menu_tab\">w/b ".date("j/n/y",$link_nextmonth)." >></a>
</p>";

print "<p>Entries can be edited for up to 2 hours. After this period has expired, entries can only be edited by an administrator. After 4 weeks have expired, timesheet entries to the system cannot be made.</p>";

// Check the amount of time that has passed and print the entry form if it's less than 4 weeks.
// if (time() - $ts_weekbegin < 2419200 OR $user_usertype_current > 3) {
include("inc_files/inc_data_timesheet_edit.php");
// }

// Begin the table entry system

print "<h2>Entries for this week</h2>";
include("inc_files/inc_data_timesheet_list.php");

if ($ts_list_total >= 40) {
print "<p><a href=\"timesheet_user_pdf.php?week=$ts_entry\">[print completed timesheet]</a><p>";
}


?>
