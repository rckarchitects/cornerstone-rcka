<?php

// Include the cookie check information

function CheckLogin() {
	
	global $conn;

	include_once("inc_files/inc_checkcookie_logincheck.php");
	include_once("inc_files/inc_functions_general.php");
	

	$checkform_username = addslashes($_POST[checkform_username]);
	$password_submitted = md5($_POST[password]);

	$sql = "SELECT * FROM intranet_user_details where user_username = '" . $checkform_username . "' ";
	$result = mysql_query($sql, $conn);
	

	$array = mysql_fetch_array($result);
	$password_actual = $array['user_password'];
	$user_username = $array['user_username'];
	$user_id = intval($array['user_id']);
	$user_usertype = intval($array['user_usertype']);
	$user_active = intval(intval($array['user_active']));
	$user_user_added = intval($array['user_user_added']);
	

	if (($password_actual != $password_submitted) OR ($user_active == 0)) {
		


		
		setcookie(user, "");
		setcookie(password, "");
		setcookie(name, $checkform_username, time()+60);
		


/* 		$ip_address = $_SERVER['REMOTE_ADDR'];
		if ($_SERVER['REMOTE_HOST']) { $ip_address = $ip_address . " (" . $_SERVER['REMOTE_HOST'] . ")"; }
		if ($checkform_username) { $ip_address = $ip_address . ", using username: '".  $checkform_username . "'.";  }
		$ip_address = $ip_address . ".";

		$actionmessage "<p>Failed Login from IP address: " . $ip_address . ".</p>";

		$array_admin = GetAdmins(5);
		foreach ( $array_admin AS $user_ids) {
			AlertBoxInsert($user_ids,"Login Failed",$actionmessage,1,86400,1);
		} */


		header ("Location: index.php");

	} else {

					if ($_POST[publicpc] != 1) {
					setcookie(user, $user_id, time()+36000);
					setcookie(password, $password_actual, time()+604800);
					} else {
					setcookie(user, $user_id);
					setcookie(password, $password_actual);
					}	
					header ("Location: index2.php");
	}

}


CheckLogin();
