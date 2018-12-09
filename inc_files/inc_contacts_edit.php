<?php 

// First, determine the title of the page

echo "<h1>Contacts</h1>";

function AddContactForm() {
	
	global $conn;

		if($_GET[status] == "edit") {
		echo "<h2>Edit Contact</h2>";
		} else {
		echo "<h2>Add Contact</h2>";
		}

		ProjectSubMenu(NULL,$user_usertype_current,"contacts_admin",1);

		// Now populate the variables with either the failed results from the $_POST submission or from the database if we're editing an existing project

		if($_GET[status] == "edit") {
		$sql = "SELECT * FROM contacts_contactlist where contact_id = '$_GET[contact_id]'";
		$result = mysql_query($sql, $conn);
		$array = mysql_fetch_array($result);

		$contact_id = $array['contact_id'];
		$contact_prefix = $array['contact_prefix'];
		$contact_namefirst = $array['contact_namefirst'];
		$contact_namesecond = $array['contact_namesecond'];
		$contact_title = $array['contact_title'];
		$contact_company = $array['contact_company'];
		$contact_telephone = $array['contact_telephone'];
		$contact_telephone_home = $array['contact_telephone_home'];
		$contact_fax = $array['contact_fax'];
		$contact_mobile = $array['contact_mobile'];
		$contact_email = $array['contact_email'];
		$contact_sector = $array['contact_sector'];
		$contact_reference = $array['contact_reference'];
		$contact_department = $array['contact_department'];
		$contact_added = $array['contact_added'];
		$contact_relation = $array['contact_relation'];
		$contact_discipline = $array['contact_discipline'];
		$contact_include = $array['contact_include'];
		$contact_address = $array['contact_address'];
		$contact_city = $array['contact_city'];
		$contact_county = $array['contact_county'];
		$contact_postcode = $array['contact_postcode'];
		$contact_country = $array['contact_country'];
		$contact_linkedin = $array['contact_linkedin'];

		if ($contact_title == '0') { $contact_title = NULL; }

		} elseif($_GET[status] == "add") {

		$contact_prefix = $_POST[contact_prefix];
		$contact_namefirst = $_POST[contact_namefirst];
		$contact_namesecond = $_POST[contact_namesecond];
		$contact_title = $_POST[contact_title];
		$contact_company = $_POST[contact_company];
		$contact_telephone = $_POST[contact_telephone];
		$contact_telephone_home = $_POST[contact_telephone_home];
		$contact_fax = $_POST[contact_fax];
		$contact_mobile = $_POST[contact_mobile];
		$contact_email = $_POST[contact_email];
		$contact_sector = $_POST[contact_sector];
		$contact_reference = $_POST[contact_reference];
		$contact_department = $_POST[contact_department];
		$contact_added = $_POST[contact_added];
		$contact_relation = $_POST[contact_relation];
		$contact_discipline = $_POST[contact_discipline];
		$contact_include = $_POST[contact_include];
		$contact_address = $_POST[contact_address];
		$contact_city = $_POST[contact_city];
		$contact_county = $_POST[contact_county];
		$contact_postcode = $_POST[contact_postcode];
		$contact_country = $_POST[contact_country];
		$contact_linkedin = $_POST[contact_linkedin];

		}

			echo "<form method=\"post\" action=\"index2.php?page=contacts_view_detailed\">";

			echo "<div><h3>Contact Name</h3>";
			
			echo "<p class=\"minitext\">Fields marked * are required.</p>";
			
			echo "<p>Prefix<br />";
			include("inc_files/inc_data_contacts_prefixlist.php");
			echo "</p>";
			
			echo "<p>First Name*<br />";
			echo "<input type=\"text\" name=\"contact_namefirst\" class=\"inputbox\" size=\"45\" value=\"$contact_namefirst\" required=\"required\" />";
			echo "</p>";
			echo "<p>Second Name*<br />";
			echo "<input type=\"text\" name=\"contact_namesecond\" class=\"inputbox\" size=\"45\" value=\"$contact_namesecond\" required=\"required\" />";

			echo "<p>Title<br />";
			echo "<input type=\"text\" name=\"contact_title\" class=\"inputbox\" size=\"45\" maxlength=\"100\" value=\"$contact_title\" />";
			echo "</p>";

			echo "</div>";
			echo "<div><h3>Contact Details</h3>";
			
			echo "<p>Company<br />";
			include("inc_data_contacts_companylist.php");
			echo "</p>";

			echo "<p>Direct Telephone (Leave blank for company number)<br />";
			echo "<input type=\"text\" name=\"contact_telephone\" class=\"inputbox\" size=\"24\" value=\"$contact_telephone\" /></p>";
			echo "<p>Direct Fax (Leave blank for company fax)<br />";
			echo "<input type=\"text\" name=\"contact_fax\" class=\"inputbox\" size=\"45\" value=\"$contact_fax\" /></p>";
			echo "<p>Mobile<br />";
			echo "<input type=\"text\" name=\"contact_mobile\" class=\"inputbox\" size=\"45\" value=\"$contact_mobile\" /></p>";
			echo "<p>Contact Email<br />";
			echo "<input type=\"email\" name=\"contact_email\" class=\"inputbox\" size=\"45\" value=\"$contact_email\" /></p>";
			echo "<p>Sector<br />";
			include("inc_data_contacts_sectorlist.php");
			echo "<p>Department<br />";
			echo "<input type=\"text\" name=\"contact_department\" class=\"inputbox\" size=\"45\" value=\"$contact_department\" />";
			
			echo "</div>";
			echo "<div><h3>Home Details</h3>";	

			echo "<p>Home Telephone<br />";
			echo "<input type=\"text\" name=\"contact_telephone_home\" class=\"inputbox\" size=\"45\" value=\"$contact_telephone_home\" />";
			echo "<p>Home Address<br />";
			echo "<textarea class=\"inputbox\" name=\"contact_address\" cols=\"54\" rows=\"4\">$contact_address</textarea></p>";
			echo "<p>Home City<br />";
			echo "<input type=\"text\" name=\"contact_city\" class=\"inputbox\" size=\"45\" value=\"$contact_city\" />";
			echo "<p>Home County<br />";
			echo "<input type=\"text\" name=\"contact_county\" class=\"inputbox\" size=\"45\" value=\"$contact_county\" />";
			
			echo "<p>Home Postcode<br />";
			echo "<input type=\"text\" name=\"contact_postcode\" class=\"inputbox\" size=\"45\" value=\"$contact_postcode\" />";
			
			echo "<p>Country<br />";
			include("inc_data_contacts_countrylist.php");
			echo "</p>";
			
			echo "</div>";
			echo "<div><h3>Additional Information</h3>";
			
			echo "<p>Notes<br />";
			echo "<textarea class=\"inputbox\" name=\"contact_reference\" rows=\"8\" cols=\"54\">$contact_reference</textarea>";

			echo "<p>Discipline<br />";
			include("inc_data_contacts_disciplinelist.php");

			echo "<p>LinkedIn Profile<br />";
			echo "<input type=\"text\" name=\"contact_linkedin\" class=\"inputbox\" size=\"45\" maxlength=\"255\" value=\"$contact_linkedin\" />";
			
			
			echo "<h3>Marketing issue</h3>";
			echo "<p class=\"minitext\">The following options selects whether the contact will appear within marketing issues ($contact_include).</p>";
			echo "<input type=\"radio\" value=\"\" name=\"contact_include\""; if ($contact_include == "" OR $contact_include == NULL OR $contact_include == 0) { echo " checked=\"checked\" "; }
			echo " />&nbsp;None<br />";
			echo "<input type=\"radio\" value=\"1\" name=\"contact_include\""; if ($contact_include == "1") { echo " checked=\"checked\" "; }
			echo " />&nbsp;Email and Hard copy<br />";
			echo "<input type=\"radio\" value=\"2\" name=\"contact_include\""; if ($contact_include == "2") { echo " checked=\"checked\" "; }
			echo " />&nbsp;Email only<br />";
			echo "<input type=\"radio\" value=\"3\" name=\"contact_include\""; if ($contact_include == "3") { echo " checked=\"checked\" "; }
			echo " />&nbsp;Hard Copy only<br />";
			echo "</p>";
			
			echo "</div>";
			echo "<p><input type=\"submit\" class=\"inputsubmit\" value=\"Submit\" /></p>";
			
			// Hidden values
			
		// Hidden values 

		if($_GET[status] == "edit") {
		echo "<input type=\"hidden\" value=\"contact_edit\" name=\"action\" />";
		echo "<input type=\"hidden\" value=\"$contact_id\" name=\"contact_id\" />";
		} else {
		echo "<input type=\"hidden\" value=\"contact_add\" name=\"action\" />";
		}

		echo "</form>";

}

AddContactForm();
