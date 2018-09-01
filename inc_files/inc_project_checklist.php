<?php

if (intval($_GET[proj_id]) > 0) { $proj_id = $_GET[proj_id]; } elseif (intval($_POST[proj_id]) > 0) { $proj_id = $_POST[proj_id]; }

ProjectSwitcher ("project_checklist",$proj_id,1,1);

echo "<h2>Project Checklist</h2>";

	ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
	ProjectSubMenu($proj_id,$user_usertype_current,"project_checklist",2);

if ($_GET[item] > 0 && $user_usertype_current > 2) { $item = $_GET[item]; } else { unset($item); }

$proj_id = intval($_GET[proj_id]);
$showhidden = $_GET[showhidden];



if (!$_GET[group_id]) { $group_id = 1; } else { $group_id = intval($_GET[group_id]); }







StageTabs($group_id, $proj_id, "index2.php?page=project_checklist&amp;proj_id=$proj_id&amp;");

	echo "

		<script type=\"text/javascript\">

			function hideRow(row, hideVal) {
			if (document.getElementById(row)) {
			  var displayStyle = (hideVal!=true)? '' : 'none' ;
			  document.getElementById(row).style.display = displayStyle;
			}
		  }

			
		</script>
			
	";

CheckListRows($proj_id,$group_id,$showhidden);