<?php


include "inc_files/inc_checkcookie.php"; 

$cpd = 75;

// Begin creating the page

echo "<table><tr><td>ID</td><td>Date</td><td>Hours</td><td>Description</td></tr>";


// Get the relevant infomation from the Invoice Database

	$sql = "SELECT * FROM intranet_timesheet WHERE ts_project = $cpd AND ts_user = $_COOKIE[user]";
	
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
	
		$ts_id = $array['ts_id'];
		$ts_entry = date ("d M Y" , $array['ts_entry'] );
		$ts_desc = $array['ts_desc'];
		$ts_hours = $array['ts_hours'];
		


		
echo "<tr><td>".$ts_id."</td><td>".$ts_entry."</td><td>".$ts_hours."</td><td>".$ts_desc."</td></tr>";



}

echo "</table>";

?>
