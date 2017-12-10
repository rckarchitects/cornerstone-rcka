<?php

if ($_GET[proj_id] > 0) { $proj_id = intval($_GET[proj_id]); }

$maxnum = 5;

	SearchPanel($user_usertype_current);

// First print the blog entries if there are any

$sql = "SELECT * FROM intranet_projects_blog where blog_proj = $_GET[proj_id] ORDER BY blog_date DESC LIMIT $maxnum";
$sql2 = "SELECT blog_id FROM intranet_projects_blog where blog_proj = $_GET[proj_id] ORDER BY blog_date DESC";
$result = mysql_query($sql, $conn) or die(mysql_error());
$result2 = mysql_query($sql2, $conn) or die(mysql_error());
$result_num = mysql_num_rows($result2);

unset($array_pages);
unset($array_title);
unset($array_access);
unset($array_images);

if (mysql_num_rows($result) > 0) {
		

		while ($array = mysql_fetch_array($result)) {

			$blog_id = $array['blog_id'];
			$blog_date = $array['blog_date'];
			$blog_user = $array['blog_user'];
			$blog_text = $array['blog_text'];
			$blog_title = $array['blog_title'];
			$blog_view = $array['blog_view'];
			$blog_type = $array['blog_type'];
			$blog_proj = $array['blog_proj'];
			
			
			$array_pages[] = "index2.php?page=project_blog_view&amp;blog_id=$blog_id&amp;proj_id=$blog_proj";
			$array_title[] = TimeFormat($blog_date) . "<br />" . $blog_title;
		
		}
		
		if ($result_num < $maxnum) { $print_total = $result_num; } else { $print_total = $maxnum; }
				
			
			
		if ($result_num > $maxnum) {
		
			
			$array_title[] =  "[More]";
			$array_pages[]  = "index2.php?page=project_blog_list&amp;proj_id=$blog_proj";
		
		}
		
// Menu - Journal Entries

	if ($module_journal == 1) {


			SideMenu ("Project Journal", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
			
	}

}

// Menu - Drawings

if ($module_drawings == 1) {

	$array_pages = array("index2.php?page=drawings_list&amp;proj_id=$proj_id");
	$array_title = array("Drawings");
	$array_images = array();
	$array_access = array(2);

	SideMenu ("Drawings", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}
	

?>
