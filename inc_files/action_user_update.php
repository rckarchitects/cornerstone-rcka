<?php

function ActionUpdateUser() {
	
	global $conn;
	
		 if (intval($_POST['user_id']) > 0) { $user_id = intval($_POST['user_id']); } else { unset($user_id); }

			$user_user_added = AssessDays($_POST['user_user_added'],8);
			$user_user_ended = AssessDays($_POST['user_user_ended'],18);
			
			$user_address_county = addslashes ( $_POST['user_address_county'] );
			$user_address_postcode = addslashes ( $_POST['user_address_postcode'] );
			$user_address_town = addslashes ( $_POST['user_address_town'] );
			$user_address_3 = addslashes ( $_POST['user_address_3'] );
			$user_address_2 = addslashes ( $_POST['user_address_2'] );
			$user_address_1 = addslashes ( $_POST['user_address_1'] );
			$user_name_first = addslashes ( $_POST['user_name_first'] );
			$user_name_second = addslashes ( $_POST['user_name_second'] );
			$user_username = addslashes ( $_POST['user_username'] );
			$user_email = addslashes ( $_POST['user_email'] );
			$user_initials = addslashes ( $_POST['user_initials'] );
			$user_notes = addslashes ( $_POST['user_notes'] );
			
		 if ($user_id > 0) {
			 
			if ($_POST['update_user_password'] == "yes" && $_POST['update_user_password'] != NULL) {
					$user_password = md5($_POST['user_password']);
					$update_password = ", user_password = '" . $user_password . "' ";
			} else {
					unset($update_password);
			}
			
			$sql = "UPDATE intranet_user_details SET
					user_address_county = '" . $user_address_county . "',
					user_address_postcode = '" . $user_address_postcode . "',
					user_address_town = '" . $user_address_town . "',
					user_address_3 = '" . $user_address_3 . "',
					user_address_2 = '" . $user_address_2 . "',
					user_address_1 = '" . $user_address_1 . "',
					user_name_first = '" . $user_name_first . "',
					user_name_second = '" . $user_name_second . "',
					user_num_extension = '" . addslashes ( $_POST['user_num_extension'] ) . "',
					user_num_mob = '" . addslashes ( $_POST['user_num_mob'] ) . "',
					user_num_home = '" . addslashes ( $_POST['user_num_home'] ) . "',
					user_email = '" . $user_email . "',
					user_usertype = '" . intval ($_POST['user_usertype'] ) . "',
					user_active = '" . intval ( $_POST['user_active'] ) . "',
					user_username = '" . $user_username . "',
					user_user_rate = '" . floatval ( $_POST['user_user_rate'] ) . "',
					user_user_timesheet = '" . intval ( $_POST['user_user_timesheet'] ) . "',
					user_user_added = '" . $user_user_added . "',
					user_user_ended = '" . $user_user_ended . "',
					user_holidays = '" . floatval ( $_POST['user_holidays'] ) . "',
					user_initials = '" . $user_initials . "',
					user_prop_target = '" . floatval ( $_POST['user_prop_target'] ) . "',
					user_user_timesheet = '" . intval ( $_POST['user_user_timesheet'] ) . "',
					user_timesheet_hours = '" . floatval ( $_POST['user_timesheet_hours'] ) . "',
					user_notes = '" . $user_notes . "',
					user_team = " . intval ( $_POST['user_team'] ) . "
					" . $update_password . "
					WHERE user_id = " . $user_id . " LIMIT 1";
			
			$result = mysql_query($sql, $conn) or die(mysql_error());		
			
			//echo "<p>" . $sql . "</p>";
			
		} elseif ($user_id == NULL) {
			
			$user_password = md5($_POST['user_password']);
			
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
					user_notes,
					user_team
					) VALUES (
					'" . $user_address_county . "',
					'" . $user_address_postcode . "',
					'" . $user_address_town . "',
					'" . $user_address_3 . "',
					'" . $user_address_2 . "',
					'" . $user_address_1 . "',
					'" . $user_name_first . "',
					'" . $user_name_second . "',
					'" . $_POST['user_num_extension'] . "',
					'" . $_POST['user_num_mob'] . "',
					'" . $_POST['user_num_home'] . "',
					'" . $user_email . "',
					'" . $_POST['user_usertype'] . "',
					'" . $_POST['user_active'] . "',
					'" . $user_username . "',
					'" . $_POST['user_user_rate'] . "',
					'" . $_POST['user_user_timesheet'] . "',
					'" . $user_user_added . "',
					'" . $user_user_ended . "',
					'" . $_POST['user_holidays'] . "',
					'" . $user_initials . "',
					'" . $_POST['user_prop_target'] . "',
					'" . $user_password . "',
					'" . $user_timesheet_hours . "',
					'" . intval ( $_POST['user_prop_target'] ) . ",
					'" . $user_notes . "',
					'" . intval ( $_POST['user_team'] ) . "
					)
			";
			
			
			$result = mysql_query($sql, $conn) or die(mysql_error());
			

			
		}
	
}

if ($user_usertype_current > 4) {

	ActionUpdateUser();

}
