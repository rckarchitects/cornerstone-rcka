<?php

function ActionCheckLocationEntry($time) {
	
	global $conn;
	
	if (intval($time) == 0) { $time = time(); } else { $time = intval($time); }

	$sql = "SELECT location_id, location_type FROM intranet_user_location WHERE location_date = '" . date("Y-m-d",$time)  . "' AND location_user = " . intval($_COOKIE['user']);
	
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	
	if ($array['location_id']) { return $array['location_id'];  }

}

function ActionLocationAdd($location_id,$time) {
	
	global $conn;
	
	if (intval($time) == 0) { $time = time(); } else { $time = intval($time); }

	$sql = "INSERT INTO intranet_user_location (location_id, location_user, location_date, location_type, location_timestamp) VALUES (NULL, " . intval($_COOKIE['user']) . ", '" . date("Y-m-d",$time) . "', '" . addslashes($_POST['location_type']) . "', "  . time() . ")";
	$result = mysql_query($sql, $conn);

}

function ActionLocationUpdate($location_id,$time) {
	
	global $conn;
	
	if (intval($time) == 0) { $time = time(); } else { $time = intval($time); }

	$sql = "UPDATE intranet_user_location SET location_type = '" . addslashes($_POST['location_type']) . "', location_date = '" . date("Y-m-d",$time) . "', location_timestamp = " . $time . " WHERE location_id = " . intval($location_id) . " LIMIT 1";
	
	//echo "<p>" . $sql  . "</p>";
	
	$result = mysql_query($sql, $conn);

}

if (intval($_POST['location_nextday']) == 1) { $time = NextWorkingDay(); } else { $time = time(); }

$location_id = ActionCheckLocationEntry($time);

if ($location_id > 0) {

	ActionLocationUpdate($location_id,$time);

} else {
	
	ActionLocationAdd(NULL,$time);
	
}

