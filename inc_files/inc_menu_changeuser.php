<?
if ($user_id_current ==  "") {
header("Location:login.php");
}

$sql = "SELECT * FROM intranet_user_details WHERE user_active = '1' order by 'user_name_second'";
$result = mysql_query($sql, $conn) or die(mysql_error());

print "<p class=\"heading_side\">Current User</p>";

print "<fieldset>";
print "<legend>Change User</legend>";

print "<form method=\"post\" action=\"logincheck.php\">";

print "<p><select name=\"checkform_user\" class=\"inputbox\">";

while ($array = mysql_fetch_array($result)) {
$user_name_first = $array['user_name_first'];
$user_name_second = $array['user_name_second'];
$user_completename = $user_name_first . "&nbsp;" . $user_name_second;
$user_id_select = $array['user_id'];

print "<option value=\"$user_id_current\" class=\"inputbox\"";
if ($user_id_current == $user_id_select) {
print " selected";
}
print ">".$user_completename."</option>";
}

print "</select></p><p>";

print "<input type=\"password\" name=\"password\" class=\"inputbox\" /></p><p><input type=\"submit\" value=\"Change\" class=\"inputsubmit\" /><input type=\"hidden\" name=\"password_check\" value=\"yes\" /><input type=\"hidden\" name=\"usercheck\" value=\"yes\" /></p></form>";

print "</fieldset>";

?>
