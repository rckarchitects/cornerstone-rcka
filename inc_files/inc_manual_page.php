<?php

	echo "<h1>Project Manual</h1>";

if ($user_usertype_current >= 2 && intval($_GET[manual_id]) > 0 && $_GET[action] == "edit") {
	
	ManualPageEdit($_COOKIE[user], intval($_GET[manual_id]));
	
} elseif ($user_usertype_current >= 2 && $_GET[action] == "add") {
	
	ManualPageAdd(intval($user_id_current));
	
} elseif ($user_usertype_current > 1 && intval($_GET[manual_id])) {
	
	ManualPageView(intval($_GET[manual_id]));
	
} else {
	
	ManualIndexView();
	
}