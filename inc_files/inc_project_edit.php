<?php

// First, determine the title of the page

if($_GET[proj_id] != NULL) {
print "<h1>Edit Project</h1>";
} else {
print "<h1>Add New Project</h1>";
}

print "<p class=\"menu_bar\">";
print "<a href=\"#\" onclick=\"itemSwitch(1); return false;\" class=\"menu_tab\">Main</a>";
print "<a href=\"#\" onclick=\"itemSwitch(2); return false;\" class=\"menu_tab\">Client</a>";
print "<a href=\"#\" onclick=\"itemSwitch(3); return false;\" class=\"menu_tab\">Particulars</a>";
print "</p>";

// Now populate the variables with either the failed results from the $_POST submission or from the database if we're editing an existing project

if ($_GET[proj_id] != NULL) { $proj_id = $_GET[proj_id]; }

if ($_GET[status] != NULL) { $status = $_GET[status]; }

if($status == "edit") {
$sql = "SELECT * FROM intranet_projects where proj_id = '$proj_id'";
$result = mysql_query($sql, $conn);
$array = mysql_fetch_array($result);

$proj_num = $array['proj_num'];
$proj_name = $array['proj_name'];
$proj_address_1 = $array['proj_address_1'];
$proj_address_2 = $array['proj_address_2'];
$proj_address_3 = $array['proj_address_3'];
$proj_address_town = $array['proj_address_town'];
$proj_address_county = $array['proj_address_county'];
$proj_address_country = $array['proj_address_country'];
$proj_address_postcode = $array['proj_address_postcode'];
$proj_client_contact_id = $array['proj_client_contact_id'];

$proj_consult_41 = $array['proj_consult_41'];
$proj_consult_42 = $array['proj_consult_42'];
$proj_consult_43 = $array['proj_consult_43'];
$proj_consult_6 = $array['proj_consult_6'];
$proj_consult_7 = $array['proj_consult_7'];
$proj_consult_8 = $array['proj_consult_8'];
$proj_consult_9 = $array['proj_consult_9'];
$proj_consult_10 = $array['proj_consult_10'];
$proj_consult_11 = $array['proj_consult_11'];
$proj_consult_12 = $array['proj_consult_12'];
$proj_consult_13 = $array['proj_consult_13'];
$proj_consult_14 = $array['proj_consult_14'];
$proj_consult_15 = $array['proj_consult_15'];
$proj_consult_16 = $array['proj_consult_16'];
$proj_consult_17 = $array['proj_consult_17'];
$proj_consult_18 = $array['proj_consult_18'];
$proj_consult_19 = $array['proj_consult_19'];

$proj_client_accounts_name = $array['proj_client_accounts_name'];
$proj_client_accounts_phone = $array['proj_client_accounts_phone'];
$proj_client_accounts_fax = $array['proj_client_accounts_fax'];
$proj_client_accounts_email = $array['proj_client_accounts_email'];

$proj_date_proposal = $array['proj_date_proposal'];
$proj_date_appointment = $array['proj_date_appointment'];
$proj_date_start = $array['proj_date_start'];
$proj_date_complete = $array['proj_date_complete'];

$proj_rep_black = $array['proj_rep_black'];
$proj_active = $array['proj_active'];
$proj_desc = $array['proj_desc'];
$proj_riba = $array['proj_riba'];
$proj_riba_begin = $array['proj_riba_begin'];
$proj_riba_conclude = $array['proj_riba_conclude'];
$proj_procure = $array['proj_procure'];
$proj_conc = $array['proj_conc'];
$proj_value = $array['proj_value'];
$proj_value_type = $array['proj_value_type'];
$proj_id = $array['proj_id'];

$proj_active = $array['proj_active'];
$proj_account_track = $array['proj_account_track'];
$proj_fee_track = $array['proj_fee_track'];
$proj_fee_type = $array['proj_fee_type'];
$proj_planning_ref = $array['proj_planning_ref'];
$proj_buildingcontrol_ref = $array['proj_buildingcontrol_ref'];
$proj_fee_percentage = $array['proj_fee_percentage'];

print "<form method=\"post\" action=\"index2.php?page=project_view&amp;status=edit&amp;proj_id=$proj_id\">";

} elseif($status == "add") {

$proj_num = $_POST[proj_num];
$proj_name = $_POST[proj_name];
$proj_address_1 = $_POST[proj_address_1];
$proj_address_2 = $_POST[proj_address_2];
$proj_address_3 = $_POST[proj_address_3];
$proj_address_town = $_POST[proj_address_town];
$proj_address_county = $_POST[proj_address_county];
$proj_address_country = $_POST[proj_address_country];
$proj_address_postcode = $_POST[proj_address_postcode];
$proj_client_contact_id = $_POST[proj_client_contact_id];

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

$proj_client_accounts_name = $_POST[proj_client_accounts_name];
$proj_client_accounts_phone = $_POST[proj_client_accounts_phone];
$proj_client_accounts_fax = $_POST[proj_client_accounts_fax];
$proj_client_accounts_email = $_POST[proj_client_accounts_email];

$proj_rep_black = $_POST[proj_rep_black];
$proj_active = $_POST[proj_active];
$proj_desc = $_POST[proj_desc];
$proj_riba = $_POST[proj_riba];
$proj_riba_begin = $_POST[proj_riba_begin];
$proj_riba_conclude = $_POST[proj_riba_conclude];
$proj_procure = $_POST[proj_procure];
$proj_conc = $_POST[proj_conc];
$proj_value = $_POST[proj_value];
$proj_value_type = $_POST[proj_value_type];
$proj_id = $_POST[proj_id];

$proj_active = $_POST[proj_active];
$proj_account_track = $_POST[proj_account_track];
$proj_fee_track = $_POST[proj_fee_track];
$proj_fee_type = $_POST[proj_fee_type];
$proj_planning_ref = $_POST[proj_planning_ref];
$proj_buildingcontrol_ref = $_POST[proj_buildingcontrol_ref];
$proj_fee_percentage = $_POST[proj_fee_percentage];

// Find the next number in the sequence
if ($_POST[proj_num] == NULL) {
	$sql_newnum = "SELECT proj_num FROM intranet_projects ORDER BY proj_num DESC LIMIT 1";
	$result_newnum = mysql_query($sql_newnum, $conn);
	$array_newnum = mysql_fetch_array($result_newnum);
	$proj_num = $array_newnum['proj_num'] + 1;
	$newnum = "<span class=\"minitext\"><br />(Next available project number automatically added.)</span>";
} else {
	
	unset($newnum);
}


print "<form method=\"post\" action=\"index2.php\">";

}

print "<div id=\"item_switch_1\">";

print "
<h2>Project Details</h2>
<p class=\"minitext\">Fields marked * are required.</p>
<p>Project Number*<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_num\" maxlength=\"8\" value=\"$proj_num\" />$newnum</p>
<p>Project Name*<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_name\" maxlength=\"50\" value=\"$proj_name\" /></p>
<h2>Project Address</h2>
<p>Address Line 1<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_address_1\" maxlength=\"50\" value=\"$proj_address_1\" /></p>
<p>Address Line 2<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_address_2\" maxlength=\"50\" value=\"$proj_address_2\" /></p>
<p>Address Line 3<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_address_3\" maxlength=\"50\" value=\"$proj_address_3\" /></p>
<p>Town<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_address_town\" maxlength=\"50\" value=\"$proj_address_town\" /></p>
<p>County<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_address_county\" maxlength=\"50\" value=\"$proj_address_county\" /></p>
<p>Postcode<br /><input type=\"text\" class=\"inputbox\" size=\"54\" maxlength=\"50\" name=\"proj_address_postcode\" value=\"$proj_address_postcode\" /></p>";

print "<p>Country<br />";
include("inc_files/inc_data_project_address_country.php");
print "</p>";

// Now print the admin only options if applicable

if ($user_usertype_current > 2) {

print "<h2>Project Status</h2>";

print "<p>Should this project be included in cost summaries?<br />";
print "<input type=\"radio\" name=\"proj_account_track\" value=\"1\"";
if ($proj_account_track == "1" OR $proj_account_track == "") {print " checked"; }
print " />&nbsp;Yes<br /><input type=\"radio\" name=\"proj_account_track\" value=\"0\"";
if ($proj_account_track == "0") {print " checked"; }
print " />";
print "&nbsp;No</p>";

print "<p>Project Active?<br />";
print "<input type=\"radio\" name=\"proj_active\" value=\"1\"";
if ($proj_active == "1" OR $proj_active == "") {print " checked"; }
print " />&nbsp;Yes<br /><input type=\"radio\" name=\"proj_active\" value=\"0\"";
if ($proj_active == "0") {print " checked"; }
print " />";
print "&nbsp;No</p>";

print "<p>Fee-Earning Project?<br />";
print "<input type=\"radio\" name=\"proj_fee_track\" value=\"1\"";
if ($proj_fee_track == "1" OR $proj_fee_track == "") {print " checked"; }
print " />&nbsp;Yes<br /><input type=\"radio\" name=\"proj_fee_track\" value=\"0\"";
if ($proj_fee_track == "0") {print " checked"; }
print " />";
print "&nbsp;No</p>";

print "<p>Project Leader<br />";
include ("inc_files/inc_data_project_leader.php");
print "</p>";

}

print "<p><input type=\"submit\" value=\"Submit\" class=\"inputsubmit\" /></p>";

print "</div>";

// Client details

print "<div id=\"item_switch_2\">";

print "<h2>Client Details</h2>
<p>Client Name (1.0)<br />";
include("inc_files/inc_data_project_contacts.php");
print "</p>
<p>Accounts Name<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_client_accounts_name\" maxlength=\"50\" value=\"$proj_client_accounts_name\" /></p>
<p>Accounts Phone<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_client_accounts_phone\" maxlength=\"50\" value=\"$proj_client_accounts_phone\" /></p>
<p>Accounts Fax<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_client_accounts_fax\" maxlength=\"50\" value=\"$proj_client_accounts_fax\" /></p>
<p>Accounts Email<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_client_accounts_email\" maxlength=\"50\" value=\"$proj_client_accounts_email\" /></p>


";

print "<p><input type=\"submit\" value=\"Submit\" class=\"inputsubmit\" /></p>";

print "</div><div id=\"item_switch_3\">";

print "

<h2>Project Particulars</h2>

<p>Planning Reference Number<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_planning_ref\" maxlength=\"50\" value=\"$proj_planning_ref\" /></p>

<p>Building Control Reference Number<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_buildingcontrol_ref\" maxlength=\"50\" value=\"$proj_buildingcontrol_ref\" /></p>

<p>Proposal Issued</p>";
include("inc_files/inc_data_project_date_proposal.php");

print "<p>Date of Appointment</p>";
include("inc_files/inc_data_project_date_appointment.php");

print "<p>Project Start</p>";
include("inc_files/inc_data_project_timestart.php");

print "<p>Project Complete</p>";
include("inc_files/inc_data_project_timecomplete.php");

print "<p>Project Description<br /><textarea name=\"proj_desc\" class=\"inputbox\" cols=\"54\" rows=\"8\">$proj_desc</textarea></p>
<p>Procurement Method<br />";
include("inc_files/inc_data_project_procurement.php"); print "</p>";

print "<p>Contract Value (approx.)<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_value\" maxlength=\"12\" value=\"$proj_value\" /></p>";

print "<p>Total Fee (all stages)<br /><input type=\"text\" class=\"inputbox\" size=\"54\" name=\"proj_fee_percentage\" maxlength=\"12\" value=\"$proj_fee_percentage\" /></p>";

print "<p>Value Type<br />";

include("inc_files/inc_data_project_value.php"); print "</p>";

// We're omitting this (9 July 2011) to switch to bespoke project stages

// print "<p>Beginning RIBA Stage<br />"; include("inc_files/inc_data_project_dropdown_riba_begin.php"); print "</p>";
// print "<p>Current RIBA Stage<br />"; include("inc_files/inc_data_project_dropdown_riba_stages.php"); print "</p>";
// print "<p>Concluding RIBA Stage<br />"; include("inc_files/inc_data_project_dropdown_riba_conclude.php"); print "</p>";

// print "<p><input type=\"submit\" value=\"Submit\" class=\"inputsubmit\" /></p>";

print "</div>";

// Hidden values 

if($status == "add") {
print "<input type=\"hidden\" value=\"project_add\" name=\"action\" />";
} elseif($status == "edit") {
print "<input type=\"hidden\" value=\"project_edit\" name=\"action\" />";
print "<input type=\"hidden\" value=\"$proj_id\" name=\"proj_id\" />";
}

echo "</form>";


print "
		<script type=\"text/javascript\">
		document.getElementById(\"item_switch_1\").style.display = \"$main_show\";
		document.getElementById(\"item_switch_2\").style.display = \"none\";
		document.getElementById(\"item_switch_3\").style.display = \"none\";
		</script>
";




?>
