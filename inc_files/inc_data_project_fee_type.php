<?php

$sql = "SELECT * FROM intranet_timesheet_rate_type order by rate_value, rate_name";
$result = mysql_query($sql, $conn) or die(mysql_error());

print "<select name=\"proj_fee_type\" class=\"inputbox\">";

if ($proj_fee_type == NULL) {
print "<option value=\"\" selected>";
print "-- None --</option>";
} else {
print "<option value=\"\">";
print "-- None --</option>";
}

while ($array = mysql_fetch_array($result)) {
$rate_id = $array['rate_id'];
$rate_value = $array['rate_value'];
$rate_name = $array['rate_name'];

print "<option value=\"$rate_id\" class=\"inputbox\"";
if ($rate_id == $proj_fee_type) {
print " selected";
}
print ">".$rate_name."&nbsp;[".$rate_value."&nbsp;per hour]</option>";
}

print "</select>";

?>

