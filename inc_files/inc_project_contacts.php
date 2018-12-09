<?php

if (intval($proj_id) > 0) { $proj_id = intval($proj_id); } elseif (intval($_GET[proj_id]) > 0) { $proj_id = $_GET[proj_id]; } elseif (intval($_POST[proj_id]) > 0) { $proj_id = $_POST[proj_id]; }

ProjectSwitcher("project_contacts",$proj_id,1,1);

echo "<h2>Project Contacts</h2>";

ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
ProjectSubMenu($proj_id,$user_usertype_current,"project_contacts");

if (intval($_GET[contact_proj_id] > 0) OR $_GET[contact_proj_add] == "add") { ProjectContactEdit($proj_id,$_GET[contact_proj_id]); }

ProjectContacts($proj_id,$user_usertype_current);