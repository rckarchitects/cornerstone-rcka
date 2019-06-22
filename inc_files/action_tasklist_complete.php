<?php

function ActionTaskComplete($tasklist_id) {
	
	global $conn;
	
	$sql = "UPDATE intranet_tasklist SET tasklist_percentage = 100, tasklist_completed = " . time() . " WHERE tasklist_id = " . intval($tasklist_id) . " LIMIT 1";
	
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
}


ActionTaskComplete($_POST[tasklist_id]);