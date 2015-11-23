<?php

// Include the cookie check information

include("inc_files/inc_checkcookie_logincheck.php");

$checkform_username = $_POST[checkform_username];
$password_submitted = $_POST[password];

$sql = "SELECT * FROM intranet_user_details where user_username = '$checkform_username' ";
$result = mysql_query($sql, $conn);

$array = mysql_fetch_array($result);
$password_actual = $array['user_password'];
$user_username = $array['user_username'];
$user_id = $array['user_id'];
$user_usertype = $array['user_usertype'];
$user_active = $array['user_active'];
$user_user_added = $array['user_user_added'];

if ($password_actual != md5($password_submitted) OR $user_active == 0) {
setcookie(user, "");
setcookie(password, "");
setcookie(name, $checkform_username, time()+60);
header ("Location: login.php");

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


?>
