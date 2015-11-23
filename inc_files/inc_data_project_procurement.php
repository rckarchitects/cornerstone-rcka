<?php

$sql = "SELECT * FROM intranet_procure order by procure_title";
$result = mysql_query($sql, $conn) or die(mysql_error());

print "<select name=\"proj_procure\" class=\"inputbox\">";

print "<option value=\"\">-- N/A --</option>";

while ($array = mysql_fetch_array($result)) {
$procure_id = $array['procure_id'];
$procure_title = $array['procure_title'];
$procure_desc = $array['procure_desc'];

print "<option value=\"$procure_id\" class=\"inputbox\"";
if ($procure_id == $proj_procure) {
print " selected";
}
print ">".$procure_title."</option>";
}

print "</select>";
?>

