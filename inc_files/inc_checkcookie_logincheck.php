<?php

include("inc_action_functions.php5");

// Get the database variables from file

$database_read = file_get_contents("secure/database.inc");
$database_read_array = explode("\n", $database_read);

$settings_companyaddress = file_get_contents("secure/address.inc");

$database_location = $database_read_array[0];
$database_username = $database_read_array[1];
$database_password = $database_read_array[2];
$database_name = $database_read_array[3];
$settings_popup_login = $database_read_array[4];
$settings_popup_newmessage = $database_read_array[5];
$settings_style = $database_read_array[6];
$settings_name = $database_read_array[7];
$settings_companyname = $database_read_array[8];
$settings_companytelephone = $database_read_array[9];
$settings_companyfax = $database_read_array[10];
$settings_companyweb = $database_read_array[11];
$settings_ip_lock = $database_read_array[12];
$settings_ip_address = $database_read_array[13];
$settings_country = $database_read_array[14];
$settings_showtech = $database_read_array[15];
$settings_alertcolor = $database_read_array[16];
$settings_vat = $database_read_array[17];
$settings_refresh = $database_read_array[18];
$settings_mileage = $database_read_array[19];

$conn = mysql_connect("$database_location", "$database_username", "$database_password");
mysql_select_db("$database_name", $conn);

 
?>
