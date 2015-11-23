<?php

$sql_discipline = "SELECT * FROM contacts_disciplinelist ORDER BY discipline_name";

	$result_discipline = mysql_query($sql_discipline, $conn);
	
	echo "<h1>List of disciplines</h1>";
	
	if (mysql_num_rows($result_discipline) > 0) {
	
	echo "<table>";
	
	while ($array_discipline = mysql_fetch_array($result_discipline)) {
	$discipline_id = $array_discipline['discipline_id'];
	$discipline_name = $array_discipline['discipline_name'];
	echo "<tr><td><a href=\"index2.php?page=discipline_view&amp;discipline_id=$discipline_id\">$discipline_name</a></td></tr>";
	}
	
	echo "</table>";

} else {

echo "<p>-- None found --</p>";

}


?>