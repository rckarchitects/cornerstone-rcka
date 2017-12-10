<?php

function ListContacts($listorder,$startletter,$desc_order,$listbegin,$listmax) {

	global $conn;

	if (!$startletter) { $startletter = "a"; } else { $startletter = htmlentities($startletter) ; }
	if (!$listorder) { $listorder = "contact_namesecond"; } else { $listorder = htmlentities($listorder) ; }
	if (intval($listbegin) > 0) { $listbegin = intval($listbegin); } else { $listbegin = 0 ;}
	if (intval($listmax) == 0) { $listmax = 1000; } else { $listmax = intval($listmax) ;}
	if (intval($desc_order) > 0) { $desc_order = "DESC"; } else { unset($desc_order); }

  			$sql = "SELECT * FROM contacts_contactlist LEFT JOIN contacts_companylist ON company_id = contact_company LEFT JOIN intranet_contacts_targetlist ON target_contact = contact_id WHERE $listorder LIKE '$startletter%' ORDER BY $listorder $desc_order,contact_namefirst, contact_id LIMIT $listbegin,$listmax";

			$result = mysql_query($sql, $conn) or die(mysql_error());

			if (mysql_num_rows($result) > 0) {
			
			echo "<div id=\"TargetList\" class=\"bodybox\">";
			
				ListUserTargets($_COOKIE[user]);
				
			echo "</div>";
			
			
			while ($array = mysql_fetch_array($result)) {
			
				unset($contact_details_complete);
				
				if (($checkrepeat_namefirst == $array['contact_namefirst']) && ($checkrepeat_namesecond == $array['contact_namesecond']) && ($checkrepeat_company == $array['contact_company'])) { $duplicate = 1; } else { unset($duplicate); }
				

				
					$contact_id = $array['contact_id'];
					$contact_prefix = $array['contact_prefix'];
					$contact_namefirst = $array['contact_namefirst'];
					$contact_namesecond = $array['contact_namesecond'];
					$contact_title = $array['contact_title'];
					$contact_company = $array['contact_company'];
					$contact_telephone = $array['contact_telephone'];
					$contact_fax = $array['contact_fax'];
					$contact_email = $array['contact_email'];
					$contact_sector = $array['contact_sector'];
					$contact_reference = $array['contact_reference'];
					$contact_department = $array['contact_department'];
					$contact_added = $array['contact_added'];
					$contact_relation = $array['contact_relation'];
					
					$target_id = $array['target_id'];
					$target_type = $array['target_type'];
					$target_user = intval ( $array['target_user'] );
					
					$company_id = $array['company_id'];
					$company_name = $array['company_name'];
					$company_address = $array['company_address'];
					$company_city = $array['company_city'];
					$company_county = $array['company_county'];
					$company_postcode = $array['company_postcode'];
					$company_phone = $array['company_phone'];
					$company_fax = $array['company_fax'];
					$company_web = $array['company_web'];
					$contact_mobile = $array['contact_mobile'];
					
					$checkrepeat_namefirst = $contact_namefirst;
					$checkrepeat_namesecond = $contact_namesecond;
					$checkrepeat_company = $contact_company;
					
					if ($target_id > 0 && $target_user == $_COOKIE[user]) {
						$selected = "checked=\"checked\""; 
					} else {
						unset($selected);
					}
					
					if ($duplicate) {
						echo "<div class=\"bodybox1\" id=\"target_$contact_id\">";
					} elseif ($target_id > 0 && $target_user == $_COOKIE[user]) {
						echo "<div class=\"bodybox2\" id=\"target_$contact_id\">";
					} else {
						echo "<div class=\"bodybox\" id=\"target_$contact_id\">";
					}
					
					if ($target_user == $_COOKIE[user] OR $target_user == 0) {
					
						echo "<form><input type=\"checkbox\" class=\"clickbox\" $selected value=\"$contact_id\" name=\"target_user\" /></form>";
					
					}

					if ($company_name) {
						echo "<p class=\"minitext\" id=\"company_$company_id\"><a href=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">$company_name</a></p>";
					} else {
						echo "<p class=\"minitext\">&nbsp;</p>";
					}
					
					if ($user_usertype_current > 0) {
						echo "<a href=\"index2.php?page=contacts_edit&amp;contact_id=$contact_id&amp;status=edit\" style=\"float: right;\"><img src=\"images/button_edit.png\" alt=\"Edit Contact\" /></a>";
					}
					
					if ($duplicate && $user_usertype_current > 0) {
						echo "<a href=\"index2.php?page=contacts_merge&amp;contact_id=$contact_id\" style=\"float: right;\"><img src=\"images/button_delete.png\" alt=\"Merge Contact\" />&nbsp;</a>";
					}
					
					
					echo "<p class=\"maxitext\"><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">$contact_namefirst $contact_namesecond</a></p>";
					
					if ($contact_title) { echo "<p>" . $contact_title . "</p>"; }
					
					
					
					if ($contact_mobile) { $contact_details_complete = $contact_details_complete . "M " . $contact_mobile . "|"; }
					
					if ($contact_telephone) { $contact_details_complete = $contact_details_complete . "T " . $contact_telephone . "|"; }
					elseif ($company_phone) { $contact_details_complete = $contact_details_complete . "T " . $company_phone . "|"; }
					
					if ($contact_email) { $contact_details_complete = $contact_details_complete . "|<a href=\"mailto: " . $contact_email . "\">" . $contact_email . "</a>|"; }
					
					$contact_details_complete = rtrim($contact_details_complete,"|");
					
					$contact_details_complete = str_replace("||","</p><p class=\"minitext\">",$contact_details_complete);
					$contact_details_complete = str_replace("|","<br />",$contact_details_complete);
					
					if ($contact_details_complete) { echo "<p class=\"minitext\">" . $contact_details_complete . "</p>"; }

					
					echo "</div>";


				}

		} else { echo "<div class=\"bodybox\"><p>No contacts found.</p></div>"; }
				
}

function AlphabetFilter($current_letter,$listorder,$desc_order) {

	global $conn;
	
	if ($desc_order) { $desc_order = "DESC"; } else { unset($desc_order); }
	
	$letter_array = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");

echo "<div class=\"menu_bar\">";

foreach ($letter_array AS $letter) {

	echo "<a class=\"submenu_bar\" href=\"index2.php?page=contacts_view&amp;startletter=$letter&desc_order=$desc_order\">" . strtoupper ( $letter ) . "</a>";

}


echo "</div>";


}

function ListDuplicates($contact_id) {

	global $conn;

		$sql = "SELECT * FROM contacts_contactlist LEFT JOIN contacts_companylist ON company_id = contact_company WHERE contact_id = $contact_id";

		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result) > 0) {
		
			echo "<h2>Merge Contacts</h2>";
			
			$array = mysql_fetch_array($result);

					$contact_namefirst = $array['contact_namefirst'];
					$contact_namesecond = $array['contact_namesecond'];
					$contact_company = $array['contact_company'];
					if (!$contact_company) { $contact_company = "IS NULL"; } else { $contact_company = "= " . intval($contact_company); }
					$contact_id = $array['contact_id'];
					$contact_email = $array['contact_email'];
					$contact_title = $array['contact_title'];
					$company_name = $array['company_name'];
					
					echo "<p>There are at least two contacts with the same name registered to the same company. These are likely to be duplicates. Please select one of the following to merge to the other.</p>";
					
					echo "<form method=\"post\" action=\"index2.php?page=contacts_company_view&amp;company_id=" . intval($array['contact_company']) . "\">";
					
					echo "<table>";
					
					echo "<tr><th style=\"width: 20px; text-align: center;\"><img src=\"images/button_delete.png\" alt=\"Contact to merge\" /></th><th style=\"width: 20px; text-align: center;\"><img src=\"images/button_list.png\" alt=\"Contact to merge to\" /></th><th>Name</th><th>Title</th><th>Company</th><th>Email</th></tr>";
					
					echo "<tr><td style=\"width: 20px; text-align: center;\"><input type=\"radio\" checked=\"checked\" value=\"$contact_id\" name=\"contact_delete\" /></td><td style=\"width: 20px; text-align: center;\"><input type=\"radio\" value=\"$contact_id\" name=\"contact_mergeto\" /></td><td>$contact_namefirst $contact_namesecond</td><td>$contact_title</td><td>$company_name</td><td>$contact_email</td></tr>";
					
					$sql_find = "SELECT * FROM contacts_contactlist LEFT JOIN contacts_companylist ON company_id = contact_company WHERE contact_namefirst = '$contact_namefirst' AND contact_namesecond = '$contact_namesecond' AND contact_id != $contact_id AND contact_company $contact_company";
					$result_find = mysql_query($sql_find, $conn) or die(mysql_error());
					
					while ($array_find = mysql_fetch_array($result_find)) {
					
						$contact_id_find = $array_find['contact_id'];
						$contact_namefirst_find = $array_find['contact_namefirst'];
						$contact_namesecond_find = $array_find['contact_namesecond'];
						$contact_email_find = $array_find['contact_email'];
						$contact_title_find = $array_find['contact_title'];
						$company_name_find = $array_find['company_name'];
						
						echo "<tr><td style=\"width: 25px; text-align: center;\"><input type=\"radio\" value=\"$contact_id_find\" name=\"contact_delete\" /></td><td style=\"width: 20px; text-align: center;\"><input type=\"radio\" value=\"$contact_id_find\" name=\"contact_mergeto\" /></td><td>$contact_namefirst_find $contact_namesecond_find</td><td>$contact_title_find</td><td>$company_name_find</td><td>$contact_email_find</td></tr>";
					
					}
					
					echo "</table><input type=\"submit\" /><input type=\"hidden\" name=\"action\" value=\"contact_merge\" /></form>";
		}
		
}

function GetCompanyFromContact($contact_id) {



	global $conn;
	if (intval($contact_id) > 0) {
		$sql = "SELECT contact_company FROM contacts_contactlist WHERE contact_id = $contact_id LIMIT 1";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		$company_id = $array['contact_company'];

		
		return $company_id;
		
		
	}
	
}

function ToggleTargetList() {

	echo "


			<script type=\"text/javascript\">
			
			
				$(document).ready(function(){
					$(\".clickbox\").click(function(){
					
					 var Name = $(this).val();
					 var LoadPage = \"ajax.php?action=toggle_target&target_contact=\" + Name;
					 var ReplaceNote = \"company_\" + Name;
					 var TargetDiv = \"target_\" + Name;
					 
						$(\"#TargetList\").load(LoadPage);
						$(\"#\" + TargetDiv).toggleClass(\"bodybox bodybox2\");
			
					 });
				});
				</script>




	";


}

function ContactsDropdownSelect($contact_id_select,$fieldname) {
	
	global $conn;
	
	$sql = "SELECT contact_id, contact_namefirst, contact_namesecond, contact_company FROM contacts_contactlist ORDER BY contact_namesecond";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	echo "<select class=\"inputbox\" name=\"$fieldname\">";

	echo "<option value=\"\">-- None --</option>";

	while ($array = mysql_fetch_array($result)) {

		$contact_id = $array['contact_id'];
		$contact_namefirst = $array['contact_namefirst'];
		$contact_namesecond = $array['contact_namesecond'];
		$contact_company = $array['contact_company'];

		if ($contact_company > 0) {
            $sql2 = "SELECT company_name, company_postcode FROM contacts_companylist WHERE company_id = $contact_company LIMIT 1";
            $result2 = mysql_query($sql2, $conn) or die(mysql_error());
            $array2 = mysql_fetch_array($result2);
            $company_name = $array2['company_name'];
            $company_postcode = $array2['company_postcode'];
            
            if ($company_postcode != NULL) {
              $print_company_details = " [".$company_name.", ".$company_postcode."]";
              } else {
              $print_company_details = " [".$company_name."]";
              }

            

            } else {

            unset($print_company_details);

            }

            echo "<option value=\"$contact_id\"";
            if ($contact_id == $contact_id_select) { echo " selected"; }
            echo ">".$contact_namesecond.", ".$contact_namefirst.$print_company_details."</option>";



	}

	echo "</select>";
	
	
}

function ContactDrawingList($contact_id) {
	
	global $conn;
	
	if (intval($contact_id) > 0) {
		
		$contact_id = intval($contact_id);
		
		$sql_drawing = "SELECT * FROM intranet_drawings_issued_set, intranet_projects, intranet_drawings_issued LEFT JOIN contacts_companylist ON company_id = issue_company WHERE proj_id = set_project AND issue_contact = $contact_id AND issue_set = set_id ORDER BY set_date DESC";
		
		$current_set = 0;
		
		$result_drawing = mysql_query($sql_drawing, $conn);
		if (mysql_num_rows($result_drawing) > 0) {
				echo "<fieldset><legend>Drawing Issue</legend>";
				echo "<table>";
				
				echo "<tr><th>Date</th><th>Project</th><th>Company</th><th>Reason for Issue</th></tr>";
				
					while ($array_drawing = mysql_fetch_array($result_drawing)) {
					$set_id = $array_drawing['set_id'];
					$set_date = $array_drawing['set_date'];
					$set_reason = $array_drawing['set_reason'];
					$proj_id = $array_drawing['proj_id'];
					$proj_num = $array_drawing['proj_num'];
					$proj_name = $array_drawing['proj_name'];
					$company_id = $array_drawing['company_id'];
					$company_name = $array_drawing['company_name'];
				
					if ($set_id != $current_set) {
						
						echo "<tr><td><a href=\"index2.php?page=datebook_view_day&amp;time=$set_date\">" . TimeFormat($set_date) . "</a></td><td><a href=\"" . $pref_location . "/index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a></td><td><a href=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">$company_name</a></td><td><a href=\"index2.php?page=drawings_issue_list&set_id=$set_id&amp;proj_id=$proj_id\">$set_reason</a></td></tr>";
				
				}
				
					$current_set = $set_id;
				
				}
		
			}
	
	}
	
}

function ListUserTargets($user_id) {

global $conn;

			$sql = "SELECT * FROM intranet_contacts_targetlist LEFT JOIN contacts_contactlist ON contact_id = target_contact WHERE target_user = " . $user_id . " ORDER BY target_type, contact_namesecond, contact_namefirst";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			
			$current_target_type = NULL;
			
			if (mysql_num_rows($result) > 0) {
			
				
				while ($array = mysql_fetch_array($result)) {
					
					if ($array['target_type'] == "1past") { $target_type = "Past Clients"; }
					elseif ($array['target_type'] == "2current") { $target_type = "Current Clients"; }
					elseif ($array['target_type'] == "3future") { $target_type = "Future Clients"; }
					
					if ($current_target_type == NULL) { $listcomplete = $listcomplete . "<p class=\"minitext\"><span><strong>" . $target_type . "</strong>&nbsp;"; $current_target_type = $array['target_type']; }
					elseif ($current_target_type != $array['target_type']) { $listcomplete = rtrim($listcomplete,", "); $listcomplete = $listcomplete . "</p><p class=\"minitext\"><span><strong>" . $target_type . "</strong>&nbsp;"; $current_target_type = $array['target_type']; }
					
					$listcomplete = $listcomplete . "<a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=" . $array['contact_id'] .  "\">" . $array['contact_namefirst'] . " " . $array['contact_namesecond'] . "</a>, ";
				
				}
				
				$listcomplete = rtrim($listcomplete,", "); $listcomplete = $listcomplete . "</p><p class=\"minitext\"><a href=\"index2.php?page=contacts_targetlist\" class=\"minibutton\">List My Contacts</a></p>";
				
				echo $listcomplete;
				
			
			}

}

function ListUserTargetsColumns($user_id) {

global $conn;

			$sql = "SELECT * FROM intranet_contacts_targetlist LEFT JOIN contacts_contactlist ON contact_id = target_contact LEFT JOIN contacts_companylist ON company_id = contact_company LEFT JOIN intranet_projects_blog ON blog_contact = contact_id AND blog_contact = target_contact WHERE target_user = " . $user_id . " ORDER BY target_type, blog_date, contact_namesecond, contact_namefirst";
			$sql = "SELECT * FROM intranet_contacts_targetlist LEFT JOIN contacts_contactlist ON contact_id = target_contact LEFT JOIN contacts_companylist ON company_id = contact_company WHERE target_user = " . $user_id . " ORDER BY target_type, contact_namesecond, contact_namefirst";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			
			$current_target_type = NULL;
			
			if (mysql_num_rows($result) > 0) {
			
				
				while ($array = mysql_fetch_array($result)) {
					
					if ($array['target_type'] == "1past") { $target_type = "Past Clients"; }
					elseif ($array['target_type'] == "2current") { $target_type = "Current Clients"; }
					elseif ($array['target_type'] == "3future") { $target_type = "Future Clients"; }
					
					if ($current_target_type == NULL) { $listcomplete = $listcomplete . "<div class=\"bodybox\" style=\"width: 30%; height: auto; min-height: 500px;\"><h2>" . $target_type . "</h2>"; $current_target_type = $array['target_type']; }
					elseif ($current_target_type != $array['target_type']) { $listcomplete = $listcomplete . "</div><div class=\"bodybox\" style=\"width: 30%; height: auto; min-height: 500px;\"><h2>" . $target_type . "</h2>"; $current_target_type = $array['target_type']; }
					
					$listcomplete = $listcomplete . "<p><strong><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=" . $array['contact_id'] .  "\">" . $array['contact_namefirst'] . " " . $array['contact_namesecond'] . "</strong></a>";
					
					if ($array['company_name']) { $listcomplete = $listcomplete . ", " . $array['company_name'] ; }
					
					$listcomplete = $listcomplete . "</p>";
				
				}
				
				$listcomplete = $listcomplete . "</div>";
				
				echo $listcomplete;
				
			
			}

}

function TargetType($contact_id) {
	
	global $conn;
	
	$contact_id = intval($contact_id);
	
	$sql = "SELECT contact_proj_contact, (UNIX_TIMESTAMP(ts_fee_commence) + ts_fee_time_end), ts_fee_commence, ts_fee_time_end, ts_fee_time_end FROM intranet_timesheet_fees, intranet_contacts_project WHERE ts_fee_project = contact_proj_project AND contact_proj_contact = $contact_id AND ts_fee_prospect = 100 ORDER BY (UNIX_TIMESTAMP(ts_fee_commence) + ts_fee_time_end) DESC LIMIT 1";
	
			$result = mysql_query($sql, $conn) or die(mysql_error());
			$array = mysql_fetch_array($result);
			$ts_fee_end = intval ( $array['(UNIX_TIMESTAMP(ts_fee_commence) + ts_fee_time_end)'] );
			$ts_fee_end = intval ( $array['(UNIX_TIMESTAMP(ts_fee_commence) + ts_fee_time_end)'] );
			
			if ($ts_fee_end > time()) { return "2current"; }
			elseif ($ts_fee_end < time() && $ts_fee_end > 0) { return "1past"; }
			else {return "3future"; }
			
}

function ToggleTarget($target_contact) {

	global $conn;
	
			$sql = "SELECT target_id FROM intranet_contacts_targetlist WHERE target_contact = $target_contact AND target_user = " . $_COOKIE[user] . " LIMIT 1";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			$array = mysql_fetch_array($result);
			$target_id = $array['target_id'];
			
			$target_type = TargetType($target_contact);
			
			if ($target_id > 0) {
			
				$sql_update = "DELETE FROM intranet_contacts_targetlist WHERE target_id = $target_id LIMIT 1";
			
			} else {
				
				$target_date = CreateDateFromTimestamp(time());
				$sql_update = "INSERT INTO intranet_contacts_targetlist (target_id, target_contact, target_user, target_date, target_type) VALUES (NULL, $target_contact, $_COOKIE[user], '$target_date', '$target_type')";
			
			}
			
			$result = mysql_query($sql_update, $conn) or die(mysql_error());
			
			ListUserTargets($_COOKIE[user]);
}


function DeleteContact($contact_id_old,$contact_id_new) {

	global $conn;

		$contact_id_old = intval ( $contact_id_old ) ;
		$company_id_old = GetCompanyFromContact($contact_id_old);

		$contact_id_new = intval ( $contact_id_new );
		$company_id_new = GetCompanyFromContact($contact_id_new);

			if (($contact_id_old > 0) && ($contact_id_new > 0) && ($contact_id_new != $contact_id_old)) {

					
					
					if ($company_id_new && $company_id_old) {
						
						$sql[] = "UPDATE intranet_contacts_project SET contact_proj_contact = $contact_id_new, contact_proj_company = $company_id_new WHERE contact_proj_contact = $contact_id_old AND contact_proj_company = $company_id_old";
						
						$sql[] = "UPDATE intranet_drawings_issued SET issue_contact = $contact_id_new, issue_company = $company_id_new WHERE issue_contact = $contact_id_old AND issue_company = $company_id_old";
						
					} elseif (!$company_id_new && !$company_id_old) {
					
						$sql[] = "UPDATE intranet_contacts_project SET contact_proj_contact = $contact_id_new WHERE contact_proj_contact = $contact_id_old";
						
						$sql[] = "UPDATE intranet_drawings_issued SET issue_contact = $contact_id_new WHERE issue_contact = $contact_id_old";
						
					}
					
					$sql[] = "UPDATE intranet_projects SET proj_client_contact_id = $contact_id_new WHERE proj_client_contact_id = $contact_id_old";
					
					$sql[] = "UPDATE intranet_phonemessage SET message_from_id = $contact_id_new WHERE message_from_id = $contact_id_old";
					
					$sql[] = "UPDATE intranet_projects_blog SET blog_contact = $contact_id_new WHERE blog_contact = $contact_id_old";

					
					$sql[] = "DELETE FROM contacts_contactlist WHERE contact_id = $contact_id_old LIMIT 1";

					$affected_rows = 0;
					
					foreach ($sql AS $sql_execute) {
					
						$result = mysql_query($sql_execute, $conn) or die(mysql_error());
						
						$techmessage = $techmessage . "<br />" . $sql_execute;
					
						$affected_rows = $affected_rows + mysql_affected_rows($result);
					}
					
					$affected_rows = $affected_rows . " entries updated.";
					
					$output = array($techmessage,$affected_rows);
					
					return $output;
					
			}

}
