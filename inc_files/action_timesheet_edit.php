<?php

function Action_TimeSheetEdit($ts_id) {

		global $conn;

		$profit = 1;

		$ts_id = intval($ts_id);

		// Check whether a form has been submitted, and process the result

			$nowtime = time();

			// Process the incoming data

			$timesheet_add_project = CleanUp($_POST[ts_project]);

			$timesheet_add_hours = floatval($_POST[timesheet_add_hours]);
			$timesheet_add_desc = CleanUp($_POST[timesheet_add_desc]);
			
			$timesheet_add_date = CleanUp($_POST[timesheet_add_date]);
			$timesheet_add_day = date("j",$timesheet_add_date);
			$timesheet_add_month = date("n",$timesheet_add_date);
			$timesheet_add_year = date("Y",$timesheet_add_date);
			
			$ts_user = intval($_POST[ts_user]);

			// Establish the current overhead rate for the form submission

			$sql1 = "SELECT * FROM intranet_timesheet_overhead order by overhead_date DESC LIMIT 1";
			$result1 = mysql_query($sql1, $conn) or die(mysql_error());
			$array1 = mysql_fetch_array($result1);
			$overhead_rate_latest = $array1['overhead_rate'];

			// Establish the current hourly rate for the form submission

			$sql2 = "SELECT user_user_rate, user_prop_target FROM intranet_user_details WHERE user_id = '$ts_user' LIMIT 1";
			$result2 = mysql_query($sql2, $conn) or die(mysql_error());
			$array2 = mysql_fetch_array($result2);
			$rate_value_user = $array2['user_user_rate'];
			$user_prop_target = $array2['user_prop_target'];
			
			echo "<p>$sql2</p>";
			
			// Calculate the total hourly rate
				
				$rate_value = $rate_value_user;
				
			// Calculate the profit
				
				$ts_profit = 0;
				
				// Update the stage fee with the override dropdown
				
				$ts_stage_fee = $_POST[ts_stage_fee];
				
			// And now stick the whole lot into the database


			$sql3 = "
			UPDATE intranet_timesheet SET
			ts_project = '$timesheet_add_project',
			ts_hours = '$timesheet_add_hours',
			ts_desc = '$timesheet_add_desc',
			ts_day = '$timesheet_add_day',
			ts_month = '$timesheet_add_month',
			ts_year = '$timesheet_add_year',
			ts_entry = '$timesheet_add_date',
			ts_rate = '$rate_value',
			ts_projectrate = '$ts_profit',
			ts_stage_fee = '$ts_stage_fee',
			ts_day_complete = '$ts_day_complete',
			ts_cost_factored = '$ts_cost_factored',
			ts_prop_adjust = '$ts_prop_adjust',
			ts_non_fee_earning = '$user_prop_target'
			WHERE ts_id = '$ts_id' LIMIT 1";
			
			mysql_query($sql3, $conn);
			
			$actionmessage = "<p>Timesheet entry #<a href=\"index2.php?page=timesheet&amp;ts_id=$ts_id\">$ts_id</a> updated successfully.</p>";
			
			AlertBoxInsert($_COOKIE[user],"Timesheet Edited",$actionmessage,$ts_id,0,0);
	
}

if (intval($_POST[timesheet_add_hours] && intval($_POST[ts_id]) > 0) < 24) {
	Action_TimeSheetEdit($_POST[ts_id]);
}