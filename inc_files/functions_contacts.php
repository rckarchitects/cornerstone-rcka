<?php

function ContactViewDetailed($select_contact_id) {
		
		global $conn;
		global $$user_usertype_current;
		
			$select_contact_id = intval($select_contact_id);

						$sql_contact = "SELECT * FROM contacts_contactlist LEFT JOIN intranet_user_details ON user_id = contact_added_by WHERE contact_id = " . intval ( $select_contact_id ) . " LIMIT 1";
						$result_contact = mysql_query($sql_contact, $conn);
						$array_contact = mysql_fetch_array($result_contact);
						
						if (mysql_num_rows($result_contact) > 0) {
						
						$contact_id = $array_contact['contact_id'];
						$contact_namefirst = $array_contact['contact_namefirst'];
						$contact_namesecond = $array_contact['contact_namesecond'];
						$contact_company = $array_contact['contact_company'];
						$contact_title = $array_contact['contact_title'];
						$contact_telephone = $array_contact['contact_telephone'];
						$contact_telephone_home = $array_contact['contact_telephone_home'];
						$contact_fax = $array_contact['contact_fax'];
						$contact_email = $array_contact['contact_email'];
						$contact_sector = $array_contact['contact_sector'];
						$contact_reference = $array_contact['contact_reference'];
						$contact_department = $array_contact['contact_department'];
						$contact_added = $array_contact['contact_added'];
						$contact_relation = $array_contact['contact_relation'];
						$contact_mobile = $array_contact['contact_mobile'];
						$contact_address = $array_contact['contact_address'];
						$contact_city = $array_contact['contact_city'];
						$contact_county = $array_contact['contact_county'];
						$contact_postcode = $array_contact['contact_postcode'];
						$contact_phone = $array_contact['contact_telephone'];
						$contact_fax = $array_contact['contact_fax'];
						$contact_include = $array_contact['contact_include'];
						$contact_linkedin = $array_contact['contact_linkedin'];
						
						$user_name_first = $array_contact['user_name_first'];
						$user_name_second = $array_contact['user_name_second'];
						
						$contact_added_date = "Added ".date("jS M y",$contact_added);
						
						// Select Sector
								$sql3 = "SELECT * FROM contacts_sectorlist WHERE sector_id = '$contact_sector' LIMIT 1";
								$result3 = mysql_query($sql3, $conn) or die(mysql_error());
								$array3 = mysql_fetch_array($result3);
								$sector_name = $array3['sector_name'];

						// Select Relationship
								$sql4 = "SELECT * FROM contacts_relationlist WHERE relation_id = '$contact_relation' LIMIT 1";
								$result4 = mysql_query($sql4, $conn) or die(mysql_error());
								$array4 = mysql_fetch_array($result4);
								$relation_color = $array4['relation_color'];

						// Select Prefix
								$sql5 = "SELECT * FROM contacts_prefixlist WHERE prefix_id = '$contact_prefix' LIMIT 1";
								$result5 = mysql_query($sql5, $conn) or die(mysql_error());
								$array5 = mysql_fetch_array($result5);
								$prefix_name = $array5['prefix_name'];
								
								if ($contact_prefix > 0) { $prefix_name = "(".$prefix_name.")"; }
						
						echo "<h2>$contact_namefirst $contact_namesecond</h2>";
						
						ProjectSubMenu(NULL,$user_usertype_current,"contacts_admin",1);
						ProjectSubMenu(NULL,$user_usertype_current,"contacts_admin",2);
						

								
								$contact_name = $contact_namefirst." ".$contact_namesecond;
								if ($contact_title) { $contact_name = $contact_name . ", $contact_title"; }
								if ($contact_department != NULL) { $contact_name = $contact_name." (".$contact_department.")"; }
								
								$label_address = urlencode($contact_name)."|".urlencode($contact_address)."|".urlencode($contact_city)."|".urlencode($contact_county)."|".urlencode($contact_postcode)."|".urlencode($contact_country);
						echo "<div><h3>".$contact_name;

						
						if ($contact_address != NULL) { echo "&nbsp;<a href=\"http://labelstudio.redcitrus.com/?address=$label_address\"><img src=\"images/button_pdf.png\" alt=\"Address Labels\" /></a>"; }
						echo "</h3>";
						
							  // Begin setting out the table
							  echo "<table>"; 	
							  
							  // Email address
							  echo "<tr><td style=\"width: 20px;\" class=\"color\">E</td><td class=\"color\">";
							  if ($contact_email != NULL) { echo "<a href=\"mailto:$contact_email\">$contact_email</a>"; } else { echo "--"; }
							  echo "</td>";
							  
							  echo "<td rowspan=\"4\" style=\"width: 20px;\">A</td>";
						
							  echo "<td rowspan=\"4\" style=\"width: 55%;\" class=\"color\">";

								$checkaddress = 0;
								if ($contact_postcode != NULL) { $postcode = PostcodeFinder($contact_postcode); $checkaddress = 1; }
								if ($contact_address != NULL) { echo nl2br($contact_address); $checkaddress = 1;  }
								if ($contact_city != NULL) { echo "<br />".$contact_city; $checkaddress = 1;  }
								if ($contact_county != NULL) { echo "<br />".$contact_county; $checkaddress = 1;  }
								if ($contact_postcode != NULL) { echo "<br /><a href=\"$postcode\">".$contact_postcode."</a>"; $checkaddress = 1;  }
								
								if ($checkaddress == 0) { echo "--"; } else { $checkaddress = 0; }
						
								echo "</td></tr>";
								
								// Print the Phone Number
								echo "<tr><td class=\"color\">T</td><td class=\"color\">";
								if ($contact_telephone != NULL) { echo $contact_telephone."&nbsp; [direct]"; } else if ($contact_telephone_home != NULL) { echo $contact_telephone_home."&nbsp; [home]"; } else { echo "--"; }
								echo "</td></tr>";

								echo "<tr><td class=\"color\">F</td><td class=\"color\">";
								if ($contact_fax != NULL) { echo $contact_fax; } else { echo "--"; }
								echo "</td></tr>";

								echo "<tr><td class=\"color\">M</td><td class=\"color\">";
								if ($contact_mobile != NULL) { echo $contact_mobile; } else { echo "--"; }
								echo "</td></tr>";
								
								if ($contact_linkedin) {
									echo "<tr><td>L</td><td colspan=\"3\"><a href=\"$contact_linkedin\">$contact_linkedin</a></td></tr>";				
								}
								
								if ($contact_include == 1) { $marketing = ".&nbsp;This person receives both emails and hard copy marketing.";
								
								} elseif ($contact_include == 2) { $marketing = ".&nbsp;This person receives only email marketing.";
								
								} elseif ($contact_include == 3) { $marketing = ".&nbsp;This person receives only hard copy marketing.";
									
								} else { unset($marketing); }
								
								echo "<tr><td colspan=\"4\"><span class=\"minitext\">Contact added: <a href=\"index2.php?page=datebook_view_day&amp;time=$contact_added\">".date("j M Y", $contact_added)."</a> by $user_name_first $user_name_second $marketing</span></td></tr>";
							
								echo "</table>";
								echo "</div>";
								
								if ($contact_company) { return $contact_company; }
								
								
						}
			
}

function ContactProjects($contact_id) {
	
	global $conn;
	
	$contact_id = intval($contact_id);
	
	// List other projects for this contact
	
	$sql_proj = "SELECT * FROM intranet_contacts_project, intranet_projects WHERE contact_proj_project = proj_id AND contact_proj_contact = $contact_id ORDER BY proj_num";
	$result_proj = mysql_query($sql_proj, $conn);
	
	if (mysql_num_rows($result_proj) > 0) {
	
	echo "<div><h3>Projects</h3>";
	
		echo "<table>";
		while ($array_proj = mysql_fetch_array($result_proj)) {	
			$proj_id = $array_proj['proj_id'];
			$proj_num = $array_proj['proj_num'];
			$proj_name = $array_proj['proj_name'];
			echo "<tr><td style=\"width: 15%;\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a></td><td>$proj_name</td>";
		}
		echo "</table>";
	
	echo "</div>";
	}
	
	

}


function ContactOnePage($contact_id) {
	
	global $conn;
	
	$contact_id = intval($contact_id);
	$url = "http://app.onepagecrm.com/add_new_contact?";
	
	$sql = "SELECT * FROM contacts_contactlist LEFT JOIN contacts_companylist ON company_id = contact_company WHERE contact_id = $contact_id LIMIT 1";
	$result = mysql_query($sql, $conn);
	
	
	//$url = "firstname=Johny&lastname=Bravo&company=Cartoon%20Network&tags[]=beefcake";
	
	if (mysql_num_rows($result) > 0) {
		
		$array = mysql_fetch_array($result);
		
		$url = $url . "&amp;";
	
		if ($array['contact_namefirst']) { $url = $url . "firstname=" . urlencode($array['contact_namefirst']) . "&amp;"; }
		if ($array['contact_namesecond']) { $url = $url . "lastname=" . urlencode($array['contact_namesecond']) . "&amp;"; }
		if ($array['contact_email']) { $url = $url . "email=" . urlencode($array['contact_email']) . "&amp;"; }
		if ($array['company_name']) { $url = $url . "company=" . urlencode($array['company_name']) . "&amp;"; }
		if ($array['company_web']) { $url = $url . "web=" . urlencode($array['company_web']) . "&amp;"; }
		if ($array['contact_telephone']) { $url = $url . "phone=" . urlencode($array['contact_telephone']) . "&amp;"; }
		elseif ($array['company_phone']) { $url = $url . "phone=" . urlencode($array['company_phone']) . "&amp;"; }
		if ($array['contact_telephone']) { $url = $url . "phone=" . urlencode($array['contact_telephone']) . "&amp;"; }
		if ($array['company_web']) { $url = $url . "web=" . urlencode($array['company_web']) . "&amp;"; }
		if ($array['contact_title']) { $url = $url . "job_title=" . urlencode($array['contact_title']) . "&amp;"; }
		return $url;
	
	}
	
	
}

function ContactClient($contact_id) {
	
	global $conn;
	
	$contact_id = intval($contact_id);
	
	$sql_client = "SELECT proj_num, proj_name, proj_id FROM intranet_projects WHERE proj_client_contact_id = $contact_id";
	$result_client = mysql_query($sql_client, $conn);
	if (mysql_num_rows($result_client) > 0) {
		echo "<div><h3>Client for projects</h3>";
		echo "<table>";
		while ($array_client = mysql_fetch_array($result_client)) {
		$proj_id = $array_client['proj_id'];
		$proj_num = $array_client['proj_num'];
		$proj_name = $array_client['proj_name'];
		echo "<tr><td style=\"width: 15%;\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a></td><td>$proj_name</td></tr>";
		}
		echo "</table></div>";
	}
	
}

function ContactNotes($contact_id) {
	
	global $conn;
	
	$contact_id = intval($contact_id);
	
	if ($contact_reference != NULL) { echo "<div><h3>Notes</h3><blockquote>".PresentText($contact_reference)."</blockquote></div>"; }
	
	// Any file notes or phone records which relate to this client?
	
	$sql_blog = "SELECT blog_id, blog_date, blog_title FROM intranet_projects_blog WHERE blog_contact = '$contact_id' ORDER BY blog_date";
	$result_blog = mysql_query($sql_blog, $conn);
	if (mysql_num_rows($result_blog) > 0) {
		echo "<div><h3>Journal Entries</h3>";
		echo "<table>";
		while ($array_blog = mysql_fetch_array($result_blog)) {
		$blog_id = $array_blog['blog_id'];
		$blog_date = $array_blog['blog_date'];
		$blog_title = $array_blog['blog_title'];
		echo "<tr><td style=\"width: 25%;\"><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date\">".TimeFormat($blog_date)."</a></td><td><a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id\">$blog_title</a></td></tr>";
		}
		echo "</table></div>";
	}
	
}
	
function ContactPostalAddress($contact_id) {
	
	global $conn;
	
	$contact_id = intval($contact_id);
	
	echo "<div><h3>Quick Postal Address</h3>";
	
	echo "<textarea id=\"address\" onClick=\"SelectAll('address')\" style=\"width: 50%; height: 100px;\">";
	
	if ($contact_namefirst && $contact_namesecond) { echo $contact_namefirst . " " . $contact_namesecond; }
	if ($company_name) { echo "\n" . $company_name; }
	
	echo "\n";

	$print_address  = preg_replace( "/\r|\n/", "<br />", $print_address );

	$address_array = explode ("<br />",$print_address);
	
	foreach ($address_array AS $address_line) {
		if (strlen ( $address_line ) > 1 ) { echo $address_line . "\n"; }
	}
	
	if ($company_postcode) { echo $company_postcode; } elseif ($contact_postcode) { echo $contact_postcode; }
	
	echo "</textarea>";
	
	
	echo "</div>";
	
}

function CompanyViewDetailed($company_id) {
	
	global $conn;
	
	$company_id = intval($company_id);
	
	$sql_company = "SELECT * FROM contacts_companylist WHERE company_id = $company_id LIMIT 1";
	$result_company = mysql_query($sql_company, $conn);
	$array_company = mysql_fetch_array($result_company);
	
	$company_name = $array_company['company_name'];
	$company_web = $array_company['company_web'];
	$company_address = $array_company['company_address'];
	$company_city = $array_company['company_city'];
	$company_county = $array_company['company_county'];
	$company_postcode = $array_company['company_postcode'];
	$company_fax = $array_company['company_fax'];
	$company_phone = $array_company['company_phone'];
	$company_country = $array_company['company_country'];
	
	$sql_country = "SELECT * FROM intranet_contacts_countrylist WHERE country_id = '$company_country' LIMIT 1";
	$result_country = mysql_query($sql_country, $conn);
	$array_country = mysql_fetch_array($result_country);
	$country_name = $array_country['country_name'];

	
			$label_address = urlencode($contact_name)."|".urlencode($company_name)."|".urlencode($company_address)."|".urlencode($company_city)."|".urlencode($company_county)."|".urlencode($company_postcode)."|".urlencode($company_country);
	echo "<div><h3><a href=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">$company_name</a>";
	
	if ($company_address != NULL) { echo "&nbsp;<a href=\"http://labelstudio.redcitrus.com/?address=$label_address\"><img src=\"images/button_pdf.png\" alt=\"Address Labels\" /></a>"; }
	echo "</h3>";
	
		  // Begin setting out the table
		  echo "<table width=\"100%\" cellpadding=\"2\">"; 	
		  
		  // Email address
		  echo "<tr><td style=\"width: 20px;\" class=\"color\">W</td><td class=\"color\">";
		  if ($company_web != NULL) { echo "<a href=\"http://$company_web\">$company_web</a>"; } else { echo "--"; }
		  echo "</td>";
		  
		  echo "<td rowspan=\"3\" style=\"width: 20px;\">A</td>";
	
		  echo "<td rowspan=\"3\" style=\"width: 55%;\" class=\"color\">";
		  
		  $print_address = NULL;
		  
          	if ($company_postcode != NULL) { $postcode = PostcodeFinder($company_postcode); }
		  	if ($company_address != NULL) { $print_address = nl2br($company_address); }
			if ($company_city != NULL) { $print_address = $print_address . "<br />".$company_city; }
			if ($company_county != NULL) { $print_address = $print_address . "<br />".$company_county; }
			echo $print_address;
			if ($company_postcode != NULL) { echo "<br /><a href=\"$postcode\">".$company_postcode."</a>"; }
	
			echo "</td></tr>";
			
			// Print the Phone Number
			echo "<tr><td class=\"color\">T</td><td class=\"color\">";
			if ($company_phone != NULL) { echo $company_phone; } else { echo "--"; }
			echo "</td></tr>";

			echo "<tr><td class=\"color\">F</td><td class=\"color\">";
			if ($company_fax != NULL) { echo $company_fax; } else { echo "--"; }
			echo "</td></tr>";
		
			echo "</table>";
			
			echo "</div>";
	
	

	
}

function ContactRelatedContacts($contact_company) {
	
	global $conn;
	
	$contact_company = intval($contact_company);
	
		// List others from the same company
		
		$sql_company_members = "SELECT * FROM contacts_contactlist, contacts_companylist WHERE contact_company = $contact_company AND contact_company = company_id ORDER BY contact_namesecond";
		$result_company_members = mysql_query($sql_company_members, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result_company_members) > 1 AND $contact_company > 0) {
		
			$contact_other_id_exclude = $contact_id;
			
			echo "<div><h3>Related Contacts</h3>";
			
			echo "<table>";
			echo "<tr><th style=\"width: 50%;\">Name</th><th>Email Address</th><th>Postcode</th></tr>";
			while ($array_company_members = mysql_fetch_array($result_company_members)) {	
			$contact_other_id = $array_company_members['contact_id'];
			$contact_other_namefirst = $array_company_members['contact_namefirst'];
			$contact_other_namesecond = $array_company_members['contact_namesecond'];
			$contact_other_email = $array_company_members['contact_email'];
			$company_other_postcode = $array_company_members['contact_postcode'];
					if ($contact_other_id != $contact_other_id_exclude) {
					echo "<tr><td><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_other_id\">$contact_other_namefirst&nbsp;$contact_other_namesecond</a></td><td>";
					
					if ($contact_email != NULL) {
					echo "<a href=\"mailto:$contact_other_email\">$contact_other_email</a>";
					} else { echo "--"; }
					
					echo "</td><td>$company_other_postcode</td></tr>";
					
					}
			}
			echo "</table>";
			echo "</div>";
		}
		
		
	
}

function DisciplineNonProject($discipline_id) {
	
	
	global $conn;
	
	$discipline_id = intval($discipline_id);


		echo "<div><h3>Other Contacts</h3>";

		$sql_contact = "SELECT * FROM contacts_disciplinelist, contacts_contactlist LEFT JOIN contacts_companylist ON contacts_contactlist.contact_company = contacts_companylist.company_id WHERE contact_discipline = $discipline_id AND discipline_id = contact_discipline ORDER BY contact_namesecond, contact_namefirst";
		$result_contact = mysql_query($sql_contact, $conn) or die(mysql_error());

		$count = 0;

		echo "\n<table>";
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
				$discipline_id = $array_contact['discipline_id'];
				$discipline_name = $array_contact['discipline_name'];
				$contact_proj_note = $array_contact['contact_proj_note'];
				
			if (in_array($contact_id, $jobs_array) == FALSE) {
				
				$count++;
				
			print "\n<tr><td style=\"width: 30%;\"><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">";
			echo "$contact_namefirst $contact_namesecond";
			echo "</a></td><td>";
			if ($company_name != NULL) { echo "<a href=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">".$company_name."</a><br />"; }
			echo "</td><td>";
			if ($contact_email != NULL) { echo "Email: $contact_email&nbsp;<a href=\"mailto:$contact_email\"><img src=\"images/button_email.png\" alt=\"Email\"/></a><br />"; }
			if ($contact_telephone != NULL) { echo "T: $contact_telephone<br />"; } elseif ($company_phone != NULL) { echo "T: $company_phone<br />"; }
			if ($contact_mobile != NULL) { echo "M: $contact_mobile"; }
			echo "</td>";
			if (trim($contact_proj_note) != "") {
			echo "<td>$contact_proj_note</td>";
			}
			echo "\n</tr>";
			}
		}
		echo "</table>";

		if ($count == 0) { echo "<p>- None - </p>"; }

		echo "</div>";

}


function DisciplineProject($discipline_id) {
	
	global $conn;
	
	$discipline_id = intval($discipline_id);

				echo "<div><h3>Project Contacts</h3>";

				$sql_contact = "SELECT * FROM contacts_disciplinelist, intranet_projects, intranet_contacts_project, contacts_contactlist LEFT JOIN contacts_companylist ON contacts_contactlist.contact_company = contacts_companylist.company_id WHERE contact_proj_contact = contact_id  AND discipline_id = contact_proj_role AND discipline_id = $discipline_id AND contact_proj_project = proj_id ORDER BY contact_namesecond, contact_namefirst, proj_num";
				$result_contact = mysql_query($sql_contact, $conn) or die(mysql_error());

				if (mysql_num_rows($result_contact) > 0) {

				$current_id = NULL;

				echo "\n<table>";
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
						$discipline_id = $array_contact['discipline_id'];
						$discipline_name = $array_contact['discipline_name'];
						$proj_id = $array_contact['proj_id'];
						$proj_num = $array_contact['proj_num'];
						$proj_name = $array_contact['proj_name'];
						$contact_proj_note = $array_contact['contact_proj_note'];
						
						if ($current_id > 0 AND $contact_id != $current_id ) { echo "</td></tr>"; }
						
						if ($contact_id != $current_id) {
						
					print "\n<tr><td style=\"width: 30%;\" rowspan=\"2\"><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">";
					echo "$contact_namefirst $contact_namesecond";
					echo "</a></td><td>";
					if ($company_name != NULL) { echo "<a href=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">".$company_name."</a><br />"; } else { echo "--"; }
					echo "</td><td>";
					if ($contact_email != NULL) { echo "Email: $contact_email&nbsp;<a href=\"mailto:$contact_email\"><img src=\"images/button_email.png\" alt=\"Email\"/></a><br />"; }
					if ($contact_telephone != NULL) { echo "T: $contact_telephone<br />"; } elseif ($company_phone != NULL) { echo "T: $company_phone<br />"; }
					if ($contact_mobile != NULL) { echo "M: $contact_mobile"; }
					echo "</td>";
					echo "</tr>";
					echo "\n<tr><td colspan=\"2\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a>";
					
					$jobs_array[] = $contact_id;
					
					} else { echo ", <a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">".$proj_num." ".$proj_name."</a>"; }
				$current_id = $contact_id;
				}
				echo "</td></tr>";
				echo "</table>";

				} else { echo "<p>- None - </p>"; }

				echo "</div>";

}


function SelectCompany () {
	GLOBAL $conn;
	$sql = "SELECT DISTINCT company_name, company_id, company_postcode FROM contacts_companylist ORDER BY company_name, company_postcode";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
		echo "<option value=\"" . $array[company_id] . "\">" . $array[company_name];
		if ($array[company_postcode] != NULL) { echo " (" . $array[company_postcode] . ")"; }
		echo " - id: " . $array[company_id] . "</option>";
	}
}

function ContactsDisciplines() {
	
	global $conn;

		$sql_discipline = "SELECT * FROM contacts_disciplinelist ORDER BY discipline_name";

			$result_discipline = mysql_query($sql_discipline, $conn);
			

			
			if (mysql_num_rows($result_discipline) > 0) {
			
			echo "<table>";
			
			while ($array_discipline = mysql_fetch_array($result_discipline)) {
				$discipline_id = $array_discipline['discipline_id'];
				$discipline_name = $array_discipline['discipline_name'];
				echo "<tr><td><a href=\"index2.php?page=contacts_discipline_view&amp;discipline_id=$discipline_id\">$discipline_name</a></td></tr>";
			}
			
				echo "</table>";
			
			} else { echo "<p>-- None found --</p>"; }


}

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
				echo "<div><h3>Drawing Issues</h3>";
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
				
				echo "</table></div>";
			
		
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
					
					if ($current_target_type == NULL) { $listcomplete = $listcomplete . "<div class=\"bodybox\" style=\"width: 30%; height: auto; min-height: 500px;\"><h3>" . $target_type . "</h3>"; $current_target_type = $array['target_type']; }
					elseif ($current_target_type != $array['target_type']) { $listcomplete = $listcomplete . "</div><div class=\"bodybox\" style=\"width: 30%; height: auto; min-height: 500px;\"><h3>" . $target_type . "</h3>"; $current_target_type = $array['target_type']; }
					
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
					
					$actionmessage = "<p>Contact with ID $contact_id_new has been updated.</p>";
					
					AlertBoxInsert($_COOKIE[user],"Contact Merged",$actionmessage,$contact_id_new,0,0);
					
					return $output;
					
					
					
			}

}
