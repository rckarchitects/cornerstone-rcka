<?php

function ActionBlogAdd() {
	
	global $conn;

				// Check that the required values have been entered, and alter the page to show if these values are invalid

				if ($_POST[blog_text] == "") { $alertmessage = "The text field was left empty."; $page = "blog_edit"; $action = "add"; $proj_id = $_POST[blog_proj]; }
				elseif ($_POST[blog_title] == "") { $alertmessage = "The title was left empty."; $page = "blog_edit"; $action = "add"; $proj_id = $_POST[blog_proj]; }

				elseif ( !checkdate($_POST[blog_date_month], $_POST[blog_date_day], $_POST[blog_date_year]) ) {
				$alertmessage = "The date is invalid."; $page = "blog_edit"; }


				else {

				// This determines the page to show once the form submission has been successful

				$page = "blog_view";

				// Begin to clean up the $_POST submissions

				$blog_user = intval($_POST[blog_user]);
				$blog_date = CleanUp($_POST[blog_date]);
				$blog_proj = intval($_POST[blog_proj]);
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

					$blog_date_minute = CleanNumber($_POST[blog_date_minute]);
					$blog_date_hour = CleanNumber($_POST[blog_date_hour]);
					$blog_date_day = CleanNumber($_POST[blog_date_day]);
					$blog_date_month = CleanNumber($_POST[blog_date_month]);
					$blog_date_year = CleanNumber($_POST[blog_date_year]);
					
					$blog_date = mktime($blog_date_hour, $blog_date_minute, 0, $blog_date_month, $blog_date_day, $blog_date_year);

				// Construct the MySQL instruction to add these entries to the database

				$sql_add = "INSERT INTO intranet_projects_blog (
				blog_id,
				blog_date,
				blog_user,
				blog_proj,
				blog_text,
				blog_title,
				blog_view,
				blog_type,
				blog_contact,
				blog_link,
				blog_task,
				blog_pinned,
				blog_access,
				blog_sticky,
				blog_updated_date,
				blog_updated_by,
				blog_revision
				) values (
				NULL,
				'$blog_date',
				$blog_user,
				$blog_proj,
				'$blog_text',
				'$blog_title',
				'$blog_view',
				'$blog_type',
				'$blog_contact',
				'$blog_link',
				'$blog_task',
				$blog_pinned,
				$blog_access,
				$blog_sticky,
				0,
				0,
				0
				)";
				
				//echo "<p>" . $sql_add . "</p>";

				$result = mysql_query($sql_add, $conn) or die(mysql_error());

				$id_added = mysql_insert_id();

				$actionmessage = "<p>Journal entry <a href=\"index2.php?page=project_blog_view&amp;blog_id=$id_added&amp;proj_id=$blog_proj\">\" " . $blog_title . "\"</a> was added successfully.</p>";

				AlertBoxInsert($_COOKIE[user],"Journal Entry Added",$actionmessage,$id_added,0,1,$blog_proj);

				if ($id_added > 0) { BackupJournal($id_added); }

				}
				
		return $id_added;

}

$blog_id = ActionBlogAdd();