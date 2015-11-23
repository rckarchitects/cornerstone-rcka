<?

print "<select name=\"contact_title\" class=\"inputbox\">";

if ($_POST[contact_title] != NULL) { $contact_title = $_POST[contact_title]; }

$sql = "SELECT * FROM contacts_titlelist order by title_name";
$result = mysql_query($sql, $conn) or die(mysql_error());

print "<option value=\"\">-- None --</option>";

while ($array = mysql_fetch_array($result)) {
$title_id = $array['title_id'];
$title_name = $array['title_name'];

print "<option value=\"$title_id\"";
if ($contact_title == $title_id) {
	print " selected";
}

print ">$title_name</option>";

}

print "</select>";



?>
