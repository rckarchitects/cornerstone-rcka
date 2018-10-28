<?php

ProjectSwitcher ("project_actionstream",$proj_id,1,1);

echo "<h2>Action Stream</h2>";
ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);



if (intval($proj_id) > 0) { 
	ProjectActionStream ($proj_id);
} else {
	echo "<p>No project selected.</p>";
}