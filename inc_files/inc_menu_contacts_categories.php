<?php

print "<p class=\"heading_side\">Relationship Categories</p>";

// Populate the legend

	$sql = "SELECT * FROM contacts_relationlist WHERE relation_color != '' order by relation_name";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
		print "<table cellspacing=\"4\">";
		
	while ($array = mysql_fetch_array($result)) {
	
	$relation_name = $array['relation_name'];
	$relation_id = $array['relation_id'];
	$relation_color = $array['relation_color'];
	
		print "<tr><td width=\"20\" height=\"20\" style=\"background: #$relation_color;\"></td><td class=\"color\"><a href=\"index2.php?page=contacts&amp;listbegin=0&amp;listorder=contact_namesecond&amp;contact_relation=$relation_id\">$relation_name</a></td>";
		
	}
		print "</table>";
		
print "<p class=\"menu_bar\"><a href=\"contacts_categories_edit\">Edit Categories</a></p>";
		
?>
