<?
// Include the cookie check information

include "inc_files/inc_checkcookie.php";

// Perform any actions required

if ($_POST[action] != NULL) { $include_action = "inc_files/action_" . $_POST[action] .".php"; include_once($include_action); }

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

echo "<p><a href=\"index2.php\"><< back to intranet</a>";

echo "</p>";
	
// Titles

$week_begin = date("l, jS F Y",$ts_weekbegin);
$time_week_end = $ts_weekbegin + 345600;
$week_end = date("l, jS F Y",$time_week_end);

$link_lastmonth = $ts_weekbegin - 3024000;
$link_lastweek = $ts_weekbegin - 604800;
$link_nextweek = $ts_weekbegin + 604800;
$link_nextmonth = $ts_weekbegin + 3024000;

print "
<h2><a href=\"popup_timesheet_mobile.php?week=$link_lastweek".$user_filter."\">< w/b ".date("j M Y",$link_lastweek)."</a></h2>";

if (BeginWeek(time()) != $_GET[week]) { echo "<h2><a href=\"popup_timesheet_mobile.php?week=".BeginWeek(time()) . $user_filter."\">This Week</a></h2>";  }

if ($link_nextweek < time()) {
echo "<h2><a href=\"popup_timesheet_mobile.php?week=$link_nextweek".$user_filter."\" >w/b ".date("j M Y",$link_nextweek)." ></a>"; }

print "<h1>Timesheet, w/b ".TimeFormat($ts_weekbegin); if ($_GET[user_view] > 0) { echo " for $user_name"; } echo "</h1>";

if ($_GET[user_view] == $_COOKIE[user] OR $_GET[ts_id] > 0 OR $_GET[user_view] == NULL) {
include("inc_files/inc_data_timesheet_edit.php");
}

include("inc_files/inc_data_timesheet_list.php");

print "</div>";

print "</body>";
print "</html>";

?>
