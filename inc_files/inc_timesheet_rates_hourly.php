<?php

print "<h1>Hourly Rates</h1>";

print "<fieldset><legend>Edit Standard Hourly Rates&nbsp;<a href=\"index2.php?page=help_rates\"><img src=\"images/button_help.png\" alt=\"Click for help\" /></a></legend>";

print "<p>The following list shows the cost per hour of each user on the system. This should exclude any overheads, which are controlled using the \"overhead\" option.</p>";

// Begin the array to show current hourly rates

print "<form action=\"index2.php?page=timesheet_rates_hourly\" method=\"post\">";

	print "<table width=\"100%\">";
	print "<tr><td class=\"color_head\">Name</td><td class=\"color_head\">Hourly Cost</td></tr>";


	$sql = "SELECT * FROM intranet_user_details WHERE user_active = 1 order by user_name_second ";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	if (mysql_num_rows($result) < 1) { print "<tr><td colspan=\"2\">No rates found</td></tr>"; }
	
	$count = 0;
	
	while ($array = mysql_fetch_array($result)) {
	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];
	$user_id = $array['user_id'];
	$user_rate_value = $array['user_rate_value'];

	$sql2 = "SELECT rate_value FROM intranet_timesheet_rate_user WHERE rate_user = '$user_id' LIMIT 1 ";
	$result2 = mysql_query($sql2, $conn) or die(mysql_error());
	$array2 = mysql_fetch_array($result2);
	$rate_value = $array2['rate_value'];

	print "<tr><td>$user_name_first $user_name_second</td><td><input type=\"text\" value=\"$rate_value\" name=\"rate_value[]\" /><input type=\"hidden\" value=\"$user_id\" name=\"user_id[]\" /></td></tr>";
	
	$count++;
	
	}
	
	print "<tr><td></td><td><input type=\"submit\" value=\"Update Rates\" /></td></tr>";

	print "</table>";
	
	print "<input type=\"hidden\" name=\"action\" value=\"timesheet_rate_hourly_edit\" />";
	print "</form>";
	
print "</fieldset>";

?>
