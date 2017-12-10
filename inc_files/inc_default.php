<?

echo "<div class=\"menu_bar\">";

echo "<a href=\"#\" onclick=\"itemSwitch(1); return false;\" class=\"menu_tab\">Projects</a>";
echo "<a href=\"#\" onclick=\"itemSwitch(2); return false;\" class=\"menu_tab\">Tasks</a>";
echo "<a href=\"#\" onclick=\"itemSwitch(3); return false;\" class=\"menu_tab\">Messages</a>";

echo "</div>";


// Menu

echo "<div id=\"item_switch_1\">";

	include("inc_files/inc_project_list.php");

echo "</div>";

echo "<div id=\"item_switch_2\">";

	include("inc_files/inc_tasklist_summary.php");

echo "</div>";

echo "<div id=\"item_switch_3\">";

	// include("inc_files/inc_messages_list.php");
	echo "<p class=\"submenu_bar\"><a href=\"index2.php?page=phonemessage_edit&amp;status=new\" class=\"submenu_bar\">Add New Message</a></p>";
	echo "<h2>Messages</h2>";
	echo "<p>You currently have no outstanding messages.</p>";
	
echo "</div>";

echo "
		<script type=\"text/javascript\">
		document.getElementById(\"item_switch_1\").style.display = \"block\";
		document.getElementById(\"item_switch_2\").style.display = \"none\";
		document.getElementById(\"item_switch_3\").style.display = \"none\";
		</script>
";

?>

