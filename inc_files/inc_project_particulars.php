<?php

if (intval($_GET[proj_id]) > 0) { $proj_id = $_GET[proj_id]; } elseif (intval($_POST[proj_id]) > 0) { $proj_id = $_POST[proj_id]; }

ProjectSwitcher ("project_particulars",$proj_id,1,1);

	echo "<h2>Project Particulars</h2>";
	ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
	echo "<div class=\"page\">";
	ProjectParticulars($proj_id);
	echo "</div>";