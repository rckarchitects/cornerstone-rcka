<?php

if ($_GET[company_id] > 0) { $company_id = CleanNumber($_GET[company_id]); } elseif ($company_id_added > 0) {$company_id = $company_id_added; } else { $company_id = 0; }

echo "<h1>Contacts</h1>";


if ($company_id > 0) {

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

			echo "<h2>" . $company_name . "</h2>";
			
			ProjectSubMenu(NULL,$user_usertype_current,"contacts_admin",1);
			ProjectSubMenu(NULL,$user_usertype_current,"company_admin",2);
			
			echo "<div><h3>Company Details</h3>";
			
			echo "<table width=\"100%\">";
			
			echo "<tr><td class=\"color\" style=\"width: 12px; text-align: center;\">A</td><td>";
			
				if ($company_address != NULL) { echo nl2br($company_address)."<br />"; }
				if ($company_city != NULL) { echo $company_city."<br />"; }
				if ($company_county != NULL) { echo $company_county."<br />"; }
				if ($company_postcode != NULL) { echo "<a href=\"".PostcodeFinder($company_postcode)."\">".$company_postcode."</a><br />"; }			
				if ($company_country != NULL) { echo $country_printable_name."<br />"; }
			
			echo "</td></tr>";
			
				if ($company_phone != NULL) { echo "<tr><td class=\"color\" align=\"center\">T</td><td class=\"color\">".$company_phone."</td></tr>"; }
				if ($company_fax != NULL) { echo "<tr><td class=\"color\" align=\"center\">F</td><td class=\"color\">".$company_fax."</td></tr>"; }
				if ($company_web != NULL) { echo "<tr><td class=\"color\" align=\"center\">W</td><td class=\"color\"><a href=\"http://$company_web\">".$company_web."</a></td></tr>"; }
			
			echo "</table>";
			
			echo "</div>";

// Return the contacts who work for this company

$sql_contact = "SELECT * FROM contacts_contactlist WHERE contact_company = '$company_id' ORDER BY contact_namesecond";
$result_contact = mysql_query($sql_contact, $conn) or die(mysql_error());

if (mysql_num_rows($result_contact) > 0) {

   echo "<div><h3>Company Contacts</h3>";

   echo "<table width=\"100%\">";
   while ($array_contact = mysql_fetch_array($result_contact)) {
   $contact_id = $array_contact['contact_id'];
   $contact_namefirst = $array_contact['contact_namefirst'];
   $contact_namesecond = $array_contact['contact_namesecond'];
   $contact_mobile = $array_contact['contact_mobile'];
   $contact_telephone = $array_contact['contact_telephone'];
   $contact_email = $array_contact['contact_email'];
   
   echo "<tr>";
   echo "<td class=\"color\"><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">$contact_namefirst&nbsp;$contact_namesecond</td>";
   if ($contact_mobile != "" ) { echo "<td class=\"color\" align=\"center\">M</td><td class=\"color\">$contact_mobile</td>"; }
   elseif ($contact_telephone != "" ) { echo "<td class=\"color\" align=\"center\">T</td><td class=\"color\">$contact_telephone</td>"; }
   elseif ($contact_email != "" ) { echo "<td class=\"color\" align=\"center\">E</td><td class=\"color\"><a href=\"mailto:$contact_email\">$contact_email</a></td>"; }
   else { echo "<td colspan=\"2\"></td>"; }
   echo "</tr>";   

   }
   echo "</table>";
   
   echo "</div>";
}

if ($company_notes != NULL) { echo "<fieldset><legend>Notes</legend><blockquote>".DeCode($company_notes)."</blockquote></fieldset>"; }





} else {


echo "<p>No company found.</p>";



}