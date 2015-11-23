<?php

$settings_companyname = htmlentities($settings_companyname);
$settings_companyaddress = nl2br(htmlentities($settings_companyaddress));

print "<ul class=\"button_left\"><li>$settings_companyname<br />$settings_companyaddress";

if($settings_companytelephone != NULL) { print "<br />T&nbsp;".$settings_companytelephone; }
echo "<br />(or 020 7831 7002)";
if($settings_companyfax != NULL) { print "<br />F&nbsp;".$settings_companyfax; }
if($settings_companyweb != NULL) { print "<br />W&nbsp;<a href=\"http://$settings_companyweb\">".$settings_companyweb."</a>"; }

print "</li></ul>";

?>
