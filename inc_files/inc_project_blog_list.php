<?php

if ($_GET[proj_id] != NULL) {$proj_id = CleanNumber($_GET[proj_id]); } elseif ($_POST[blog_proj] != NULL) {$proj_id = CleanNumber($_POST[blog_proj]); } else { $proj_id = NULL; }

print "<h1>Journal Entries</h1>";

$sql = "SELECT * FROM intranet_projects_blog, intranet_projects, intranet_user_details WHERE blog_proj = proj_id AND proj_id = '$proj_id' AND blog_user = user_id order by blog_date DESC";
$result = mysql_query($sql, $conn) or die(mysql_error());
$result_project = mysql_query($sql, $conn) or die(mysql_error());
$array_project = mysql_fetch_array($result_project);
$proj_num = $array_project['proj_num'];
$proj_name = $array_project['proj_name'];
$user_name_first = $array_project['user_name_first'];
$user_name_second = $array_project['user_name_second'];
$user_id = $array_project['user_id'];

// Include a bar to navigate through the pages

		print "<p class=\"submenu_bar\">";

		$items_to_view = 10;

		if ($_GET[limit] == NULL) {$limit = 0; } else { $limit = $_GET[limit]; }
		$total_items = mysql_num_rows($result);
		$page_prev = $limit - $items_to_view;
		$page_next = $limit + $items_to_view;
		
		if ($limit > 0) { print "<a href=\"index2.php?page=project_blog_list&amp;proj_id=$proj_id&amp;limit=$page_prev\" class=\"submenu_bar\">Previous Page</a>"; }
		if ($page_next < $total_items) { print "<a href=\"index2.php?page=project_blog_list&amp;proj_id=$proj_id&amp;limit=$page_next\" class=\"submenu_bar\">Next Page</a>"; }
		print "</p>";

		print "<h2>$proj_num&nbsp;$proj_name</h2>";

$nowtime = time();

if (mysql_num_rows($result) > 0) {

print "<table summary=\"List of Journal Entries for $proj_num $proj_name\">";

$counter = 0;
$title = NULL;
$type = 0;

while ($array = mysql_fetch_array($result)) {

		$blog_id = $array['blog_id'];
		$blog_title = $array['blog_title'];
		$blog_date = $array['blog_date'];
		$blog_type = $array['blog_type'];
		$blog_user = $array['blog_user'];
		$blog_user_name_first = $array['user_name_first'];
		$blog_user_name_second = $array['user_name_second'];
	
	if ($blog_type == "phone") { $blog_type_view = "Telephone Call"; $type++; }
	elseif ($blog_type == "filenote") { $blog_type_view = "File Note"; $type++; }
	elseif ($blog_type == "meeting") { $blog_type_view = "Meeting Note"; $type++; }
	elseif ($blog_type == "email") { $blog_type_view = "Email Message"; $type++; }
	else { $blog_type_view = NULL; $type = 0; }
	
	$blog_type_list = array("phone","filenote","meeting","email");
	
 if ($counter >= $limit AND $counter < $page_next) {
		$counter_title++;
		print "<tr>";
		print "<td>$type.</td><td><a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id&amp;proj_id=$proj_id\">".$blog_title."</a>&nbsp;<a href=\"pdf_journal.php?blog_id=$blog_id\"><img src=\"images/button_pdf.png\" /></a></td>";
		print "<td style=\"width: 20%;\"><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date\">".TimeFormat($blog_date)."</a></td>";
		print "<td><a href=\"index2.php?page=user_view&amp;user_id=$user_id\">".$blog_user_name_first."&nbsp;".$blog_user_name_second."</a></td>";
		print "<td style=\"width: 20%;\"><span class=\"minitext\">$blog_type_view</span></td>";
		print "</tr>";
}

$title = $blog_type;
$counter++;

}


print "</table>";

} else {

print "<p>There are no journal entries on the system for this project.</p>";

}

?>