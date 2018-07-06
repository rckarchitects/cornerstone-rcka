<?php

ProjectSwitcher("project_contacts",$proj_id,1,1);

echo "<h2>Project Contacts</h2>";

TopMenu ("project_view1",1,$proj_id);

ProjectSubMenu($proj_id,$user_usertype_current,"project_contacts");

if (intval($_GET[contact_proj_id] > 0) OR $_GET[contact_proj_add] == "add") { ProjectContactEdit($proj_id,$_GET[contact_proj_id]); }

ProjectContacts($proj_id,$user_usertype_current);