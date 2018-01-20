<?php

$proj_id = intval($_GET[proj_id]);

ProjectSwitcher ("project_checklist",$proj_id,1,1);

if ($_GET[item] > 0 && $user_usertype_current > 2) { $item = $_GET[item]; } else { unset($item); }

$proj_id = intval($_GET[proj_id]);
$showhidden = $_GET[showhidden];

echo "<h2>Project Checklist</h2>";

if (!$_GET[group_id]) { $group_id = 1; } else { $group_id = intval($_GET[group_id]); }

echo "<div class=\"menu_bar\"><a href=\"pdf_project_checklist.php?proj_id=$proj_id\" class=\"menu_tab\">Checklist <img src=\"images/button_pdf.png\" /></a><a href=\"pdf_project_checklist_stages.php?proj_id=$proj_id\" class=\"menu_tab\">Stages <img src=\"images/button_pdf.png\" /></a><a href=\"index2.php?page=project_checklist_edit&amp;proj_id=$proj_id&amp;group_id=$group_id\" class=\"menu_tab\">Edit <img src=\"images/button_edit.png\" /></a>";

if ($showhidden == "yes") {
	echo "<a href=\"index2.php?page=project_checklist&amp;showhidden=no&amp;proj_id=$proj_id\" class=\"menu_tab\">Hide Hidden Items</a>";
} else {
	echo "<a href=\"index2.php?page=project_checklist&amp;showhidden=yes&amp;proj_id=$proj_id\" class=\"menu_tab\">Show Hidden Items</a>";
}

echo "</div>";





StageTabs($group_id, $proj_id, "index2.php?page=project_checklist&amp;proj_id=$proj_id&amp;");



CheckListRows($proj_id,$group_id,$showhidden);