<?php

// Check that the required values have been entered, and alter the page to show if these values are invalid

if ($_POST[proj_num] == "") { $alertmessage = "The project number was left empty."; $page = "project_edit"; $action = "edit"; $proj_id = $_POST[proj_id]; }
elseif ($_POST[proj_name] == "") { $alertmessage = "The project name was left empty."; $page = "project_edit"; $action = "edit"; $proj_id = $_POST[proj_id]; }

else {

// This determines the page to show once the form submission has been successful

$page = "project_view&amp;proj_id=$_POST[proj_id]";

// Calculate the project start and completion dates from the pull-down lists

	if ($_POST[proj_date_start_day] > 0  AND $_POST[proj_date_start_month] > 0 AND $_POST[proj_date_start_year] > 0 ) {
		if (checkdate($_POST[proj_date_start_month], $_POST[proj_date_start_day], $_POST[proj_date_start_year]) == "1") {
			$proj_date_start = mktime(12, 0, 0, $_POST[proj_date_start_month], $_POST[proj_date_start_day], $_POST[proj_date_start_year]);
		}
		} else {
		unset($proj_date_start);
		}

	if ($_POST[proj_date_complete_day] > 0  AND $_POST[proj_date_complete_month] > 0 AND $_POST[proj_date_complete_year] > 0 ) {
		if (checkdate($_POST[proj_date_complete_month], $_POST[proj_date_complete_day], $_POST[proj_date_complete_year]) == "1") {
			$proj_date_complete = mktime(12, 0, 0, $_POST[proj_date_complete_month], $_POST[proj_date_complete_day], $_POST[proj_date_complete_year]);
		}
		} else {
		unset($proj_date_compete);
}

	if ($_POST[proj_date_proposal_day] > 0  AND $_POST[proj_date_proposal_month] > 0 AND $_POST[proj_date_proposal_year] > 0 ) {
		if (checkdate($_POST[proj_date_proposal_month], $_POST[proj_date_proposal_day], $_POST[proj_date_proposal_year]) == "1") {
			$proj_date_proposal = mktime(12, 0, 0, $_POST[proj_date_proposal_month], $_POST[proj_date_proposal_day], $_POST[proj_date_proposal_year]);
		}
		} else {
		unset($proj_date_proposal);
}

	if ($_POST[proj_date_appointment_day] > 0  AND $_POST[proj_date_appointment_month] > 0 AND $_POST[proj_date_appointment_year] > 0 ) {
		if (checkdate($_POST[proj_date_appointment_month], $_POST[proj_date_appointment_day], $_POST[proj_date_appointment_year]) == "1") {
			$proj_date_appointment = mktime(12, 0, 0, $_POST[proj_date_appointment_month], $_POST[proj_date_appointment_day], $_POST[proj_date_appointment_year]);
		}
		} else {
		unset($proj_date_appointment);
}

// Begin to clean up the $_POST submissions

$proj_num = CleanUp($_POST[proj_num]);
$proj_name = CleanUp($_POST[proj_name]);
$proj_address_1 = CleanUpAddress($_POST[proj_address_1]);
$proj_address_2 = CleanUpAddress($_POST[proj_address_2]);
$proj_address_3 = CleanUpAddress($_POST[proj_address_3]);
$proj_address_town = CleanUpAddress($_POST[proj_address_town]);
$proj_address_county = CleanUpAddress($_POST[proj_address_county]);
$proj_address_country = CleanUpAddress($_POST[proj_address_country]);
$proj_address_postcode = CleanUp($_POST[proj_address_postcode]);
$proj_client_contact_id = $_POST[proj_client_contact_id];
$proj_client_accounts_name = CleanUp($_POST[proj_client_accounts_name]);
$proj_client_accounts_phone = CleanUpPhone($_POST[proj_client_accounts_phone]);
$proj_client_accounts_fax = CleanUpPhone($_POST[proj_client_accounts_fax]);
$proj_client_accounts_email = CleanUpEmail($_POST[proj_client_accounts_email]);
$proj_rep_black = $_POST[proj_rep_black];
$proj_active = $_POST[proj_active];
$proj_desc = CleanUp($_POST[proj_desc]);
//$proj_riba = $_POST[proj_riba];
$proj_riba_begin = $_POST[proj_riba_begin];
$proj_riba_conclude = $_POST[proj_riba_conclude];
$proj_procure = $_POST[proj_procure];
$proj_conc = $_POST[proj_conc];
$proj_value = CleanUp($_POST[proj_value]);
$proj_value_type = $_POST[proj_value_type];
$proj_consult_41 = $_POST[proj_consult_41];
$proj_consult_42 = $_POST[proj_consult_42];
$proj_consult_43 = $_POST[proj_consult_43];
$proj_consult_6 = $_POST[proj_consult_6];
$proj_consult_7 = $_POST[proj_consult_7];
$proj_consult_8 = $_POST[proj_consult_8];
$proj_consult_9 = $_POST[proj_consult_9];
$proj_consult_10 = $_POST[proj_consult_10];
$proj_consult_11 = $_POST[proj_consult_11];
$proj_consult_12 = $_POST[proj_consult_12];
$proj_consult_13 = $_POST[proj_consult_13];
$proj_consult_14 = $_POST[proj_consult_14];
$proj_consult_15 = $_POST[proj_consult_15];
$proj_consult_16 = $_POST[proj_consult_16];
$proj_consult_17 = $_POST[proj_consult_17];
$proj_consult_18 = $_POST[proj_consult_18];
$proj_consult_19 = $_POST[proj_consult_19];
$proj_consult_20 = $_POST[proj_consult_20];
$proj_account_track = $_POST[proj_account_track];
$proj_fee_track = $_POST[proj_fee_track];
$proj_fee_type = $_POST[proj_fee_type];
$proj_planning_ref = $_POST[proj_planning_ref];
$proj_buildingcontrol_ref = $_POST[proj_buildingcontrol_ref];
$proj_fee_percentage = $_POST[proj_fee_percentage];

// Construct the MySQL instruction to add these entries to the database

$sql_add = "UPDATE intranet_projects SET
proj_num = '$proj_num',
proj_name = '$proj_name',
proj_address_1 = '$proj_address_1',
proj_address_2 = '$proj_address_2',
proj_address_3 = '$proj_address_3',
proj_address_town = '$proj_address_town',
proj_address_county = '$proj_address_county',
proj_address_country = '$proj_address_country',
proj_address_postcode = '$proj_address_postcode',
proj_client_contact_id = '$proj_client_contact_id',
proj_client_accounts_name = '$proj_client_accounts_name',
proj_client_accounts_phone = '$proj_client_accounts_phone',
proj_client_accounts_fax = '$proj_client_accounts_fax',
proj_client_accounts_email = '$proj_client_accounts_email',
proj_date_proposal = '$proj_date_proposal',
proj_date_appointment = '$proj_date_appointment',
proj_date_start = '$proj_date_start',
proj_date_complete = '$proj_date_complete',
proj_desc = '$proj_desc',
proj_riba_begin = '$proj_riba_begin',
proj_riba_conclude = '$proj_riba_conclude',
proj_procure = '$proj_procure',
proj_conc = '$proj_conc',
proj_value = '$proj_value',
proj_value_type = '$proj_value_type',
proj_consult_41 = '$proj_consult_41',
proj_consult_42 = '$proj_consult_42',
proj_consult_43 = '$proj_consult_43',
proj_consult_6 = '$proj_consult_6',
proj_consult_7 = '$proj_consult_7',
proj_consult_8 = '$proj_consult_8',
proj_consult_9 = '$proj_consult_9',
proj_consult_10 = '$proj_consult_10',
proj_consult_11 = '$proj_consult_11',
proj_consult_12 = '$proj_consult_12',
proj_consult_13 = '$proj_consult_13',
proj_consult_14 = '$proj_consult_14',
proj_consult_15 = '$proj_consult_15',
proj_consult_16 = '$proj_consult_16',
proj_consult_17 = '$proj_consult_17',
proj_consult_18 = '$proj_consult_18',
proj_consult_19 = '$proj_consult_19',
proj_tenant_1 = '$proj_tenant_1',
proj_planning_ref = '$proj_planning_ref',
proj_buildingcontrol_ref = '$proj_buildingcontrol_ref'
WHERE proj_id = '$_POST[proj_id]'";

$result = mysql_query($sql_add, $conn) or die(mysql_error());

if ($user_usertype_current > 3) {

$sql_add2 = "UPDATE intranet_projects SET
proj_rep_black = '$proj_rep_black',
proj_active = '$proj_active',
proj_account_track = '$proj_account_track',
proj_fee_track = '$proj_fee_track',
proj_fee_type = '$proj_fee_type',
proj_fee_percentage = '$proj_fee_percentage'
WHERE proj_id = '$_POST[proj_id]'";
$result2 = mysql_query($sql_add2, $conn) or die(mysql_error());

}

$actionmessage = "The entry for project <b>$proj_num $proj_name</b> was updated successfully.";

$techmessage = $sql_add."<br />".$result."<br />".$sql_add2."<br/ >".$result2;

}

?>
