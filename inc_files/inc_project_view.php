<?php


if ($_GET[proj_id] > 0) { $proj_id = intval($_GET[proj_id]); }
elseif ($_POST[proj_id] > 0) { $proj_id = intval($_POST[proj_id]); }
elseif ($proj_id > 0) { $proj_id = intval(proj_id); }

ProjectSwitcher("project_view",$proj_id,0,0);

echo "<div class=\"menu_bar\">";
echo "<a href=\"#\" onclick=\"itemSwitch(1); return false;\" class=\"menu_tab\">Main</a>";
echo "<a href=\"#\" onclick=\"itemSwitch(4); return false;\" class=\"menu_tab\">Contacts</a>";
echo "<a href=\"#\" onclick=\"itemSwitch(8); return false;\" class=\"menu_tab\">Tasks</a>";
echo "<a href=\"#\" onclick=\"itemSwitch(2); return false;\" class=\"menu_tab\">Drawings</a>";

echo "<a href=\"index2.php?page=project_checklist&amp;proj_id=$proj_id\" class=\"menu_tab\">Checklist</a>";

echo "<a href=\"index2.php?page=project_planningcondition_list&amp;proj_id=$proj_id\" class=\"menu_tab\">Planning Tracker</a>";

if ($user_usertype_current > 3) {
	echo "<a href=\"#\" onclick=\"itemSwitch(5); return false;\" class=\"menu_tab\">Fees</a>";
}
if ($user_usertype_current >= 4) {
	echo "<a href=\"#\" onclick=\"itemSwitch(6); return false;\" class=\"menu_tab\">Expenses</a>";
if ( $module_invoices == 1) { echo "<a href=\"#\" onclick=\"itemSwitch(7); return false;\" class=\"menu_tab\">Invoices</a>"; }
}

echo "<a href=\"index2.php?page=project_blog_list&amp;proj_id=$proj_id\" class=\"menu_tab\">Journal</a>";

echo "<a href=\"#\" onclick=\"itemSwitch(3); return false;\" class=\"menu_tab\">Particulars</a>";
echo "</div>";


echo "<div id=\"item_switch_1\">"; ProjectSubMenu($proj_id,$user_usertype_current,"project_edit"); ProjectList($proj_id); echo "</div>";

echo "<div id=\"item_switch_2\">"; ProjectDrawingList($proj_id,$user_usertype_current); echo "</div>";

echo "<div id=\"item_switch_3\">"; ProjectParticulars($proj_id,$user_usertype_current); echo "</div>";

echo "<div id=\"item_switch_4\">"; ProjectSubMenu($proj_id,$user_usertype_current,"project_contacts"); ProjectContacts($proj_id,$user_usertype_current); echo "</div>";

if ($user_usertype_current > 2) {
	
			if ($module_fees == 1) {
				echo "<div id=\"item_switch_5\">";
					ProjectSubMenu($proj_id,$user_usertype_current,"project_fee"); ProjectFees($proj_id,$user_usertype_current);
				echo "</div>";				
			}
			
			if ($module_expenses == 1) {
			
				echo "<div id=\"item_switch_6\">";
					include("inc_files/inc_project_expenses.php");
				echo "</div>";
				
			}
			
			if ($module_invoices == 1) {

				echo "<div id=\"item_switch_7\">";
					ProjectSubMenu($proj_id,$user_usertype_current,"project_invoice"); ProjectInvoices($proj_id,"project_invoice");
				echo "</div>";
				
			}
			
			if ($module_tasks == 1) {
			
				echo "<div id=\"item_switch_8\">";
					ProjectSubMenu($proj_id,$user_usertype_current,"project_tasks"); ProjectTasks($proj_id);
				echo "</div>";
					
			}

}



if ($_GET[show] == "contacts") { $show_contact = "block"; $show_default = "none"; } else { $show_contact = "none"; $show_default = "block";  }

echo "
		<script type=\"text/javascript\">
		document.getElementById(\"item_switch_1\").style.display = \"$show_default\";
		document.getElementById(\"item_switch_2\").style.display = \"none\";
		document.getElementById(\"item_switch_3\").style.display = \"$show_contact\";
		document.getElementById(\"item_switch_4\").style.display = \"none\";
		document.getElementById(\"item_switch_8\").style.display = \"none\";
		";
		
if ($user_usertype_current > 2) {
				echo "
				document.getElementById(\"item_switch_5\").style.display = \"none\";
				document.getElementById(\"item_switch_6\").style.display = \"none\";
				document.getElementById(\"item_switch_7\").style.display = \"none\";			
				";
}
		
		echo "</script>";



