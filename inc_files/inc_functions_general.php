<?php

$removestrings_all = array("<",">","|");
$removestrings_phone = array("+44","(",")");

$currency_symbol = array("£","€");
$currency_text = array("&pound;","&euro;");
$currency_junk = array("£","€");

$text_remove = array("Ã","Â");

ini_set("upload_max_filesize","10M");

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

function ProjectProcurement($proj_procure) {
	
		GLOBAL $conn;

		$sql = "SELECT * FROM intranet_procure order by procure_title";
		$result = mysql_query($sql, $conn) or die(mysql_error());

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
					echo "<a href=\"" . $page . "group_id=" . $group_id . "\" class=\"submenu_bar\">$group_code</a>";
				} else {
					echo "<a href=\"" . $page . "group_id=" . $group_id . "\" class=\"submenu_bar\">$group_code</a>";
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

function SearchPanel($user_usertype_current,$search_id) {

	
	echo "<form action=\"index2.php?page=search\" method=\"post\">";
	
	if ($_POST[tender_search] == "yes") { $checked1 = " checked = \"checked\" "; } else { unset($checked1) ; }
	if ($_POST[search_phrase] == "yes") { $checked2 = " checked = \"checked\" "; } else { unset($checked2) ; }

	echo "<p><input type=\"search\" name=\"keywords\" value=\"$_POST[keywords]\" id=\"$search_id\" onClick=\"SelectAll('$search_id');\" /></p>";
	
	if ($user_usertype_current > 1) {
		echo "<p><input type=\"checkbox\" name=\"tender_search\" value=\"yes\" $checked1 />&nbsp;<span class=\"minitext\">Search tenders?</span><br />";
	} else {
		echo "<p>";
	}
	
	echo "<input type=\"checkbox\" name=\"search_phrase\" value=\"yes\" $checked2 />&nbsp;<span class=\"minitext\">Search Complete Phrase?</span></p>";
	
	echo "<p><input type=\"submit\" value=\"Go\" /></p>";
	
	echo "</form>";
	
	
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
			
		} else {
		
		$output = array($proj_id,$proj_num,$proj_name);
		return $output;
		
		}
		
	}

	


}

function ProjectSubMenu($proj_id,$user_usertype_current,$page) {

				$array_menu_page = array();
				$array_menu_text = array();
				$array_menu_image = array();
				$array_menu_usertype = array();

	if ($page == "project_edit") {

				$array_menu_page[] = "index2.php?page=project_edit&amp;status=edit&amp;proj_id=$proj_id";
				$array_menu_text[] = "Edit Project";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype = 3;
			
				$array_menu_page[] = "index2.php?page=project_edit&amp;status=add";
				$array_menu_text[] = "Add Project";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype = 0;
			
				$array_menu_page[] = "pdf_project_sheet.php?proj_id=$proj_id";
				$array_menu_text[] = "Project Sheet";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype = 2;
			
		
	} elseif ($page == "project_invoice") {
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_edit&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add Invoice";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype = 3;

				$array_menu_page[] = "index2.php?page=timesheet_invoice_items_edit&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add Invoice Item";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype = 3;
				
	} elseif ($page == "project_fee") {
	
				$array_menu_page[] = "index2.php?page=project_hourlyrates_view&amp;proj_id=$proj_id";
				$array_menu_text[] = "Edit Hourly Rates";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype = 3;
				
				$array_menu_page[] = "index2.php?page=project_timesheet_view&amp;proj_id=$proj_id";
				$array_menu_text[] = "View Expenditure";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype = 3;
				
				$array_menu_page[] = "index2.php?page=timesheet_fees_edit&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add Fee Stage";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype = 3;
				
				$array_menu_page[] = "pdf_fee_drawdown.php?proj_id=$proj_id";
				$array_menu_text[] = "View Fee Drawdown";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype = 3;
				
				$array_menu_page[] = "pdf_fee_drawdown.php?proj_id=$proj_id&amp;showinvoices=yes";
				$array_menu_text[] = "View Fee Drawdown (with invoices)";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype = 3;
				
	} elseif ($page == "project_contacts") {
	
				$array_menu_page[] = "index2.php?page=project_contacts&amp;contact_proj_add=add&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add Project Contact";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype = 3;
				
	} elseif ($page == "project_blog" OR $page == "project_blog_list" OR $page == "project_blog_edit") {
	
				$array_menu_page[] = "index2.php?page=project_blog_edit&status=add&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add Journal Entry";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype = 1;
				
				$array_menu_page[] = "index2.php?page=project_blog_list&amp;proj_id=$proj_id";
				$array_menu_text[] = "List Journal Entries";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype = 1;
				
	} elseif ($page == "project_tasks") {
	
				$array_menu_page[] = "index2.php?page=tasklist_edit&amp;proj_id=$proj_id";
				$array_menu_text[] = "Add New Task";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype = 1;
		
				$array_menu_page[] = "index2.php?page=tasklist_project&amp;proj_id=$proj_id";
				$array_menu_text[] = "Outstanding Tasks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype = 1;
				
				$array_menu_page[] = "index2.php?page=tasklist_project&amp;view=complete&amp;proj_id=$proj_id";
				$array_menu_text[] = "Completed Tasks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype = 1;
	}


		$count = 0;
		
		echo "<div class=\"submenu_bar\">";

		foreach ($array_menu_page AS $menu_link) {
		 		 
				echo "<a href=\"$array_menu_page[$count]\" class=\"submenu_bar\">";
				if ($array_menu_image[$count]) { echo "<img src=\"images/$array_menu_image[$count]\" />&nbsp;"; }
				echo $array_menu_text[$count];
				
				echo "</a>";
		 
				$count++;
		 
		}

		echo "</div>";



		
}

function ProjectList($proj_id) {
	
	echo "<h2>Project Information</h2>";
	TopMenu ("project_view1",1,$proj_id);
	ProjectSubMenu($proj_id,$user_usertype_current,"project_edit");

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
					echo "<tr><td>Procurement Method</td><td>$proj_procure</td></tr>";
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

function ProjectSelect($proj_id_select,$field_name) {
	
		GLOBAL $conn;
		
		if ($proj_id_select > 0) {
			$proj_id_select_add = "(proj_active = 1 OR proj_id = $proj_id_select)";
		} else {
			$proj_id_select_add = "proj_active = 1";			
		}
	
		echo "<select name=\"" . $field_name .  "\">";
		$sql = "SELECT * FROM intranet_projects WHERE $proj_id_select_add ORDER BY proj_num DESC";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		while ($array = mysql_fetch_array($result)) {
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
		$input = "£".number_format($input,2,'.',',');
		return($input);
		}
		
function RemoveShit($input) {
$remove_symbols = array("Â","Ã");
$swap_1 = array("â‚¬", "\n");
$replace_1 = array("€", "\n");
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

function DayLink($input) {
	
	$output = "<a href=\"index2.php?page=datebook_view_day&amp;time=" . $input . "\">" . TimeFormat($input) . "</a>";
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

function ListAvailableImages($directory) {
	
	global $conn;
	
	$sql = "SELECT * FROM intranet_media WHERE media_type = 'png' OR media_type = 'jpg' OR media_type = 'gif' ORDER BY media_title, media_timestamp DESC";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
		
		$list = $list . "{title: '" . $array['media_title'] . "', value: '" . $array['media_path'] . $array['media_file'] . "'},";
		
	}
	
	$list = rtrim($list,",");
	
	echo $list;

	
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

						echo "<h2 id=\"holidaysthisyear\">Holidays in $year</h2>";

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
		
		echo "<table><tr><td rowspan=\"4\">Change selected holidays</td>
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
			$sql = "SELECT * FROM intranet_tender WHERE tender_submitted = 1 ORDER BY tender_date DESC";
			echo "<h2>List of all submitted tenders</h2>";
		} elseif (intval($_GET[tender_pending]) == 1) {
			$sql = "SELECT * FROM intranet_tender WHERE tender_submitted = 1 AND (tender_result = 0 OR tender_result IS NULL) ORDER BY tender_date DESC";
			echo "<h2>List of all pending tenders</h2>";
		} else {
			$sql = "SELECT * FROM intranet_tender ORDER BY tender_date DESC";
			echo "<h2>List of all tenders</h2>";
		}
		
		$result = mysql_query($sql, $conn) or die(mysql_error());

				echo "<div class=\"submenu_bar\">";
							
					if (intval($_GET[tender_submitted]) == 1 OR intval($_GET[tender_pending]) == 1) {
						echo "<a href=\"index2.php?page=tender_list\" class=\"submenu_bar\">List All Tenders</a>";
					}
				
				
					if (intval($_GET[tender_submitted]) != 1) {
						echo "<a href=\"index2.php?page=tender_list&amp;tender_submitted=1\" class=\"submenu_bar\">List Only Submitted Tenders</a>";
					}
					
					if (intval($_GET[tender_pending]) != 1) {
						echo "<a href=\"index2.php?page=tender_list&amp;tender_pending=1\" class=\"submenu_bar\">List Only Pending Tenders</a>";
					}
					
					if ($user_usertype_current > 3) {
						echo "<a href=\"index2.php?page=tender_edit\" class=\"submenu_bar\">Add Tender <img src=\"images/button_new.png\" alt=\"Add New Tender\" /></a>";
					}

					
				echo "</div>";
				
				


				if (mysql_num_rows($result) > 0) {
				
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
				
				if ((($tender_date - $nowtime) < 86400) && (($tender_date - $nowtime) > 0)) {
					$style = "style=\"background: rgba(255,130,0,0.5); border: solid 1px rgba(255,130,0,0.8);\"";
				} elseif ((($tender_date - $nowtime) < 604800) && (($tender_date - $nowtime) > 0)) {
					$style = "style=\"background: rgba(255,130,0,0.5); border: solid 1px rgba(255,130,0,0.8);\"";
				} elseif ($tender_date > time()) {
					$style = "style=\"background: rgba(175,213,0,0.3); border: solid 1px rgba(175,213,0,0.8);\"";
				} elseif ($tender_submitted == 1 && $tender_result == 1) {
					$style = "style=\"background: rgba(0,0,255,0.3); border: solid 1px rgba(0,0,255,0.8);\"";
				} elseif ($tender_submitted == 1 && $tender_result == 2) {
					$style = "style=\"background: rgba(255,0,0,0.3); border: 1px solid rgba(255,0,0,0.8);\"";
				} elseif ($tender_submitted == 1 && $tender_result == 0) {
					$style = "style=\"background: rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.8);\"";
				} elseif ($tender_date < time()) {
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
	
	$drawing_status_array = array("","S1","S2","S3","S4");
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

					echo "<h2>Journal Entries</h2>";

					$sql = "SELECT * FROM intranet_projects_blog, intranet_projects, intranet_user_details WHERE blog_proj = proj_id AND proj_id = '$proj_id' AND blog_user = user_id AND (blog_access = $user_usertype_current OR blog_access IS NULL) order by blog_date DESC";
					$result = mysql_query($sql, $conn) or die(mysql_error());
					$result_project = mysql_query($sql, $conn) or die(mysql_error());
					$array_project = mysql_fetch_array($result_project);
					$proj_num = $array_project['proj_num'];
					$proj_name = $array_project['proj_name'];
					$user_name_first = $array_project['user_name_first'];
					$user_name_second = $array_project['user_name_second'];
					$user_id = $array_project['user_id'];

					// Include a bar to navigate through the pages

							echo "<p class=\"submenu_bar\">";

							$items_to_view = 10;

							if ($_GET[limit] == NULL) {$limit = 0; } else { $limit = $_GET[limit]; }
							$total_items = mysql_num_rows($result);
							$page_prev = $limit - $items_to_view;
							$page_next = $limit + $items_to_view;
							
							if ($limit > 0) { echo "<a href=\"index2.php?page=project_blog_list&amp;proj_id=$proj_id&amp;limit=$page_prev\" class=\"submenu_bar\">Previous Page</a>"; }
							if ($page_next < $total_items) { echo "<a href=\"index2.php?page=project_blog_list&amp;proj_id=$proj_id&amp;limit=$page_next\" class=\"submenu_bar\">Next Page</a>"; }
							echo "</p>";


					$nowtime = time();

					if (mysql_num_rows($result) > 0) {

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
						
					 if ($counter >= $limit AND $counter < $page_next) {
							$counter_title++;
							echo "<tr>";
							echo "<td>$type.</td><td><a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id&amp;proj_id=$proj_id\">".$blog_title."</a>&nbsp;<a href=\"pdf_journal.php?blog_id=$blog_id\"><img src=\"images/button_pdf.png\" /></a></td>";
							echo "<td style=\"width: 20%;\"><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date\">".TimeFormat($blog_date)."</a></td>";
							echo "<td><a href=\"index2.php?page=user_view&amp;user_id=$user_id\">".$blog_user_name_first."&nbsp;".$blog_user_name_second."</a></td>";
							echo "<td style=\"width: 20%;\"><span class=\"minitext\">$blog_type_view</span></td>";
							echo "</tr>";
					}

					$title = $blog_type;
					$counter++;

					}


					echo "</table>";

					} else {

					echo "<p>There are no journal entries on the system for this project.</p>";

					}

}

function SearchTerms($search_text,$search_field) {
		$counter = 0;
		$max_count = count($search_text);
		while($counter < $max_count) {
		if ($counter > 0) { $searching_blog = $searching_blog." AND $search_field LIKE "; }
		$searching_blog = $searching_blog."'%".$search_text[$counter]."%'";
		$counter++;
		}
		$searching_blog = "$search_field LIKE ".$searching_blog;
		return($searching_blog);
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

function AlertBoxInsert($user_id,$alert_category,$alert_message,$alert_entryref,$snoozetime,$verbose) {
	
		global $conn;
		
		$alert_entryref = intval ( $alert_entryref );
		
		if ($alert_entryref > 0) {
		
			$verbose = intval($verbose);
			$snoozetime = intval($snoozetime);
			$user_id = intval($user_id);
			
			$alert_url = "'" . addslashes ( $_SERVER['HTTP_REFERER'] ) . "'";
			
			$sql = "SELECT * FROM intranet_alerts WHERE alert_timestamp > " . (time() - $snoozetime) . " AND alert_user = " . $user_id . " AND alert_category = '" . $alert_category . "' AND alert_entryref = " . $alert_entryref . " LIMIT 1";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			if (mysql_num_rows($result) == 0) {
				$sql_add = "INSERT INTO intranet_alerts (alert_id, alert_user, alert_category, alert_message, alert_timestamp, alert_status, alert_entryref, alert_url) VALUES (NULL, " . $user_id . ",'" . $alert_category . "','" . $alert_message . "'," . time() . "," . $verbose . ", " . $alert_entryref . ", " . $alert_url . ")";
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

function AlertsList($user_id,$user_usertype_current) {

	global $conn;
	global $user_usertype_current;
	
	$user_usertype_current = intval($user_usertype_current);
	
	$user_id = intval($user_id);
	
		if ($_GET[view] == "all" && $user_usertype_current > 4) { unset($filter); } else { $filter = "WHERE alert_user = " . $user_id; }

		$sql = "SELECT * FROM intranet_alerts LEFT JOIN intranet_user_details ON user_id = alert_user $filter ORDER BY alert_timestamp DESC LIMIT 100";
		
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result) > 0) {
			
			echo "<table>";
			
			echo "<tr><th>ID</th><th style=\"width: 20%;\">Subject</th><th style=\"width: 50%;\">Content</th><th>User</th><th style=\"text-align: right;\">Date</th><th style=\"text-align: right;\">Dismissed</th></tr>";
		
			while ($array = mysql_fetch_array($result)) { 
				
				if ($array['alert_status'] == 0) { $alert_message = "<strong>" . $array['alert_message'] . "</strong>"; } else { $alert_message = $array['alert_message']; }
				
				if ($array['alert_updated']) { $time_format = TimeFormat($array['alert_updated']) . "<br /><span class=\"minitext\">" . date("H:i",$array['alert_updated']) ."</span>"; } else { $time_format = "-"; }
				
				
							
				echo "<tr><td>" . $array['alert_id'] . "</td><td>" . $array['alert_category'] . "</td><td>" . $alert_message . "</td><td>" . $array['user_name_first'] . " " . $array['user_name_second'] . "</td><td style=\"text-align: right;\">" . TimeFormat($array['alert_timestamp'])  . "<br /><span class=\"minitext\">" . date("H:i",$array['alert_timestamp']) ."</span></td><td style=\"text-align: right;\">" . $time_format  . "</td></tr>";
				
				if ($user_usertype_current > 4 && $array['alert_url']) { echo "<tr><td colspan=\"2\"></td><td colspan=\"4\"><p><span class=\"minitext\">". $array['alert_url'] . "</span></p></td></tr>"; }
			
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


	echo "<table>";
	echo "<tr><th>Item</th><th>Stage</th><th>Required</th><th style=\"width: 15%;\">Date Completed</th><th colspan=\"4\">Comment</th></tr>";

	$current_item = 0;

	if (mysql_num_rows($result_checklist) > 0) {

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

}


echo "</table>";


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

				}

				
				ProjectSubMenu($proj_id,$user_usertype_current,"project_fee");
				
				echo "<h2>Fee Stages</h2>";

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
												if ($user_usertype_current > 2) { echo "<td style=\"$highlight\"><a href=\"index2.php?page=timesheet_fees_edit&amp;ts_fee_id=$ts_fee_id\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a></td>"; }
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

echo "<div id=\"item_switch_3\">";

		// Project Page Menu
		echo "<div class=\"submenu_bar\">";
			if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
				echo "<a href=\"index2.php?page=project_edit&amp;status=edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Edit</a>";
			}
			if ($user_usertype_current > 1) {
				echo "<a href=\"index2.php?page=project_blog_edit&amp;status=add&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add Project Journal Entry</a>";
			}
		echo "</div>";

					if ($proj_date_start > 0 OR $proj_date_complete > 0 OR $proj_date_proposal > 0 OR $proj_date_appointment > 0) {
							echo "<h2>Project Dates</h2><table summary=\"Project Dates\">";
							if ($proj_date_proposal > 0) { echo "<tr><td style=\"width: 40%;\">Date of Proposal</td><td>".TimeFormat($proj_date_proposal)."</td></tr>"; }
							if ($proj_date_appointment > 0) { echo "<tr><td style=\"width: 40%;\">Date of Appointment</td><td>".TimeFormat($proj_date_appointment)."</td></tr>"; }
							if ($proj_date_start > 0) { echo "<tr><td style=\"width: 40%;\">Start Date</td><td>".TimeFormat($proj_date_start)."</td></tr>"; }
							if ($proj_date_complete > 0) { echo "<tr><td style=\"width: 40%;\">Completion Date</td><td>".TimeFormat($proj_date_complete)."</td></tr>"; }
							echo "</table>";
					}		
					

					echo "<h2>Project Particulars</h2><table summary=\"Project Particulars\">";

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

echo "</div>";

}


function ProjectInvoices($proj_id) {

		echo "<h2>Project Invoices</h2>";


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




function ProjectContacts($proj_id,$user_usertype_current) {

global $conn;

			
			$sql_contact = "SELECT * FROM contacts_disciplinelist, contacts_contactlist, intranet_contacts_project LEFT JOIN contacts_companylist ON contact_proj_company = contacts_companylist.company_id WHERE contact_proj_contact = contact_id  AND discipline_id = contact_proj_role AND contact_proj_project = $proj_id ORDER BY discipline_name, contact_namesecond";
			$result_contact = mysql_query($sql_contact, $conn) or die(mysql_error());

			if (mysql_num_rows($result_contact) > 0) {

			echo "<table>";
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
			echo "</table>";

			} else { echo "<p>- None - </p>"; }

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

		echo "<form method=\"post\" action=\"index2.php?page=project_contacts&amp;proj_id=$proj_id\">";

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

		echo "</form>";

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

	$sql = "SELECT *, UNIX_TIMESTAMP(ts_fee_commence) FROM intranet_user_details, intranet_projects LEFT JOIN intranet_timesheet_fees ON `proj_riba` = `ts_fee_id` WHERE proj_rep_black = user_id $project_active AND proj_fee_track = 1 order by proj_num";
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
		$proj_riba = $array['proj_riba'];
		
		// This has been added since the last update
		
		$ts_fee_text = $array['ts_fee_text'];
		
		//
		
		$sql_task = "SELECT tasklist_id FROM intranet_tasklist WHERE tasklist_project = " . intval( $proj_id ) . " AND tasklist_person = " . intval( $user_id_current ) . " AND tasklist_percentage < 100 ORDER BY tasklist_due DESC";
		$result_task = mysql_query($sql_task, $conn) or die(mysql_error());
		$project_tasks_due = mysql_num_rows($result_task);
		if ( $project_tasks_due > 0) { $add_task = "<br /><span class=\"minitext\"><a href=\"index2.php?page=tasklist_project&amp;proj_id=$proj_id&amp;show=user\">You have $project_tasks_due pending task(s) for this project</a></span>"; } else { $add_task = NULL; }
		
		if ($ts_fee_text != NULL) { $current_stage = $ts_fee_text; } elseif ($proj_fee_type == NULL) { $current_stage = "--"; } elseif ($riba_id == NULL) { $current_stage = "Prospect"; } else { $current_stage = $riba_letter." - ".$riba_desc; }
		
		if (array_search($proj_id,$array_projects_recent) > 0 OR $_GET[active] != NULL) {
			
								if ($_GET[active] == NULL) {
								$array_projectcheck = TimeRemaining($proj_id, $proj_riba, $ts_fee_target, $ts_fee_value);
								}
								if ($array_projectcheck[1]!= NULL) { $row_color = $array_projectcheck[1]; } else { unset($row_color); } 
								if ($array_projectcheck[0]!= NULL) { $row_text = "<br />" . $array_projectcheck[0]; } else { unset($row_text); } 

											echo "<tr><td class=\"" . $row_color  . "\">";
											
											if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
												echo "<a href=\"index2.php?page=project_edit&amp;proj_id=$proj_id&amp;status=edit\" style=\"float: right;\" class=\"HideThis $row_color \"><img src=\"images/button_edit.png\" alt=\"Edit\" />";
											}
											
											echo ProjActive($proj_active,$proj_num,$proj_id) . "&nbsp";

											echo ProjActive($proj_active,$proj_name,$proj_id).$add_task;
											
											
											
											echo "</td>";
											
											if ($_GET[active] == "current") { echo "<td class=\"HideThis $row_color \"><span class=\"minitext\">" . $proj_desc . "</span></td>"; }
											
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

function TopMenu ($page_type,$level,$proj_id) {
	
	global $user_usertype_current;
	
	if (intval($proj_id) > 0) { $proj_id = intval($proj_id); } else { unset($proj_id); }
	
	if ($page_type == "media") {
		$links = array("index2.php?page=media","index2.php?page=media&amp;action=upload");
		$buttons = array("Browse Library","Upload Files");
		$access = array(0,3);
		unset($js);
	} elseif ($page_type == "default1") {
		$links = array("#","#","#");
		$buttons = array("Projects","Tasks","Messages");
		$access = array(0,0,0);
		$js = array("onclick=\"itemSwitch(1); return false;\"","onclick=\"itemSwitch(2); return false;\"","onclick=\"itemSwitch(3); return false;\"");
	} elseif ($page_type == "project_view1") {
		$links = array("index2.php?page=project_view&amp;proj_id=$proj_id","index2.php?page=project_contacts&amp;proj_id=$proj_id","index2.php?page=tasklist_project&amp;proj_id=$proj_id","index2.php?page=drawings_list&amp;proj_id=$proj_id","index2.php?page=project_checklist&amp;proj_id=$proj_id","index2.php?page=project_planningcondition_list&amp;proj_id=$proj_id","#","#","index2.php?page=project_blog_list&amp;proj_id=$proj_id","#");
		$buttons = array("Project Home","Contacts","Tasks","Drawings","Checklist","Planning Tracker","Fees","Invoices","Journal","Particulars");
		$access = array(1,1,1,1,1,1,3,4,1,1);
		$js = array(NULL,NULL,NULL,NULL,NULL,NULL,"onclick=\"itemSwitch(5); return false;\"","onclick=\"itemSwitch(6); return false;\"",NULL,"onclick=\"itemSwitch(3); return false;\"");
	} elseif ($page_type == "project_view2") {
		$links = array("#","#","#");
		$buttons = array("Projects","Tasks","Messages");
		$access = array(0,0,0);
		$js = array("onclick=\"itemSwitch(1); return false;\"","onclick=\"itemSwitch(2); return false;\"","onclick=\"itemSwitch(3); return false;\"");		
	} elseif ($page_type == "project_ambition_schedule") {
		$links = array("index2.php?page=project_ambition_schedule&amp;type=project_marketing&amp;filter=active","index2.php?page=project_ambition_schedule&amp;type=project_marketing&amp;filter=all");
		$buttons = array("Active Projects","All Projects");
		$access = array(0,0);
		unset($js);	
	}
	
	$counter = 0;
	
	if ($level == 1) { $class1 = "menu_bar"; $class2 = "menu_tab"; } else { $class1 = "submenu_bar"; $class2 = "submenu_bar"; }
		
		
		echo "<div class=\"" . $class1 . "\">";
		
		foreach ($links AS $link) {
			if ($user_usertype_current >= $access[$counter]) {
				echo "<a href=\"" . $link . "\" class=\"" . $class2 . "\" " . $js[$counter] . ">" . $buttons[$counter] . "</a>";
			}
			$counter++;
		}
		
		echo "</div>";	
	
	
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
	
	function ProjectDrawingList($proj_id) {
		
		global $conn;
					
					echo "<h2>Drawing List</h2>";

					TopMenu ("project_view1",1,$proj_id);
					
					echo "<div class=\"submenu_bar\"><a href=\"pdf_drawing_list.php?proj_id=$proj_id\" class=\"submenu_bar\">Drawing Schedule&nbsp;<img src=\"images/button_pdf.png\" alt=\"Download drawing list as PDF\" /></a><a href=\"pdf_drawing_matrix.php?proj_id=$proj_id\" class=\"submenu_bar\">Drawing Matrix&nbsp;<img src=\"images/button_pdf.png\" alt=\"Download drawing matrix as PDF\" /></a></div>";
					
					$drawing_class = $_POST[drawing_class];
					$drawing_type = $_POST[drawing_type];
					echo "<div style=\"float: left;\"><h3>Filter:</h3>";
					echo "<form method=\"post\" action=\"index2.php?page=drawings_list&amp;proj_id=$proj_id&amp;drawing_class=$drawing_class&amp;drawing_type=$drawing_type\" >";
					$array_class_1 = array("","SK","PL","TD","CN","CT","FD");
					$array_class_2 = array("- All -","Sketch","Planning","Tender","Contract","Construction","Final Design");
					ClassList($array_class_1,$array_class_2,"drawing_class");
					echo "&nbsp;";
					$array_class_1 = array("","SV","ST","GA","AS","DE","DOC");
					$array_class_2 = array("- All -","Survey","Site Location","General Arrangement","Assembly","Detail","Document");
					ClassList($array_class_1,$array_class_2,"drawing_type");
					echo "</form></div>";
					
					if ($drawing_class != NULL) { $drawing_class = " AND drawing_number LIKE '%-$drawing_class-%' "; } else { unset($drawing_class); }
					if ($drawing_type != NULL) { $drawing_type = " AND drawing_number LIKE '%-$drawing_type-%' "; } else { unset($drawing_type); }

				$sql = "SELECT * FROM intranet_drawings, intranet_drawings_scale, intranet_drawings_paper WHERE drawing_project = $proj_id AND drawing_scale = scale_id AND drawing_paper = paper_id $drawing_class $drawing_type order by drawing_number";
				$result = mysql_query($sql, $conn) or die(mysql_error());


						if (mysql_num_rows($result) > 0) {

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
						
						

						} else {

						echo "<table><tr><td>No drawings found.</td></tr></table>";

						}
	}
