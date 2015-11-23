<?php


		if ($_GET[filterletter] != NULL) {
  			$sql = "SELECT * FROM contacts_contactlist, contacts_relationlist WHERE contact_relation = relation_id AND $listorder LIKE '$_GET[filterletter]%' AND $listorder > '0' AND $listorder != '' order by $listorder,contact_namesecond LIMIT $desc_order $listbegin,$listmax ";
		} else {
			$sql = "SELECT * FROM contacts_contactlist, contacts_relationlist WHERE contact_relation = relation_id AND $listorder != '' order by $listorder $desc_order LIMIT $listbegin,$listmax ";
		}
			$sql_num = "SELECT * FROM contacts_contactlist, contacts_companylist";

			$result = mysql_query($sql, $conn) or die(mysql_error());
			
			// Establish the number of rows returned above
			
			$sql_num = mysql_num_rows($result);

			$count = 1;
			$listcount = $listbegin+1;



			while ($array = mysql_fetch_array($result)) {
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
			$contact_mobile = $array['contact_mobile'];
			
			$relation_color = $array['relation_color'];
			
			if ($contact_company > 0) {
			
			$sql_company = "SELECT * FROM contacts_companylist WHERE company_id = $contact_company LIMIT 1";
			$result_company = mysql_query($sql_company, $conn) or die(mysql_error());
			$array_company  = mysql_fetch_array($result_company );
			
			$company_id = $array_company['company_id'];
			$company_name = $array_company['company_name'];
			$display_address = $array_company['company_address'];
			$display_city = $array_company['company_city'];
			$display_county = $array_company['company_county'];
			$display_postcode = $array_company['company_postcode'];
			$display_phone = $array_company['company_phone'];
			$display_fax = $array_company['company_fax'];
			$company_web = $array_company['company_web'];
			
			
			} else {
			
			unset($company_name);
			$display_address = $array['contact_address'];
			$display_city = $array['contact_city'];
			$display_county = $array['contact_county'];
			$display_postcode = $array['contact_postcode'];
			$display_phone = $array['contact_telephone'];
			$display_fax = $array['contact_fax'];
			unset($company_web);
			
			}
			
			// Insert direct number if this exists
			
			if ($contact_telephone != NULL AND $contact_company > 0 ) { $display_phone = $contact_telephone." [D]"; }
			if ($contact_fax != NULL AND $contact_company > 0 ) { $display_fax = $contact_fax." [D]"; }

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

  			print "<h2>";

			if ($_GET[listorder] == "contact_namefirst") {
				print "<a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">$prefix_name $contact_namefirst $contact_namesecond</a>&nbsp;<a href=\"vcard.php?contact_id=$contact_id\"><img src=\"images/button_vcf.png\" alt=\"Download VCard\" /></a></h2>";
			} else {
				print "<a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">$contact_namesecond, $contact_namefirst</a> $prefix_name&nbsp;<a href=\"vcard.php?contact_id=$contact_id\"><img src=\"images/button_vcf.png\" alt=\"Download VCard\" /></a></h2>";
			}
			
// Begin setting out the table
print "<table cellpadding=\"2\">"; 

// If there's a company name, add it here

if ($company_name != NULL) { print "<tr><td colspan=\"4\" style=\"background: #$relation_color;\"><a href=\"index2.php?page=contacts_company_view&amp;company_id=".$company_id."\">"; print $company_name; print "</a></td></tr>"; }
else { print "<tr><td colspan=\"4\" bgcolor=\"#$relation_color\">Private</td></tr>"; }

// Email address
print "<tr><td width=\"20\" class=\"color\">E</td><td class=\"color\">";
if ($contact_email != NULL) { print "<a href=\"mailto:$contact_email\">$contact_email</a>"; } else { print "--"; }
print "</td>";



print "<td rowspan=\"5\" width=\"55%\" class=\"color\">";

// Insert company details

	// Work out the streetmap location URL
										  
        if ($display_postcode != NULL) { $postcode = PostcodeFinder($display_postcode); }
		if ($display_address != NULL) { print nl2br($display_address); }
		if ($display_city != NULL) { print "<br />".$display_city; }
		if ($display_county != NULL) { print "<br />".$display_county; }
		if ($display_postcode != NULL) { print "<br /><a href=\"$postcode\">".$display_postcode."</a>"; }
		
print "</td></tr>";

// Print the Phone Number
print "<tr><td class=\"color\">T</td><td class=\"color\">";
if ($display_phone != NULL) { print $display_phone; } else { print "--"; }
print "</td></tr>";

print "<tr><td class=\"color\">F</td><td class=\"color\">";
if ($display_fax != NULL) { print $display_fax; } else { print "--"; }
print "</td></tr>";

print "<tr><td class=\"color\">M</td><td class=\"color\">";
if ($contact_mobile != NULL) { print $contact_mobile; } else { print "--"; }
print "</td></tr>";

print "<tr><td class=\"color\">W</td><td class=\"color\">";
if ($company_web != NULL) { print "<a href=\"http://$company_web\">$company_web</a><br />"; } else { print "--"; }
print "</td></tr>";

print "<tr><td colspan=\"4\" class=\"noborder\">";
print "<font class=\"minitext\">Added: ".date("j M y", $contact_added)." | <a href=\"index2.php?page=contacts_edit&amp;contact_id=".$contact_id."&amp;status=edit\">Edit Contact</a>";
if ($contact_company > 0) { print " | <a href=\"index2.php?page=contacts_company_edit&amp;company_id=".$company_id."&amp;status=edit\">Edit Company</a>"; }
print "</font></td></tr>";

print "</table>";

}

?>
