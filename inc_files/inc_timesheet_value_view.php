<?php

// First List the active projects

$sql = "SELECT * FROM intranet_projects WHERE proj_active = 1 AND proj_fee_track = 1 order by proj_num";
$result = mysql_query($sql, $conn) or die(mysql_error());

print "<h1>Projects</h1>";

// Menu

print "<p class=\"submenu_bar\">";
	if ($user_usertype_current > 4) {
		print "<a href=\"index2.php?page=project_edit&amp;status=add\" class=\"submenu_bar\">Add Project</a>";
	}
print "<a href=\"index2.php?page=project_all\" class=\"submenu_bar\">View All</a>";
	if ($user_usertype_current > 3) {
print "<a href=\"projectlist_pdf.php\" class=\"submenu_bar\">PDF project list</a>";
	}
print "</p>";

print "<h2>Contract Values</h2>";


if (mysql_num_rows($result) > 0) {

$proj_value_total = 0;

print "<table summary=\"Lists the contract values of all current projects\">";

print "<tr><td colspan=\"3\"><strong>Project</strong></td><td><strong>Contract Value</strong></td></tr>";

while ($array = mysql_fetch_array($result)) {
$proj_num = $array['proj_num'];
$proj_name = $array['proj_name'];
$proj_id = $array['proj_id'];
$proj_value = $array['proj_value'];

print "<tr><td width=\"20\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a>";

print "</td><td width=\"24\" style=\"text-align: center\">";

if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
print "<a href=\"index2.php?page=project_edit&amp;proj_id=$proj_id&amp;status=edit\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a>&nbsp;";
}

print "</td>
<td>$proj_name</td>
<td style=\"text-align: right\">".MoneyFormat($proj_value)."</td>
";

// Keep a running total

$proj_value_total = $proj_value_total + $proj_value;


	if ($usertype_status > 3 AND $client_contact_name > 0) {
			print "&nbsp;<a href=timesheet_client.php?client_id=$client_contact_name><img src=\"files_images/button_doc.png\" alt=\"Project List for $company_name\" /></a>&nbsp;";
	} elseif ($usertype_status > 3 AND $client_contact_name > 0 ) {
			print "&nbsp;<img src=\"files_images/button_doc.png\" alt=\"Client: $contact_namefirst $contact_namesecond, $company_name\" />&nbsp;</td>";
	}


print "</tr>";

}

print "<tr><td colspan=\"3\"><strong>TOTAL</strong></td><td style=\"text-align: right\"><strong>".MoneyFormat($proj_value_total)."</strong></td></tr>";

print "</table>";

} else {

print "There are no live projects on the system";

}

?>

