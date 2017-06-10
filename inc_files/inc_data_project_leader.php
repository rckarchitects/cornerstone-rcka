<?php

if ($_GET[status] == "add") { $active = " WHERE user_active = 1 "; } else { unset($active);  }

$sql = "SELECT * FROM intranet_user_details $active order by user_name_second";
$result = mysql_query($sql, $conn) or die(mysql_error());

print "<select name=\"proj_rep_black\" class=\"inputbox\">";

if ($proj_rep_black == NULL) {
print "<option value=\"\" selected>";
print "- None -</option>";
} else {
print "<option value=\"\">";
print "- None -</option>";
}

while ($array = mysql_fetch_array($result)) {
$user_name_first = $array['user_name_first'];
$user_name_second = $array['user_name_second'];
$user_completename = $user_name_first . "&nbsp;" . $user_name_second . "&nbsp;[" . $user_id . "]";
$user_id = $array['user_id'];

print "<option value=\"$user_id\" class=\"inputbox\"";
if ($user_id == $proj_rep_black) {
print " selected";
}
print ">".$user_completename."</option>";
}

print "</select>";

?>

