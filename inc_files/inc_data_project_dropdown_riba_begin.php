<?php

$sql = "SELECT * FROM riba_stages order by riba_order";
$result = mysql_query($sql, $conn) or die(mysql_error());

print "<select name=\"proj_riba_begin\" class=\"inputbox\">";

print "<option value=\"\">-- N/A --";

while ($array = mysql_fetch_array($result)) {
$riba_id = $array['riba_id'];
$riba_letter = $array['riba_letter'];
$riba_desc = $array['riba_desc'];

print "<option value=\"$riba_id\" class=\"inputbox\"";
if ($riba_id == $proj_riba_begin) {
print " selected";
}
print ">";
if ($riba_letter != NULL) { print $riba_letter.":&nbsp;"; }
print $riba_desc."</option>";
}

print "</select>";
?>

