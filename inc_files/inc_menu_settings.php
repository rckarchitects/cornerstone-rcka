<?php

print "<h1 class=\"heading_side\">Options</h1>";

print "<ul class=\"button_left\">";

	print "<li><a href=\"index2.php\"><img src=\"images/button_home.png\" alt=\"Home\" />&nbsp;Home</a></li>";
	
	if ($user_usertype_current > 4) { print "<li><a href=\"index2.php?page=admin_settings\"><img src=\"images/button_settings.png\" alt=\"System Settings\" />&nbsp;Configuration</a></li>"; }
	
	print "<li><a href=\"logout.php\"><img src=\"images/button_logout.png\" alt=\"Logout\" />&nbsp;Log Out</a></li>";

print "</ul>";


?>
