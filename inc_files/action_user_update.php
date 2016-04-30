<?php

if ($_POST[user_id] > 0) { $user_id = $_POST[user_id]; }
else { $user_id = ""; }

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
	user_user_timesheet = '$_POST[user_user_timesheet]'
	$update_password
	WHERE user_id = '$user_id' LIMIT 1";
	
	$result = mysql_query($sql, $conn) or die(mysql_error());	
	
	
} elseif ($user_id == NULL && $user_usertype_current > 3) {
	
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
	user_password
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
	'$user_password' )
	";


	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	
	
}
	
	
	
	
	

	
	




	
	

	//echo "<p>$sql</p>";
	
?>
