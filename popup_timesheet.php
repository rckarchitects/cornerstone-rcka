<?
// Include the cookie check information

include "inc_files/inc_checkcookie.php";

// Perform any actions required

if ($_GET[user_view] > 0) { $viewuser = $_GET[user_view]; } else { $viewuser = $_COOKIE[user]; }

if ($_POST[action] != NULL) { $include_action = "inc_files/action_" . $_POST[action] .".php"; include_once($include_action); TimeSheetHours($viewuser,""); }

$timesheetcomplete = TimeSheetHours($viewuser,"return");

// Include the header information

include("inc_files/inc_header.php");

// Set the week beginning variable from either POST or GET

	if ($_POST[ts_weekbegin] != NULL) {
	$ts_weekbegin = $_POST[ts_weekbegin];
	} elseif ($_GET[week] != NULL) {
	$ts_weekbegin = $_GET[week];
	} else {
	$ts_weekbegin = BeginWeek(time());
	}

$TSFormat = "popup";

// Header

print "<body>";

print "<div id=\"pagewrapper\">";

if ($timesheetcomplete > $settings_timesheetlimit) { echo "<p><a href=\"index2.php\"><< back to intranet</a>"; }

if ($user_usertype_current > 3 AND $TSFormat == "popup") {
	
	$weeks_expired = 6; // This controls how long we wait before people who have left the practice vanish from the top bar
	$weeks_expired = $weeks_expired * 604800;
	$weeks_expired = $ts_weekbegin - $weeks_expired;

	echo "&nbsp;&nbsp;&nbsp;User:&nbsp;";
	$sql_userlist = "SELECT user_initials, user_id, user_name_first, user_name_second FROM intranet_user_details WHERE (user_user_ended IS NULL OR user_user_ended = 0) AND (user_user_ended < $weeks_expired OR  user_user_ended IS NULL OR user_user_ended = 0) ORDER BY user_initials";
	$result_userlist = mysql_query($sql_userlist, $conn);
	while ($array_userlist = mysql_fetch_array($result_userlist)) {
	$user_id = $array_userlist['user_id'];
	$user_initials = $array_userlist['user_initials'];
	$user_user_added = $array_userlist['user_user_added'];
	
	if ($user_id == $viewuser) {
	$user_name = " for " . $array_userlist['user_name_first'] ." ". $array_userlist['user_name_second'];
	$user_datum = $user_user_added;
	}
	if ($user_id != $viewuser) {
		echo "<a href=\"popup_timesheet.php?week=$ts_weekbegin&amp;user_view=$user_id\">$user_initials</a>";
	} else {
		echo "<strong>$user_initials</strong>";
	}
	
	echo " | ";
	
	
	}

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
	

echo "</p>";
	
// Titles
$week_number = date("W", $ts_weekbegin);
$week_begin = date("l, jS F Y",$ts_weekbegin);
$time_week_end = $ts_weekbegin + 345600;
$week_end = date("l, jS F Y",$time_week_end);

$link_lastmonth = $ts_weekbegin - 3024000;
$link_lastweek = $ts_weekbegin - 604800;
$link_nextweek = $ts_weekbegin + 604800;
$link_nextmonth = $ts_weekbegin + 3024000;

print "<h1>Timesheet - Week Beginning ".TimeFormat($ts_weekbegin) . "&nbsp;(" . $timesheetcomplete . "% complete)"; if ($_GET[user_view] > 0) { echo " for $user_name"; } echo " - Week " . $week_number . "</h1>";

print "<p class=\"menu_bar\">";
if ($user_view != NULL) { $user_filter = "&amp;user_view=" . $user_view; } else { $user_filter = NULL; }

if ($link_lastmonth > $user_user_added) {
	echo "<a href=\"popup_timesheet.php?user_view=$viewuser&amp;week=$link_lastmonth".$user_filter."\" class=\"menu_tab\"><< w/b <strong>".date("j M Y",$link_lastmonth)."</strong></a>";
}

if (($user_user_added - $link_lastweek) < 604800) {

echo "<a href=\"popup_timesheet.php?user_view=$viewuser&amp;week=$link_lastweek".$user_filter."\" class=\"menu_tab\">< w/b <strong>".date("j M Y",$link_lastweek)."</strong></a>";
}

echo "<a href=\"popup_timesheet.php?user_view=$viewuser&amp;week=".BeginWeek(time()) . $user_filter."\" class=\"menu_tab\"><strong>This Week</strong></a>";


if ($link_nextweek < time() AND $link_nextweek < $user_user_ended) {
echo "<a href=\"popup_timesheet.php?user_view=$viewuser&amp;week=$link_nextweek".$user_filter."\" class=\"menu_tab\">w/b <strong>".date("j M Y",$link_nextweek)." ></strong></a>"; }

if ($link_nextmonth < time() AND $link_nextmonth < $user_user_ended) {
echo "<a href=\"popup_timesheet.php?user_view=$viewuser&amp;week=$link_nextmonth".$user_filter."\" class=\"menu_tab\">w/b <strong>".date("j M Y",$link_nextmonth)."</strong> >></a>"; }

echo "</p>";

if ($_GET[user_view] == $_COOKIE[user] OR $_GET[ts_id] > 0 OR $_GET[user_view] == NULL OR $user_usertype_current > 3 ) {
include("inc_files/inc_data_timesheet_edit.php");
}

include("inc_files/inc_data_timesheet_list.php");

print "</div>";

print "</body>";
print "</html>";

?>
