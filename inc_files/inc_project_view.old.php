<?php

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

$proj_consult_41 = $array['proj_consult_41'];
$proj_consult_42 = $array['proj_consult_42'];
$proj_consult_43 = $array['proj_consult_43'];
$proj_consult_6 = $array['proj_consult_6'];
$proj_consult_7 = $array['proj_consult_7'];
$proj_consult_8 = $array['proj_consult_8'];
$proj_consult_9 = $array['proj_consult_9'];
$proj_consult_10 = $array['proj_consult_10'];
$proj_consult_11 = $array['proj_consult_11'];
$proj_consult_12 = $array['proj_consult_12'];
$proj_consult_13 = $array['proj_consult_13'];
$proj_consult_14 = $array['proj_consult_14'];
$proj_consult_15 = $array['proj_consult_15'];
$proj_consult_16 = $array['proj_consult_16'];
$proj_consult_17 = $array['proj_consult_17'];
$proj_consult_18 = $array['proj_consult_18'];
$proj_consult_19 = $array['proj_consult_19'];

$proj_planning_ref = $array['proj_planning_ref'];
$proj_buildingcontrol_ref = $array['proj_buildingcontrol_ref'];
$proj_fee_percentage = $array['proj_fee_percentage'];

// Determine the country
$sql = "SELECT country_printable_name FROM intranet_contacts_countrylist where country_id = '$proj_address_country' LIMIT 1";
$result = mysql_query($sql, $conn);
$array = mysql_fetch_array($result);
$country_printable_name = $array['country_printable_name'];



$proj_tenant_1 = $array['proj_tenant_1'];

print "<h1>".$proj_num."&nbsp;".$proj_name."</h1>";

print "<p class=\"menu_bar\">";
print "<a href=\"#\" onclick=\"itemSwitch(1); return false;\" class=\"menu_tab\">Main</a>";
print "<a href=\"#\" onclick=\"itemSwitch(2); return false;\" class=\"menu_tab\">Client</a>";
print "<a href=\"#\" onclick=\"itemSwitch(4); return false;\" class=\"menu_tab\">Contacts</a>";
print "<a href=\"#\" onclick=\"itemSwitch(8); return false;\" class=\"menu_tab\">Tasks</a>";
if ($user_usertype_current > 2) {
	print "<a href=\"#\" onclick=\"itemSwitch(5); return false;\" class=\"menu_tab\">Fees</a>";
}
if ($user_usertype_current >= 4) {
	print "<a href=\"#\" onclick=\"itemSwitch(6); return false;\" class=\"menu_tab\">Expenses</a>";
	print "<a href=\"#\" onclick=\"itemSwitch(7); return false;\" class=\"menu_tab\">Invoices</a>";
	print "<a href=\"#\" onclick=\"itemSwitch(9); return false;\" class=\"menu_tab\">Website</a>";
}

print "<a href=\"#\" onclick=\"itemSwitch(3); return false;\" class=\"menu_tab\">Particulars</a>";
print "</p>";


print "<div id=\"item_switch_1\">";

// Project Page Menu
print "<p class=\"submenu_bar\">";
	if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
		print "<a href=\"index2.php?page=project_edit&amp;status=edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Edit</a>";
	}
	if ($user_usertype_current > 0) {
		print "<a href=\"index2.php?page=project_blog_edit&amp;status=add&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add Project Journal Entry</a>";
	}
	print "<a href=\"index2.php?page=tasklist_edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add Project Task</a>";
	if ($user_usertype_current > 2) {
		print "<a href=\"pdf_project_sheet.php?proj_id=$proj_id\" class=\"submenu_bar\">Project Sheet&nbsp;<img src=\"images/button_pdf.png\" alt=\"Output project sheet to PDF\" /></a>";
	}
	if ($user_usertype_current > 1) {
		print "<a href=\"index2.php?page=drawings_list&amp;proj_id=$proj_id\" class=\"submenu_bar\">View Drawings</a>";
	}
	
print "</p>";

					print "<h2>Project Information</h2>";

					print "<table summary=\"Project Information\">";

					print "<tr><td style=\"width: 40%;\">Site Address</td><td>";

					if ($proj_address_1 != "") { print $proj_address_1."<br />"; }
					if ($proj_address_2 != "") {print $proj_address_2."<br />"; }
					if ($proj_address_3 != "") {print $proj_address_3."<br />"; }
					if ($proj_address_town != "") {print $proj_address_town."<br />"; }
					if ($proj_address_county != "") {print $proj_address_county."<br />"; }
					if ($proj_address_postcode != "") {
					    $proj_address_postcode_link = PostcodeFinder($proj_address_postcode);
					    print "<a href=\"".$proj_address_postcode_link."\">".$proj_address_postcode."</a><br />";
					    }
					if ($country_printable_name != "") {print $country_printable_name."<br />"; }

					print "</td></tr>";

					if ($proj_date_start > 0) { print "<tr><td  >Project Start Date</td><td  >$proj_date_start</td></tr>"; }
					if ($proj_date_complete > 0) { print "<tr><td  >Project Completion Date</td><td  >$proj_date_complete</td></tr>"; }

					if ($proj_desc != "") { print "<tr><td  >Project Description</td><td  >$proj_desc</td></tr>"; }

					if ($proj_riba_begin > 0) {
					print "<tr><td  >Starting RIBA Work Stage</td><td  >";
					include("inc_files/inc_data_project_riba_begin.php");
					print "</td></tr>";
					}

					if ($proj_riba > 0) {
					print "<tr><td >Current RIBA Work Stage</td><td  >";
					include("inc_files/inc_data_project_riba_stages.php");
					print "</td></tr>";
					}

					if ($proj_riba_conclude > 0) {
					print "<tr><td>Concluding RIBA Work Stage</td><td  >";
					include("inc_files/inc_data_project_riba_conclude.php");
					print "</td></tr>";
					}

					if ($proj_procure > 0) {
					print "<tr><td>Procurement Method</td><td>$proj_procure</td></tr>";
					}

					if ($proj_value != 0) {
					$proj_value_show = MoneyFormat($proj_value);
					print "<tr><td>Contract Value</td><td><a href=\"index2.php?page=timesheet_value_view&amp;proj_id=$proj_id\">$proj_value_show</a></td></tr>";
					}
					
					if ($proj_fee_percentage > 0) {
					print "<tr><td>Fee Percentage</td><td>".$proj_fee_percentage."%</td></tr>";
					print "<tr><td>Total Fee<br /><span class=\"minitext\">(Assuming 100% of fee)</span></td><td>".MoneyFormat(($proj_value * ($proj_fee_percentage / 100)))."</td></tr>";
					}

					print "</table>";

print "</div><div id=\"item_switch_2\">";

		// Project Page Menu
		print "<p class=\"submenu_bar\">";
			if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
				print "<a href=\"index2.php?page=project_edit&amp;status=edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Edit</a>";
			}
			if ($user_usertype_current > 1) {
				print "<a href=\"index2.php?page=project_blog_edit&amp;status=add&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add Project Journal Entry</a>";
			}
		print "</p>";

					// Pull the details from the contact database

					print "<h2>Client</h2>";
					print "<table summary=\"Client Details\">";

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
								print "<tr><td style=\"width: 40%;\">Invoices / Accounts</td><td >$print_client_name</td></tr>";
							}
				
					
					print "</table>";

print "</div><div id=\"item_switch_3\">";

		// Project Page Menu
		print "<p class=\"submenu_bar\">";
			if ($user_usertype_current > 0 OR $user_id_current == $proj_rep_black) {
				print "<a href=\"index2.php?page=project_edit&amp;status=edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Edit</a>";
			}
			if ($user_usertype_current > 1) {
				print "<a href=\"index2.php?page=project_blog_edit&amp;status=add&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add Project Journal Entry</a>";
			}
		print "</p>";

					if ($proj_date_start > 0 OR $proj_date_complete > 0 OR $proj_date_proposal > 0 OR $proj_date_appointment > 0) {
							print "<h2>Project Dates</h2><table summary=\"Project Dates\">";
							if ($proj_date_proposal > 0) { print "<tr><td style=\"width: 40%;\">Date of Proposal</td><td>".TimeFormat($proj_date_proposal)."</td></tr>"; }
							if ($proj_date_appointment > 0) { print "<tr><td style=\"width: 40%;\">Date of Appointment</td><td>".TimeFormat($proj_date_appointment)."</td></tr>"; }
							if ($proj_date_start > 0) { print "<tr><td style=\"width: 40%;\">Start Date</td><td>".TimeFormat($proj_date_start)."</td></tr>"; }
							if ($proj_date_complete > 0) { print "<tr><td style=\"width: 40%;\">Completion Date</td><td>".TimeFormat($proj_date_complete)."</td></tr>"; }
							print "</table>";
					}		
					

					print "<h2>Project Particulars</h2><table summary=\"Project Particulars\">";

					if ($array['proj_date_start'] != NULL) { $proj_date_start = date("jS F Y", $array['proj_date_start']); } else { unset($proj_date_start); }
					if ($array['proj_date_compelte'] != NULL) { $proj_date_complete = date("jS F Y", $array['proj_date_complete']); } else { unset($proj_date_complete); }

					// Determine the procurement method

					if ($proj_procure != NULL) {

						$sql_procure = "SELECT * FROM intranet_procure where procure_id = $proj_procure LIMIT 1";
						$result_procure = mysql_query($sql_procure, $conn);
						$array_procure = mysql_fetch_array($result_procure);
						$proj_procure = $array_procure['procure_title'];
					}

					print "<tr><td style=\"width: 40%;\">Project Leader</td><td>";

					// Get the user details

						$sql_proj_leader = "SELECT user_name_first, user_name_second, user_email FROM intranet_user_details where user_id = '$proj_rep_black' LIMIT 1 ";
						$result_proj_leader = mysql_query($sql_proj_leader, $conn) or die(mysql_error());

						$array_proj_leader = mysql_fetch_array($result_proj_leader);
						$name_first = $array_proj_leader['user_name_first'];
						$name_second = $array_proj_leader['user_name_second'];
						$user_email = $array_proj_leader['user_email'];
						
						print "<a href=\"index2.php?page=user_view&amp;user_id=111\">".$name_first." ".$name_second."</a>";

						if ($user_email != NULL) { print "<a href=\"mailto:".$user_email."\"><img src=\"images/button_email.png\" alt=\"Send email to $name_first&nbsp;$name_second \" /></a>"; }

					print "</td></tr>
					<tr><td>Database ID</td><td  >$proj_id</td></tr>
					<tr><td>Active</td><td  >";
					if ($proj_active == 0) {
					print "No";
					} else {
					print "Yes";
					}
					print "</td></tr>";

					print "</table>";

print "</div><div id=\"item_switch_4\">";


		// Project Page Menu
		print "<p class=\"submenu_bar\">";
			if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
				print "<a href=\"index2.php?page=project_edit&amp;status=edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Edit</a>";
			}
			if ($user_usertype_current > 1) {
				print "<a href=\"index2.php?page=project_blog_edit&amp;status=add&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add Project Journal Entry</a>";
			}
		print "</p>";

include("inc_files/inc_project_contacts.php");

print "</div>";

if ($user_usertype_current > 2) {

			print "<div id=\"item_switch_5\">";
				include("inc_files/inc_project_fees.php");
			print "</div>";

			print "<div id=\"item_switch_6\">";
				include("inc_files/inc_project_expenses.php");
			print "</div>";

			print "<div id=\"item_switch_7\">";
				include("inc_files/inc_project_invoices.php");
			print "</div>";
			
			print "<div id=\"item_switch_8\">";
				include("inc_files/inc_project_tasks.php");
			print "</div>";
			
}


// Website text

if ($user_usertype_current > 2) {

echo "<div id=\"item_switch_9\">";
echo "<h2>Website entry details</h2>";

include_once("secure/database_website.inc");
$sql_web = "SELECT * FROM rcka_projects where project_num = '$proj_num' LIMIT 1";
$result_web = mysql_query($sql_web, $conn_web);
$array_web = mysql_fetch_array($result_web);
$project_id = $array_web['project_id'];
$project_num = htmlspecialchars($array_web['project_num']);
$project_title = $array_web['project_title'];
$project_location = $array_web['project_location'];
$project_desc = $array_web['project_desc'];

if (mysql_num_rows($result_web) > 0) {

echo "<fieldset><legend>$project_title ($project_num) <a href=\"index2.php?page=project_web_edit&amp;project_num=$project_num\"><img src=\"images/button_edit.png\"></a></legend>";
echo "<p><strong>$project_location</strong></p>";
echo "<blockquote>$project_desc</blockquote>";

echo "</fieldset>";

} else { echo "<p>-- None found --</p>"; }

echo "</div>";

}

print "
		<script type=\"text/javascript\">
		document.getElementById(\"item_switch_1\").style.display = \"block\";
		document.getElementById(\"item_switch_2\").style.display = \"none\";
		document.getElementById(\"item_switch_3\").style.display = \"none\";
		document.getElementById(\"item_switch_4\").style.display = \"none\";
		document.getElementById(\"item_switch_8\").style.display = \"none\";
		";
		
if ($user_usertype_current > 2) {
				print "
				document.getElementById(\"item_switch_5\").style.display = \"none\";
				document.getElementById(\"item_switch_6\").style.display = \"none\";
				document.getElementById(\"item_switch_7\").style.display = \"none\";	
				document.getElementById(\"item_switch_9\").style.display = \"none\";				
				";
}
		
		print "</script>";


?>



