<?php

print "<h1>Project Rates</h1>";

print "<p>Select a project from the list below to view the total hourly rates per member of staff for that project.</p>";

// Begin the array to show current hourly rates


	print "<table width=\"100%\">";
	print "<tr><td class=\"color_head\" colspan=\"2\">Project</td><td class=\"color_head\" width=\"40%\">Current Rate<br /><span class=\"minitext\">(excluding overheads and personnel rates)</span></td></tr>";


	$sql = "SELECT proj_num, proj_name, rate_value, rate_name, proj_id FROM intranet_projects, intranet_timesheet_rate_type WHERE proj_fee_track = 1 AND proj_fee_type = rate_id order by proj_num ";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	if (mysql_num_rows($result) < 1) { print "<tr><td class=\"color\" colspan=\"2\">No projects found</td></tr>"; }
	
	$color = 1;

	while ($array = mysql_fetch_array($result)) {
	$proj_id = $array['proj_id'];
	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$rate_value = $array['rate_value'];
	$rate_name = $array['rate_name'];
	
	$color_class = "color_".$color;

	print "<tr>
			<td class=\"$color_class\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a>&nbsp;$proj_name</td>
			<td class=\"$color_class\" align=\"center\"><a href=\"index2.php?page=timesheet_rates_project_list&amp;proj_id=$proj_id\">View</a></td>
			<td class=\"$color_class\" align=\"right\"><a href=\"index2.php?page=timesheet_rates_project_list&amp;proj_id=$proj_id\">$rate_name</a> [&pound;$rate_value]</td>
			</tr>";
	
	$color++;
	
	if ($color > 2) { $color = 1; }
	
	}

	print "</table>";

?>
