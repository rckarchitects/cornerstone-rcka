<?php

if (intval($_GET[proj_id]) > 0) { $proj_id = $_GET[proj_id]; } elseif (intval($_POST[proj_id]) > 0) { $proj_id = $_POST[proj_id]; }

ProjectSwitcher("project_blog_list",$proj_id,0,0);

echo "<h2>Journal Entries</h2>";

ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
ProjectSubMenu($proj_id,$user_usertype_current,"project_blog_list");
ListProjectJournalEntries($proj_id);