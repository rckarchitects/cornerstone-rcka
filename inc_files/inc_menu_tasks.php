<?php
print "<h1 class=\"heading_side\">Project Task List</h1>";
print "<p class=\"menu_bar\">";

if ($user_usertype_current > 1) { print "<a href=\"index2.php?page=tasklist_add\" class=\"menu_tab\">Add New</a>"; }

if ($user_usertype_current > 1)  { print "<a href=\"index2.php?page=tasklist_edit\" class=\"menu_tab\">Edit</a>"; }

print "<a href=\"index2.php?page=tasklist_list\" class=\"menu_tab\">List</a></p>";

print "<ul class=\"button_left\">";
print "<li><a href=\"index2.php?page=tasklist_view&amp;subcat=all\">View All Current Tasks</a></li>";
print "<li><a href=\"index2.php?page=tasklist_view&amp;subcat=user\">View My Current Tasks</a></li>";
print "</ul>";

$sql = "SELECT * FROM intranet_tasklist, intranet_projects WHERE tasklist_person = $user_id_current AND tasklist_project = proj_id AND tasklist_percentage < 100 order by tasklist_due DESC LIMIT 5";
$result = mysql_query($sql, $conn) or die(mysql_error());

if (mysql_num_rows($result) > 0) {

print "<h1 class=\"heading_side\">Current Tasks</h1>";
  
print "<ul class=\"button_left\">";

while ($array = mysql_fetch_array($result)) {
	$tasklist_id = $array['tasklist_id'];
	$job_id = $array['tasklist_project'];
	$tasklist_title = TrimLength($array['tasklist_notes'], 60);
	$tasklist_due = $array['tasklist_due'];

	$checktime = time() - 43200;

	if ($checktime > $tasklist_due AND $tasklist_due > 0 AND $tasklist_due != NULL) {
		$format = "class=\"alert\"";
	}


	$sql2 = "SELECT proj_num, proj_id, proj_fee_track FROM intranet_projects WHERE proj_id = $job_id";
	$result2 = mysql_query($sql2, $conn) or die(mysql_error());

	$array2 = mysql_fetch_array($result2);
	$proj_id = $array2['proj_id'];
	$proj_num = $array2['proj_num'];
	$proj_fee_track = $array2['proj_fee_track'];

	if ($proj_fee_track == "1") { print "<li><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">"; } else { print "<li>"; }
		print $proj_num.": ";
	if ($proj_fee_track == "1") { print "</a>"; }
	print "<a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=$tasklist_id\">".$tasklist_title."</a>";
	print "</li>";
}

print "</ul>";

} else {

print "<p>You have no active tasks on the system</p>";

}

?>
