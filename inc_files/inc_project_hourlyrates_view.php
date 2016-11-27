<?php


print "<h2>Hourly Rates</h2>";

// Determine the date a week ago

$date_lastweek = time() - 604800;

$sql = "SELECT * FROM intranet_tasklist WHERE tasklist_project = '$proj_id'  AND tasklist_percentage < 100 order by tasklist_due DESC";

$result = mysql_query($sql, $conn) or die(mysql_error());


?>