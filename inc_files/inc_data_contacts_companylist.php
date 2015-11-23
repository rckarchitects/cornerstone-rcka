<?

print "<select name=\"contact_company\" class=\"inputbox\">";

if ($_POST[contact_company] != NULL) { $contact_company = $_POST[contact_company]; }

$sql = "SELECT company_id, company_name, company_postcode FROM contacts_companylist order by company_name";

$result = mysql_query($sql, $conn) or die(mysql_error());

print "<option value=\"\">-- None --</option>";

while ($array = mysql_fetch_array($result)) {
$company_id = $array['company_id'];
$company_name = $array['company_name'];
$company_postcode = $array['company_postcode'];

print "<option value=\"$company_id\"";
if ($contact_company == $company_id) {
	print " selected";
}

if ($company_postcode != NULL) { $printcomma = ","; } else { $printcomma = NULL; }

print ">".$company_name.$printcomma." ".$company_postcode."</option>";

}

print "</select>";



?>
