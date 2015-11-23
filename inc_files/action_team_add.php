<?php

// Check that the required values have been entered, and alter the page to show if these values are invalid

if ($_POST[user_username] == "") { $alertmessage = "The username was left empty."; $page= "team_edit"; $status = "add"; }
if ($_POST[user_password_1] == "") { $alertmessage = "The password was left empty."; $page= "team_edit"; $status = "add"; }
if ($_POST[user_password_2] == "") { $alertmessage = "The password was left empty."; $page= "team_edit"; $status = "add"; }
if ($_POST[user_password_1] != $_POST[user_password_2]) { $alertmessage = "The passwords do not match."; $page= "team_edit"; $status = "add"; }

else {

// This determines the page to show once the form submission has been successful

$status = "team_add";

// Begin to clean up the $_POST submissions

$user_password = md5(CleanUp($_POST[user_password_1]));
$user_username = CleanUp($_POST[user_username]);
$user_address_1 = CleanUpAddress($_POST[user_address_1]);
$user_address_2 = CleanUpAddress($_POST[user_address_2]);
$user_address_3 = CleanUpAddress($_POST[user_address_3]);
$user_address_town = CleanUpAddress($_POST[user_address_town]);
$user_address_postcode = CleanUpPostcode($_POST[user_address_postcode]);
$user_address_county = CleanUpAddress($_POST[user_address_county]);
$user_name_first = CleanUpNames($_POST[user_name_first]);
$user_name_second = CleanUpNames($_POST[user_name_second]);
$user_num_extension = CleanUp($_POST[user_num_extension]);
$user_num_home = CleanUpPhone($_POST[user_num_home]);
$user_num_mob = CleanUpPhone($_POST[user_num_mob]);
$user_email = CleanUpEmail($_POST[user_email]);
$user_user_rate = CleanUp($_POST[user_user_rate]);
$user_user_added = time();
$user_timesheet = CleanUp($_POST[user_timesheet]);
$user_holidays = CleanUp($_POST[user_holidays]);
$user_active = CleanUp($_POST[user_active]);
$user_usertype = CleanUp($_POST[user_usertype]);

// Construct the MySQL instruction to add these entries to the database

$sql_add = "INSERT INTO intranet_user_details (
user_id,
user_password,
user_address_county,
user_address_postcode,
user_address_town,
user_address_3,
user_address_2,
user_address_1,
user_name_first,
user_name_second,
user_num_extension,
user_num_mob,
user_num_home,
user_email,
user_usertype,
user_active,
user_username,
user_user_rate,
user_user_added,
user_user_timesheet,
user_holidays
) values (
'NULL',
'$user_password',
'$user_address_county',
'$user_address_postcode',
'$user_address_town',
'$user_address_3',
'$user_address_2',
'$user_address_1',
'$user_name_first',
'$user_name_second',
'$user_num_extension',
'$user_num_mob',
'$user_num_home',
'$user_email',
'$user_usertype',
'$user_active',
'$user_username',
'$user_user_rate',
'$user_user_added',
'$user_user_timesheet',
'$user_holidays'
)";

print $sql_add;

$result = mysql_query($sql_add, $conn) or die(mysql_error());

$actionmessage = "User added successfully.";

$techmessage = $sql_add;

}

?>
