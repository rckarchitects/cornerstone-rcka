<?php


include "inc_files/inc_checkcookie.php"; 

$cpd = 75;

// Begin creating the page

echo "<table><tr><th>ID</th><th>Project Number</th><th>Project Name</th><th>Project Stage</th><th>Standard Fee Stage</th><th>User</th><th>Date</th><th>Hours</th><th>Cost</th><th>Description</th></tr>";


// Get the relevant infomation from the Invoice Database

	$sql = "SELECT * FROM intranet_projects, intranet_user_details, intranet_timesheet LEFT JOIN intranet_timesheet_fees ON ts_stage_fee = ts_fee_id LEFT JOIN intranet_timesheet_group ON group_id = ts_fee_group WHERE ts_project = proj_id AND ts_user = user_id  AND ts_entry > 1430438400 AND ts_entry < 1462060740 AND proj_fee_track = 1 ORDER BY ts_entry, proj_num, user_name_second";
	
	//$sql = "SELECT * FROM intranet_projects, intranet_user_details, intranet_timesheet WHERE ts_project = proj_id AND ts_user = user_id AND ts_entry > 1398902400 ORDER BY ts_stage_fee";
	
	//$sql = "SELECT * FROM intranet_timesheet LEFT JOIN intranet_timesheet_fees ON ts_fee_stage = ts_fee_id ORDER BY ts_id LIMIT 10";
	
	//$sql = "SELECT * FROM intranet_timesheet ORDER BY ts_entry LIMIT 10";
	
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
	
		$ts_id = $array['ts_id'];
		$ts_entry = date ("d M Y" , $array['ts_entry'] );
		$ts_desc = $array['ts_desc'];
		$ts_hours = $array['ts_hours'];
		$ts_rate = $array['ts_rate'];
		$ts_cost = $ts_hours * $ts_rate;
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$proj_fee_track = $array['proj_fee_track'];
		$ts_fee_id = $array['ts_fee_id'];
		$ts_fee_text = $array['ts_fee_text'];
		$ts_fee_value = $array['ts_fee_value'];
		$ts_stage_fee = $array['ts_stage_fee'];
		
		$group_code = $array['group_code'];
		$group_description = $array['group_description'];
		
		if ($group_code != NULL) {
		$group = $group_code . ". " . $group_description;
		} else {
		$group = "<span style=\"color: red;\">- Not Assigned -</span>";
		}
		
		if ($proj_fee_track == 1 && ($ts_stage_fee == "0" OR $ts_fee_value == "0" OR $ts_stage_fee == NULL OR $ts_fee_value == NULL)) { $speculative = "- Unpaid Prospect -"; } else { unset($speculative); }
		
		//$ts_fee_text = $array['ts_stage_fee']; 

		$ts_user = $array['user_name_first'] . " " . $array['user_name_second'];


		
echo "<tr><td>".$ts_id."</td><td>".$proj_num."</td><td>".$proj_name."</td><td>".$ts_fee_text."</td><td>".$group."</td><td>".$ts_user."</td><td>".$ts_entry."</td><td>".$ts_hours."</td><td>".$ts_cost."</td><td>".$ts_desc."</td></tr>";



}

echo "</table>";

?>
