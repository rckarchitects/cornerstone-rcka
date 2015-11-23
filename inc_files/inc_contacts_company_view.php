<?php

if ($_GET[company_id] > 0) { $company_id = CleanNumber($_GET[company_id]); } elseif ($company_id_added > 0) {$company_id = $company_id_added; } else { $company_id = 0; }

if ($company_id == NULL) { header("location:index2.php"); }

$sql_company = "SELECT * FROM contacts_companylist WHERE company_id = '$company_id' LIMIT 1";
$result_company = mysql_query($sql_company, $conn) or die(mysql_error());
$array_company = mysql_fetch_array($result_company);

			$company_id = $array_company['company_id'];
			$company_name = $array_company['company_name'];
			$company_address = $array_company['company_address'];
			$company_city = $array_company['company_city'];
			$company_county = $array_company['company_county'];
			$company_country = $array_company['company_country'];
			$company_postcode = $array_company['company_postcode'];
			$company_phone = $array_company['company_phone'];
			$company_fax = $array_company['company_fax'];
			$company_web = $array_company['company_web'];
			$company_notes = $array_company['company_notes'];
			
			// Determine the country
			$sql_country = "SELECT country_printable_name FROM intranet_contacts_countrylist where country_id = '$company_country' LIMIT 1";
			$result_country = mysql_query($sql_country, $conn);
			$array_country = mysql_fetch_array($result_country);
			$country_printable_name = $array_country['country_printable_name'];

			print "<h1>$company_name&nbsp;<a href=\"index2.php?page=contacts_company_edit&amp;company_id=$company_id&amp;status=edit\"><img src=\"images/button_edit.png\" alt=\"Edit Entry\" /></a></h1>";
			
			print "<fieldset><legend>Company Details</legend>";
			
			print "<table width=\"100%\">";
			
			print "<tr><td class=\"color\" style=\"width: 12px; text-align: center;\">A</td><td>";
			
				if ($company_address != NULL) { print nl2br($company_address)."<br />"; }
				if ($company_city != NULL) { print $company_city."<br />"; }
				if ($company_county != NULL) { print $company_county."<br />"; }
				if ($company_postcode != NULL) { print "<a href=\"".PostcodeFinder($company_postcode)."\">".$company_postcode."</a><br />"; }			
				if ($company_country != NULL) { print $country_printable_name."<br />"; }
			
			print "</td></tr>";
			
				if ($company_phone != NULL) { print "<tr><td class=\"color\" align=\"center\">T</td><td class=\"color\">".$company_phone."</td></tr>"; }
				if ($company_fax != NULL) { print "<tr><td class=\"color\" align=\"center\">F</td><td class=\"color\">".$company_fax."</td></tr>"; }
				if ($company_web != NULL) { print "<tr><td class=\"color\" align=\"center\">W</td><td class=\"color\"><a href=\"http://$company_web\">".$company_web."</a></td></tr>"; }
			
			print "</table>";
			
			print "</fieldset>";

if ($user_usertype > 3) {			
print "<p class=\"menu_bar\"><a href=\"index2.php?page=contacts_company_edit&amp;company_id=$company_id&amp;status=edit\">Edit Company</a></p>";
}

// Return the contacts who work for this company

$sql_contact = "SELECT * FROM contacts_contactlist WHERE contact_company = '$company_id' ORDER BY contact_namesecond";
$result_contact = mysql_query($sql_contact, $conn) or die(mysql_error());

if (mysql_num_rows($result_contact) > 0) {

   print "<fieldset><legend>Company Contacts</legend>";

   print "<table width=\"100%\">";
   while ($array_contact = mysql_fetch_array($result_contact)) {
   $contact_id = $array_contact['contact_id'];
   $contact_namefirst = $array_contact['contact_namefirst'];
   $contact_namesecond = $array_contact['contact_namesecond'];
   $contact_mobile = $array_contact['contact_mobile'];
   $contact_telephone = $array_contact['contact_telephone'];
   $contact_email = $array_contact['contact_email'];
   
   print "<tr>";
   print "<td class=\"color\"><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">$contact_namefirst&nbsp;$contact_namesecond</td>";
   if ($contact_mobile != "" ) { print "<td class=\"color\" align=\"center\">M</td><td class=\"color\">$contact_mobile</td>"; }
   elseif ($contact_telephone != "" ) { print "<td class=\"color\" align=\"center\">T</td><td class=\"color\">$contact_telephone</td>"; }
   elseif ($contact_email != "" ) { print "<td class=\"color\" align=\"center\">E</td><td class=\"color\"><a href=\"mailto:$contact_email\">$contact_email</a></td>"; }
   else { echo "<td colspan=\"2\"></td>"; }
   print "</tr>";   

   }
   print "</table>";
   
   print "</fieldset>";
}

if ($company_notes != NULL) { echo "<fieldset><legend>Notes</legend><blockquote>".DeCode($company_notes)."</blockquote></fieldset>"; }

?>
