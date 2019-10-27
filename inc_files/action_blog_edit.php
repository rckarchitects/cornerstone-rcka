<?php

function BlogUpdateAction() {
	
	global $conn;

	

					// Begin to clean up the $_POST submissions

					$blog_id = intval($_POST[blog_id]);
					$blog_user = CleanUp($_POST[blog_user]);
					$blog_proj = CleanUp($_POST[blog_proj]);
					$blog_text = addslashes($_POST[blog_text]);
					$blog_view = CleanUp($_POST[blog_view]);
					$blog_title = CleanUp($_POST[blog_title]);
					$blog_type = CleanUp($_POST[blog_type]);
					$blog_contact = CleanNumber($_POST[blog_contact]);
					$blog_link = CleanUp($_POST[blog_link]);
					$blog_task = CleanUp($_POST[blog_task]);
					$blog_pinned = intval($_POST[blog_pinned]);
					$blog_access = intval($_POST[blog_access]);
					$blog_sticky = intval($_POST[blog_sticky]);
					$blog_updated_date = time();
					$blog_updated_by = intval($_POST[blog_user]);
					$blog_drawing_ref = $_POST[blog_drawing_ref];
					
					$blog_formal_revision = $_POST[blog_formal_revision];
					
					BackupJournal($blog_id);

						$blog_date_minute = CleanNumber($_POST[blog_date_minute]);
						$blog_date_hour = CleanNumber($_POST[blog_date_hour]);
						$blog_date_day = CleanNumber($_POST[blog_date_day]);
						$blog_date_month = CleanNumber($_POST[blog_date_month]);
						$blog_date_year = CleanNumber($_POST[blog_date_year]);
						
						$blog_date = mktime($blog_date_hour, $blog_date_minute, 0, $blog_date_month, $blog_date_day, $blog_date_year);

					// Construct the MySQL instruction to add these entries to the database
					
					if ($blog_formal_revision == "yes") { $blog_formal_revision = "blog_revision = blog_revision + 1,"; } else { unset($blog_formal_revision); }

					$sql_add = "UPDATE intranet_projects_blog SET
					blog_proj = '$blog_proj',
					blog_text = '$blog_text',
					blog_view = '$blog_view',
					blog_title = '$blog_title',
					blog_type = '$blog_type',
					blog_contact = '$blog_contact',
					blog_link = '$blog_link',
					blog_task = '$blog_task',
					blog_pinned = $blog_pinned,
					blog_access = $blog_access,
					blog_sticky = $blog_sticky,
					blog_lock = 0,
					blog_updated_date = $blog_updated_date,
					blog_updated_by = $blog_updated_by,
					" . $blog_formal_revision . "
					blog_drawing_ref = '$blog_drawing_ref'
					WHERE blog_id = $blog_id LIMIT 1
					";

					$result = mysql_query($sql_add, $conn) or die(mysql_error());

					$actionmessage = "<p>Journal Entry \"<a href=\"index2.php?page=project_blog_view&amp;blog_id=" . $blog_id . "&amp;proj_id=" . $blog_proj . "\">" . $blog_title . "\"</a> was edited successfully.</p>";

					AlertBoxInsert($_COOKIE[user],"Journal Entry Updated",$actionmessage,$blog_id,0,0,$blog_proj);
				

}

BlogUpdateAction();
