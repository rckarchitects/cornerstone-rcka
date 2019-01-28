<?php

$removestrings_all = array("<",">","|");
$removestrings_phone = array("+44","(",")");

$currency_symbol = array("�","�");
$currency_text = array("&pound;","&euro;");
$currency_junk = array("�","�");

$text_remove = array("�","�");

ini_set("upload_max_filesize","10M");


function PinnedJournalEntries($user_usertype_current) {
		
		global $conn;
	
		$sql = "SELECT blog_id, blog_title FROM intranet_projects_blog WHERE blog_pinned = 1 AND (blog_access <= " . intval($user_usertype_current) . " OR blog_access IS NULL) ORDER BY blog_date DESC";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		if (mysql_num_rows($result) > 0) {
			$array_pages = array();
			$array_title = array();
			$array_access = array();
			$array_images = array();
			while ($array = mysql_fetch_array($result)) {
					$array_pages[] = "index2.php?page=project_blog_view&amp;blog_id=" . $array['blog_id'];
					$array_title[] = $array['blog_title'];
					$array_images[] = "button_list.png";
					$array_access[] = 1;
			}
			SideMenu ("Pinned Journal Entries", $array_pages, $array_title, $array_access, $user_usertype_current,$array_images, "r");
		}
	
}

function ProjectActionStream ($proj_id) {
	
	global $conn;
	
	$proj_id = intval($proj_id);
	
	$sql = "SELECT user_name_first, user_name_second, alert_message, alert_timestamp FROM intranet_alerts LEFT JOIN intranet_user_details ON user_id = alert_user WHERE alert_project = $proj_id ORDER BY alert_timestamp DESC";
	
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	if (mysql_num_rows($result) > 0) {
		
		echo "<table>";
		
		while ($array = mysql_fetch_array($result)) {
			
			echo "<tr><td style=\"max-width: 20%;\">" . DayLink($array['alert_timestamp'],1) . "</td><td style=\"max-width: 50%;\">" . $array['alert_message'] . "</td><td style=\"text-align: right;\">" . $array['user_name_first'] . "&nbsp;" . $array['user_name_second'] . "</td></tr>";
			
		}
		
		echo "</table>";
		
	} else {
		
		echo "<p>No actions found.</p>";
		
	}
	
}

function TeamMenu($user_usertype_current) {
	
	global $conn;

	$sql = "SELECT * FROM intranet_user_details WHERE user_active = 1 order by user_name_second";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	$array_pages = array();
	$array_title = array();
	$array_access = array();
	$array_images = array();

	while ($array = mysql_fetch_array($result)) {

		$user_name_first = $array['user_name_first'];
		$user_name_second = $array['user_name_second'];
		$user_num_mob = $array['user_num_mob'];
		$user_num_home = $array['user_num_home'];
		$user_num_extension = $array['user_num_extension'];
		$user_email = $array['user_email'];
		$user_id = $array['user_id'];
		$user_usertype = $array['user_usertype'];
		
		$user_name = $user_name_first . " " . $user_name_second;
		
		if ($user_usertype_current > 4) { $user_name = $user_name . "&nbsp;[" . $user_usertype . "]"; }

		$array_pages[] = "index2.php?page=user_view&amp;user_id=" . $user_id;
		$array_title[] = $user_name;
		$array_images[] = "button_list.png";
		$array_access[] = 1;
				
	}

		
		$array_pages[] = "index2.php?page=user_list";
		$array_title[] = "List All Users";
		$array_images[] = "button_list.png";
		$array_access[] = 4;
		
		$array_pages[] = "index2.php?page=user_edit&amp;user_add=true";
		$array_title[] = "Add New User";
		$array_images[] = "button_new.png";
		$array_access[] = 4;
		
				
	SideMenu ("Team", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images, "r");
	
}

function Logo($settings_style,$settings_name) {

	$logo = "skins/" . $settings_style . "/images/logo.png";

	echo "<div id=\"maintitle\" class=\"HideThis\">";

			echo "<a href=\"index2.php\" class=\"image\">";

			if (file_exists($logo)) {
					echo "<img src=\"$logo\" alt=\"$settings_name\" style=\"text-align: center; width: 150px;\" />";
			} else {
					echo $settings_name;
			}

			echo "</a>";

	echo "</div>";

}

function ProjectListLPA() {
	
	GLOBAL $conn;
	$sql = "SELECT proj_lpa FROM intranet_projects WHERE proj_lpa IS NOT NULL GROUP BY proj_lpa";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		echo "<datalist id=\"proj_lpa\">";
		
		while ($array = mysql_fetch_array($result)) {
			echo "<option value=\"" . $array['proj_lpa'] . "\"></option>";
		}
		
		echo "</datalist>";
		
		
	}
	
}

function DataList($field,$table) {
	
	GLOBAL $conn;
	$sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $field . " IS NOT NULL GROUP BY " . $field;
	$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		echo "<datalist id=\"" . $field . "\">";
		
		while ($array = mysql_fetch_array($result)) {
			echo "<option value=\"" . $array[$field] . "\"></option>";
		}
		
		echo "</datalist>";
		
		
	}
	
}

function ProjectProcurement($proj_procure,$proj_id) {
	
		GLOBAL $conn;

		
		if (intval($proj_id) > 0 && intval($proj_procure) > 0) {
			$sql = "SELECT * FROM intranet_procure WHERE procure_id = " . $proj_procure;
		} else {
			$sql = "SELECT * FROM intranet_procure order by procure_title";
		}
		
		$result = mysql_query($sql, $conn) or die(mysql_error());
			
		if (intval($proj_id) > 0 && intval($proj_procure) > 0) {
			
			$array = mysql_fetch_array($result);
			$procure_title = $array['procure_title'];
				
			return $procure_title;
			
		} else {
		
			echo "<p>Procurement Method<br /><select name=\"proj_procure\" class=\"inputbox\">";

			echo "<option value=\"\">-- N/A --</option>";

			while ($array = mysql_fetch_array($result)) {
			$procure_id = $array['procure_id'];
			$procure_title = $array['procure_title'];
			$procure_desc = $array['procure_desc'];

			echo "<option value=\"$procure_id\" class=\"inputbox\"";
			if ($procure_id == $proj_procure) {
			echo " selected";
			}
			echo ">".$procure_title."</option>";
			}

			echo "</select></p>";		
		
		}



}

function PresentCost($input) { 
		$output = "&pound;" . numberformat($input, 2);
		return $output;
}	


function StageTabs ($group_id_selected, $proj_id, $page, $filter) {
	GLOBAL $conn;
	
	if ($filter == "edit") {
	$sql_group = "SELECT group_id, group_code, group_description FROM intranet_timesheet_group WHERE group_project = 1 AND group_active = 1 ORDER BY group_order";
	} else {
	$sql_group = "SELECT * FROM intranet_timesheet_group, intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON item_id = checklist_item WHERE group_project = 1 AND group_active = 1 AND checklist_project = $proj_id AND item_stage = group_id GROUP BY group_id ORDER BY group_order";
	}
	$result_group = mysql_query($sql_group, $conn) or die(mysql_error());
	if (mysql_num_rows($result_group) > 0) {
		
		echo "<div class=\"submenu_bar\">";
			while ($array_group = mysql_fetch_array($result_group)) {
				$group_id = $array_group['group_id'];
				$group_code = $array_group['group_code'];
				if ($group_id_selected == $group_id) { $group_code = "<strong>$group_code</strong>";
					echo "<a href=\"" . $page . "&amp;group_id=" . $group_id . "\" class=\"submenu_bar\">$group_code</a>";
					$group_description = $group_code . " " . $array_group['group_description'];
				} else {
					echo "<a href=\"" . $page . "&amp;group_id=" . $group_id . "\" class=\"submenu_bar\">$group_code</a>";
				}
			}
		echo "</div>";
		
		echo "<h3>" . $group_description . "</h3>";
		
		
	}
}


function SelectStage($item_stage, $bg) {

		GLOBAL $conn;

		$sql_stages = "SELECT * FROM intranet_timesheet_group WHERE group_active = 1 ORDER BY group_order";
		$result_stages = mysql_query($sql_stages, $conn) or die(mysql_error());
		
		
		echo "Select Project Stage: <select name=\"item_stage\">";
		
		echo "<option value=\"\">-- None --</option>";
		
		while ($array_stages = mysql_fetch_array($result_stages)) {
			
			if ($item_stage == $array_stages['group_id'] ) { $selected = " selected=\"selected\" "; } else { unset($selected); }
			
			echo "<option value=\"" . $array_stages['group_id'] . "\"" . $selected . ">" . $array_stages['group_code'] . ": " . $array_stages['group_description'] . "</option>";
		}
				
		echo "</select>";
		


}

function GetProjectInfo($proj_id) {
					if ($proj_id != NULL) {
						GLOBAL $conn;
						$sql = "SELECT proj_num, proj_name FROM intranet_projects WHERE proj_id = $proj_id";
						$result = mysql_query($sql, $conn) or die(mysql_error());
						$array = mysql_fetch_array($result);
						$proj_num = $array['proj_num'];
						$proj_name = $array['proj_name'];
						$proj_title = $proj_num . " " . $proj_name;
						echo "<a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">" . $proj_title . "</a>";
					}
}

function GetProjectName($proj_id) {
					if ($proj_id != NULL) {
						GLOBAL $conn;
						$sql = "SELECT proj_num, proj_name FROM intranet_projects WHERE proj_id = $proj_id";
						$result = mysql_query($sql, $conn) or die(mysql_error());
						$array = mysql_fetch_array($result);
						$proj_num = $array['proj_num'];
						$proj_name = $array['proj_name'];
						$proj_title = $proj_num . " " . $proj_name;
					}
					
					return $proj_title;
}

function SearchPanel($user_usertype_current,$search_id) {
	
	
	echo "<div><form action=\"index2.php?page=search\" method=\"post\">";
	
	
	
	if ($_POST[tender_search] == "yes") { $checked1 = " checked = \"checked\" "; } else { unset($checked1) ; }
	if ($_POST[search_phrase] == "yes") { $checked2 = " checked = \"checked\" "; } else { unset($checked2) ; }

	echo "<p style=\"float: left;\"><span class=\"heading_side_left\">Search<input type=\"search\" name=\"keywords\" value=\"$_POST[keywords]\" id=\"$search_id\" onClick=\"SelectAll('$search_id');\" style=\"width: 100%;\" /></span></p>";
	
	
	
	if ($user_usertype_current > 1) {
		echo "<p style=\"float: left; margin-right: 20px;\"><input type=\"checkbox\" name=\"tender_search\" value=\"yes\" $checked1 />&nbsp;<span class=\"minitext\">Search tenders?</span><br />";
	} else {
		echo "<p style=\"float: left; margin-right: 20px;\">";
	}
	
	echo "<input type=\"checkbox\" name=\"search_phrase\" value=\"yes\" $checked2 />&nbsp;<span class=\"minitext\">Search Complete Phrase?</span></p>";
	
	echo "<p style=\"float: left;\"><input type=\"submit\" value=\"Go\" /></p>";
	
	echo "</form></div>";
	
	
}

function ProjectTitle($show,$proj_id) {

	GLOBAL $conn;
	
	if ($proj_id > 0) { $proj_id = intval($proj_id); }
	elseif ($_GET[proj_id] > 0) { $proj_id = intval($_GET[proj_id]); }
	elseif ($_POST[proj_id] > 0) { $proj_id = intval($_POST[proj_id]); }
	else { unset($proj_id); }
	
	if ($proj_id > 0) {
	
		$sql = "SELECT proj_name, proj_num FROM intranet_projects WHERE proj_id = $proj_id LIMIT 1";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		
		if ($show == 1) {
			
			echo "<h2><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a></h2>";
			
		} elseif ($show == 2) {
			
			echo "<h1><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a></h1>";
			
		} else {
		
		$output = array($proj_id,$proj_num,$proj_name);
		return $output;
		
		}
		
	}

	


}

function ProjectSubMenu($proj_id,$user_usertype_test,$page,$level) {
	
				global $user_usertype_current;
				global $user_id_current;

				$array_menu_page = array();
				$array_menu_text = array();
				$array_menu_image = array();
				$array_menu_usertype = array();
				
				$proj_id = intval($proj_id);
				$level = intval($level);

	if ($page == "project_view" && $level == 1 && intval($proj_id) > 0) {
		
				$array_menu_page[] = "index2.php?page=project_view&amp;proj_id=$proj_id";
				$array_menu_text[] = "Project Home";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
			
				$array_menu_page[] = "index2.php?page=project_contacts&amp;proj_id=$proj_id";
				$array_menu_text[] = "Contacts";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
			
				$array_menu_page[] = "index2.php?page=tasklist_project&amp;proj_id=$proj_id";
				$array_menu_text[] = "Tasks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=drawings_list&amp;proj_id=$proj_id";
				$array_menu_text[] = "Drawings";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
			
				$array_menu_page[] = "index2.php?page=project_checklist&amp;proj_id=$proj_id";
				$array_menu_text[] = "Checklist";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
			
				$array_menu_page[] = "index2.php?page=project_planningcondition_list&amp;proj_id=$proj_id";
				$array_menu_text[] = "Planning Tracker";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=project_blog_list&amp;proj_id=$proj_id";
				$array_menu_text[] = "Journal";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=project_fees&amp;proj_id=$proj_id";
				$array_menu_text[] = "Fees";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_list&amp;proj_id=$proj_id";
				$array_menu_text[] = "Invoices";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				
				$array_menu_page[] = "index2.php?page=project_particulars&amp;proj_id=$proj_id";
				$array_menu_text[] = "Particulars";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				
				$array_menu_page[] = "index2.php?page=project_risks&amp;proj_id=$proj_id";
				$array_menu_text[] = "Risks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=project_actionstream&amp;proj_id=$proj_id";
				$array_menu_text[] = "Action Stream";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
	} elseif ($page == "project_view" && $level == 2 && intval($proj_id) > 0) {

				$array_menu_page[] = "index2.php?page=project_edit&amp;status=edit&amp;proj_id=$proj_id";
				$array_menu_text[] = "Edit Project";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 3;
			
				$array_menu_page[] = "index2.php?page=project_edit&amp;status=add";
				$array_menu_text[] = "Add Project";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 0;
			
				$array_menu_page[] = "pdf_project_sheet.php?proj_id=$proj_id";
				$array_menu_text[] = "Project Sheet";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;		
				
		
	} elseif ($page == "project_invoice" && $level == 2 && intval($proj_id) > 0) {

				$array_menu_page[] = "index2.php?page=timesheet_invoice_items_edit&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add Invoice Item";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;

				$array_menu_page[] = "index2.php?page=timesheet_invoice_edit&amp;invoice_id=" . intval($_GET[invoice_id]);
				$array_menu_text[] = "Edit Invoice";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 3;
				
	} elseif ($page == "project_risks" && $level == 2 && intval($proj_id) > 0) {

				$array_menu_page[] = "index2.php?page=project_risks&amp;action=list&amp;view=list&amp;proj_id=$proj_id";
				$array_menu_text[] = "List Risks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;

				$array_menu_page[] = "index2.php?page=project_risks&amp;action=add&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add New Risk";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;

				if (intval($_GET[risk_id]) > 0) {
					$array_menu_page[] = "index2.php?page=project_risk&amp;action=edit&amp;risk_id=". intval($_GET[risk_id]);
					$array_menu_text[] = "Edit Risk";
					$array_menu_image[] = "button_edit.png";
					$array_menu_usertype[] = 3;
				}
				
				$array_menu_page[] = "pdf_risks.php?proj_id=" . $proj_id;
				$array_menu_text[] = "Risk Register";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				
	} elseif ($page == "project_fee" OR $page == "project_timesheet_view" && intval($proj_id) > 0) {

				$array_menu_page[] = "index2.php?page=project_fees&proj_id=$proj_id";
				$array_menu_text[] = "List Fee Stages";
				$array_menu_image[] = "button_lsit.png";
				$array_menu_usertype[] = 3;
	
				$array_menu_page[] = "index2.php?page=project_hourlyrates_view&amp;proj_id=$proj_id";
				$array_menu_text[] = "Edit Hourly Rates";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=project_timesheet_view&amp;proj_id=$proj_id";
				$array_menu_text[] = "View Expenditure";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=timesheet_fees_edit&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add Fee Stage";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "pdf_fee_drawdown.php?proj_id=$proj_id";
				$array_menu_text[] = "View Fee Drawdown";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "pdf_fee_drawdown.php?proj_id=$proj_id&amp;showinvoices=yes";
				$array_menu_text[] = "View Fee Drawdown (with invoices)";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "http://intranet.rcka.co/pdf_project_performance_summary.php?proj_id=" . intval($proj_id);
				$array_menu_text[] = "Project Performance Summary";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 4;
				
	} elseif ($page == "project_contacts" && intval($proj_id) > 0) {
	
				$array_menu_page[] = "index2.php?page=project_contacts&amp;contact_proj_add=add&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add Project Contact";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;
				
	} elseif (($page == "project_blog" OR $page == "project_blog_list" OR $page == "project_blog_edit")  && intval($proj_id) > 0 ) {
	
				$array_menu_page[] = "index2.php?page=project_blog_edit&status=add&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add Journal Entry";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_blog_list&amp;proj_id=$proj_id";
				$array_menu_text[] = "List Journal Entries";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
	} elseif ($page == "project_tasks" && intval($proj_id) > 0) {
	
				$array_menu_page[] = "index2.php?page=tasklist_edit&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add New Task";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;
		
				$array_menu_page[] = "index2.php?page=tasklist_project&amp;proj_id=$proj_id";
				$array_menu_text[] = "Outstanding Tasks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=tasklist_project&amp;view=complete&amp;proj_id=$proj_id";
				$array_menu_text[] = "Completed Tasks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
	} elseif ($page == "tasklist_view") {
	
				$array_menu_page[] = "index2.php?page=tasklist_edit";
				$array_menu_text[] = "Add New Task";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;
		
				$array_menu_page[] = "index2.php?page=tasklist_view";
				$array_menu_text[] = "Outstanding Tasks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=tasklist_view&amp;view=complete";
				$array_menu_text[] = "Completed Tasks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
	} elseif ( $page == "date_list") {

				$array_menu_page[] = "index2.php?page=date_list";
				$array_menu_text[] = "List Future Dates";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=date_list&amp;filter=2";
				$array_menu_text[] = "List Past Dates";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;

				$array_menu_page[] = "index2.php?page=date_edit";
				$array_menu_text[] = "Add New Dates";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;

	} elseif ( $page == "project_expenses" && intval($proj_id) > 0) {

				$array_menu_page[] = "timesheet_expense_edit&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add Expenses";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;


	} elseif ( $page == "drawings_list" && $level == 2 && intval($proj_id) > 0) {

				$array_menu_page[] = "pdf_drawing_list.php?proj_id=$proj_id";
				$array_menu_text[] = "Drawing Schedule";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "pdf_drawing_matrix.php?proj_id=$proj_id";
				$array_menu_text[] = "Drawing Matrix";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "pdf_drawing_matrix.php?proj_id=$proj_id";
				$array_menu_text[] = "Drawing Matrix";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				

	} elseif ( $page == "planning_conditions" && $level == 2 && intval($proj_id) > 0) {

				if ($_GET[showdetail] == 1) {
					$array_menu_page[] = "index2.php?page=project_planningcondition_list&amp;proj_id=$proj_id";
					$array_menu_text[] = "Simple List";
					$array_menu_image[] = "button_list.png";
					$array_menu_usertype[] = 2;

				} else {
					$array_menu_page[] = "index2.php?page=project_planningcondition_list&amp;proj_id=$proj_id&amp;showdetail=1";
					$array_menu_text[] = "Detailed List";
					$array_menu_image[] = "button_list.png";
					$array_menu_usertype[] = 2;
				}
				
				$array_menu_page[] = "index2.php?page=project_planningcondition_edit&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add New";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "pdf_planning_conditions.php?proj_id=$proj_id";
				$array_menu_text[] = "Condition Schedule";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				

	} elseif ( $page == "project_checklist" && $level == 2 && intval($proj_id) > 0) {
		
				$group_id = intval($_GET[group_id]);
				
				if ($_GET[page] == "project_checklist_edit") {
					
					$array_menu_page[] = "index2.php?page=project_checklist&amp;proj_id=$proj_id&amp;group_id=$group_id";
					$array_menu_text[] = "Back to List";
					$array_menu_image[] = "button_list.png";
					$array_menu_usertype[] = 2;					
				
				}

				$array_menu_page[] = "index2.php?page=project_checklist_edit&amp;proj_id=$proj_id&amp;group_id=$group_id";
				$array_menu_text[] = "Edit Group";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 2;		
				
				$array_menu_page[] = "pdf_project_checklist_stages.php?proj_id=$proj_id";
				$array_menu_text[] = "Stages";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "pdf_project_checklist.php?proj_id=$proj_id";
				$array_menu_text[] = "Checklist";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				
	} elseif ( $page == "phonemessage_view") {
		
				if (intval($_GET[user_id]) > 0) { $user_id = intval($_GET[user_id]); } else { $user_id = intval($_COOKIE[user]); }
		
				$array_menu_page[] = "index2.php?page=phonemessage_view&amp;status=outstanding&amp;user_id=$user_id";
				$array_menu_text[] = "Outstanding Messages";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;	

				$array_menu_page[] = "index2.php?page=phonemessage_view&amp;status=all&amp;user_id=$user_id";
				$array_menu_text[] = "All Messages";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=phonemessage_view&amp;status=user&amp;user_id=$user_id";
				$array_menu_text[] = "Messages for Others";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;	
				
	
		
	} elseif ( $page == "project_drawings" && intval($proj_id) > 0) {
		
				if (intval($_GET[proj_id]) > 0) { $proj_id = intval($_GET[proj_id]); }
		
				$array_menu_page[] = "index2.php?page=project_view&amp;proj_id=$proj_id";
				$array_menu_text[] = "Project Home";
				$array_menu_image[] = "button_home.png";
				$array_menu_usertype[] = 1;	

				$array_menu_page[] = "index2.php?page=drawings_list&amp;proj_id=$proj_id";
				$array_menu_text[] = "Drawing List";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=drawings_edit&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add New Drawing";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=drawings_issue&amp;proj_id=$proj_id";
				$array_menu_text[] = "Issue Drawings";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
	
				$array_menu_page[] = "index2.php?page=drawings_issues&amp;proj_id=$proj_id";
				$array_menu_text[] = "List Drawing Issues";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
		
	} elseif ( $page == "contacts_admin"  && $level == 1) {
	
				$array_menu_page[] = "index2.php?page=contacts_view";
				$array_menu_text[] = "All Contacts";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;	
				
				$array_menu_page[] = "index2.php?page=contacts_edit&amp;status=add";
				$array_menu_text[] = "Add Contact";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;	

				$array_menu_page[] = "index2.php?page=contacts_company_edit&amp;status=add";
				$array_menu_text[] = "Add Company";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=contacts_add_sector";
				$array_menu_text[] = "Add Sector";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=contacts_discipline_list";
				$array_menu_text[] = "List Disciplines";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
	
				$array_menu_page[] = "index2.php?page=contacts_company_merge";
				$array_menu_text[] = "Merge Companies";
				$array_menu_image[] = "button_settings.png";
				$array_menu_usertype[] = 4;
				
	} elseif ( $page == "company_admin") {
		
				if (intval($_GET[company_id]) > 0) { $company_id = intval($_GET[company_id]);
	
					$array_menu_page[] = "index2.php?page=contacts_company_edit&amp;company_id=" . $company_id . "&amp;status=edit";
					$array_menu_text[] = "Edit Company";
					$array_menu_image[] = "button_edit.png";
					$array_menu_usertype[] = 2;
				
				}

	} elseif ( $page == "contacts_admin" OR $page == "contacts_view_detailed"  && $level == 2) {
		
				if (intval($contact_id) > 0) { $contact_id = intval(contact_id); } elseif (intval($_POST[contact_id]) > 0) { $contact_id = intval($_POST[contact_id]); } elseif (intval($_GET[contact_id]) > 0) { $contact_id = intval($_GET[contact_id]); }
				
					if (intval($contact_id) > 0) {
		
						$array_menu_page[] = "index2.php?page=contacts_edit&amp;contact_id=" . $contact_id."&amp;status=edit";
						$array_menu_text[] = "Edit Contact";
						$array_menu_image[] = "button_edit.png";
						$array_menu_usertype[] = 1;
						
						$array_menu_page[] = "index2.php?page=project_blog_edit&amp;status=add&amp;contact_id=" . $contact_id;
						$array_menu_text[] = "Add Journal Entry";
						$array_menu_image[] = "button_new.png";
						$array_menu_usertype[] = 1;
						
						$array_menu_page[] = "vcard.php?contact_id=" . $contact_id;
						$array_menu_text[] = "VCard File";
						$array_menu_image[] = "button_list.png";
						$array_menu_usertype[] = 1;
						
						//if ($module_onepage == 1) {
							$array_menu_page[] = ContactOnePage($contact_id);
							$array_menu_text[] = "Add to OnepageCRM";
							$array_menu_image[] = "button_new.png";
							$array_menu_usertype[] = 1;
						//}
					
					}
				
	} elseif ( $page == "timesheet_admin") {
		
			if (intval($_GET[user_id]) > 0) { $user_id = intval($_GET[user_id]); } else { $user_id = intval($_COOKIE[user]); }
		
				$array_menu_page[] = "index2.php?page=timesheet&user_view=$user_id";
				$array_menu_text[] = "Timesheets Home";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=timesheet_analysis";
				$array_menu_text[] = "Timesheet Analysis";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				
				$array_menu_page[] = "index2.php?page=timesheet_settings";
				$array_menu_text[] = "Timesheet Settings";
				$array_menu_image[] = "button_settings.png";
				$array_menu_usertype[] = 4;
				
	} elseif ( $page == "timesheet_settings") {
		
				$array_menu_page[] = "index2.php?page=timesheet_rates_hourly";
				$array_menu_text[] = "Hourly Rates";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				
				$array_menu_page[] = "index2.php?page=timesheet_rates_overhead";
				$array_menu_text[] = "Overhead Rates";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				
				$array_menu_page[] = "index2.php?page=timesheet_rates_project";
				$array_menu_text[] = "Project Rates";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
		
	} elseif ( $page == "invoice_admin") {
		
				$array_menu_page[] = "index2.php?page=timesheet_invoice";
				$array_menu_text[] = "Invoices Home";
				$array_menu_image[] = "button_home.png";
				$array_menu_usertype[] = 3;
			
				$array_menu_page[] = "index2.php?page=timesheet_invoice_edit";
				$array_menu_text[] = "Add Invoice";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_view_outstanding&amp;status=paid";
				$array_menu_text[] = "Paid Invoices";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_view_outstanding";
				$array_menu_text[] = "Oustanding Invoices";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_view_outstanding&amp;status=current";
				$array_menu_text[] = "Current Invoices";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_view_outstanding&amp;status=future";
				$array_menu_text[] = "Future Invoices";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_view_month";
				$array_menu_text[] = "Invoices by Month";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				
	} elseif ( $page == "project_ambition") {
				
				$array_menu_page[] = "index2.php?page=project_ambition_schedule&amp;type=" . $_GET[type];
				$array_menu_text[] = "List Active Projects";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype = 2;
				
				$array_menu_page[] = "index2.php?page=project_ambition_schedule&amp;filter=all&amp;type=" . $_GET[type];
				$array_menu_text[] = "List All Projects";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;			
			
	} elseif ( $page == "media") {
				
				$array_menu_page[] = "index2.php?page=media";
				$array_menu_text[] = "Media Library";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=media&action=upload";
				$array_menu_text[] = "Upload Media";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;			
		
	} elseif ( $page == "user_admin" && $level == 1) {
				
				$array_menu_page[] = "index2.php?page=user_edit&amp;user_add=true";
				$array_menu_text[] = "Add User";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=user_list";
				$array_menu_text[] = "List Users";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;		
				
				$array_menu_page[] = "index2.php?page=phonemessage_edit&amp;status=new&amp;user_id=" . intval($_GET[user_id]);
				$array_menu_text[] = "Add Phone Message";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;		
		
	} elseif ( $page == "user_admin" && $level == 2) {
				
				$array_menu_page[] = "index2.php?page=user_edit&status=edit&amp;user_id=" . intval($_GET[user_id]);
				$array_menu_text[] = "Edit User";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=user_change_password&amp;user_id=" . intval($_GET[user_id]);
				$array_menu_text[] = "Change Password";
				$array_menu_image[] = "button_edit.png";
				if (intval($_COOKIE[user]) == intval($_GET[user_id])) { $array_menu_usertype[] = 1; } else { $array_menu_usertype[] = 4; }
		
	} elseif ( $page == "manual_page" && $level == 1) {
				
				$array_menu_page[] = "index2.php?page=manual_page";
				$array_menu_text[] = "Contents";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=manual_page&amp;action=add";
				$array_menu_text[] = "Add Page";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
				
	} elseif ( $page == "manual_page" && $level == 2 && intval($_GET[manual_id]) > 0) {
				
				$array_menu_page[] = "index2.php?page=manual_page&amp;action=edit&amp;manual_id=" . intval($_GET[manual_id]) ;
				$array_menu_text[] = "Edit";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 2;
				
	} elseif ( $page == "blog_view" && $level == 2 && intval($_GET[blog_id]) > 0) {
				
				$array_menu_page[] = "index2.php?page=project_blog_list&amp;proj_id=" . intval($_GET[proj_id]) ;
				$array_menu_text[] = "List All";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_blog_edit&amp;status=add&amp;proj_id=" . intval($_GET[proj_id]) ;
				$array_menu_text[] = "Add Journal Entry";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_blog_edit&amp;status=edit&amp;proj_id=" . intval($proj_id) . "&amp;blog_id=" . intval($_GET[blog_id]);
				$array_menu_text[] = "Edit Journal Entry";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 2;
				
	} elseif ( $page == "blog_list" && intval($proj_id) > 0) {
		
				$array_menu_page[] = "index2.php?page=project_blog_list&amp;proj_id=" . intval($_GET[proj_id]) ;
				$array_menu_text[] = "List All";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_blog_edit&amp;status=add&amp;proj_id=" . intval($_GET[proj_id]) ;
				$array_menu_text[] = "Add Journal Entry";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;		
		
		
	} elseif ( $page == "fee_stage_list") {
		
		
				$array_menu_page[] = "pdf_jobbook.php" ;
				$array_menu_text[] = "Printable Version";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 1;
		
		
	}


		$current_url =  htmlspecialchars ( substr($_SERVER[REQUEST_URI],1) );
		
		$count = 0;
		
		if ($level == 1) { $level_style = "menu_bar"; $tab_style = "menu_tab"; } else { $level_style = "submenu_bar"; $tab_style = "submenu_bar"; }
		
		if (count($array_menu_page) > 0) {
			
		
				echo "<div class=\"" . $level_style . "\" id=\"" . $array_menu_page[$count] . "\">";
				

				foreach ($array_menu_page AS $menu_link) {
					
							if ($user_usertype_current >= $array_menu_usertype[$count]) {
					
									if ($current_url != $array_menu_page[$count]) {
										 
										echo "<a href=\"$array_menu_page[$count]\" class=\"" . $tab_style . "\">";
										if ($array_menu_image[$count]) { echo "<img src=\"images/$array_menu_image[$count]\" />&nbsp;"; }
										echo $array_menu_text[$count];
										echo "</a>";
										
									} else {
										
										echo "<a href=\"$array_menu_page[$count]\" class=\"" . $tab_style . "\" style=\"background-color: white;\">";
										if ($array_menu_image[$count]) { echo "<img src=\"images/$array_menu_image[$count]\" />&nbsp;"; }
										echo $array_menu_text[$count];
										echo "</a>";
										
									}
									
							}
				 
						$count++;
				 
				}

				echo "</div>";
		
		}


		
}

function ProjectList($proj_id) {
	


	global $conn;
	
	$sql = "SELECT * FROM intranet_projects where proj_id = $_GET[proj_id]";
$result = mysql_query($sql, $conn);
$array = mysql_fetch_array($result);

$proj_num = $array['proj_num'];
$proj_name = $array['proj_name'];
$proj_address_1 = $array['proj_address_1'];
$proj_address_2 = $array['proj_address_2'];
$proj_address_3 = $array['proj_address_3'];
$proj_address_town = $array['proj_address_town'];
$proj_address_county = $array['proj_address_county'];
$proj_address_country = $array['proj_address_country'];
$proj_address_postcode = $array['proj_address_postcode'];
$proj_client_contact_id = $array['proj_client_contact_id'];

$proj_rep_black = $array['proj_rep_black'];
$proj_active = $array['proj_active'];
$proj_desc = $array['proj_desc'];
$proj_riba = $array['proj_riba'];
$proj_type = $array['proj_type'];
$proj_riba_begin = $array['proj_riba_begin'];
$proj_riba_conclude = $array['proj_riba_conclude'];
$proj_procure = $array['proj_procure'];
$proj_conc = $array['proj_conc'];
$proj_value = $array['proj_value'];
$proj_value_type = $array['proj_value_type'];
$proj_id = $array['proj_id'];

$proj_date_proposal = $array['proj_date_proposal'];
$proj_date_appointment = $array['proj_date_appointment'];
$proj_date_commence = $array['proj_date_start'];
$proj_date_complete = $array['proj_date_complete'];

$proj_planning_ref = $array['proj_planning_ref'];
$proj_buildingcontrol_ref = $array['proj_buildingcontrol_ref'];
$proj_fee_percentage = $array['proj_fee_percentage'];

$proj_lpa = $array['proj_lpa'];

$proj_ambition_internal = $array['proj_ambition_internal'];
$proj_ambition_client = $array['proj_ambition_client'];
$proj_ambition_marketing = $array['proj_ambition_marketing'];
$proj_tenant_1 = $array['proj_tenant_1'];
$proj_location = $array['proj_location'];

$proj_info = $array['proj_info'];

// Determine the country
$sql = "SELECT country_printable_name FROM intranet_contacts_countrylist where country_id = '$proj_address_country' LIMIT 1";
$result = mysql_query($sql, $conn);
$array = mysql_fetch_array($result);
$country_printable_name = $array['country_printable_name'];

					

					echo "<table summary=\"Project Information\">";

					echo "<tr><td style=\"width: 40%;\">Site Address</td><td>";

					if ($proj_address_1 != "") { echo $proj_address_1."<br />"; }
					if ($proj_address_2 != "") {echo $proj_address_2."<br />"; }
					if ($proj_address_3 != "") {echo $proj_address_3."<br />"; }
					if ($proj_address_town != "") {echo $proj_address_town."<br />"; }
					if ($proj_address_county != "") {echo $proj_address_county."<br />"; }
					if ($proj_address_postcode != "") {
					    $proj_address_postcode_link = PostcodeFinder($proj_address_postcode);
					    echo "<a href=\"".$proj_address_postcode_link."\">".$proj_address_postcode."</a><br />";
					    }
					if ($country_printable_name != "") {echo $country_printable_name."<br />"; }

					echo "</td></tr>";
					
					if ($proj_type) { echo "<tr><td><a href=\"index2.php?page=project_ambition_schedule&amp;type=project_type\">Project Type</a></td><td>$proj_type</td></tr>"; }

					if ($proj_date_start > 0) { echo "<tr><td  >Project Start Date</td><td  >$proj_date_start</td></tr>"; }
					if ($proj_date_complete > 0) { echo "<tr><td  >Project Completion Date</td><td>" . TimeFormat($proj_date_complete) . "</td></tr>"; }
					if ($proj_lpa) { echo "<tr><td><a href=\"index2.php?page=project_ambition_schedule&amp;type=proj_lpa\">Local Planning Authority (LPA)</a></td><td>" . $proj_lpa . "</td></tr>"; }
					if ($proj_desc) { echo "<tr><td>Project Description</td><td>" . nl2br ($proj_desc) . "</td></tr>"; }
					if ($proj_ambition_internal) { echo "<tr><td><a href=\"index2.php?page=project_ambition_schedule&amp;type=project_ambition\">Project Ambition</a></td><td>" . nl2br ($proj_ambition_internal) . "</td></tr>"; }
					if ($proj_ambition_client) { echo "<tr><td><a href=\"index2.php?page=project_ambition_schedule&amp;type=client_ambition\">Client Ambition</a></td><td>" . nl2br ($proj_ambition_client) . "</td></tr>"; }
					if ($proj_info) { echo "<tr><td><a href=\"index2.php?page=project_ambition_schedule&amp;type=project_information\">Project Information</a></td><td>" . nl2br ($proj_info) . "</td></tr>"; }
					if ($proj_ambition_marketing) { echo "<tr><td><a href=\"index2.php?page=project_ambition_schedule&amp;type=project_marketing\">Marketing Ambition</a></td><td>" . nl2br ($proj_ambition_marketing) . "</td></tr>"; }
				

					if ($proj_procure > 0) {
					echo "<tr><td>Procurement Method</td><td>" . ProjectProcurement($proj_procure, $proj_id) . "</td></tr>";
					}

					if ($proj_value != 0) {
					$proj_value_show = MoneyFormat($proj_value);
					echo "<tr><td>Contract Value</td><td><a href=\"index2.php?page=timesheet_value_view&amp;proj_id=$proj_id\">$proj_value_show</a></td></tr>";
					}
					
					if ($proj_fee_percentage > 0) {
					echo "<tr><td>Fee Percentage</td><td>".$proj_fee_percentage."%</td></tr>";
					echo "<tr><td>Total Fee<br /><span class=\"minitext\">(Assuming 100% of fee)</span></td><td>".MoneyFormat(($proj_value * ($proj_fee_percentage / 100)))."</td></tr>";
					}
					
					
					if ($proj_location) {
						echo "<tr><td><a href=\"index2.php?page=project_ambition_schedule&amp;type=project_location\">Project File Location</a></td><td>$proj_location</td></tr>";
					}

					echo "</table>";
					
}


function ProjectClientList($proj_id,$user_usertype_current) {

global $conn;

		$proj_id = intval($proj_id);

		$sql_project = "SELECT proj_rep_black FROM intranet_projects WHERE proj_id = $proj_id LIMIT 1";
		$result_project = mysql_query($sql_project, $conn);
		$array_project = mysql_fetch_array($project_project);
		$proj_rep_black = $array_project['proj_rep_black'];

					// Pull the details from the contact database

					echo "<h2>Client</h2>";
					echo "<table summary=\"Client Details\">";

						$sql_contact = "SELECT * FROM contacts_contactlist WHERE contact_id = '$proj_client_contact_id' LIMIT 1";
						$result_contact = mysql_query($sql_contact, $conn);
						$array_contact = mysql_fetch_array($result_contact);
						
						$contact_id = $array_contact['contact_id'];
						$contact_namefirst = $array_contact['contact_namefirst'];
						$contact_namesecond = $array_contact['contact_namesecond'];
						$contact_company = $array_contact['contact_company'];
						
				
						// And then pull the company details from the company database if required
						
							if ($contact_company > 0) {
							$sql_company = "SELECT * FROM contacts_companylist WHERE company_id = '$contact_company' LIMIT 1";
							$result_company = mysql_query($sql_company, $conn);
							$array_company = mysql_fetch_array($result_company);
							$company_id = $array_company['company_id'];
							$company_name = $array_company['company_name'];
							}	
						
						// Compile the full name and email address, and then create a mailto link if the email address is returned
						
						$contact_email = $array_contact['contact_email'];
						$print_contact_name = $contact_namefirst." ".$contact_namesecond;

							if (strlen($contact_email) > 3) {
								$print_client_name = "<a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">".$contact_namefirst."&nbsp;".$contact_namesecond."</a>&nbsp;<a href=\"mailto:$contact_email\"><img src=\"images/button_email.png\" alt=\"Send email to $contact_namefirst&nbsp;$contact_namesecond \" /></a>";
							} else {
								$print_client_name = "<a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">".$contact_namefirst."&nbsp;".$contact_namesecond."</a>";
					        }
							if (strlen($company_name) > 1) {
								$print_client_name = $print_client_name."<br /><a href=\"index2.php?page=contacts_company_view&amp;company_id=".$company_id."\">".$company_name."</a>";
							}
							
							if ($proj_client_contact_id > 0) {
								echo "<tr><td style=\"width: 40%;\">Invoices / Accounts</td><td >$print_client_name</td></tr>";
							}
				
					
					echo "</table>";
					
}

function ProjectSelect($proj_id_select,$field_name,$active,$include_null) {
	
		GLOBAL $conn;
		
		if ($active == 1) {
			$proj_id_select_add = "(proj_active = 1 OR proj_active = 0)";
		} else {
			$proj_id_select_add = "proj_active = 1";
		}
		
		$sql = "SELECT * FROM intranet_projects WHERE $proj_id_select_add ORDER BY proj_active DESC, proj_num DESC";
	
		echo "<select name=\"" . $field_name .  "\">";
		
		$active_test = NULL;
		
		if (intval($include_null) > 0) { echo "<option value=\"\" class=\"inputbox\">-- No Project --</option>"; }
		
		$result = mysql_query($sql, $conn) or die(mysql_error());
		while ($array = mysql_fetch_array($result)) {
			
			
			
			if ($active_test != $array['proj_active'] && $array['proj_active'] == 1) { echo "<option disabled=\"disabled\">Active Projects</option>"; $active_test = $array['proj_active']; }
			elseif ($active_test != $array['proj_active'] && $array['proj_active'] == 0) { echo "<option disabled=\"disabled\">Inactive Projects</option>"; $active_test = $array['proj_active']; }
			
				$proj_num = $array['proj_num'];
				$proj_name = $array['proj_name'];
				$proj_id = $array['proj_id'];
				echo "<option value=\"$proj_id\" class=\"inputbox\"";
				if ($proj_id_select == $proj_id) { echo " selected";}
				elseif ($proj_id == $proj_id_page) { echo " selected";}
				echo ">$proj_num $proj_name</option>";
		}
		echo "</select>";
	
	
}

function ProjectSwitcher ($page, $proj_id, $proj_active, $proj_fee) {
	

	if (intval($proj_id) > 0) {
	
					GLOBAL $conn;
					
					$start = NULL;

					echo "<div id=\"project_switcher\" style=\"display: none;\"><form action=\"index2.php\" method=\"get\">";
					echo "<input type=\"hidden\" name=\"page\" value=\"$page\" />";
					
					//if ($proj_active == 1) { $project_filter = $project_filter . "AND proj_active > 0 "; }
					if ($proj_fee == 1) { $project_filter = $project_filter . "AND proj_fee_track > 0 "; }
					
					if ($project_filter) { $project_filter = ltrim($project_filter,"AND "); $project_filter = "WHERE " . $project_filter; }

					$sql_switcher = "SELECT proj_id, proj_name, proj_num, proj_active FROM intranet_projects $project_filter ORDER BY proj_active DESC, proj_num DESC";
					
					$result_switcher = mysql_query($sql_switcher, $conn) or die(mysql_error());
					echo "<select onchange=\"this.form.submit()\" name=\"proj_id\">";
					while ($array_switcher = mysql_fetch_array($result_switcher)) {
					
						if (!$start && $array_switcher['proj_active'] == 1) { echo "<option disabled=\"disabled\">Active Projects</option>"; $start = 1; }
						if ($start == 1 && $array_switcher['proj_active'] == 0) { echo "<option disabled=\"disabled\">Inactive Projects</option>"; $start = 0; }
					
						$proj_id_switcher = $array_switcher['proj_id'];
						$proj_num_switcher = $array_switcher['proj_num'];
						$proj_name_switcher = $array_switcher['proj_name'];
						if ($proj_id == $proj_id_switcher) { $select = " selected=\"selected\" "; } else { unset($select); }
						echo "<option value=\"$proj_id_switcher\" $select>$proj_num_switcher $proj_name_switcher</option>";
					}
					echo "</select>";


					echo "</form></div>";
	}
}

function CreateDays($date,$hour) {

		$date_array = explode("-",$date);
		$d = $date_array[2];
		$m = $date_array[1];
		$y = $date_array[0];

		if ($date == "0000-00-00") { $output = NULL; } else { $output = mktime($hour,0,0,$m,$d,$y); }
		
		return $output;
	
}

function CreateTimeFromDetailedTime($time,$date) {

		$time_array = explode(":",$time);
		$date_array = explode("-",$date);

		$hour = intval($time_array[0]);
		$minute = intval($time_array[1]);
		$second = 0;
		$month = intval($date_array[1]);
		$day = intval($date_array[2]);
		$year = intval($date_array[0]);
		
		$output = mktime($hour,$minute,0,$month,$day,$year);

		return $output;

}


function CreateDateFromTimestamp($timestamp) {

		$output = date("Y-m-d",$timestamp);
		
		return $output;

}

function CreateTimeFromTimestamp($timestamp) {

		$output = date("H:i",$timestamp);
		
		return $output;

}

function CleanUp($input) {
	// global $currency_symbol;
	// global $currency_text;
	global $removestrings_all;
	// $input = str_replace($removestrings_all, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = addslashes($input);
	// $input = str_replace($currency_junk,$currency_text,$input);
	return($input);
}

function CleanUpAddress($input) {
	$input = str_replace($removestrings_all, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = htmlentities($input);
	$input = addslashes($input);
	return($input);
}

function DeCode($input) {
	global $currency_symbol;
	global $currency_text;
	global $currency_junk;
	$input = html_entity_decode($input);
	return($input);
}

function PresentText($input) {
	global $currency_symbol;
	global $currency_text;
	global $currency_junk;
	$input = str_replace($currency_symbol,$currency_junk,$input);
	//$input = htmlentities($input);
	$input = nl2br($input);
	$input = trim($input);
	$string = $input;
	$input = wordwrap($input, 40, "\n", true);
	//$input = preg_replace('/\[(.*?)\]\s*\((.*?)\)/', '<a href="$2">$1</a>', '[text](url)');
	return $input;
	}

function CleanUpNames($input) {
	$input = str_replace($removestrings_all, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = htmlentities($input);
	$input = addslashes($input);
	return($input);
}

function CleanUpEmail($input) {
	$input = strtolower($input);
	$input = str_replace($removestrings_all, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = htmlentities($input);
	return($input);
}

function CleanUpPhone($input) {
	$input = strtolower($input);
	$input = str_replace($removestrings_phone, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = htmlentities($input);
	return($input);
} 

function CleanUpPostcode($input) {
	$input = ucwords(strtoupper($input));
	$input = str_replace($removestrings_all, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = htmlentities($input);
	return($input);
}

function CleanNumber($input) {
	return($input);
}

function PostcodeFinder($input) {
	$spaces = " ";
	$input = str_replace($spaces, "+", $input);
	$input = "http://google.com/maps?q=$input";
	// $input = "http://www.streetmap.co.uk/streetmap.dll?postcode2map?$input";
	return($input);
}

function TimeFormat($input) {
	$input = gmdate("j M Y", $input);
	return($input);
}

function TimeFormatBrief($input) {
	$input = gmdate("j.n.y", $input);
	return($input);
}

function TimeFormatDetailed($input) {
	$input = gmdate("g.ia, j F Y", $input);
	return($input);
}

function TimeFormatDay($input) {
	$input = gmdate("l, j F Y", $input);
	return($input);
}

function TrimLength($input,$max) {
	if (strlen($input) > $max) {
	  $input = substr($input,0,$max-3)."...";
	}
	return($input);
  }

function MoneyFormat($input) {  
	$input =  "&pound;".number_format($input, 2);
	return($input);
}

function CashFormat($input) {
		$input = "�".number_format($input,2,'.',',');
		return($input);
		}
		
function RemoveShit($input) {
$remove_symbols = array("�","�");
$swap_1 = array("€", "\n");
$replace_1 = array("�", "\n");
		$output = str_replace($remove_symbols, "", $input);
		$output = str_replace($swap_1, $replace_1, $output);
return $output;
}

function NumberFormat($input) {
	$input = number_format($input, 2, '.', '');
	return($input);
}

function BeginWeek($input) {
	$dayofweek = date("w", $input);
	if ($dayofweek == 1) { $dayofweek = 0; }
	elseif ($dayofweek == 2) { $dayofweek = 1; }
	elseif ($dayofweek == 3) { $dayofweek = 2; }
	elseif ($dayofweek == 4) { $dayofweek = 3; }
	elseif ($dayofweek == 5) { $dayofweek = 4; }
	elseif ($dayofweek == 6) { $dayofweek = 5; }
	elseif ($dayofweek == 0) { $dayofweek = 6; }
	$daysofweek = (($dayofweek) * 86400 ) - 7200;
	$today = mktime(0, 0, 0, date("n", $input), date("j", $input), date("Y", $input));
	$monday = ( $today - $daysofweek );
	return($monday);
}

function BeginMonth($time,$week,$backwards) {
	//"backwards" means how many weeks to go back - assume none
	if ($backwards > 0) { $time = $time - ($backwards * 604800); } 
	$month = date("n", $time);
	$year = date("Y", $time);
	$firstday = mktime(12,0,0,$month,1,$year);
	if ($week != NULL) { $firstday = BeginWeek($firstday); }
	return($firstday);
}

function TextPresent($input) {
	$input = htmlentities($input);
	$input = nl2br($input);
	return($input);
}

function UserDetails($user) {
	
	global $conn;
	
	$user = intval($user);
	
	$sql = "SELECT user_name_first, user_name_second FROM intranet_user_details WHERE user_id = $user LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		$array = mysql_fetch_array($result);
		$name = "<a href=\"index2.php?page=user_view&amp;user_id=" . $user . "\">" . $array['user_name_first'] . " " . $array['user_name_second'] . "</a>";
	}
	
	return $name;
}

function DateDropdown($input, $timecode) {

		$date_day = array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
		$date_month_display = array("January","February","March","April","May","June","July","September","October","November","December");
		$date_month = array("1","2","3","4","5","6","7","8","9","10","11","12");
		$date_year = array("2000","2001","2002","2003","2004","2006","2007","2008","2009","2010");
		echo "Day:&nbsp;";
		echo "<select name=\"".$input."_day\">";
		echo "<option value=\"\">-- N/A --";
		$counter = 0;
		while ($counter < count($date_day)) {
			echo "<option value=\"$date_day[$counter]\">$date_day[$counter]</option>";
			if (date("j", $timecode) == $date_month[$counter]) { echo " selected "; }
			$counter++;
		}
		echo "</select>";
		echo "&nbsp;Month:&nbsp;";
		echo "<select name=\"".$input."_month\">";
		echo "<option value=\"\">-- N/A --";
		$counter = 0;
		while ($counter < count($date_month)) {
			echo "<option value=\"$date_month[$counter]\">$date_month_display[$counter]</option>";
			if (date("n", $timecode) == $date_month[$counter]) { echo " selected "; }
			$counter++;
		}
		echo "</select>";
		echo "&nbsp;Year:&nbsp;";
		echo "<select name=\"".$input."_year\">";
		echo "<option value=\"\">-- N/A --";
		$counter = 0;
		while ($counter < count($date_year)) {
			echo "<option value=\"$date_year[$counter]\">$date_year[$counter]</option>";
			if (date("Y", $timecode) == $date_month[$counter]) { echo " selected "; }
			$counter++;			
		}
}

function VATDown($input, $input2) {
	$input2 = $input2 / 100;
	$input2 = $input2 + 1;
	$input2 = 1 / $input2;
	$input = $input * $input2;
	return($input);
}

function InvoiceDueDays($invoice_text, $invoice_due, $invoice_date) {
	$invoice_due_days = $invoice_due - $invoice_date;
	$invoice_due_days = $invoice_due_days / 86400;
	settype($invoice_due_days, "integer");
	$invoice_text = str_replace("[due]", $invoice_due_days, $invoice_text);
	return $invoice_text;
}

function AssessDays($input,$hour) {
	
		if ($hour == NULL) { $hour = 12; }

		$date_array = explode("-",$input);
		$d = $date_array[2];
		$m = $date_array[1];
		$y = $date_array[0];
		
		$time = mktime($hour, 0, 0, $m ,$d, $y);
		
		return $time ;

}

function KeyWords($input) { 
				
	$keywords = explode(",", $input);
	$count = 0;
	$total = count($keywords);
	while ($count < $total)
	{
	$keyword = trim($keywords[$count]);
		if (strlen($keywords[$count]) > 3) {
		$output = $output . "&nbsp;<a href=\"index2.php?page=tender_search&amp;tender_keyword=$keyword\">$keyword</a>"; }
		$count++;
	$output = $output . "</a>,";
	}
	$output = rtrim($output,",");
	echo $output;
}

function TenderWords($input) {
	$input = str_replace(" & "," and ",$input);
	$keyword_array = 
	"housing standard,hca,quality standard,quality management,design standard,communit,consultant,consultation,value,communication,customer service,customer satisfaction,partnering,collaboration,experience,resident involvement,participation,environmental,structure,training,development,turnover,accreditation,achievement,award,competition,budget constraint,contract,certification,innovation,personnel,improvement,design team,approach,diverse,stakeholder,design and build,SMART,cabe,detailing,construction,kpis,scale,performance,tenures,geographical area,multi-use,mixed-use,new-build,new build,good design,special needs,complaint,sustainab,refurb,engage,planner,resident,planning,communicate,decent homes,collaborative,lifetime homes,building for life,standards,diversity,equality";
$keyword_explode = explode(",",$keyword_array);
$counter = 0;
$total = count($keyword_explode);
		while ($counter < $total) {
		$keyword_explode_padded = $keyword_explode[$counter];
		$replace = "<a href=\"index2.php?page=tender_search&amp;tender_keyword=$keyword_explode[$counter]\">".$keyword_explode[$counter]."</a>";
		$input = str_replace($keyword_explode_padded,$replace,$input);
		$counter++;
		}

echo $input;

}

function WordCount($input) {
	$output = str_word_count(strip_tags($input));
	return $output;
}
		
function ShowSkins($input) {
$input = "/".$input;
$array_skins = scandir($input);
return $array_skins;
}

function DayLink($input,$detail) {
	
	if (intval($detail) == 1) { $dayprint = TimeFormatDetailed($input); } else { $dayprint = TimeFormat($input); }
	
	$output = "<a href=\"index2.php?page=datebook_view_day&amp;timestamp=" . $input . "\">" . $dayprint . "</a>";
	return $output;

}

function SideMenu ($title, $array_pages, $array_title, $array_access, $user_usertype_current, $array_images, $align) {

$current_page = $_SERVER['QUERY_STRING'];

	$min_level = min($array_access);
	
	if ($align == "r") { $class = "_right"; } else { $class = "_left"; }
	
	if ($min_level <= $user_usertype_current ) {

			
			$count = 0;
			
			echo "<span id=\"heading_$title\" class=\"heading_side$class\"
				onMouseUp=\"document.getElementById('" . $title . $count . "').style.display='block'; document.getElementById('heading_" . $title . "').style.display='none'; document.getElementById('subheading_" . $title . "').style.display='block'\" style=\"cursor: pointer;\">$title</span>";
			echo "<span id=\"subheading_$title\" class=\"heading_side$class\"
				onMouseUp=\"document.getElementById('" . $title . $count . "').style.display='none'; document.getElementById('heading_" . $title . "').style.display='block'; document.getElementById('subheading_" . $title . "').style.display='none'\" style=\"display: none; cursor: pointer;\">$title</span>";
			echo "<ul id=\"" . $title . $count . "\" class=\"menu_side$class\" style=\"display: none;\">";
			foreach ($array_pages as $page) {
				if (($user_usertype_current >= $array_access[$count]) && ( $current_page != $array_pages[$count] )) {
					if ($array_images[$count]) { $image = "<img src=\"images/$array_images[$count]\" alt=\"$array_title[$count]\" />&nbsp;"; } else { unset($image); } 
					if ($array_pages[$count]) { $link = "<a class=\"menu_side$class\" href=\"$array_pages[$count]\">" . $image . $array_title[$count] . "</a>"; } else { unset($link); } 					
					echo "<li>" . $link . "</li>";
				} elseif ($user_usertype_current >= $array_access[$count]) {
					echo "<li><span class=\"menu_side$class\">$array_title[$count]</span></li>";
				}
				$count++;
			}
			echo "</ul>";
			
	}

}

function UserHolidays($user_id,$text,$year) {

	GLOBAL $database_location;
	GLOBAL $database_username;
	GLOBAL $database_password;
	GLOBAL $database_name;
	GLOBAL $settings_timesheetstart;
	
	if (!$year) { $year = date("Y",time()); }
	

	$conn = mysql_connect("$database_location", "$database_username", "$database_password");
	mysql_select_db("$database_name", $conn);
	
	// Establish the beginning of the year
		
	$this_year = date("Y",time());
	$next_year = $this_year + 1;
	$beginning_of_year = mktime(0,0,0,1,1,$this_year);
	$end_of_year = mktime(0,0,0,1,1,$next_year);
	
	$holiday_datum = mktime(0,0,0,1,1,2012);
	
	$sql_user_details = "SELECT user_user_added, user_user_ended, user_holidays FROM intranet_user_details WHERE user_id = $user_id";
	$result_user_details = mysql_query($sql_user_details, $conn) or die(mysql_error());
	$array_user_details = mysql_fetch_array($result_user_details);
	$user_user_added = $array_user_details['user_user_added'];
	if ($user_user_added == NULL OR $user_user_added < $holiday_datum ) { $user_user_added = $holiday_datum; }
	$user_user_ended = $array_user_details['user_user_ended'];
	$user_holidays = $array_user_details['user_holidays'];
	
	$sql_user_holidays = "SELECT SUM(holiday_length) FROM intranet_user_holidays WHERE holiday_user = $user_id AND holiday_paid = 1 AND holiday_timestamp < $end_of_year AND holiday_timestamp > $user_user_added";
	$result_user_holidays = mysql_query($sql_user_holidays, $conn) or die(mysql_error());
	$array_user_holidays = mysql_fetch_array($result_user_holidays);
	$user_holidays_taken = $array_user_holidays['SUM(holiday_length)'];
	
	//if ($user_user_added == NULL OR $user_user_added == 0) { $user_user_added = $settings_timesheetstart; }
	$begin_count = $user_user_added;
	
	if ($end_of_year > $user_user_ended AND $user_user_ended > 0) { $end_of_year = $user_user_ended; $ended = " (your employment ended on " . TimeFormat($user_user_ended) . ") "; }

	$seconds_to_end_of_year = $end_of_year - $begin_count;
	
	$years_total = $seconds_to_end_of_year / (365 * 60 * 60 * 24);
	
	$total_holidays_allowed = round($user_holidays * $years_total) - $user_holidays_taken;
	
	//$years_to_now = $seconds_to_end_of_year / (60 * 60 * 24 * 365);
	//$total_holidays_allowed =  ( round ( $user_holidays * $years_to_now ) ) - $user_holidays_taken;
	
	
	
	if ($text != NULL) {
	
		$workingdays = WorkingDays($year);
		
		$user_holiday_array = UserHolidaysArray($user_id,$year,$workingdays);
		//$array = array($length,$user_holidays,$holiday_allowance,$holiday_allowance_thisyear,$holiday_paid_total,$holiday_unpaid_total,$study_leave_total,$jury_service_total,$toil_service_total,$holiday_year_remaining,$listadd,$listend,$user_name_first, $user_name_second);
	
	echo "<p>Your annual holiday allowance is <strong>" . $user_holiday_array[1] . "</strong> days.</p><p>You are entitled to <strong>" . $user_holiday_array[9] . " days</strong> before the end of " . $year . "</p>";
	}
	
	return $total_holidays_allowed;
	
}

function UserDropdown($input_user) {

GLOBAL $conn;

	$sql = "SELECT user_id, user_active, user_name_first, user_name_second FROM intranet_user_details ORDER BY user_active DESC, user_name_second";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	echo "<select class=\"inputbox\" name=\"user_id\">";

	unset($user_active_test);
	
	while ($array = mysql_fetch_array($result)) {


		$user_id = $array['user_id'];
		$user_active = $array['user_active'];
		$user_name_first = $array['user_name_first'];
		$user_name_second = $array['user_name_second'];
		
		if ( $user_active != $user_active_test) {
				if ($user_active == 0) { echo "<option disabled></option><option disabled><i>Inactive Users</i></option><option disabled>------------------------</option>"; } else { echo "<option disabled><i>Active Users</i></option><option disabled>------------------------</option>"; } 
		$user_active_test = $user_active; }
		
            echo "<option value=\"$user_id\"";
            if ($user_id == $input_user) { echo " selected"; }
            echo ">".$user_name_first."&nbsp;".$user_name_second."</option>";
		}

	echo "</select>";
	
}

function DisplayDate($date) {

	// Date in format YYYY-MM-DD

	$output = explode ("-",$date);
	$output = mktime(12,0,0,$output[1],$output[2],$output[0]);
	return $output;

}

function DisplayDay($time) {

	// Time in timestamp
	
	$time = intval($time);
	$output = date("Y-m-d",$time);
	return $output;

}

function DateList($impending_only) {
	

		global $conn;
		global $user_usertype_current;
		$now = date("Y-m-d",time());
		
		$impending_only = intval($impending_only);
		
		$weeks = 2;

		$alert = time() + $weeks * 60 * 60 * 24 * 7;
		$alert = date("Y-m-d",$alert);

		if ($impending_only == 1) {
		
			//$sql = "SELECT * FROM intranet_datebook LEFT JOIN intranet_projects ON proj_id = date_project WHERE date_day < '$alert' AND date_day >= " . "'" . $now . "' ORDER BY date_day";
			$sql = "SELECT * FROM intranet_datebook LEFT JOIN intranet_projects ON proj_id = date_project WHERE date_day < DATE_ADD('" . $now . "', INTERVAL date_warning WEEK) AND date_day >= " . "'" . $now . "' ORDER BY date_day";
			
		} elseif ($impending_only == 2) { 
		
			$sql = "SELECT * FROM intranet_datebook LEFT JOIN intranet_projects ON proj_id = date_project WHERE $impending date_day < " . "'" . $now . "' ORDER BY date_day DESC";

		
		} else {
			
			$sql = "SELECT * FROM intranet_datebook LEFT JOIN intranet_projects ON proj_id = date_project WHERE $impending date_day >= " . "'" . $now . "' ORDER BY date_day";
			
		}

		$result = mysql_query($sql, $conn) or die(mysql_error());
	
		if (mysql_num_rows($result) > 0 ) {
	
			if ($impending_only == 1) { echo "<h2>Next " . $weeks . " Weeks</h2>";  }
			elseif ($impending_only == 2) {  echo "<h2>Past Dates</h2>"; }
			else { echo "<h2>Future Dates</h2>"; }		

			if ($impending_only != 1) { ProjectSubMenu('',$user_usertype_current,"date_list"); }

			echo "<table>";
	
			echo "<tr><th>Description</th><th>Date</th><th>Project</th><th colspan=\"2\" class=\"HideThis\">Category</th></th>";
	
			while ($array = mysql_fetch_array($result)) {
				
				if ($array['date_day'] == date("Y-m-d",time())) {
					$style="alert_warning";
				} elseif (((DisplayDate($array['date_day']))) < (time() + 604800) && (DisplayDate($array['date_day']) > (time()))) {
					$style="alert_careful";
				} else {
					unset($style);
				}
				
				if ((intval($_GET[date_id]) == $array['date_id']) && $array['date_notes']) { $embolden = "font-weight: bold;"; } else { unset($embolden); }
	
				echo "<tr><td class=\"$style\" style=\"width: 25%; $embolden\">";
				if ($array['date_notes']) { echo "<a href=\"index2.php?page=date_list&amp;date_id=" . $array['date_id']  . "\">" . $array['date_description'] . "&nbsp;&#8681;</a>"; } else { echo $array['date_description']; }
				echo "</td><td class=\"$style\"  style=\"width: 25%; $embolden\">" . TimeFormat ( DisplayDate($array['date_day']) ) . "</td><td class=\"$style\"  style=\"width: 25%; $embolden\">" . $array['proj_num'] . " " . $array['proj_name'] . "</td>";
				if ($array['date_day'] == $_COOKIE[user] OR $user_usertype_current > 3) {
					echo "<td class=\"$style HideThis\" style=\"$embolden\">" . $array['date_category'] . "</td><td style=\"text-align: right;\" class=\"$style HideThis\"><a href=\"index2.php?page=date_edit&amp;date_id=" . $array['date_id'] . "\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a></td>";
				} else {
					echo "<td colspan=\"2\" class=\"$style HideThis\" style=\"$embolden\">" . $array['date_category'] . "</td></td></tr>";
				}

				if ((intval($_GET[date_id]) == $array['date_id']) && $array['date_notes']) { echo "</table><div class=\"page\">" . $array['date_notes'] . "</div><table>"; }

	
	
			}
	
			echo "</table>";
			
			if ($impending_only == 1) {  echo "<p><a href=\"index2.php?page=date_list\" class=\"submenu_bar\">More</a></p>"; }
	
		}	
	
	}

function ListAvailableImages($directory) {
	
	global $conn;
	
	$recent = time() - (1209600); //  2 weeks
	
	$sql = "SELECT * FROM intranet_media WHERE (media_type = 'png' OR media_type = 'jpg' OR media_type = 'gif') AND (media_timestamp > $recent) ORDER BY media_title, media_timestamp DESC";

	
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
		
		$list = $list . "{title: '" . $array['media_title'] . "', value: '" . $array['media_path'] . $array['media_file'] . "'},";
		
	}
	
	$list = rtrim($list,",");
	
	echo $list;

	
}

function PersistentStorage($id, $varname, $content) {
	
	
}

function TextAreaEdit() {

				echo "
					<script type=\"text/javascript\">
					tinymce.init({
					selector: \"textarea\",
					plugins: [
						\"advlist autolink lists link charmap preview anchor textcolor table image code\"
					],
					menubar: false,
					toolbar: \"undo redo | bold italic underline strikethrough | bullist numlist outdent indent | link unlink | forecolor | table | alignleft aligncenter alignright | image | code \",
					table_toolbar: \"tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol\",
					image_list: [";
						ListAvailableImages("uploads");
				echo "],
					autosave_ask_before_unload: false,
					height : 300,
					max_height: 1000,
					min_height: 160
					});
				</script>";
}

function EditForm($answer_id,$answer_question,$answer_ref,$answer_words,$answer_weighting,$tender_id) {

				TextAreaEdit();
						
				echo "<a name=\"$answer_id\"></a><form action=\"index2.php?page=tender_view&amp;tender_id=$tender_id#$answer_id\" method=\"post\">
					<tr><td style=\"width: 10%;\" name=\"$answer_id\">";
					echo "Ref: <br />";
					echo "<input type=\"text\" name=\"answer_ref\" value=\"$answer_ref\" size=\"4\" required=\"required\"></td><td>";
					if ($answer_id == NULL) { echo "Add question:<br />"; } else { echo "Edit question below:<br />"; }
					echo "<textarea style=\"width: 100%; height: 360px;\" name=\"answer_question\">$answer_question</textarea>
					<br />Words allowed:&nbsp;<input type=\"text\" maxlength=\"4\" name=\"answer_words\" value=\"$answer_words\" />&nbsp;Weighting:<input type=\"text\" maxlength=\"10\" name=\"answer_weighting\" value=\"$answer_weighting\" /> 
					<br /><input type=\"submit\" />
					<input type=\"hidden\" name=\"answer_id\" value=\"$answer_id\" />
					<input type=\"hidden\" name=\"answer_tender_id\" value=\"$tender_id\" />
					<input type=\"hidden\" name=\"action\" value=\"tender_question_edit\" />
					</form>
					<form action=\"index2.php?page=tender_view&amp;tender_id=$tender_id#$answer_id\" method=\"post\"><input type=\"submit\" value=\"Cancel\" /></form>
				";
}

function ListHolidays($days) {

	global $conn;
	
	
	
	$nowtime = time() - 43200;
	
	if (intval ($days) == 0) { $days = 7; } else { $days = intval($days); }
	
	$time =  60 * 60 * 24 * intval ($days);
	
	echo "<h2>Upcoming Holidays - Next $days Days</h2>";

		$sql5 = "SELECT user_id, user_name_first, user_name_second, holiday_date, holiday_timestamp, holiday_paid, holiday_length, holiday_approved FROM intranet_user_details, intranet_user_holidays WHERE holiday_user = user_id AND holiday_timestamp BETWEEN $nowtime AND " . ($nowtime + $time) ." ORDER BY holiday_timestamp, user_name_second";
		$result5 = mysql_query($sql5, $conn) or die(mysql_error());
		if (mysql_num_rows($result5) > 0) {
			$current_date = 0;
			
			$holidaymessage = $holidaymessage . "<table>";
			while ($array5 = mysql_fetch_array($result5)) {
			
					if ($current_id != $user_id AND $current_id > 0) {
						$holidaymessage = $holidaymessage . "</td></tr>";
					} 
					
					$user_id = $array5['user_id'];
					$user_name_first = $array5['user_name_first'];
					$user_name_second = $array5['user_name_second'];
					$holiday_timestamp = $array5['holiday_timestamp'];
					$holiday_length = $array5['holiday_length'];
					$holiday_paid = $array5['holiday_paid'];
					$holiday_date = $array5['holiday_date'];
					$holiday_approved = $array5['holiday_approved'];
					
					$calendar_link = "index2.php?page=holiday_approval&amp;year=" . date("Y",$holiday_timestamp) . "#Week" . date("W", $holiday_timestamp);
					
					if ($holiday_approved == NULL) { $holiday_approved1 = "<span style=\"color: red;\">"; $holiday_approved2 = "</span>";  } else { unset($holiday_approved1); unset($holiday_approved2); }
					if ($current_date != $holiday_date) {
						$holidaymessage = $holidaymessage . "<tr><td>" . TimeFormatDay($holiday_timestamp) . "</td><td>";
					} else { 
						$holidaymessage = $holidaymessage . ", ";
					}
					
					if ($holiday_length < 1) { $holiday_length = " (Half Day)"; } else { unset($holiday_length); }
					
					$holidaymessage = $holidaymessage . "<a href=\"$calendar_link\">" . $holiday_approved1 . $user_name_first . " " . $user_name_second . $holiday_length . $holiday_approved2 . "</a>"; ;
					
					$current_date = $holiday_date;
			}
			
			$holidaymessage = $holidaymessage . "</td></tr></table>";
		}

	echo $holidaymessage;


}

function FooterBar() {
	
	echo "<div id=\"mainfooter\">powered by <a href=\"https://github.com/rckarchitects/cornerstone-rcka/wiki/Welcome-to-Cornerstone\">RCKa Cornerstone</a></div>";
	
}

function StyleBody($size,$font,$bold){
			Global $pdf;
			Global $format_font;
			if (!$font) { $font = $format_font; }
			$pdf->SetFont($font,$bold,$size);
			
		}
		
function StyleHeading($title,$text,$notes){
			Global $pdf;
			Global $x_current;
			Global $y_current;
			Global $y_left;
			Global $y_right;
			if ($y_current > 230 AND $x_current > 75) { $pdf->addPage(); $x_current = 10; $y_current = 15; }
			$pdf->SetXY($x_current,$y_current);
			$pdf->SetFont('Helvetica','b',10);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetX($x_current);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetLineWidth(0.3);
			$pdf->MultiCell(90,5,$title,B, L, false);
			$pdf->MultiCell(90,1,'',0, L, false);
			Notes($notes);
			$pdf->Ln(1);
			StyleBody(10);
			$pdf->SetX($x_current);
			$pdf->MultiCell(90,4,$text,0, L, false);
			
			if ($x_current < 75) {
				$x_current = 105;
				$y_left = $pdf->GetY();
			} else {
				$x_current = 10;
				$y_right = $pdf->GetY();
				if ($y_left > $y_right) { $y_current = $y_left + 5; } else { $y_current = $y_right + 5; }
				if ($y_current > 220) { $pdf->addPage(); $y_current = 20; }
			}
			
			
		}
		
function ListHoliday($day_begin, $color_switch) {

		if ($color_switch == 1) { SetColor1(); } else { SetColor2(); }

		GLOBAL $conn;
		GLOBAL $pdf;
		
		StyleBody(8,'Helvetica','');
		
		$day = date("D j",$day_begin);
		
		$pdf->Cell(15,10,$day);
		
		$day_begin = $day_begin + 43200;
		$date = date("Y-m-d",$day_begin);
		
		StyleBody(14,'Helvetica','B');
		
		$sql_bankhols = "SELECT bankholidays_description FROM intranet_user_holidays_bank WHERE bankholidays_datestamp = '$date'";
		$result_bankhols = mysql_query($sql_bankhols, $conn) or die(mysql_error());
		$array_bankhols = mysql_fetch_array($result_bankhols);
		if ($array_bankhols['bankholidays_description']) { $pdf->Cell(0,12,$array_bankhols['bankholidays_description'],0,0,'L',0); } else {
		
			$sql = "SELECT * FROM `intranet_user_holidays`, `intranet_user_details` WHERE user_id = holiday_user AND holiday_datestamp = '$date' ORDER BY user_initials";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			while ($array = mysql_fetch_array($result)) {
				if ($array['holiday_length'] < 1) { 
				$pdf->Cell(6,12,'',0,0,'C',1);
				$xval = $pdf->GetX() - 6;
				$pdf->SetX($xval);
				$pdf->Cell(12,12,$array['user_initials'],0,0,'C',0);
				} else {
				$pdf->Cell(12,12,$array['user_initials'],0,0,'C',1);
				}
				$pdf->Cell(2,12,'',0,0,'C',0);
				if ($pdf->GetX() < 25) { $pdf->SetX(25); }
			}
			
		}
		
		$pdf->Ln(14);


}
	
function OtherHolidaysToday($user_id,$date) {

	GLOBAL $conn;
	GLOBAL $pdf;
	
	$sql_user_holidays = "SELECT user_initials, holiday_approved FROM intranet_user_holidays LEFT JOIN intranet_user_details ON user_id = holiday_user WHERE holiday_user != $user_id AND holiday_datestamp = '$date' ORDER BY user_initials";
	$result_user_holidays = mysql_query($sql_user_holidays, $conn) or die(mysql_error());
	
	$numrows = mysql_num_rows($result_user_holidays);
	
	if ($numrows > 0) {
			$cellwidth = 75 / $numrows;
			if ($cellwidth > 10) { $cellwidth = 10; }
			
			
			
			while ($array_user_holidays = mysql_fetch_array($result_user_holidays)) {
			
				if ($array_user_holidays['holiday_approved'] > 0) { $pdf->SetTextColor(0,0,0); } else { $pdf->SetTextColor(255,0,0); }
				
				$pdf->Cell($cellwidth,7.5,$array_user_holidays['user_initials'],'B',0,L,0);		
			}
			
			$pdf->Cell(0,7.5,'','B',1,L,0);	
		
			
	} else {
	
				$pdf->SetTextColor(0,0,0);
	
				$pdf->Cell(0,7.5,$array_user_holidays['user_initials'],'B',1,C,0);
	
	}
	
	$pdf->SetTextColor(0,0,0);


}

function UserHolidaysArray($user_id,$year,$working_days) {
	
	GLOBAL $conn;

			$sql_user = "SELECT user_user_added, user_user_ended, user_holidays, user_name_first, user_name_second FROM intranet_user_details WHERE user_id = $user_id LIMIT 1";
			$result_user = mysql_query($sql_user, $conn);
			$array_user = mysql_fetch_array($result_user);
			$user_user_added = $array_user['user_user_added'];
			$user_user_ended = $array_user['user_user_ended'];
			$user_name_first = $array_user['user_name_first'];
			$user_name_second = $array_user['user_name_second'];
			
			$user_holidays = $array_user['user_holidays'];
			
			$holiday_datum = mktime(0,0,0,1,1,2012);
			
			$nextyear = $year + 1;
			
			if ($user_user_added == NULL OR $user_user_added < $holiday_datum ) { $user_user_added = $holiday_datum; $listadd = "-"; } else { $listadd = date ( "d M Y", $user_user_added ); }
			
			if ($user_user_ended == NULL OR $user_user_ended == 0) { $user_user_ended = mktime(0,0,0,1,1,$nextyear); $listend = "-"; } else { $listend = date ( "d M Y", $user_user_ended ); }
			
	
							$sql_count = "SELECT * FROM intranet_user_holidays WHERE holiday_user = $user_id AND holiday_assigned = $year ORDER BY holiday_timestamp";
							$result_count = mysql_query($sql_count, $conn);
							while ($array_count = mysql_fetch_array($result_count)) {
							

								$holiday_year = $array_count['holiday_year'];
								$holiday_length = $array_count['holiday_length'];
								$holiday_paid = $array_count['holiday_paid'];
								
								$holiday_allowance = $user_user_ended - $user_user_added;
							$yearlength = 365.242 * 24 * 60 * 60;
							$holiday_allowance = ( $holiday_allowance / $yearlength ) * $user_holidays;
							$holiday_allowance = round($holiday_allowance);
							
							$holiday_allowance_thisyear = $user_user_ended - mktime(0,0,0,1,1,$year);
							if ($user_user_added > mktime(0,0,0,1,1,$year)) { $holiday_allowance_thisyear = $holiday_allowance_thisyear - ($user_user_added - mktime(0,0,0,1,1,$year)); }
							
							
							
							$holiday_allowance_thisyear = $holiday_allowance_thisyear / (365.242 * 24 * 60 * 60) ;
							
							if ($holiday_allowance < $user_holidays) { $year_allowance = $holiday_allowance; } else { $year_allowance = $user_holidays; }
							
					
							$holiday_allowance_thisyear = round ($user_holidays * $holiday_allowance_thisyear);
								
											
											if ($holiday_paid == 1) { $holiday_paid_total = $holiday_paid_total + $holiday_length; }
											elseif ($holiday_paid == 2) { $study_leave_total = $study_leave_total + $holiday_length; }
											elseif ($holiday_paid == 3) { $jury_service_total = $jury_service_total + $holiday_length; }
											elseif ($holiday_paid == 4) { $toil_service_total = $toil_service_total + $holiday_length; $holiday_paid_total = $holiday_paid_total - $holiday_length;  }
											elseif ($holiday_paid == 5) {   }
											else { $holiday_unpaid_total = $holiday_unpaid_total + $holiday_length; }
											
											

											if ($holiday_paid == 1) { $holiday_total = $holiday_total + $holiday_length; }
											
								
								}
								
							// Calculate any adjustments for unpaid holiday	
								
							$unpaid_adjustment = ($working_days - $holiday_unpaid_total) / $working_days;

							$holiday_allowance_thisyear = ceil ($unpaid_adjustment * $holiday_allowance_thisyear);
							
							$length = round ((($user_user_ended - $user_user_added) / 31556908.8), 2);
							
							$holiday_allowance = (ceil($length * $user_holidays * 2) / 2);
							
							// Temporary
							// if ($length > 1) {
							// $holiday_allowance_thisyear = $user_holidays;
							// } else {
							// $holiday_allowance_thisyear = ceil ($length * $user_holidays * 2) / 2;
							// }
							// End Temporary
							
							$holiday_year_remaining = $holiday_allowance_thisyear - $holiday_paid_total;
							
							$array = array($length,$user_holidays,$holiday_allowance,$holiday_allowance_thisyear,$holiday_paid_total,$holiday_unpaid_total,$study_leave_total,$jury_service_total,$toil_service_total,$holiday_year_remaining,$listadd,$listend,$user_name_first, $user_name_second,$unpaid_adjustment);
	
							return $array;
	
}

function WorkingDays($year) {
	
	GLOBAL $conn;
	
	$year = intval($year);
	
	$sql = "SELECT COUNT(bankholidays_id) FROM intranet_user_holidays_bank WHERE bankholidays_year = $year";
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	$bankholidays = $array['COUNT(bankholidays_id)'];
	
	$thisyear = $year;
	$day = mktime(12,0,0,1,1,$year);
	$countdays = 0;
	while ($thisyear == $year) {
		
		if (date("w",$day) > 0 && date("w",$day) < 6) { $countdays++; }
		$day = $day + 86400;
		$thisyear = intval ( date("Y",$day) );

	}
	
	$workingdays = $countdays - $bankholidays;
	
	return $workingdays;
	
}

function HolidaySchedule($year,$user_usertype_current,$working_days,$beginnning_of_this_year,$beginnning_of_next_year) {

GLOBAL $conn;

						echo "<h3 id=\"holidaysthisyear\">Holidays in $year</h3>";

						echo "<p>There were $working_days working days in $year.</p>";

						if ($user_usertype_current < 3) { $limit = "AND user_id = $user_id"; } else { unset( $limit );}

						$sql_users = "SELECT user_id, user_name_first, user_name_second FROM intranet_user_details WHERE (
						(user_user_added BETWEEN $beginnning_of_this_year AND $beginnning_of_next_year)
						OR (user_user_ended BETWEEN $beginnning_of_this_year AND $beginnning_of_next_year)
						OR (user_user_added < $beginnning_of_this_year AND (user_user_ended = 0 OR user_user_ended IS NULL))
						) $limit ORDER BY user_name_second";


						$result_users = mysql_query($sql_users, $conn);
						echo "<table>";

						echo "<tr>
						<th style=\"width: 15%;\">Name</th>
						<th style=\"width: 10%;\">Date Started</th>
						<th style=\"width: 10%;\">Date Ended</th>
						<th style=\"width: 6%; text-align: right;\">Years<br />(to end of $year)</th>
						<th style=\"width: 10%; text-align: right;\">Annual Allowance</th>
						<th style=\"width: 6%; text-align: right;\">Total Allowance</th>
						<th style=\"width: 6%; text-align: right;\">Allowance ($year)</th>
						<th style=\"width: 6%; text-align: right;\">Days Taken ($year)</th>
						<th style=\"width: 6%; text-align: right;\">Days Unpaid ($year)</th>
						<th style=\"width: 6%; text-align: right;\">Study Leave ($year)</th>
						<th style=\"width: 6%; text-align: right;\">Jury Service ($year)</th>
						<th style=\"width: 6%; text-align: right;\">TOIL ($year)</th>
						<th style=\"text-align: right;\">Days Remaining ($year)</th></tr>";

						while ($array_users = mysql_fetch_array($result_users)) {


							

							$user_id = $array_users['user_id'];
							$user_name_first = $array_users['user_name_first'];
							$user_name_second = $array_users['user_name_second'];
							
														
							$holiday_paid_total = 0;
							$holiday_unpaid_total = 0;
							$holiday_total = 0;
							$study_leave_total = 0;
							$jury_service_total = 0;
							$toil_service_total = 0;
							$toil_total = 0;
							
							$UserHolidaysArray = UserHolidaysArray($user_id,$year,$working_days); 
							
							$length = $UserHolidaysArray[0];
							$user_holidays = $UserHolidaysArray[1];
							$holiday_allowance = $UserHolidaysArray[2];
							$holiday_allowance_thisyear = $UserHolidaysArray[3];
							$holiday_paid_total = $UserHolidaysArray[4];
							$holiday_unpaid_total = $UserHolidaysArray[5];
							$study_leave_total = $UserHolidaysArray[6];
							$jury_service_total = $UserHolidaysArray[7];
							$toil_service_total = $UserHolidaysArray[8];
							$holiday_year_remaining = $UserHolidaysArray[9];
							$listadd = $UserHolidaysArray[10];
							$listend = $UserHolidaysArray[11];
							$user_name_first = $UserHolidaysArray[12];
							$user_name_second = $UserHolidaysArray[13];
							$unpaid_adjustment = $UserHolidaysArray[14];
							
							if ($holiday_year_remaining < 0) { $holiday_year_remaining = "<span style=\"color: red;\">" . $holiday_year_remaining . "</span>"; }
							
							if ($_GET[showuser] == $user_id) { $bg = "; font-weight: bold; background: rgba(100,100,150,0.5)\""; } else { unset($bg); }
								
							echo "
							<tr>
							<td style=\"$bg\"><a href=\"index2.php?page=holiday_approval&amp;showuser=$user_id&year=$_GET[year]#holidaysthisyear\">$user_name_first $user_name_second</a></td>
							<td style=\"$bg\">" . $listadd . "</td>
							<td style=\"$bg\">" . $listend . "</td>
							<td style=\"text-align:right; $bg\">$length</td>
							<td style=\"text-align:right; $bg\">$user_holidays</td>
							<td style=\"text-align:right; $bg\">$holiday_allowance</td>
							<td style=\"text-align:right; $bg\">$holiday_allowance_thisyear</td>
							<td style=\"text-align:right; $bg\">$holiday_paid_total</td>
							<td style=\"text-align:right; $bg\">$holiday_unpaid_total</td>
							<td style=\"text-align:right; $bg\">$study_leave_total</td>
							<td style=\"text-align:right; $bg\">$jury_service_total</td>
							<td style=\"text-align:right; $bg\">$toil_service_total</td>
							<td style=\"text-align:right; $bg\">$holiday_year_remaining</td>
							</tr>";
							
							if ($_GET[showuser] == $user_id) {
							
									$bg = "; background: rgba(100,100,150,0.1)\"";
							
										if ($unpaid_adjustment < 1 && $_GET[showuser] == $user_id) {
											echo "<tr><td colspan=\"13\" style=\"font-style: italic; $bg\">
											$user_name_first took $holiday_unpaid_total unpaid holidays during $year, from a total of $working_days possible working days. Available holiday has therefore been reduced to " . round (100 *  $unpaid_adjustment ) . "% of the total allowance for this year.
											</td></tr>";
										}
							
									$bg = "; background: rgba(100,100,150,0.2)\"";
								
									$sql_totalhols = "SELECT holiday_timestamp, holiday_length, holiday_paid, holiday_assigned FROM intranet_user_holidays WHERE holiday_user = $user_id AND holiday_assigned = $year ORDER BY holiday_timestamp";
									$result_totalhols = mysql_query($sql_totalhols, $conn);

										if (mysql_num_rows($result_totalhols) > 0) {
										
										$rows = mysql_num_rows($result_totalhols);
											
												$totalhols_count = 0;
												$totalholsup_count = 0;
												
											
												while ($array_totalhols = mysql_fetch_array($result_totalhols)) {
												
												$holiday_length = $array_totalhols['holiday_length'];
												
												if ($array_totalhols['holiday_paid'] == 0 ) { $holiday_type = "Unpaid Leave"; }
												elseif ($array_totalhols['holiday_paid'] == 2 ) { $holiday_type = "Study Leave";  }
												elseif ($array_totalhols['holiday_paid'] == 3 ) { $holiday_type = "Jury Service"; }
												elseif ($array_totalhols['holiday_paid'] == 4 ) { $holiday_type = "TOIL"; }
												elseif ($array_totalhols['holiday_paid'] == 5 ) { $holiday_type = "Compassionate Leave"; }
												else { $holiday_type = "Standard"; $totalhols_count = $totalhols_count + $holiday_length; }
												
												if ($holiday_length == 0.5) { $holiday_type = $holiday_type . " (half day)"; }

													echo "<tr><td colspan=\"4\" style=\"$bg\">" . date ( "l, j F Y", $array_totalhols['holiday_timestamp'] ) . "</td>";
													echo "<td colspan=\"3\" style=\"$bg\">$holiday_type</td>";
														
														
														echo "
														<td style=\"text-align: right; $bg\">$totalhols_count</td>
														<td style=\"$bg\" colspan=\"5\"></td>
														";
													
												echo "</tr>";
												
												}
												
												if ($_GET[showuser] == $user_id) { $bg = "; background: rgba(100,100,150,0.35)\""; } else { unset($bg); }
												
												echo "<tr><td colspan=\"7\" style=\"$bg\"><strong>Total</strong></td><td style=\"text-align: right; $bg\"><strong>$totalhols_count</strong></td><td colspan=\"5\" style=\"$bg\"></th></tr>";
											
											
										} else {
										
												echo "<tr><td></td><td colspan=\"12\">No holidays found for $year</td></tr>";
										
										}
										
								unset($bg);
								
								
							}


						}

						echo "</table>";








}

function ChangeHolidays($year) {
	
		$year_before = $year - 1;
		$year_after = $year + 1;
		
		echo "<table class=\"HideThis\"><tr><td rowspan=\"4\">Change selected holidays</td>
		<td><input type=\"radio\" value=\"approve\" name=\"approve\" checked=\"checked\" />&nbsp;Approve</td>
		<td><input type=\"radio\" value=\"unapprove\" name=\"approve\" />&nbsp;Unapprove</td>
		<td><input type=\"radio\" value=\"delete\" name=\"approve\" />&nbsp;Delete</td>
		<td><input type=\"radio\" value=\"to_paid\" name=\"approve\" />&nbsp;Make Paid Holiday</td>
		<td><input type=\"radio\" value=\"to_unpaid\" name=\"approve\" />&nbsp;Make Unpaid Holiday</td>
		</tr><tr>
		<td><input type=\"radio\" value=\"to_studyleave\" name=\"approve\" />&nbsp;Make Study Leave</td>
		<td><input type=\"radio\" value=\"to_juryservice\" name=\"approve\" />&nbsp;Make Jury Service</td>
		<td><input type=\"radio\" value=\"to_half\" name=\"approve\" />&nbsp;Make Half Day</td>
		<td><input type=\"radio\" value=\"to_full\" name=\"approve\" />&nbsp;Make Full Day</td>
		<td><input type=\"radio\" value=\"to_toil\" name=\"approve\" />&nbsp;Make TOIL</td>
		</tr><tr>
		<td><input type=\"radio\" value=\"compassionate\" name=\"approve\" />&nbsp;Make Compassionate Leave</td>
		<td><input type=\"radio\" value=\"$year_before\" name=\"approve\" />&nbsp;Assign to " . $year_before . "</td>
		<td><input type=\"radio\" value=\"$year\" name=\"approve\" />&nbsp;Assign to " . $year . "</td>
		<td><input type=\"radio\" value=\"$year_after\" name=\"approve\" />&nbsp;Assign to " . $year_after . "</td>
		<td><input type=\"radio\" value=\"to_maternity\" name=\"approve\" />&nbsp;Make Maternity / Paternity Leave</td>
		</tr>
		<tr>
		<td colspan=\"5\"><input type=\"hidden\" value=\"$_COOKIE[user]\" name=\"user_id\" /><input type=\"submit\" value=\"Submit\" /></td>
		</tr>
		</table>
		";
		
}

function TenderList() {

		GLOBAL $conn;
		GLOBAL $user_usertype_current ;
		
		$submitted_total = 0;
		$successful_total = 0;

		$nowtime = time();

		if ($_GET[detail] == "yes") { $detail = "yes"; }

		if (intval($_GET[tender_submitted]) == 1) {
			$sql = "SELECT * FROM intranet_tender ORDER BY tender_date DESC";
			echo "<h2>List of all tenders</h2>";
		} elseif (intval($_GET[tender_pending]) == 1) {
			$sql = "SELECT * FROM intranet_tender WHERE tender_submitted = 1 AND (tender_result = 0 OR tender_result IS NULL) ORDER BY tender_date DESC";
			echo "<h2>List of all pending tenders</h2>";
		} else {
			$sql = "SELECT * FROM intranet_tender WHERE tender_submitted = 1 OR (tender_date > " . time() . " AND tender_result != 3) ORDER BY tender_date DESC";
			echo "<h2>List of all submitted and future tenders</h2>";
		}
		
		$result = mysql_query($sql, $conn) or die(mysql_error());

				echo "<div class=\"submenu_bar\">";
							
					if (intval($_GET[tender_submitted]) == 0 OR intval($_GET[tender_pending]) == 1) {
						echo "<a href=\"index2.php?page=tender_list&amp;tender_submitted=1\" class=\"submenu_bar\">List All Tenders</a>";
					}
				
				
					if (intval($_GET[tender_submitted]) != 0) {
						echo "<a href=\"index2.php?page=tender_list\" class=\"submenu_bar\">List Only Submitted Tenders</a>";
					}
					
					if (intval($_GET[tender_pending]) != 1) {
						echo "<a href=\"index2.php?page=tender_list&amp;tender_pending=1\" class=\"submenu_bar\">List Only Pending Tenders</a>";
					}
					
					if ($user_usertype_current > 3) {
						echo "<a href=\"index2.php?page=tender_edit\" class=\"submenu_bar\">Add Tender <img src=\"images/button_new.png\" alt=\"Add New Tender\" /></a>";
					}

					
				echo "</div>";
				
				if (mysql_num_rows($result) > 0) {
					
					echo "<div>";
				
				$time_line = NULL;

			
				while ($array = mysql_fetch_array($result)) {
				
				$tender_id = $array['tender_id'];
				$tender_name = $array['tender_name'];
				if ($array['tender_type']) { $tender_type = "<br />". $array['tender_type']; }
				if ($array['tender_procedure']) { $tender_type = $tender_type . "<br /><span class=\"minitext\">". $array['tender_procedure'] . "</span>"; }
				$tender_date = $array['tender_date'];
				$tender_client = $array['tender_client'];
				$tender_description = nl2br($array['tender_description']);
				$tender_keywords = $array['tender_keywords'];
				$tender_submitted = $array['tender_submitted'];
				$tender_result = $array['tender_result'];
				
				if ($tender_submitted == 1) { $submitted_total++; }
				if ($tender_result == 1) { $successful_total++; }
				
				if ((($tender_date - $nowtime) < 86400) && (($tender_date - $nowtime) > 0)  && $tender_result != 3) {
					$style = "style=\"background: rgba(255,130,0,0.5); border: solid 1px rgba(255,130,0,0.8);\"";
				} elseif ((($tender_date - $nowtime) < 604800) && (($tender_date - $nowtime) > 0) && $tender_result != 3) {
					$style = "style=\"background: rgba(255,130,0,0.5); border: solid 1px rgba(255,130,0,0.8);\"";
				} elseif ($tender_date > time() && $tender_result != 3) {
					$style = "style=\"background: rgba(175,213,0,0.3); border: solid 1px rgba(175,213,0,0.8);\"";
				} elseif ($tender_submitted == 1 && $tender_result == 1 && $tender_result != 3) {
					$style = "style=\"background: rgba(0,0,255,0.3); border: solid 1px rgba(0,0,255,0.8);\"";
				} elseif ($tender_submitted == 1 && $tender_result == 2 && $tender_result != 3) {
					$style = "style=\"background: rgba(255,0,0,0.3); border: 1px solid rgba(255,0,0,0.8);\"";
				} elseif ($tender_submitted == 1 && $tender_result == 0 && $tender_result != 3) {
					$style = "style=\"background: rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.8);\"";
				} elseif (($tender_date < time()) OR $tender_result == 3) {
					$style = "style=\"background: rgba(0,0,0,0.1); border: solid 1px rgba(0,0,0,0.25); color: #ccc;\"";

				} else {
					$style = "style=\"background: rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.8);\"";
				}
				
				if ($tender_date > time()) {
					$deadline = " (" . DeadlineTime($tender_date - $nowtime) . ")";
				} else {
					unset($deadline);					
				}
				
				if (($nowtime > $tender_date) && ($nowtime < $time_line)) { echo "<div class=\"bodybox\" style=\"background: white; color: rgba(255,0,0,1); border: solid 1px rgba(255,0,0,0.8); font-size: 2em;\"><strong><span class=\"minitext\">Today is</span><br />" . TimeFormat($nowtime) . "</strong></div>"; }
										
				
				echo "<div class=\"bodybox\" $style><a href=\"index2.php?page=tender_edit&tender_id=$tender_id\" style=\"float: right; margin: 0 0 5px 5px;\"><img src=\"images/button_edit.png\" alt=\"Edit Tender\" /></a><p><strong><a href=\"index2.php?page=tender_view&amp;tender_id=$tender_id\">$tender_name</a></strong>$tender_type</p>";
				echo "<p>Deadline: ". date("d M Y",$tender_date) . $deadline . "<br /><span class=\"minitext\">" . $tender_client . "</span></p>";
				
				$time_line = $tender_date;
				
				echo "</div>";

				}
				
				echo "</div>";

				} else {

				echo "There are no tenders on the system.";

				}
				
				if ($submitted_total > 0 && (intval($_GET[tender_pending]) != 1)) {
				
					$success_rate = number_format ( 100 * ($successful_total / $submitted_total), 0 );
					
					echo "<div class=\"bodybox\"><p><strong>Statistics</strong></p><p>You have submitted $submitted_total tenders with a " . $success_rate . "% success rate.</p></div>";
					
				}
				
}

function NotAllowed() {
	
	echo "<h1>Access Denied</h1><p>You have insufficient privileges to view this page.</p>";
	
}

function NewPage() {

	GLOBAL $pdf;
	$pdf->addPage();
	$current_y = $pdf->GetY();
	$new_y = $current_y + 50;
	$pdf->SetY($new_y);

}

function Paragraph ($input) {
	
	GLOBAL $pdf;
	GLOBAL $format_font;
	
	$text_array = explode ("\n",$input);
	
	$header = 1;
	
	foreach ($text_array AS $para ) {
		
		$para = trim($para);
		
		
		
		$pdf->SetTextColor(0);
		if (substr($para,0,3) == "-- ") {
			$pdf->SetFont('ZapfDingbats','',4);
			$para = trim($para,"-- ");
			$pdf->SetX(0);
			$pdf->Cell(35,4,'n',0,0,R,0);
			$pdf->SetX(35);
			$pdf->SetFont($format_font,'',10);
			$pdf->MultiCell(145,4,$para,0,L);
		} elseif (substr($para,0,2) == "- ") {
			$pdf->SetFont('ZapfDingbats','',5);
			$para = trim($para,"- ");
			$pdf->SetX(0);
			$pdf->Cell(30,4,'l',0,0,R,0);
			$pdf->SetX(30);
			$pdf->SetFont($format_font,'',10);
			$pdf->MultiCell(145,4,$para,0,L);
		} elseif (substr($para,0,1) == "|") {
			if ($header == 1) { $pdf->SetLineWidth(0.5); $header = 0; } else { $pdf->SetLineWidth(0.2); }
			$row = explode ("|",$para);
			$delete = array_shift($row);
			foreach ($row AS $cell ) {
				$cell_width = 150 / count($row);
				$pdf->SetFont($format_font,'',10);
				$pdf->Cell($cell_width,7,$cell,1,0,L,0);
				$pdf->SetFont($format_font,'',10);
			}
			$pdf->Ln(7);
			$pdf->SetX(25);
		} else {
		$pdf->SetX(25);
		$pdf->SetFont($format_font,'',10);
		$pdf->MultiCell(150,4,$para,0,L);
		}
		
		
	
	}
	
	
}

function UpDate ($qms_date) {
						
						GLOBAL $pdf;
						
						$current_x = $pdf->GetX();
						$current_y = $pdf->GetY();
						$new_y = $pdf->GetY() + 2;
					
						$pdf->SetXY(180,$new_y);
						$pdf->SetTextColor(180);
						$pdf->SetDrawColor(180);
						$pdf->SetFont('Helvetica','',5);
						$pdf->Cell(0,2,$qms_date,0,0);
						$pdf->SetTextColor(0);
						
						$pdf->SetXY($current_x,$current_y);
					
					}
					
function AddBullets($input) {
	
		GLOBAL $pdf;
		
		if (substr($input,2) == "- ") {
			
			
		} else {
			
			
		}
	
	
}

function ProjectData($proj_id, $type) {
	
	GLOBAL $conn;
	$proj_id = intval($proj_id);
	$sql_proj = "SELECT * FROM intranet_projects WHERE proj_id = $proj_id LIMIT 1";
	$result_proj = mysql_query($sql_proj, $conn) or die(mysql_error());
	$array_proj = mysql_fetch_array($result_proj);
	
	if ($type = "name") {	
	$output = $array_proj['proj_num'] . " " . $array_proj['proj_name'];
	}
	
	return $output;
	
}

function ChecklistDate($proj_id, $checklist_item) {
	
	GLOBAL $conn;
	$proj_id = intval($proj_id);
	$checklist_item = intval (trim ($checklist_item,"#") );
	if ($proj_id > 0 AND $checklist_item > 0){
		
		$sql_checklist_date = "SELECT checklist_date FROM intranet_project_checklist WHERE checklist_project = $proj_id AND checklist_item = $checklist_item ORDER BY checklist_date DESC LIMIT 1";
		$result_checklist_date = mysql_query($sql_checklist_date, $conn) or die(mysql_error());
		$array_checklist_date = mysql_fetch_array($result_checklist_date);
		
		if ($array_checklist_date['checklist_date'] != "0000-00-00" && $array_checklist_date['checklist_date'] != NULL) {
			$output = strtotime( $array_checklist_date['checklist_date'] );
			$output = date("j F Y",$output);
		}
		
		return $output;
	
	}
	
}

function FindClause($qms_text) {
	
		GLOBAL $conn;
		if (strpbrk($qms_text,"^")) {
		
			$text_section = explode("^",$qms_text);
			$text_section = explode(" ",$text_section[1]);
			$text_section = intval($text_section[0]);
			if ($text_section > 0)
			$sql_checklist_ref = "SELECT qms_id,qms_toc1, qms_toc2,qms_toc3,qms_toc4 FROM intranet_qms WHERE qms_id = $text_section";
			$result_checklist_ref = mysql_query($sql_checklist_ref, $conn) or die(mysql_error());
			$array_checklist_ref = mysql_fetch_array($result_checklist_ref);
			$qms_id = $array_checklist_ref['qms_id'];
			
			$qms_clause = $array_checklist_ref['qms_toc1'];
			if ($array_checklist_ref['qms_toc2'] > 0) { $qms_clause = $qms_clause . "." . $array_checklist_ref['qms_toc2']; }
			if ($array_checklist_ref['qms_toc3'] > 0) { $qms_clause = $qms_clause . "." . $array_checklist_ref['qms_toc3']; }
			if ($array_checklist_ref['qms_toc4'] > 0) { $qms_clause = $qms_clause . "." . $array_checklist_ref['qms_toc4']; }
			
			$finder = "^" . $qms_id;
			
			$qms_text = str_replace($finder,$qms_clause,$qms_text);
			
		}
	
	return $qms_text;
	
}

function ClauseCrossReference($qms_text) {
	
		$test = 0;
	
		while ($test != 1) {
			
			if (substr_count($qms_text,"^") > 0) { 
				$qms_text = FindClause($qms_text);
				$test = 0;
			} else {
				$test = 1;
			}
			
		}

		return $qms_text;
		
}

function DrawingStatusDropdown ($current_status,$variable_name) {
	
	$drawing_status_array = array("","S0","S1","S2","S3","S4");
	sort($drawing_status_array);


echo "<select name=\"$variable_name\">";
		foreach ($drawing_status_array AS $drawing_status_list) {
		if ($drawing_status_list == $current_status) { $select = "selected=\"selected\""; } else { unset($select); }
		echo "<option value=\"$drawing_status_list\" $select>$drawing_status_list</option>";
	}
echo "</select>";

	
}

function DeadlineTime($time) {
	
	if ($time < 86400) {
		
		$output = round ($time / 3600) . " hours";
		
	} elseif ($time < 129600) {
		
		$output = round ($time / 86400) . " day";
		
		
	} elseif ($time < 1209600) {
		
		$output = round ($time / 86400) . " days";
		
	} elseif ($time < 4838400) {
		
		$output = round ($time / 604800) . " weeks";
	
	} else {
		
		$output = round ($time / 18396000) . " months";
	
	}
	
	return $output;
	
}

function ListProjectJournalEntries($proj_id) {
	
		global $conn;
		global $user_usertype_current;
		global $user_id_current;
		
		$proj_id = intval($proj_id);

					$sql = "SELECT * FROM intranet_projects_blog, intranet_projects, intranet_user_details WHERE blog_proj = proj_id AND proj_id = $proj_id AND blog_user = user_id AND (blog_access <= " . $user_usertype_current . " OR blog_access IS NULL) AND (blog_view = 0 OR blog_view = " . $user_id_current . ") order by blog_date DESC";
					$result = mysql_query($sql, $conn) or die(mysql_error());
					$result_project = mysql_query($sql, $conn) or die(mysql_error());
					$array_project = mysql_fetch_array($result_project);
					$proj_num = $array_project['proj_num'];
					$proj_name = $array_project['proj_name'];
					$user_name_first = $array_project['user_name_first'];
					$user_name_second = $array_project['user_name_second'];
					$user_id = $array_project['user_id'];


					$nowtime = time();

					if (mysql_num_rows($result) > 0) {
						
						echo "<p>" . mysql_num_rows($result) . " results found.</p>";

					echo "<table summary=\"List of Journal Entries for $proj_num $proj_name\">";

					$counter = 0;
					$title = NULL;
					$type = 0;

					while ($array = mysql_fetch_array($result)) {

							$blog_id = $array['blog_id'];
							$blog_title = $array['blog_title'];
							$blog_date = $array['blog_date'];
							$blog_type = $array['blog_type'];
							$blog_user = $array['blog_user'];
							$blog_user_name_first = $array['user_name_first'];
							$blog_user_name_second = $array['user_name_second'];
						
						if ($blog_type == "phone") { $blog_type_view = "Telephone Call"; $type++; }
						elseif ($blog_type == "filenote") { $blog_type_view = "File Note"; $type++; }
						elseif ($blog_type == "meeting") { $blog_type_view = "Meeting Note"; $type++; }
						elseif ($blog_type == "email") { $blog_type_view = "Email Message"; $type++; }
						else { $blog_type_view = NULL; $type = 0; }
						
						$blog_type_list = array("phone","filenote","meeting","email");
						

							echo "<tr>";
							echo "<td>$type.</td><td><a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id&amp;proj_id=$proj_id\">".$blog_title."</a>&nbsp;<a href=\"pdf_journal.php?blog_id=$blog_id\"><img src=\"images/button_pdf.png\" /></a></td>";
							echo "<td style=\"width: 20%;\"><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date\">".TimeFormat($blog_date)."</a></td>";
							echo "<td><a href=\"index2.php?page=user_view&amp;user_id=$user_id\">".$blog_user_name_first."&nbsp;".$blog_user_name_second."</a></td>";
							echo "<td style=\"width: 20%;\"><span class=\"minitext\">$blog_type_view</span></td>";
							echo "</tr>";


					$title = $blog_type;

					}


					echo "</table>";

					} else {

					echo "<p>There are no journal entries on the system for this project.</p>";

					}

}


function AlertBoxShow($user_id) {
	
		global $conn;
		$sql = "SELECT * FROM intranet_alerts WHERE alert_timestamp < " . time() . " AND alert_user = " . $user_id . " AND (alert_status = 0 OR alert_status = NULL) ORDER BY alert_timestamp DESC";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		if (mysql_num_rows($result) > 0) {
			echo "<div>";
			while ($array = mysql_fetch_array($result)) {
				$alert_id = $array['alert_id'];
				$alert_category = $array['alert_category'];
				$alert_message = $array['alert_message'];
				echo "<div class=\"warning\" style=\"height: 160px;\" id=\"target_" . $alert_id . "\"><form><input type=\"checkbox\" value=\"" . $alert_id . "\" class=\"alert_delete\" style=\"float: right; margin: 5px 5px 10px 10px;\" /></form><p><strong>" . $alert_category . "</strong></p>" . $alert_message . "</div>";
			}
			echo "</div>";
		}
}

function GetAdmins($user_usertype) {
	global $conn;
	
	if (intval($user_usertype) > 0) {
		$sql = "SELECT user_id FROM intranet_user_details WHERE user_usertype = " . intval($user_usertype) . " ORDER BY user_id";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$output_array = array();
		while ($array = mysql_fetch_array($result)) {
			$output_array[] = $array['user_id'];
		}
		return $output_array;
	}
	
}

function AlertBoxInsert($user_id,$alert_category,$alert_message,$alert_entryref,$snoozetime,$verbose,$alert_project) {
	
		global $conn;
		
		$alert_entryref = intval ( $alert_entryref );
		
		if ($alert_entryref > 0) {
		
			$verbose = intval($verbose);
			$snoozetime = intval($snoozetime);
			$user_id = intval($user_id);
			$alert_project = intval($alert_project);
			if ($alert_project == 0) { $alert_project = "NULL"; }
			
			$alert_url = "'" . addslashes ( $_SERVER['HTTP_REFERER'] ) . "'";
			
			$sql = "SELECT * FROM intranet_alerts WHERE alert_timestamp > " . (time() - $snoozetime) . " AND alert_user = " . $user_id . " AND alert_category = '" . $alert_category . "' AND alert_entryref = " . $alert_entryref . " LIMIT 1";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			if (mysql_num_rows($result) == 0) {
				$sql_add = "INSERT INTO intranet_alerts (alert_id, alert_user, alert_category, alert_message, alert_timestamp, alert_status, alert_entryref, alert_url, alert_project) VALUES (NULL, " . $user_id . ",'" . $alert_category . "','" . $alert_message . "'," . time() . "," . $verbose . ", " . $alert_entryref . ", " . $alert_url  . ", " . $alert_project . ")";
				$result_add = mysql_query($sql_add, $conn) or die(mysql_error());  

			}
		
		}
		

}

function CheckOutstandingTasks($user_id) {
	
		global $conn;

			$futuretime = time() - 43200;
			$sql3 = "SELECT tasklist_id FROM intranet_tasklist WHERE tasklist_person = '" . $user_id . "' AND tasklist_percentage < '100' ";
			$sql4 = "SELECT * FROM intranet_tasklist WHERE tasklist_person = '$_COOKIE[user]' AND tasklist_percentage < '100' AND tasklist_due < $futuretime AND tasklist_due > 0 ";
			$result3 = mysql_query($sql3, $conn) or die(mysql_error());
			$result4 = mysql_query($sql4, $conn) or die(mysql_error());
			$tasks_outstanding = mysql_num_rows($result3);
			$tasks_overdue = mysql_num_rows($result4);
			
		if ($tasks_overdue > 0 AND substr($_GET[page],0,8) != "tasklist") {
			
			if ($tasks_overdue > 1) { $tasks_plural = "tasks"; } else { $tasks_plural = "task"; }
			
			$outstanding = 1;
			$outstanding_tasks =  "<p class=\"body\">You have ".$tasks_overdue." ".$tasks_plural." outstanding. <a href=\"index2.php?page=tasklist_view&amp;subcat=user\">Click here</a> to view current task list.</p>";
			
			AlertBoxInsert($_COOKIE[user],"Tasks",$outstanding_tasks,0,86400);
			
		}

}

function CheckExpenses() {
	
	global $conn;

			

						$sql5 = "SELECT ts_expense_id FROM intranet_timesheet_expense WHERE ts_expense_verified = 0";
						$result5 = mysql_query($sql5, $conn) or die(mysql_error());
						$expenses_overdue = mysql_num_rows($result5);
						
					if ($expenses_overdue > 0 AND substr($_GET[page],0,17) != "timesheet_expense") {
						
						if ($expenses_overdue > 1) { $expenses_plural = "expenses claims"; } else { $expenses_plural = " expenses claim"; }
						
						$outstanding = 1;
						$outstanding_expenses = "<p class=\"body\">You have ".$expenses_overdue."&nbsp;".$expenses_plural." awaiting validation. <a href=\"index2.php?page=timesheet_expense_list\">Click here</a> to view oustanding items.</p>";
						
						AlertBoxInsert($_COOKIE[user],"Expenses",$outstanding_expenses,0,86400);
					}

}

function CheckFutureTenders() {
	
	global $conn;

		$weeks = 2;
		$seconds = 60 * 60 * 24 * 7 * $weeks;

		if ($user_usertype_current > 2 AND substr($_GET[page],0,6) != "tender") {

						$sql6 = "SELECT * FROM intranet_tender WHERE tender_date > '" . time() . "' AND (tender_date - " . time() . " < $seconds) ORDER BY tender_date";
						$result6 = mysql_query($sql6, $conn) or die(mysql_error());
						$tenders_soon = mysql_num_rows($result6);

						while ($array6 = mysql_fetch_array($result6)) {
							$tender_id = $array6['tender_id'];
							$tender_name = $array6['tender_name'];
							$tender_date = $array6['tender_date'];
							$days_to_go = ($tender_date - $nowtime) / 86400;
							$days_to_go = round($days_to_go);
							$outstanding_tender = "<p><a href=\"index2.php?page=tender_view&amp;tender_id=$tender_id\">$tender_name</a><br /><i> ".TimeFormatDetailed($tender_date)."&nbsp;(".$days_to_go."&nbsp;days to go)</i></p>";
							
							AlertBoxInsert($_COOKIE[user],"Tenders",$outstanding_tender,$tender_id,86400);

						}		
		}

}

function CheckCheckList() {
	
		global $conn;
		
		$today_date = date("Y-m-d", time());

		$sql5 = "SELECT * FROM intranet_projects, intranet_project_checklist LEFT JOIN intranet_project_checklist_items ON checklist_item = item_id  WHERE proj_id = checklist_project AND checklist_deadline = '$today_date' ORDER BY item_group, item_order, checklist_date, item_name";
		$result5 = mysql_query($sql5, $conn) or die(mysql_error());
		if (mysql_num_rows($result5) > 0) {
			while ($array5 = mysql_fetch_array($result5)) {
			$checklist_today = "<p><a href=\"index2.php?page=project_checklist&amp;proj_id=" . $array5['proj_id'] . "#" . $array5['item_id'] . "\">" . $array5['item_name'] . "</a></td><td>" . $array5['proj_num'] . " " . $array5['proj_name'] . "</p>";
			}
			AlertBoxInsert($_COOKIE[user],"Checklist",$checklist_today,$array5['item_id'],86400);
		}
	
}

function CheckInvoicesToBeIssued($user_id) {
	
	global $conn;
	
			$today_day = date("j",time()); $today_month = date("n",time()); $today_year = date("Y",time());
		$day_begin = mktime(0,0,0,$today_month,$today_day,$today_year);
		$day_end = $day_begin + 86400;
		$sql3 = "SELECT invoice_id, invoice_ref, proj_name, proj_num FROM intranet_timesheet_invoice, intranet_projects WHERE `invoice_date` BETWEEN '$day_begin' AND '$day_end' AND `proj_rep_black` = $user_id AND `proj_id` = `invoice_project` ORDER BY `invoice_ref` ";
		$result3 = mysql_query($sql3, $conn) or die(mysql_error());
		if (mysql_num_rows($result3) > 0) {
			while ($array3 = mysql_fetch_array($result3)) {
			$invoicemessage = "<p>Invoice ref. <a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=" . $array3['invoice_id'] . "\">" . $array3['invoice_ref'] . "</a> for" . $array3['proj_num'] . " " . $array3['proj_name'] . " to be issued today.</p>";
			AlertBoxInsert($user_id,"Invoices Issued",$invoicemessage,$array3['invoice_id'],86400);
			}
			
		}
	
}

function CheckInvoicesOverdue($user_id) {
	
		global $conn;

		$sql4 = "SELECT invoice_id, invoice_ref, proj_name, invoice_due FROM intranet_timesheet_invoice, intranet_projects WHERE `invoice_due` < " .time()." AND `proj_rep_black` = $user_id AND `proj_id` = `invoice_project` AND `invoice_paid` = 0 AND `invoice_baddebt` != 'yes' ORDER BY `invoice_due` ";
		$result4 = mysql_query($sql4, $conn) or die(mysql_error());
		if (mysql_num_rows($result4) > 0) {
			$invoiceduemessage = "<table>";
			while ($array4 = mysql_fetch_array($result4)) {
			$invoiceduemessage = "<p><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=" . $array4['invoice_id'] . "\">" . $array4['invoice_ref'] . "</a></td><td>" . $array4['proj_name'] . "</td><td>Due: <a href=\"index2.php?page=datebook_view_day&amp;time=" . $array4['invoice_due'] . "\"> " . TimeFormat($array4['invoice_due']) . "</a></p>";
			AlertBoxInsert($_COOKIE[user],"Invoices Overdue",$invoicemessage,$array3['invoice_id'],86400);
			}
		}
		
}

function CheckOutstandingTimesheets($user_id) {
	
	global $conn;
	
			$timesheetcomplete = TimeSheetHours($user_id,"");
		
		
		if ( $_COOKIE[timesheetcomplete] < 75) {
		
			$timesheetaction = "<p>Your timesheets are only " . $timesheetcomplete . "% complete - <a href = \"popup_timesheet.php\">please fill them out</a>. If your timesheet drops below " . $settings_timesheetlimit . "% complete, you will not be able to access the intranet.</p>";
			
			AlertBoxInsert($_COOKIE[user],"Timesheets",$timesheetaction,0,86400);
		
		}
	
}

function CheckTelephoneMessages($user_id) {
	
	global $conn;
	
		if ($_COOKIE[phonemessageview] > 0 OR $_COOKIE[phonemessageview] == NULL) {
		$sql = "SELECT * FROM intranet_phonemessage WHERE message_for_user = '$user_id' AND message_viewed = 0";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$messages_outstanding = mysql_num_rows($result);
		if ($messages_outstanding > 0) {
			while ($array = mysql_fetch_array($result)) {
				$telephonemessage = "<p>Call from " . $array['message_from_name'] . "";
				if ($array['message_from_name']) { $telephonemessage = $telephonemessage . ", " . $array['message_from_name']; }
				$telephonemessage = $telephonemessage . ". ";
				if ($array['message_text']) { $telephonemessage = $telephonemessage . "<br />Message: " . rtrim($array['message_text'],".") . "."; }
				if ($array['message_from_number']) { $telephonemessage = $telephonemessage . "<br />Number " . $array['message_from_number']; }
				if ($array['message_date']) { $telephonemessage = $telephonemessage . "<br /><i>Taken " . TimeFormat($array['message_date']) . "</i>"; }
				$telephonemessage = $telephonemessage . "</p>";
				AlertBoxInsert($_COOKIE[user],"Telephone Message",$telephonemessage,0,86400);
			}
		}
		}
	
}

function AlertDelete($alert_id, $user_id) {

	global $conn;
	
			
			if (intval($alert_id) > 0 && intval($user_id) > 0) {
		
				$sql_update = "UPDATE intranet_alerts SET alert_status = 1, alert_updated = " . time() . " WHERE alert_id = " . $alert_id . " AND alert_user = " . $user_id . " LIMIT 1";
		
				$result = mysql_query($sql_update, $conn) or die(mysql_error());
				
			}
			
}

function AlertsList($user_id) {

	global $conn;
	global $user_usertype_current;
	
	$user_usertype_current = intval($user_usertype_current);
	
	$user_id = intval($user_id);
	
		if ($_GET[view] == "all" && $user_usertype_current > 4) { unset($filter); } else { $filter = "WHERE alert_user = " . $user_id; }

		$sql = "SELECT * FROM intranet_alerts LEFT JOIN intranet_user_details ON user_id = alert_user LEFT JOIN intranet_projects ON alert_project = proj_id $filter ORDER BY alert_timestamp DESC LIMIT 100";
		
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result) > 0) {
			
			echo "<table>";
			
			echo "<tr><th>ID</th><th style=\"width: 20%;\">Subject</th><th>Content</th><th style=\"width: 10%;\">User</th><th style=\"text-align: right; width: 10%;\">Date</th><th style=\"width: 20%;\">Project</th><th style=\"text-align: right; width: 10%;\">Dismissed</th></tr>";
		
			while ($array = mysql_fetch_array($result)) { 
				
				if ($array['alert_status'] == 0) { $alert_message = "<strong>" . $array['alert_message'] . "</strong>"; } else { $alert_message = $array['alert_message']; }
				
				if ($array['alert_updated']) { $time_format = TimeFormat($array['alert_updated']) . "<br /><span class=\"minitext\">" . date("H:i",$array['alert_updated']) ."</span>"; } else { $time_format = "-"; }
				
				
							
				echo "<tr><td>" . $array['alert_id'] . "</td><td>";
				
				if ($user_usertype_current > 4 && $array['alert_url']) { echo "<a href=\"" . $array['alert_url'] . "\">". $array['alert_category'] . "</a>"; } else { echo $array['alert_category']; }
				
				echo "</td><td>" . $alert_message . "</td><td>" . $array['user_name_first'] . " " . $array['user_name_second'] . "</td><td style=\"text-align: right;\">" . TimeFormat($array['alert_timestamp'])  . "<br /><span class=\"minitext\">" . date("H:i",$array['alert_timestamp']) ."</span></td><td><a href=\"index2.php?page=project_actionstream&amp;proj_id=" . $array['proj_id'] . "\">" . $array['proj_num'] . "&nbsp;" . $array['proj_name']  . "</a></td><td style=\"text-align: right;\">" . $time_format  . "</td></tr>";
				
				
			
			}
			
			echo "</table>";
			
		
		} else {
			
			echo "<p>No log entries found.</p>";
			
		}

}

function UserAccessType($selectname,$user_usertype,$currentlevel,$maxlevel) {
	

	echo "<select name=\"$selectname\">";
		
			echo "<option value=\"1\"";
				if ($currentlevel == 1) { echo " selected=\"selected\" "; }
			echo ">Guest</option>";
			
			echo "<option value=\"2\"";
				if ($currentlevel == 2) { echo " selected=\"selected\" "; }
			echo ">Basic User</option>";
			
			echo "<option value=\"3\"";
				if ($currentlevel == 3) { echo " selected=\"selected\" "; }
			echo ">Standard User</option>";
			
			echo "<option value=\"4\"";
				if ($currentlevel == 4) { echo " selected=\"selected\" "; }
			echo ">Power User</option>";
			
			echo "<option value=\"5\"";
				if ($currentlevel > 4) { echo " selected=\"selected\" "; }
			echo ">Administrator</option>";
		
	
		echo "</select>";
		
}

function UsersList($active) {
	
	GLOBAL $conn;
	
			echo "<h1>Users</h1>";
	
			if ($active == 0) {
				echo "<h2>Active Users</h2>";
				echo "<div class=\"sub_menu\"><a class=\"menu_tab\" href=\"index2.php?page=user_list&amp;list_active=1\">All Users</a></div>";
				
				$showactive = " WHERE user_active = 1 ";
			
			} else {
				echo "<h2>All Users</h2>";
				
				echo "<div class=\"sub_menu\"><a class=\"menu_tab\" href=\"index2.php?page=user_list\">Active Users</a></div>";
				
				unset($showactive);
			}

			$sql = "SELECT * FROM intranet_user_details $showactive ORDER BY user_active DESC, user_name_second";
			$result = mysql_query($sql, $conn);
			
			
			echo "<table><tr><th>Name</th><th>Initials</th><th>Date Started</th><th>Date Ended</th><th>Mobile</th><th>Email</th><th colspan=\"2\">User Type</th><th style=\"text-align: right;\">Hourly Rate (Cost)</th><th style=\"text-align: right;\">Weekly Hours</th><th style=\"text-align: right;\" colspan=\"2\">Target Fee-Earning Hours<span class=\"minitext\"><br />Equivalent Hourly Rate<br />Total Weekly Rate</span></th></tr>";
			
			$cost_per_hour_total = 0;
			$cost_per_week_total = 0;
			$total_hours_week = 0;
			$total_hourly_worked = 0;
			$total_people = 0;
			$total_hourly_cost = 0;
			
			while ($array = mysql_fetch_array($result)) {
				
					$user_id = $array['user_id'];
					$user_name_first = $array['user_name_first'];
					$user_name_second = $array['user_name_second'];
					$user_initials = $array['user_initials'];
					$user_num_mob = $array['user_num_mob'];
					$user_email = $array['user_email'];
					$user_active = $array['user_active'];
					$user_usertype = $array['user_usertype'];
					$user_timesheet_hours = $array['user_timesheet_hours'];
					$user_prop_target = $array['user_prop_target'];
					if ($array['user_user_added'] > 0) { $user_user_added = TimeFormatDay($array['user_user_added']); } else { $user_user_added = "-"; }
					if ($array['user_user_ended'] > 0) { $user_user_ended = TimeFormatDay($array['user_user_ended']); } else { $user_user_ended = "-"; }
					$user_user_rate = "&pound;" . number_format($array['user_user_rate'],2);
					
					if ($user_active != 1) { $user_timesheet_hours = 0; }
					
					$fee_earning_hours_per_week = intval((1 - $user_prop_target) * $user_timesheet_hours);
					
					$cost_per_hour = $fee_earning_hours_per_week * $array['user_user_rate'] / $user_timesheet_hours;
					
					if ($cost_per_hour > 0) { $total_people++ ; $total_hourly_cost = $total_hourly_cost + $cost_per_hour; }
					
					$cost_per_week = $cost_per_hour * $user_timesheet_hours;
					
					$cost_per_hour_total = $cost_per_hour_total + $cost_per_hour;
					$cost_per_week_total = $cost_per_week_total + $cost_per_week;
					$total_hours_week = $total_hours_week + $fee_earning_hours_per_week;
					
					if ($user_usertype == 1) { $user_usertype = "(1)</td><td>Guest"; }
					elseif ($user_usertype == 2) { $user_usertype = "(2)</td><td>Basic User"; }
					elseif ($user_usertype == 3) { $user_usertype = "(3)</td><td>Standard User"; }
					elseif ($user_usertype == 4) { $user_usertype = "(4)</td><td>Power User"; }
					elseif ($user_usertype == 5) { $user_usertype = "(5)</td><td>Administrator"; }
					
					if ($user_active == "1") { $user_active_print = "Active Users"; } else { $user_active_print = "Inactive Users"; }
					
					if ($current_active != $user_active) { echo "<tr><td colspan=\"12\"><strong>$user_active_print</strong></td></tr>"; $current_active = $user_active;  }
					
					echo "<tr><td><a href=\"index2.php?page=user_view&amp;user_id=$user_id\">$user_name_first $user_name_second</a></td><td>$user_initials</td><td>$user_user_added</td><td>$user_user_ended</td><td>$user_num_mob</td><td>$user_email</td><td>$user_usertype</td><td style=\"text-align: right;\">$user_user_rate</td><td style=\"text-align: right;\">$user_timesheet_hours</td><td style=\"text-align: right;\">" . $fee_earning_hours_per_week . "<span class=\"minitext\"><br />&pound;" . number_format($cost_per_hour,2) . "<br />&pound;" . number_format($cost_per_week,2) . "</span>
					</td><td><a href=\"index2.php?page=user_edit&amp;status=edit&user_id=$user_id\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a></td></tr>";

								
			}
			
			echo "<tr><td>Total Fee Hours</td><td colspan=\"10\" style=\"text-align: right;\">" . number_format ( $total_hours_week ) . "</td><td rowspan=\"6\"></td></tr>";
			echo "<tr><td>Total Hourly Fee</td><td colspan=\"10\" style=\"text-align: right;\">" . MoneyFormat($cost_per_hour_total) . "</td></tr>";
			echo "<tr><td>Total Weekly Fee</td><td colspan=\"10\" style=\"text-align: right;\">" . MoneyFormat($cost_per_week_total) . "</td></tr>";
			echo "<tr><td>Total Fee Earners</td><td colspan=\"10\" style=\"text-align: right;\">" . number_format ($total_people) . "</td></tr>";
			echo "<tr><td>Average Hourly Fee</td><td colspan=\"10\" style=\"text-align: right;\">" . MoneyFormat($total_hourly_cost / $total_people) . "</td></tr>";
			echo "<tr><td>Average Weekly Cost</td><td colspan=\"10\" style=\"text-align: right;\">" . MoneyFormat(($total_hourly_cost / $total_people) * 40) . "</td></tr>";
			echo "</table>";
		
						
}


function CheckListRows($proj_id,$group_id,$showhidden) {

	global $conn;
	
	if ($showhidden != "yes") { $sqlhidden = " AND checklist_required != 1 "; } else { unset($sqlhidden); }
	
	$sql_checklist = "SELECT * FROM intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id AND checklist_project = $proj_id LEFT JOIN intranet_timesheet_group ON group_id = item_stage WHERE ((group_id = '$group_id') OR (item_stage IS NULL)) $sqlhidden ORDER BY item_group, item_order, checklist_date, item_name";

	$result_checklist = mysql_query($sql_checklist, $conn) or die(mysql_error());

	echo "
	<script>
	$(document).ready(function(){
		$(\".Row1\").dblclick(function(){
			var ThisRow = document.getElementsByClassName(\"Row1\");
			$(\".Row1\").hide();
			$(\".Row2\").show();
			$(\"#testslot\").html(\"Row Name:\" + ThisRow );
		});
		$(\".Row2\").change(function(){
			$(ThisRow).hide();
			$(\".Row1\").show();
		});
	});
	</script>";
	
	echo "<div id=\"testslot\"></div>";




	$current_item = 0;

	if (mysql_num_rows($result_checklist) > 0) {
						
					echo "<table>";
					echo "<tr><th>Item</th><th>Stage</th><th>Required</th><th style=\"width: 15%;\">Date Completed</th><th colspan=\"4\">Comment</th></tr>";

					$group = NULL;

					while ($array_checklist = mysql_fetch_array($result_checklist)) {
					$item_id = $array_checklist['item_id'];
					$item_name = $array_checklist['item_name'];
					$item_date = $array_checklist['item_date'];
					$item_group = $array_checklist['item_group'];
					$item_required = $array_checklist['item_required'];
					$item_notes = $array_checklist['item_notes'];
					
					$group_code = $array_checklist['group_code'];
					
					$checklist_id = $array_checklist['checklist_id'];
					$checklist_required = $array_checklist['checklist_required'];
					$checklist_date	= $array_checklist['checklist_date'];
					$checklist_comment = htmlentities ( $array_checklist['checklist_comment']);
					$checklist_user = $_COOKIE[user];
					$checklist_link	= $array_checklist['checklist_link'];
					$checklist_item	= $array_checklist['checklist_item'];
					$checklist_timestamp = time();
					$checklist_deadline = $array_checklist['checklist_deadline'];
					//$checklist_project = $proj_id;
					
					if ($item_group != $group) { echo "<tr><td colspan=\"8\"><strong>$item_group</strong></td></tr>"; }
					
						// Change the background color depending on status
						if ($checklist_required == 2 && ( $checklist_date == "0000-00-00" OR $checklist_date == NULL ) ) { $bg =  "class=\"alert_warning \""; } // red
						elseif ($checklist_required == 2 && ( $checklist_date != "0000-00-00" OR $checklist_date != NULL ) ) { $bg =  "class=\"alert_ok \""; } // green
						elseif ($checklist_required == 1) { $bg =  "class=\"alert_neutral \""; } // grey
						else { $bg =  "class=\" alert_neutral \""; } // grey
						
						
						
						
						if ($checklist_deadline != "0000-00-00" && $checklist_deadline != NULL) {
							$checklist_date = $checklist_date . "<br /><span class=\"minitext\">Deadline: $checklist_deadline</span>";
						}
					
					
					echo "<tr id=\"checklist_row_" . $item_id . "\" class=\"Row1\"><td $bg>";
					//if ($item_name_current != $item_name) { 
					
					echo $item_name;

					$item_name_current = $item_name;
					echo "</td>";
					
					echo "<td $bg>$group_code</td>";
					
					echo "<td $bg>";
					
					if (!$item) {
					
						if ($checklist_required == 1) { echo "Not Required"; }
						elseif ($checklist_required == 2) { echo "Required"; }
						else { echo "?"; }
					
					}
					
					echo "</td>";

					if (!$item) {	
						if ($checklist_date == 0) { $checklist_date = "-";}
						echo "<td $bg>$checklist_date</td>";
						echo "<td $bg>$checklist_comment</td>";
						if ($checklist_link) {
							echo "<td colspan=\"2\" $bg><a href=\"$checklist_link\" target=\"_blank\"><img src=\"images/button_internet.png\" /></a></td>";
						} elseif ($_GET[item] == $item_id) {
							echo "<td colspan=\"3\"  $bg></td>";
						} else {
							echo "<td colspan=\"2\" $bg></td>";
						}
					}

					
					if ($item_notes != NULL) {
					
						if ($_GET[item] != $item_id AND $_POST[item] != $item_id) { $hidden = "none"; } else { unset($hidden); }
					
						if (!$item) {
							echo "<td $bg><a href=\"javascript:void(0);\" onclick=\"hideRow($item_id, false);\"><img src=\"images/button_help.png\" alt=\"Help\" /></a></td>";
						}
						
						echo "</tr>";
						
						echo "<tr id=\"$item_id\" style=\"display: $hidden;\"><td colspan=\"7\" style=\"padding: 12px; background: rgba(255,255,255,1);\">$item_notes</td>";
						
					} else { echo "<td $bg></td>"; }
					
						echo "</tr>";

					
					$group = $item_group;
					
					$current_item = $item_id;
					
					echo "	<tr class=\"Row2\" style=\"display: none;\">
							<td $bg><input type=\"text\" name=\"item_name\" value=\"$item_name\" $bg /></td>
							<td $bg></td>
							<td $bg></td>
							<td $bg></td>
							<td $bg></td>
							<td $bg></td>
							<td $bg></td>
							<td $bg></td>
							</tr>";

					}
					
					echo "</table>";

} else { echo "<p>No checklist items found.</p>"; }





}

function InvoiceLineItems ($ts_fee_id, $highlight, $stage_fee) {
	
	global $conn;
	
	$highlight = $highlight . " font-size: 75%;";
	
	$ts_fee_id = intval($ts_fee_id);
	
	$invoice_total = 0;
	$invoice_paid_total = 0;
	$invoice_paid_remaining = 0;
	
	$sql = "SELECT * FROM intranet_timesheet_invoice_item, intranet_timesheet_invoice WHERE invoice_id = invoice_item_invoice AND invoice_item_stage = $ts_fee_id ORDER BY invoice_date, invoice_ref";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	if (mysql_num_rows($result) > 0) {
	
				while ($array = mysql_fetch_array($result)) {
					
					$invoice_id = $array['invoice_id'];
					$invoice_item_id = $array['invoice_item_id'];
					$invoice_item_invoice = $array['invoice_item_invoice'];
					$invoice_date = $array['invoice_date'];
					$invoice_paid = $array['invoice_paid'];
					$invoice_ref = $array['invoice_ref'];
					$invoice_item_novat = $array['invoice_item_novat'];
					$invoice_project = $array['invoice_project'];
					
					$invoice_total = $invoice_total + $invoice_item_novat;
					
					if ($invoice_paid) { $invoice_paid_total = $invoice_paid_total + $invoice_item_novat; }
					
					echo "	<tr>
							<td style=\"" . $highlight . "\" colspan=\"2\"></td>
							<td style=\"" . $highlight . "\"><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">" . $invoice_ref . "</a>";
					
					if (!$invoice_date_paid) { echo "&nbsp;<a href=\"index2.php?page=timesheet_invoice_items_edit&amp;proj_id=" . $invoice_project . "&amp;invoice_item_id=" . $invoice_item_id . "\"><img src=\"images/button_edit.png\" alt=\"Edit\" />"; }
					
					echo "</td>
							<td style=\"" . $highlight . "\">" . TimeFormat($invoice_date) . "</td>
							<td style=\"" . $highlight . "text-align: right;" . "\" colspan=\"3\">" . MoneyFormat($invoice_item_novat) . "</td>
							<td style=\"" . $highlight . "\" colspan=\"2\"></td>
							</tr>
						";
					}
					
					$stage_fee_remaining = $stage_fee - $invoice_total;
					
					if ($stage_fee_remaining > 0) { $stage_fee_remaining = "<span style=\"color: red; font-weight: bold;\">" . MoneyFormat($stage_fee_remaining) . "</span>"; }
					else { $stage_fee_remaining = MoneyFormat($stage_fee_remaining); }
					
					
					echo "	<tr>
							<td style=\"" . $highlight . "\" colspan=\"2\"></td>
							<td style=\"" . $highlight . "\" colspan=\"2\">Remaining to invoice</td>
							<td style=\"" . $highlight . "text-align: right;" . "\" colspan=\"3\">" . $stage_fee_remaining . "</td>
							<td style=\"" . $highlight . "\" colspan=\"2\"></td>
							</tr>
						";
				
				$invoice_paid_remaining = $invoice_total - $invoice_paid_total;
				
				if ($invoice_paid_remaining > 0) { $invoice_paid_remaining_print = "<span style=\"color: red;\">" . MoneyFormat($invoice_paid_remaining) . "</span>"; }
				else { $invoice_paid_remaining_print = MoneyFormat($invoice_paid_remaining); }
						
				if ($invoice_paid_remaining > 0) {
						
					echo "	<tr>
							<td style=\"" . $highlight . "\" colspan=\"2\"></td>
							<td style=\"" . $highlight . "\" colspan=\"2\">Remaining to be paid</td>
							<td style=\"" . $highlight . "text-align: right;" . "\" colspan=\"3\">" . $invoice_paid_remaining_print . "</td>
							<td style=\"" . $highlight . "\" colspan=\"2\"></td>
							</tr>
					";
					
				}
	
	}
	
	$output = array();
	$output[] = $invoice_total;
	$output[] = $invoice_paid_total;
	
	return $output;
	
}

function ProjectFees($proj_id) {

	global $conn;
	global $user_usertype_current;

	$proj_id = intval ( $proj_id );

				// Check if we're updating the current fee stage

				if ($_POST[fee_stage_current] > 0) { 

					$fee_stage_current = CleanNumber($_POST[fee_stage_current]);
					$sql_update = "UPDATE intranet_projects SET proj_riba = '$fee_stage_current' WHERE proj_id = '$proj_id' LIMIT 1";
					$result_update = mysql_query($sql_update, $conn) or die(mysql_error());
					
					$alert_message = "<p>The active fee stage for  " . GetProjectName($proj_id) .  " has been updated to " . $fee_stage_current . ".</p>";
					
					AlertBoxInsert($_COOKIE[user],"Project Fees",$alert_message,$fee_stage_current,4,0,$proj_id);

				}

				$sql = "SELECT * FROM intranet_projects, intranet_timesheet_fees LEFT JOIN intranet_timesheet_group ON group_id = ts_fee_group WHERE ts_fee_project = $proj_id AND proj_id = ts_fee_project ORDER BY ts_fee_commence, ts_fee_text";
				$result = mysql_query($sql, $conn) or die(mysql_error());


						if (mysql_num_rows($result) > 0) {
							
						echo "<table summary=\"Lists the fees for the selected project\">";
						
						echo "<form method=\"post\" action=\"index2.php?page=project_fees&amp;proj_id=$proj_id\">";
						
						echo "<tr><th colspan=\"3\">Stage</th><th>Begin Date</th><th>End Date</th><th>Likelihood</th><th";
						if ($user_usertype_current > 2) { echo " colspan=\"3\""; }
						echo ">Fee for Stage</th></tr>";
						

						$fee_total = 0;
						$invoice_total = 0;
						$counter = 0;
						$prog_begin = $proj_date_commence;
						
						$target_cost_total = 0;
						
						$invoice_total = 0;
						$invoice_paid_total = 0;
						
										while ($array = mysql_fetch_array($result)) {
												
												$ts_fee_id = $array['ts_fee_id'];
												$ts_fee_time_begin = $array['ts_fee_time_begin'];
												$ts_fee_time_end = $array['ts_fee_time_end'];
												$prog_end = $prog_begin + $ts_fee_time_end;
												$ts_fee_value = $array['ts_fee_value'];
												$ts_fee_text = $array['ts_fee_text'];
												$ts_fee_comment = $array['ts_fee_comment'];
												$ts_fee_commence = $array['ts_fee_commence'];
												$ts_fee_percentage = $array['ts_fee_percentage'];
												$ts_fee_invoice = $array['ts_fee_invoice'];
												$ts_fee_project = $array['ts_fee_project'];
												$ts_fee_pre = $array['ts_fee_pre'];
												$ts_fee_stage = $array['ts_fee_stage'];
												$group_code = $array['group_code'];
												if ($group_code == NULL) { $group_code = "-"; }
												$ts_fee_target = 1 / $array['ts_fee_target'];
												$ts_fee_prospect = $array['ts_fee_prospect'];
												$ts_fee_pre_lag = $array['ts_fee_pre_lag']; 
												$proj_value = $array['proj_value'];
												$proj_fee_percentage = $array['proj_fee_percentage'];
												$proj_riba = $array['proj_riba'];
												if ($array['proj_date_start'] != 0) { $proj_date_start = $array['proj_date_start']; } else { $proj_date_start = time(); }
												
												if ($ts_fee_comment != NULL) { $ts_fee_text = $ts_fee_text . "<span class=\"minitext\"><br />". $ts_fee_comment . "</span>"; }
												
												//  Pull any invoices from the system which relate to this fee stage
													$sql2 = "SELECT * FROM intranet_timesheet_invoice WHERE invoice_id = '$ts_fee_invoice' LIMIT 1";
													$result2 = mysql_query($sql2, $conn) or die(mysql_error());
													$array2 = mysql_fetch_array($result2);
													$invoice_id = $array2['invoice_id'];
													$invoice_ref = $array2['invoice_ref'];
													$invoice_date = $array2['invoice_date'];
												
												$proj_fee_total = $proj_value * ($proj_fee_percentage / 100);
												
												if ($ts_fee_percentage > 0) { $ts_fee_calc = ($proj_fee_total * ($ts_fee_percentage / 100)); } else { $ts_fee_calc = $ts_fee_value; }
												
												$fee_total = $fee_total + $ts_fee_calc;
												
												//  This bit needs re-writing to cross out any completed stages	
												// if ($proj_riba > $riba_order) { $highlight = $highlight."text-decoration: line-through;"; }
												
												$prog_begin = AssessDays ($ts_fee_commence);
												if ($prog_begin > 0) { $prog_end = $prog_begin + $ts_fee_time_end; } else { $prog_begin = time(); }
												
												// Calculate the time we are through the stage
														if (time() > $prog_begin && time() < $prog_end) {
														
															$percent_complete = time() - $prog_begin;
															$percent_complete = $percent_complete / $ts_fee_time_end;
														
														}
														elseif (time() > $prog_end) { $percent_complete = 1; }
														else { $percent_complete = 0; }
														$percent_complete = $percent_complete * 100;
														
														$percent_complete = round ($percent_complete,0);
														
														$fee_period_length = intval(($prog_end - $prog_begin) / 604800);
												
												if ($prog_begin > 0) { $prog_begin_print = "<a href=\"index2.php?page=datebook_view_day&amp;time=$prog_begin\">".TimeFormat($prog_begin)."</a>"; } else { $prog_begin_print = "-"; }
												if ($prog_end > 0) { $prog_end_print = "<a href=\"index2.php?page=datebook_view_day&amp;time=$prog_end\">".TimeFormat($prog_end)."</a>"; } else { $prog_end_print = "-"; }
												
												if ($prog_end > 0 && $fee_period_length > 0) { $prog_end_print = $prog_end_print . "<br /><span class=\"minitext\">Length: "  . $fee_period_length . " wks</span>"; }
									
												if ($ts_fee_pre) { $prog_begin_print_add = $ts_fee_pre ; }
												
												if ($ts_fee_pre_lag > 0) { $prog_begin_print_add = $prog_begin_print_add . " + " . round($ts_fee_pre_lag / 604800) . " weeks"; }
												
												if ($prog_begin_print_add) { $prog_begin_print = $prog_begin_print . "<br /><span class=\"minitext\"  onmouseover=\"ChangeBackgroundColor(\"stage_" . $prog_begin_print_add . "\")\">[" . $prog_begin_print_add . "]</span>"; }
												

												
												
												$proj_duration_print = "Complete: " . $percent_complete . "%</span>";
												
												if ( $percent_complete < 100) { $bg_color = "rgba(255,0,0,0.5)"; } else { $bg_color = "rgba(150,200,25,1)"; }
												
												$proj_duration_print = $proj_duration_print . "<div style=\"margin: 5px 0 0 0; background: $bg_color; height: 3px; width:" . $percent_complete . "%\"></div>";
												
												if ($ts_fee_id == $proj_riba) { $ts_fee_id_selected = " checked=\"checked\""; $highlight = " background: rgba(200,200,200,0.5);"; } else { unset($ts_fee_id_selected); unset($highlight); }
												
												if ($prog_end < time()) { $highlight = $highlight . " background: rgba(175,213,0,0.3);"; } elseif ( $ts_fee_id == $proj_riba ) { $highlight = $highlight . " background: rgba(255,175,0,0.3);"; } else { $highlight = $highlight . " background: rgba(255,0,0,0.3);"; }
												
												
												$fee_factored = $ts_fee_calc * $ts_fee_target; $fee_target = "<br /><span class=\"minitext\">Cumulative: "  . MoneyFormat($fee_total) . "<br />Target Cost: " . MoneyFormat($fee_factored). " + " .  number_format(((1 / $ts_fee_target) * 100) - 100 ) . "% profit</span>"; $target_cost_total = $target_cost_total + $fee_factored;
												
												if ($ts_fee_prospect == 0) { $ts_fee_likelihood = "Dead"; }
												elseif ($ts_fee_prospect == 10) { $ts_fee_likelihood = "Unlikely"; }
												elseif ($ts_fee_prospect == 25) { $ts_fee_likelihood = "Possible"; }
												elseif ($ts_fee_prospect == 50) { $ts_fee_likelihood = "Neutral"; }
												elseif ($ts_fee_prospect == 75) { $ts_fee_likelihood = "Probable"; }
												else { $ts_fee_likelihood = "Definite"; }
												
												$ts_fee_prospect = $ts_fee_likelihood . "&nbsp;(" . $ts_fee_prospect . "%)";
												
												
												echo "<tr id=\"stage_$ts_fee_id\"><td style=\"$highlight\"><input type=\"radio\" name=\"fee_stage_current\" value=\"$ts_fee_id\" $ts_fee_id_selected /> </td><td style=\"$highlight\">$group_code<br /><span class=\"minitext\">[$ts_fee_id]</span></td><td style=\"$highlight\">$ts_fee_text</td><td style=\"$highlight\">".$prog_begin_print."</td><td style=\"$highlight\">".$prog_end_print."</td><td style=\"$highlight\">".$ts_fee_prospect."</td><td  style=\"$highlight; text-align: right;\">".MoneyFormat($ts_fee_calc) . $fee_target ."</td>\n";
												echo "<td style=\"$highlight\">".$proj_duration_print."</td>";
												if ($user_usertype_current > 2) { echo "<td style=\"$highlight ;min-width: 30px;\"><a href=\"index2.php?page=timesheet_fees_edit&amp;ts_fee_id=$ts_fee_id\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a></td>"; }
												echo "</tr>";
												
												$totals_array = InvoiceLineItems($ts_fee_id,$highlight,$ts_fee_calc);

												$invoice_total = $invoice_total + $totals_array[0];
												$invoice_paid_total = $invoice_paid_total + $totals_array[1];				
												
												// Include a line if the invoice has been issued
												
												if ($invoice_id > 0) {
												
												echo "<tr>";
												if ($user_usertype_current > 2) { echo "<td colspan=\"5\">"; } else { echo "<td colspan=\"4\">"; }
													echo "Invoice Ref: <a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">$invoice_ref</a>, issued: ".TimeFormat($invoice_date);
														if ($invoice_paid > 0) { echo ", paid: ".TimeFormat($invoice_paid); }
													echo "</td></tr>";
												}
												
												$counter++;
												$prog_begin = $prog_begin + $ts_fee_time_end;
												
												unset($highlight);
												
											}
					
						unset($highlight);
						
						if ($user_usertype_current > 3) {
						
								echo "<tr><td colspan=\"6\"><strong>Total Fee for All Stages</strong></td><td style=\"text-align: right;\"><strong>". MoneyFormat($fee_total) . "</strong></td><td colspan=\"2\"></td></tr>";
								
								$profit = (( $fee_total / $target_cost_total ) - 1) * 100;
								
								$target_fee_percentage = number_format ($profit,2);
								
								echo "<tr><td colspan=\"6\"><strong>Target Cost for All Stages</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($target_cost_total). "</strong></td><td colspan=\"2\">" . $target_fee_percentage . "% profit overall</td></tr>";

								if ($invoice_total > 0) {
									echo "<tr><td colspan=\"6\">Invoice Total</td><td style=\"text-align: right;\">".MoneyFormat($invoice_total). "</td><td colspan=\"2\"></td></tr>";
								}
								
								if ($invoice_paid_total > 0) {
									echo "<tr><td colspan=\"6\">Invoice Paid Total</td><td style=\"text-align: right;\">".MoneyFormat($invoice_paid_total). "</td><td colspan=\"2\"></td></tr>";
								}
						
						}
						
						echo "<tr><td colspan=\"9\"><input type=\"submit\" value=\"Update Current Fee Stage\" /></td></tr>";
						
						echo "</form>";
						
						echo "</table>";
						
						$sql = "SELECT ts_fee_id, ts_fee_text FROM intranet_timesheet_fees WHERE ts_fee_project = $proj_id ORDER BY ts_fee_text, ts_fee_time_begin";
						$result = mysql_query($sql, $conn) or die(mysql_error());
						
						$sql_count = "SELECT ts_project FROM intranet_timesheet WHERE ts_project = $proj_id AND ts_stage_fee = 0";
						$result_count = mysql_query($sql_count, $conn) or die(mysql_error());
						$null_rows = mysql_num_rows($result_count);
						
						
						if ($user_usertype_current > 3 && mysql_num_rows($result) > 0 && $null_rows > 0) { 
						
									echo "<fieldset><legend>Reconcile Unassigned Hours</legend>";
									
											echo "<p>Move all unassigned hours ($null_rows entries) to this fee stage:</p>";
											
											echo "<p><form action=\"index2.php?page=project_fees&amp;proj_id=$proj_id\" method=\"post\">";
											echo "<input type=\"hidden\" name=\"action\" value=\"fee_move_unassigned\" />";
											
											echo "<select name=\"ts_fee_id\">";
											
											while ($array = mysql_fetch_array($result)) {
												
												$ts_fee_id = $array['ts_fee_id'];
												$ts_fee_text = $array['ts_fee_text'];
												
												if ($proj_riba == $ts_fee_id) { $selected = "selected = \"selected\""; } else { unset($selected); }
												
												echo "<option value=\"$ts_fee_id\" $selected>$ts_fee_text</option>";
												
											
											}
											
											echo "</select>";
											echo "&nbsp;<input type=\"hidden\" name=\"proj_id\" value=\"$proj_id\" />";
											echo "<input type=\"submit\"  onclick=\"return confirm('Are you sure you want to move all unallocated hours to this fee stage?')\">";
											
											echo "</form></p>";
											
											echo "<p>Alternatively, <a href=\"index2.php?page=timesheet_fee_reconcile&amp;proj_id=$proj_id\">click here</a> to undertake detailed reconciliation.</p>";
									
									echo "</fieldset>";
						
						}
						
				} else {

					echo "<p>There are no fee stages on the system for this project.</p>";
					
				}
				
}
				
function ProjectParticulars($proj_id) {

global $conn;



					if ($proj_date_start > 0 OR $proj_date_complete > 0 OR $proj_date_proposal > 0 OR $proj_date_appointment > 0) {
							echo "<h3>Project Dates</h3><table summary=\"Project Dates\">";
							if ($proj_date_proposal > 0) { echo "<tr><td style=\"width: 40%;\">Date of Proposal</td><td>".TimeFormat($proj_date_proposal)."</td></tr>"; }
							if ($proj_date_appointment > 0) { echo "<tr><td style=\"width: 40%;\">Date of Appointment</td><td>".TimeFormat($proj_date_appointment)."</td></tr>"; }
							if ($proj_date_start > 0) { echo "<tr><td style=\"width: 40%;\">Start Date</td><td>".TimeFormat($proj_date_start)."</td></tr>"; }
							if ($proj_date_complete > 0) { echo "<tr><td style=\"width: 40%;\">Completion Date</td><td>".TimeFormat($proj_date_complete)."</td></tr>"; }
							echo "</table>";
					}		
					

					echo "<table summary=\"Project Particulars\">";

					if ($array['proj_date_start'] != NULL) { $proj_date_start = date("jS F Y", $array['proj_date_start']); } else { unset($proj_date_start); }
					if ($array['proj_date_compelte'] != NULL) { $proj_date_complete = date("jS F Y", $array['proj_date_complete']); } else { unset($proj_date_complete); }

					// Determine the procurement method

					if ($proj_procure != NULL) {

						$sql_procure = "SELECT * FROM intranet_procure where procure_id = $proj_procure LIMIT 1";
						$result_procure = mysql_query($sql_procure, $conn);
						$array_procure = mysql_fetch_array($result_procure);
						$proj_procure = $array_procure['procure_title'];
					}

					echo "<tr><td style=\"width: 40%;\">Project Leader</td><td>";

					// Get the user details

						$sql_proj_leader = "SELECT user_name_first, user_name_second, user_email FROM intranet_user_details where user_id = '$proj_rep_black' LIMIT 1 ";
						$result_proj_leader = mysql_query($sql_proj_leader, $conn) or die(mysql_error());

						$array_proj_leader = mysql_fetch_array($result_proj_leader);
						$name_first = $array_proj_leader['user_name_first'];
						$name_second = $array_proj_leader['user_name_second'];
						$user_email = $array_proj_leader['user_email'];
						
						echo "<a href=\"index2.php?page=user_view&amp;user_id=111\">".$name_first." ".$name_second."</a>";

						if ($user_email != NULL) { echo "<a href=\"mailto:".$user_email."\"><img src=\"images/button_email.png\" alt=\"Send email to $name_first&nbsp;$name_second \" /></a>"; }

					echo "</td></tr>
					<tr><td>Database ID</td><td  >$proj_id</td></tr>
					<tr><td>Active</td><td  >";
					if ($proj_active == 0) {
					echo "No";
					} else {
					echo "Yes";
					}
					echo "</td></tr>";

					echo "</table>";

}


function ProjectInvoices($proj_id) {

		global $conn;

		$sql = "SELECT * FROM intranet_timesheet_invoice WHERE invoice_project = '$proj_id' order by invoice_date, invoice_ref";
		$result = mysql_query($sql, $conn) or die(mysql_error());

		if (mysql_num_rows($result) > 0) {

		echo "<table summary=\"Invoice Schedule\">";

		echo "<tr><td><strong>Invoice Number</strong></td><td><strong>Issued</strong></td><td><strong>Due</strong></td><td><strong>Paid</strong></td></tr>";

		$invoice_total_sub = 0;
		$invoice_total_paid = 0;
		$invoice_total_all = 0;

		while ($array = mysql_fetch_array($result)) {

				$invoice_item_total = 0;
		  
				$invoice_id = $array['invoice_id'];
				$invoice_date = $array['invoice_date'];
				$invoice_due = $array['invoice_due'];
				$invoice_project = $array['invoice_project'];
				$invoice_ref = $array['invoice_ref'];
				$invoice_paid = $array['invoice_paid'];
				$invoice_notes = $array['invoice_notes'];
				$invoice_baddebt = $array['invoice_baddebt'];
				$rowspan = 3;
				
				if ($invoice_date < time()) {
				$confirm = "onClick=\"javascript:return confirm('This item has been invoiced - are you sure you want to edit it?')\""; }
				else { unset($confirm); }
				
						// Pull the corresponding results from the Invoice Item list
						$sql2 = "SELECT * FROM intranet_timesheet_invoice_item WHERE invoice_item_invoice = '$invoice_id'";
						$result2 = mysql_query($sql2, $conn) or die(mysql_error());
						if (mysql_num_rows($result2) > 0) { $rowspan++; }
						// Pull the corresponding results from the Expenses List
						$sql3 = "SELECT ts_expense_value, ts_expense_vat FROM intranet_timesheet_expense WHERE ts_expense_invoiced = '$invoice_id'";
						$result3 = mysql_query($sql3, $conn) or die(mysql_error());
						if (mysql_num_rows($result3) > 0) { $rowspan++; }
				
				if (time() > $invoice_due AND $invoice_paid < 1) { $highlight = " style=\"background-color: #$settings_alertcolor\" "; $highlight2 = "style=\"background-color: #$settings_alertcolor; text-align: right;\"";  } else { $highlight = ""; $highlight2 = "style=\"text-align: right;\""; }
				
		if ($invoice_baddebt == "yes") { echo "<tr><td colspan=\"4\" $highlight><strong>Listed as a bad debt</strong></td></tr>"; }
			
		echo "<tr>";	
		echo "<td $highlight rowspan=\"$rowspan\" style=\"width: 25%;\"><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">".$invoice_ref."</a>";
		if ($user_usertype_current > 3) {echo "&nbsp;<a href=\"index2.php?page=timesheet_invoice_edit&amp;status=edit&amp;invoice_id=$invoice_id\" $confirm><img src=\"images/button_edit.png\" alt=\"Edit Invoice\" /></a>"; }
		if ($invoice_notes != NULL) { echo "<br />".TextPresent($invoice_notes); }
		echo "</td>";
		echo "<td $highlight>".TimeFormat($invoice_date)."</td>";
		echo "<td $highlight>".TimeFormat($invoice_due)."</td>";
		if ($invoice_paid > 0) { echo "<td $highlight>".TimeFormat($invoice_paid)."</td>"; } else { echo "<td $highlight></td>"; }
		echo "</tr>";


				// Output the Invoice Item details
				if (mysql_num_rows($result2) > 0) {
					while ($array2 = mysql_fetch_array($result2)) {
					$invoice_item_novat = $array2['invoice_item_novat'];
					$invoice_item_vat = $array2['invoice_item_vat'];
					if ($invoice_paid > 0) { $invoice_total_paid = $invoice_total_paid + $invoice_item_novat; }
					$invoice_item_vat_total = $invoice_item_vat_total + $invoice_item_vat;
					$invoice_item_total = $invoice_item_total + $invoice_item_novat;
					$invoice_total_all = $invoice_total_all + $invoice_item_novat;
					$invoice_total_sub = $invoice_total_sub + $invoice_item_novat;
				}
					echo "<tr><td colspan=\"2\" $highlight>Fees</td><td $highlight2>".MoneyFormat($invoice_item_total)."</td></tr>";
				}
				
						// Output the Expenses details
				if (mysql_num_rows($result3) > 0) {
					$invoice_expense_total = 0;
					while ($array3 = mysql_fetch_array($result3)) {
					$ts_expense_novat = $array3['ts_expense_novat'];
					$ts_expense_vat = $array3['ts_expense_vat'];
					$invoice_expense_total = $invoice_expense_total + $ts_expense_value;
					$invoice_item_vat_total = $invoice_item_vat_total + $ts_expense_vat;
					}
					echo "<tr><td colspan=\"2\" $highlight>Expenses</td><td $highlight2>".MoneyFormat($invoice_expense_total)."</td></tr>";
					//$invoice_total_all = $invoice_total_all + $invoice_expense_total;
					$invoice_total_sub = $invoice_total_sub + $invoice_expense_total;
					// if ($invoice_paid > 0) { $invoice_total_paid = $invoice_total_paid + $invoice_expense_total; }
					}
					
		echo "<tr><td colspan=\"2\" $highlight>Sub Total</td><td $highlight2>".MoneyFormat($invoice_total_sub)."</td></tr>";
		echo "<tr><td colspan=\"2\" $highlight><u>Invoice Total</u> (gross, including expenses)</td><td $highlight2><u>".MoneyFormat($invoice_item_vat_total)."</u></td></tr>";
				
				$invoice_total_sub = 0;
				$invoice_item_vat_total = 0;


		}

		echo "<tr><td colspan=\"3\"><strong>Issued (net, excluding expenses)</strong><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_total_all)."</strong></td></tr>";

		echo "<tr><td colspan=\"3\"><strong>Paid (net)</strong><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_total_paid)."</strong></td></tr>";

		$invoice_outstanding = $invoice_total_all - $invoice_total_paid;

		echo "<tr><td colspan=\"3\"><strong>Outstanding (net)</strong><td style=\"text-align: right\"><strong>".MoneyFormat($invoice_outstanding)."</strong></td></tr>";

		echo "</table>";

		} else {

			echo "<p>There are no invoices on the system for this project.</p>";

		}

}

function InsufficientRights() {
	
	echo "<p>You do not have sufficient rights to perform this action.</p>";
		
}


function ProjectContacts($proj_id,$user_usertype_current) {

global $conn;
$proj_id = intval($proj_id);

			
			$sql_contact = "SELECT * FROM contacts_disciplinelist, contacts_contactlist, intranet_contacts_project LEFT JOIN contacts_companylist ON contact_proj_company = contacts_companylist.company_id WHERE contact_proj_contact = contact_id  AND discipline_id = contact_proj_role AND contact_proj_project = $proj_id ORDER BY discipline_name, contact_namesecond";
			$result_contact = mysql_query($sql_contact, $conn) or die(mysql_error());

			if (mysql_num_rows($result_contact) > 0) {

			echo "<div><table>";
				while ($array_contact = mysql_fetch_array($result_contact)) {
					$contact_id = $array_contact['contact_id'];
					$contact_namefirst = $array_contact['contact_namefirst'];
					$contact_namesecond = $array_contact['contact_namesecond'];
					$company_name = $array_contact['company_name'];
					$company_id = $array_contact['company_id'];
					$contact_email = $array_contact['contact_email'];
					$contact_telephone = $array_contact['contact_telephone'];
					$contact_mobile = $array_contact['contact_mobile'];
					$company_phone = $array_contact['company_phone'];
					$contact_company = $array_contact['contact_company'];
					$discipline_id = $array_contact['discipline_id'];
					$discipline_name = $array_contact['discipline_name'];
					$contact_proj_id = $array_contact['contact_proj_id'];
					$contact_proj_note = $array_contact['contact_proj_note'];
					$contact_proj_company = $array_contact['contact_proj_company'];
				
					
			print "<tr><td style=\"width: 30%;\"><a href=\"index2.php?page=discipline_view&amp;discipline_id=$discipline_id\">$discipline_name</a></td>";
			echo "<td";
			if (trim($contact_proj_note) == "") { echo " colspan=\"2\" "; }
			echo "><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">";
			echo "$contact_namefirst $contact_namesecond";
			echo "</a>";
			if ($company_name != NULL) { echo ",&nbsp;<a href=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">".$company_name."</a>"; }
			if ($company_change != NULL) { echo "$company_change"; }
			if ($contact_email != NULL) { echo "<br />Email: $contact_email&nbsp;<a href=\"mailto:$contact_email\"><img src=\"images/button_email.png\" alt=\"Email\"/></a>"; }
			if ($contact_telephone != NULL) { echo "<br />T: $contact_telephone"; } elseif ($company_phone != NULL) { echo "<br />T: $company_phone"; }
			if ($contact_mobile != NULL) { echo "<br />M: $contact_mobile"; }
			echo "</td>";
			if (trim($contact_proj_note) != "") {
			echo "<td style=\"width: 25%;\">".$contact_proj_note.$note."</td>";
			}
			echo "<td><a href=\"index2.php?page=project_contacts&amp;contact_proj_id=$contact_proj_id&amp;proj_id=$_GET[proj_id]&amp;action=project_contact_remove&amp;contact_proj_id=$contact_proj_id\" onClick=\"javascript:return confirm('Are you sure you want to delete this project contact?');\"><img src=\"images/button_delete.png\" /></a></td><td><a href=\"index2.php?page=project_contacts&amp;contact_proj_id=$contact_proj_id&amp;proj_id=$_GET[proj_id]\"><img src=\"images/button_edit.png\" /></a></td></tr>";


			}
			echo "</table></div>";

			} else { echo "<div><p>No Project Contacts Found.</p></div>"; }

}


function ProjectContactEdit($proj_id,$contact_proj_contact) {

global $conn;

		$proj_id = intval($proj_id);
		$contact_proj_id = intval($contact_proj_contact);

		// First, identify if we're adding or editing

		if ( intval ( $contact_proj_id ) > 0 ) {

				$contact_proj_array = ProjectContactCheck($_GET[contact_proj_id]);
				$contact_proj_id = $contact_proj_array[0]; 
				$contact_proj_contact = $contact_proj_array[1];
				$contact_proj_role = $contact_proj_array[2];
				$contact_proj_note = $contact_proj_array[3];
				$contact_proj_company = $contact_proj_array[4];
				$contact_id = $contact_proj_array[5];

		// work out the CURRENT company to see if the contact has changed

		$sql_check_previous = "SELECT contact_namefirst, contact_namesecond, contact_company FROM contacts_contactlist WHERE contact_id = " . intval ( $contact_id ) . " LIMIT 1 ";
		$result_check_previous = mysql_query($sql_check_previous, $conn) or die(mysql_error());
		$array_check_previous = mysql_fetch_array($result_check_previous);
		$contact_company_previous = $array_check_previous['contact_company'];
		$contact_namefirst = $array_check_previous['contact_namefirst'];
		$contact_namesecond = $array_check_previous['contact_namesecond'];

		echo "<h2>Edit Project Contact Entry for $contact_namefirst $contact_namesecond</h2>";
		} else {
		echo "<h2>Add Project Contacts</h2>";
		}

		echo "<div><form method=\"post\" action=\"index2.php?page=project_contacts&amp;proj_id=$proj_id\">";

		if ($contact_proj_id > 0) {

					if ($contact_proj_company != $contact_company_previous) {
					echo "<div class=\"form_50\"><p><strong>$contact_proj_company / $contact_company_previous <br />Note:</strong><br />The contact listed for this project is no longer with the company which undertook the work on this project. Please ensure that the company listed below is correct.</p></div>";
					}

					// Contact company

					print "<div class=\"form_50\">Company:<br /><select name=\"contact_proj_company\">";

					$sql_company = "SELECT company_name, company_postcode, company_id FROM contacts_companylist ORDER BY company_name, company_postcode";
					$result_company = mysql_query($sql_company, $conn) or die(mysql_error());
					
					if ($contact_proj_company > 0) { $company_selected = $contact_proj_company;} elseif ($project_company > 0) { $company_selected = $project_company;} else { $company_selected = NULL; }
					
					echo "<option value=\"\">-- None --</option>";
						while ($array_company = mysql_fetch_array($result_company)) {

							$company_id = $array_company['company_id'];
							$company_name = $array_company['company_name'];
							$company_postcode = $array_company['company_postcode'];
							
							if ($company_id == $company_selected) { $selected = "selected=\"selected\""; } else { $selected = NULL; }
							echo "<option value=\"$company_id\" $selected>$company_name, $company_postcode</option>\n";
					}

					echo "</select>";
					echo "<input type=\"hidden\" value=\"$contact_id\" name=\"contact_proj_contact\" >";
					echo "</div>";

		} else {
		
			$sql_contact = "SELECT contact_id, contact_namefirst, contact_namesecond, contact_company, company_name, company_postcode, company_id FROM contacts_contactlist LEFT JOIN contacts_companylist ON contacts_contactlist.contact_company = contacts_companylist.company_id WHERE contact_namefirst != '' AND contact_namesecond != '' AND contact_namesecond NOT LIKE '&%' AND contact_namesecond NOT LIKE '-%' AND contact_namesecond NOT LIKE '?%' ORDER BY contact_namesecond, contact_namefirst, contact_company";
			$result_contact = mysql_query($sql_contact, $conn) or die(mysql_error());
			echo "<div class=\"form_50\">Contact:<br />";
			echo "<select name=\"contact_proj_contact\">";
			
					while ($array_contact = mysql_fetch_array($result_contact)) {

						$contact_id = $array_contact['contact_id'];
						$company_id = $array_contact['company_id'];
						$contact_namefirst = $array_contact['contact_namefirst'];
						$contact_namesecond = $array_contact['contact_namesecond'];
						$contact_company = $array_contact['contact_company'];
						$contact_postcode = $array_contact['contact_postcode'];
						$company_name = $array_contact['company_name'];
						$company_postcode = $array_contact['company_postcode'];
						
						$name_print = $contact_namesecond.", ".$contact_namefirst;
						
						if ($contact_proj_id == NULL AND $contact_company) { $print_company = "- " . $company_name." [".$company_postcode."]"; } else { $print_company = NULL; }
						if ($contact_proj_contact == $contact_id) { $selected = "selected=\"selected\""; $project_company = $company_id; } else { $selected = NULL; }
						echo "<option value=\"$contact_id\" $selected>$name_print $print_company</option>\n";
			}
			
			echo "</select></div>";
		
		
		}

		echo "<div class=\"form_50\">Role<br />";
		$sql_disc = "SELECT discipline_id, discipline_name, discipline_ref FROM contacts_disciplinelist ORDER BY discipline_name";
		$result_disc = mysql_query($sql_disc, $conn) or die(mysql_error());
		print "<select name=\"contacts_discipline\">";

			while ($array_disc = mysql_fetch_array($result_disc)) {

				$discipline_id = $array_disc['discipline_id'];
				$discipline_name = $array_disc['discipline_name'];
				if ($contact_proj_role == $discipline_id) { $selected = "selected=\"selected\""; } else { $selected = NULL; }
				echo "<option value=\"$discipline_id\" $selected>$discipline_name</option>\n";
		}

		echo "</select></div><div class=\"form_50\">Notes:<br /><textarea name=\"contact_proj_note\" cols=\"38\" rows=\"3\">";
		if ($_GET[contact_proj_id] > 0) { echo $contact_proj_note; }
		echo "</textarea></div>";

		echo "<div class=\"form_100\">";

		if ($_GET[contact_proj_id] > 0) {
		echo "<input type=\"hidden\" name=\"action\" value=\"project_contact_edit\" /><input type=\"hidden\" name=\"contact_proj_project\" value=\"$_GET[proj_id]\" /><input type=\"hidden\" name=\"contact_proj_id\" value=\"$contact_proj_id\" /><input type=\"submit\" value=\"Update Contact\" />";
		} else {
		echo "<input type=\"hidden\" name=\"action\" value=\"project_contact_add\" /><input type=\"hidden\" name=\"contact_proj_project\" value=\"$_GET[proj_id]\" /><input type=\"submit\" value=\"Add Contact\" />";
		}

		echo "</div>";

		echo "</form></div>";

}

function ProjectContactCheck($contact_proj_id) {

	global $conn;

		$sql_check = "SELECT contact_proj_contact, contact_proj_role, contact_proj_note, contact_proj_company FROM intranet_contacts_project WHERE contact_proj_id = '$contact_proj_id' LIMIT 1 ";
		$result_check = mysql_query($sql_check, $conn) or die(mysql_error());
		$array_check = mysql_fetch_array($result_check);
		
		$return_array = array();
		
		$return_array[] = $contact_proj_id;
		$return_array[] = $array_check['contact_proj_contact'];
		$return_array[] = $array_check['contact_proj_role'];
		$return_array[] = $array_check['contact_proj_note'];
		$return_array[] = $array_check['contact_proj_company'];
		$return_array[] = $array_check['contact_proj_contact'];
		
		return $return_array;
		
}


function ProjActive($input,$input2,$proj_id) {
	if ($input != "1") { $output = "<del><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$input2</a></del>"; } else { $output = "<a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$input2</a>"; }
	return $output;
}
			
function TimeRemaining($proj_id, $ts_fee_id, $ts_fee_target, $ts_fee_value) {
		GLOBAL $conn;
		GLOBAL $user_id;
		GLOBAL $user_usertype_current;
		if ($ts_fee_id != NULL) {
			
			// Establish cost of stage to date for this user
			$sql_user = "SELECT SUM(ts_cost_factored), user_user_rate FROM intranet_timesheet, intranet_user_details WHERE ts_user = user_id AND ts_user = $_COOKIE[user] AND ts_stage_fee = $ts_fee_id";
			$result_user = mysql_query($sql_user, $conn) or die(mysql_error());
			$array_user = mysql_fetch_array($result_user);
			$ts_cost_factored_user = $array_user['SUM(ts_cost_factored)'];
			$user_user_rate = $array_user['user_user_rate'];
			
			// Establish cost of stage to date for all users
			$sql_all = "SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE ts_stage_fee = $ts_fee_id";
			$result_all = mysql_query($sql_all, $conn) or die(mysql_error());
			$array_all = mysql_fetch_array($result_all);
			$ts_cost_factored_all = $array_all['SUM(ts_cost_factored)'];
			$cost_remaining_all = $ts_fee_value - $ts_cost_factored_all;
			
			// Establish hours to date on project if no fee stage
			if ($ts_fee_value == 0) {
			$sql_hours = "SELECT SUM(ts_hours) FROM intranet_timesheet WHERE ts_project = $proj_id AND ts_user = $_COOKIE[user]";
			$result_hours = mysql_query($sql_hours, $conn) or die(mysql_error());
			$array_hours = mysql_fetch_array($result_hours);
			$ts_hours_total = $array_hours['SUM(ts_hours)'];
			}
			
			$user_percent = $ts_cost_factored_user / $ts_cost_factored_all;
			$user_cost = $user_percent * $cost_remaining_all;
			$hours_remaining_user = round ( $user_cost / $user_user_rate );
			
					$cost_percentage = $ts_cost_factored_all / ( $ts_fee_value / $ts_fee_target);
			
			$cost_percentage_cost = $ts_cost_factored_all / $ts_fee_value;

			if ($hours_remaining_user > 0 && $user_percent > 0.1 && $cost_percentage > 0.2 && $cost_percentage < 1) {
			$row_text = "<span class=\"minitext\"><i>You have <strong>" . round($hours_remaining_user) . "</strong> hour(s) remaining on this stage</i></span>";
			$row_color = "alert_warning";
			} elseif ( $cost_percentage > 1 && $cost_percentage_cost < 1 ) {
			$percent_over = round(100 * ($cost_percentage - 1) );
			$row_text = "<span class=\"minitext\"><i>This fee stage has overspent target profitability by <strong>" . $percent_over . "%</strong>.</i></span>";
			$row_color = "alert_careful";
			} elseif ( $cost_percentage_cost > 1) {
			$percent_over = round(100 * ($cost_percentage_cost - 1) );
			$row_text = "<span class=\"minitext\"><i>This fee stage has overspent by <strong>" . $percent_over . "%</strong> and is now losing money.</i></span>";
			$row_color = "alert_warning";
			} elseif ( $ts_fee_value == 0 && $ts_fee_id > 0) {
			$row_text = "<span class=\"minitext\"><i>There is no fee currently associated with this stage.<br />Your project hours to date: <strong>" . number_format ( $ts_hours_total,1) . "</strong></i></span>";
			$row_color = "alert_neutral";
			} elseif ( $ts_fee_value == 0) {
			$row_text = "<span class=\"minitext\"><i>There is no fee stage currently associated with this project.<br />Your project hours to date: <strong>" . number_format ( $ts_hours_total,1) . "</strong></i></span>";
			$row_color = "alert_neutral";
			} else {
			$row_color = "alert_ok";
			}
			
			if ($user_usertype_current > 4 && $_GET[maintenance] == "yes") {
				$row_text = $row_text . "<br />user_user_rate = $user_user_rate";
				$row_text = $row_text . "<br />ts_cost_factored_user = $ts_cost_factored_user";
				$row_text = $row_text . "<br />ts_cost_factored_all = $ts_cost_factored_all";
				$row_text = $row_text . "<br />cost_remaining_all = $cost_remaining_all";
				$row_text = $row_text . "<br />ts_fee_value = $ts_fee_value";
				$row_text = $row_text . "<br />user_percent = $user_percent";
				$row_text = $row_text . "<br />user_cost = $user_cost";
				$row_text = $row_text . "<br />hours_remaining_user = $hours_remaining_user";
				$row_text = $row_text . "<br />proj_id = $proj_id";
				$row_text = $row_text . "<br />ts_fee_id = $ts_fee_id";
				$row_text = $row_text . "<br />ts_hours_total = $ts_hours_total";
			}
		}
		
	return array ($row_text, $row_color);

}

function CreatePDFThumbnail ($file) {

	
	if (!extension_loaded('imagick')) { echo "<p>imagick not installed</p>"; }
	
		//$im = new imagick('$file[0]');
		//$im->setImageFormat('jpg');
		//header('Content-Type: image/jpeg');
		//echo $im;
	
}

function SelectProjectStage($option_name, $current_id) {

		global $conn;
			
	$sql_group = "SELECT * FROM intranet_timesheet_group WHERE group_active = 1 ORDER BY group_code, group_order";
	$result_group = mysql_query($sql_group, $conn) or die(mysql_error());
	$array_group = mysql_fetch_array($result_group);
	
	echo "<select name=\"" . $option_name . "\">";
	
	echo "<option value=\"\">-- None --</option>"; 
	
	while ($array_group = mysql_fetch_array($result_group)) {
	
		$group_id = $array_group['group_id'];
		$group_order = $array_group['group_order'];
		$group_code = $array_group['group_code'];
		$group_description = $array_group['group_description'];
		$group_active = $array_group['group_active'];
		
		if ($group_code != NULL) { $group_code = $group_code . ": "; }
		
		if ($group_id == $current_id ) { $select_group = " selected=\"selected\""; } else { unset($select_group); }
		
		echo "<option value=\"$group_id\" $select_group>" . $group_code . $group_description . "</option>";
		
	}
	
	echo "</select>";
	
}


function ProjectListFrontPage($user_id_current) {
	
	
global $conn;
global $user_usertype_current;

$user_id_current = intval($user_id_current);

if ($_GET[listorder] != NULL) { $listorder = $_GET[listorder];}

$active = CleanUp($_GET[active]);
if ($active == "0") { $project_active = " AND proj_active = 0";
} elseif ($active == "all") { unset($project_active);
} else { $project_active = " AND proj_active = 1 "; }



// Create an array which shows the recent projects worked on by the user

$timesheet_period = 16; // weeks
$timesheet_period = $timesheet_period * 604800;
$timesheet_period = time() - $timesheet_period;

$sql_timesheet_projects = "SELECT ts_project FROM intranet_timesheet WHERE ts_user = " . intval($_COOKIE[user]) . " AND ts_datestamp > " . intval ($timesheet_period) . " GROUP BY ts_project";
$result_timesheet_projects = mysql_query($sql_timesheet_projects, $conn) or die(mysql_error());

if (mysql_num_rows($result_timesheet_projects) == 0) {

	$sql_timesheet_projects = "SELECT ts_project FROM intranet_timesheet WHERE ts_datestamp > " . intval($timesheet_period) . " GROUP BY ts_project";
	$result_timesheet_projects = mysql_query($sql_timesheet_projects, $conn) or die(mysql_error());	

}


$array_projects_recent = array();
while ($array_timesheet_projects = mysql_fetch_array($result_timesheet_projects)) {
array_push($array_projects_recent,$array_timesheet_projects['ts_project']);
}

// Get the list of projects from the database

	$sql = "SELECT *, UNIX_TIMESTAMP(ts_fee_commence) FROM intranet_user_details, intranet_projects LEFT JOIN intranet_timesheet_fees ON `proj_riba` = `ts_fee_id` WHERE proj_rep_black = user_id $project_active AND proj_fee_track = 1 order by proj_num DESC";
	$result = mysql_query($sql, $conn) or die(mysql_error());




		
		
		

		
	
		echo "<div class=\"menu_bar\">";
		
		if ($_GET[active] != NULL) {
			echo "<a href=\"index2.php\" class=\"submenu_bar\">My Projects</a>";
		} else {
			echo "<a href=\"index2.php?active=current&listorder=\" class=\"submenu_bar\">All Active Projects</a>";
		}
				
		echo "<a href=\"index2.php?active=all&amp;listorder=$listorder\" class=\"submenu_bar\">All Projects</a>";
		echo "<a href=\"index2.php?active=0&amp;listorder=$listorder\" class=\"submenu_bar\">Inactive Projects</a>";
		
		if ($user_usertype_current > 3) {
			echo "<a href=\"index2.php?page=project_edit&amp;status=add\" class=\"submenu_bar\">Add Project (+)</a>";
		}
		
		if ($user_usertype_current > 3) {
			// echo "<a href=\"index2.php?page=project_analysis\" class=\"submenu_bar\">Project Analysis</a>";
			}
		echo "<a href=\"index2.php?page=project_blog_edit&amp;status=add\" class=\"submenu_bar\">Add Journal Entry (+)</a>";
		echo "</div>";
		
		if ($_GET[active] == "current") { 
			echo "<h3>All Active Projects</h3>";
		} else {
			echo "<h3>My Projects</h3>";
		}
	

		if (mysql_num_rows($result) > 0) {

		echo "<table summary=\"Lists of projects\">";
	

		while ($array = mysql_fetch_array($result)) {
		
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$proj_rep_black = $array['proj_rep_black'];
		$proj_client_contact_name = $array['proj_client_contact_name'];
		$proj_contact_namefirst = $array['proj_contact_namefirst'];
		$proj_contact_namesecond = $array['proj_contact_namesecond'];
		$proj_company_name = $array['proj_company_name'];
		$proj_fee_type = $array['proj_fee_type'];
		$proj_desc = nl2br($array['proj_desc']);
		$riba_id = $array['riba_id'];
		$riba_desc = $array['riba_desc'];
		$riba_letter = $array['riba_letter'];
		$proj_id = $array['proj_id'];
		$user_initials = $array['user_initials'];
		$user_id = $array['user_id'];
		$riba_stage_include = $array['riba_stage_include'];
		$proj_active = $array['proj_active'];
		$ts_fee_id = $array['ts_fee_id'];
		$ts_fee_target = $array['ts_fee_target'];
		$ts_fee_value = $array['ts_fee_value'];
		$ts_fee_time_begin = $array['UNIX_TIMESTAMP(ts_fee_commence)'];
		$ts_fee_time_end = $array['ts_fee_time_end'];
		$ts_fee_group = $array['ts_fee_group'];
		$proj_riba = $array['proj_riba'];
		
		// This has been added since the last update
		
		$ts_fee_text = $array['ts_fee_text'];
		
		//
		
		$sql_task = "SELECT tasklist_id FROM intranet_tasklist WHERE tasklist_project = " . intval( $proj_id ) . " AND tasklist_person = " . intval( $user_id_current ) . " AND tasklist_percentage < 100 ORDER BY tasklist_due DESC";
		$result_task = mysql_query($sql_task, $conn) or die(mysql_error());
		$project_tasks_due = mysql_num_rows($result_task);
		if ( $project_tasks_due > 0) { $add_task = "<br /><span class=\"minitext\"><a href=\"index2.php?page=tasklist_project&amp;proj_id=$proj_id&amp;show=user\">You have $project_tasks_due pending task(s) for this project</a></span>"; } else { $add_task = NULL; }
		
		if ($ts_fee_text != NULL) { $current_stage = $ts_fee_text; } elseif ($proj_fee_type == NULL) { $current_stage = "--"; } elseif ($riba_id == NULL) { $current_stage = "Prospect"; } else { $current_stage = $riba_letter." - ".$riba_desc; }
		
		if ($ts_fee_group > 0) { $current_stage =  "<a href=\"index2.php?page=timesheet_fee_list&amp;group_id=" . $ts_fee_group . "#" . $ts_fee_group . "\">" . $current_stage . "</a>"; }
		
		if (array_search($proj_id,$array_projects_recent) > 0 OR $_GET[active] != NULL) {
			
								if ($_GET[active] == NULL) {
								$array_projectcheck = TimeRemaining($proj_id, $proj_riba, $ts_fee_target, $ts_fee_value);
								}
								if ($array_projectcheck[1]!= NULL) { $row_color = $array_projectcheck[1]; } else { unset($row_color); } 
								if ($array_projectcheck[0]!= NULL) { $row_text = "<br />" . $array_projectcheck[0]; } else { unset($row_text); } 

											echo "<tr><td class=\"" . $row_color  . "\" style=\"width: 25%;\">";
											
											if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
												echo "<a href=\"index2.php?page=project_edit&amp;proj_id=$proj_id&amp;status=edit\" style=\"float: right;\" class=\"HideThis $row_color \"><img src=\"images/button_edit.png\" alt=\"Edit\" />";
											}
											
											echo ProjActive($proj_active,$proj_num,$proj_id) . "&nbsp";

											echo ProjActive($proj_active,$proj_name,$proj_id).$add_task;
											
											
											
											echo "</td>";
											
											if ($_GET[active] == "current") { echo "<td class=\"HideThis $row_color \" style=\"width: 35%;\"><span class=\"minitext\">" . $proj_desc . "</span></td>"; }
											
											// Project Stage
											
											echo "<td style=\"width: 18px; text-align: center; \" class=\"HideThis $row_color \">";
												
												$deadline = $ts_fee_time_begin + $ts_fee_time_end;
												$remaining = $deadline - time();
												$remaining = round ($remaining / 604800);
												
											if ($deadline > time() && $remaining != 0) {
												echo $remaining . "<br /><span class=\"minitext\">wks</span>";
											} elseif ($deadline < time() && $deadline > 0 && $remaining != 0) {
												echo $remaining . "<br /><span class=\"minitext\">wks</span>";									
											} elseif ($deadline > 0 && $remaining == 0) {
												echo "0<br /><span class=\"minitext\">wks</span>";	
											}
												
											echo "</td><td class=\"" . $row_color . "\">$current_stage $row_text</td>";
											
											echo "<td style=\"text-align: center; \" class=\"HideThis $row_color\">";
													echo "<a href=\"index2.php?page=project_checklist&amp;proj_id=$proj_id\"><img src=\"images/button_list.png\" alt=\"Checklist\" /></a>";
											echo "</td>";
											
											echo "<td class=\"" . $row_color . "\"><a href=\"index2.php?page=user_view&amp;user_id=$user_id\">$user_initials</a></td>
														<td style=\"text-align: center; \" class=\"HideThis $row_color\"><a href=\"pdf_project_sheet.php?proj_id=$proj_id\"><img src=\"images/button_pdf.png\" alt=\"Project Detailed (PDF)\" /></a></td>";


											echo "</tr>";
											
											
	
				}

		}

		echo "</table>";

		} else {

		echo "There are no live projects on the system";

		}
		
}



function ClassList($array_class_1,$array_class_2,$type) {
					GLOBAL $proj_id;
					GLOBAL $drawing_class;
					GLOBAL $drawing_type;
					
					echo "<select name=\"$type\" onchange=\"this.form.submit()\">";
					$array_class_count = 0;
					foreach ($array_class_1 AS $class) {
						echo "<option value=\"$class\"";
						
						if ($_POST[drawing_class] == $class && $type == "drawing_class" ) { echo " selected=\"selected\" "; }
						elseif ($_POST[drawing_type] == $class && $type == "drawing_type" ) { echo " selected=\"selected\" "; }
						
						echo ">";		
						echo $array_class_2[$array_class_count];
						echo "</option>";
						$array_class_count++;
						}
						echo "</select>";
						
					}
					
	function DrawingFilter($page, $proj_id) {
		
					$drawing_class = $_POST[drawing_class];
					$drawing_type = $_POST[drawing_type];
					echo "<div><h3>Filter</h3>";
					echo "<form method=\"post\" action=\"index2.php?page=" . $page. "&amp;proj_id=" . $proj_id . "&amp;drawing_class=$drawing_class&amp;drawing_type=$drawing_type\" >";
					$array_class_1 = array("","SK","PL","TD","CN","CT","FD","DR","M3","PP","SH","SP");
					$array_class_2 = array("- All -","Sketch","Planning","Tender","Contract","Construction","Final Design","2d Drawing", "3d Model File","Presentation","Schedule","Specification");
					ClassList($array_class_1,$array_class_2,"drawing_class");
					echo "&nbsp;";
					$array_class_1 = array("","SV","ST","GA","AS","DE","DOC","SCH");
					$array_class_2 = array("- All -","Survey","Site Location","General Arrangement","Assembly","Detail","Document","Schedule");
					ClassList($array_class_1,$array_class_2,"drawing_type");
					echo "</form><p>Please note that by using this filter you will clear any entries you may have previously entered below.</p></div>";
		
	}
	
	function ProjectDrawingList($proj_id) {
		
		global $conn;
					

					
					$drawing_class = $_POST[drawing_class];
					$drawing_type = $_POST[drawing_type];
					
					
					if ($drawing_class != NULL) { $drawing_class = " AND drawing_number LIKE '%-$drawing_class-%' "; } else { unset($drawing_class); }
					if ($drawing_type != NULL) { $drawing_type = " AND drawing_number LIKE '%-$drawing_type-%' "; } else { unset($drawing_type); }

				$sql = "SELECT * FROM intranet_drawings, intranet_drawings_scale, intranet_drawings_paper WHERE drawing_project = $proj_id AND drawing_scale = scale_id AND drawing_paper = paper_id $drawing_class $drawing_type order by drawing_number";
				$result = mysql_query($sql, $conn) or die(mysql_error());
				
					echo "<div>";
					DrawingFilter("drawings_list", $proj_id);
					echo "</div>";
					

						if (mysql_num_rows($result) > 0) {
							
						echo "<div>";

						echo "<table summary=\"Lists all of the drawings for the project\">";
						echo "<tr><td><strong>Drawing Number</strong></td><td><strong>Title</strong></td><td><strong>Rev.</strong></td><td><strong>Status</strong></td><td><strong>Scale</strong></td><td><strong>Paper</strong></td></tr>";

						while ($array = mysql_fetch_array($result)) {
						$drawing_id = $array['drawing_id'];
						$drawing_number = $array['drawing_number'];
						$scale_desc = $array['scale_desc'];
						$paper_size = $array['paper_size'];
						$drawing_title = $array['drawing_title'];
						$drawing_author = $array['drawing_author'];
						$drawing_status = $array['drawing_status'];
						
						if (!$drawing_status) { $drawing_status = "-"; }
						
						$sql_rev = "SELECT * FROM intranet_drawings_revision WHERE revision_drawing = '$drawing_id' ORDER BY revision_letter DESC LIMIT 1";
						$result_rev = mysql_query($sql_rev, $conn) or die(mysql_error());
						$array_rev = mysql_fetch_array($result_rev);
						if ($array_rev['revision_letter'] != NULL) { $revision_letter = strtoupper($array_rev['revision_letter']); } else { $revision_letter = " - "; }
						
						if ($revision_letter == "*") { $strikethrough = "; text-decoration: strikethrough"; } else { unset($strikethrough); }
						
						if ($drawing_id == $drawing_affected) { $background = " style=\"bgcolor: red; $strikethrough\""; } else { unset($background); }		

						echo "<tr><td $background><a href=\"index2.php?page=drawings_detailed&amp;drawing_id=$drawing_id&proj_id=$proj_id\">$drawing_number</a>";
						
						if ($drawing_author == $_COOKIE[user] OR $user_usertype_current > 2) {
							echo "&nbsp;<a href=\"index2.php?page=drawings_edit&amp;drawing_id=$drawing_id&amp;proj_id=$proj_id&amp;drawing_edit=yes\"><img src=\"images/button_edit.png\" alt=\"Edit this drawing\" /></a>";
						}

						echo "</td><td $background>".nl2br($drawing_title)."</td><td $background>$revision_letter</td><td $background>$drawing_status</td><td $background>$scale_desc</td><td $background>$paper_size</td>";


						echo "</tr>";

						}

						echo "</table>";
						
						echo "</div>";

						} else {

						echo "<div><p>No drawings found.</p></div>";

						}
	}
	
	
	function TelephoneMessage($user_id) {
	
	global $conn;

		$user_id = intval($user_id);
		

		if ($_GET[status] == "all") {
			$sql = "SELECT * FROM intranet_phonemessage LEFT JOIN intranet_user_details ON message_for_user = user_id WHERE message_for_user = '$user_id' ORDER BY message_date DESC";
			echo "<h2>All Messages</h2>";
		} elseif ($_GET[status] == "user") {
			$sql = "SELECT * FROM intranet_phonemessage LEFT JOIN intranet_user_details ON message_for_user = user_id WHERE message_taken = '$user_id' ORDER BY message_date DESC";
			echo "<h2>Messages for Others</h2>";
		} else {
			$sql = "SELECT * FROM intranet_phonemessage LEFT JOIN intranet_user_details ON message_for_user = user_id WHERE message_for_user = '$user_id' AND message_viewed = 0 ORDER BY message_date DESC";
			echo "<h2>Outstanding Messages</h2>";
		}
		
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		ProjectSubMenu($proj_id,$user_usertype_current,"phonemessage_view",1);



		


				if (mysql_num_rows($result) > 0) {

					echo "<table summary=\"Lists all telephone messages\">";
					
					echo "<tr><th>Date / Time</th><th>Message From</th><th>Message For</th><th>Message</th></tr>";

					while ($array = mysql_fetch_array($result)) {
					$message_id = $array['message_id'];
					$message_taken = $array['message_taken'];
					$message_from_id = $array['message_from_id'];
					$message_from_name = $array['message_from_name'];
					$message_from_company = $array['message_from_company'];
					$message_from_number = $array['message_from_number'];
					$message_project = $array['message_project'];
					$message_viewed = $array['message_viewed'];
					$message_date = $array['message_date'];
					$message_text = $array['message_text'];
					
					if ($message_from_number) { $message_from_name = $message_from_name . " (" . $message_from_number . ")"; }
					
					if ($message_viewed > 0) { $highlight = NULL;} else {  $highlight = "background-color: ".$settings_alertcolor."; font-weight: bold;";}
					
					echo "<tr>";
					echo "<td style=\"width: 25%;$highlight\"><a href=\"index2.php?page=datebook_view_day&amp;time=$message_date\">".TimeFormatDetailed($message_date)."</a>";
					if ($message_viewed > 0) { echo "<br /><span class=\"minitext\">Viewed: ".TimeFormatDetailed($message_date)."</span>"; }
					echo "</td><td style=\"$highlight\">";
					
					if ($message_from_name != NULL) { echo $message_from_name."<br />".$message_from_company; }
					else { $data_contact = $message_from_id; include("dropdowns/inc_data_contacts_name.php"); }
					
					echo "</td><td>" . $array['user_name_first'] . " " . $array['user_name_second'] . "</td><td style=\"width: 40%;$highlight\"><a href=\"index2.php?page=phonemessage_view_detailed&amp;message_id=$message_id\">".$message_text."</a></td></tr>";

					
					}

					echo "</table>";

				} else {

					echo "<p>There are no live messages on the system</p>";

				}
		
}

function BackupJournal($blog_id) {
	
	global $conn;
	global $user_id_current;
	$blog_id = intval($blog_id);
	$backup_path = "backup/";
	
	$time = time();
	
	$sql = "SELECT * FROM intranet_projects_blog LEFT JOIN intranet_user_details ON blog_user = user_id LEFT JOIN intranet_projects ON blog_proj = proj_id WHERE blog_id = $blog_id LIMIT 1";
	
	$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		
		$array = mysql_fetch_array($result);
		
		$backup_file = $backup_path . "journal_" . $array['blog_id'] . "_" . $array['blog_user'] . "_" . $time . ".html";
		
		$output = "<h1>" . $array['blog_title'] . "</h1>";
		$output = $output . "<h2>" . $array['proj_num'] . " " . $array['proj_name'] . "</h2>";
		$output = $output . "<h3>By " . $array['user_name_first'] . " " . $array['user_name_second'] . "</h3><hr />";
		$output = $output . "<article>" . $array['blog_text'] . "</article>";
		$output = $output . "<hr /><p>Entry date: " . TimeFormatDetailed($array['blog_date']) . ", backed up " . TimeFormatDetailed($time) . "</p>";
		
		$file = fopen($backup_file, "w");
		fwrite($file, $output);
		fclose($file);
		
		$alert_message = "<p>Journal Entry <a href=\"index2.php?page=project_blog_view&amp;blog_id=" . $blog_id . "\">\"" . $array['blog_title'] . "</a>\" has been archived to the following location: <a href=\"" . $backup_file . "\">" . $backup_file . "</a></p>";
		
		AlertBoxInsert($user_id_current,"Journal Entry Archived",$alert_message,$array['blog_id'],0,0,$array['blog_proj']);
		
	}
	
}

function DrawingDetail($drawing_id) {
	
		global $conn;
	
		$drawing_id = intval($drawing_id);

		if ($drawing_id > 0) {

		$sql = "SELECT * FROM intranet_drawings, intranet_drawings_scale, intranet_drawings_paper, intranet_projects, intranet_user_details WHERE drawing_id = '$_GET[drawing_id]' AND drawing_scale = scale_id AND drawing_paper = paper_id AND proj_id = drawing_project AND drawing_author = user_id LIMIT 1";
		$result = mysql_query($sql, $conn) or die(mysql_error());


				if (mysql_num_rows($result) > 0) {
				
				$array = mysql_fetch_array($result);
				$drawing_number = $array['drawing_number'];
				$drawing_id = $array['drawing_id'];
				$scale_desc = $array['scale_desc'];
				$paper_size = $array['paper_size'];
				$drawing_title = $array['drawing_title'];
				$drawing_author = $array['drawing_author'];
				$drawing_date = $array['drawing_date'];
				$drawing_status = $array['drawing_status'];
				$proj_id = $array['proj_id'];
				$proj_num = $array['proj_num'];
				$proj_name = $array['proj_name'];
				$drawing_author = $array['user_name_first']."&nbsp;".$array['user_name_second'];
				
				echo "<h2>$drawing_number</h2>";
				
				ProjectSubMenu($proj_id,$user_usertype_current,"project_drawings",1);
				
				if (!$drawing_status) { $drawing_status = "-"; }
				
						// Drawing issue menu
							echo "<div class=\"submenu_bar\">";
							echo "<a href=\"index2.php?page=drawings_list&amp;proj_id=$proj_id\" class=\"submenu_bar\"><img src=\"images/button_list.png\" alt=\"Drawing List\" />&nbsp;Drawing List</a>";
							echo "<a href=\"index2.php?page=drawings_issue&proj_id=$proj_id\" class=\"submenu_bar\"><img src=\"images/button_list.png\" alt=\"Issue Drawings\" />&nbsp;Issue Drawings</a>";
							echo "<a href=\"index2.php?page=drawings_edit&amp;drawing_id=$drawing_id&amp;proj_id=$proj_id&amp;drawing_edit=yes\" class=\"submenu_bar\"><img src=\"images/button_edit.png\" alt=\"Edit Drawing\" />&nbsp;Edit Drawing</a>";
							echo "<a href=\"index2.php?page=drawings_revision_edit&amp;drawing_id=$drawing_id&amp;proj_id=$proj_id\" class=\"submenu_bar\"><img src=\"images/button_new.png\" alt=\"Add new revision\" />&nbsp;Add new revision</a>";
							
							// Allow this drawing to be deleted if it has not already been issued (in which case, it's too late)
							
							$sql_drawing_delete = "SELECT issue_id FROM intranet_drawings_issued WHERE issue_drawing = $drawing_id";
							$result_drawing_delete = mysql_query($sql_drawing_delete, $conn) or die(mysql_error());
							$drawing_issue_count = mysql_num_rows($result_drawing_delete);
							if ($drawing_issue_count == 0) {
							
								echo "<a href=\"index2.php?page=drawings_list&amp;drawing_id=$drawing_id&amp;proj_id=$proj_id&amp;action=drawing_delete\" class=\"submenu_bar\"  onClick=\"javascript:return confirm('Are you sure you want to delete this drawing? Deleted drawings (and any revisions) will be permanently deleted and cannot be recovered. There are currently $drawing_count revisions of this drawing on the system.')\"><img src=\"images/button_delete.png\" alt=\"Delete Drawing\" />&nbsp;Delete Drawing</a>";
							
							}
							
							
					echo "</div>";
				
				
				
				echo "<h3>Drawing Information</h3>";
				

				echo "<table summary=\"Lists the details for drawing $drawing_number\">";
				
				echo "<tr><td style=\"width: 25%;\"><strong>Project</strong></td><td>$proj_num $proj_name</td></tr>";
				
				echo "<tr><td><strong>Drawing Number</strong></td><td>$drawing_number";
						if ($drawing_author == $_COOKIE[user] OR $user_usertype_current > 1) {
						echo "&nbsp;<a href=\"index2.php?page=drawings_edit&amp;drawing_id=$drawing_id&amp;proj_id=$proj_id&amp;drawing_edit=yes\"><img src=\"images/button_edit.png\" alt=\"Edit this drawing\" /></a>";
				}
				echo "</td></tr>";
				
				echo "<tr><td><strong>Title</strong></td><td>".nl2br($drawing_title)."</td></tr>";
				
				echo "<tr><td><strong>Status</strong></td><td>$drawing_status</td></tr>";
				
				echo "<tr><td><strong>Scale</strong></td><td>$scale_desc</td></tr>";
				
				echo "<tr><td><strong>Paper</strong></td><td>$paper_size</td></tr>";
				
				echo "<tr><td><strong>Author</strong></td><td>$drawing_author</td></tr>";
				
				echo "<tr><td><strong>Date</strong></td><td>" . TimeFormat($drawing_date) . "</td></tr>";

				echo "</table>";
				

				
				
				echo "<h3>Revision History</h3>";
				
				
				$sql_rev = "SELECT * FROM intranet_drawings_revision, intranet_user_details WHERE revision_drawing = '$_GET[drawing_id]' AND revision_author = user_id ORDER BY revision_letter DESC";
				$result_rev = mysql_query($sql_rev, $conn) or die(mysql_error());
				
				if (mysql_num_rows($result_rev) > 0) {
					
					

				echo "<table desc=\"Revision list for drawing $drawing_number\">
		<tr><th>Rev.</th><th>Date</th><th>Description</th><th>Author</th></tr>";
				
				while ($array_rev = mysql_fetch_array($result_rev)) {
				$revision_id = $array_rev['revision_id'];
				$revision_letter = strtoupper($array_rev['revision_letter']);
				$revision_desc = nl2br($array_rev['revision_desc']);
				$revision_time = $array_rev['revision_date'];
				$revision_date = TimeFormat($revision_time);
				$revision_author = $array_rev['revision_author'];
				$revision_author_name = $array_rev['user_name_first']."&nbsp;".$array_rev['user_name_second'];
				
				echo "<tr><td>$revision_letter";
				
				if ($revision_author == $_COOKIE[user] OR $user_usertype_current > 1) {
						echo "&nbsp;<a href=\"index2.php?page=drawings_revision_edit&amp;drawing_id=$drawing_id&amp;revision_id=$revision_id\"><img src=\"images/button_edit.png\" alt=\"Edit this revision\" /></a>";
				}
				
				
				echo "</td><td><a href=\"index2.php?page=datebook_view_day&amp;time=$revision_time\">$revision_date</a></td><td>$revision_desc</td><td>$revision_author_name</td></tr>";
				
				}
				
				print "</table>";
				
				} else {
				
				echo "<p>There are no revisions for this drawing.</p>";
				
				}

				// Drawing Issues
				
				
				
				//$sql_issued = "SELECT * FROM intranet_drawings_issued, intranet_drawings_issued_set, intranet_drawings LEFT JOIN intranet_drawings_revision ON revision_drawing = drawing_id WHERE issue_drawing = $drawing_id AND set_id = issue_set AND drawing_id = issue_drawing ORDER BY set_date DESC";
				
				$sql_issued = "SELECT * FROM intranet_drawings_issued_set, intranet_drawings, intranet_drawings_issued LEFT JOIN intranet_drawings_revision ON revision_id = issue_revision WHERE drawing_id = $drawing_id AND set_id = issue_set AND drawing_id = issue_drawing GROUP BY set_id ORDER BY set_date DESC, issue_revision DESC, set_id DESC";
				
				$result_issued = mysql_query($sql_issued, $conn) or die(mysql_error());
				
				echo "<h3>Drawing Issues</h3>";
				
				
				
				if (mysql_num_rows($result_issued) > 0) {
					
					echo "<table>";
					
					echo "<tr><th>Issue Date</th><th>Issue Set</th><th>Revision</th><th colspan=\"2\">Issue Status</th></tr>";
					
					while ($array_issued = mysql_fetch_array($result_issued)) {
					
						$set_date = $array_issued['set_date'];
						$revision_letter = strtoupper($array_issued['revision_letter']);
						$issue_set = $array_issued['issue_set'];
						$set_reason = $array_issued['set_reason'];
						$set_id = $array_issued['set_id'];
						
						if ($revision_letter == NULL) { $revision_letter = "-"; }
						
							echo "<tr><td><a href=\"index2.php?page=datebook_view_day&amp;timestamp=$set_date\">" . TimeFormat($set_date) . "</a></td><td><a href=\"index2.php?page=drawings_issue_list&amp;set_id=$issue_set&amp;proj_id=$proj_id\">$set_id</a></td><td>$revision_letter</td><td><a href=\"index2.php?page=drawings_issue_list&amp;set_id=$issue_set&amp;proj_id=$proj_id\">$set_reason</a></td><td style=\"width: 20px;\">&nbsp;<a href=\"pdf_drawing_issue.php?issue_set=$issue_set&amp;proj_id=$proj_id\"><img src=\"images/button_pdf.png\" alt=\"PDF drawing issue sheet\" /></a></td></tr>";
					
							}
							
					echo "</table>";
					
				} else { echo "<p>This drawing has not been issued.</p>"; }  
					
				
				
				
						// Drawing issue history
						
						
						/* $sql_history = "SELECT * FROM intranet_drawings_issued_set, intranet_user_details, intranet_drawings_issued LEFT JOIN intranet_drawings_revision ON revision_id = issue_revision WHERE issue_set = set_id AND user_id = set_user AND issue_drawing = $_GET[drawing_id] ORDER BY set_date DESC";
						$result_history = mysql_query($sql_history, $conn) or die(mysql_error());
					
						echo "<h2>Issue History</h2>";
						
						if (mysql_num_rows($result_history) > 0) {
							
							
								
								
								echo "<table desc=\"Issue history for drawing $drawing_number\"><tr><th>Date</th><th>Revision</th><th>Reason</th><th>Issued by</th></tr>";
								
								
						
								while ($array_history = mysql_fetch_array($result_history)) {
									
									if ( $array_history['revision_id'] > 0) { $revision_letter = strtoupper ($array_history['revision_letter'] ); } else { $revision_letter = "-"; }
									
									echo "<tr><td>" . TimeFormat($array_history['set_date']) . "</td><td>" . $revision_letter . "</td><td><a href=\"index2.php?page=drawings_issue_list&amp;set_id=" . $array_history['set_id'] . "&amp;proj_id=" . $array_history['issue_project'] . "\">" . $array_history['set_reason'] . "</a></td><td>" . $array_history['user_initials'] . "</td></tr>";
									
								}
							
								
								echo "</table>";
						
						
						} else { echo "<p>This drawing has not been issued.</p>"; } */
				
				

				} else {

				echo "<p>This drawing does not exist.</p>";

				}
			
		} else {

		echo "<p>No project selected.</p>";

		}

}

function UserChangePasswordForm($user_id) {
	
	$user_id = intval($user_id);
	
	global $conn;
	
		echo "<div>";
		echo "<form method=\"post\" action=\"http://intranet.rcka.co/index2.php?page=user_view&amp;user_id=" . $user_id . "\">";
		echo "<h3>Enter new password</h3><p><input type=\"password\" name=\"user_password1\" value=\"\" required=\"required\" /></p>";
		echo "<h3>Repeat new password</h3><p><input type=\"password\" name=\"user_password2\" value=\"\" required=\"required\" /></p>";
		echo "<input type=\"hidden\" value=\"" . $user_id . "\" name=\"user_id\" />";
		echo "<input type=\"hidden\" value=\"user_change_password\" name=\"action\" />";
		if ($user_id == $_COOKIE[user]) {
			echo "<p>Please note that if you are changing your own password you will be automatically logged out and will need to login again using your new password.</p>";
		} else {
			echo "<p>Changing a user's password will automatically log them out of the system and they will be required to login again using their new password.</p>";
		}
		echo "<p><input type=\"submit\" />";
		echo "</form>";
		echo "</div>";
	
}


function GetUserName($user_id) {
	
	GLOBAL $conn;
	GLOBAL $user_usertype_current;
	$user_id = intval($user_id);
	$sql = "SELECT * FROM intranet_user_details WHERE user_id = '$user_id' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];
	
	if ($user_id > 0 && $user_usertype_current > 3) { 
		echo "<h2>" . $user_name_first . "&nbsp;" . $user_name_second . "</h2>";
	} elseif ($user_id == 0 && $user_usertype_current > 3) { 
		echo "<h2>Add New User</h2>";
	} else {
		echo "<h2>Error</h2>";
	}
}


function GetUserNameOnly($user_id) {
	
	GLOBAL $conn;
	GLOBAL $user_usertype_current;
	$user_id = intval($user_id);
	$sql = "SELECT * FROM intranet_user_details WHERE user_id = '$user_id' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];
	
	$username = $user_name_first . " " . $user_name_second;
	
	return $username;
}

function UserForm ($user_id) {
	
	GLOBAL $user_usertype_current;
	GLOBAL $conn;
	
	$sql = "SELECT * FROM intranet_user_details WHERE user_id = '$user_id' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$user_address_county = $array['user_address_county'];
	$user_address_postcode = $array['user_address_postcode'];
	$user_address_town = $array['user_address_town'];
	$user_address_3 = $array['user_address_3'];
	$user_address_2 = $array['user_address_2'];
	$user_address_1 = $array['user_address_1'];
	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];
	$user_num_extension = $array['user_num_extension'];
	$user_num_mob = $array['user_num_mob'];
	$user_num_home = $array['user_num_home'];
	$user_email = $array['user_email'];
	$user_usertype = intval ( $array['user_usertype'] );
	$user_active = $array['user_active'];
	$user_username = $array['user_username'];
	$user_user_rate = $array['user_user_rate'];
	$user_user_added = $array['user_user_added'];
	$user_user_ended = $array['user_user_ended'];
	$user_user_timesheet = $array['user_user_timesheet'];
	$user_holidays = $array['user_holidays'];
	$user_initials = $array['user_initials'];
	$user_prop_target = $array['user_prop_target'];
	$user_timesheet_hours = $array['user_timesheet_hours'];
	$user_notes = $array['user_notes'];
	
	echo "<form method=\"post\" action=\"index2.php?page=user_list\" autocomplete=\"off\">";
	
	echo "<div><h3>Name</h3>";
	
		echo "<p>First Name<br /><input type=\"text\" name=\"user_name_first\" value=\"$user_name_first\" maxlength=\"50\" size=\"32\" required=\"required\" /></p>";
		echo "<p>Surname<br /><input type=\"text\" name=\"user_name_second\" value=\"$user_name_second\" maxlength=\"50\" size=\"32\" required=\"required\" /></p>";
		if ($user_usertype_current > 2) {
		echo "<p>Username<br /><input type=\"text\" name=\"user_username\" value=\"$user_username\" maxlength=\"50\" size=\"32\" required=\"required\" /></p>";
		} else {
		echo "<p>Username</p><p><span style=\"margin: 2px; padding: 2px; background: #fff;\">$user_username</span> (Cannot be changed)</p>";
		}
		echo "<p>Initials<br /><input type=\"text\" name=\"user_initials\" value=\"$user_initials\" maxlength=\"12\" size=\"32\" /></p>";
		echo "<p>Email<br /><input type=\"text\" name=\"user_email\" value=\"$user_email\" maxlength=\"50\" size=\"32\" type=\"email\" /></p>";
		
	echo "</div>";
	
	echo "<div><h3>Home Address</h3>";
	
		echo "<p>Address<br /><input type=\"text\" name=\"user_address_1\" value=\"$user_address_1\" maxlength=\"50\" size=\"32\" /><br />";
		echo "<input type=\"text\" name=\"user_address_2\" value=\"$user_address_2\" maxlength=\"50\" size=\"32\" /><br />";
		echo "<input type=\"text\" name=\"user_address_3\" value=\"$user_address_3\" maxlength=\"50\" size=\"32\" /></p>";
		
		echo "<p>Town / City<br /><input type=\"text\" name=\"user_address_town\" value=\"$user_address_town\" maxlength=\"50\" size=\"32\" /></p>";
		echo "<p>County<br /><input type=\"text\" name=\"user_address_county\" value=\"$user_address_county\" maxlength=\"50\" size=\"32\" /></p>";
		echo "<p>Postcode<br /><input type=\"text\" name=\"user_address_postcode\" value=\"$user_address_postcode\" maxlength=\"50\" size=\"32\" /></p>";
		
	echo "</div>";
	
	echo "<div><h3>Telephone</h3>";
	
		echo "<p>Extension<br /><input type=\"text\" name=\"user_num_extension\" value=\"$user_num_extension\" maxlength=\"50\" size=\"32\" /></p>";
		echo "<p>Mobile<br /><input type=\"text\" name=\"user_num_mob\" value=\"$user_num_mob\" maxlength=\"50\" size=\"32\" /></p>";
		echo "<p>Home<br /><input type=\"text\" name=\"user_num_home\" value=\"$user_num_home\" maxlength=\"50\" size=\"32\" /></p>";
		
	echo "</h3>";
	
	
	echo "<div><h3>Notes</h3>";
	
		echo "<textarea name=\"user_notes\" style=\"width: 95%; height: 150px;\">$user_notes</textarea>";
		
	echo "</div>";
	
	if ($user_usertype_current > 3) {
	
		echo "<div><h3>Details</h3>";
		
		
		echo "<p>User Type<br />";
		
		UserAccessType("user_usertype",0,$user_usertype,0);
		
		echo "<p><input type=\"checkbox\" name=\"user_active\" value=\"1\"";
		if ($user_active == 1 OR $user_id == NULL) { echo "checked=checked "; }
		echo "/>&nbsp;User Active</p>";
		echo "<p>Holiday Allowance<br /><input type=\"text\" name=\"user_holidays\" value=\"$user_holidays\" maxlength=\"6\" size=\"32\" type=\"number\" /></p>";
		echo "<p>Hourly Rate (excluding overheads)<br /><input name=\"user_user_rate\" value=\"$user_user_rate\" maxlength=\"12\" size=\"32\" type=\"number\" /></p>";
		echo "<p>Hours per Week<br /><input type=\"number\" name=\"user_timesheet_hours\" value=\"$user_timesheet_hours\" size=\"32\"  /></p>";
		echo "<p><input type=\"checkbox\" name=\"user_user_timesheet\" value=\"1\"";
		if ($user_user_timesheet == 1 OR $user_id == NULL) { echo "checked=checked "; }
		echo "/>&nbsp;Require Timesheets</p>";
		echo "<p>Non-Fee Earning Time Allowance<br />";
		echo "<select name=\"user_prop_target\">";
		echo "<option value=\"0\" "; if ($user_prop_target == 0) { echo "selected=\"selected\""; } ; echo ">None</option>";
		echo "<option value=\"0.05\" "; if ($user_prop_target == 0.05) { echo "selected=\"selected\""; } ; echo ">%5</option>";
		echo "<option value=\"0.1\" "; if ($user_prop_target == 0.1) { echo "selected=\"selected\""; } ; echo ">10%</option>";
		echo "<option value=\"0.15\" "; if ($user_prop_target == 0.15) { echo "selected=\"selected\""; } ; echo ">15%</option>";
		echo "<option value=\"0.2\" "; if ($user_prop_target == 0.2) { echo "selected=\"selected\""; } ; echo ">20%</option>";
		echo "<option value=\"0.25\" "; if ($user_prop_target == 0.25) { echo "selected=\"selected\""; } ; echo ">25%</option>";
		echo "<option value=\"0.3\" "; if ($user_prop_target == 0.3) { echo "selected=\"selected\""; } ; echo ">30%</option>";
		echo "<option value=\"0.35\" "; if ($user_prop_target == 0.35) { echo "selected=\"selected\""; } ; echo ">35%</option>";
		echo "<option value=\"0.4\" "; if ($user_prop_target == 0.4) { echo "selected=\"selected\""; } ; echo ">40%</option>";
		echo "<option value=\"0.45\" "; if ($user_prop_target == 0.45) { echo "selected=\"selected\""; } ; echo ">45%</option>";
		echo "<option value=\"0.5\" "; if ($user_prop_target == 0.5) { echo "selected=\"selected\""; } ; echo ">50%</option>";
		echo "<option value=\"0.55\" "; if ($user_prop_target == 0.55) { echo "selected=\"selected\""; } ; echo ">55%</option>";
		echo "<option value=\"0.60\" "; if ($user_prop_target == 0.6) { echo "selected=\"selected\""; } ; echo ">60%</option>";
		echo "<option value=\"0.65\" "; if ($user_prop_target == 0.65) { echo "selected=\"selected\""; } ; echo ">65%</option>";
		echo "<option value=\"0.70\" "; if ($user_prop_target == 0.7) { echo "selected=\"selected\""; } ; echo ">70%</option>";
		echo "<option value=\"0.75\" "; if ($user_prop_target == 0.75) { echo "selected=\"selected\""; } ; echo ">75%</option>";
		echo "<option value=\"0.80\" "; if ($user_prop_target == 0.8) { echo "selected=\"selected\""; } ; echo ">80%</option>";
		echo "<option value=\"0.85\" "; if ($user_prop_target == 0.85) { echo "selected=\"selected\""; } ; echo ">85%</option>";
		echo "<option value=\"0.9\" "; if ($user_prop_target == 0.9) { echo "selected=\"selected\""; } ; echo ">90%</option>";
		echo "<option value=\"0.95\" "; if ($user_prop_target == 0.95) { echo "selected=\"selected\""; } ; echo ">95%</option>";
		echo "<option value=\"1\" "; if ($user_prop_target == 1) { echo "selected=\"selected\""; } ; echo ">100%</option>";
		echo "</select></p>";
		echo "</div>";
	

	
	echo "<div><h3>Dates</h3>";
		
		if ($user_user_added > 0) {
			$user_user_added_print = date("Y",$user_user_added) . "-" . date("m",$user_user_added) . "-" . date("d",$user_user_added);
		} elseif ($user_id == NULL) {
			$user_user_added_print = date("Y",time()) . "-" . date("m",time()) . "-" . date("d",time());
		} else { unset($user_user_added); }
		
		if ($user_user_ended > 0) {
			$user_user_ended_print = date("Y",$user_user_ended) . "-" . date("m",$user_user_ended) . "-" . date("d",$user_user_ended);
		} else { unset($user_user_ended); }
	
		echo "<p>Date Started<br /><input type=\"date\" name=\"user_user_added\" value=\"$user_user_added_print\" /></p>";
		
		echo "<p>Date Ended<br /><input type=\"date\" name=\"user_user_ended\" value=\"$user_user_ended_print\" /></p>";
		
	echo "</div>";
	

	
	}
	
	if ($user_id > NULL) {
	echo "<input type=\"hidden\" name=\"action\" value=\"user_update\" />";
	echo "<input type=\"hidden\" name=\"user_id\" value=\"$user_id\" />";
	echo "<input type=\"submit\" value=\"Update\" />";
	} else {
	echo "<input type=\"submit\" value=\"Submit\" />";
	echo "<input type=\"hidden\" name=\"action\" value=\"user_update\" />";
	}
	
	echo "</form></p>";
	
	
	
	
}

function ProjectID($type,$table,$identifier,$id) {
	
	global $conn;
	$sql = "SELECT $type FROM $table WHERE $identifier = $id LIMIT 1";
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	$output = $array[$type];
	
	return $output;
	
}



