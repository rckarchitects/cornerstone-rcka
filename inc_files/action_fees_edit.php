<?php

unset($alertmessage);


// Begin to clean up the $_POST submissions

		$ts_fee_id = intval ($_POST[ts_fee_id]);
		$ts_fee_project = intval ($_POST[ts_fee_project]);
		$ts_fee_text = addslashes($_POST[ts_fee_text]);
		$ts_fee_stage = addslashes($_POST[ts_fee_stage]);
		$ts_fee_group = intval ($_POST[ts_fee_group] );
		$ts_fee_target = floatval ($_POST[ts_fee_target]);
		$ts_fee_comment = addslashes($_POST[ts_fee_comment]);
		$ts_fee_commence = $_POST[ts_fee_commence];
		$ts_fee_prospect = $_POST[ts_fee_prospect];
		
		if ($ts_fee_text == NULL && $ts_fee_group != NULL) {
			$sql_group_name = "SELECT group_code, group_description FROM intranet_timesheet_group WHERE group_id = $ts_fee_group LIMIT 1";
			$result_group_name = mysql_query($sql_group_name, $conn) or die(mysql_error());
			$array_group_name = mysql_fetch_array($result_group_name);
			$ts_fee_text = addslashes($array_group_name['group_code']) . ". " . addslashes($array_group_name['group_description']);
		}
		
		$ts_fee_proj_change = $_POST[ts_fee_proj_change];
		
		if ($ts_fee_commence == 0) { $ts_fee_commence = BeginWeek ( time() ); }
		
		$choose = CleanNumber($_POST[choose]);
		
		if ($choose == "value") {
		$ts_fee_value = CleanNumber($_POST[ts_fee_value]);
		$ts_fee_percentage = "";
		} else {
		$ts_fee_percentage = CleanNumber($_POST[ts_fee_percentage]);
		$ts_fee_value = "";
		}

		$ts_fee_duration = CleanNumber($_POST[ts_fee_duration]);
		$ts_fee_pre = CleanNumber($_POST[ts_fee_pre]);
		$ts_fee_pre_lag = 604800 * $_POST[ts_fee_pre_lag];
		
		$ts_fee_duration = $ts_fee_duration * 604800;
		
		// Calculate the revised date of commencement if previous stage provided
		
		if ($ts_fee_pre > 0) {
			
					
			$sql_pre = "SELECT ts_fee_commence, ts_fee_time_end FROM intranet_timesheet_fees WHERE ts_fee_id = $ts_fee_pre LIMIT 1";
			$result_pre = mysql_query($sql_pre, $conn) or die(mysql_error());
			$array_pre = mysql_fetch_array($result_pre);
			
			$stage_length = $array_pre['ts_fee_time_end'];
			$stage_start = AssessDays ( $array_pre['ts_fee_commence'] );
			
			$delay = $ts_fee_pre_lag;
			
			$ts_fee_commence = $stage_start + $stage_length + $delay ;
			
			$ts_fee_commence = date("Y-n-j",$ts_fee_commence);
			
			
		}
		
		
		// Now update any fee stages directly linked to this one	

			if ($ts_fee_id > 0) {
				
				$ts_fee_end = $ts_fee_duration + AssessDays ( $ts_fee_commence );
				$ts_fee_end =  date("Y-n-j",$ts_fee_end);				
				
				$sql_update = "UPDATE intranet_timesheet_fees SET ts_fee_commence = $ts_fee_end WHERE ts_fee_pre = $ts_fee_id";
				$result_update = mysql_query($sql_update, $conn) or die(mysql_error());
				//echo "<p>$sql_update ($delay)</p>";
			
			}
		
		
		
		
		// Now we need to go through all of the projects on the list and update them to the new start date if the preceding stage has been updated
		
		function UpdateAll ($ts_fee_id) {
		
			GLOBAL $conn;
			GLOBAL $ts_fee_project;
			global $user_usertype_current;
			
			$count = 0;
	
			$updated_rows = 1;
		
			while ($updated_rows > 0) {
		
			$sql_all = "SELECT ts_fee_id, ts_fee_commence, ts_fee_pre, ts_fee_time_end, ts_fee_pre_lag FROM intranet_timesheet_fees WHERE ts_fee_project = $ts_fee_project AND ts_fee_id != $ts_fee_id AND ts_fee_pre > 0 ORDER BY ts_fee_pre";
			$result_all = mysql_query($sql_all, $conn) or die(mysql_error());
			
			//echo "<p>$sql_all</p>";
			
			while ($array_all = mysql_fetch_array($result_all)) {
				
				$ts_fee_id = $array_all['ts_fee_id'];
				$ts_fee_pre = $array_all['ts_fee_pre'];
				$ts_fee_pre_lag = $array_all['ts_fee_pre_lag'];
				
				//echo "<h3>ID: $ts_fee_id - $ts_fee_pre</h3>";
			
						// Work out the conclusion of the previous linked stage
							$sql_previous = "SELECT ts_fee_id, ts_fee_commence, ts_fee_pre, ts_fee_time_end FROM intranet_timesheet_fees WHERE ts_fee_project = $ts_fee_project AND ts_fee_id = $ts_fee_pre LIMIT 1";
							$result_previous = mysql_query($sql_previous, $conn) or die(mysql_error());
							$array_previous = mysql_fetch_array($result_previous);
							$newdate = AssessDays ( $array_previous['ts_fee_commence'] ) + $array_previous['ts_fee_time_end'] + $ts_fee_pre_lag;
							$newdate = date("Y-n-j",$newdate);
							//echo "<p>$sql_previous</p>";
						
						//echo "<p>New Start: " . $newdate . "</p>";
						
						$sql_push = "UPDATE intranet_timesheet_fees SET ts_fee_commence = '$newdate' WHERE ts_fee_id = $ts_fee_id AND ts_fee_project = $ts_fee_project";
						$result_push = mysql_query($sql_push, $conn) or die(mysql_error());

						$updated_rows = mysql_affected_rows();
						//if ($updated_rows == 0 && $user_usertype_current > 2) { echo "<p>[$count] " . $sql_push . "(Updated Rows: $updated_rows)</p>"; } else { echo "<p>[$count] " . $sql_push . "(Updated Rows: $updated_rows)</p>";}
									
						//echo "<p>$sql_push<br />Affected rows: " .  $updated_rows . "</p>";
						
								if ($_POST[datum] == 1) {
			
								$sql_datum = "UPDATE intranet_timesheet_fees SET ts_datum_commence = '$newdate' WHERE ts_fee_id = $ts_fee_id LIMIT 1";
								$result_datum = mysql_query($sql_datum, $conn) or die(mysql_error());
			
								}
				
				}
				

				
				$count++;
				
				// Let's try and find what's happening inside this loop...
				if ($user_usertype_current > 3) {
						$actionmessage = "<p>" . addslashes ( $sql_push ) . "</p>";
						AlertBoxInsert($_COOKIE[user],"Fee Stages Updated",$actionmessage,$ts_id,0,0);
				}
				
				if ($updated_rows == 0) { break; }
				
				// This is a horrible way of breaking the loop if necessary
				if ($count > 100) { break; }
		
			}
			
		}
		

		
		// Now update the timesheet system and fees database if the fee stage has been moved to another projects
		
		if ($ts_fee_proj_change != $ts_fee_project && $ts_fee_id > 0) {
			
			//THIS ISN'T WORKING
			
			$sql_fee_update = "UPDATE intranet_timesheet_fees SET ts_fee_project = $ts_fee_proj_change, ts_fee_pre = NULL, ts_fee_pre_lag = 0 WHERE ts_fee_project = $ts_fee_project AND ts_fee_id = $ts_fee_id LIMIT 1";
			$result_fee_update = mysql_query($sql_fee_update, $conn) or die(mysql_error());
			$updated_rows = mysql_affected_rows();
			//echo "<p>$sql_fee_update<br />$updated_rows affected rows</p>";
			
			$sql_fee_update_link = "UPDATE intranet_timesheet_fees SET ts_fee_pre = NULL, ts_fee_pre_lag = 0 WHERE ts_fee_project = $ts_fee_project AND ts_fee_pre = $ts_fee_id";
			$result_fee_update_link = mysql_query($sql_fee_update_link, $conn) or die(mysql_error());
			$updated_rows = mysql_affected_rows();
			//echo "<p>$sql_fee_update_link<br />$updated_rows affected rows</p>";
			
			$sql_timesheet_update = "UPDATE intranet_timesheet SET ts_project = $ts_fee_proj_change WHERE ts_project = $ts_fee_project AND ts_stage_fee = $ts_fee_id";
			$result_timesheet_update = mysql_query($sql_timesheet_update, $conn) or die(mysql_error());
			$updated_rows = mysql_affected_rows();
			//echo "<p>$sql_timesheet_update<br />$updated_rows affected rows</p>";
			
			
		}
		
		

// Check that the required values have been entered, and alter the page to show if these values are invalid

// elseif ($ts_fee_text == "" ) { $alertmessage = "The description was left empty."; $page_redirect = "timesheet_fees_edit"; }
// elseif ($ts_fee_value < 1 AND $ts_fee_percentage < 1) { $alertmessage = "The fee amount was left empty."; $page_redirect = "timesheet_fees_edit";}
if ($ts_fee_percentage > 100) { $alertmessage = "The fee percentage is greater than 100."; $page_redirect = "timesheet_fees_edit";}

if ($alertmessage == NULL) {

// Construct the MySQL instruction to add these entries to the database

if ($ts_fee_id > 0) {
	
		$sql_edit = "UPDATE intranet_timesheet_fees SET
		ts_fee_stage = '$ts_fee_stage',
		ts_fee_group = '$ts_fee_group',
		ts_fee_time_begin = '',
		ts_fee_time_end = '$ts_fee_duration',
		ts_fee_text = '$ts_fee_text',
		ts_fee_value = '$ts_fee_value',
		ts_fee_project = '$ts_fee_project',
		ts_fee_percentage = '$ts_fee_percentage',
		ts_fee_pre = '$ts_fee_pre',
		ts_fee_pre_lag = '$ts_fee_pre_lag',
		ts_fee_commence = '$ts_fee_commence',
		ts_fee_prospect = '$ts_fee_prospect',
		ts_fee_target = '$ts_fee_target',
		ts_fee_comment = '$ts_fee_comment'
		WHERE ts_fee_id = '$ts_fee_id' LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$techmessage = $sql_edit;
		
		if ($_POST[datum] == 1) {
			
			$sql_datum = "UPDATE intranet_timesheet_fees SET ts_datum_commence = '$ts_fee_commence', ts_datum_length = '$ts_fee_duration' WHERE ts_fee_id = '$ts_fee_id' LIMIT 1";
			$result = mysql_query($sql_datum, $conn) or die(mysql_error());
			$actionmessage = "<p>Fee stage \"<a href=\"index2.php?page=project_fees&amp;proj_id=$ts_fee_project\">$ts_fee_text</a>\" updated successfully.</p>";
			
		} else {
			
			$actionmessage = "<p>Fee stage \"<a href=\"index2.php?page=project_fees&amp;proj_id=$ts_fee_project\">$ts_fee_text</a>\" updated successfully.</p>";
			
		}
		
		UpdateAll($ts_fee_id);
		
		AlertBoxInsert($_COOKIE[user],"Fee Stage Updated",$actionmessage,$ts_fee_id,0,0,$ts_fee_project);
		
		//echo "<p>$sql_edit</p>";
		
} else {

		$sql_add = "INSERT INTO intranet_timesheet_fees (
		ts_fee_id,
		ts_fee_stage,
		ts_fee_group,
		ts_fee_time_begin,
		ts_fee_time_end,
		ts_fee_text,
		ts_fee_value,
		ts_fee_project,
		ts_fee_percentage,
		ts_fee_pre,
		ts_fee_pre_lag,
		ts_fee_commence,
		ts_fee_prospect,
		ts_fee_target,
		ts_fee_comment,
		ts_datum_commence,
		ts_datum_length
		) values (
		NULL,
		'$ts_fee_stage',
		'$ts_fee_group',
		'',
		'$ts_fee_duration',
		'$ts_fee_text',
		'$ts_fee_value',
		'$ts_fee_project',
		'$ts_fee_percentage',
		'$ts_fee_pre',
		'$ts_fee_pre_lag',
		'$ts_fee_commence',
		'$ts_fee_prospect',
		'$ts_fee_target',
		'$ts_fee_comment',
		'$ts_fee_commence',
		'$ts_fee_duration'
		)";
		
		
		$result = mysql_query($sql_add, $conn) or die(mysql_error());
		$ts_fee_id = mysql_affected_rows();
		$actionmessage = "<p>Fee stage \"<a href=\"index2.php?page=project_view&amp;proj_id=$ts_fee_project\">$ts_fee_text</a>\" added successfully.</p>";
		$techmessage = $sql_add;
		AlertBoxInsert($_COOKIE[user],"Fee Stage Added",$actionmessage,$ts_fee_id,0,0,$ts_fee_project);
}





}
