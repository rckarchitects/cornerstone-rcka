<?php

if ($_GET[user_view] != NULL) { $user_view = $_GET[user_view]; } else { $user_view = $_COOKIE[user]; }

// Check whether a form has been submitted, and process the result

if ($_POST[ts_action] == "add_entry") {
	
	
	if ($_POST[ts_hours] > 24 OR $_POST[ts_hours] == NULL OR $_POST[ts_hours] == 0) {
		$ts_error = "You have entered an invalid amount. Please review your submission and try again.";
	} else {
		
		
	// Establish the current hourly rate and overhead rate for the form submission
	
	$sql = "SELECT * FROM timesheet_overhead order by overhead_rate DESC LIMIT 1";	
	$result = mysql_query($sql, $conn) or die(mysql_error());	
	$array = mysql_fetch_array($result);
	$overhead_rate_latest = $array['overhead_rate'];
	
	$sql = "SELECT * FROM timesheet_rate WHERE rate_user = $user_view LIMIT 1";	
	$result = mysql_query($sql, $conn) or die(mysql_error());	
	$array = mysql_fetch_array($result);
	$rate_value_user = $array['rate_value'];
	
		
	$ts_error = NULL;
	
	$ts_hours = $_POST[ts_hours];
	settype($ts_hours, "double");
	
	$ts_day = date("j",$_POST[ts_day]);
	$ts_month = date("n",$_POST[ts_day]);
	$ts_year = date("Y",$_POST[ts_day]);
	
	$nowtime = time();
	$sql = "INSERT INTO timesheet (ts_id, ts_user, ts_project, ts_hours, ts_desc, ts_day, ts_month, ts_year, ts_entry, ts_datestamp, ts_rate, ts_overhead) values ('NULL', '$user_view', '$_POST[ts_project]', '$ts_hours', '$_POST[ts_desc]', '$ts_day', '$ts_month', '$ts_year', '$_POST[ts_day]', '$nowtime', '$rate_value_user', '$overhead_rate_latest' )";
	mysql_query($sql, $conn);
	
	}
	
	
} 

if ($_POST[ts_action] == "edit_entry"){
	
	
	if ($_POST[ts_hours] > 24 OR $_POST[ts_hours] == NULL OR $_POST[ts_hours] == 0) {
		$ts_error = "You have entered an invalid amount. Please review your submission and try again.";
	} else {
		
	$ts_error = NULL;
	
	$ts_hours = $_POST[ts_hours];
	settype($ts_hours, "double");
	
	$ts_day = date("j",$_POST[ts_day]);
	$ts_month = date("n",$_POST[ts_day]);
	$ts_year = date("Y",$_POST[ts_day]);
	
	$nowtime = time();
	$sql = "UPDATE timesheet SET ts_project = '$_POST[ts_project]', ts_hours = '$_POST[ts_hours]', ts_desc = '$_POST[ts_desc]', ts_entry = '$_POST[ts_day]', ts_datestamp = '$nowtime', ts_day = '$ts_day', ts_month = '$ts_month', ts_year = '$ts_year' WHERE ts_id = '$_POST[ts_id]' LIMIT 1";
	mysql_query($sql, $conn);
	
	}
	
	
	
}




?>