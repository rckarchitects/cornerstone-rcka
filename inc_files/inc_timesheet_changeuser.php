<?php

print "<h1>Change User to View</h1>";

// Form to change user view

print "<fieldset><legend>Change User</legend>";

print "<form method=\"post\" action=\"index2.php?page=timesheet&amp;status=changeuser\">";

print "<p>";

print "<select name=\"viewuser\" class=\"inputbox\">";

$sql = "SELECT * FROM intranet_user_details order by user_name_second";
$result = mysql_query($sql, $conn) or die(mysql_error());


while ($array = mysql_fetch_array($result)) {
$user_name_first = $array['user_name_first'];
$user_name_second = $array['user_name_second'];
$user_id = $array['user_id'];

print "<option value=\"$user_id\" class=\"inputbox\"";
if ($user_id == $viewuser) {
print " selected";
}
print ">$user_name_first&nbsp;$user_name_second</option>";
}

print "</select></p><p>";

print "<input type=submit value=\"Change User\" class=\"inputsubmit\" />";

print "</p>";

print "</form>";

print "</fieldset>";


?>
