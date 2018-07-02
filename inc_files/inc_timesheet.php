<?php


// Perform any actions required

if ($_GET[user_view] > 0) {
	$viewuser = intval( $_GET[user_view] );
} elseif ($_POST[user_view] > 0) {
	$viewuser = intval( $_GET[user_view] );
} else {
	$viewuser = intval( $_COOKIE[user] );
}

if ($_POST[action] != NULL) { TimeSheetHours($viewuser,""); }

// Work out if we're editing an existing entry

if (intval($_GET[ts_id]) > 0) {
	$ts_id = intval($_GET[ts_id]);
} elseif (intval ($_POST[ts_id]) > 0) {
	$ts_id = intval($_POST[ts_id]);
}

// Set the week beginning variable from either POST or GET

if (intval($_POST[ts_weekbegin]) > 0 ) {
	$ts_weekbegin = intval($_POST[ts_weekbegin]);
} elseif (intval($_GET[week]) > 0) {
	$ts_weekbegin = intval($_GET[week]);
} else {
	$ts_weekbegin = intval(BeginWeek(time()));
}


TimeSheetHeader($ts_weekbegin, $viewuser);

if ($viewuser == $_COOKIE[user] OR $_GET[ts_id] > 0 OR $viewuser == NULL OR $user_usertype_current > 3 ) {
	TimeSheetEdit($ts_weekbegin,$viewuser,$ts_id);
}

TimeSheetUserUpdates($viewuser, $ts_weekbegin);

TimeSheetList($viewuser,$ts_weekbegin,$user_usertype_current);

