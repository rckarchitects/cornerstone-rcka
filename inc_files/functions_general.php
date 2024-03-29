<?php

$removestrings_all = array("<",">","|");
$removestrings_phone = array("+44","(",")");

$currency_symbol = array("�","�");
$currency_text = array("&pound;","&euro;");
$currency_junk = array("�","�");

$text_remove = array("�","�");

ini_set("upload_max_filesize","10M");

$backup_path = "backups/";

function GetDatabaseAccess() {
	
	global $conn;
	
			$database_read = file_get_contents("../secure/database.inc");
			$database_read_array = explode("\n", $database_read);
			$database_location = $database_read_array[0];
			$database_username = $database_read_array[1];
			$database_password = $database_read_array[2];
			$database_name = $database_read_array[3];

			return array($database_name,$database_location,$database_username,$database_password);
			
}

$db_connect = GetDatabaseAccess();
$conn = mysql_connect($db_connect[1],$db_connect[2],$db_connect[3]);
mysql_select_db($db_connect[0], $conn);

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
					echo "<img src=\"$logo\" alt=\"$settings_name\" style=\"text-align: center; width: 150px;\" class=\"practicelogo\" />";
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

function DataList($field,$table,$proj_id,$project_field) {
	
	if (intval($proj_id > 0)) { $project_filter = " AND " . $project_field . " = " . intval($proj_id) ; }
	
	GLOBAL $conn;
	$sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $field . " IS NOT NULL " . $project_filter . " GROUP BY " . $field;
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

function GetProjectNum($proj_id) {
					if ($proj_id != NULL) {
						GLOBAL $conn;
						$sql = "SELECT proj_num FROM intranet_projects WHERE proj_id = $proj_id";
						$result = mysql_query($sql, $conn) or die(mysql_error());
						$array = mysql_fetch_array($result);
						$proj_num = $array['proj_num'];
					}
					
					return $proj_num;
}

function GetProjectStage($proj_id) {
	
					if ($proj_id != NULL) {
						GLOBAL $conn;
						$sql = "SELECT proj_riba FROM intranet_projects WHERE proj_id = " . $proj_id;
						$result = mysql_query($sql, $conn) or die(mysql_error());
						$array = mysql_fetch_array($result);
						$proj_riba = $array['proj_riba'];
					}
					
					return $proj_num;
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

function ProjectList($proj_id) {

global $conn;
	
$sql = "SELECT * FROM intranet_projects LEFT JOIN intranet_team ON team_id = proj_team LEFT JOIN intranet_contacts_countrylist ON country_id = proj_address_country WHERE proj_id = " . intval($proj_id);
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
$proj_ambition_social = $array['proj_ambition_social'];
$proj_tenant_1 = $array['proj_tenant_1'];
$proj_location = $array['proj_location'];

$proj_info = $array['proj_info'];

$proj_identifier = $array['proj_identifier'];
					

					echo "<table summary=\"Project Information\">";
					
					if ($proj_identifier) { echo "<tr><td>Project Identifier</td><td>" . $proj_identifier . "</td></tr>"; }
					
					if ($array['proj_team']) { echo "<tr><td>Team</td><td><a href=\"index2.php?page=project_all&amp;team=" . $array['team_id'] . "\">" . $array['team_name'] . "</a></td></tr>"; }
					
					if ($proj_rep_black) { echo "<tr><td>Project Leader</td><td><a href=\"index2.php?page=user_view&amp;user_id=" . intval($proj_rep_black) . "\">" . GetUserNameOnly($proj_rep_black) . "</a></td></tr>"; }

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
					if ($array['country_printable_name']) {echo $array['country_printable_name'] . "<br />"; }

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
					if ($proj_ambition_social) { echo "<tr><td><a href=\"index2.php?page=project_ambition_schedule&amp;type=project_ambition_social\">Social Value Ambition</a></td><td>" . nl2br ($proj_ambition_social) . "</td></tr>"; }
				

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

function ProjectSelect($proj_id_select,$field_name,$active,$include_null,$javascript) {
	
		GLOBAL $conn;
		
		if ($active == 1) {
			$proj_id_select_add = "(proj_active = 1 OR proj_active = 0)";
		} else {
			$proj_id_select_add = "proj_active = 1";
		}
		
		$sql = "SELECT * FROM intranet_projects WHERE $proj_id_select_add ORDER BY proj_active DESC, proj_num DESC";
	
		echo "<select name=\"" . $field_name .  "\" " . $javascript . ">";
		
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
					echo "<select onchange=\"this.form.submit()\" name=\"proj_id\" onblur=\"HideProjectSwitcher()\">";
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
	
	if (intval($input) > 0) {
	
		if (intval($detail) == 1) { $dayprint = TimeFormatDetailed($input); } else { $dayprint = TimeFormat($input); }
		
		$output = "<a href=\"index2.php?page=datebook_view_day&amp;timestamp=" . $input . "\">" . $dayprint . "</a>";
		
	
	} else { $output = "-"; }
	
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

function DateList_Important($impending_only) {
	

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
	
			while ($array = mysql_fetch_array($result)) {
				
				$item_edit = "index2.php?page=date_edit&amp;date_id=" . $array['date_id'];
				$item_project = "<a href=\"index2.php?page=project_view&amp;proj_id=" . $array['proj_id'] . "\">" . GetProjectName($array['proj_id'])  . "</a>";
				
				if ($array['date_notes']) {
					$item_description = "<a href=\"index2.php?page=date_list&amp;date_id=" . $array['date_id'] . "\">" . $array['date_description'] . "</a>";
				} else {
					$item_description = $array['date_description'];
				}

				$output_array[] = array($array['date_day'],$item_project,$array['date_category'],$item_description,$array['date_notes'],$array_date_edit,$item_edit);
	
			}
	
		}
		
		return $output_array;
	
	}
	
function DateList_Tenders($impending_only) {
	
	global $conn; 
	
		if ($impending_only == 1) {
			
			$impending = time() + 1209600;
		
			$sql = "SELECT * FROM intranet_tender WHERE tender_date > " . time() . " AND tender_date < $impending AND tender_submitted != 1 AND tender_result != 3 ORDER BY tender_date DESC";
			
		} elseif ($impending_only == 2) { 
		
			$sql = "SELECT * FROM intranet_tender WHERE tender_date < " . time() . " AND tender_submitted != 1 AND tender_result != 3 ORDER BY tender_date DESC";

		} else {
			
			$sql = "SELECT * FROM intranet_tender WHERE tender_date > " . time() . " AND tender_submitted != 1 AND tender_result != 3 ORDER BY tender_date";
			
		}
		
		$result = mysql_query($sql, $conn) or die(mysql_error());
	
		if (mysql_num_rows($result) > 0 ) {
	
			while ($array = mysql_fetch_array($result)) {
				
				$date_day = CreateDateFromTimestamp($array['tender_date']) ;
				$item_edit = "index2.php?page=tender_edit&amp;tender_id=" . $array['tender_id'];
				$tender_name = "<a href=\"index2.php?page=tender_view&amp;tender_id=" . $array['tender_id'] . "\">" . $array['tender_name'] . "</a>";
				
				$output_array[] = array($date_day,$array['proj_id'],"Tender Deadline",$tender_name,$array['tender_description'],$array_date_edit,$item_edit);
	
			}
	
		}
		
		return $output_array;
		
}
	
function DateList($impending_only) {
	
	$weeks = 2;
	
		$array_important = DateList_Important($impending_only);
		$array_tenders = DateList_Tenders($impending_only);
		
		$array_dates = array_merge($array_important,$array_tenders);
		
		if ($impending_only == 2) { array_multisort($array_dates,SORT_DESC); }
		else { array_multisort($array_dates); }
		
		
		global $user_usertype_current;
		
		//print_r($array_dates);
		
		//echo "<p>Array: " . $array_dates[2][0] . "</p>";
		
	
		if (count($array_dates) > 0 ) {
	
			if ($impending_only == 1) { echo "<h2>Next " . $weeks . " Weeks</h2>";  }
			elseif ($impending_only == 2) {  echo "<h2>Past Dates</h2>"; }
			else { echo "<h2>Future Dates</h2>"; }

			if ($impending_only != 1) { ProjectSubMenu('',$user_usertype_current,"date_list"); }

			echo "<table>";
	
			echo "<tr><th>Description</th><th>Date</th><th>Project</th><th colspan=\"2\" class=\"HideThis\">Category</th></th>";
			
			$counter = 1;
			$headline = 0;
	
			foreach ($array_dates AS $date_item) {
							
				if ($date_item[0] == date("Y-m-d",time())) {
					$style="alert_warning";
					if ($headline < 1) { echo "<tr><th colspan=\"5\">Today</th></th>"; $headline = 1; }
				} elseif (((DisplayDate($date_item[0]))) < (time() + 604800) && (DisplayDate($date_item[0]) > (time()))) {
					if ($headline < 2) { echo "<tr><th colspan=\"5\">Next Fortnight</th></th>"; $headline = 2; }
					$style="alert_careful";
				} elseif (((DisplayDate($date_item[0]))) < (time() + 2678400) && (DisplayDate($date_item[0]) > (time()))) {
					if ($headline < 3) { echo "<tr><th colspan=\"5\">Next Month</th></th>"; $headline = 3; }
					unset($style);
				} elseif (((DisplayDate($date_item[0]))) < (time() + 15552000) && (DisplayDate($date_item[0]) > (time()))) {
					if ($headline < 4) { echo "<tr><th colspan=\"5\">Next Six Months</th></th>"; $headline = 4; }
					unset($style);
				} elseif (((DisplayDate($date_item[0]))) > (time() + 15552000) && (DisplayDate($date_item[0]) > (time()))) {
					if ($headline < 5) { echo "<tr><th colspan=\"5\">In the Future</th></th>"; $headline = 5; }
					unset($style);
				} else {
					unset($style);
				}
				
				
				
				if ((intval($_GET[date_id]) == $date_item[0]) && $date_item[4]) { $embolden = "font-weight: bold;"; } else { unset($embolden); }
	
				echo "<tr><td class=\"$style\" style=\"width: 50%; $embolden\">"; 

				if ($date_item[4]) { echo "<a href=\"index2.php?page=date_list&amp;filter=" . intval($_GET[filter]) . "&amp;date_id=" . $counter . "\" class=\"submenu_bar\" style=\"float: right;\">&#8681;</a>"; }
				
				echo $date_item[3];

				echo "</td><td class=\"$style\"  style=\"width: 15%; $embolden\"><a href=\"index2.php?page=datebook_view_day&amp;timestamp=" . DisplayDate($date_item[0]) . "\">" . TimeFormat ( DisplayDate($date_item[0]) ) . "</a></td><td class=\"$style\"  style=\"width: 25%; $embolden\">" . $date_item[1] . "</td>";
					echo "<td class=\"$style HideThis\" style=\"$embolden\">" . $date_item[2] . "</td><td style=\"text-align: right;\" class=\"$style HideThis\"><a href=\"" . $date_item[6] . "\"><img src=\"images/button_edit.png\" alt=\"Edit\" class=\"button\" /></a></td>";


				if ((intval($_GET[date_id]) == $counter) && $date_item[4] && $_GET[date_id]) { echo "</table><div class=\"page\">" . $date_item[4] . "</div><table>"; }
				
				$counter++;
	
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
						\"advlist autolink autosave fullscreen lists link charmap preview anchor textcolor table image code wordcount save\"
					],
					menubar: false,
					toolbar: \"save undo redo | formatselect | bold italic underline strikethrough | bullist numlist outdent indent | link unlink | forecolor | table | alignleft aligncenter alignright | image | fullpage | removeformat | code | fullscreen | restoredraft | wordcount \",
					table_toolbar: \"tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol\",
					image_list: [";
						ListAvailableImages("uploads");
				echo "],
					autosave_ask_before_unload: true,
					height : 500,
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

function FooterBar() {
	
	global $settings_companyname;
	global $settings_companyweb;
	global $settings_companyaddress;
	global $settings_companytelephone;
	
	$settings_companyaddress = str_replace("\n"," | ", $settings_companyaddress);
	
	echo "<div id=\"mainfooter\">powered by <a href=\"https://github.com/rckarchitects/cornerstone-rcka/wiki/Welcome-to-Cornerstone\">RCKa Cornerstone</a></div>";
	
	echo "<div id=\"mainfooter_print\">";
	
	echo "<p><strong>" . $settings_companyname . " | " . $settings_companyaddress . " | T " . $settings_companytelephone . " | " . $settings_companyweb . "</strong><br />Current at " . TimeFormatDetailed(time()) . "</p>";
	
	echo "</div>";
	
}
	


function NotAllowed() {
	
	echo "<h1>Access Denied</h1><p>You have insufficient privileges to view this page.</p>";
	
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

					$sql = "SELECT * FROM intranet_projects_blog, intranet_projects, intranet_user_details WHERE blog_proj = proj_id AND proj_id = $proj_id AND blog_user = user_id AND (blog_access <= " . $user_usertype_current . " OR blog_access IS NULL) AND (blog_view = 0 OR blog_view = " . $user_id_current . ") order by blog_sticky DESC, blog_date DESC";
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
							
							$blog_sticky = $array['blog_sticky'];
						
						if ($blog_type == "phone") { $blog_type_view = "Telephone Call"; $type++; }
						elseif ($blog_type == "filenote") { $blog_type_view = "File Note"; $type++; }
						elseif ($blog_type == "meeting") { $blog_type_view = "Meeting Note"; $type++; }
						elseif ($blog_type == "email") { $blog_type_view = "Email Message"; $type++; }
						else { $blog_type_view = NULL; $type = 0; }
						
						$blog_type_list = array("phone","filenote","meeting","email");
						

							echo "<tr>";
							echo "<td>$type.</td><td><a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id&amp;proj_id=$proj_id\">".$blog_title."</a></td>";
							echo "<td style=\"width: 20%;\"><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date\">".TimeFormat($blog_date)."</a></td>";
							echo "<td><a href=\"index2.php?page=user_view&amp;user_id=$user_id\">".$blog_user_name_first."&nbsp;".$blog_user_name_second."</a></td>";
							echo "<td style=\"width: 20%;\"><span class=\"minitext\">$blog_type_view</span></td>";
							
							if ($blog_sticky == 1) { echo "<td><img src=\"images/icon_pinned.png\" style=\"width: 16px;\" alt=\"Pinned Journal Entry\" /></td>"; } else { echo "<td></td>"; }
							
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
			echo "<div id=\"warnings\">";
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
			
			$alert_message = addslashes($alert_message);
			
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


function CheckListRows($proj_id,$group_id,$showhidden) {

	global $conn;
	
	$sql_checklist = "SELECT * FROM intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id AND checklist_project = $proj_id LEFT JOIN intranet_timesheet_group ON group_id = item_stage WHERE ((group_id = '$group_id') OR (item_stage IS NULL)) ORDER BY item_group, item_order, checklist_date, item_name";

	$result_checklist = mysql_query($sql_checklist, $conn) or die(mysql_error());

	echo "
	<script type=\"text/javascript\">
	
						function HideLine(GetLineID,ShowLineID){
						
								GetLineID.style.display='table-row';
								ShowLineID.style.display='none';			
						}
						
						function ShowLine(GetLineID,ShowLineID){
							
								var ToggleRequiredButton = document.getElementById(\"ToggleRequiredButton_Hide\");
								var ToggleRequiredButton = document.getElementById(\"ToggleRequiredButton_Show\");
						
								GetLineID.style.display='none';
								ShowLineID.style.display='table-row';
								SubmitButton.style.display='block';
								ToggleRequiredButton_Hide.style.display='None';
								ToggleRequiredButton_Show.style.display='None';
						}
											
						function ToggleRequired() {
						
								var x = document.getElementsByClassName(\"RowHide\");
								if (x[0].style.display === \"none\") {
									for (i = 0; i < x.length; i++) {
										x[i].style.display='table-row';
									}
									ToggleRequiredButton_Show.style.display='None';
									ToggleRequiredButton_Hide.style.display='Block';
									
								} else {
									for (i = 0; i < x.length; i++) {
										x[i].style.display='none';
									}
									ToggleRequiredButton_Hide.style.display='None';
									ToggleRequiredButton_Show.style.display='Block';
								}
						}
						
	</script>";


	$current_item = 0;

	if (mysql_num_rows($result_checklist) > 0) {
		
		echo "<div style=\"height: 25px;\"><a href=\"#\" id=\"ToggleRequiredButton_Show\" class=\"submenu_bar\" onclick=\"ToggleRequired('Show')\" />Show Not Required</a><a href=\"#\" id=\"ToggleRequiredButton_Hide\" class=\"submenu_bar\" onclick=\"ToggleRequired('Hide')\" style=\"display: none;\" />Hide Not Required</a></div>";
					
					echo "<form action=\"index2.php?page=project_checklist&amp;group_id=" . intval($group_id) . "&amp;proj_id=" . intval($proj_id). "\" method=\"post\" enctype=\"multipart/form-data\">";
					echo "<input type=\"hidden\" name=\"action\" value=\"checklist_update\" />";
					echo "<table>";
					echo "<tr><th style=\"width: 30%;\">Item</th><th style=\"width: 5%;\">Stage</th><th style=\"width: 15%;\">Required</th><th style=\"width: 20%;\">Date Completed</th><th>Comment</th><th colspan=\"3\">Link</th></tr>";

					$group = NULL;
					
					$counter = 0;

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
						if ($checklist_required == 2 && ( $checklist_date == "0000-00-00" OR $checklist_date == NULL ) ) { $bg =  "class=\"alert_warning edittable\""; } // red
						elseif ($checklist_required == 2 && ( $checklist_date != "0000-00-00" OR $checklist_date != NULL ) ) { $bg =  "class=\"alert_ok edittable\""; } // green
						elseif ($checklist_required == 1) { $bg =  "class=\"edittable\""; } // grey
						else { $bg =  "class=\" alert_neutral edittable\""; } // grey
						
						
						if ($checklist_date == 0) { $checklist_date = "-";}
						
						if ($checklist_deadline != "0000-00-00" && $checklist_deadline != NULL) {
							$checklist_date_additional = "<br /><span class=\"minitext\">Deadline: " . DayLink(DisplayDate($checklist_deadline)) . "</span>";
						} else {
							unset($checklist_date_additional);
						}
					
					if ($checklist_required != 1) { $rowshow_class = "class=\"RowShow\""; } else { $rowshow_class = "class=\"RowHide\" style=\"display: none;\""; }
					
					echo "<tr id=\"item_line_" . $checklist_id . "\" ondblclick=\"ShowLine(item_line_" . $checklist_id . ",item_line_" . $checklist_id . "_form)\" $rowshow_class><td $bg>";
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
						
						echo "<td $bg>" . DayLink(DisplayDate($checklist_date)) . $checklist_date_additional . "</td>";
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
							echo "<td $bg><a href=\"javascript:void(0);\" id=\"item_line_" . $item_id . "><img src=\"images/button_help.png\" alt=\"Help\" /></a></td>";
						}
						
						echo "</tr>";
						
						echo "<tr id=\"$item_id\" style=\"display: $hidden;\"><td colspan=\"7\" style=\"padding: 12px; background: rgba(255,255,255,1);\">$item_notes</td>";
						
					} else { echo "<td $bg></td>"; }
					
						echo "</tr>";

					
					$group = $item_group;
					
					$current_item = $item_id;
					
					
					if ($checklist_required == 1) { $no = " selected=\"selected\" "; unset($yes); unset($maybe); }
					elseif ($checklist_required == 2) { $yes = " selected=\"selected\" "; unset($no); unset($maybe);}
					else { $maybe = " selected=\"selected\" "; unset($yes); unset($no); }
					
					echo "	<tr id=\"item_line_" . $checklist_id. "_form\" style=\"display: none;\" onblur=\"HideLine(item_line_" . $checklist_id . ",item_line_" . $checklist_id . "_form)\">
							<td $bg>$item_name</td>
							<td $bg>" . $group_code . "</td>
							<td $bg><select name=\"checklist_required[]\" $bg><option value=\"0\" $maybe $bg>-</option><option value=\"1\" $no $bg>No</option><option value=\"2\" $yes $bg>Yes</option></select></td>
							<td $bg><input type=\"date\" name=\"checklist_date[]\" value=\"$checklist_date\" $bg /></td>
							<td $bg><input type=\"text\" name=\"checklist_comment[]\" value=\"$checklist_comment\" $bg /></td>
							<td $bg><input type=\"link\" name=\"checklist_link[]\" value=\"$checklist_link\" $bg  /></td>
							<td $bg>";
							echo "<input type=\"file\" name=\"checklist_upload[]\" $bg/>";
							echo "
							</td><td $bg></td>
							<input type=\"hidden\" name=\"item_title[]\" value=\"$item_name\" />
							<input type=\"hidden\" name=\"item_counter[]\" value=\"$counter\" />
							<input type=\"hidden\" name=\"item_id[]\" value=\"$item_id\" />
							<input type=\"hidden\" name=\"checklist_id[]\" value=\"$checklist_id\" />
							<input type=\"hidden\" name=\"checklist_user[]\" value=\"" . $_COOKIE[user] . "\" />
							<input type=\"hidden\" name=\"checklist_timestamp[]\" value=\"" . time() . "\" />
							<input type=\"hidden\" name=\"checklist_project[]\" value=\"$proj_id\" />
							</tr>";
							
							$counter++;

					}
					
					echo "</table>";
					echo "<div id=\"SubmitButton\" style=\"display: none;\"><input type=\"Submit\" value=\"Update\" /></div>";
					echo "</form>";

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
					
					if (!$invoice_date_paid) { echo "&nbsp;<a href=\"index2.php?page=timesheet_invoice_items_edit&amp;proj_id=" . $invoice_project . "&amp;invoice_item_id=" . $invoice_item_id . "\"><img src=\"images/button_edit.png\" alt=\"Edit\" class=\"button\" />"; }
					
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

					$fee_stage_current = CleanNumber($_POST['fee_stage_current']);
					$sql_update = "UPDATE intranet_projects SET proj_riba = " . intval($fee_stage_current) . " WHERE proj_id = " . $proj_id . " LIMIT 1";
					$result_update = mysql_query($sql_update, $conn) or die(mysql_error());
					
					$alert_message = "<p>The active fee stage for  " . GetProjectName($proj_id) .  " has been updated to " . $fee_stage_current . ".</p>";
					
					AlertBoxInsert($_COOKIE['user'],"Project Fees",$alert_message,$fee_stage_current,4,0,$proj_id);

				}

				$sql = "SELECT * FROM intranet_projects, intranet_timesheet_fees LEFT JOIN intranet_timesheet_group ON group_id = ts_fee_group WHERE ts_fee_project = $proj_id AND proj_id = ts_fee_project ORDER BY ts_fee_commence, ts_fee_text";
				$result = mysql_query($sql, $conn) or die(mysql_error());


						if (mysql_num_rows($result) > 0) {
							
						echo "<form method=\"post\" action=\"index2.php?page=project_fees&amp;proj_id=" . $proj_id . "\">";
							
						echo "<table summary=\"Lists the fees for the selected project\">";
						
						
						
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
												
												
												echo "<tr id=\"stage_$ts_fee_id\"><td style=\"$highlight\"><input type=\"radio\" name=\"fee_stage_current\" value=\"$ts_fee_id\" class=\"HideThis\" $ts_fee_id_selected /> </td><td style=\"$highlight\">$group_code<br /><span class=\"minitext\">[$ts_fee_id]</span></td><td style=\"$highlight\">$ts_fee_text</td><td style=\"$highlight\">".$prog_begin_print."</td><td style=\"$highlight\">".$prog_end_print."</td><td style=\"$highlight\">".$ts_fee_prospect."</td><td  style=\"$highlight; text-align: right;\">".MoneyFormat($ts_fee_calc) . $fee_target ."</td>\n";
												echo "<td style=\"$highlight\">".$proj_duration_print."</td>";
												if ($user_usertype_current > 2) { echo "<td style=\"$highlight ;min-width: 30px;\"><a href=\"index2.php?page=timesheet_fees_edit&amp;ts_fee_id=$ts_fee_id\"><img src=\"images/button_edit.png\" alt=\"Edit\" class=\"button\" /></a></td>"; }
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
						
						
						
						echo "</table>";
						
						echo "</form>";
						
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
	
	ListAllProjects(UserGetTeam($user_id_current));
	
}

function ClassList($array_class_1,$array_class_2,$type) {
					GLOBAL $proj_id;
					GLOBAL $drawing_class;
					GLOBAL $drawing_type;
					
					echo "<select name=\"" . $type . "\" onchange=\"this.form.submit()\">";
					$array_class_count = 0;
					foreach ($array_class_1 AS $class) {
						echo "<option value=\"" . $class . "\"";
						
						if ($_POST['drawing_class'] == $class && $type == "drawing_class" ) { echo " selected=\"selected\" "; }
						elseif ($_POST['drawing_type'] == $class && $type == "drawing_type" ) { echo " selected=\"selected\" "; }
						
						echo ">";		
						echo $array_class_2[$array_class_count];
						echo "</option>";
						$array_class_count++;
						}
						echo "</select>";
						
					}

function TelephoneMessage($user_id) {
	
	global $conn;

		$user_id = intval($user_id);
		

		if ($_GET['status'] == "all") {
			$sql = "SELECT * FROM intranet_phonemessage LEFT JOIN intranet_user_details ON message_for_user = user_id WHERE message_for_user = '$user_id' ORDER BY message_date DESC";
			echo "<h2>All Messages</h2>";
		} elseif ($_GET['status'] == "user") {
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
	global $backup_path;
	$blog_id = intval($blog_id);
	
	
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

function BackupProjectManual($manual_id) {
	
	global $conn;
	global $user_id_current;
	global $backup_path;
	$manual_id = intval($manual_id);
	
	$time = time();
	
	$sql = "SELECT * FROM intranet_stage_manual LEFT JOIN intranet_user_details ON manual_author = user_id WHERE manual_id = $manual_id LIMIT 1";
	
	$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		
		$array = mysql_fetch_array($result);
		
		$backup_file = $backup_path . "projectmanual_" . $array['manual_id'] . "_" . $array['manual_author'] . "_" . $time . ".html";
		
		$output = "<h1>" . $array['manual_title'] . "</h1>";
		if ($array['manual_section']) { $output = $output . "<h2>" . $array['manual_section'] . "</h2>"; }
		$output = $output . "<h3>By " . $array['user_name_first'] . " " . $array['user_name_second'] . "</h3><hr />";
		$output = $output . "<article>" . $array['manual_text'] . "</article>";
		$output = $output . "<hr /><p>Entry date: " . TimeFormatDetailed($array['manual_updated']) . ", backed up " . TimeFormatDetailed($time) . "</p>";
		
		$file = fopen($backup_file, "w");
		fwrite($file, $output);
		fclose($file);
		
		$alert_message = "<p>Project Manual Entry <a href=\"index2.php?page=manual_page&amp;manual_id=" . $manual_id . "\">\"" . $array['manual_title'] . "</a>\" has been archived to the following location: <a href=\"" . $backup_file . "\">" . $backup_file . "</a></p>";
		
		AlertBoxInsert($user_id_current,"Project Manual Entry Archived",$alert_message,$array['manual_id'],0,0,NULL);
		
	}
	
}

function ListBackups($type,$id) {
	
	global $backup_path;
	
	$limit = 10;
	
	$array_files = scandir ( $backup_path );
	
	$find_file = $type . "_" . $id . "_";
	
		$count = 1;
	
			rsort($array_files);
			
			foreach ($array_files AS $file) {
				
				$file_name = explode("_",$file);
				
				if ($file_name[0] == $type && intval($file_name[1]) == intval($id) ) {
					
					if ($count == 1) { echo "<div id=\"file_history\"><h3>Previous Versions</h3><table>"; }
					
					echo "<tr><td><a href=\"" . $backup_path . $file . "\">" . TimeFormat($file_name[3]) . "</a></td><td>" . GetUserNameOnly($file_name[2]) . "</td></tr>";
					
					 $count++;
					
					}
					
				if ($count > $limit) { echo "<tr><td colspan=\"2\"><i>Showing most recent " . $limit . " backups only.</i></td></tr>"; break;  }
				
			}
			
			
			if ($count > 1) { echo "</table></div>"; }
	
	
}


function ProjectID($type,$table,$identifier,$id) {
	
	global $conn;
	$sql = "SELECT " . $type . " FROM " . $table . " WHERE " . $identifier . " = " . $id . " LIMIT 1";
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	$output = $array[$type];
	
	return $output;
	
}

function FileUploadChecklist($media_title,$media_project,$media_category,$fileName,$fileSize,$fileTmpName,$media_description) {

	unset($actionmessage);
	
	global $conn;
	
    $currentDir = getcwd();
    $uploadDirectory = "/uploads/";
	

    $fileExtensions = array('jpeg','jpg','png','pdf'); // Get all the file extensions

   // $fileName = $_FILES[$checklist_upload]['name'];
    //$fileSize = $_FILES[$checklist_upload]['size'];
    //$fileTmpName  = $_FILES[$checklist_upload]['tmp_name'];
    //$fileType = $_FILES[$checklist_upload]['type'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
	
	$new_file_name = "media_" . date("Y-m-d",time()) . "_" . $_COOKIE[user] . "_" . time() . "." . $fileExtension;
	
	$media_title = addslashes($media_title);

    $uploadPath = $currentDir . $uploadDirectory . $new_file_name;
	

    if (isset($uploadPath)) {
		
		

			if (! in_array($fileExtension,$fileExtensions)) {
				$actionmessage = $actionmessage . "This file extension is not allowed ($checklist_upload)<br />";
				//echo "<p>$actionmessage</p>";
			}

			if ($fileSize > 20000000) {
				$actionmessage = $actionmessage . "This file is more than 10MB. Sorry, it has to be less than or equal to 10MB.<br />";
				//echo "<p>$actionmessage</p>";
			}

			if (empty($actionmessage)) {
				
				
				
				$didUpload = move_uploaded_file($fileTmpName, $uploadPath);

				if ($didUpload) {
					$actionmessage = $actionmessage . "The file " . $fileName . " has been uploaded to " . $currentDir . $uploadDirectory . " and renamed to " . $new_file_name . " with a size of " . MediaSize($fileSize, $precision = 2) . ".<br />";
					
					$media_path = addslashes ($uploadDirectory);
					$media_name = $new_file_name;
					$media_timestamp = time();
					$media_user = intval($_COOKIE[user]);
					$media_type = $fileExtension;
					$media_file = addslashes($new_file_name);
					$media_size = $fileSize;
					$media_description = addslashes($media_description);
					$media_checklist = intval($_POST[media_checklist]);
					$media_project = intval($media_project);
					
					$sql_add = "INSERT INTO intranet_media (
								media_id,
								media_path,
								media_title,
								media_timestamp,
								media_user,
								media_type,
								media_file,
								media_category,
								media_size,
								media_description,
								media_checklist,
								media_project
								) values (
								'NULL',
								'$media_path',
								'$media_title',
								$media_timestamp,
								$media_user,
								'$media_type',
								'$media_file',
								'$media_category',
								'$media_size',
								'$media_description',
								$media_checklist,
								$media_project
								)";
								
								$result = mysql_query($sql_add, $conn) or die(mysql_error());
								$id_added = mysql_insert_id();
								
								$output_file_link = $media_path . $media_file;
								return $output_file_link;
					
				} else {
					
					
					$actionmessage = $actionmessage . "An error occurred somewhere. Try again or contact the administrator.<br />";
					
					//echo "<p>$actionmessage from $fileTmpName to $uploadPath </p>";
					
				}
			}
		} else {
			
			$actionmessage = $actionmessage . "An unknown error occurred.<br />";

			
		}
		
		$actionmessage = "<p>" . rtrim($actionmessage,"<br />") .  "</p>";

		AlertBoxInsert($media_user,"File Upload",$actionmessage,$id_added,0,1,$media_project);

}

function GetNextBankHoliday($time) {
	
	if (!$time) { $time = time(); } else { $time = intval($time); }
	
	global $conn;

	$sql_bankholidays = "SELECT bankholiday_timestamp, bankholidays_description FROM intranet_user_holidays_bank WHERE bankholiday_timestamp > " . $time . " ORDER BY bankholiday_timestamp LIMIT 1";
	$result_bankholidays = mysql_query($sql_bankholidays, $conn);
	$array_bankholidays = mysql_fetch_array($result_bankholidays);
	$bankholiday_timestamp  = $array_bankholidays['bankholiday_timestamp'];
	
	echo "<p>The next Bank Holiday is " . $array_bankholidays['bankholidays_description'] . ", on " . TimeFormat($bankholiday_timestamp) . ".</p>";

}

function NextWorkingDay() {
	
	global $conn;
	
	$sql1 = "SELECT bankholidays_datestamp FROM intranet_user_holidays_bank WHERE bankholidays_datestamp > '" . date("Y-m-d",time()) . "' LIMIT 1";
	$result1 = mysql_query($sql1, $conn);
	$array1 = mysql_fetch_array($result1);
	
	$nextday_array = array();
	
	$nextday_array[] = $array1['bankholidays_datestamp'];
	
	$sql2 = "SELECT holiday_datestamp FROM intranet_user_holidays WHERE holiday_datestamp > '" . date("Y-m-d",time()) . "' AND holiday_user = " . intval($_COOKIE['user']);
		
	$result2 = mysql_query($sql2, $conn);
	
	while ($array2 = mysql_fetch_array($result2)) {
		
		$nextday_array[] = $array2['holiday_datestamp'];
				
	}
	
	$thisday = time() + 86400;
	
	while (in_array($nextday_array) OR date("w",$thisday) == 6 OR date("w",$thisday) == 0) {

		$thisday = $thisday + 86400;
		
	}
		
	return $thisday;
	
}

function CheckAnyNextDay($user_id) {
	
	global $conn;
	$sql = "SELECT location_id FROM intranet_user_location WHERE location_date > '" . date("Y-m-d",time()) . "' AND location_user = " . intval($user_id) . " LIMIT 1";
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	
	//echo "<P>" . $sql . "</p>";
	
	if ($array['location_id'] > 1) { return 1; } else { return 0; }
	
}

function GetNextDay($time) {
	
	global $conn;
	
	if (intval($time)) { $time = intval($time); } else { $time = time(); }
	
	$time = $time + 86400;
	
	$sql1 = "SELECT * FROM intranet_user_location WHERE location_date > '" . date("Y-m-d",time()) . "' AND location_user = " . intval($_COOKIE['user']) . " ORDER BY location_date ASC LIMIT 1";
	$result1 = mysql_query($sql1, $conn);
	$array1 = mysql_fetch_array($result1);
	
	$sql2 = "SELECT user_name_first, user_name_second FROM intranet_user_location LEFT JOIN intranet_user_details ON user_id WHERE location_date = '" . date("Y-m-d",$time) . "' AND location_user != " . intval($_COOKIE['user']) . " AND location_type = 'In the studio' AND user_id = location_user";
	
	//echo "<P>" . $sql2 . "</p>";
	
	$result2 = mysql_query($sql2, $conn);
	
	$in_the_studio = mysql_num_rows($result2);
	
	$people_in_the_studio = array();
	
	while ($array2 = mysql_fetch_array($result2)) {
		
		$people_in_the_studio[] = $array2['user_name_first'] . " " . $array2['user_name_second'];
		
	}
	
	$nextworkingdate = NextWorkingDay();
	
	if (date("Y-m-d",$time) == $array1['location_date']) { $next_day_text = "Tomorrow"; } else { $next_day_text = "On " . date("l", $nextworkingdate); }
	
	if ($array1['location_id'] == "In the studio" && $in_the_studio == 0) { $output =  "<br /><p>" . $next_day_text . ", I will be " . strtolower($array1['location_type']) . " alone."; }
	elseif ($array1['location_id'] == "In the studio" && $in_the_studio == 1) { $output =  "<br /><p>" . $next_day_text . ", I will be " . strtolower($array1['location_type']) . " with one other."; }
	elseif ($array1['location_id'] == "In the studio" && $in_the_studio > 1) { $output =  "<br /><p>" . $next_day_text . ", I will be " . strtolower($array1['location_type']) . " with " . $array2['in_the_studio'] .  "others."; }
	elseif ($array1['location_id'] && $in_the_studio == 0) { $output =  "<br /><p>" . $next_day_text . ", I will be " . strtolower($array1['location_type']) . "."; }
	elseif ($array1['location_id'] && $in_the_studio == 1) { $output =  "<br /><p>" . $next_day_text . ", I will be " . strtolower($array1['location_type']) . ". There will be 1 person in the studio."; }
	elseif ($array1['location_id'] && $in_the_studio > 1) { $output =  "<br /><p>" . $next_day_text . ", I will be " . strtolower($array1['location_type']) . ". There will be " . $in_the_studio . " people in the studio: " . join(", ",$people_in_the_studio) . "."; }
		
	return $output;
	
}

function GetTeamName($team_id) {
	
	global $conn;
	$sql = "SELECT team_name FROM intranet_team WHERE team_id = " . intval($team_id) . " LIMIT 1";
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	
	return $array['team_name'];
	
}
