<?php

$sql_disc = "SELECT discipline_name FROM contacts_disciplinelist WHERE discipline_id = '$_GET[discipline_id]' LIMIT 1";
$result_disc = mysql_query($sql_disc, $conn) or die(mysql_error());
$array_disc = mysql_fetch_array($result_disc);
$discipline_name = $array_disc['discipline_name'];

echo "<h1>$discipline_name</h1>";

echo "<fieldset><legend>Project Contacts</legend>";

$sql_contact = "SELECT * FROM contacts_disciplinelist, intranet_projects, intranet_contacts_project, contacts_contactlist LEFT JOIN contacts_companylist ON contacts_contactlist.contact_company = contacts_companylist.company_id WHERE contact_proj_contact = contact_id  AND discipline_id = contact_proj_role AND discipline_id = '$_GET[discipline_id]' AND contact_proj_project = proj_id ORDER BY contact_namesecond, contact_namefirst, proj_num";
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

echo "</fieldset>";

// All contacts with this discipline


echo "<fieldset><legend>Other Contacts</legend>";

$sql_contact = "SELECT * FROM contacts_disciplinelist, contacts_contactlist LEFT JOIN contacts_companylist ON contacts_contactlist.contact_company = contacts_companylist.company_id WHERE contact_discipline = '$_GET[discipline_id]' AND discipline_id = contact_discipline ORDER BY contact_namesecond, contact_namefirst";
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

echo "</fieldset>";


?>