<?php

// Perform any actions required

if ($_GET[user_view] > 0) { $viewuser = intval( $_GET[user_view] ); } else { $viewuser = intval( $_COOKIE[user] ); }

if ($_POST[action] != NULL) { $include_action = "inc_files/action_" . $_POST[action] .".php"; include_once($include_action); TimeSheetHours($viewuser,""); }


// Set the week beginning variable from either POST or GET

	if (intval($_POST[ts_weekbegin]) > 0 ) {
	$ts_weekbegin = intval($_POST[ts_weekbegin]);
	} elseif (intval($_GET[week]) > 0) {
	$ts_weekbegin = intval($_GET[week]);
	} else {
	$ts_weekbegin = intval(BeginWeek(time()));
	}


TimeSheetHeader($ts_weekbegin, $viewuser,$user_usertype_current);

if ($viewuser == $_COOKIE[user] OR $_GET[ts_id] > 0 OR $viewuser == NULL OR $user_usertype_current > 3 ) {
$ts_weekbegin = intval ($_GET[week]);

TimeSheetEdit($ts_weekbegin,$viewuser);
}

include("inc_files/inc_data_timesheet_list.php");
