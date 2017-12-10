<?php

if ($_GET[proj_id] != NULL) {$proj_id = intval($_GET[proj_id]); } elseif ($_POST[blog_proj] != NULL) {$proj_id = intval($_POST[blog_proj]); } else { unset($proj_id); }

ListProjectJournalEntries($proj_id);