<?php

// Retrieve and process the values passed using the $_GET submission


if ($_GET[proj_id] != NULL) { $proj_id = intval($_GET[proj_id]); } else { unset ( $proj_id ); }
if ($_GET[status] != NULL) { $status = $_GET[status]; } else { $status = "add"; }
if ($_GET[blog_id] != NULL) { $blog_id = intval($_GET[blog_id]); } else { unset($blog_id); }
if ($_POST[contact_id]) { $contact_id = intval($_POST[contact_id]); } elseif ($_GET[contact_id]) { $contact_id = intval($_GET[contact_id]); } else { unset($contact_id); }

if ($proj_id == 0) { echo "<h1>Journal</h1>"; }

function BlogLock($blog_id,$user_id) {
	
	global $conn;
	
	$blog_id = intval($blog_id);
	$user_id = intval($user_id);
	
	if ($user_id > 0 && $user_id > 0) { $sql = "UPDATE intranet_projects_blog SET blog_lock = $user_id WHERE blog_id = $blog_id LIMIT 1"; $result = mysql_query($sql, $conn) or die(mysql_error()); }
	
}


function BlogCheckLock($blog_id,$user_id) {
	
	global $conn;
	intval($blog_id);
	
	$blog_id = intval($blog_id);
	$user_id = intval($user_id);	
	
	$sql = "SELECT blog_title, blog_lock, user_name_first, user_name_second FROM intranet_projects_blog, intranet_user_details WHERE user_id = blog_lock AND blog_id = $blog_id";
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	
	$user_name = $array['user_name_first'] . " " . $array['user_name_second'];
	
	if (intval($array['blog_lock']) > 0 && intval($array['blog_lock']) != $user_id) { return array($array['blog_text'],$user_name,$array['blog_title']); }
	
}


function BlogEdit($blog_id,$proj_id,$status) {
	
	global $conn;
	
	$blog_id = intval($blog_id);
	$proj_id = intval($proj_id);
	global $user_id_current;
	global $user_usertype_current;
	

					if(intval($blog_id) > 0 && intval($proj_id) > 0) {

						$sql = "SELECT * FROM intranet_projects_blog WHERE blog_id = " . $blog_id . " AND (blog_view = 0 OR blog_view = " . $user_id_current . " ) AND (blog_access IS NULL OR blog_access <= " . $user_usertype_current . ") LIMIT 1";
						$result = mysql_query($sql, $conn);
						$array = mysql_fetch_array($result);

						$blog_text = $array['blog_text'];
						$blog_title = $array['blog_title'];
						$blog_user = $array['blog_user'];
						$blog_date	= $array['blog_date'];
						$blog_proj = $array['blog_proj'];
						$blog_type = $array['blog_type'];
						$blog_contact = $array['blog_contact'];
						$blog_link = $array['blog_link'];
						$blog_task = $array['blog_task'];
						$blog_pinned = $array['blog_pinned'];
						$blog_access = $array['blog_access'];
						$blog_view = $array['blog_view'];
						$blog_sticky = $array['blog_sticky'];
						$blog_updated_date = $array['blog_updated_date'];
						$blog_updated_by = $array['blog_updated_by'];
						
						$blog_revision = $array['blog_revision'];
						
						$blog_drawing_ref = $array['blog_drawing_ref'];
						
						$contact_id = $blog_contact;
						
						$blog_date_minute = date("i",$blog_date);
						$blog_date_hour = date("G",$blog_date);
						$blog_date_day = date("j",$blog_date);
						$blog_date_month = date("n",$blog_date);
						$blog_date_year = date("Y",$blog_date);
						
						if ($blog_title == NULL) {

								$sql2 = "SELECT * FROM intranet_projects where proj_id = $proj_id";
								$result2 = mysql_query($sql2, $conn);
								$array2 = mysql_fetch_array($result2);
								$proj_num = $array2['proj_num'];
								$proj_name = $array2['proj_name'];
								echo "<h2>Add Blog Entry for: $proj_num&nbsp;$proj_name</h2>";
								
								ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
								
								ProjectSubMenu($proj_id,$user_usertype_current,"project_blog_edit",2);
						} else {
								echo "<h2>" . $blog_title . "</h2>";
								ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
								ProjectSubMenu($proj_id,$user_usertype_current,"project_blog_edit",2);
						}
						
						if ($blog_id > 0) {
							
							echo "<form method=\"post\" action=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id\">";
						}
						
					} elseif($status == "add") {

						$sql = "SELECT proj_num, proj_name FROM intranet_projects where proj_id = '$proj_id'";
						$result = mysql_query($sql, $conn);
						$array = mysql_fetch_array($result);

						$proj_num = $array['proj_num'];
						$proj_name = $array['proj_name'];
						
						$blog_text = $_POST[blog_text];
						$blog_title = $_POST[blog_title];
						$blog_user = $_POST[blog_user];
						$blog_date	= $_POST[blog_date];
						$blog_proj = $_POST[blog_proj];
						$blog_type = $_POST[blog_type];
						$blog_contact = $_POST[blog_contact];
						$blog_link = $_POST[blog_link];
						$blog_task = $_POST[blog_task];
						$blog_pinned = $_POST[blog_pinned];
						$blog_access = $_POST[blog_access];
						$blog_view = $_POST[blog_view];
						$blog_sticky = $_POST[blog_sticky];
						
						$blog_drawing_ref = $_POST[blog_drawing_ref];
						
						$blog_updated_date = $_POST[blog_updated_date];
						$blog_updated_by = $_POST[blog_updated_by];
						$blog_drawing_ref = $_POST[blog_drawing_ref];
						
						$blog_date_minute = date("i",time());
						$blog_date_hour = date("G",time());
						$blog_date_day = date("j",time());
						$blog_date_month = date("n",time());
						$blog_date_year = date("Y",time());
						
						echo "<h2>Add Journal Entry</h2>";
						ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
						echo "<form method=\"post\" action=\"index2.php?page=project_blog_view\">";

					}



					echo "
					<h3>Title</h3><p>
					<input type=\"text\" name=\"blog_title\" maxlength=\"100\" size=\"50\" value=\"$blog_title\" required=\"required\" /></p>";
					
					echo "<h3>Document Reference</h3><p><input type=\"text\" name=\"blog_drawing_ref\" maxlength=\"50\" size=\"50\" value=\"" . $blog_drawing_ref . "\" /></p>";

					if($status == "add" AND $proj_id != NULL) {

					echo "<input type=\"hidden\" value=\"blog_add\" name=\"action\" />";
					echo "<input type=\"hidden\" value=\"$proj_id\" name=\"blog_proj\" />";


					echo "<input type=\"hidden\" value=\"".$nowtime."\" name=\"blog_date\" />";

					echo "<input type=\"hidden\" value=\"". $user_id_current."\" name=\"blog_user\" />";

					} elseif($status == "edit" OR $proj_id == NULL ) {
						
						echo "<h3>Project</h3><p>";

							if ($blog_proj) {
								ProjectSelect($blog_proj,"blog_proj",1);
							} else {
								ProjectSelect($blog_proj,"blog_proj");
							}
							
						echo "</p>";	
							
					echo "<input type=\"hidden\" value=\"" . $user_id_current . "\" name=\"blog_user\" />";
					echo "<input type=\"hidden\" value=\"" . $blog_id . "\" name=\"blog_id\" />";
							


					}
					
					PersistentStorage("blog_text", "blog_content", $blog_text);

					TextAreaEdit("blog_text");

					echo "
					<h3>Entry</h3><p><textarea name=\"blog_text\" rows=\"12\" cols=\"48\">".$blog_text."</textarea></p>
					<p>Viewable only to me?&nbsp;<input type=\"checkbox\" name=\"blog_view\" value=\"" . intval($user_id_current) . "\"";

						if ($blog_view == $user_id_current) { echo " checked "; }

					echo " /></p><h3>Entry type</h3><p><select name=\"blog_type\">";

					if ($blog_type == NULL) { $blog_type = "filenote"; }

					echo "<option value=\"email\" ";	if ($blog_type == "email") { echo "selected"; }; echo ">Email Message</option>";
					echo "<option value=\"filenote\" ";	if ($blog_type == "filenote") { echo "selected"; }; echo ">File Note</option>";
					echo "<option value=\"meeting\" ";	if ($blog_type == "meeting") { echo "selected"; }; echo ">Meeting Note</option>";
					echo "<option value=\"review\" ";	if ($blog_type == "review") { echo "selected"; }; echo ">Project Review</option>";
					echo "<option value=\"phone\" ";	if ($blog_type == "phone") { echo "selected"; }; echo ">Telephone Call</option>";
					echo "<option value=\"rfi\" ";	if ($blog_type == "rfi") { echo "selected"; }; echo ">Request for Information (RFI)</option>";
					echo "<option value=\"stage\" ";	if ($blog_type == "stage") { echo "selected"; }; echo ">Stage Report</option>";
					echo "<option value=\"stage\" ";	if ($blog_type == "feeprop") { echo "selected"; }; echo ">Fee Proposal</option>";

					echo "</select>";

					echo "<h3>Access Level</h3><p>Accessible to this level and below:<br />";
						UserAccessType("blog_access",$user_usertype_current,$blog_access,$user_usertype_current);
					echo "</p>";

					echo "<h3>Contact</h3><p>";
						ContactsDropdownSelect($contact_id,"blog_contact");
					echo "</p>";

					// Link this entry with another one

							$sql3 = "SELECT blog_id, blog_title, blog_date FROM intranet_projects_blog WHERE blog_proj = '$proj_id' AND blog_id != '$blog_id' AND blog_user = '$_COOKIE[user]' order by blog_date DESC";
							$result3 = mysql_query($sql3, $conn);
							if (mysql_num_rows($result3) > 0) {
								echo "<h3>Link with other entry</h3><p><select name=\"blog_link\">";
								echo "<option value=\"\">-- None --</option>";
								while ($array3 = mysql_fetch_array($result3)) {
									$blog_id_link = $array3['blog_id'];
									$blog_date_link = $array3['blog_date'];
									$blog_title_link = $array3['blog_title'];
									echo "<option value=\"$blog_id_link\"";
									if ($blog_id_link == $blog_link) { echo " selected"; }
									echo ">$blog_title_link (".TimeFormat($blog_date_link).")</option>";
								}
								echo "</select></p>";
							}
													
					// Link this entry with a task

							$sql4 = "SELECT tasklist_id, tasklist_notes, tasklist_added FROM intranet_tasklist WHERE tasklist_project = '$proj_id' AND tasklist_person = '$_COOKIE[user]' order by tasklist_due DESC";
							$result4 = mysql_query($sql4, $conn);
							if (mysql_num_rows($result4) > 0) {
								echo "<h3>Link with task</h3><p><select name=\"blog_task\">";
								echo "<option value=\"\">-- None --</option>";
								while ($array4 = mysql_fetch_array($result4)) {
									$task_id = $array4['tasklist_id'];
									$task_added = $array4['tasklist_added'];
									$task_notes = "[Added ".TimeFormat($task_added)."] - ".substr($array4['tasklist_notes'], 0, 60)."...";
									echo "<option value=\"$task_id\"";
									if ($task_id == $blog_task) { echo " selected"; }
									echo ">$task_notes</option>";
								}
								echo "</select></p>";
							}

					// Hidden values 

					$nowtime = time();
					$hour = 7;
					$day = 1;
					$month = 1;
					$month_array = array("","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
					$year = 1;

					echo "<h3>Date</h3><p>";

							echo "Hour&nbsp;<select name=\"blog_date_hour\">"; $ampm = "am";
							while ($hour <= 23) {
								echo "<option value=\"$hour\"";
									if ($blog_date_hour == $hour) { echo " selected"; }
								echo ">$hour $ampm</option>";
								$hour++;
									if ($hour == 12) { $ampm = "pm"; }
							}
							echo "</select>&nbsp;";

					echo "Minutes&nbsp;<input type=\"text\" name=\"blog_date_minute\" value=\"$blog_date_minute\" maxlength=\"2\" size=\"3\" />";

							echo "&nbsp;Day&nbsp;<select name=\"blog_date_day\">";
							while ($day <= 31) {
								echo "<option value=\"$day\"";
									if ($blog_date_day == $day) { echo " selected"; }
								echo ">$day</option>";
								$day++;
							}
							echo "</select>&nbsp;";
							
							echo "&nbsp;Month&nbsp;<select name=\"blog_date_month\">";
							while ($month <= 12) {
								echo "<option value=\"$month\"";
									if ($blog_date_month == $month) { echo " selected"; }
								echo ">$month_array[$month]</option>";
								$month++;
							}
							echo "</select>&nbsp;";
							

					echo "Year&nbsp;
							<input type=\"text\" name=\"blog_date_year\" value=\"$blog_date_year\" maxlength=\"4\" size=\"5\"  />
							";
					if ($blog_pinned == 1 ) { $blog_pinned = "checked=\"checked\""; } else { unset($blog_pinned); }
					echo "<p><input type=\"checkbox\" value=\"1\" name=\"blog_pinned\" $blog_pinned />&nbsp;Pin to side menu?</p>";
					
					if ($blog_sticky == 1 ) { $blog_sticky = "checked=\"checked\""; } else { unset($blog_sticky); }
					echo "<p><input type=\"checkbox\" value=\"1\" name=\"blog_sticky\" $blog_sticky />&nbsp;Pin to top?</p>";
					
					$blog_revision_new = $blog_revision + 1;
					
					echo "<p><input type=\"checkbox\" value=\"yes\" name=\"blog_formal_revision\" />&nbsp;Formal revision?<span class=\"minitext\"><br />(increases the revision number from " . $blog_revision . " to " . $blog_revision_new . ")</span></p>";
					
							
							if ($blog_id > 0) {		
								echo "<input type=\"hidden\" value=\"blog_edit\" name=\"action\" />";
								echo "<p><input type=\"submit\" value=\"Update\" class=\"inputsubmit\" /></p>";
							} else {
								echo "<input type=\"hidden\" value=\"blog_add\" name=\"action\" />";
								echo "<p><input type=\"submit\" value=\"Add\" class=\"inputsubmit\" /></p>";
							}
							


					echo "</form>";

}

$check_whether_locked = BlogCheckLock($blog_id,$_COOKIE[user]);

if ($check_whether_locked == NULL) {	
	
	BlogLock($blog_id,$_COOKIE[user]);
	BlogEdit($blog_id,$proj_id,$status);

} else {

	echo "<h2>" . $check_whether_locked[2] . "</h2>";
	ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
	ProjectSubMenu($proj_id,$user_usertype_current,"blog_view",2);
	echo "<p>This entry is currently locked for editing by " . $check_whether_locked[1] . ".</p>";

}	