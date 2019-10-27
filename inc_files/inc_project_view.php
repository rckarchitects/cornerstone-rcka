<?php

if (intval($_GET[proj_id]) > 0) { $proj_id = $_GET[proj_id]; } elseif (intval($_POST[proj_id]) > 0) { $proj_id = $_POST[proj_id]; }

ProjectSwitcher("project_view",$proj_id,0,0);

	echo "<h2>Project Information</h2>";
	ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
	ProjectSubMenu($proj_id,$user_usertype_current,"project_view",2);
	echo "<div class=\"page\">";
	ProjectList($proj_id);
	echo "</div>";