<?php

if ($_GET[tasklist_id] != NULL) {

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
	
	echo "<form action=\"index2.php?page=tasklist_view&amp;status=add\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"tasklist_id\" value=\"$tasklist_id\" />";
	echo "<h2>Edit Existing Task</h2>";

} elseif ($_GET[proj_id] != NULL) {

	echo "<form action=\"index2.php?page=tasklist_project&amp;proj_id=$_GET[proj_id]\" method=\"post\">";
	echo "<h2>Add New Task</h2>";

} else {

	echo "<form action=\"index2.php?page=tasklist_view&amp;status=add\" method=\"post\">";
	echo "<h2>Add New Task</h2>";

}

echo "<p>Select Project<br />";

if ($tasklist_project > 0) { $tasklist_select = $tasklist_project; } elseif ($_GET[proj_id] != NULL) { $tasklist_select = $_GET[proj_id]; }

ProjectSelect($tasklist_select,"tasklist_project");

echo "</p>";

// Now the description

echo "<p>Details:<br /><textarea name=\"tasklist_notes\" class=\"inputbox\" cols=\"48\" rows=\"4\">$tasklist_notes</textarea></p>";


$sql = "SELECT * FROM intranet_user_details WHERE user_active = '1' order by user_name_second";
$result = mysql_query($sql, $conn) or die(mysql_error());

$counter = 0;
if ($tasklist_percentage == NULL) { $tasklist_percentage = 0; }
echo "<p>Percentage Complete<br />";
while ($counter <= 100) {
echo "<input type=\"radio\" name=\"tasklist_percentage\" value=\"$counter\"";
	if ($counter == $tasklist_percentage) { echo " checked "; }
echo "/>&nbsp;$counter%";
$counter = $counter + 10;
}
echo "</p>";

echo "<p>Person Responsible:<br />";

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

echo "</select></p>";


echo "<p>Due Date:<br />";

$nowtime = time();
$nowtime_week_pre = $nowtime;
$nowday = date("d", $nowtime);

$todayday = date("d", $_POST[ts_date]);

echo "<select name=\"tasklist_due\">";

echo "<option value=\"0\">-- None --</option>";

if ($tasklist_due > 0) {
	echo "<option value=\"$tasklist_due\" selected>";
	echo date("l j F Y", $tasklist_due);
	echo "</option>";
}

for ($datecount=1; $datecount<=40; $datecount++) {
$listday = $nowtime_week_pre+86400*$datecount;
$thenday = date("d", $listday);

if (date("D", $listday) == "Sat" or date("D", $listday) == "Sun") {

} else {
echo "<option value=\"$listday\"";
if (date("z",$tasklist_due) == date("z",$listday)) { echo " selected "; }
echo ">";
echo date("l j F Y", $listday);
echo "</option>";
}

}

if ($tasklist_due > $listday) {
	echo "<option value=\"$tasklist_due\" selected>";
	echo date("l j F Y", $tasklist_due);
	echo "</option>";
}



echo "</select>";

echo "<p>Accessible To:<br />";
UserAccessType("tasklist_access",$user_usertype,$tasklist_access,$maxlevel);
echo "</p>";

echo "<p>Comment:<br /><textarea name=\"tasklist_comment\" cols=\"48\" rows=\"6\">$tasklist_comment</textarea></p>";


echo "<input type=\"hidden\" name=\"action\" value=\"tasklist_edit\" />";
echo "<p><input type=\"submit\" value=\"Submit\" /></p>";


echo "</form>";
