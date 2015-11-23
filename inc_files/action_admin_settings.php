<?php

$divider = "\n";

// if($user_usertype_current < 4) { header("Location:index2.php"); } else {

CleanUpAddress($update_companyaddress);

$settings_refresh = $_POST[settings_refresh] * 60;


$update_settings = $_POST[database_location].$divider.$_POST[database_username].$divider.$_POST[database_password].$divider.$_POST[database_name].$divider.$_POST[settings_popup_login].$divider.$_POST[settings_popup_newmessage].$divider.$_POST[settings_style].$divider.$_POST[settings_name].$divider.$_POST[settings_companyname].$divider.$_POST[settings_companytelephone].$divider.$_POST[settings_companyfax].$divider.$_POST[settings_companyweb].$divider.$_POST[settings_ip_lock].$divider.$_POST[settings_ip_address].$divider.$_POST[settings_country].$divider.$_POST[settings_showtech].$divider.$_POST[settings_alertcolor].$divider.$_POST[settings_vat].$divider.$settings_refresh.$divider.$_POST[settings_mileage];

$update_companyaddress = $_POST[settings_companyaddress];

$settings_file = "secure/database.inc";
$address_file = "secure/address.inc";

file_put_contents($settings_file,$update_settings);
file_put_contents($address_file,$update_companyaddress);

$actionmessage = "Your preferences have been updated successfully. Any changes will be reflected shortly.";

// }

?>
