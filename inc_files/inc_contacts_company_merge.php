<?php

echo "<h1>Contacts</h1>";

if ($user_usertype_current > 3) {

if (intval ($_POST[company_old]) > 0 && intval ($_POST[company_new]) > 0) {
	
	$company_old = intval ($_POST[company_old]);
	$company_new = intval ($_POST[company_new]);
	
	echo "<ol>";
	
	if ($company_old != $company_new) {
	
	$sql_merge_1 = "UPDATE contacts_contactlist SET contact_company = $company_new WHERE contact_company = $company_old";
	$result_merge_1 = mysql_query($sql_merge_1, $conn) or die(mysql_error());
	echo "<li>Updating Contacts: " . mysql_affected_rows() . " row(s) updated</li>";
	
	$sql_merge_2 = "UPDATE intranet_drawings_issued SET issue_company = $company_new WHERE issue_company = $company_old";
	$result_merge_2 = mysql_query($sql_merge_2, $conn) or die(mysql_error());
	echo "<li>Updating Drawing Issues: " . mysql_affected_rows() . " row(s) updated</li>";
	
	$sql_merge_3 = "UPDATE intranet_contacts_project SET contact_proj_company = $company_new WHERE contact_proj_company = $company_old";
	$result_merge_3 = mysql_query($sql_merge_3, $conn) or die(mysql_error());
	echo "<li>Updating Project Contacts: " . mysql_affected_rows() . " row(s) updated</li>";
	
	$sql_merge_4 = "UPDATE intranet_projects_planning SET condition_responsibility = $company_new WHERE condition_responsibility = $company_old";
	$result_merge_4 = mysql_query($sql_merge_4, $conn) or die(mysql_error());
	echo "<li>Updating Planning Conditions: " . mysql_affected_rows() . " row(s) updated</li>";
	
	}
	
	$actionmessage = "<p>Company ID. " . $company_old . " has been merged with company ID <a href=\"index2.php?page=contacts_company_view&amp;company_id=" . $company_new . "\">" . $company_new . "</a>";
	
	if ($_POST[delete_old] == "yes") {
		$sql_merge_5 = "DELETE FROM contacts_companylist WHERE company_id = $company_old LIMIT 1";
		$result_merge_5 = mysql_query($sql_merge_5, $conn) or die(mysql_error());
		echo "<li>Removing Obsolete Company: " . mysql_affected_rows() . " row(s) deleted</li>";
		$actionmessage = $actionmessage . ", and company ID " . $company_old . " has been deleted.";
	} else {
		$actionmessage = $actionmessage . ".";
	}
	
	$actionmessage = $actionmessage . "</p>";
	
	echo "</ol>";
	
	AlertBoxInsert($_COOKIE[user],"Company Merged",$actionmessage,$company_new,0,0);
	
}



echo "<h2>Merge Companies</h2>";

ProjectSubMenu(NULL,$user_usertype_current,"contacts_admin",1);

echo "<p>You can use the following form to merge two separate companies. All entries from the left-hand column will be copied to the right-hand column, and the entry from the left-hand column will be deleted if you wish.</p><p>Use this with caution.</p>";

echo "<form method=\"post\" action=\"index2.php?page=contacts_company_merge\">";

echo "<h3>From...</h3>";

echo "<select name=\"company_old\">";

SelectCompany();

echo "</select>";

echo "<h3>To...</h3>";

echo "<select name=\"company_new\">";

SelectCompany();

echo "</select>";



echo "<p><input type=\"checkbox\" name=\"delete_old\" value=\"yes\" />&nbsp;Delete old company?</p>";

echo "<p><input type=\"submit\" /></p>";

echo "</form>";

} else {
	
	echo "<p>You do not have sufficient rights to access this page.</p>";
	
}


?>