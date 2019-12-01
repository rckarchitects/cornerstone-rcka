<?php

if ($user_usertype_current < 4) {
	

NotAllowed();
	
	
} else {
	
	echo "<h1>Team</h1>";
	
	
				if (intval($_GET[list_active]) == 0) {
				echo "<h2>Active Users</h2>";
				echo "<div class=\"menu_bar\"><a class=\"menu_tab\" href=\"index2.php?page=user_list&amp;list_active=1\">All Users</a></div>";
			
			
				} else {
					echo "<h2>All Users</h2>";
					
					echo "<div class=\"menu_bar\"><a class=\"menu_tab\" href=\"index2.php?page=user_list\">Active Users</a></div>";
					
				}
	
	echo "<div class=\"page\">";
	
		if (intval($_GET[list_active]) == 0) { UsersList(0); }
		elseif (intval($_GET[list_active] == 1)) { UsersList(1); }
		
	echo "</div>";

}



