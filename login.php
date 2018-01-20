<?php

// Perform the security check

if ($_COOKIE[user] != NULL) {
header( "Location: index.php");
}

function LoginScreen ($settings_name,$user_name) {

	echo "<body>";

	echo "<div id=\"pagewrapper\">";

	echo "<div id=\"login_head\">$settings_name</div>";

	echo "<div id=\"login_body\">";

	echo "<form method=\"post\" action=\"logincheck.php\">";

	echo "<br /><p>Username:<br /><input type=text value=\"$user_name\" class=\"inputbox\" name=\"checkform_username\" /></p>";
	echo "<p>Password:<br /><input type=\"password\" name=\"password\" class=\"inputbox\" /></p>";

	if ($user_name == NULL) {
	echo "Public Computer?&nbsp;&nbsp;<input type=\"checkbox\" name=\"publicpc\" value=\"1\" checked />";
	}


	echo "<input type=\"hidden\" name=\"password_check\" value=\"yes\" />";
	echo "<input type=\"hidden\" name=\"usercheck\" value=\"yes\" />";
	echo "<p><input type=\"submit\" value=\"Login\" class=\"inputsubmit\" /></p>";

	echo "</form>";

	echo "</div>";

	echo "<div id=\"login_footer\"></div>";

	echo "</div>";

	echo "</body>";
	echo "</html>";

}

// Include the cookie check information

include_once("inc_files/inc_checkcookie_logincheck.php");

// Include the header information

include_once("inc_files/inc_header.php");

LoginScreen ($settings_name,$_COOKIE[name]);


