<?php

if ($proj_id == NULL AND $_GET[proj_id] != NULL) { $proj_id = $_GET[proj_id]; }

print "
<p class=\"submenu_bar\">
<a href=\"index2.php?page=tasklist_edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add New Task</a>
<a href=\"index2.php?page=tasklist_complete&amp;proj_id=$proj_id\" class=\"submenu_bar\">Completed Tasks</a>
</p>";

print "<h2>Outstanding Tasks";

if ($proj_id != NULL) {
$sql_proj = "SELECT proj_num, proj_name FROM intranet_projects WHERE proj_id = '$proj_id' LIMIT 1 ";
$result_proj = mysql_query($sql_proj, $conn) or die(mysql_error());
$array_proj = mysql_fetch_array($result_proj);
$proj_num = $array_proj['proj_num'];
$proj_name = $array_proj['proj_name'];
echo " - ".$proj_num." ".$proj_name;
}

echo "</h2>";

// Determine the date a week ago

$date_lastweek = time() - 604800;

if ($_GET[show] == "user") { $user_tasks = "AND tasklist_person = ".$user_id_current; } else { $user_tasks = NULL; }

$sql = "SELECT * FROM intranet_tasklist WHERE tasklist_project = '$proj_id' $user_tasks AND tasklist_percentage < 100 order by tasklist_due DESC";

$result = mysql_query($sql, $conn) or die(mysql_error());

if (mysql_num_rows($result) > 0) {

$counter = 1;

while ($array = mysql_fetch_array($result)) {
  
$tasklist_id = $array['tasklist_id'];
$tasklist_notes = $array['tasklist_notes'];
$tasklist_percentage = $array['tasklist_percentage'];
$tasklist_completed = $array['tasklist_completed'];
$tasklist_person = $array['tasklist_person'];

$tasklist_due = $array['tasklist_due'];

					if ($tasklist_due > 0) { $tasklist_due_date = "Due ".TimeFormat($tasklist_due); } else { $tasklist_due_date = ""; }
					$tasklist_person = $array['tasklist_person'];
					$proj_num = $array['proj_num'];
					$proj_name = $array['proj_name'];
					$proj_fee_track = $array['proj_fee_track'];
					
					
					if ($proj_id != $proj_id_repeat AND $counter > 1) { print "</table>"; unset($proj_id_repeat); }
					if ($proj_id != $proj_id_repeat) {
					   		print "<h2>";
							if ($proj_fee_track == "1") {
							print "<a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\" name=\"view_task_$proj_id\">$proj_num&nbsp;$proj_name</a>";
							} else {
							print $proj_num."&nbsp;".$proj_name;
							}
							print "</h2><table summary=\"Outstanding tasks for $proj_num\">";
					}
					
					$proj_id_repeat = $proj_id;
					
					$checktime = time() - 43200;
					
					
					$sql2 = "SELECT user_name_first, user_name_second, user_id FROM intranet_user_details WHERE user_id = '$tasklist_person' ";
					$result2 = mysql_query($sql2, $conn) or die(mysql_error());
					
					$array2 = mysql_fetch_array($result2);
					$user_name_first = $array2['user_name_first'];
					$user_name_second = $array2['user_name_second'];
					$user_id = $array2['user_id'];
					
					if ($tasklist_due < time() AND $tasklist_completed < 100 AND $tasklist_due > 0) { $alert = "style=\"background-color: #".$settings_alertcolor."\""; } else { $alert = ""; }
					
					print "<tr><td width=\"5%\" ".$alert.">";
					print $counter.".</td><td width=\"40%\" ".$alert."><a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=".$tasklist_id."\">".$tasklist_notes."</a>";
				
					print "<br /><span class=\"minitext\"><a href=\"index2.php?page=datebook_view_day&amp;time=$task_due\">".$tasklist_due_date."</a></span></td><td width=\"20%\" ".$alert.">";
					print $user_name_first." ".$user_name_second." ".$tasklist_percentage_desc;
					
			
					print "</td><td ".$alert.">";
					
					// Insert the percentage bar	
						
					if ($tasklist_percentage == 0 ) { $tasklist_percentage_graph = "tasklist_percent_000.gif"; }
					elseif ($tasklist_percentage == 10 ) { $tasklist_percentage_graph = "tasklist_percent_010.gif"; }
					elseif ($tasklist_percentage == 20 ) { $tasklist_percentage_graph = "tasklist_percent_020.gif"; }
					elseif ($tasklist_percentage == 30 ) { $tasklist_percentage_graph = "tasklist_percent_030.gif"; }
					elseif ($tasklist_percentage == 40 ) { $tasklist_percentage_graph = "tasklist_percent_040.gif"; }
					elseif ($tasklist_percentage == 50 ) { $tasklist_percentage_graph = "tasklist_percent_050.gif"; }
					elseif ($tasklist_percentage == 60 ) { $tasklist_percentage_graph = "tasklist_percent_060.gif"; }
					elseif ($tasklist_percentage == 70 ) { $tasklist_percentage_graph = "tasklist_percent_070.gif"; }
					elseif ($tasklist_percentage == 80 ) { $tasklist_percentage_graph = "tasklist_percent_080.gif"; }
					elseif ($tasklist_percentage == 90 ) { $tasklist_percentage_graph = "tasklist_percent_090.gif"; }
					elseif ($tasklist_percentage == 100 ) { $tasklist_percentage_graph = "tasklist_percent_100.gif"; }
					
					// Print the bar chart and make it clickable if it belongs to the current user
					
					if ($user_id == $_COOKIE[user]) {
					
							if ($_GET[subcat] != NULL) { $task_subcat = CleanUp($_GET[subcat]); } else { $task_subcat = "user"; }
					
							print "
							<img src=\"images/$tasklist_percentage_graph\" width=\"225\" height=\"17\" border=\"0\" alt=\"\" usemap=\"#task_$tasklist_id\" />
							<map name=\"task_$tasklist_id\">
							<area shape=\"rect\" alt=\"\" coords=\"201,1,219,9\" href=\"index2.php?page=tasklist_view&amp;action=tasklist_change_percent&amp;tasklist_id=$tasklist_id&amp;tasklist_percent=100&amp;subcat=$task_subcat#view_task_$tasklist_id\">
							<area shape=\"rect\" alt=\"\" coords=\"181,1,199,9\" href=\"index2.php?page=tasklist_view&amp;action=tasklist_change_percent&amp;tasklist_id=$tasklist_id&amp;tasklist_percent=90&amp;subcat=$task_subcat#view_task_$tasklist_id\">
							<area shape=\"rect\" alt=\"\" coords=\"161,1,179,9\" href=\"index2.php?page=tasklist_view&amp;action=tasklist_change_percent&amp;tasklist_id=$tasklist_id&amp;tasklist_percent=80&amp;subcat=$task_subcat#view_task_$tasklist_id\">
							<area shape=\"rect\" alt=\"\" coords=\"141,1,159,9\" href=\"index2.php?page=tasklist_view&amp;action=tasklist_change_percent&amp;tasklist_id=$tasklist_id&amp;tasklist_percent=70&amp;subcat=$task_subcat#view_task_$tasklist_id\">
							<area shape=\"rect\" alt=\"\" coords=\"121,1,139,9\" href=\"index2.php?page=tasklist_view&amp;action=tasklist_change_percent&amp;tasklist_id=$tasklist_id&amp;tasklist_percent=60&amp;subcat=$task_subcat#view_task_$tasklist_id\">
							<area shape=\"rect\" alt=\"\" coords=\"101,1,119,9\" href=\"index2.php?page=tasklist_view&amp;action=tasklist_change_percent&amp;tasklist_id=$tasklist_id&amp;tasklist_percent=50&amp;subcat=$task_subcat#view_task_$tasklist_id\">
							<area shape=\"rect\" alt=\"\" coords=\"81,1,99,9\" href=\"index2.php?page=tasklist_view&amp;action=tasklist_change_percent&amp;tasklist_id=$tasklist_id&amp;tasklist_percent=40&amp;subcat=$task_subcat#view_task_$tasklist_id\">
							<area shape=\"rect\" alt=\"\" coords=\"61,1,79,9\" href=\"index2.php?page=tasklist_view&amp;action=tasklist_change_percent&amp;tasklist_id=$tasklist_id&amp;tasklist_percent=30&amp;subcat=$task_subcat#view_task_$tasklist_id\">
							<area shape=\"rect\" alt=\"\" coords=\"41,1,59,9\" href=\"index2.php?page=tasklist_view&amp;action=tasklist_change_percent&amp;tasklist_id=$tasklist_id&amp;tasklist_percent=20&amp;subcat=$task_subcat#view_task_$tasklist_id\">
							<area shape=\"rect\" alt=\"\" coords=\"21,1,39,9\" href=\"index2.php?page=tasklist_view&amp;action=tasklist_change_percent&amp;tasklist_id=$tasklist_id&amp;tasklist_percent=10&amp;subcat=$task_subcat#view_task_$tasklist_id\">
							<area shape=\"rect\" alt=\"\" coords=\"1,1,19,9\" href=\"index2.php?page=tasklist_view&amp;action=tasklist_change_percent&amp;tasklist_id=$tasklist_id&amp;tasklist_percent=0&amp;subcat=$task_subcat#view_task_$tasklist_id\">
							</map>
							";
							
					} else {
					
							print "<br />
							<img src=\"images/$tasklist_percentage_graph\" width=\"225\" height=\"17\" border=\"0\" alt=\"\" />";
					
					}
					
					print "</td></tr>";
					
					if ($proj_id != $proj_id_repeat) { $counter = 1; unset($proj_id_repeat); } else { $counter++;  }
	}
	
print "</table>";

} else {

	print "<p>There are no active tasks on the system for this project.</p>";

}

?>