<?php

include "inc_files/inc_checkcookie.php";

echo "<table>";

	$sql_contact = "SELECT contact_namefirst, contact_namesecond, contact_id,contact_title, title_id, title_name FROM contacts_contactlist, contacts_titlelist WHERE contact_title = title_id ORDER BY contact_namesecond,contact_namefirst";
	$result_contact = mysql_query($sql_contact, $conn);
	
	while ($array_contact = mysql_fetch_array($result_contact)) {
	
	$contact_id = $array_contact['contact_id'];
	$contact_title = $array_contact['contact_title'];
	$title_id = $array_contact['title_id'];
	$title_name = $array_contact['title_name'];
	$contact_name = $array_contact['contact_namefirst']." ".$array_contact['contact_namesecond'];
	
	
			if  ($contact_title > 0 AND strlen($contact_title) < 4) {
				$sql_update = "UPDATE contacts_contactlist SET contact_title = '$title_name' where contact_id = '$contact_id' LIMIT 1";
				$result_update = mysql_query($sql_update, $conn) or die(mysql_error());
				echo "<tr><td>$contact_id</td><td>$contact_name</td><td>$contact_title</td><td>$title_name</td><td>...Updated.</td></tr>";
			} elseif ($contact_title === 0) {
				$sql_update = "UPDATE contacts_contactlist SET contact_title = '' WHERE contact_id = '$contact_id' LIMIT 1";
				$result_update = mysql_query($sql_update, $conn) or die(mysql_error());
				echo "<tr><td>$contact_id</td><td>$contact_name</td><td>$contact_title</td><td>$title_name</td><td>...Updated (To NULL).</td></tr>";
			}
	
	}

echo "</table>";

?>


