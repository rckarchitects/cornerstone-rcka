<?php

print "<h1>Project Analysis</h1>";

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
		
		print "<h2>Project Analysis as of ".TimeFormat(time())."</h2>";

// Table header

		if (mysql_num_rows($result) > 0) {

		print "<table summary=\"Project Fee Analysis\">";
		
		// Output a series of cells containing each of the RIBA stages
				print "<tr>";
				print "<td colspan=\"2\"><strong>Project</strong></td>";
				print "<td><strong>Total Fee</strong></td>";
				print "<td><strong>Duration (Months)</strong></td>";
				print "<td><strong>Invoiced</strong></td>";
				print "<td><strong>Per Month</strong></td>";
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
		$proj_fee_percentage = $array['proj_fee_percentage'];
		$proj_value = $array['proj_value'];

		print "<tr><td><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a></td><td>$proj_name";

		if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
		print "&nbsp;<a href=\"index2.php?page=project_edit&amp;proj_id=$proj_id&amp;status=edit\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a>";
		}
		if ($proj_fee_percentage < 100) {
		$proj_fee_total = MoneyFormat(($proj_fee_percentage/100) * $proj_value);
		} else {
		$proj_fee_total = MoneyFormat($proj_fee_percentage);
		}
		
		print "</td><td style=\"text-align: right;\">$proj_fee_total</td>";
		print "<td>Duration</td>";
		
		$nowtime = time();
		$sql2 = "SELECT invoice_item_novat FROM intranet_timesheet_invoice, intranet_timesheet_invoice_item WHERE invoice_project = $proj_id AND invoice_item_invoice = invoice_id AND invoice_paid > 0 ";
		$result2 = mysql_query($sql2, $conn) or die(mysql_error());
		
		while ($array2 = mysql_fetch_array($result2)) {
			$proj_invoiced = $proj_invoiced + $array2['invoice_item_novat'];
		}
		
		$proj_invoiced = MoneyFormat($proj_invoiced);
		
		print "<td style=\"text-align: right\">$proj_invoiced</td>";

		print "<td></td>";
		
		print "</tr>\n";

		}

		print "</table>";

		} else {

		print "There are no live projects on the system";

		}
		
?>