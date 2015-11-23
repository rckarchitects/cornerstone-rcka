<?php

$cpd_points_total = 0;
$cpd_time_total = 0;

// Define the beginning of the year...

$nowtime = time();

$nowyear = date("Y", $nowtime);

$yearbegin = $lastday = mktime(0, 0, 0, 0, 0, $nowyear);

// print "<h1>Personal CPD Record</h1>";

// print "<p class=\"menu_bar\"><a href=\"cpd.php?action=view\" class=\"menu_tab\">View Records</a><a href=\"cpd.php?action=add\" class=\"menu_tab\">Add Entry</a></p>";

$sql = "SELECT * FROM cpd WHERE cpd_user = $user_id_current AND cpd_date > '$yearmake' ";
$result = mysql_query($sql, $conn) or die(mysql_error());


while ($array = mysql_fetch_array($result)) {

		$cpd_value = $array['cpd_value'];
		$cpd_time = $array['cpd_time'];

		$cpd_points = $cpd_value * $cpd_time;

		$cpd_points_total = $cpd_points_total + $cpd_points;
		$cpd_time_total = $cpd_time_total + $cpd_time;

}

$cpd_points_remain = 100 - $cpd_points_total;
settype($cpd_points_main, int);

$cpd_time_remain = 35 - $cpd_time_total;
if ($cpd_time_remain <= 0) {
$cpd_time_remain = 0;
}

print "<table summary=\"Annual CPD Summary\">";

print "<tr><td rowspan=\"2\">$nowyear totals</td><td>".$cpd_time_total." Hours<br />(".$cpd_time_remain." Hours remaining)</td></tr>";

print "<tr><td>".$cpd_points_total." Points<br />(".$cpd_points_remain." Points remaining)</td></tr>";

print "</table>";

?>
