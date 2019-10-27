<?php

ProjectSwitcher ("drawings_issue",$proj_id,1,1);

echo "<h2>Drawing Issue</h2>";



	ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
	ProjectSubMenu($proj_id,$user_usertype_current,"project_drawings",2);
	

DrawingIssueSetup($_GET[proj_id]);
