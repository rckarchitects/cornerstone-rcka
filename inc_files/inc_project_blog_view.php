<?php

if (intval($blog_id) > 0) { $blog_id = intval($blog_id) ; }
elseif (intval($_POST[blog_id]) > 0) { $blog_id = intval($_POST[blog_id]) ; }
elseif (intval($_GET[blog_id]) > 0) { $blog_id = intval($_GET[blog_id]) ; }

if (!$_GET[proj_id] && !$_POST[proj_id]) {
	$proj_id = ProjectID("blog_proj","intranet_projects_blog","blog_id",$blog_id);
	ProjectTitle(2,$proj_id);
}

function BlogView($blog_id) {

				global $conn;
				global $user_id_current;
				global $user_usertype_current;
				$blog_id = intval($blog_id);
				
				
					$sql = "SELECT * FROM intranet_projects_blog WHERE blog_id = " . intval($blog_id) . " AND (blog_access <= " . intval($user_usertype_current) . " OR blog_access IS NULL) AND (blog_view = 0 OR blog_view = " . $user_id_current . ") LIMIT 1";

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
					$blog_proj = $array['blog_proj'];

					if (intval($proj_id) == 0) { "<h1>Journal $proj_id</h1>"; }

					if ($user_usertype_current > 0 && $blog_id > 0) {

					if ($blog_user != $user_id_current AND $blog_view == 1 AND $user_usertype_current < 4) { echo "<h1 class=\"alert\">Error</h1>"; echo "<p>You do not have sufficient privileges to view this entry.</p>"; }

					else {
						
						echo "<h2>".$blog_title.", ".TimeFormat($blog_date)."</h2>";
						
						ProjectSubMenu($blog_proj,$user_usertype_current,"project_view",1);
						ProjectSubMenu($blog_proj,$user_usertype_current,"blog_view",2);



					if ($blog_contact) {
						$data_contact = $blog_contact; echo "<h3>Contact</h3><p>"; include("dropdowns/inc_data_contacts_name.php"); echo "</p>"; 
					}

					echo "<div><div class=\"float\"><article><h3>Date</h3><p>".date("g:ia", $blog_date)." <a href=\"index2.php?page=datebook_view_day&amp;timestamp=$blog_date\">".TimeFormat($blog_date)."</a>
					</p></div>";

								$type_find = array("phone","filenote","meeting","email","rfi");
								$type_replace = array("Telephone Call","File Note","Meeting Note", "Email Message","Request for Information (RFI)");
								$blog_type_view = str_replace($type_find,$type_replace,$blog_type);
								
					echo "<div class=\"float\"><h3>Entry by</h3><p>";
					$data_user_id = $blog_user; include("dropdowns/inc_data_user_name.php");
					echo "</p></div></div>";

					echo "<h3>$blog_type_view</h3><div class=\"page\"><p>".$blog_text."</p></article></div>";

					// Blogs that this entry links to

					if ($blog_link > 0) {

					$sql2 = "SELECT * FROM intranet_projects_blog WHERE blog_id = '$blog_link'";
					$result2 = mysql_query($sql2, $conn);
					$array2 = mysql_fetch_array($result2);
					echo "<h3>This entry links to</h3>";
						$blog_id_link = $array2['blog_id'];
						$blog_date_link = $array2['blog_date'];
						$blog_title_link = $array2['blog_title'];
						echo "<p><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date_link\">".TimeFormat($blog_date_link)."</a> - <a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id_link&amp;proj_id=$proj_id\">".$blog_title_link."</a></p>";
					}

					// Blogs that link to this entry

					$sql3 = "SELECT * FROM intranet_projects_blog WHERE blog_link = '$blog_id' ORDER BY blog_date DESC";
					$result3 = mysql_query($sql3, $conn);
					if (mysql_num_rows($result3) > 0){

						echo "<h3>Links to this entry</h3>";

						while ($array3 = mysql_fetch_array($result3)) {

							$blog_id_linkto = $array3['blog_id'];
							$blog_date_linkto = $array3['blog_date'];
							$blog_title_linkto = $array3['blog_title'];

							echo "<p><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date_linkto\">".TimeFormat($blog_date_linkto)."</a> - <a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id_linkto&amp;proj_id=$proj_id\">".$blog_title_linkto."</a></p>";
							
						}
					}

					// Tasks related to this entry

					if ($blog_task > 0) {

					$sql4 = "SELECT * FROM intranet_tasklist WHERE tasklist_id = '$blog_task'";
					$result4 = mysql_query($sql4, $conn);
					$array4 = mysql_fetch_array($result4);
					echo "<h3>Tasks related to this entry</h3>";
						$tasklist_id = $array4['tasklist_id'];
						$tasklist_notes = $array4['tasklist_notes'];
						$tasklist_due = $array4['tasklist_due'];
						echo "<p><a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=$tasklist_id\">$tasklist_notes</a><br />Due: <a href=\"index2.php?page=datebook_view_day&amp;time=$tasklist_due\">".TimeFormat($tasklist_due)."</a></p>";
					}


					}
					
		if (intval($proj_id) > 0) { return $blog_proj; }
		
	} else {
		
		echo "<p>No journal entry found.</p>";
	
	}
	

}

$proj_id = BlogView($blog_id);

ListBackups("journal",$blog_id);



