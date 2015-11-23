<?php

print "<select name=\"company_country\" class=\"inputbox\">";

if ($_POST[company_country] != NULL) { $company_country = $_POST[company_country]; } else  {$company_country = $settings_country; }

$sql = "SELECT country_id, country_printable_name FROM intranet_contacts_countrylist order by country_name";
$result = mysql_query($sql, $conn) or die(mysql_error());

print "<option value=\"\">-- None --</option>";

while ($array = mysql_fetch_array($result)) {
$country_id = $array['country_id'];
$country_printable_name = $array['country_printable_name'];

print "<option value=\"$country_id\"";
if ($country_id == $company_country) {
	print " selected";
}

print ">$country_printable_name</option>";

}

print "</select>";



?>
