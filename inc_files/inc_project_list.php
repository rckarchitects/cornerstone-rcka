<?php

			function ProjActive($input,$input2) {
			if ($input != "1") { $output = "<del>$input2</del>"; } else { $output = $input2; }
			return $output;
			}
			
			function TimeRemaining($proj_id, $ts_fee_id, $ts_fee_target, $ts_fee_value) {
				GLOBAL $conn;
				GLOBAL $user_id;
				GLOBAL $user_usertype_current;
				if ($ts_fee_id != NULL) {
					
					// Establish cost of stage to date for this user
					$sql_user = "SELECT SUM(ts_cost_factored), user_user_rate FROM intranet_timesheet, intranet_user_details WHERE ts_user = user_id AND ts_user = $_COOKIE[user] AND ts_stage_fee = $ts_fee_id";
					$result_user = mysql_query($sql_user, $conn) or die(mysql_error());
					$array_user = mysql_fetch_array($result_user);
					$ts_cost_factored_user = $array_user['SUM(ts_cost_factored)'];
					$user_user_rate = $array_user['user_user_rate'];
					
					// Establish cost of stage to date for all users
					$sql_all = "SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE ts_stage_fee = $ts_fee_id";
					$result_all = mysql_query($sql_all, $conn) or die(mysql_error());
					$array_all = mysql_fetch_array($result_all);
					$ts_cost_factored_all = $array_all['SUM(ts_cost_factored)'];
					$cost_remaining_all = $ts_fee_value - $ts_cost_factored_all;
					
					// Establish hours to date on project if no fee stage
					if ($ts_fee_value == 0) {
					$sql_hours = "SELECT SUM(ts_hours) FROM intranet_timesheet WHERE ts_project = $proj_id AND ts_user = $_COOKIE[user]";
					$result_hours = mysql_query($sql_hours, $conn) or die(mysql_error());
					$array_hours = mysql_fetch_array($result_hours);
					$ts_hours_total = $array_hours['SUM(ts_hours)'];
					}
					
					$user_percent = $ts_cost_factored_user / $ts_cost_factored_all;
					$user_cost = $user_percent * $cost_remaining_all;
					$hours_remaining_user = round ( $user_cost / $user_user_rate );
					
					$cost_percentage = $ts_cost_factored_all / ( $ts_fee_value / $ts_fee_target);
					
					$cost_percentage_cost = $ts_cost_factored_all / $ts_fee_value;

					if ($hours_remaining_user > 0 AND $user_percent > 0.1 AND $cost_percentage > 0.2 AND $cost_percentage < 1) {
					$row_text = "<span class=\"minitext\"><i>You have <strong>" . round($hours_remaining_user) . "</strong> hour(s) remaining on this stage</i></span>";
					$row_color = "rgba(0,255,0,0.4)";
					} elseif ( $cost_percentage > 1 AND $cost_percentage_cost < 1 ) {
					$percent_over = round(100 * ($cost_percentage - 1) );
					$row_text = "<span class=\"minitext\"><i>This fee stage has overspent target profitability by <strong>" . $percent_over . "%</strong>.</i></span>";
					$row_color = "rgba(255,220,0, 0.4)";
					} elseif ( $cost_percentage_cost > 1) {
					$percent_over = round(100 * ($cost_percentage_cost - 1) );
					$row_text = "<span class=\"minitext\"><i>This fee stage has overspent by <strong>" . $percent_over . "%</strong> and is now losing money.</i></span>";
					$row_color = "rgba(255,0,0, 0.4)";
					} elseif ( $ts_fee_value == 0 && $ts_fee_id > 0) {
					$row_text = "<span class=\"minitext\"><i>There is no fee currently associated with this stage.<br />Your project hours to date: <strong>" . number_format ( $ts_hours_total,1) . "</strong></i></span>";
					$row_color = "rgba(200,200,200, 0.4)";
					} elseif ( $ts_fee_value == 0) {
					$row_text = "<span class=\"minitext\"><i>There is no fee stage currently associated with this project.<br />Your project hours to date: <strong>" . number_format ( $ts_hours_total,1) . "</strong></i></span>";
					$row_color = "rgba(200,200,200, 0.4)";
					} else {
					$row_color = "rgba(0,255,0,0.4)";
					}
					
					if ($user_usertype_current > 4 && $_GET[maintenance] == "yes") {
						$row_text = $row_text . "<br />user_user_rate = $user_user_rate";
						$row_text = $row_text . "<br />ts_cost_factored_user = $ts_cost_factored_user";
						$row_text = $row_text . "<br />ts_cost_factored_all = $ts_cost_factored_all";
						$row_text = $row_text . "<br />cost_remaining_all = $cost_remaining_all";
						$row_text = $row_text . "<br />ts_fee_value = $ts_fee_value";
						$row_text = $row_text . "<br />user_percent = $user_percent";
						$row_text = $row_text . "<br />user_cost = $user_cost";
						$row_text = $row_text . "<br />hours_remaining_user = $hours_remaining_user";
						$row_text = $row_text . "<br />proj_id = $proj_id";
						$row_text = $row_text . "<br />ts_fee_id = $ts_fee_id";
						$row_text = $row_text . "<br />ts_hours_total = $ts_hours_total";
					}
				}
				
			return array ($row_text, $row_color);
			
			}

if ($_GET[listorder] != NULL) { $listorder = $_GET[listorder];}

$active = CleanUp($_GET[active]);
if ($active == "0") { $project_active = " AND proj_active = 0";
} elseif ($active == "all") { $project_active = " AND proj_fee_track = 1 ";
} else { $project_active = " AND proj_active = 1 "; }

// Advance any projects if selected

if ($_GET[move] != NULL) {
$sql_stage = "SELECT riba_id FROM riba_stages WHERE riba_stage_include = 1 ORDER BY riba_order";
$result_stage = mysql_query($sql_stage, $conn) or die(mysql_error());
	while ($array_stage = mysql_fetch_array($result_stage)) {
	$riba_stage[] = $array_stage['riba_id'];
	}

	$key = array_search($_GET[stage_current], $riba_stage);
	$key_prev = $key - 1;
	$key_next = $key + 1;
	
	if ($_GET[move] == "prev") { $key_move = $riba_stage[$key_prev]; }
	elseif ($_GET[move] == "next") { $key_move = $riba_stage[$key_next]; }
	
$sql_shift = "UPDATE intranet_projects SET proj_riba = '$key_move' WHERE proj_id = '$_GET[proj_id]' LIMIT 1";
$result_shift = mysql_query($sql_shift, $conn) or die(mysql_error());
}

// This bit has been removed by the last update

// Work out the top and bottom of the list of RIBA stages

// $sql_riba = "SELECT riba_id FROM riba_stages WHERE riba_stage_include = 1 ORDER BY riba_order";
// $result_riba = mysql_query($sql_riba, $conn) or die(mysql_error());
// $rows_total = mysql_num_rows($result_riba);
// $rows_total = $rows_total - 1;
// while ($array_riba = mysql_fetch_array($result_riba)) {
// $riba_counter[] = $array_riba['riba_id'];
// }
// $riba_begin = $riba_counter[0];
// $riba_end = $riba_counter[$rows_total];


// Let's see if we can create an array which shows the recent projects worked on by the user

$timesheet_period = 16; // weeks
$timesheet_period = $timesheet_period * 604800;
$timesheet_period = time() - $timesheet_period;

$sql_timesheet_projects = "SELECT ts_project FROM intranet_timesheet WHERE ts_user = $_COOKIE[user] AND ts_datestamp > $timesheet_period GROUP BY ts_project";
$result_timesheet_projects = mysql_query($sql_timesheet_projects, $conn) or die(mysql_error());
$array_projects_recent = array();
while ($array_timesheet_projects = mysql_fetch_array($result_timesheet_projects)) {
array_push($array_projects_recent,$array_timesheet_projects['ts_project']);
}

// Get the list of projects from the database

$sql = "SELECT *, UNIX_TIMESTAMP(ts_fee_commence) FROM intranet_user_details, intranet_projects LEFT JOIN intranet_timesheet_fees ON `proj_riba` = `ts_fee_id` WHERE proj_rep_black = user_id $project_active order by proj_num";

$result = mysql_query($sql, $conn) or die(mysql_error());


		print "<p class=\"submenu_bar\">";
		
		if ($_GET[active] != NULL) {
			echo "<a href=\"index2.php\" class=\"submenu_bar\">Recent Projects</a>";
		} else {
			echo "<a href=\"index2.php?active=current&listorder=\" class=\"submenu_bar\">Active Projects</a>";
		}
				
		print "<a href=\"index2.php?active=all&amp;listorder=$listorder\" class=\"submenu_bar\">All Projects</a>";
		print "<a href=\"index2.php?active=0&amp;listorder=$listorder\" class=\"submenu_bar\">Inactive Projects</a>";
		
		if ($user_usertype_current > 3) {
			print "<a href=\"index2.php?page=project_edit&amp;status=add\" class=\"submenu_bar\">Add Project (+)</a>";
		}
		
		if ($user_usertype_current > 3) {
			// print "<a href=\"index2.php?page=project_analysis\" class=\"submenu_bar\">Project Analysis</a>";
			}
		print "<a href=\"index2.php?page=project_blog_edit&amp;status=add\" class=\"submenu_bar\">Add Journal Entry (+)</a>";
		print "</p>";
		
		
		
		if ($_GET[active] == "current") { 
			print "<h2>All Active Projects</h2>";
		} else {
			print "<h2>Recent Projects</h2>";
		}


		if (mysql_num_rows($result) > 0) {

		print "<table summary=\"Lists of projects\">";
		print "<tr><td colspan=\"3\">Project</td>";
			
		print "<td colspan=\"3\">Current Stage</td>";
		
		print "</td>";
		print "<td colspan=\"2\">Leader</td></tr>";

		while ($array = mysql_fetch_array($result)) {
		
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$proj_rep_black = $array['proj_rep_black'];
		$proj_client_contact_name = $array['proj_client_contact_name'];
		$proj_contact_namefirst = $array['proj_contact_namefirst'];
		$proj_contact_namesecond = $array['proj_contact_namesecond'];
		$proj_company_name = $array['proj_company_name'];
		$proj_fee_type = $array['proj_fee_type'];
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
		if ( $project_tasks_due > 0) { $alert_task = "style=\"font-weight: bold;\""; $add_task = "<br /><span class=\"minitext\"><a href=\"index2.php?page=tasklist_project&amp;proj_id=$proj_id&amp;show=user\">You have $project_tasks_due pending task(s) for this project</a></span>"; } else { $alert_task = NULL; $add_task = NULL; }
		
		if ($ts_fee_text != NULL) { $current_stage = $ts_fee_text; } elseif ($proj_fee_type == NULL) { $current_stage = "--"; } elseif ($riba_id == NULL) { $current_stage = "Prospect"; } else { $current_stage = $riba_letter." - ".$riba_desc; }
		
		if (array_search($proj_id,$array_projects_recent) > 0 OR $_GET[active] != NULL) {
			
								if ($_GET[active] == NULL) {
								$array_projectcheck = TimeRemaining($proj_id, $proj_riba, $ts_fee_target, $ts_fee_value);
								}
								if ($array_projectcheck[1]!= NULL) { $row_color_style = " style=\"background-color: " . $array_projectcheck[1] . "\""; } else { unset($row_color_style); } 
								if ($array_projectcheck[1]!= NULL) { $row_color = "background-color: " . $array_projectcheck[1] . ";"; } else { unset($row_color); } 
								if ($array_projectcheck[0]!= NULL) { $row_text = "<br />" . $array_projectcheck[0]; } else { unset($row_text); } 

											print "<tr><td $row_color_style><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">".ProjActive($proj_active,$proj_num)."</a>";
											
											

											print "</td><td style=\"width: 24px; text-align: center; $row_color\">";

											if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
											print "<a href=\"index2.php?page=project_edit&amp;proj_id=$proj_id&amp;status=edit\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a>&nbsp;";
											}

											print "</td><td $alert_task $row_color_style>".ProjActive($proj_active,$proj_name).$add_task."</td>";
											
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
												
											echo "</td><td $row_color_style><span class=\"minitext\">$current_stage $row_text</span></td>";
											
											echo "<td style=\"text-align: center; $row_color\">";
													echo "<a href=\"index2.php?page=project_checklist&amp;proj_id=$proj_id\"><img src=\"images/ic_list_black_18dp.png\" alt=\"Checklist\" /></a>";
											echo "</td>";
											
											echo "<td $row_color_style><a href=\"index2.php?page=user_view&amp;user_id=$user_id\">$user_initials</a></td>
														<td style=\"text-align: center; $row_color\"><a href=\"pdf_project_sheet.php?proj_id=$proj_id\"><img src=\"images/button_pdf.png\" alt=\"Project Detailed (PDF)\" /></a></td>";


											print "</tr>";
											
											
	
				}

		}

		print "</table>";

		} else {

		print "There are no live projects on the system";

		}
		
?>