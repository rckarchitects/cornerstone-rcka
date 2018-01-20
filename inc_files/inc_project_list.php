<?php


if ($_GET[listorder] != NULL) { $listorder = $_GET[listorder];}

$active = CleanUp($_GET[active]);
if ($active == "0") { $project_active = " AND proj_active = 0";
} elseif ($active == "all") { unset($project_active);
} else { $project_active = " AND proj_active = 1 "; }



// Create an array which shows the recent projects worked on by the user

$timesheet_period = 16; // weeks
$timesheet_period = $timesheet_period * 604800;
$timesheet_period = time() - $timesheet_period;

$sql_timesheet_projects = "SELECT ts_project FROM intranet_timesheet WHERE ts_user = $_COOKIE[user] AND ts_datestamp > $timesheet_period GROUP BY ts_project";
$result_timesheet_projects = mysql_query($sql_timesheet_projects, $conn) or die(mysql_error());

if (mysql_num_rows($result_timesheet_projects) == 0) {

	$sql_timesheet_projects = "SELECT ts_project FROM intranet_timesheet WHERE ts_datestamp > $timesheet_period GROUP BY ts_project";
	$result_timesheet_projects = mysql_query($sql_timesheet_projects, $conn) or die(mysql_error());	

}


$array_projects_recent = array();
while ($array_timesheet_projects = mysql_fetch_array($result_timesheet_projects)) {
array_push($array_projects_recent,$array_timesheet_projects['ts_project']);
}

// Get the list of projects from the database

	$sql = "SELECT *, UNIX_TIMESTAMP(ts_fee_commence) FROM intranet_user_details, intranet_projects LEFT JOIN intranet_timesheet_fees ON `proj_riba` = `ts_fee_id` WHERE proj_rep_black = user_id $project_active AND proj_fee_track = 1 order by proj_num";
	$result = mysql_query($sql, $conn) or die(mysql_error());



		echo "<div class=\"menu_bar\">";
		
		if ($_GET[active] != NULL) {
			echo "<a href=\"index2.php\" class=\"submenu_bar\">My Projects</a>";
		} else {
			echo "<a href=\"index2.php?active=current&listorder=\" class=\"submenu_bar\">All Active Projects</a>";
		}
				
		echo "<a href=\"index2.php?active=all&amp;listorder=$listorder\" class=\"submenu_bar\">All Projects</a>";
		echo "<a href=\"index2.php?active=0&amp;listorder=$listorder\" class=\"submenu_bar\">Inactive Projects</a>";
		
		if ($user_usertype_current > 3) {
			echo "<a href=\"index2.php?page=project_edit&amp;status=add\" class=\"submenu_bar\">Add Project (+)</a>";
		}
		
		if ($user_usertype_current > 3) {
			// echo "<a href=\"index2.php?page=project_analysis\" class=\"submenu_bar\">Project Analysis</a>";
			}
		echo "<a href=\"index2.php?page=project_blog_edit&amp;status=add\" class=\"submenu_bar\">Add Journal Entry (+)</a>";
		echo "</div>";
		
		
		
		if ($_GET[active] == "current") { 
			echo "<h2>All Active Projects</h2>";
		} else {
			echo "<h2>My Projects</h2>";
		}


		if (mysql_num_rows($result) > 0) {

		echo "<table summary=\"Lists of projects\">";
		
		if ($_GET[active] == "current") { 
			echo "<tr><td colspan=\"4\" style=\"width: 40%;\">Project</td>";
		} else {
			echo "<tr><td colspan=\"3\">Project</td>";
		}
			
		echo "<td colspan=\"3\">Current Stage</td>";
		
		echo "</td>";
		echo "<td colspan=\"2\">Leader</td></tr>";

		while ($array = mysql_fetch_array($result)) {
		
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$proj_rep_black = $array['proj_rep_black'];
		$proj_client_contact_name = $array['proj_client_contact_name'];
		$proj_contact_namefirst = $array['proj_contact_namefirst'];
		$proj_contact_namesecond = $array['proj_contact_namesecond'];
		$proj_company_name = $array['proj_company_name'];
		$proj_fee_type = $array['proj_fee_type'];
		$proj_desc = nl2br($array['proj_desc']);
		$riba_id = $array['riba_id'];
		$riba_desc = $array['riba_desc'];
		$riba_letter = $array['riba_letter'];
		$proj_id = $array['proj_id'];
		$user_initials = $array['user_initials'];
		$user_id = $array['user_id'];
		$riba_stage_include = $array['riba_stage_include'];
		$proj_active = $array['proj_active'];
		$ts_fee_id = $array['ts_fee_id'];
		$ts_fee_target = $array['ts_fee_target'];
		$ts_fee_value = $array['ts_fee_value'];
		$ts_fee_time_begin = $array['UNIX_TIMESTAMP(ts_fee_commence)'];
		$ts_fee_time_end = $array['ts_fee_time_end'];
		$proj_riba = $array['proj_riba'];
		
		// This has been added since the last update
		
		$ts_fee_text = $array['ts_fee_text'];
		
		//
		
		$sql_task = "SELECT tasklist_id FROM intranet_tasklist WHERE tasklist_project = $proj_id AND tasklist_person = $user_id_current AND tasklist_percentage < 100 ORDER BY tasklist_due DESC";
		$result_task = mysql_query($sql_task, $conn) or die(mysql_error());
		$project_tasks_due = mysql_num_rows($result_task);
		if ( $project_tasks_due > 0) { $add_task = "<br /><span class=\"minitext\"><a href=\"index2.php?page=tasklist_project&amp;proj_id=$proj_id&amp;show=user\">You have $project_tasks_due pending task(s) for this project</a></span>"; } else { $add_task = NULL; }
		
		if ($ts_fee_text != NULL) { $current_stage = $ts_fee_text; } elseif ($proj_fee_type == NULL) { $current_stage = "--"; } elseif ($riba_id == NULL) { $current_stage = "Prospect"; } else { $current_stage = $riba_letter." - ".$riba_desc; }
		
		if (array_search($proj_id,$array_projects_recent) > 0 OR $_GET[active] != NULL) {
			
								if ($_GET[active] == NULL) {
								$array_projectcheck = TimeRemaining($proj_id, $proj_riba, $ts_fee_target, $ts_fee_value);
								}
								if ($array_projectcheck[1]!= NULL) { $row_color_style = " style=\"background-color: " . $array_projectcheck[1] . "\""; } else { unset($row_color_style); } 
								if ($array_projectcheck[1]!= NULL) { $row_color = "background-color: " . $array_projectcheck[1] . ";"; } else { unset($row_color); } 
								if ($array_projectcheck[0]!= NULL) { $row_text = "<br />" . $array_projectcheck[0]; } else { unset($row_text); } 

											echo "<tr><td $row_color_style><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">".ProjActive($proj_active,$proj_num,$proj_id)."</a>";
											
											

											echo "</td><td style=\"width: 24px; text-align: center; $row_color\">";

											if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
											echo "<a href=\"index2.php?page=project_edit&amp;proj_id=$proj_id&amp;status=edit\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a>&nbsp;";
											}

											echo "</td><td $alert_task $row_color_style >".ProjActive($proj_active,$proj_name,$proj_id).$add_task . "</td>";
											
											if ($_GET[active] == "current") { echo "<td><span class=\"minitext\">" . $proj_desc . "</span></td>"; }
											
											// Project Stage
											
											echo "<td style=\"width: 18px; text-align: center; $row_color\">";
												
												$deadline = $ts_fee_time_begin + $ts_fee_time_end;
												$remaining = $deadline - time();
												$remaining = round ($remaining / 604800);
												
											if ($deadline > time() && $remaining != 0) {
												echo $remaining . "<br /><span class=\"minitext\">wks</span>";
											} elseif ($deadline < time() && $deadline > 0 && $remaining != 0) {
												echo $remaining . "<br /><span class=\"minitext\">wks</span>";									
											} elseif ($deadline > 0 && $remaining == 0) {
												echo "0<br /><span class=\"minitext\">wks</span>";	
											}
												
											echo "</td><td $row_color_style>$current_stage $row_text</td>";
											
											echo "<td style=\"text-align: center; $row_color\">";
													echo "<a href=\"index2.php?page=project_checklist&amp;proj_id=$proj_id\"><img src=\"images/button_list.png\" alt=\"Checklist\" /></a>";
											echo "</td>";
											
											echo "<td $row_color_style><a href=\"index2.php?page=user_view&amp;user_id=$user_id\">$user_initials</a></td>
														<td style=\"text-align: center; $row_color\"><a href=\"pdf_project_sheet.php?proj_id=$proj_id\"><img src=\"images/button_pdf.png\" alt=\"Project Detailed (PDF)\" /></a></td>";


											echo "</tr>";
											
											
	
				}

		}

		echo "</table>";

		} else {

		echo "There are no live projects on the system";

		}
		
?>
