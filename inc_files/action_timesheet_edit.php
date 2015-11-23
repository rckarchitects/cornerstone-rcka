<?php

$viewuser = $_COOKIE[user];

$profit = 1;

$ts_id = $_POST[ts_id];

// Check whether a form has been submitted, and process the result

	if ($_POST[timesheet_add_hours] > 24 OR $_POST[timesheet_add_hours] == NULL OR $_POST[timesheet_add_hours] == 0 OR is_float($_POST[timesheet_add_hours]) ) {

		$alertmessage = "You have entered an invalid number of hours. Please review your submission and try again.";
		
		$timesheet_add_date = CleanUp($_POST[timesheet_add_date]); $ts_entry = $timesheet_add_date;
		$timesheet_add_hours = (float)$_POST[timesheet_add_hours]; $ts_hours = $timesheet_add_hours;
		$timesheet_add_desc = CleanUp($_POST[timesheet_add_desc]); $ts_desc = $timesheet_add_desc;
		$timesheet_add_project = CleanUp($_POST[ts_project]); $ts_project = $timesheet_add_project;
		
	

	}	else {

	$nowtime = time();

    // Process the incoming data

	$timesheet_add_project = CleanUp($_POST[ts_project]);

	$timesheet_add_hours = (float)$_POST[timesheet_add_hours];
    $timesheet_add_desc = CleanUp($_POST[timesheet_add_desc]);
    
    $timesheet_add_date = CleanUp($_POST[timesheet_add_date]);
	$timesheet_add_day = date("j",$timesheet_add_date);
	$timesheet_add_month = date("n",$timesheet_add_date);
	$timesheet_add_year = date("Y",$timesheet_add_date);

	// Establish the current overhead rate for the form submission

	$sql1 = "SELECT * FROM intranet_timesheet_overhead order by overhead_date DESC LIMIT 1";
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
	$array1 = mysql_fetch_array($result1);
	$overhead_rate_latest = $array1['overhead_rate'];

	// Establish the current hourly rate for the form submission

	$sql2 = "SELECT user_user_rate FROM intranet_user_details WHERE user_id = '$viewuser' LIMIT 1";
	$result2 = mysql_query($sql2, $conn) or die(mysql_error());
	$array2 = mysql_fetch_array($result2);
	$rate_value_user = $array2['user_user_rate'];
	
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
	ts_cost_factored = '$ts_cost_factored'
	WHERE ts_id = '$ts_id' LIMIT 1";
	
	mysql_query($sql3, $conn);
	
	$actionmessage = "Timesheet entry updated successfully.";
}
	
	if ($showtech == 1 ) { $techmessage = $sql3; }
	
?>
