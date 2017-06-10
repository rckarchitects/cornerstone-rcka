<?php

if ($_GET[ts_fee_id] != NULL) { $ts_fee_id = CleanNumber($_GET[ts_fee_id]); } else { $ts_fee_id = ""; }

if ($ts_fee_id != NULL) {
	$sql = "SELECT * FROM intranet_timesheet_fees WHERE ts_fee_id = $ts_fee_id LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	
		$ts_fee_stage = $array['ts_fee_stage'];
		$ts_fee_group = $array['ts_fee_group'];
		$ts_fee_time_begin = $array['ts_fee_time_begin'];
		$ts_fee_duration = $array['ts_fee_time_end'] / 604800;
		$ts_fee_text = $array['ts_fee_text'];
		$ts_fee_value = $array['ts_fee_value'];
		$ts_fee_project = $array['ts_fee_project'];
		$ts_fee_percentage = $array['ts_fee_percentage'];
		$ts_fee_pre = $array['ts_fee_pre'];
		$ts_fee_pre_lag = round (($array['ts_fee_pre_lag'] / 604800),0);
		$ts_fee_commence = $array['ts_fee_commence'];
		$ts_fee_prospect = $array['ts_fee_prospect'];	
		$ts_fee_target = $array['ts_fee_target'];		
		$ts_fee_comment = $array['ts_fee_comment'];
		

		
		$ts_datum_commence = $array['ts_datum_commence'];
		$ts_datum_length = $array['ts_datum_length'];
		
		echo "<h1>Edit Fee Stage</h1>";
		// echo "<p class=\"menu_bar\">Menu goes here</p>";
		echo "<input type=\"hidden\" name=\"ts_fee_id\" value=\"$ts_fee_id\" />";
		
} else {

		$ts_fee_stage = CleanNumber($_POST[ts_fee_stage]);
		$ts_fee_text = CleanUp($_POST[ts_fee_text]);
		$ts_fee_value = CleanUp($_POST[ts_fee_value]);
			if ($_POST[ts_fee_project]) { $ts_fee_project = CleanUp($_POST[ts_fee_project]); }
			elseif ($_GET[proj_id]) { $ts_fee_project = CleanUp($_GET[proj_id]); }
		$ts_fee_percentage = CleanNumber($_POST[ts_fee_percentage]);
		$ts_fee_prospect = CleanNumber($_POST[ts_fee_prospect]);
		$ts_fee_target = CleanNumber($_POST[ts_fee_target]);
		$ts_fee_comment = CleanUp($_POST[ts_fee_comment]);
		$ts_fee_commence = CleanUp($_POST[ts_fee_commence]);
		
		if ($_GET[proj_id] != NULL) { $proj_id_page = $_GET[proj_id]; }
		
		echo "<h1>Add Fee Stage</h1>";
		

}

echo "<form action=\"index2.php?page=project_fees\" method=\"post\">";

// Begin the invoice entry system

	$nowtime = time();
	
	if ($ts_fee_time_begin_day > 0) { $nowtime_day = $ts_fee_time_begin_day; $thentime_day = $ts_fee_time_end_day;} else {$nowtime_day = date("d",$nowtime); $thentime_day = date("d",($nowtime)); }
	if ($ts_fee_time_begin_month > 0) { $nowtime_month = $ts_fee_time_begin_month; $thentime_month = $ts_fee_time_end_month; } else { $nowtime_month = date("m",$nowtime); $thentime_month = date("m",$nowtime); }
	if ($ts_fee_time_begin_year > 0) { $nowtime_year = $ts_fee_time_begin_year; $thentime_year = $ts_fee_time_end_year; } else { $nowtime_year = date("Y",$nowtime); $thentime_year = date("Y",$nowtime); }
	
	// Project list

	if ($ts_fee_project > 0) { $ts_fee_project_selected = $ts_fee_project; } elseif ($_GET[proj_id] > 0) { $ts_fee_project_selected = $_GET[proj_id]; }
	
echo "<fieldset><legend>Project</legend>";


if ($ts_fee_project == "") {

echo "<p><select name=\"ts_fee_project\">";

	if ($ts_project > 0) {
		$sql = "SELECT * FROM intranet_projects order by proj_num";
	} else {
		$sql = "SELECT * FROM intranet_projects WHERE proj_active = '1' order by proj_num";
	}
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$proj_active = $array['proj_active'];
	$proj_id = $array['proj_id'];
	echo "<option value=\"$proj_id\"";
		if ($proj_id == $ts_fee_project_selected) { echo " selected=\"selected\""; }
	echo ">$proj_num $proj_name</option>";
	}
	echo "</select></p>";
	
} else {

	$sql = "SELECT proj_num, proj_name, proj_id FROM intranet_projects WHERE proj_id = '$ts_fee_project'";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	echo "<p><strong>".$array['proj_num']." ".$array['proj_name']."</strong><input type=\"hidden\" name=\"ts_fee_project\" value=\"$ts_fee_project\" /><input type=\"hidden\" name=\"proj_id\" value=\"$ts_fee_project\" /></p>";
	$proj_id = $array['proj_id'];
	
}

echo "</fieldset>";

echo "<fieldset><legend>Details</legend><p>";
		
	echo "<h3>Prospect</h3><p>";
	
	if ($ts_fee_prospect == 25) { $possible = "checked=\"checked\""; }
	elseif ($ts_fee_prospect == 50) { $neutral = "checked=\"checked\""; }
	elseif ($ts_fee_prospect == 75) { $probable = "checked=\"checked\""; }
	elseif($ts_fee_prospect == 100) { $definite = "checked=\"checked\""; }
	elseif($ts_fee_prospect == 10) { $potential = "checked=\"checked\""; }
	elseif($ts_fee_prospect == 0) { $dead = "checked=\"checked\""; }
	else { $neutral = "checked=\"checked\""; } 
	
	echo "<input type=\"radio\" value=\"0\" name=\"ts_fee_prospect\" $dead />&nbsp;Dead&nbsp;";
	echo "<input type=\"radio\" value=\"10\" name=\"ts_fee_prospect\" $potential />&nbsp;Unlikely&nbsp;";
	echo "<input type=\"radio\" value=\"25\" name=\"ts_fee_prospect\" $possible />&nbsp;Possible&nbsp;";
	echo "<input type=\"radio\" value=\"50\" name=\"ts_fee_prospect\" $neutral />&nbsp;Neutral&nbsp;";
	echo "<input type=\"radio\" value=\"75\" name=\"ts_fee_prospect\" $probable />&nbsp;Probable&nbsp;";
	echo "<input type=\"radio\" value=\"100\" name=\"ts_fee_prospect\" $definite />&nbsp;Definite</p>";
	
	// Select Project Stage / Group
	
	echo "<h3>Project Stage</h3>";
	
	$sql_group = "SELECT * FROM intranet_timesheet_group WHERE group_active = 1 ORDER BY group_code, group_order";
	$result_group = mysql_query($sql_group, $conn) or die(mysql_error());
	$array_group = mysql_fetch_array($result_group);
	
	echo "<select name=\"ts_fee_group\">";
	
	echo "<option value=\"\">-- None --</option>"; 
	
	while ($array_group = mysql_fetch_array($result_group)) {
	
		$group_id = $array_group['group_id'];
		$group_order = $array_group['group_order'];
		$group_code = $array_group['group_code'];
		$group_description = $array_group['group_description'];
		$group_active = $array_group['group_active'];
		
		if ($group_code != NULL) { $group_code = $group_code . ": "; }
		
		if ($group_id == $ts_fee_group ) { $select_group = " selected=\"selected\""; } else { unset($select_group); }
		
		echo "<option value=\"$group_id\" $select_group>" . $group_code . $group_description . "</option>";
		
	}
	
	echo "</select>";
		
	// Text field

		echo "<h3>Description</h3><p>";
		echo "<span class=\"minitext\">(if applicable)</span>";
		echo "<br /><input type=\"text\" name=\"ts_fee_text\" value=\"$ts_fee_text\" maxlength=\"60\" size=\"60\" /></p>";
		
		echo "<h3>Comment</h3><p>";
		echo "<textarea name=\"ts_fee_comment\" style=\"width: 90%; height: 50px;\">" . $ts_fee_comment . "</textarea></p>";
		
		echo "</fieldset>";

	echo "<fieldset><legend>Fee Type</legend>";
	echo "<h3>Fee for Stage</h3><p><input type=\"hidden\" value=\"value\" name=\"choose\"";
	echo " />&nbsp;(&pound;) <input type=\"number\" name=\"ts_fee_value\" size=\"24\" value=\"";
		echo NumberFormat($ts_fee_value);
	echo "\" /></p>";
	
	echo "<h3>Profit Target</h3>";
	
			echo "<select name=\"ts_fee_target\">";
			
					if ($ts_fee_target == 1.0 ) { $fee_target = " selected=\"selected\""; } else { unset($fee_target); }
					echo "<option value=\"1.0\" $fee_target>Cost / Nil Profit</option>";
					
					if ($ts_fee_target == 1.05 ) { $fee_target = " selected=\"selected\""; } else { unset($fee_target); }
					echo "<option value=\"1.05\" $fee_target>5% Profit</option>";
					
					if ($ts_fee_target == 1.10) { $fee_target = " selected=\"selected\""; } else { unset($fee_target); }
					echo "<option value=\"1.10\" $fee_target>10% Profit</option>";
					
					if ($ts_fee_target == 1.15) { $fee_target = " selected=\"selected\""; } else { unset($fee_target); }
					echo "<option value=\"1.15\" $fee_target>15% Profit</option>";
					
					if ($ts_fee_target == 1.20) { $fee_target = " selected=\"selected\""; } else { unset($fee_target); }
					echo "<option value=\"1.20\" $fee_target>20% Profit</option>";
					
					if ($ts_fee_target == 1.25) { $fee_target = " selected=\"selected\""; } else { unset($fee_target); }
					echo "<option value=\"1.25\" $fee_target>25% Profit</option>";
					
					if ($ts_fee_target == 1.30) { $fee_target = " selected=\"selected\""; } else { unset($fee_target); }
					echo "<option value=\"1.30\" $fee_target>30% Profit</option>";
					
					if ($ts_fee_target == 1.35) { $fee_target = " selected=\"selected\""; } else { unset($fee_target); }
					echo "<option value=\"1.35\" $fee_target>35% Profit</option>";
					
					if ($ts_fee_target == 1.40) { $fee_target = " selected=\"selected\""; } else { unset($fee_target); }
					echo "<option value=\"1.40\" $fee_target>40% Profit</option>";
					
					if ($ts_fee_target == 1.45) { $fee_target = " selected=\"selected\""; } else { unset($fee_target); }
					echo "<option value=\"1.45\" $fee_target>45% Profit</option>";
					
					if ($ts_fee_target == 1.5 OR $ts_fee_target == NULL) { $fee_target = " selected=\"selected\""; } else { unset($fee_target); }
					echo "<option value=\"1.5\" $fee_target>50% Profit</option>";
				
			
			
			echo "</select>";
	
	echo "</fieldset>";


	echo "<fieldset><legend>Duration</legend>";
	echo "<h3>Duration of Stage in weeks (whole numbers only)</h3>";
	echo "<p><input type=\"number\" name=\"ts_fee_duration\" maxlength=\"3\" value=\"$ts_fee_duration\" /> weeks</p>";
	
	echo "<h3>Preceding Stage</h3><p>";

	echo "<select name=\"ts_fee_pre\">";
	
// Default if a fee stage has already been entered for this entry

$sql5 = "SELECT * FROM intranet_timesheet_fees WHERE ts_fee_project = '$ts_fee_project' AND ts_fee_id != '$ts_fee_id' ORDER BY ts_fee_time_begin";
$result5 = mysql_query($sql5, $conn) or die(mysql_error());
echo "<option value=\"\">-- Fixed Date (Below) --</option>";
while ($array5 = mysql_fetch_array($result5)) {
	$ts_fee_text = $array5['ts_fee_text'];
	$ts_fee_id_loop = $array5['ts_fee_id'];
	$ts_fee_stage = $array5['ts_fee_stage'];
			if ($ts_fee_stage > 0) {
				$sql3 = "SELECT riba_letter, riba_desc FROM riba_stages WHERE riba_id = '$ts_fee_stage' LIMIT 1";
				$result3 = mysql_query($sql3, $conn) or die(mysql_error());
				$array3 = mysql_fetch_array($result3);
				$ts_fee_text = $array3['riba_letter']." - ".$array3['riba_desc'];
		}
	echo "<option value=\"$ts_fee_id_loop\"";
		if ($ts_fee_pre == $ts_fee_id_loop) { echo " selected=\"selected\""; }
	echo ">$ts_fee_text</option>";
}

echo "</select>";

echo "<p><input type=\"number\" value=\"$ts_fee_pre_lag\" name=\"ts_fee_pre_lag\" />&nbsp;Enter lag (+/-) in weeks from previous stage.</p>";

// Date for start of stage if none entered

echo "<h3>Date of fee stage commencement</h3>";

if (!$ts_fee_commence) { $ts_fee_commence = date("Y-m-d",time());}

echo "<p><input type=\"date\" name=\"ts_fee_commence\" value=\"$ts_fee_commence\" /></p>";

echo "</fieldset>";

if ($ts_fee_id != "" && $proj_active == 1) {

		echo "<fieldset><legend>Change Project</legend><p>You can move this fee stage to another project here:</p>";

		$data_project = $ts_fee_project;
		$result_data = "ts_fee_project";
		include_once("dropdowns/inc_data_dropdown_projects.php");

} else { echo "<input type=\"hidden\" name=\"ts_fee_project\" value=\"$ts_fee_project\" />"; }


echo "</fieldset>";

if ($ts_fee_id > 0) {

	echo "<fieldset><legend>Datum</legend>";
	
	if ($ts_datum_commence != NULL) {	
	echo "<p>Current Datum: " . TimeFormat ( AssessDays($ts_datum_commence) ) . " to " . TimeFormat ( AssessDays($ts_datum_commence) + $ts_datum_length ) . "</p>";
	}

	if ($ts_datum_commence == NULL) { $checked = " checked=\"checked\" "; } else { unset($checked); }
	echo "<p><input type=\"checkbox\" value=\"1\" name=\"datum\" $checked />&nbsp;Reset the datum to these new dates?</p>";

	echo "</fieldset>";

}


	
		echo "<fieldset><legend>Change Project</legend>";
		echo "<p>You can move this fee stage to another project by selecting it from below. Note that this will break any links with other fee stages in the current project.</p>";

		$sql6 = "SELECT proj_num, proj_name, proj_id FROM intranet_projects ORDER BY proj_num DESC";
				$result6 = mysql_query($sql6, $conn) or die(mysql_error());
				echo "<select name=\"ts_fee_proj_change\">";
				while ($array6 = mysql_fetch_array($result6)) {
					$proj_id_change = $array6['proj_id'];
					$proj_num_change = $array6['proj_num'];
					$proj_name_change = $array6['proj_name'];
					echo "<option value=\"$proj_id_change\"";
						if ($proj_id_change == $proj_id) { echo " selected=\"selected\""; }
					echo ">$proj_num_change - $proj_name_change</option>";
		
				}

	echo "</select></fieldset>";
	

	// Close the table

	echo "<p><input type=\"hidden\" name=\"ts_fee_id\" value=\"$ts_fee_id\" /><input type=\"hidden\" name=\"action\" value=\"fees_edit\" /><input type=\"submit\" value=\"Submit\" class=\"inputsubmit\" /></p>";
	echo "</form>";


?>
