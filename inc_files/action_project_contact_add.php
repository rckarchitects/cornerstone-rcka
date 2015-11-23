<?php

// Clean up results

$contact_proj_role = $_POST[contacts_discipline];
$contact_proj_contact = $_POST[contact_proj_contact];
$contact_proj_date = time();
$contact_proj_project = CleanUp($_POST[contact_proj_project]);
$contact_proj_note = CleanUp($_POST[contact_proj_note]);

// Work out which company the contact CURRENTLY works for

$sql_company = "SELECT contact_company FROM contacts_contactlist WHERE contact_id = '$contact_proj_contact' LIMIT 1";
$result_company = mysql_query($sql_company, $conn) or die(mysql_error());
$array_company = mysql_fetch_array($result_company);
$contact_proj_company = $array_company['contact_company'];

// Construct the MySQL instruction to add these entries to the database

$sql_add = "INSERT INTO intranet_contacts_project (
contact_proj_id,
contact_proj_role,
contact_proj_contact,
contact_proj_date,
contact_proj_project,
contact_proj_note,
contact_proj_company
) values (
'NULL',
'$contact_proj_role',
'$contact_proj_contact',
'$contact_proj_date',
'$contact_proj_project',
'$contact_proj_note',
'$contact_proj_company '
)";

$result = mysql_query($sql_add, $conn) or die(mysql_error());

$actionmessage = "The project contact was added successfully.\n".$sql_company;

$techmessage = $sql_add;


?>
