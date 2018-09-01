<?php 

$action = $_GET[status];

// First, determine the title of the page

echo "<h1>Contacts</h1>";

if($action == "add") {
echo "<h2>Add Company</h2>";
} elseif($action == "edit") {
echo "<h2>Edit Company</h2>";
}

ProjectSubMenu(NULL,$user_usertype_current,"contacts_admin",1);

// Now populate the variables with either the failed results from the $_POST submission or from the database if we're editing an existing project

if($action == "edit") {
$sql = "SELECT * FROM contacts_companylist where company_id = '$_GET[company_id]'";
$result = mysql_query($sql, $conn);
$array = mysql_fetch_array($result);

$company_id = $array['company_id'];
$company_name = $array['company_name'];
$company_address = $array['company_address'];
$company_city = $array['company_city'];
$company_county = $array['company_county'];
$company_postcode = $array['company_postcode'];
$company_web = ltrim ($array['company_web'] , "http://");
$company_phone = $array['company_phone'];
$company_fax = $array['company_fax'];
$company_notes = $array['company_notes'];

} elseif($action == "add") {

$company_id = $_POST[company_id];
$company_name = $_POST[company_name];
$company_address = $_POST[company_address];
$company_city = $_POST[company_city];
$company_county = $_POST[company_county];
$company_postcode = $_POST[company_postcode];
$company_web = $_POST[company_web];
$company_phone = $_POST[company_phone];
$company_fax = $_POST[company_fax];
$company_notes = $_POST[company_notes];

}

	echo "<form method=\"post\" action=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">";

	echo "<fieldset><legend>Company Name</legend>";
	
	echo "<p class=\"minitext\">Fields marked * are required.</p>";
	
	echo "<p>Company Name*<br />";
	echo "<input type=\"text\" name=\"company_name\" class=\"inputbox\" size=\"45\" value=\"$company_name\" />";
	echo "</p>";
	echo "</fieldset>";
	
	echo "<fieldset><legend>Company Address</legend>";

	echo "<p>Company Telephone<br />";
	echo "<input type=\"text\" name=\"company_phone\" class=\"inputbox\" size=\"24\" value=\"$company_phone\" /></p>";
	echo "<p>Company Fax<br />";
	echo "<input type=\"text\" name=\"company_fax\" class=\"inputbox\" size=\"45\" value=\"$company_fax\" /></p>";
	
	echo "<p>Company Address<br />";
	echo "<textarea class=\"inputbox\" name=\"company_address\" cols=\"54\" rows=\"4\">$company_address</textarea></p>";
	echo "<p>Company City<br />";
	echo "<input type=\"text\" name=\"company_city\" class=\"inputbox\" size=\"45\" value=\"$company_city\" />";
	echo "<p>Company County<br />";
	echo "<input type=\"text\" name=\"company_county\" class=\"inputbox\" size=\"45\" value=\"$company_county\" />";
	
	echo "<p>Company Postcode<br />";
	echo "<input type=\"text\" name=\"company_postcode\" class=\"inputbox\" size=\"45\" value=\"$company_postcode\" />";
	
	echo "<p>Company Country<br />";
	include("inc_data_company_countrylist.php");
	echo "</p>";
	
	echo "</fieldset>";
	echo "<fieldset><legend>Additional Information</legend>";

	echo "<p>Company Web Site<br />";
	echo "http://<input type=\"text\" name=\"company_web\" class=\"inputbox\" size=\"45\" value=\"$company_web\" />";
	echo "<p>Notes<br /><textarea name=\"company_notes\" rows=\"8\" cols=\"60\">$company_notes</textarea></p>";
	
	echo "</fieldset>";
	echo "<p><input type=\"submit\" class=\"inputsubmit\" value=\"Submit\" /></p>";
	
	// Hidden values
	
// Hidden values 

if($_GET[status] == "add") {
echo "<input type=\"hidden\" value=\"company_add\" name=\"action\" />";
} elseif($_GET[status] == "edit") {
echo "<input type=\"hidden\" value=\"company_edit\" name=\"action\" />";
echo "<input type=\"hidden\" value=\"$company_id\" name=\"company_id\" />";
}

echo "</form>";