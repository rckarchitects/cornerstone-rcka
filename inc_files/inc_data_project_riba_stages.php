<?php

$sql = "SELECT * FROM riba_stages WHERE riba_id = '$proj_riba' LIMIT 1";
$result = mysql_query($sql, $conn) or die(mysql_error());

$array = mysql_fetch_array($result);
$riba_id = $array['riba_id'];
$riba_letter = $array['riba_letter'];
$riba_desc = $array['riba_desc'];

if ($proj_riba != "" AND $proj_riba != "0") {
	if ($riba_letter != NULL) { print $riba_letter.":&nbsp;"; }
	print "<a href=\"index2.php?page=riba_stages#riba_".$riba_id."\">";
	print $riba_desc;
	print "</a>";
}


?>

