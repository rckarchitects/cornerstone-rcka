<?php

function ActionCheckLocationEntry() {
	
	global $conn;

	$sql = "SELECT location_id, location_type FROM intranet_user_location WHERE location_date = '" . date("Y-m-d",time())  . "' AND location_user = " . intval($_COOKIE['user']);
	
		
	
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	
	if ($array['location_id']) { return $array['location_id'];  }

}

function ActionLocationAdd($location_id) {
	
	global $conn;

	$sql = "INSERT INTO intranet_user_location (location_id, location_user, location_date, location_type, location_timestamp) VALUES (NULL, " . intval($_COOKIE['user']) . ", '" . date("Y-m-d",time()) . "', '" . addslashes($_POST['location_type']) . "', "  . time() . ")";
	$result = mysql_query($sql, $conn);

}

function ActionLocationUpdate($location_id) {
	
	global $conn;

	$sql = "UPDATE intranet_user_location SET location_type = '" . addslashes($_POST['location_type']) . "', location_timestamp = " . time() . " WHERE location_id = " . intval($location_id) . " LIMIT 1";
	
	$result = mysql_query($sql, $conn);

}

$location_id = ActionCheckLocationEntry();

if ($location_id > 0) {

	ActionLocationUpdate($location_id);

} else {
	
	ActionLocationAdd();
	
}

