<?php

echo "<h1>Activity Log</h1>";

if (!$_GET[view]) {
	echo "<h2>My Activity</h2>";
} elseif ($_GET[view] == "all") {
	echo "<h2>All User Activity</h2>";	
}

if ($user_usertype_current > 4) {
	
	echo "<div class=\"menu_bar\">";
	
		echo "<a href=\"index2.php?page=alert_list&amp;view=all\" class=\"menu_tab\">View All Users</a>";
		
		echo "<a href=\"index2.php?page=alert_list\" class=\"menu_tab\">View My Activities</a>";
	
	echo "</div>";
	
} else {
	
	
	
}

AlertsList($_COOKIE[user],$user_usertype_current);

