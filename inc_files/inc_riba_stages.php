<?php

print "<h1>RIBA Plan of Work</h1>";


// Project Page Menu
print "<p class=\"menu_bar\">";
	if ($user_usertype_current > 3) {
		print "<a href=\"index2.php?page=riba_stages_edit\">Edit</a>";
	}
	
print "</p>";

print "<table>";

print "<tr><td><strong>Letter</strong></td><td><strong>Name</strong></td><td><strong>Percentage of Fee</strong></td></tr>";


$sql = "SELECT * FROM riba_stages ORDER BY riba_order";
$result = mysql_query($sql, $conn);
$array = mysql_fetch_array($result);

	while ($array = mysql_fetch_array($result)) {
	
			$riba_id = $array['riba_id'];
			$riba_letter = $array['riba_letter'];
			$riba_desc = $array['riba_desc'];
			$riba_percentage = $array['riba_percentage'];
			$riba_text = $array['riba_text'];

			print "<tr><td id=\"riba_".$riba_id."\"";
			if ($riba_text != NULL) { print " rowspan=\"2\" "; }
			print "><strong>";
			print $riba_letter."</strong></td><td><strong>".$riba_desc."</strong></td><td style=\"text-align: right; width: 30px;\"><strong>".$riba_percentage."%</strong></td></tr>";
			if ($riba_text != NULL) { print "<tr><td colspan=\"2\"><span class=\"minitext\">".$riba_text."</span></td></tr>"; }
			
	}



print "</table>\n\n";


?>



