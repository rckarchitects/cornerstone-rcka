<?php

if ($risk_id) { $risk_id = intval($risk_id); }
elseif ($_GET[risk_id]) { $risk_id = intval($_GET[risk_id]); }
elseif ($_POST[risk_id]) { $risk_id = intval($_POST[riskid]); }

if ($proj_id) { $proj_id = intval($proj_id); }
elseif ($_GET[proj_id]) { $proj_id = intval($_GET[proj_id]); }
elseif ($_POST[proj_id]) { $proj_id = intval($_POST[proj_id]); }

if (!$_GET[proj_id] && !$_POST[proj_id]) {
	$proj_id = ProjectID("risk_project","intranet_project_risks","risk_id",$risk_id);
	ProjectTitle(2,$proj_id);
}

ProjectSwitcher("project_risks",$proj_id,1,1);

echo "<h2>Risk Register</h2>";

ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
ProjectSubMenu($proj_id,$user_usertype_current,"project_risks",2);

RiskList($proj_id);

RiskEdit($risk_id,$proj_id);