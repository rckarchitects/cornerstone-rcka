<?php

// Perform the security check

if ($_COOKIE[user] != NULL) {
header( "Location: index.php");
}

// Include the cookie check information

include("inc_files/inc_checkcookie_logincheck.php");

// Include the header information

include("inc_files/inc_header.php");

// Header

print "<body>";

print "<div id=\"pagewrapper\">";

print "<div id=\"login_head\">$settings_name</div>";

print "<div id=\"login_body\">";

print "<form method=\"post\" action=\"logincheck.php\">";

print "<br /><p>Username:<br /><input type=text value=\"$_COOKIE[name]\" class=\"inputbox\" name=\"checkform_username\" /></p>";
print "<p>Password:<br /><input type=\"password\" name=\"password\" class=\"inputbox\" /></p>";

if ($_COOKIE[name] == NULL) {
print "Public Computer?&nbsp;&nbsp;<input type=\"checkbox\" name=\"publicpc\" value=\"1\" checked />";
}


print "<input type=\"hidden\" name=\"password_check\" value=\"yes\" />";
print "<input type=\"hidden\" name=\"usercheck\" value=\"yes\" />";
print "<p><input type=\"submit\" value=\"Login\" class=\"inputsubmit\" /></p>";

print "</form>";

print "</div>";

print "<div id=\"login_footer\"></div>";

print "</div>";

print "</body>";
print "</html>";

?>
