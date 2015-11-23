<?

print "<select name=\"contact_discipline\" class=\"inputbox\">";

if ($_POST[contact_discipline] != NULL) { $contact_discipline = $_POST[contact_discipline]; }

$sql = "SELECT * FROM contacts_disciplinelist order by discipline_name";

$result = mysql_query($sql, $conn) or die(mysql_error());

print "<option value=\"\" >-- None --</option>";

while ($array = mysql_fetch_array($result)) {
$discipline_id = $array['discipline_id'];
$discipline_name = $array['discipline_name'];
$discipline_ref = $array['discipline_ref'];

print "<option value=$discipline_id";
if ($contact_discipline == $discipline_id) {
	print " selected";
}

print ">$discipline_name";

if ($discipline_ref != NULL) { print " [".$discipline_ref."]</option>"; }

}

print "</select>";



?>
