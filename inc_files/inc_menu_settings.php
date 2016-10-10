<?php

echo "<h1 class=\"heading_side\">System</h1>";

echo "<ul class=\"button_left\">";

	echo "<li><a href=\"index2.php\"><img src=\"images/button_home.png\" alt=\"Home\" />&nbsp;Home</a></li>";
	
	// Admin only settings
	
	if ($user_usertype_current > 4) {
	
		echo "<li><a href=\"index2.php?page=admin_settings\"><img src=\"images/button_settings.png\" alt=\"System Settings\" />&nbsp;Configuration</a></li>";
		
		echo "<li><a href=\"backup.php\"><img src=\"images/button_save.png\" alt=\"Backup Database\" />&nbsp;Backup Database</a></li>";
		
	}
	
	echo "<li><a href=\"logout.php\"><img src=\"images/button_logout.png\" alt=\"Logout\" />&nbsp;Log Out</a></li>";

echo "</ul>";


?>
