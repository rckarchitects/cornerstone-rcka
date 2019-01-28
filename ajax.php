<?php

include "inc_files/inc_checkcookie.php";

if ($_GET[action] == "toggle_target" && intval($_GET[target_contact]) > 0) {
	$toggle_target = intval($_GET[target_contact]);
	ToggleTarget($toggle_target);
}

elseif ($_GET[action] == "alert_delete" && intval($_GET[alert_id]) > 0) {
	$alert_delete = intval($_GET[alert_id]);
	AlertDelete($alert_delete, $_COOKIE[user]);
}

elseif ($_GET[action] == "task_complete") {
	$task_complete = explode("_",$_GET[task_id]);
	$task_complete = intval($task_complete[2]);
	TaskComplete($task_complete);
}

elseif ($_GET[action] == "task_uncomplete") {
	$task_complete = explode("_",$_GET[task_id]);
	$task_complete = intval($task_complete[2]);
	TaskUncomplete($task_complete);
}