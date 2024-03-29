<?php


function LoginScreen ($settings_name) {

	echo "<body>";

	echo "<div id=\"pagewrapper\">";

	echo "<div id=\"login_head\">" . $settings_name . "</div>";

	echo "<div id=\"login_body\">";

	echo "<form method=\"post\" action=\"logincheck.php\">";
	
	echo "<input type=\"hidden\" name=\"target_url\" value=\"" .  filter_input(INPUT_GET, 'target', FILTER_VALIDATE_URL) . "\" />"; 

	echo "<br /><p>Username:<br /><input type=text value=\"\" class=\"inputbox\" name=\"checkform_username\" /></p>";
	echo "<p>Password:<br /><input type=\"password\" name=\"password\" class=\"inputbox\" /></p>";
	echo "<p><input type=\"submit\" value=\"Login\" class=\"inputsubmit\" /></p>";

	echo "</form>";

	echo "</div>";

	echo "<div id=\"login_footer\"></div>";

	echo "</div>";

	echo "</body>";
	echo "</html>";

}

function CheckForm() {

	$settings_file = 'secure/database.inc';

	if (!file_exists($settings_file)) {

		echo "<p>There has been a problem reading your settings file.</p>";

	} else {
		
	// Include the cookie check information

	include_once("inc_files/inc_checkcookie_logincheck.php");

	// Include the header information

	include_once("inc_files/inc_header.php");
        
        $name = filter_input(INPUT_COOKIE, 'name', FILTER_SANITIZE_STRING);

	LoginScreen ($settings_name,$name);

	}
	
}

CheckForm();

