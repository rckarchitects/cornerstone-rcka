<?php

include("inc_action_functions.php");

if ($_GET[time] != NULL) { $time = CleanNumber($_GET[time]); setcookie("lastdayview", $time);  } else { $time = time(); }

$ip_current = getenv("REMOTE_ADDR"); // get the ip number of the user

if ($_COOKIE[password] == NULL OR $_COOKIE[user] == NULL) {
header("Location: login.php");


} else {

//date_default_timezone_set ('Europe/London');


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
$settings_pdffont = $database_read_array[20];
$settings_timesheetstart = $database_read_array[21];
$settings_timesheetlimit = $database_read_array[22];

if ($user_user_added > $settings_timesheetstart) { $settings_timesheetstart = $user_user_added; }

if ($settings_ip_address != $ip_current AND $settings_ip_lock == 1) { header("Location: wrongip.php"); }

// Assign the database connection settings
$conn = mysql_connect("$database_location", "$database_username", "$database_password");
mysql_select_db("$database_name", $conn);


$sql_ipcheck = "SELECT user_password, user_usertype, user_timesheet_hours, user_id FROM intranet_user_details WHERE user_password = '$_COOKIE[password]' AND user_id = '$_COOKIE[user]' LIMIT 1";
$result_ipcheck = mysql_query($sql_ipcheck, $conn) or die(mysql_error());

$array_ipcheck = mysql_fetch_array($result_ipcheck);
$array_ipcheck['user_password'];

    if ($array_ipcheck['user_password'] != $_COOKIE[password] ) {
    header("location: index.php");
  }
$user_usertype_current = $array_ipcheck['user_usertype'];
$user_id_current = $array_ipcheck['user_id'];
$user_timesheet_hours = $array_ipcheck['user_timesheet_hours'];
}

// Set Locale

setlocale(LC_ALL, 'gb_EN');

// Set the page-wide definitions from the $_GET submissions if they exist

if ($_GET[page] != NULL ) { $page = $_GET[page]; }
if ($_GET[action] != NULL ) { $action = $_GET[action]; }
 
?>
