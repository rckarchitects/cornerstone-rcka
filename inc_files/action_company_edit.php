<?php


// Check that the required values have been entered, and alter the page to show if these values are invalid

if ($_POST[company_name] == "") { $alertmessage = "The company name was left empty."; $page = "company_edit"; $action = "add"; }

else {

// This determines the page to show once the form submission has been successful

$page = "company_view";

// Begin to clean up the $_POST submissions

$company_id = $_POST[company_id];
$company_name = CleanUpNames($_POST[company_name]);
$company_phone = CleanUpPhone($_POST[company_phone]);
$company_fax = CleanUpPhone($_POST[company_fax]);
$company_address = CleanUpAddress($_POST[company_address]);
$company_city = CleanUp($_POST[company_city]);
$company_county = CleanUp($_POST[company_county]);
$company_postcode = CleanUpPostcode($_POST[company_postcode]);
$company_country = $_POST[company_country];
$company_web = $_POST[company_web];
$company_notes = $_POST[company_notes];

// Construct the MySQL instruction to add these entries to the database

$sql_add = "UPDATE contacts_companylist SET
company_name = '$company_name',
company_phone = '$company_phone',
company_fax = '$company_fax',
company_address = '$company_address',
company_city = '$company_city',
company_county = '$company_county',
company_postcode = '$company_postcode',
company_country = '$company_country',
company_web = '$company_web',
company_notes = '$company_notes'
WHERE company_id = '$company_id' LIMIT 1
";

$result = mysql_query($sql_add, $conn) or die(mysql_error());

$actionmessage = "The entry for company <b>$company_name</b> was updated successfully.";

$techmessage = $sql_add;

$company_id = mysql_affected_rows($result);

}

?>
