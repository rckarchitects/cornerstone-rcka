<?php

echo "<h1>Timesheets</h1>";
echo "<h2>Settings</h2>";

ProjectSubMenu($proj_id,$user_usertype_current,"timesheet_admin",1);

echo "<fieldset><legend>Timesheet Datum</legend>";

// Button to add timesheet datums	
	
	echo "<table width=\"100%\">";
	echo "<tr><td class=\"color\"><a href=\"timesheet_datum.php\">Add Timesheet Datums</a></td></tr>";
	echo "</table>";
	
echo "</fieldset>";