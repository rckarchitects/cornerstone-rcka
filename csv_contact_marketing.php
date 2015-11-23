<?php

// This exports to the following order:

// Company
// Salutation
// Title
// First name
// Last name
// Street 1
// Street 2
// Country
// Postcode
// City
// Telephone
// Fax
// Mobile phone
// E-mail address
// Tax area
// Account number
// Customer group
// Discount level
// Additional information 1
// Additional information 2
// Additional information 3
// Additional information 4
// Additional information 5

include "inc_files/inc_checkcookie.php";

// Get the page details

include("inc_functions.php");

// Begin the database collection

if ($_GET[email] == "yes") {

$sql_contact = "SELECT * FROM contacts_contactlist LEFT JOIN contacts_companylist ON contact_company = company_id WHERE ( contact_include = 1 OR contact_include = 2 ) ORDER BY company_name, contact_namesecond, contact_namefirst";

echo "<p><a href=\"csv_contact_marketing.php\">Click here to show all contacts</a>.</p>";

} else {

$sql_contact = "SELECT * FROM contacts_contactlist LEFT JOIN contacts_companylist ON contact_company = company_id  ORDER BY company_name, contact_namesecond, contact_namefirst";

echo "<p><a href=\"csv_contact_marketing.php?email=yes\">Click here to show email contacts only</a>.</p>";

}

// $sql_contact = "SELECT * FROM contacts_contactlist LEFT JOIN contacts_companylist ON contact_company = company_id WHERE contact_include = '1' ORDER BY company_name, contact_namesecond, contact_namefirst";
$result_contact = mysql_query($sql_contact, $conn);

echo "<table>";

$count = 0;

 while ($array_contact = mysql_fetch_array($result_contact)) {
 
	// establish the contact information from the array
	
	unset($address_go);
	
	$contact_id = $array_contact['contact_id'];
	$contact_namefirst = $array_contact['contact_namefirst'];
	$contact_namesecond = $array_contact['contact_namesecond'];
	$contact_company = $array_contact['contact_company'];
	$contact_department = $array_contact['contact_department'];
	$contact_address = $array_contact['contact_address'];
	$contact_city = $array_contact['contact_city'];
	$contact_county = $array_contact['contact_county'];
	$contact_postcode = $array_contact['contact_postcode'];
	$contact_include = $array_contact['contact_include'];
	$contact_email = $array_contact['contact_email'];
	$contact_include = $array_contact['contact_include'];
	
	if ($contact_include == 1) { $contact_include = "EH"; }
	elseif ($contact_include == 2) { $contact_include = "E"; }
	elseif ($contact_include == 3) { $contact_include = "H"; }
	else { $contact_include = "-"; }
	
	if ($contact_include > 0) { $marketing = "Marketing Contact"; } else { unset($marketing); }
	
	$company_name = $array_contact['company_name'];
	$company_address = $array_contact['company_address'];
	$company_city = $array_contact['company_city'];
	$company_postcode = $array_contact['company_postcode'];
 
	if ($contact_namefirst != NULL) { $address_go = $contact_namefirst." ".$contact_namesecond; }
	if ($contact_department != NULL) { $address_go = $address_go."\n".$contact_department; }
	if ($company_name != NULL) { $address_go = $address_go."\n".$company_name; }
	if ($company_address != NULL) { $address_go = $address_go."\n".$company_address; } elseif ($contact_address != NULL) { $address_go = $address_go."\n".$contact_address; }
	if ($company_city != NULL) { $address_go = $address_go."\n".$company_city; } elseif ($contact_city != NULL) { $address_go = $address_go."\n".$contact_city; }
	if ($company_county != NULL) { $address_go = $address_go."\n".$company_county; } elseif ($contact_county != NULL) { $address_go = $address_go."\n".$contact_county; }
	if ($company_postcode != NULL) { $address_go = $address_go.", ".$company_postcode; } elseif ($contact_postcode != NULL) { $address_go = $address_go."\n".$contact_postcode; }
	
	$address_go = str_replace("&amp;","&",$address_go);
	
	// echo "<tr><td>" . $company_name . "</td><td></td><td></td><td>" . $contact_namefirst . "</td><td>" . $contact_namesecond . "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>" . $contact_email . "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
	
	if (($current_namefirst != $contact_namefirst) AND ($current_namesecond != $contact_namesecond) AND ($current_company AND $contact_company) AND ($contact_email != NULL)) {
	
			echo "<tr><td>$count</td><td>" . $company_name . "</td><td>" . $contact_namefirst . "</td><td>" . $contact_namesecond . "</td><td>" . $contact_email . "</td><td>$contact_include</td><td>$marketing</td><td><a href=\"http://intranet.rcka.co.uk/index2.php?page=contacts_edit&amp;contact_id=$contact_id&amp;status=edit\">[edit]</a></td></tr>";
			
	}
	
	$count++;
	
	$current_namefirst = $contact_namefirst;
	$current_namesecond = $contact_namesecond;
	$current_company = $contact_company;

}

echo "</table>";


?>