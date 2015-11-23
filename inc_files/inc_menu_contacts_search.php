<?php

print "<p class=\"heading_side\">Contact Search</p>";

print "<form action=\"index2.php?page=contacts_search\" method=\"post\">";
print "<p><input type=\"text\" class=\"inputbox\" name=\"searchstring\" value=\"$_POST[searchstring]\" /></p>";
print "<p><input type=\"submit\" class=\"inputsubmit\" value=\"Search\" /></p>";
print "</form>";

		
?>
