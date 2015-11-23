<?

print "<select name=\"contact_relation\" class=\"inputbox\">";

// if ($_POST[contact_relation] != NULL) { $contact_relation = $_POST[contact_relation]; } elseif ($_GET[id] != NULL) {  } else { $contact_relation = 10; }

$sql = "SELECT * FROM contacts_relationlist order by relation_name";

$result = mysql_query($sql, $conn) or die(mysql_error());


print "<option value=\"\">-- None --</option>";

while ($array = mysql_fetch_array($result)) {
$relation_id = $array['relation_id'];
$relation_name = $array['relation_name'];

print "<option value=\"$relation_id\"";
if ($contact_relation == $relation_id) {
	print " selected";
}

print ">$relation_name</option>\n";

}

print "</select>";



?>
