<?php

if ($TSFormat == "popup") { $TSPage = "popup_timesheet.php?"; } else { $TSPage = "index2.php?page=timesheet_edit&amp;"; }

if ($_GET[ts_id] != NULL) { $ts_id = $_GET[ts_id]; } elseif ($_POST[ts_id] != NULL) { $ts_id = $_POST[ts_id]; }

if ($ts_id != NULL AND $_POST[action] == NULL) {
	print "<p class=\"submenu_bar\"><a href=\"".$TSPage."week=$ts_weekbegin\" class=\"submenu_bar\">Add New</a></p>";
	print "<h2>Edit Existing Timesheet Entry</h2>";
	$sql_ts = "SELECT * FROM intranet_timesheet WHERE ts_id = '$ts_id' LIMIT 1";
	$result_ts = mysql_query($sql_ts, $conn) or die(mysql_error());
	$array_ts = mysql_fetch_array($result_ts);
	$ts_id = $array_ts['ts_id'];
	$ts_project = $array_ts['ts_project'];
	$ts_entry = $array_ts['ts_entry'];
	$ts_hours = $array_ts['ts_hours'];
	$ts_desc = $array_ts['ts_desc'];
	$ts_datestamp = $array_ts['ts_datestamp'];
	$ts_stage_fee = $array_ts['ts_stage_fee'];
	
	if ($ts_stage_fee > 0) {
		$sql_fee = "SELECT * FROM intranet_timesheet_fees WHERE ts_fee_id = '$ts_stage_fee' LIMIT 1";
		$result_fee = mysql_query($sql_fee, $conn) or die(mysql_error());
		$array_fee = mysql_fetch_array($result_fee);
		$ts_fee_id = $array_fee['ts_fee_id'];
		$ts_fee_text = $array_fee['ts_fee_text'];
		$ts_fee_stage = $array_fee['ts_fee_stage'];
				if ($ts_fee_stage > 0) {
					$sql_riba = "SELECT * FROM riba_stages WHERE riba_id = '$ts_fee_stage' LIMIT 1";
					$result_riba = mysql_query($sql_riba, $conn) or die(mysql_error());
					$array_riba = mysql_fetch_array($result_riba);
					$riba_letter = $array_riba['riba_letter'];
					$riba_desc = $array_riba['riba_desc'];
					$ts_fee_text = $riba_letter." - ".$riba_desc;
				}
	}
	
} else {
	print "<h2>Add Timesheet Entry</h2>";
}

print "<form action=\"".$TSPage."week=$ts_weekbegin"."&amp;user_view=$viewuser"."\" method=\"post\">";

	if ($TSFormat == "popup") { echo "<table summary=\"Timesheet entry form\"><tr><td colspan=\"4\">"; }

		include("dropdowns/inc_data_dropdown_timesheet_project.php");
		
	if ($TSFormat == "popup") { echo "</td></tr><tr><td>"; } else { print "<h3>Select Date</h3><p>"; }


include("inc_data_timesheet_dropdown_date.php");

	if ($TSFormat == "popup") { echo "</td><td>"; } else { echo "</p><h3>Enter Hours</h3><p>"; }
	
	if ($ts_hours == NULL) {
	
	echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"8\" checked=\"checked\" />All Day (8h)&nbsp;<br />";
	echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"0.5\" />0.5h&nbsp;";
	echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"1\" />1h&nbsp;";
	echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"2\" />2h&nbsp;";
	echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"3\" />3h&nbsp;";
	echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"4\" />4h&nbsp;";
	echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"5\" />5h&nbsp;";
	echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"6\" />6h&nbsp;";
	echo "<input type=\"radio\" name=\"timesheet_add_hours\" value=\"7\" />7h&nbsp;";
	
	
	} else {

	print "<input type=\"text\" class=\"inputbox\" name=\"timesheet_add_hours\" size=\"12\" maxlength=\"6\" value=\"$ts_hours\" />";
	
	}

	if ($TSFormat == "popup") { echo "</td><td style=\"width: 45%;\">"; } else { echo "</p><h3>Enter Description</h3><p>"; }

print "<textarea class=\"inputbox\" name=\"timesheet_add_desc\" style=\"width: 97%;\" rows=\"2\">$ts_desc</textarea>";

	if ($TSFormat == "popup") { echo "</td><td rowspan=\"2\">"; } else { echo "</p><p>"; }

	if ($_GET[ts_id] > 0) {
	print "<input type=\"submit\" value=\"Update\" class=\"inputsubmit\" />";
	print "<input type=\"hidden\" value=\"timesheet_edit\" name=\"action\" />";
	print "<input type=\"hidden\" name=\"ts_id\" value=\"$ts_id\" />";
	} else {
	print "<input type=\"submit\" value=\"Add\" class=\"inputsubmit\" />";
	print "<input type=\"hidden\" value=\"timesheet_add\" name=\"action\" />";
	}
	
	if ($TSFormat == "popup") { echo "</td></tr></table>"; } else { echo "</p>"; }
	

print "</form>";

?>
