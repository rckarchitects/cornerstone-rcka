<?php

if ($_GET[proj_id]) { $proj_id = intval($_GET[proj_id]); }
elseif ($_POST[proj_id]) { $proj_id = intval($_POST[proj_id]); }
elseif ($_POST[tasklist_project]) { $proj_id = intval($_POST[tasklist_project]); }

if (intval($proj_id) == 0) { echo "<h1>Task List</h1>"; }

TaskListEditForm($_GET[tasklist_id]) ;
