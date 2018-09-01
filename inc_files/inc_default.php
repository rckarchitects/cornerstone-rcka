<?

echo "<div style=\"width: 100%; float: left;\">";

DateList(1);

echo "<h2>Projects</h2>";

// Menu

echo "<div id=\"item_switch_1\">";


	ProjectListFrontPage($user_id_current);

echo "</div>";

echo "<div id=\"item_switch_2\">";

	include("inc_files/inc_tasklist_summary.php");

echo "</div>";

echo "<div id=\"item_switch_3\">";

	// include("inc_files/inc_messages_list.php");
	echo "<div class=\"submenu_bar\"><a href=\"index2.php?page=phonemessage_edit&amp;status=new\" class=\"submenu_bar\">Add New Message</a></div>";
	echo "<h3>Messages</h3>";
	echo "<p>You currently have no outstanding messages.</p>";
	
echo "</div>";

echo "
		<script type=\"text/javascript\">
		document.getElementById(\"item_switch_1\").style.display = \"block\";
		document.getElementById(\"item_switch_2\").style.display = \"none\";
		document.getElementById(\"item_switch_3\").style.display = \"none\";
		</script>
";

echo "</div>";

