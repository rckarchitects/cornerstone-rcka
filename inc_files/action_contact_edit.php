<?php


// Check that the required values have been entered, and alter the page to show if these values are invalid

if ($_POST[contact_namefirst] == "") { $alertmessage = "The contact's first name was left empty."; $page = "contacts_edit"; $action = "add"; }
elseif ($_POST[contact_namesecond] == "") { $alertmessage = "The contact's surname name was left empty."; $page = "contacts_edit"; $action = "add"; }

else {

// This determines the page to show once the form submission has been successful

$page = "contacts_view";

// Begin to clean up the $_POST submissions

$contact_id = $_POST[contact_id];
$contact_prefix = $_POST[contact_prefix];
$contact_namefirst = CleanUpNames($_POST[contact_namefirst]);
$contact_namesecond = CleanUpNames($_POST[contact_namesecond]);
$contact_title = $_POST[contact_title];
$contact_company = $_POST[contact_company];
$contact_telephone = CleanUpPhone($_POST[contact_telephone]);
$contact_telephone_home = CleanUpPhone($_POST[contact_telephone_home]);
$contact_fax = CleanUpPhone($_POST[contact_fax]);
$contact_mobile = CleanUpPhone($_POST[contact_mobile]);
$contact_email = CleanUpEmail($_POST[contact_email]);
$contact_sector = $_POST[contact_sector];
$contact_reference = $_POST[contact_reference];
$contact_department = $_POST[contact_department];
$contact_relation = $_POST[contact_relation];
$contact_discipline = $_POST[contact_discipline];
$contact_include = $_POST[contact_include];
$contact_address = CleanUpAddress($_POST[contact_address]);
$contact_city = CleanUp($_POST[contact_city]);
$contact_county = CleanUp($_POST[contact_county]);
$contact_postcode = CleanUpPostcode($_POST[contact_postcode]);
$contact_country = $_POST[contact_country];

// Construct the MySQL instruction to add these entries to the database

$sql_add = "UPDATE contacts_contactlist SET
contact_prefix = '$contact_prefix',
contact_namefirst = '$contact_namefirst',
contact_namesecond = '$contact_namesecond',
contact_title = '$contact_title',
contact_company = '$contact_company',
contact_telephone = '$contact_telephone',
contact_telephone_home = '$contact_telephone_home',
contact_fax = '$contact_fax',
contact_mobile = '$contact_mobile',
contact_email = '$contact_email',
contact_sector = '$contact_sector',
contact_reference = '$contact_reference',
contact_department = '$contact_department',
contact_relation = '$contact_relation',
contact_discipline = '$contact_discipline',
contact_include = '$contact_include',
contact_address = '$contact_address',
contact_city = '$contact_city',
contact_county = '$contact_county',
contact_postcode = '$contact_postcode',
contact_country = '$contact_country'
WHERE contact_id = '$contact_id' LIMIT 1
";

$result = mysql_query($sql_add, $conn) or die(mysql_error());

$actionmessage = "The entry for contact <b>$contact_namefirst $contact_namesecond</b> was updated successfully.";

$techmessage = $sql_add;

}

?>
