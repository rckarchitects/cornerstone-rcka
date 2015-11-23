<?php

$weekly_hours = 40;

if ($_GET[user_view] != NULL) { $viewuser = $_GET[user_view]; } else { $viewuser = $_COOKIE[user]; }


$profit = 1;

// Check whether a form has been submitted, and process the result

	if ($_POST[timesheet_add_hours] > 24 OR $_POST[timesheet_add_hours] == NULL OR $_POST[timesheet_add_hours] == 0 OR is_float($_POST[timesheet_add_hours]) ) {

		$alertmessage = "You have entered an invalid number of hours. Please review your submission and try again.";
		
		$timesheet_add_date = CleanUp($_POST[timesheet_add_date]);
		$timesheet_add_hours = (float)$_POST[timesheet_add_hours];
		$timesheet_add_desc = CleanUp($_POST[timesheet_add_desc]);
		$timesheet_add_project = CleanUp($_POST[ts_project]);

    } else {

	$nowtime = time();

    // Process the incoming data

	$timesheet_add_project = CleanUp($_POST[ts_project]);

	$timesheet_add_hours = (float)$_POST[timesheet_add_hours];
    $timesheet_add_desc = CleanUp($_POST[timesheet_add_desc]);
    
    $timesheet_add_date = CleanUp($_POST[timesheet_add_date]);
	$timesheet_add_day = date("j",$timesheet_add_date);
	$timesheet_add_month = date("n",$timesheet_add_date);
	$timesheet_add_year = date("Y",$timesheet_add_date);
	$timesheet_stage_fee = CleanUp($_POST[ts_stage_fee]);

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
	
	// Establish the current hourly rate and current stage for the project in question
	
		// First pull the rate type from the project database
		$sql3 = "SELECT proj_fee_type, proj_riba FROM intranet_projects WHERE proj_id = '$timesheet_add_project' LIMIT 1";
		$result3 = mysql_query($sql3, $conn) or die(mysql_error());
		$array3 = mysql_fetch_array($result3);
		$proj_fee_type = $array3['proj_fee_type'];
		$proj_riba = $array3['proj_riba'];
		
		if ($timesheet_stage_fee == 0 && $proj_riba > 0) { $timesheet_stage_fee = $proj_riba; }
		
		// Now calculate the profit
	
		$rate_profit = 0 ; //($rate_value_user+$overhead_rate_latest) * $profit;
		
		// Calculate the total hourly rate
		
		$rate_value = $rate_value_user;
		

	// And now stick the whole lot into the database
	
	

	$sql3 = "INSERT INTO intranet_timesheet (ts_id, ts_user, ts_project, ts_hours, ts_desc, ts_day, ts_month, ts_year, ts_entry, ts_datestamp, ts_rate, ts_overhead, ts_projectrate, ts_stage_fee, ts_day_complete, ts_cost_factored) values ('NULL', '$viewuser', '$timesheet_add_project', '$timesheet_add_hours', '$timesheet_add_desc', '$timesheet_add_day', '$timesheet_add_month', '$timesheet_add_year', '$timesheet_add_date', '$nowtime', '$rate_value', '$overhead_rate_latest', '$rate_profit', '$timesheet_stage_fee', 0, '$cost_for_week' )";
	mysql_query($sql3, $conn);
	
	$ts_item_new = mysql_affected_rows();

	
}
	
	if ($showtech == 1 ) { $techmessage = $sql3; }
	
	echo $sql;
	
?>
