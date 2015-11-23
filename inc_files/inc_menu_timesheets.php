<?php

print "<h1 class=\"heading_side\">Timesheets</h1>";


print "<ul class=\"button_left\">";
print "<li><a href=\"index2.php?page=timesheet\">Open Timesheets</a></li>";

			if ($user_usertype_current > 2) {
			print "<li><a href=\"timesheet_pdf_1.php\" onclick=\"return OpenNew('timesheet_pdf_1.php')\">Timesheet Analysis&nbsp;<img src=\"images/button_doc.png\" alt=\"Timesheet Analysis\" /></a></li>";
            }
print "</ul>";

print "<h1 class=\"heading_side\">Recent timesheet entries</h1>";

print "<ul class=\"button_left\">";
	
	$sql = "SELECT * FROM intranet_timesheet, intranet_projects WHERE intranet_timesheet.ts_project = intranet_projects.proj_id AND intranet_timesheet.ts_user = '$user_id_current' order by ts_datestamp DESC LIMIT 3 ";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
	
	$ts_list_entry = $array['ts_entry'];
	$ts_list_id = $array['ts_id'];
	$ts_list_desc = $array['ts_desc'];
	$ts_list_project_num = $array['proj_num'];
	$ts_list_project_id = $array['proj_id'];
	$ts_list_hours = $array['ts_hours'];
	$proj_fee_track = $array['proj_fee_track'];
	
	$ts_list_entry_date = date("j M y", $ts_list_entry);

	print "<li>".$ts_list_entry_date.":&nbsp;";
	if ($proj_fee_track > 0) { print "<a href=\"index2.php?page=project_view&amp;proj_id=$ts_list_project_id\">"; }
	print $ts_list_project_num;
	if ($proj_fee_track > 0) { print "</a>"; }
	print ",&nbsp;".$ts_list_hours."hrs";
	
	if ($ts_list_desc != NULL) { print "<br /><font class=\"minitext\"><a href=\"index2.php?page=timesheet_edit&amp;action=edit&amp;ts_id=".$ts_list_id."\">".$ts_list_desc."</a></font>";
	}
    
	print "</li>";
	
	}
    
    if (mysql_num_rows($result) < 1) { print "No entries on the system"; }

    print "</ul>";

?>




