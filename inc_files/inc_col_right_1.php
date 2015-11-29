<?php

echo "<h1 class=\"heading_side\">Internet Feed</h1>";
echo "<ul class=\"button_left\"><li><a href=\"index2.php?page=feeds\">Internet Feeds</a></li></ul>";

echo "<h1 class=\"heading_side\">Journal</h1>";
echo "<ul class=\"button_left\"><li><a href=\"index2.php?page=project_blog_edit&amp;status=add\">Add Journal Entry</a></li></ul>";

include_once("inc_files/inc_menu_search.php");

print "
<p id=\"navigation\" class=\"menu_bar\">
<a href=\"#\" onclick=\"menuSwitch(1); return false;\">Team</a> |
<a href=\"#\" onclick=\"menuSwitch(2); return false;\">Office</a> |
<a href=\"#\" onclick=\"menuSwitch(3); return false;\">Info.</a> |
<a href=\"#\" onclick=\"menuSwitch(4); return false;\">Health &amp; Safety</a> |
</p>
";

print "<div id=\"page_element_2\">";
print "<h1 class=\"heading_side\">Office</h1>";
	include("inc_files/inc_menu_address.php");
print "</div>";

print "<div id=\"page_element_1\">";
print "<h1 class=\"heading_side\">Team</h1>";
include("inc_files/inc_menu_team.php");
print "</div>";

print "<div id=\"page_element_3\">";
print "<h1 class=\"heading_side\">Practice</h1>";
echo "<ul class=\"button_left\">";

	echo "<li><strong>Company Information</strong></li>";

	include_once("secure/sidebar_company.inc");

echo "</ul>";
print "</div>";


print "<div id=\"page_element_4\">";
print "<h1 class=\"heading_side\">H&amp;S</h1>";

	include_once("secure/sidebar_hands.inc");

echo "</div>";

echo "
		<script type=\"text/javascript\">
		document.getElementById(\"page_element_1\").style.display = \"block\";
		document.getElementById(\"page_element_2\").style.display = \"none\";
		document.getElementById(\"page_element_3\").style.display = \"none\";
		document.getElementById(\"page_element_4\").style.display = \"none\";
		</script>
";


?>


