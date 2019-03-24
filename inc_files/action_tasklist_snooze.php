<?php

function ActionTaskSnooze($tasklist_id,$snooze_value) {
	
	global $conn;
	
	$newtime = time() + ( intval($snooze_value) * 604800);
	
	$sql = "UPDATE intranet_tasklist SET tasklist_due = " . $newtime .  " WHERE tasklist_id = " . intval($tasklist_id) . " LIMIT 1";
	
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
		
	
}


ActionTaskSnooze($_POST[tasklist_id],$_POST[snooze_value]);