<?php

	$sql_prefix = "SELECT * FROM contacts_prefixlist ORDER BY prefix_name";
	$result_prefix = mysql_query($sql_prefix, $conn) or die(mysql_error());

print "<select name=\"contact_prefix\" class=\"inputbox\">";
print "<option value=\"\">-- None --</option>";
	
		while ($array_prefix = mysql_fetch_array($result_prefix)) {

		$prefix_id = $array_prefix['prefix_id'];
		$prefix_name = $array_prefix['prefix_name'];

print "<option value=\"$prefix_id\"";

    if ($contact_prefix == $prefix_id OR $_POST[contact_prefix] == $prefix_id) { print " selected"; }

print ">$prefix_name</option>";

}

print "</select>";



?>
