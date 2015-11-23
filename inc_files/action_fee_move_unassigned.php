<?php

if ($_POST[proj_id] > 0 && $_POST[ts_fee_id] > 0 && $user_usertype_current > 3) {
	
	$proj_id = $_POST[proj_id];
	$ts_fee_id  = $_POST[ts_fee_id];
	$ts_fee_text  = $_POST[ts_fee_text];
	
	$sql_update = "UPDATE intranet_timesheet SET ts_stage_fee = $ts_fee_id WHERE ts_stage_fee = 0  AND ts_project = $proj_id";
	
	$result_update = mysql_query($sql_update, $conn) or die(mysql_error());
	
	$rows_affected = mysql_affected_rows();
	
	if ($rows_affected == 1) {
		
		$actionmessage = "A single timesheet record has been reallocated.";
		
	} elseif ($rows_affected > 1) {
		
		$actionmessage = "A total of $rows_affected timesheet records have been reallocated.";
		
	}
	
	
}























?>