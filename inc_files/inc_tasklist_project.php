<?php

ProjectSwitcher("tasklist_project",$proj_id,1,1);

if ($_GET[view] == "complete") {
echo "<h2>Completed Tasks</h2>";
} else {
echo "<h2>Outstanding Tasks</h2>";	
}


ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
ProjectSubMenu($proj_id,$user_usertype_current,"project_tasks",2);
ProjectTasks($proj_id);
