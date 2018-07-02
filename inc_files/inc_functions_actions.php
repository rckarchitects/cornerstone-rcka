<?php

function UpdateUser($user_id) {
	
	global $conn;

				$user_user_added = AssessDays($_POST[user_user_added],8);
				$user_user_ended = AssessDays($_POST[user_user_ended],18);
				
				$user_address_county = addslashes ( $_POST[user_address_county] );
				$user_address_postcode = addslashes ( $_POST[user_address_postcode] );
				$user_address_town = addslashes ( $_POST[user_address_town] );
				$user_address_3 = addslashes ( $_POST[user_address_3] );
				$user_address_2 = addslashes ( $_POST[user_address_2] );
				$user_address_1 = addslashes ( $_POST[user_address_1] );
				$user_name_first = addslashes ( $_POST[user_name_first] );
				$user_name_second = addslashes ( $_POST[user_name_second] );
				$user_username = addslashes ( $_POST[user_username] );
				$user_email = addslashes ( $_POST[user_email] );
				$user_initials = addslashes ( $_POST[user_initials] );
				$user_notes = addslashes ( $_POST[user_notes] );

			if ($user_id > 0) {

				if ($_POST[update_user_password] == "yes" && $_POST[update_user_password] != NULL) {
						$user_password = md5($_POST[user_password]);
						$update_password = ", user_password = '$user_password' ";
				} else {
						unset($update_password);
				}

				$sql = "UPDATE intranet_user_details SET
				user_address_county = '$user_address_county',
				user_address_postcode = '$user_address_postcode',
				user_address_town = '$user_address_town',
				user_address_3 = '$user_address_3',
				user_address_2 = '$user_address_2',
				user_address_1 = '$user_address_1',
				user_name_first = '$user_name_first',
				user_name_second = '$user_name_second',
				user_num_extension = '$_POST[user_num_extension]',
				user_num_mob = '$_POST[user_num_mob]',
				user_num_home = '$_POST[user_num_home]',
				user_email = '$user_email',
				user_usertype = '$_POST[user_usertype]',
				user_active = '$_POST[user_active]',
				user_username = '$user_username',
				user_user_rate = '$_POST[user_user_rate]',
				user_user_timesheet = '$_POST[user_user_timesheet]',
				user_user_added = '$user_user_added',
				user_user_ended = '$user_user_ended',
				user_holidays = '$_POST[user_holidays]',
				user_initials = '$user_initials',
				user_prop_target = '$_POST[user_prop_target]',
				user_user_timesheet = '$_POST[user_user_timesheet]',
				user_timesheet_hours = '$_POST[user_timesheet_hours]',
				user_notes = '$user_notes'
				$update_password
				WHERE user_id = '$user_id' LIMIT 1";
				
				$result = mysql_query($sql, $conn) or die(mysql_error());
				
				$actionmessage = "<p>User <a href=\"index2.php?page=user_view&amp;user_id=$user_id\">$user_name_first $user_name_second</a> updated.</p>";
				
				AlertBoxInsert($_COOKIE[user],"User Updated",$actionmessage,$user_id,1,0);
				
				
			} else {
				
				$user_password = md5($_POST[user_password]);
				
				$sql = "INSERT INTO intranet_user_details (
				user_address_county,
				user_address_postcode,
				user_address_town,
				user_address_3,
				user_address_2,
				user_address_1,
				user_name_first,
				user_name_second,
				user_num_extension,
				user_num_mob,
				user_num_home,
				user_email,
				user_usertype,
				user_active,
				user_username,
				user_user_rate,
				user_user_timesheet,
				user_user_added,
				user_user_ended,
				user_holidays,
				user_initials,
				user_prop_target,
				user_password,
				user_timesheet_hours,
				user_notes
				) VALUES (
				'$user_address_county',
				'$user_address_postcode',
				'$user_address_town',
				'$user_address_3',
				'$user_address_2',
				'$user_address_1',
				'$user_name_first',
				'$user_name_second',
				'$_POST[user_num_extension]',
				'$_POST[user_num_mob]',
				'$_POST[user_num_home]',
				'$user_email',
				'$_POST[user_usertype]',
				'$_POST[user_active]',
				'$user_username',
				'$_POST[user_user_rate]',
				'$_POST[user_user_timesheet]',
				'$user_user_added',
				'$user_user_ended',
				'$_POST[user_holidays]',
				'$user_initials',
				'$_POST[user_prop_target]',
				'$user_password',
				'$user_timesheet_hours',
				'$user_notes'
				)
				";


				$result = mysql_query($sql, $conn) or die(mysql_error());
				
				$user_id = mysql_insert_id();
				
				$actionmessage = "<p>New user <a href=\"index2.php?page=user_view&amp;user_id=$user_id\">$user_name_first $user_name_second</a> added.</p>";
				
				AlertBoxInsert($_COOKIE[user],"User Added",$actionmessage,$user_id,1,0);
				
				
			}
	
}

function BlogUpdateAction() {
	
	global $conn;


					// Begin to clean up the $_POST submissions

					$blog_id = CleanUp($_POST[blog_id]);
					$blog_user = CleanUp($_POST[blog_user]);
					$blog_date = CleanUp($_POST[blog_date]);
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

						$blog_date_minute = CleanNumber($_POST[blog_date_minute]);
						$blog_date_hour = CleanNumber($_POST[blog_date_hour]);
						$blog_date_day = CleanNumber($_POST[blog_date_day]);
						$blog_date_month = CleanNumber($_POST[blog_date_month]);
						$blog_date_year = CleanNumber($_POST[blog_date_year]);
						
						$blog_date = mktime($blog_date_hour, $blog_date_minute, 0, $blog_date_month, $blog_date_day, $blog_date_year);

					// Construct the MySQL instruction to add these entries to the database

					$sql_add = "UPDATE intranet_projects_blog SET
					blog_user = '$blog_user',
					blog_date = '$blog_date',
					blog_proj = '$blog_proj',
					blog_text = '$blog_text',
					blog_view = '$blog_view',
					blog_title = '$blog_title',
					blog_type = '$blog_type',
					blog_contact = '$blog_contact',
					blog_link = '$blog_link',
					blog_task = '$blog_task',
					blog_pinned = '$blog_pinned',
					blog_access = '$blog_access'
					WHERE blog_id = '$blog_id' LIMIT 1
					";

					$result = mysql_query($sql_add, $conn) or die(mysql_error());

					$actionmessage = "<p>Journal Entry \"<a href=\"index2.php?page=project_blog_view&amp;blog_id=" . $blog_id . "&amp;proj_id=" . $blog_proj . "\">" . $blog_title . "\"</a> was edited successfully.</p>";

					AlertBoxInsert($_COOKIE[user],"Journal Entry Updated",$actionmessage,$blog_id,0);

					$techmessage = $sql_add;

}