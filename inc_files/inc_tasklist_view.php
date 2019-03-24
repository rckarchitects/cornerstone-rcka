<?php

echo "<h1>Task List</h1>";

echo "<h2>All My Tasks</h2>";

ProjectSubMenu($proj_id,$user_usertype_current,"tasklist_view",2);

if ($_GET[proj_id]) { $proj_id = intval($_GET[proj_id]); } else { unset($proj_id); }

TasklistSummary($_COOKIE[user],$proj_id);
