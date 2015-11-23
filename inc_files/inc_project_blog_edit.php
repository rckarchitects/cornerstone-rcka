<?php

// Retrieve and process the values passed using the $_GET submission

if ($_GET[proj_id] != NULL) { $proj_id = $_GET[proj_id]; } else { $proj_id = NULL; }
if ($_GET[status] != NULL) { $status = $_GET[status]; } else { $status = "add"; }
if ($_GET[blog_id] != NULL) { $blog_id = $_GET[blog_id]; } else { $blog_id = NULL; }

if($status == "edit") {

	$sql = "SELECT * FROM intranet_projects_blog where blog_id = '$blog_id'";
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
	
	$blog_date_minute = date("i",$blog_date);
	$blog_date_hour = date("G",$blog_date);
	$blog_date_day = date("j",$blog_date);
	$blog_date_month = date("n",$blog_date);
	$blog_date_year = date("Y",$blog_date);
	
	print "<h1>Edit Existing Project Blog Entry</h1>";
	
	if ($blog_id > 0) {
		echo "<form method=\"post\" action=\"index2.php?page=project_blog_list\">";
	} else {
		echo "<form method=\"post\" action=\"index2.php?page=project_blog_add\">";
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
	
	$blog_date_minute = date("i",time());
	$blog_date_hour = date("G",time());
	$blog_date_day = date("j",time());
	$blog_date_month = date("n",time());
	$blog_date_year = date("Y",time());

	print "<h1>Add New Project Blog Entry</h1>";
	
	print "<form method=\"post\" action=\"index2.php?page=project_blog_list\">";

}

if ($blog_title != NULL) { print "<h2>$blog_title</h2>"; } elseif ($proj_id > 0) {

			$sql2 = "SELECT * FROM intranet_projects where proj_id = $_GET[proj_id]";
			$result2 = mysql_query($sql2, $conn);
			$array2 = mysql_fetch_array($result2);
			$proj_num = $array2['proj_num'];
			$proj_name = $array2['proj_name'];
			print "<h2>Add Blog Entry for: $proj_num&nbsp;$proj_name</h2>";
}

print "
<h3>Title</h3><p>
<input type=\"text\" name=\"blog_title\" maxlength=\"100\" size=\"50\" value=\"$blog_title\" /></p>";

if($status == "add" AND $proj_id != NULL) {

print "<input type=\"hidden\" value=\"blog_add\" name=\"action\" />";
print "<input type=\"hidden\" value=\"$proj_id\" name=\"blog_proj\" />";


print "<input type=\"hidden\" value=\"".$nowtime."\" name=\"blog_date\" />";

print "<input type=\"hidden\" value=\"".$user_id_current."\" name=\"blog_user\" />";
print "<p><input type=\"submit\" value=\"Submit\" class=\"inputsubmit\" /></p>";

} elseif($status == "edit" OR $proj_id == NULL ) {

		$sql2 = "SELECT proj_id, proj_num, proj_name FROM intranet_projects order by proj_num";
		$result2 = mysql_query($sql2, $conn);
		print "<h3>Project</h3><p><select name=\"blog_proj\">";
		while ($array2 = mysql_fetch_array($result2)) {
			$proj_id_select = $array2['proj_id'];
			$proj_num_select = $array2['proj_num'];
			$proj_name_select = $array2['proj_name'];
			print "<option value=\"$proj_id_select\"";
			if ($proj_id_select == $blog_proj) { print " selected"; }
			print ">$proj_num_select&nbsp;$proj_name_select</option>";
		}
		print "</select></p>";
		
print "<input type=\"hidden\" value=\"".$user_id_current."\" name=\"blog_user\" />";
print "<input type=\"hidden\" value=\"$blog_id\" name=\"blog_id\" />";
		


}

TextAreaEdit();

print "
<h3>Entry</h3><p><textarea name=\"blog_text\" rows=\"12\" cols=\"48\">".$blog_text."</textarea></p>
<p>Viewable only to me?&nbsp;<input type=\"checkbox\" name=\"blog_view\" value=\"1\"";

	if ($blog_view == "1") { print " checked "; }

print " /></p><h3>Entry type</h3><p><select name=\"blog_type\">";

if ($blog_type == NULL) { $blog_type = "filenote"; }

print "<option value=\"email\" ";	if ($blog_type == "email") { print "selected"; }; print ">Email Message</option>";
print "<option value=\"filenote\" ";	if ($blog_type == "filenote") { print "selected"; }; print ">File Note</option>";
print "<option value=\"meeting\" ";	if ($blog_type == "meeting") { print "selected"; }; print ">Meeting Note</option>";
print "<option value=\"review\" ";	if ($blog_type == "review") { print "selected"; }; print ">Project Review</option>";
print "<option value=\"phone\" ";	if ($blog_type == "phone") { print "selected"; }; print ">Telephone Call</option>";

print "</select>";


print "<h3>Contact</h3><p>";
	$data_contact_id = $blog_contact;
	$data_contact_var = "blog_contact";
	include("dropdowns/inc_data_dropdown_contacts.php");
print "</p>";

// Link this entry with another one

		$sql3 = "SELECT blog_id, blog_title, blog_date FROM intranet_projects_blog WHERE blog_proj = '$proj_id' AND blog_id != '$blog_id' AND blog_user = '$_COOKIE[user]' order by blog_date DESC";
		$result3 = mysql_query($sql3, $conn);
		if (mysql_num_rows($result3) > 0) {
			print "<h3>Link with other entry</h3><p><select name=\"blog_link\">";
			print "<option value=\"\">-- None --</option>";
			while ($array3 = mysql_fetch_array($result3)) {
				$blog_id_link = $array3['blog_id'];
				$blog_date_link = $array3['blog_date'];
				$blog_title_link = $array3['blog_title'];
				print "<option value=\"$blog_id_link\"";
				if ($blog_id_link == $blog_link) { print " selected"; }
				print ">$blog_title_link (".TimeFormat($blog_date_link).")</option>";
			}
			print "</select></p>";
		}
		
// Link this entry with a task

		$sql4 = "SELECT tasklist_id, tasklist_notes, tasklist_added FROM intranet_tasklist WHERE tasklist_project = '$proj_id' AND tasklist_person = '$_COOKIE[user]' order by tasklist_due DESC";
		$result4 = mysql_query($sql4, $conn);
		if (mysql_num_rows($result4) > 0) {
			print "<h3>Link with task</h3><p><select name=\"blog_task\">";
			print "<option value=\"\">-- None --</option>";
			while ($array4 = mysql_fetch_array($result4)) {
				$task_id = $array4['tasklist_id'];
				$task_added = $array4['tasklist_added'];
				$task_notes = "[Added ".TimeFormat($task_added)."] - ".substr($array4['tasklist_notes'], 0, 60)."...";
				print "<option value=\"$task_id\"";
				if ($task_id == $blog_task) { print " selected"; }
				print ">$task_notes</option>";
			}
			print "</select></p>";
		}

// Hidden values 

$nowtime = time();
$hour = 7;
$day = 1;
$month = 1;
$month_array = array("","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$year = 1;

print "<h3>Date</h3><p>";

		print "Hour&nbsp;<select name=\"blog_date_hour\">"; $ampm = "am";
		while ($hour <= 23) {
			print "<option value=\"$hour\"";
				if ($blog_date_hour == $hour) { print " selected"; }
			print ">$hour $ampm</option>";
			$hour++;
				if ($hour == 12) { $ampm = "pm"; }
		}
		print "</select>&nbsp;";

print "Minutes&nbsp;<input type=\"text\" name=\"blog_date_minute\" value=\"$blog_date_minute\" maxlength=\"2\" size=\"3\" />";

		print "&nbsp;Day&nbsp;<select name=\"blog_date_day\">";
		while ($day <= 31) {
			print "<option value=\"$day\"";
				if ($blog_date_day == $day) { print " selected"; }
			print ">$day</option>";
			$day++;
		}
		print "</select>&nbsp;";
		
		print "&nbsp;Month&nbsp;<select name=\"blog_date_month\">";
		while ($month <= 12) {
			print "<option value=\"$month\"";
				if ($blog_date_month == $month) { print " selected"; }
			print ">$month_array[$month]</option>";
			$month++;
		}
		print "</select>&nbsp;";
		

print "Year&nbsp;
		<input type=\"text\" name=\"blog_date_year\" value=\"$blog_date_year\" maxlength=\"4\" size=\"5\"  />
		";
		
		if ($blog_id > 0) {		
			print "<input type=\"hidden\" value=\"blog_edit\" name=\"action\" />";
			print "<p><input type=\"submit\" value=\"Update\" class=\"inputsubmit\" /></p>";
		} else {
			print "<input type=\"hidden\" value=\"blog_add\" name=\"action\" />";
			print "<p><input type=\"submit\" value=\"Add\" class=\"inputsubmit\" /></p>";
		}

print "</form>";



?>
