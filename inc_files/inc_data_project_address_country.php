<?php

print "<select name=\"proj_address_country\" class=\"inputbox\">";

if ($proj_country != NULL) { $contact_country = $proj_country; } else  {$proj_country = $settings_country; }

$sql = "SELECT country_id, country_printable_name FROM intranet_contacts_countrylist order by country_name";
$result = mysql_query($sql, $conn) or die(mysql_error());

print "<option value=\"\">-- None --</option>";

while ($array = mysql_fetch_array($result)) {
$country_id = $array['country_id'];
$country_printable_name = $array['country_printable_name'];

print "<option value=\"$country_id\"";
if ($country_id == $proj_address_country OR $country_id == $settings_country) {
	print " selected";
}

print ">$country_printable_name</option>";

}

print "</select>";



?>
