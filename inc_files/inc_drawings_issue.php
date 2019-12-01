<?php

ProjectSwitcher ("drawings_issue",$proj_id,1,1);

echo "<h2>Drawing Issue</h2>";

	ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
	ProjectSubMenu($proj_id,$user_usertype_current,"project_drawings",2);
	
if (intval($_GET['set_id']) > 0) { $set_id = intval($_GET['set_id']); }

if (intval($set_id) == 0) {

	DrawingIssueSetup($_GET[proj_id]);
	
} else {
	
	$set_id = intval($set_id);
	
	if ($_POST['issue_name'] && !$set_issued_to_name) { $set_issued_to_name = explode (",",$_POST['issue_name']); } elseif ($_GET['issue_name'] && !$set_issued_to_name) { $set_issued_to_name = explode (",",$_GET['issue_name']); }
	if ($_POST['issue_company'] && !$set_issued_to_company) { $set_issued_to_company = explode (",",$_POST['issue_company']); } elseif ($_GET['issue_company'] && !$set_issued_to_company) { $set_issued_to_company = explode (",",$_GET['issue_company']); }
	
	DrawingIssueConfirm($set_id,$set_issued_to_name,$set_issued_to_company);
	
	DrawingIssueList($proj_id,$set_id,$set_issued_to_name,$set_issued_to_company);
	
}
