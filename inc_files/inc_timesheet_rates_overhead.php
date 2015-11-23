<?php

print "<h1>Hourly Rates</h1>";

print "<fieldset><legend>Overhead Rates&nbsp;<a href=\"index2.php?pahe=help&amp;subcat=timesheet_overhead\"><img src=\"images/button_help.png\" /></a></legend>";

print "<p>You can add a figure here for overheads to be applied to all hours registered by users on the timesheet system. When they make an entry on the timesheet, this overhead will be added to their hourly rate to produce a total cost for their hours worked.</p>";

// Begin the array to show the last 5 or so overhead rates

    print "<table width=\"100%\">";
	print "<tr><td class=\"color\">Rate</td><td class=\"color\">Date of Change</td></tr>";

	$sql = "SELECT * FROM intranet_timesheet_overhead order by overhead_date DESC LIMIT 10";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
	$overhead_id = $array['overhead_id'];
	$overhead_rate = $array['overhead_rate'];
	$overhead_date = $array['overhead_date'];
	$overhead_date_print = date("l, jS F Y",$overhead_date);


	print "<tr><td class=\"color\" align=\"right\">$overhead_rate</td><td class=\"color\">$overhead_date_print</td></tr>";

	}
	
	print "</table>";

	print "<form action=\"index2.php?page=timesheet_rates_overhead\" method=\"post\"><input type=\"hidden\" name=\"action\" value=\"timesheet_overhead_edit\" /><p>Add New:<br /><input type=\"text\" class=\"inputbox\" name=\"overhead_rate\" /></p><p><input type=\"submit\" class=\"inputsubmit\" value=\"Add\" /></p></form>";


	
print "</fieldset>";


?>
