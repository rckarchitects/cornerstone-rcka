<?

print "<select name=\"contact_sector\" class=\"inputbox\">";

if ($_POST[contact_sector] != NULL) { $contact_sector = $_POST[contact_sector]; }

$sql = "SELECT * FROM contacts_sectorlist order by sector_name";
$result = mysql_query($sql, $conn) or die(mysql_error());

print "<option value=\"\">-- None --</option>";

while ($array = mysql_fetch_array($result)) {
$sector_id = $array['sector_id'];
$sector_name = $array['sector_name'];

print "<option value=\"$sector_id\"";
if ($contact_sector == $sector_id) {
	print " selected";
}

print ">$sector_name</option>";

}

print "</select>";



?>
