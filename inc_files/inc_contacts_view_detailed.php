<?php

	if ($_GET[contact_id] > 0) { $select_contact_id = $_GET[contact_id]; }
	elseif ($_POST[contact_id] > 0) {  $select_contact_id = $_POST[contact_id]; }
	elseif ($contact_id > 0) {  $select_contact_id = $contact_id; }
	else {
	header("Location:index2.php");
	}

	$sql_contact = "SELECT * FROM contacts_contactlist WHERE contact_id = '$select_contact_id' LIMIT 1";
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
	
	$contact_added_date = "Added ".date("jS M y",$contact_added);
	
	// Select Title
			$sql2 = "SELECT * FROM contacts_titlelist WHERE title_id = '$contact_title' LIMIT 1";
			$result2 = mysql_query($sql2, $conn) or die(mysql_error());
			$array2 = mysql_fetch_array($result2);
			$title_name = $array2['title_name'];

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
	
	print "<h1>Contact Details</h1>";
	
			print "<p class=\"menu_bar\">";
			print "<a href=\"index2.php?page=contacts_edit&amp;contact_id=".$contact_id."&amp;status=edit\" class=\"menu_tab\">Edit Contact</a>";
			if ($contact_company > 0) { print "<a href=\"index2.php?page=contacts_company_edit&amp;company_id=".$contact_company."&amp;status=edit\" class=\"menu_tab\">Edit Company</a>"; }
			print "<a href=\"vcard.php?contact_id=$contact_id\" class=\"menu_tab\">VCard</a></p>";
			
			$contact_name = $contact_namefirst." ".$contact_namesecond;
			if ($contact_title != '0') { $contact_name = $contact_name . ", $contact_title"; }
			if ($contact_department != NULL) { $contact_name = $contact_name." (".$contact_department.")"; }
			
			$label_address = urlencode($contact_name)."|".urlencode($contact_address)."|".urlencode($contact_city)."|".urlencode($contact_county)."|".urlencode($contact_postcode)."|".urlencode($contact_country);
	print "<fieldset><legend>".$contact_name;

	
	if ($contact_address != NULL) { echo "&nbsp;<a href=\"http://labelstudio.redcitrus.com/?address=$label_address\"><img src=\"images/button_pdf.png\" alt=\"Address Labels\" /></a>"; }
	echo "</legend>";
	
		  // Begin setting out the table
		  print "<table>"; 	
		  
		  // Email address
		  print "<tr><td style=\"width: 20px;\" class=\"color\">E</td><td class=\"color\">";
		  if ($contact_email != NULL) { print "<a href=\"mailto:$contact_email\">$contact_email</a>"; } else { print "--"; }
		  print "</td>";
		  
		  print "<td rowspan=\"4\" style=\"width: 20px;\">A</td>";
	
		  print "<td rowspan=\"4\" style=\"width: 55%;\" class=\"color\">";

			$checkaddress = 0;
          	if ($contact_postcode != NULL) { $postcode = PostcodeFinder($contact_postcode); $checkaddress = 1; }
		  	if ($contact_address != NULL) { print nl2br($contact_address); $checkaddress = 1;  }
			if ($contact_city != NULL) { print "<br />".$contact_city; $checkaddress = 1;  }
			if ($contact_county != NULL) { print "<br />".$contact_county; $checkaddress = 1;  }
			if ($contact_postcode != NULL) { print "<br /><a href=\"$postcode\">".$contact_postcode."</a>"; $checkaddress = 1;  }
			
			if ($checkaddress == 0) { echo "--"; } else { $checkaddress = 0; }
	
			print "</td></tr>";
			
			// Print the Phone Number
			print "<tr><td class=\"color\">T</td><td class=\"color\">";
			if ($contact_telephone != NULL) { print $contact_telephone."&nbsp; [direct]"; } else if ($contact_telephone_home != NULL) { print $contact_telephone_home."&nbsp; [home]"; } else { print "--"; }
			print "</td></tr>";

			print "<tr><td class=\"color\">F</td><td class=\"color\">";
			if ($contact_fax != NULL) { print $contact_fax; } else { print "--"; }
			print "</td></tr>";

			print "<tr><td class=\"color\">M</td><td class=\"color\">";
			if ($contact_mobile != NULL) { print $contact_mobile; } else { print "--"; }
			print "</td></tr>";
			
			if ($contact_include > 0) { $marketing = ".&nbsp;This person is listed as a marketing contact."; } else { unset($marketing); }
			
			print "<tr><td colspan=\"4\">Contact added: <a href=\"index2.php?page=datebook_view_day&amp;time=$contact_added\">".date("j M y", $contact_added)."</a>$marketing</td></tr>";
		
			print "</table>";
			echo "</fieldset>";
	
	
// Company details if available	
	
if ($contact_company > 0) {


	$sql_company = "SELECT * FROM contacts_companylist WHERE company_id = '$contact_company' LIMIT 1";
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
	print "<fieldset><legend><a href=\"index2.php?page=contacts_company_view&amp;company_id=$contact_company\">$company_name</a>";
	
	if ($company_address != NULL) { echo "&nbsp;<a href=\"http://labelstudio.redcitrus.com/?address=$label_address\"><img src=\"images/button_pdf.png\" alt=\"Address Labels\" /></a>"; }
	echo "</legend>";
	
		  // Begin setting out the table
		  print "<table width=\"100%\" cellpadding=\"2\">"; 	
		  
		  // Email address
		  print "<tr><td style=\"width: 20px;\" class=\"color\">W</td><td class=\"color\">";
		  if ($company_web != NULL) { print "<a href=\"http://$company_web\">$company_web</a>"; } else { print "--"; }
		  print "</td>";
		  
		  print "<td rowspan=\"3\" style=\"width: 20px;\">A</td>";
	
		  print "<td rowspan=\"3\" style=\"width: 55%;\" class=\"color\">";
		  
		  $print_address = NULL;
		  
          	if ($company_postcode != NULL) { $postcode = PostcodeFinder($company_postcode); }
		  	if ($company_address != NULL) { $print_address = nl2br($company_address); }
			if ($company_city != NULL) { $print_address = $print_address . "<br />".$company_city; }
			if ($company_county != NULL) { $print_address = $print_address . "<br />".$company_county; }
			echo $print_address;
			if ($company_postcode != NULL) { print "<br /><a href=\"$postcode\">".$company_postcode."</a>"; }
	
			print "</td></tr>";
			
			// Print the Phone Number
			print "<tr><td class=\"color\">T</td><td class=\"color\">";
			if ($company_phone != NULL) { print $company_phone; } else { print "--"; }
			print "</td></tr>";

			print "<tr><td class=\"color\">F</td><td class=\"color\">";
			if ($company_fax != NULL) { print $company_fax; } else { print "--"; }
			print "</td></tr>";
		
			print "</table>";
			
			echo "</fieldset>";
	
	}
	
	// List other projects for this contact
	
	$sql_proj = "SELECT * FROM intranet_contacts_project, intranet_projects WHERE contact_proj_project = proj_id AND contact_proj_contact =  '$contact_id' ORDER BY proj_num";
	$result_proj = mysql_query($sql_proj, $conn);
	
	if (mysql_num_rows($result_proj) > 0) {
	
	echo "<fieldset><legend>Projects</legend>";
	
	echo "<table>";
	while ($array_proj = mysql_fetch_array($result_proj)) {	
	$proj_id = $array_proj['proj_id'];
	$proj_num = $array_proj['proj_num'];
	$proj_name = $array_proj['proj_name'];
	echo "<tr><td style=\"width: 15%;\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a></td><td>$proj_name</td>";
	}
	echo "</table>";
	}
	
	echo "</fieldset>";

	// echo "<fieldset><legend>Quick Copy</legend>";
	// echo "<textarea rows=\"8\" cols=\"48\">";
	// echo $contact_name;
	// echo "</textarea>";
	
	
	// List others from the same company
	
	$sql_company_members = "SELECT * FROM contacts_contactlist, contacts_companylist WHERE contact_company = '$contact_company' AND contact_company = company_id ORDER BY contact_namesecond";
	$result_company_members = mysql_query($sql_company_members, $conn) or die(mysql_error());
	
	if (mysql_num_rows($result_company_members) > 1 AND $contact_company > 0) {
	
	$contact_other_id_exclude = $contact_id;
	
	echo "<fieldset><legend>Related Contacts</legend>";
	
	echo "<table>";
	echo "<tr><td style=\"width: 50%;\">Name</td><td>Email Address</td><td>Postcode</td></tr>";
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
	}
	
	echo "</fieldset>";
	
	
	
	// Client for any projects?
	
	
	$sql_client = "SELECT proj_num, proj_name, proj_id FROM intranet_projects WHERE proj_client_contact_id = '$contact_id'";
	$result_client = mysql_query($sql_client, $conn);
	if (mysql_num_rows($result_client) > 0) {
		echo "<fieldset><legend>Client for projects</legend>";
		echo "<table>";
		while ($array_client = mysql_fetch_array($result_client)) {
		$proj_id = $array_client['proj_id'];
		$proj_num = $array_client['proj_num'];
		$proj_name = $array_client['proj_name'];
		echo "<tr><td style=\"width: 15%;\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a></td><td>$proj_name</td></tr>";
		}
		echo "</table></fieldset>";
	}
	
	// Add notes if any
	
	if ($contact_reference != NULL) { echo "<fieldset><legend>Notes</legend><blockquote>".PresentText($contact_reference)."</blockquote></fieldset>"; }
	
	// Any file notes or phone records which relate to this client?
	
	
	$sql_blog = "SELECT blog_id, blog_date, blog_title FROM intranet_projects_blog WHERE blog_contact = '$contact_id' ORDER BY blog_date";
	$result_blog = mysql_query($sql_blog, $conn);
	if (mysql_num_rows($result_blog) > 0) {
		echo "<fieldset><legend>Journal Entries</legend>";
		echo "<table>";
		while ($array_blog = mysql_fetch_array($result_blog)) {
		$blog_id = $array_blog['blog_id'];
		$blog_date = $array_blog['blog_date'];
		$blog_title = $array_blog['blog_title'];
		echo "<tr><td style=\"width: 25%;\"><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date\">".TimeFormat($blog_date)."</a></td><td><a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id\">$blog_title</a></td></tr>";
		}
		echo "</table></fieldset>";
	}
	
	echo "<fieldset><legend>Quick Postal Address</legend>";
	
	echo "<textarea id=\"address\" onClick=\"SelectAll('address')\" style=\"width: 50%; height: 100px;\">";
	
	if ($contact_namefirst && $contact_namesecond) { echo $contact_namefirst . " " . $contact_namesecond . "\n"; }
	if ($company_name) { echo $company_name . "\n"; }	
	echo str_replace ("<br />" , "\n" , $print_address) . "\n";
	if ($company_postcode) { echo $company_postcode; } elseif ($contact_postcode) { echo $contact_postcode; }
	
	echo "</textarea>";
	
	
	echo "</fielddset>";
	
	// List any drawing issues to this contact
	
	$sql_drawing = "SELECT * FROM intranet_drawings_issued, intranet_drawings_issued_set LEFT JOIN intranet_projects ON proj_id = set_project WHERE issue_contact = $contact_id AND issue_set = set_id ORDER BY set_date DESC";
	
	$current_set = 0;
	
	$result_drawing = mysql_query($sql_drawing, $conn);
	if (mysql_num_rows($result_drawing) > 0) {
		echo "<fieldset><legend>Drawing Issue</legend>";
		echo "<table>";
		while ($array_drawing = mysql_fetch_array($result_drawing)) {
		$set_id = $array_drawing['set_id'];
		$set_date = $array_drawing['set_date'];
		$set_reason = $array_drawing['set_reason'];
		$proj_num = $array_drawing['proj_num'];
		$proj_name = $array_drawing['proj_name'];
		
		if ($set_id != $current_set) {
			
			echo "<tr><td><a href=\"index2.php?page=datebook_view_day&amp;time=$set_date\">" . TimeFormat($set_date) . "</a></td><td><a href=\"http://intranet.rcka.co.uk/index2.php?page=project_view&amp;proj_id=$proj_num\">$proj_num $proj_name</a></td><td><a href=\"index2.php?page=drawings_issue_list&set_id=$set_id&amp;proj_id=$proj_id\">$set_reason</a></td></tr>";
		
		}
		
		$current_set = $set_id;
		
		}
		echo "</table></fieldset>";
	}
	
	
} else {
	
	header("Location:index2.php");
	
	}

?>
