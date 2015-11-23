<?php

if($user_usertype_current < 5) { header("location:index2.php"); }

print "<h1>Settings</h1>";


print "<h2>Configuration File</h2>";
if (is_writable("secure/database.inc")) { print "<p>The configuration file is present and writeable.</p>";} else { print "<p>The configuration file is present, but <u>is not writeable</u>. You will not be able to save any changes you make to the options below.</p>"; }

print "<form action=\"index2.php?page=admin_settings\" method=\"post\">";

print "<h2>Database Information</h2>";

print "<p class=\"minitext\">Warning! Altering the settings below may disable the operation of the intranet system. Only change these values if you are absolutely sure what you are doing!</p>";

print "<p>Database Server Address<br /><input type=\"text\" name=\"database_location\" class=\"inputbox\" size=\"52\" maxlength=\"75\" value=\"$database_location\" /></p>";

print "<p>Database Username<br /><input type=\"text\" name=\"database_username\" class=\"inputbox\" size=\"52\" maxlength=\"75\" value=\"$database_username\" /></p>";

print "<p>Database Password<br /><input type=\"text\" name=\"database_password\" class=\"inputbox\" size=\"52\" maxlength=\"75\" value=\"$database_password\" /></p>";

print "<p>Database Name<br /><input type=\"text\" name=\"database_name\" class=\"inputbox\" size=\"52\" maxlength=\"75\" value=\"$database_name\" /></p>";

print "<h2>Preferences</h2>";

print "<p>Name of Intranet System<br /><input type=\"text\" name=\"settings_name\" class=\"inputbox\" size=\"52\" maxlength=\"75\" value=\"$settings_name\" /></p>";

print "<p>Company Name<br /><input type=\"text\" name=\"settings_companyname\" class=\"inputbox\" size=\"52\" maxlength=\"75\" value=\"$settings_companyname\" /></p>";

print "<p>Company Address<br /><textarea name=\"settings_companyaddress\" class=\"inputbox\" rows=\"6\" cols=\"52\">";
print strip_tags($settings_companyaddress);
print "</textarea></p>";

print "<p>Company Country<br />";
include("inc_data_admin_settings_country.php");
print "</p>";

print "<p>Company Telephone Number<br /><input type=\"text\" name=\"settings_companytelephone\" class=\"inputbox\" size=\"52\" maxlength=\"75\" value=\"$settings_companytelephone\" /></p>";

print "<p>Company Fax Number<br /><input type=\"text\" name=\"settings_companyfax\" class=\"inputbox\" size=\"52\" maxlength=\"75\" value=\"$settings_companyfax\" /></p>";

print "<p>Company Web Address<br /><input type=\"text\" name=\"settings_companyweb\" class=\"inputbox\" size=\"52\" maxlength=\"75\" value=\"$settings_companyweb\" /></p>";

print "<p>Enable pop-up reminders for logging in?<br /><input type=\"checkbox\" name=\"settings_popup_login\" value=\"1\" ";
if ($settings_popup_login == 1) { print " checked"; }
print " />&nbsp;Yes</p>";

print "<p>Enable pop-up reminders for unread messages?<br /><input type=\"checkbox\" name=\"settings_popup_newmessage\" value=\"1\" ";
if ($settings_popup_newmessage == 1) { print " checked"; }
print " />&nbsp;Yes</p>";

$remote_ip = $_SERVER["REMOTE_ADDR"];

print "<p>Limit access to a specific IP address?<br /><input type=\"checkbox\" name=\"settings_ip_lock\" value=\"1\" ";
if ($settings_ip_lock == 1) { print " checked"; }
print " />&nbsp;Yes<font class=\"minitext\">&nbsp;(Your current IP address is: $remote_ip)</font></p><p>IP Address:<br /><input type=\"text\" name=\"settings_ip_address\" class=\"inputbox\" maxlength=\"48\" size=\"52\" ";

if ($settings_ip_address != "") { print " value=\"$settings_ip_address\""; } else { print "value=\"$remote_ip\"";  }

print " /></p>";

print "<p>Show error messages?<br /><input type=\"checkbox\" name=\"settings_showtech\" value=\"1\" ";
if ($settings_showtech == 1) { print " checked"; }
print " />&nbsp;Yes<br /><font class=\"minitext\">(This is only needed if you are experiencing problems. Messages will only appear to system administrators for security reasons.)</font></p>";

print "<p>Alert Colour<br /><input type=\"text\" name=\"settings_alertcolor\" size=\"52\" maxlength=\"6\" value=\"$settings_alertcolor\" /></p>";

print "<p>VAT Rate<br /><input type=\"text\" name=\"settings_vat\" size=\"12\" maxlength=\"6\" value=\"$settings_vat\" />%</p>";

$settings_refresh = $settings_refresh / 60;

print "<p>Refresh Rate (minutes - leave blank for no refresh)<br /><input type=\"text\" name=\"settings_refresh\" class=\"inputbox\" size=\"52\" maxlength=\"3\" value=\"$settings_refresh\" /></p>";

print "<p>Mileage Allowance (&pound;/mile)<br />&pound;<input type=\"text\" name=\"settings_mileage\" class=\"inputbox\" size=\"52\" maxlength=\"12\" value=\"$settings_mileage\" /></p>";

// Now display the system information

print "<h2>System Information</h2>";

$df = disk_free_space("/") / 1000000;
$df = number_format($df);
$dt = disk_total_space("/") / 1000000;
$dt = number_format($dt);
$diskspace = $df."Mb of ".$dt."Mb Total";

print "<p>Disk Space Remaining:<br />$diskspace</p>";

print "<input type=\"hidden\" name=\"action\" value=\"admin_settings\" />";

print "<p><input type=\"submit\" value=\"Update Preferences\" class=\"inputsubmit\" /></p>";

print  "</form>";


?>
