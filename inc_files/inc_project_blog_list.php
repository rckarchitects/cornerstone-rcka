<?php

if ($_GET[proj_id] != NULL) {$proj_id = intval($_GET[proj_id]); } elseif ($_POST[blog_proj] != NULL) {$proj_id = intval($_POST[blog_proj]); } else { unset($proj_id); }

if ($_POST[proj_id]) { $proj_id = intval($_POST[proj_id]); } else { echo "<h1>All Blog Entries</h1>"; }

ProjectSwitcher("project_blog_list",$proj_id,0,0);

ProjectSubMenu($proj_id,$user_usertype_current,"project_blog_list");

ListProjectJournalEntries($proj_id);