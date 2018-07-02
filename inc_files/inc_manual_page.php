<?php

	echo "<h1>Practice Manual</h1>";

if ($user_usertype_current > 3 && intval($_GET[manual_id]) > 0 && $_GET[action] == "edit") {
	
	ManualPageEdit($_COOKIE[user], intval($_GET[manual_id]), $user_usertype_current);
	
} elseif ($user_usertype_current > 3 && $_GET[action] == "add") {
	
	ManualPageAdd(intval(intval($_COOKIE[user]), intval($user_usertype_current)));
	
} elseif ($user_usertype_current > 1 && intval($_GET[manual_id])) {
	
	ManualPageView(intval($_GET[manual_id]));
	
} else {
	
	ManualIndexView();
	
}