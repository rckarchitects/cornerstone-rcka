<?php

print "<h1 class=\"heading_side\">Standards &amp; Procedures</h1>";

print "<p class=\"menu_bar\">";

	if ($user_usertype_current > 3) {
		print "<a href=\"index2.php?page=procedure_add\" class=\"menu_tab\">Add New</a>";
	}

print "</p>";

print "<ul class=\"button_left\">";
	print "<li><a href=\"index2.php?page=procedures&amp;item=printcosts\">Print Costs</a></li>";
print "</ul>";

?>
