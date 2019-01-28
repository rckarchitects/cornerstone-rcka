<?php


function TaskComplete($task_id) {
	
	global $conn;
	
	$task_id = intval($task_id);
	$sql = "UPDATE intranet_tasklist SET tasklist_percentage = 100, tasklist_completed = " . time() . " WHERE tasklist_id = " . $task_id  . " LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	$sql = "SELECT * FROM intranet_tasklist WHERE tasklist_id = " . $task_id . " LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$alert_entrytext = "<p>Task \"<a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=" . $task_id  . "\">" . addslashes($array['tasklist_notes']) . "</a>\", assigned to " . GetUserNameOnly($array['tasklist_person']) . ",  has been marked as complete.</p>";
	AlertBoxInsert($_COOKIE[user],"Task Completed",$alert_entrytext,$task_id,0,0,$array['tasklist_project']);
	
}

function TaskUncomplete($task_id) {
	
	global $conn;
	
	$task_id = intval($task_id);
	$sql = "UPDATE intranet_tasklist SET tasklist_percentage = 0, tasklist_completed = NULL WHERE tasklist_id = " . $task_id  . " LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	$sql = "SELECT * FROM intranet_tasklist WHERE tasklist_id = " . $task_id . " LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$alert_entrytext = "<p>Task \"<a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=" . $task_id  . "\">" . addslashes($array['tasklist_notes']) . "</a>\", assigned to " . GetUserNameOnly($array['tasklist_person']) . ", has been marked as incomplete.</p>";
	AlertBoxInsert($_COOKIE[user],"Task Edited",$alert_entrytext,$task_id,0,0,$array['tasklist_project']);
	
}

function ProjectTasks($proj_id) {

global $conn;

if ($proj_id == NULL AND intval($_GET[proj_id]) > 0) { $proj_id = intval($_GET[proj_id]); } else { $proj_id = intval($proj_id); }



// Determine the date a week ago

$date_lastweek = time() - 604800;

if ($_GET[show] == "user") { $user_tasks = "AND tasklist_person = ".$user_id_current; } else { $user_tasks = NULL; }

if ($_GET[view] == "complete") {
$filter = " AND tasklist_percentage = 100 ";
} else {
$filter = " AND tasklist_percentage < 100 ";	
}

$sql = "SELECT * FROM intranet_tasklist WHERE tasklist_project = '$proj_id' $user_tasks $filter order by tasklist_category, tasklist_due DESC";

$result = mysql_query($sql, $conn) or die(mysql_error());

if (mysql_num_rows($result) > 0) {

$counter = 1;

$current_category = NULL;

echo "<div class=\"page\"><table summary=\"Outstanding tasks for $proj_num\">";

while ($array = mysql_fetch_array($result)) {
  
$tasklist_id = $array['tasklist_id'];
$tasklist_notes = $array['tasklist_notes'];
$tasklist_percentage = $array['tasklist_percentage'];
$tasklist_completed = $array['tasklist_completed'];
$tasklist_person = $array['tasklist_person'];
$tasklist_due = $array['tasklist_due'];
$tasklist_project = $array['tasklist_project'];
$tasklist_category = ucwords($array['tasklist_category']);

					if ($tasklist_due > 0) { $tasklist_due_date = "Due ".TimeFormat($tasklist_due); } else { $tasklist_due_date = ""; }
					$tasklist_person = $array['tasklist_person'];
					$proj_num = $array['proj_num'];
					$proj_name = $array['proj_name'];
					$proj_fee_track = $array['proj_fee_track'];
					
					
					if ($proj_id != $proj_id_repeat AND $counter > 1) { echo "</table>"; unset($proj_id_repeat); }
					if ($proj_id != $proj_id_repeat && $proj_id == 0) {
							if ($proj_fee_track == "1") {
								echo "<h2><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\" name=\"view_task_$proj_id\">" . ProjectData($proj_id, "name") . "</a></h2>";

							} else {
								echo "<h2>" . ProjectData($proj_id, "name") . "</h2>";
							}
							
					}
					
					
				
					if ($tasklist_category != $current_category) {
						echo "<tr><th colspan=\"5\">" . $tasklist_category . "</th></tr>";
						$current_category = $tasklist_category;
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
					
					if ($_GET[view] == "complete") { $strikethrough = "style=\"text-decoration: line-through;\""; } else { unset($strikethrough); }
					
					echo "<tr><td width=\"5%\" ".$alert.">";
					echo $counter.".</td><td width=\"40%\" ".$alert."><a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=".$tasklist_id."\"><span class=\"tasklist_id_" . $tasklist_id . "\" $strikethrough>".$tasklist_notes."</span></a>";
					
										
					echo "&nbsp;<a href=\"index2.php?page=tasklist_edit&amp;tasklist_id=" . $tasklist_id . "&proj_id=" . $tasklist_project . "\"><img src=\"images/button_edit.png\" alt=\"Edit Task\" /></a>";
					
					echo "</td>";
				
					echo "<td><a href=\"index2.php?page=datebook_view_day&amp;timestamp=" . $tasklist_due . "\"><span class=\"tasklist_id_" . $tasklist_id . "\" $strikethrough>".$tasklist_due_date."</span></a></td>";
					
					echo "<td width=\"20%\" ".$alert."><span class=\"tasklist_id_" . $tasklist_id . "\" $strikethrough>";
					echo $user_name_first." ".$user_name_second." ".$tasklist_percentage_desc;
				
			
					echo "</span></td><td ".$alert.">";
					
					
					// echo the bar chart and make it clickable if it belongs to the current user
					
					if ($user_id == $_COOKIE[user]) {
					
							if ($_GET[subcat] != NULL) { $task_subcat = CleanUp($_GET[subcat]); } else { $task_subcat = "user"; }
					
							echo "<input type=\"checkbox\" name=\"tasklist_percentage\" id=\"tasklist_id_" . $tasklist_id . "\" value=\"100\"";
							
								if (intval($tasklist_percentage) == 100) { echo "checked=\"checked\""; }

							if ($_GET[view] == "complete") { echo " onclick=\"UnStrikeThough('tasklist_id_" . $tasklist_id . "')\" />"; } else { echo " onclick=\"StrikeThough('tasklist_id_" . $tasklist_id . "')\" />"; }
							
					} else {
					
							echo "<input type=\"checkbox\" disabled=\"disabled\"";

								if (intval($tasklist_percentage) == 100) { echo "checked=\"checked\""; }
							
							echo " />";
					
					}
					
					echo "</td></tr>\n";
					
					if ($proj_id != $proj_id_repeat) { $counter = 1; unset($proj_id_repeat); } else { $counter++;  }
}
	
echo "</table></div>";

} else {

	echo "<p>There are no active tasks on the system for this project.</p>";

}

}

function TasklistSummary($user_id) {
	
	global $conn;
	$user_id = intval($user_id);

// Determine the date a week ago

$date_lastweek = time() - 604800;

if ($_GET[order] == "time") { $order = " tasklist_due DESC"; } else { $order = " proj_num, tasklist_due"; }

if ($_GET[view] == "complete") {
	
	$sql = "SELECT * FROM intranet_tasklist, intranet_projects WHERE tasklist_project = proj_id AND tasklist_person = " . $user_id . " AND tasklist_percentage = 100 order by " . $order . "";

} else {
	
	$sql = "SELECT * FROM intranet_tasklist, intranet_projects WHERE tasklist_project = proj_id AND tasklist_person = " . $user_id . " AND tasklist_percentage < 100 order by " . $order . "";

}

$result = mysql_query($sql, $conn) or die(mysql_error());

if (mysql_num_rows($result) > 0) {
	
echo "<div class=\"page\">";

$counter = 1;

if ($_GET[order] == "time") { echo "<table summary=\"Outstandings tasks\">"; }

while ($array = mysql_fetch_array($result)) {
  
$tasklist_id = $array['tasklist_id'];
$proj_id = $array['tasklist_project'];
$tasklist_notes = $array['tasklist_notes'];
$tasklist_percentage = $array['tasklist_percentage'];
$tasklist_completed = $array['tasklist_completed'];

$tasklist_due = $array['tasklist_due'];
$tasklist_soon = $array['tasklist_due'] - 604800;

					if ($tasklist_due > 0) { $tasklist_due_date = "Due <a href=\"index2.php?page=datebook_view_day&amp;timestamp=$tasklist_due\">".TimeFormat($tasklist_due)."</a>"; } else { $tasklist_due_date = ""; }
					$tasklist_person = $array['tasklist_person'];
					$proj_num = $array['proj_num'];
					$proj_name = $array['proj_name'];
					$proj_fee_track = $array['proj_fee_track'];
					
					
					
					if ($proj_id != $proj_id_repeat && $counter > 1 && $_GET[order] != "time") { echo "</table>"; unset($proj_id_repeat); }
					if ($proj_id != $proj_id_repeat) {
						if ($_GET[order] != "time") {
							if ($proj_fee_track == "1") {
								echo "<h3><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\" name=\"view_task_$proj_id\">$proj_num&nbsp;$proj_name</a></h3>";
							} else {
								echo "<h3>" . $proj_num."&nbsp;".$proj_name . "</h3>";
							}
						}
						
							if ($_GET[order] != "time") {
								echo "<table summary=\"Outstandings tasks for $proj_num\">";
							}
					}
					
					$proj_id_repeat = $proj_id;
					
					$checktime = time() - 43200;
					
					
					$sql2 = "SELECT user_name_first, user_name_second, user_id FROM intranet_user_details WHERE user_id = '$tasklist_person' ";
					$result2 = mysql_query($sql2, $conn) or die(mysql_error());
					
					$array2 = mysql_fetch_array($result2);
					$user_name_first = $array2['user_name_first'];
					$user_name_second = $array2['user_name_second'];
					$user_id = $array2['user_id'];
					
					if ($tasklist_due < time() && $tasklist_completed < 100 && $tasklist_due > 0 && $_GET[view] != "complete") {
						$alert = "style=\"background-color: rgba(248,163,180,1)\" ";
					} elseif ($tasklist_soon < time() AND $tasklist_completed < 100 AND $tasklist_due > 0 && $_GET[view] != "complete") {
						$alert = " style=\"background-color: rgba(255,255,120,0.5)\" ";
					} else {
						$alert = "";
					}
					
					echo "<tr><td width=\"5%\" ".$alert."><span class=\"tasklist_id_" . $tasklist_id . "\">";
					echo $counter.".</span></td><td ".$alert."><span class=\"tasklist_id_" . $tasklist_id . "\">";
					
					if ($_GET[order] == "time") { echo "<strong>" . $proj_num . "&nbsp;" . $proj_name . "</strong><br />"; }
					
					echo "<a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=".$tasklist_id."\">" . $tasklist_notes . "</a>";
				
					echo "</span></td><td width=\"20%\" ".$alert."><span class=\"tasklist_id_" . $tasklist_id . "\">";
					echo $tasklist_due_date;
					
					
					echo "</span></td><td ".$alert." width=\"20%\"><span class=\"tasklist_id_" . $tasklist_id . "\">";
					
									// If completed, put the completed date down
					
					if ($tasklist_percentage == 100 AND intval($tasklist_completed) > 0) {
						echo "Completed ". TimeFormat($tasklist_completed);
					} else {
						echo "-";
					}
					
					
					echo "</span></td><td $alert>";
					
					if ($_GET[view] == "complete") {
					
						echo "<input type=\"checkbox\" disabled=\"disabled\" checked=\"checked\" />";
					
					} else {
						
						echo "<input type=\"checkbox\" id=\"tasklist_id_" . $tasklist_id . "\" onclick=\"StrikeThough('tasklist_id_" . $tasklist_id . "')\" />";
						
					}
					
					echo "</td></tr>";
					
					if ($proj_id != $proj_id_repeat) { $counter = 1; unset($proj_id_repeat); } else { $counter++;  }
	}
	
echo "</table>";

echo "</div>";

} else {

echo "<h2>Tasks</h2>";

	echo "<p>You have no active tasks on the system.</p>";

}

}

function TaskListEditForm($tasklist_id) {
	
	global $conn;
	$tasklist_id = intval($tasklist_id);
	$proj_id = intval($_GET[proj_id]);

				if ($tasklist_id > 0) {

					$sql = "SELECT * FROM intranet_tasklist WHERE tasklist_id = $_GET[tasklist_id] LIMIT 1";
					$result = mysql_query($sql, $conn) or die(mysql_error());
					
					$array = mysql_fetch_array($result);  

					$tasklist_id = $array['tasklist_id'];
					$tasklist_notes = $array['tasklist_notes'];
					$tasklist_fee = $array['tasklist_fee'];
					$tasklist_percentage = $array['tasklist_percentage'];
					$tasklist_completed = $array['tasklist_completed'];
					$tasklist_comment = $array['tasklist_comment'];
					$tasklist_person = $array['tasklist_person'];
					$tasklist_added = $array['tasklist_added'];
					$tasklist_due = $array['tasklist_due'];
					$tasklist_project = $array['tasklist_project'];
					$tasklist_access = $array['tasklist_access'];
					$tasklist_category = $array['tasklist_category'];
					$tasklist_feestage = $array['tasklist_feestage'];
					
					if ($tasklist_project > 0) { $target = "index2.php?page=tasklist_project&amp;proj_id=" . $tasklist_project; } else { $target =  "index2.php?page=tasklist_view&amp;status=add"; }
					
					echo "<form action=\"" . $target . "\" method=\"post\">";
					echo "<input type=\"hidden\" name=\"tasklist_id\" value=\"$tasklist_id\" />";
					echo "<h2>Edit Existing Task</h2>";

				} elseif ($proj_id != NULL) {

					echo "<form action=\"index2.php?page=tasklist_project&amp;proj_id=$_GET[proj_id]\" method=\"post\">";
					echo "<h2>Add New Task</h2>";

				} else {

					echo "<form action=\"index2.php?page=tasklist_view&amp;status=add\" method=\"post\">";
					echo "<h2>Add New Task</h2>";

				}
				
				ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
				ProjectSubMenu($proj_id,$user_usertype_current,"project_tasks",2);

				echo "<div>";

				if ($tasklist_project > 0) { $tasklist_select = $tasklist_project; } elseif ($proj_id != NULL) { $tasklist_select = $_GET[proj_id]; }

				if ($proj_id == 0) {
					
					echo "<h3>Select Project</h3>";

					ProjectSelect($proj_id,"tasklist_project",$disabled);
					
				} else {
					
					echo "<input type=\"hidden\" name=\"tasklist_project\" value=\"" . $proj_id . "\" />";
					
				}

				echo "</div>";

				// Now the description

				echo "<div><h3>Details</h3><textarea name=\"tasklist_notes\" class=\"inputbox\" cols=\"48\" rows=\"4\">$tasklist_notes</textarea></div>";
				
				//Task completed?
				
				echo "<div><input type=\"checkbox\" value=\"" . intval($tasklist_percentage) . "\" name=\"tasklist_percentage\"";

				if (intval($tasklist_percentage) == 100) { echo " checked=\"checked\" "; }

				echo "/>&nbsp;Complete</div>";

				// Category

				DataList("tasklist_category","intranet_tasklist");

				function FeeStageDropDown($proj_id, $current_fee_stage,$proj_id) {
					
					global $conn;
					
					// First establish CURRENT fee scale if this has been defined
					
					$sql = "SELECT proj_riba FROM intranet_projects WHERE proj_id = " . intval($proj_id) . " LIMIT 1";
					$result = mysql_query($sql, $conn) or die(mysql_error());
					$array = mysql_fetch_array($result);
					

					if ($current_fee_stage > 0) { $active_fee_stage = $current_fee_stage; }
					elseif (intval($array['proj_riba']) > 0) { $active_fee_stage = intval($array['proj_riba']); }
					
					$sql = "SELECT * FROM intranet_timesheet_fees LEFT JOIN intranet_timesheet_group ON group_id = ts_fee_group WHERE ts_fee_project = " . intval($proj_id) . " ORDER BY ts_fee_commence";
					$result = mysql_query($sql, $conn) or die(mysql_error());
					
					if (mysql_num_rows($result) > 0) {
						
						echo "<div><h3>Fee Stage</h3><select name=\"tasklist_feestage\">";
						
						while ($array = mysql_fetch_array($result)) {
							
							if ($active_fee_stage == $array['ts_fee_id']) { $selected = "selected=\"selected\""; }
							else { unset($selected); }
							
							echo "<option value=\"" . $array['ts_fee_id'] . "\" $selected >" . $array['group_code'] . ". " . $array['ts_fee_text'] . "</option>";
							
						}
						
						echo "</select></div>";
						
					}
					
				}

				if ($tasklist_project > 0) {

				FeeStageDropDown($tasklist_project, $tasklist_feestage,$proj_id);

				}

				echo "<div><h3>Category</h3><input type=\"text\" name=\"tasklist_category\" list=\"tasklist_category\" value=\"" . $tasklist_category . "\" /></div>";

				$sql = "SELECT * FROM intranet_user_details WHERE user_active = '1' order by user_name_second";
				$result = mysql_query($sql, $conn) or die(mysql_error());

				

				echo "<div><h3>Person Responsible</h3>";

				echo "<select name=\"tasklist_person\" class=\"inputbox\">";

				while ($array = mysql_fetch_array($result)) {
				$user_name_first = $array['user_name_first'];
				$user_name_second = $array['user_name_second'];
				$user_id = $array['user_id'];

				if ($tasklist_person > 0) { $tasklist_user_select = $tasklist_person; } else { $tasklist_user_select = $_COOKIE[user]; }

				echo "<option value=\"$user_id\"";
				if ($tasklist_user_select == $user_id) { echo " selected"; }
				echo ">".$user_name_first."&nbsp;".$user_name_second."</option>";
				}

				echo "</select></div>";


				echo "<div><h3>Due Date</h3>";

				$nowtime = time();
				$nowtime_week_pre = $nowtime;
				$nowday = date("d", $nowtime);

				$todayday = date("d", $_POST[ts_date]);

				if (intval($tasklist_due)) {
					$date_due = CreateDateFromTimestamp($tasklist_due);
				} else {
					$date_due = CreateDateFromTimestamp(time() + 1209600);
				}

				echo "<input type=\"date\" name=\"tasklist_due\" value=\"" . $date_due . "\" />";
				echo "<p class=\"minitext\">Default task deadline is 2 weeks from today.</p>";

				echo "</div>";

				echo "<div><h3>Accessible To</h3>";
				UserAccessType("tasklist_access",$user_usertype,$tasklist_access,$maxlevel);
				echo "</div>";

				echo "<div><h3>Comment</h3><textarea name=\"tasklist_comment\" cols=\"48\" rows=\"6\">$tasklist_comment</textarea></div>";


				echo "<div><input type=\"hidden\" name=\"action\" value=\"tasklist_edit\" />";
				echo "<input type=\"submit\" value=\"Submit\" /></div>";


				echo "</form>";

}
