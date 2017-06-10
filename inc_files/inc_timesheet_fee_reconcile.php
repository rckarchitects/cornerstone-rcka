<?php

if ($_GET[ts_fee_id] != NULL) { $ts_fee_id = CleanNumber($_GET[ts_fee_id]); } else { $ts_fee_id = ""; }

if ($user_usertype_current > 3 OR $ts_fee_id == NULL OR $ts_fee_id == 0 ) {
	
	// Array for project stages first (to reduce load on server)
	
	
	function FeeStageSelect($proj_id,$ts_entry) {
		
	GLOBAL $conn;
		
	$sql_fees = "SELECT ts_fee_id, ts_fee_text, ts_fee_commence, ts_fee_time_end FROM intranet_timesheet_fees WHERE ts_fee_project = $proj_id ORDER BY ts_fee_commence DESC";
	$result_fees = mysql_query($sql_fees, $conn) or die(mysql_error());
		
	
		echo "<select name=\"update_ts_entry_fee[]\">";
		
		echo "<option value=\"\">-- Not Assigned --</option>";
		
		$check_selected = 0;
		
		while ($array_fees = mysql_fetch_array($result_fees)) {
			
		$stage_start = explode("-",$array_fees['ts_fee_commence']);
		$stage_start = mktime(0,0,0,$stage_start[1],$stage_start[2],$stage_start[0]);
		$stage_end = $stage_start + $array_fees['ts_fee_time_end'];
		
		$begin = TimeFormat($stage_start);
		$end = TimeFormat($stage_end);
			
			$ts_fee_id = $array_fees['ts_fee_id'];
			$ts_fee_text = $array_fees['ts_fee_text'];
			if (($ts_entry >= $stage_start) && $check_selected == 0) { $selected = " selected=\"selected\" "; $check_selected = 1; } else { unset($selected); }
			echo "<option value=\"$ts_fee_id\" $selected>$ts_fee_text ($begin - $end)</option>";
			
		}
				
		echo "</select>";
		
		
	}
	

	echo "<h2>Reconcile Incomplete Timesheets</h2>";
	
	$sql = "SELECT * FROM intranet_timesheet, intranet_user_details WHERE user_id = ts_user AND ts_project = $proj_id AND ts_stage_fee = 0 ORDER BY ts_entry, ts_stage_fee";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		
		echo "<form action=\"index2.php?page=project_fees&amp;proj_id=$proj_id\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"action\" value=\"timesheet_fee_reconcile\" />";
		
		echo "<table>";
		while ($array = mysql_fetch_array($result)) {
			
			$ts_id = $array['ts_id'];
			$ts_entry = $array['ts_entry'];
			$ts_hours = $array['ts_hours'];
			$ts_desc = $array['ts_desc'];
			$ts_user = $array['ts_user'];
			$user_initials = $array['user_initials'];
			
			$ts_date_format = TimeFormat($ts_entry);
			
			echo "<tr><td>$user_initials</td><td>$ts_date_format</td><td>$ts_desc</td><td>";
			FeeStageSelect($proj_id,$ts_entry);
			echo "<input type=\"hidden\" name=\"update_ts_entry_id[]\" value=\"$ts_id\" />";
			echo "</td></tr>";
			
			
		}
		
		echo "</table>";
		
		echo "<input type=\"submit\" value=\"Update Timesheet Entries\" /></form>";
	}

} else {
	
	echo "<h2>Access Denied.</h2><p>You do not have sufficient privileges to view this page.";
	
}
















