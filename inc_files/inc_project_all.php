<?php

$sql = "SELECT * FROM intranet_projects order by proj_num";
$result = mysql_query($sql, $conn) or die(mysql_error());

$today = TimeFormat(time());

print "<h1>$today</h1>";

// Add another menu if the project total is greater than 10

		print "<p class=\"submenu_bar\">";
			if ($user_usertype_current > 4) {
				print "<a href=\"index2.php?page=project_edit&amp;status=add\" class=\"submenu_bar\">Add Project</a>";
			}
		print "<a href=\"index2.php\" class=\"submenu_bar\">Current Projects</a>";
			if ($user_usertype_current > 3) {
		print "<a href=\"projectlist_pdf.php\" class=\"submenu_bar\">PDF project list</a>";
			}
		print "</p>";
		
		print "<h2>All Projects</h2>";


if (mysql_num_rows($result) > 0) {

print "<table summary=\"List of all projects\">";

while ($array = mysql_fetch_array($result)) {
$proj_num = $array['proj_num'];
$proj_name = $array['proj_name'];
$proj_rep_black = $array['proj_rep_black'];
$proj_client_contact_name = $array['proj_client_contact_name'];
$proj_contact_namefirst = $array['proj_contact_namefirst'];
$proj_contact_namesecond = $array['proj_contact_namesecond'];
$proj_company_name = $array['proj_company_name'];
$proj_id = $array['proj_id'];
$proj_fee_track = $array['proj_fee_track'];




print "<tr><td width=\"20\" class=\"color\">";

if ($proj_fee_track > 0) {
print "<a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a>";
} else {
print $proj_num;
}

print "</td><td width=\"24\" align=\"center\" class=\"color\">";

if ($user_usertype_current > 4 OR $user_id_current == $proj_rep_black) {
print "<a href=\"index2.php?page=project_edit&amp;status=edit&amp;proj_id=$proj_id\"><img src=\"images/button_edit.png\" alt=\"Edit Project\" /></a>&nbsp;";
}

print "</td><td class=\"color\">$proj_name</td><td class=\"color\" align=\"center\" valign=\"middle\">";

if ($proj_fee_track > 0) {
print "<a href=\"pdf_project_directory.php?proj_id=$proj_id\" class=\"imagebutton\"><img src=\"images/button_pdf.png\" alt=\"Project Directory\" /></a>";
}

if ($user_usertype_current > 4 AND $client_contact_name > 0) {
print "&nbsp;<a href=timesheet_client.php?client_id=$client_contact_name><img src=\"files_images/button_proj_info.gif\" alt=\"Project List for $company_name\"></a>&nbsp;";
} elseif ($user_usertype_current > 4 AND $client_contact_name > 0) {
	
print "&nbsp;<img src=files_images/button_proj_info.gif border=0 alt=\"Client: $contact_namefirst $contact_namesecond, $company_name\">&nbsp;</td>";
	
}


print "</tr>";

}

print "</table>";

} else {

print "There are no live projects on the system";

}

?>

