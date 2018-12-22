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
				
				$actionmessage = "<p>User <a href=\"index2.php?page=user_view&amp;user_id=$user_id\">$user_name_first $user_name_second</a> (" . $user_initials . "), with user id: " . $user_id . ", updated.</p>";
				
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


function ActionUserChangePassword() {
	
	global $conn;
	global $user_usertype_current;
	$user_id_current = intval( $_COOKIE[user] );
	$user_usertype_current = intval($user_usertype_current);
	$user_id = intval($_POST[user_id]);
	
/* 	if (($user_usertype_current > 3) OR ($user_id_current == $user_id)) {
	
			

			// Begin to clean up the $_POST submissions

				$user_password1 = md5($_POST[user_password1]);
				$user_password2 = md5($_POST[user_password2]);
				
			if ($user_password1 == $user_password2) {
						
					// Get the password details from the database

					$sql = "SELECT user_password FROM intranet_user_details WHERE user_id = " . $user_id . " LIMIT 1";
					$result = mysql_query($sql, $conn) or die(mysql_error());
					$array = mysql_fetch_array($result);
					$user_password_old = $array['user_password'];


					// Construct the MySQL instruction to add these entries to the database
					
					
						if ($user_password1 != $user_password_old) {

							$sql_edit = "UPDATE intranet_user_details SET
							user_password = '" . $user_password1 . "'
							WHERE user_id = " . $user_id . "
							LIMIT 1";
							
							$result = mysql_query($sql_edit, $conn) or die(mysql_error());
							$actionmessage = "<p>Password for user id <a href=\"index2.php?page=user_view&amp;user_id=" . $user_id . "\">" . $user_id . "</a> changed successfully.</p>";
							$techmessage = $sql_edit;
							
							AlertBoxInsert($_COOKIE[user],"User Password Updated",$actionmessage,$user_id_current,1,0);
							
							if ($user_id_current == $user_id) { setcookie("user_password",$user_password1); }
							
						}

			}
			
	} */
	
	echo "<p>Sorry, this isn't working yet. Speak to Russell if you want to change a password.</p>";
	
	
}