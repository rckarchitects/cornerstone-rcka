<?php

print "<h1>Hourly Rates</h1>";


print "<fieldset><legend>Edit Project Rate Types&nbsp;<a href=\"index2.php?page=help_rates\"><img src=\"images/button_help.png\" alt=\"Click for help\" /></a></legend>";

print "<p>The following list shows the various rate types which are applicable to each project on the system. Use this form to update existing or add new rate types.</p>";


// Begin the array to show current hourly rates


print "<form action=\"index2.php?page=timesheet_rates_project\">";


	print "<table width=\"100%\">";
	print "<tr><td class=\"color_head\">Rate Type</td><td class=\"color_head\">Hourly Cost</td></tr>";


	$sql3 = "SELECT * FROM intranet_timesheet_rate_type";
	$result3 = mysql_query($sql3, $conn) or die(mysql_error());

	if (mysql_num_rows($result3) < 1) { print "<tr><td class=\"color\" colspan=\"2\">No rates found</td></tr>"; }
	
	$count = 0;
	
	while ($array3 = mysql_fetch_array($result3)) {
	  
	$rate_id = $array3['rate_id'];
	$rate_name = $array3['rate_name'];
	$rate_value = $array3['rate_value'];

	print "<tr><td class=\"color\">$rate_name</td><td class=\"color\" align=\"right\"><input type=\"text\" class=\"inputbox\" value=\"$rate_value\" name=\"rate_value[$count]\" /></td></tr>";
	
	$count++;

	}
	
	print "<tr><td></td><td class=\"color\"><input type=\"submit\" value=\"Update Rates\" class=\"inputbox\" /></td></tr>";

	print "</table>";
	
	print "</form>";
	
print "</fieldset>";

?>
