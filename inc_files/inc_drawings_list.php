<?php

if (intval($_GET[proj_id]) > 0) { $proj_id = $_GET[proj_id]; } elseif (intval($_POST[proj_id]) > 0) { $proj_id = $_POST[proj_id]; }

ProjectSwitcher("drawings_list",$proj_id,1,1);


echo "<h2>Drawings</h2>";
	
	ProjectSubMenu($proj_id,$user_usertype_current,"project_drawings",1);
	ProjectSubMenu($proj_id,$user_usertype_current,"drawings_list",2);
	ProjectDrawingList($proj_id);

