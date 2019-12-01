<?php

// echo "<p>Set ID: " . intval($_POST['drawing_set']) . "</p>"; echo "<p>Drawing ID: " . intval($_POST['drawing_id']) . "</p>"; echo "<p>Revision ID: " . intval($_POST['revision_id']) . "</p>"; echo "<p>Status ID: " . addslashes($_POST['drawing_status']) . "</p>"; echo "<p>Project ID: " . addslashes($_POST['drawing_project']) . "</p>"; echo "<p>Names: " . addslashes($_POST['issue_name']) . "</p>"; echo "<p>Companies: " . addslashes($_POST['issue_company']) . "</p>"; echo "<p>Drawing Issued: " . addslashes($_POST['drawing_issued']) . "</p>";

if ($_POST['issue_name']) { $set_issued_to_name = explode (",",$_POST['issue_name']); } elseif ($_GET['issue_name'] ) { $set_issued_to_name = explode (",",$_GET['issue_name']); }
if ($_POST['issue_company']) { $set_issued_to_company = explode (",",$_POST['issue_company']); } elseif ($_GET['issue_company'] ) { $set_issued_to_company = explode (",",$_GET['issue_company']); }

ActionAddDrawingIssue($set_issued_to_name,$set_issued_to_company);