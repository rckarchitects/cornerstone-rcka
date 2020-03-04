<?php

if (intval($_GET[proj_id]) > 0) { $proj_id = $_GET[proj_id]; } elseif (intval($_POST[proj_id]) > 0) { $proj_id = $_POST[proj_id]; }

ProjectSwitcher("project_fees",$proj_id,1,1);

	echo "<h2>Resource Table</h2>";
	ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
	ProjectSubMenu($proj_id,$user_usertype_current,"project_fee",2);
	echo "<p>Schedule of hours spent per person, per stage, extracted from timesheet data.</p>";
	echo "<div class=\"page\">";
	ResourceByStage_Stages($proj_id);
	echo "</div>";