<?php

if (intval($_GET[user_id]) > 0) { $user_id = intval($_GET[user_id]); }
elseif (intval($_POST[user_id] > 0)) { $user_id = intval($_POST[user_id]); }
else { $user_id = 0; }


echo "<h1>Change User Password</h1>";

if ($user_id > 0 && ($user_usertype_current > 3 OR intval($_COOKIE[user]) == $user_id)) {

GetUserName($user_id);

ProjectSubMenu($proj_id,$user_usertype_current,"user_admin",1);
ProjectSubMenu($proj_id,$user_usertype_current,"user_admin",2);

UserChangePasswordForm($user_id);

} else {
	
	echo "<h3>Error</h3>";
	echo "<p>Action prohibited.</p>";
	
}