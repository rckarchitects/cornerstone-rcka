<?php

if ($_POST[proj_id] != NULL) { $proj_id = $_POST[proj_id]; } elseif ($_GET[proj_id] != NULL) { $proj_id = $_GET[proj_id]; }

ProjectInvoices($proj_id,"project_invoice");