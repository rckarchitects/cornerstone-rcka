<?php

	if ($ts_id > 0) {
	$sql = "SELECT * FROM intranet_projects order by proj_num";
	} else {
	$sql = "SELECT * FROM intranet_projects WHERE proj_active = '1' order by proj_num";
	}
	$result = mysql_query($sql, $conn) or die(mysql_error());
	print "<select name=\"timesheet_add_project\" class=\"inputbox\">";
	while ($array = mysql_fetch_array($result)) {
	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$proj_id = $array['proj_id'];
	$proj_riba = $array['proj_riba'];
	
	if ($ts_stage_fee > 0) { $riba_stage_show = $ts_stage_fee; } elseif ($proj_riba > 0) { $riba_stage_show = $proj_riba; }
	
		if ($proj_riba > 0 AND $ts_id == 0) {
		$sql_stage_fee = "SELECT riba_letter FROM riba_stages WHERE riba_id = '$riba_stage_show' LIMIT 1";
		$result_stage_fee = mysql_query($sql_stage_fee, $conn) or die(mysql_error());
		$array_stage_fee = mysql_fetch_array($result_stage_fee);
		$riba_letter = $array_stage_fee['riba_letter'];
		$fee_option = " - ".$riba_letter;
		}
	
	print "<option value=\"$proj_id\" class=\"inputbox\"";
	if ($ts_project == $proj_id) {
	print " selected";
	}
	print ">$proj_num&nbsp;$proj_name$fee_option</option>";
	}
	print "</select>";
	
?>
