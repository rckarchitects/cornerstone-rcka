<?
$today = TimeFormatDay(time());

print "<h1>$today</h1>";

print "<p class=\"menu_bar\">";
print "<a href=\"#\" onclick=\"itemSwitch(1); return false;\" class=\"menu_tab\">Projects</a>";
print "<a href=\"#\" onclick=\"itemSwitch(2); return false;\" class=\"menu_tab\">Tasks</a>";
print "<a href=\"#\" onclick=\"itemSwitch(3); return false;\" class=\"menu_tab\">Messages</a>";
print "</p>";


// Menu

print "<div id=\"item_switch_1\">";

	include("inc_files/inc_project_list.php");

print "</div>";

print "<div id=\"item_switch_2\">";

	include("inc_files/inc_tasklist_summary.php");

print "</div>";

print "<div id=\"item_switch_3\">";

	// include("inc_files/inc_messages_list.php");
	print "<p class=\"submenu_bar\"><a href=\"index2.php?page=phonemessage_edit&amp;status=new\" class=\"submenu_bar\">Add New Message</a></p>";
	print "<h2>Messages</h2>";
	print "<p>You currently have no outstanding messages.</p>";
	
print "</div>";

print "
		<script type=\"text/javascript\">
		document.getElementById(\"item_switch_1\").style.display = \"block\";
		document.getElementById(\"item_switch_2\").style.display = \"none\";
		document.getElementById(\"item_switch_3\").style.display = \"none\";
		</script>
";

?>

