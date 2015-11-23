<?php

$maxnum = 5;

// First print the blog entries if there are any

$sql = "SELECT * FROM intranet_projects_blog where blog_proj = $_GET[proj_id] ORDER BY blog_date DESC LIMIT $maxnum";
$sql2 = "SELECT blog_id FROM intranet_projects_blog where blog_proj = $_GET[proj_id] ORDER BY blog_date DESC";
$result = mysql_query($sql, $conn) or die(mysql_error());
$result2 = mysql_query($sql2, $conn) or die(mysql_error());
$result_num = mysql_num_rows($result2);

if (mysql_num_rows($result) > 0) {

	print "<h1 class=\"heading_side\">Recent Journal Entries</h1>";
	
	print "<ul class=\"button_left\">";	

	while ($array = mysql_fetch_array($result)) {

	$blog_id = $array['blog_id'];
	$blog_date = $array['blog_date'];
	$blog_user = $array['blog_user'];
	$blog_text = $array['blog_text'];
	$blog_title = $array['blog_title'];
	$blog_view = $array['blog_view'];
	$blog_type = $array['blog_type'];
	$blog_proj = $array['blog_proj'];
	
		
			if ($blog_view != 1 OR $blog_user == $user_id_current) {
				print "<li>". DayLink($blog_date) ."<br /><a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id&amp;proj_id=$blog_proj\">".$blog_title."</a>";
			}
			if ($blog_user == $user_id_current) {
				print "&nbsp;<a href=\"index2.php?page=project_blog_edit&amp;status=edit&amp;blog_id=$blog_id&amp;proj_id=$blog_proj\"><img src=\"images/button_edit.png\" alt=\"Edit Blog Entry\" /></a>"; }
				print "</li>";
			}
	
		if ($result_num < $maxnum) { $print_total = $result_num; } else { $print_total = $maxnum; }
			
		// if ($maxnum > $result_num) { $maxnum = $result_num; }
		print "<li>Showing records 1 to $print_total of ".$result_num;
		if ($result_num > $maxnum) { print "<br /><a href=\"index2.php?page=project_blog_list&amp;proj_id=$blog_proj\">[more]</a>"; }
		print "</li>";
		print "</ul>";
	}

// Print the planning information if available
	$sql = "SELECT proj_planning_ref, proj_buildingcontrol_ref, proj_id FROM intranet_projects where proj_id = '$_GET[proj_id]' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$proj_id = $array['proj_id'];
	if ($array['proj_planning_ref'] != NULL) {
		print "<h2 class=\"heading_side\">Planning Reference</h2>";
		$array2 = mysql_fetch_array($result);
		$proj_planning_ref = $array['proj_planning_ref'];
		print "<ul class=\"button_left\"><li><a href=\"index2.php?page=proj_planning_view&amp;proj_id=$proj_id\">$proj_planning_ref</a></li></ul>";
	}
	
// Print the building control information if available
	if ($array['proj_buildingcontrol_ref'] != NULL) {
		print "<h2 class=\"heading_side\">Building Control</h2>";
		$array2 = mysql_fetch_array($result);
		$proj_buildingcontrol_ref = $array['proj_buildingcontrol_ref'];
		print "<ul class=\"button_left\"><li><a href=\"index2.php?page=proj_buildingcontrol_view&amp;proj_id=$proj_id\">$proj_buildingcontrol_ref</a></li></ul>";
	}
	
print "<h1 class=\"heading_side\">Drawings</h1>";
print "<ul class=\"button_left\">";
print "<li><a href=\"index2.php?page=drawings_list&amp;proj_id=$proj_id\">Drawing List</a></li>";
print "</ul>";

?>
