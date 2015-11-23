<?php

print "<h1>Letter</h1>";

if ($_GET[letter_id] != NULL) {

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
	
	print "<form action=\"index2.php?page=tasklist_view&amp;status=add\" method=\"post\">";
	print "<input type=\"hidden\" name=\"tasklist_id\" value=\"$tasklist_id\" />";
	print "<h2>Edit Existing Task</h2>";

} elseif ($_GET[proj_id] != NULL) {

	print "<form action=\"index2.php?page=tasklist_project&amp;proj_id=$_GET[proj_id]\" method=\"post\">";
	print "<h2>Add New Task</h2>";

} else {

	print "<form action=\"index2.php?page=letter_list\" method=\"post\">";
	print "<h2>Compose New Letter</h2>";

}

print "<p>Select Project<br />";

if ($letter_proj > 0) { $proj_select = $letter_proj; } elseif ($_GET[proj_id] != NULL) { $proj_select = $_GET[proj_id]; }

$sql = "SELECT * FROM intranet_projects order by proj_num";
$result = mysql_query($sql, $conn) or die(mysql_error());
print "<select name=\"tasklist_project\">";
while ($array = mysql_fetch_array($result)) {
$proj_num = $array['proj_num'];
$proj_name = $array['proj_name'];
$proj_id = $array['proj_id'];
print "<option value=\"$proj_id\" class=\"inputbox\"";
if ($tasklist_select == $proj_id) { print " selected"; }
print ">$proj_num&nbsp;$proj_name";
}
print "</select></p>";

// Now the description

print "<p>Details:<br /><textarea name=\"tasklist_notes\" class=\"inputbox\" cols=\"48\" rows=\"4\">$tasklist_notes</textarea></p>";


$sql = "SELECT * FROM intranet_user_details WHERE user_active = '1' order by user_name_second";
$result = mysql_query($sql, $conn) or die(mysql_error());

$counter = 0;
if ($tasklist_percentage == NULL) { $tasklist_percentage = 0; }
print "<p>Percentage Complete<br />";
while ($counter <= 100) {
print "<input type=\"radio\" name=\"tasklist_percentage\" value=\"$counter\"";
	if ($counter == $tasklist_percentage) { print " checked "; }
print "/>&nbsp;$counter%";
$counter = $counter + 10;
}
print "</p>";

print "<p>Person Responsible:<br />";

print "<select name=\"tasklist_person\" class=\"inputbox\">";

while ($array = mysql_fetch_array($result)) {
$user_name_first = $array['user_name_first'];
$user_name_second = $array['user_name_second'];
$user_id = $array['user_id'];

if ($tasklist_person > 0) { $tasklist_user_select = $tasklist_person; } else { $tasklist_user_select = $_COOKIE[user]; }

print "<option value=\"$user_id\"";
if ($tasklist_user_select == $user_id) { print " selected"; }
print ">".$user_name_first."&nbsp;".$user_name_second."</option>";
}

print "</select></p>";


print "<p>Due Date:<br />";

$nowtime = time();
$nowtime_week_pre = $nowtime;
$nowday = date("d", $nowtime);

$todayday = date("d", $_POST[ts_date]);

print "<select name=\"tasklist_due\">";

print "<option value=\"0\">-- None --</option>";

if ($tasklist_due < $nowtime_week_pre AND $tasklist_due > 0) {
	print "<option value=\"$tasklist_due\" selected>";
	print date("l j F Y", $tasklist_due);
	print "</option>";
}

for ($datecount=1; $datecount<=40; $datecount++) {
$listday = $nowtime_week_pre+86400*$datecount;
$thenday = date("d", $listday);

if (date("D", $listday) == "Sat" or date("D", $listday) == "Sun") {

} else {
print "<option value=\"$listday\"";
if (date("z",$tasklist_due) == date("z",$listday)) { print " selected "; }
print ">";
print date("l j F Y", $listday);
print "</option>";
}

}

if ($tasklist_due > $listday) {
	print "<option value=\"$tasklist_due\" selected>";
	print date("l j F Y", $tasklist_due);
	print "</option>";
}

print "</select>";

print "<p>Comment:<br /><textarea name=\"tasklist_comment\" cols=\"48\" rows=\"6\">$tasklist_comment</textarea></p>";


print "<input type=\"hidden\" name=\"action\" value=\"tasklist_edit\" />";
print "<p><input type=\"submit\" value=\"Submit\" /></p>";


print "</form>";

?>
