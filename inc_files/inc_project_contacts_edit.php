<?php

// First, identify if we're adding or editing

if ($_GET[contact_proj_id] > 0) {
$contact_proj_id = CleanNumber($_GET[contact_proj_id]);
$sql_check = "SELECT contact_proj_contact, contact_proj_role, contact_proj_note, contact_proj_company FROM intranet_contacts_project WHERE contact_proj_id = '$contact_proj_id' LIMIT 1 ";
$result_check = mysql_query($sql_check, $conn) or die(mysql_error());
$array_check = mysql_fetch_array($result_check);
$contact_proj_contact = $array_check['contact_proj_contact'];
$contact_proj_role = $array_check['contact_proj_role'];
$contact_proj_note = $array_check['contact_proj_note'];
$contact_proj_company = $array_check['contact_proj_company'];
}

if ($_GET[contact_proj_id] > 0) {
print "<h2>Edit Project Contacts</h2>";
echo "<form method=\"post\" action=\"index2.php?page=project_contacts&amp;proj_id=$_GET[proj_id]\">";
} else {
print "<h2>Add Project Contacts</h2>";
echo "<form method=\"post\" action=\"index2.php?page=project_view&amp;proj_id=$_GET[proj_id]&amp;status=edit&amp;show=contacts\">";
}

echo "<table><tr><td colspan=\"2\">Contact Name</td></tr>";
$sql_contact = "SELECT contact_id, contact_namefirst, contact_namesecond, contact_company, company_name, company_postcode, company_id FROM contacts_contactlist LEFT JOIN contacts_companylist ON contacts_contactlist.contact_company = contacts_companylist.company_id ORDER BY contact_namesecond, contact_namefirst";

$result_contact = mysql_query($sql_contact, $conn) or die(mysql_error());
print "<tr><td colspan=\"2\"><select name=\"contact_proj_contact\">";

	while ($array_contact = mysql_fetch_array($result_contact)) {

		$contact_id = $array_contact['contact_id'];
		$company_id = $array_contact['company_id'];
		$contact_namefirst = $array_contact['contact_namefirst'];
		$contact_namesecond = $array_contact['contact_namesecond'];
		$contact_postcode = $array_contact['contact_postcode'];
		$company_name = $array_contact['company_name'];
		$company_postcode = $array_contact['company_postcode'];
		
		$name_print = $contact_namesecond.", ".$contact_namefirst;

		//$name_print = NULL;
		// if (str_len($contact_namesecond) > 0) { $name_print = $contact_namesecond; }
		// if (str_len($contact_namefirst) > 0) { $name_print = $name_print.", ".$contact_namefirst; }
		
		if ($_GET[contact_proj_id] == NULL AND $company_id > 0) { $print_company = "- " . $company_name." [".$company_postcode."]"; } else { $print_company = NULL; }
		if ($contact_proj_contact == $contact_id) { $selected = "selected=\"selected\""; $project_company = $company_id; } else { $selected = NULL; }
		echo "<option value=\"$contact_id\" $selected>$name_print $print_company</option>\n";
}

echo "</select>";
echo "</td></tr>";

if ($_GET[contact_proj_id] > 0) {

			if ($contact_proj_company != $contact_company) {
			echo "<tr><td colspan=\"2\"><p><strong>Note:</strong><br />The contact listed for this project is no longer with the company which undertook the work on this project. Please ensure that the company listed below is correct.</p></td></tr>";
			}

			// Contact company

			print "<tr><td colspan=\"2\"><select name=\"contact_proj_company\">";

			$sql_company = "SELECT company_name, company_postcode, company_id FROM contacts_companylist ORDER BY company_name, company_postcode";
			$result_company = mysql_query($sql_company, $conn) or die(mysql_error());
			
			if ($contact_proj_company > 0) { $company_selected = $contact_proj_company;} elseif ($project_company > 0) { $company_selected = $project_company;} else { $company_selected = NULL; }
			
			echo "<option value=\"\">-- None --</option>";
				while ($array_company = mysql_fetch_array($result_company)) {

					$company_id = $array_company['company_id'];
					$company_name = $array_company['company_name'];
					$company_postcode = $array_company['company_postcode'];
					
					if ($company_id == $company_selected) { $selected = "selected=\"selected\""; } else { $selected = NULL; }
					echo "<option value=\"$company_id\" $selected>$company_name, $company_postcode</option>\n";
			}

			echo "</select>";
			echo "</td></tr>";

}

echo "<tr><td>Role</td><td>Notes</td></tr>";
$sql_disc = "SELECT discipline_id, discipline_name, discipline_ref FROM contacts_disciplinelist ORDER BY discipline_name";
$result_disc = mysql_query($sql_disc, $conn) or die(mysql_error());
print "<tr><td><select name=\"contacts_discipline\">";

	while ($array_disc = mysql_fetch_array($result_disc)) {

		$discipline_id = $array_disc['discipline_id'];
		$discipline_name = $array_disc['discipline_name'];
		if ($contact_proj_role == $discipline_id) { $selected = "selected=\"selected\""; } else { $selected = NULL; }
		echo "<option value=\"$discipline_id\" $selected>$discipline_name</option>\n";
}

echo "</select></td><td><textarea name=\"contact_proj_note\" cols=\"38\" rows=\"3\">";
if ($_GET[contact_proj_id] > 0) { echo $contact_proj_note; }
echo "</textarea></td></tr>";


echo "<tr><td colspan=\"2\">";

if ($_GET[contact_proj_id] > 0) {
echo "<input type=\"hidden\" name=\"action\" value=\"project_contact_edit\" /><input type=\"hidden\" name=\"contact_proj_project\" value=\"$_GET[proj_id]\" /><input type=\"hidden\" name=\"contact_proj_id\" value=\"$contact_proj_id\" /><input type=\"submit\" value=\"Update Contact\" />";
} else {
echo "<input type=\"hidden\" name=\"action\" value=\"project_contact_add\" /><input type=\"hidden\" name=\"contact_proj_project\" value=\"$_GET[proj_id]\" /><input type=\"submit\" value=\"Add Contact\" />";
}

echo "</td></tr></table>";

echo "</form>";

?>