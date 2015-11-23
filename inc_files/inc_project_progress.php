<?php

print "<h1>Project Status</h1>";

		$sql_riba = "SELECT riba_id, riba_letter FROM riba_stages order by riba_order";
		$result_riba = mysql_query($sql_riba, $conn) or die(mysql_error());

		$sql_riba2 = "SELECT riba_order FROM riba_stages order by riba_id";
		$result_riba2 = mysql_query($sql_riba2, $conn) or die(mysql_error());
		
		$array_order = array("");
		
		while ($array_riba2 = mysql_fetch_array($result_riba2)) {
				array_push($array_order, $array_riba2["riba_order"]);
		}
		
print $riba_order_array[1];

// Number of columns
$riba_columns = mysql_num_rows($result_riba);

// Menu

		print "<p class=\"submenu_bar\">";
			if ($user_usertype_current > 4) {
				print "<a href=\"index2.php?\" class=\"submenu_bar\">Project List</a>";
				print "<a href=\"index2.php?page=project_edit&amp;status=add\" class=\"submenu_bar\">Add Project</a>";
			}
		print "</p>";
		
// Page sub title
		
		print "<h2>Live Projects as of ".TimeFormat(time())."</h2>";

// Table header

		if (mysql_num_rows($result) > 0) {

		print "<table summary=\"Lists all of the current, active projects and the currernt stage\">";
		
		// Output a series of cells containing each of the RIBA stages
				print "<tr><td colspan=\"3\"></td>";
					while ($array_riba = mysql_fetch_array($result_riba)) {
						print "<td style=\"width: 20px;\">";
						print $array_riba['riba_letter'];
						print "</td>";
					}
				print "</tr>";			

// List of projects				
		
$sql = "SELECT * FROM intranet_projects WHERE proj_active = 1 AND proj_fee_track = 1 order by proj_num";
$result = mysql_query($sql, $conn) or die(mysql_error());

		while ($array = mysql_fetch_array($result)) {
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$proj_rep_black = $array['proj_rep_black'];
		$proj_client_contact_name = $array['proj_client_contact_name'];
		$proj_contact_namefirst = $array['proj_contact_namefirst'];
		$proj_contact_namesecond = $array['proj_contact_namesecond'];
		$proj_company_name = $array['proj_company_name'];
		$proj_id = $array['proj_id'];
		$proj_riba_begin = $array['proj_riba_begin'];
		$proj_riba = $array['proj_riba'];
		$proj_riba_conclude = $array['proj_riba_conclude'];

		print "<tr><td width=\"20\" class=\"color\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a>";

		print "</td><td width=\"24\" align=\"center\" class=\"color\">";

		if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
		print "<a href=\"index2.php?page=project_edit&amp;proj_id=$proj_id&amp;status=edit\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a>&nbsp;";
		}

		print "</td><td>$proj_name</td>";

		// The cell which shows the current stage
					$sql_riba = "SELECT * FROM riba_stages order by riba_order";
					$result_riba = mysql_query($sql_riba, $conn) or die(mysql_error());
					$riba_style = "width: 18px";
					$proj_begin = $array_order[$proj_riba_begin];
					$proj_conclude = $array_order[$proj_riba_conclude];
					while ($array_cells = mysql_fetch_array($result_riba)) {
					
						if ($array_cells['riba_order'] >= $proj_begin AND $array_cells['riba_order'] <= $proj_conclude) { $riba_style = "width: 18px; background-color: #FBD8D8;"; }
						if ($proj_riba == $array_cells['riba_id'] ) { $riba_style = "width: 18px; background-color: #FC8A8A;"; }
						print "<td style=\"$riba_style\">";
						print "</td>";
						$riba_style = "width: 20px";
					}

		if ($usertype_status > 3 AND $client_contact_name > 0) {
		print "&nbsp;<a href=timesheet_client.php?client_id=$client_contact_name><img src=\"files_images/button_doc.png\" alt=\"Project List for $company_name\" /></a>&nbsp;";
		} elseif ($usertype_status > 3 AND $client_contact_name > 0 ) {
			
		print "&nbsp;<img src=\"files_images/button_doc.png\" alt=\"Client: $contact_namefirst $contact_namesecond, $company_name\" />&nbsp;</td>";
			
		}


		print "</tr>";

		}

		print "</table>";

		} else {

		print "There are no live projects on the system";

		}
		
?>