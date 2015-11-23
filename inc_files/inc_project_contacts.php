<?php

echo "<h2>Project Contacts</h2>";
$sql_contact = "SELECT * FROM contacts_disciplinelist, contacts_contactlist, intranet_contacts_project LEFT JOIN contacts_companylist ON contact_proj_company = contacts_companylist.company_id WHERE contact_proj_contact = contact_id  AND discipline_id = contact_proj_role AND contact_proj_project = '$_GET[proj_id]' ORDER BY discipline_name, contact_namesecond";
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
echo "<td><a href=\"index2.php?page=project_contacts&amp;contact_proj_id=$contact_proj_id&amp;proj_id=$_GET[proj_id]&amp;action=project_contact_remove&amp;contact_proj_id=$contact_proj_id\" onClick=\"javascript:return confirm('Are you sure you want to delete this project contact?');\"><img src=\"images/button_delete.png\" /></a></td><td><a href=\"index2.php?page=project_contacts_edit&amp;contact_proj_id=$contact_proj_id&amp;proj_id=$_GET[proj_id]\"><img src=\"images/button_edit.png\" /></a></td></tr>";


}
echo "</table>";

} else { echo "<p>- None - </p>"; }

if ($user_usertype_current > 0) {
include("inc_files/inc_project_contacts_edit.php");
}


?>