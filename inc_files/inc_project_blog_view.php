<?php

$sql = "SELECT * FROM intranet_projects_blog, intranet_projects where blog_id = '$_GET[blog_id]' AND blog_proj = proj_id LIMIT 1";
$result = mysql_query($sql, $conn);
$array = mysql_fetch_array($result);

$blog_id = $array['blog_id'];
$blog_date = $array['blog_date'];
$blog_user = $array['blog_user'];
$blog_text = $array['blog_text'];
$blog_title = $array['blog_title'];
$blog_view = $array['blog_view'];
$blog_type = $array['blog_type'];
$blog_contact = $array['blog_contact'];
$blog_link = $array['blog_link'];
$blog_task = $array['blog_task'];
$proj_id = $array['proj_id'];
$proj_num = $array['proj_num'];
$proj_name = $array['proj_name'];

if ($blog_user != $user_id_current AND $blog_view == 1) { print "<h1 class=\"alert\">Error</h1>"; print "<p>You do not have sufficient privileges to view this entry.</p>"; }

else {

print "<h1>".$proj_num."&nbsp;".$proj_name."</h1>";

// Project Page Menu
print "<p class=\"submenu_bar\">";
	if ($user_usertype_current > 2 OR $user_id_current == $proj_rep_black OR $blog_user == $user_id_current) {
		print "<a href=\"index2.php?page=project_blog_edit&amp;status=edit&amp;proj_id=$proj_id&amp;blog_id=$blog_id\" class=\"submenu_bar\">Edit</a>";
	}

	if ($user_usertype_current > 1) {
		print "<a href=\"index2.php?page=project_blog_edit&amp;status=add&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add New Project Blog Entry</a>";
	}
	if ($user_usertype_current > 1) {
		print "<a href=\"/pdf_journal.php?blog_id=$blog_id\" class=\"submenu_bar\"><img src=\"images/button_pdf.png\" alt=\"PDF version\" />&nbsp;PDF Output</a>";
	}
print "</p>";

print "<h2>".$blog_title.", ".TimeFormat($blog_date)."</h2>";

if ($blog_contact > 0) { $data_contact = $blog_contact; print "<h3>Contact</h3><p>"; include("dropdowns/inc_data_contacts_name.php"); print "</p>";  }
print "<h3>Project</h3><p><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num&nbsp;$proj_name</a>
</p><h3>Date</h3><p>".date("g:ia", $blog_date)." <a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date\">".TimeFormat($blog_date)."</a>
</p>";

			$type_find = array("phone","filenote","meeting","email");
			$type_replace = array("Telephone Call","File Note","Meeting Note", "Email Message");
			$blog_type_view = str_replace($type_find,$type_replace,$blog_type);
			
print "<h3>Entry by</h3><p>";
$data_user_id = $blog_user; include("dropdowns/inc_data_user_name.php");
print "</p>";

print "<h3>$blog_type_view</h3><blockquote><p>".$blog_text."</p></blockquote>";

// Blogs that this entry links to

if ($blog_link > 0) {

$sql2 = "SELECT * FROM intranet_projects_blog WHERE blog_id = '$blog_link'";
$result2 = mysql_query($sql2, $conn);
$array2 = mysql_fetch_array($result2);
print "<h3>This entry links to</h3>";
	$blog_id_link = $array2['blog_id'];
	$blog_date_link = $array2['blog_date'];
	$blog_title_link = $array2['blog_title'];
	print "<p><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date_link\">".TimeFormat($blog_date_link)."</a> - <a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id_link&amp;proj_id=$proj_id\">".$blog_title_link."</a></p>";
}

// Blogs that link to this entry

$sql3 = "SELECT * FROM intranet_projects_blog WHERE blog_link = '$blog_id' ORDER BY blog_date DESC";
$result3 = mysql_query($sql3, $conn);
if (mysql_num_rows($result3) > 0){

	print "<h3>Links to this entry</h3>";

	while ($array3 = mysql_fetch_array($result3)) {

		$blog_id_linkto = $array3['blog_id'];
		$blog_date_linkto = $array3['blog_date'];
		$blog_title_linkto = $array3['blog_title'];

		print "<p><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date_linkto\">".TimeFormat($blog_date_linkto)."</a> - <a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id_linkto&amp;proj_id=$proj_id\">".$blog_title_linkto."</a></p>";
		
	}
}

// Tasks related to this entry

if ($blog_task > 0) {

$sql4 = "SELECT * FROM intranet_tasklist WHERE tasklist_id = '$blog_task'";
$result4 = mysql_query($sql4, $conn);
$array4 = mysql_fetch_array($result4);
print "<h3>Tasks related to this entry</h3>";
	$tasklist_id = $array4['tasklist_id'];
	$tasklist_notes = $array4['tasklist_notes'];
	$tasklist_due = $array4['tasklist_due'];
	print "<p><a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=$tasklist_id\">$tasklist_notes</a><br />Due: <a href=\"index2.php?page=datebook_view_day&amp;time=$tasklist_due\">".TimeFormat($tasklist_due)."</a></p>";
}


}
?>



