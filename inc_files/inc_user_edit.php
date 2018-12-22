<?php


if (intval($_GET[user_id]) > 0) { $user_id = intval($_GET[user_id]); }
elseif (intval($_POST[user_id] > 0)) { $user_id = intval($_POST[user_id]); }
else { $user_id = 0; }

echo "<h1>Users</h1>";;


if ($_GET[user_add] == "true" && $user_usertype_current > 3 && $user_id == 0) {
	
	unset($user_id);
	
	echo "<h2>Add New User</h2>";
	
} else {
	
	
	
	GetUserName($user_id);
	
}



if ($user_usertype_current > 3 OR intval($user_id) == intval($user_id_current)) {

	UserForm($user_id);

} else {
	
	InsufficientRights();	
	
}