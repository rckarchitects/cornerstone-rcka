<?php

echo "<h1>Task List</h1>";

echo "<h2>All My Tasks</h2>";

ProjectSubMenu($proj_id,$user_usertype_current,"tasklist_view",2);

TasklistSummary($_COOKIE[user]);
