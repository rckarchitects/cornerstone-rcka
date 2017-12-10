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