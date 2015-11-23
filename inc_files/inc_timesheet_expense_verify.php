<?php

if ($user_usertype_current < 3) { print "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; } else {

print "<h1>Verify Expenses</h1>";

$date_verified = time();
$counter = 0;
$total = 0;

$array_id = $_POST['ts_expense_verified'];

print "<p>The following expenses have been verified for ".TimeFormat($date_verified)."</p>";

print "<p><a href=\"index2.php?page=timesheet_expense_list\">Click here</a> to return to the expenses list.</p>";

print "<table summary=\"List of expenses to verify\">";

while ($counter < count($array_id)) {
		
				$sql = "SELECT * FROM intranet_timesheet_expense, intranet_projects WHERE ts_expense_id = ".$array_id[$counter]." AND proj_id = ts_expense_project LIMIT 1";
				$sql2 = "UPDATE intranet_timesheet_expense SET ts_expense_verified = '$date_verified' WHERE ts_expense_id = ".$array_id[$counter]." LIMIT 1";
				$result = mysql_query($sql, $conn) or die(mysql_error());
				$result2 = mysql_query($sql2, $conn) or die(mysql_error());
				$array_sql = mysql_fetch_array($result);
				$ts_expense_id = $array_sql['ts_expense_id'];
				$ts_expense_desc = $array_sql['ts_expense_desc'];
				$ts_expense_date = $array_sql['ts_expense_date'];
				$ts_expense_vat = $array_sql['ts_expense_vat'];
				$project_id = $array_sql['proj_id'];
				$project_num = $array_sql['proj_num'];
				$project_name = $array_sql['proj_name'];
				
				$total = $total + $ts_expense_vat;
				
				print "<tr><td>$project_num</td><td>".$ts_expense_desc."</td><td style=\"text-align: right;\">".MoneyFormat($ts_expense_vat)."</td></tr>";

$counter++;		
		
}

print "<tr><td colspan=\"2\"><strong>Total</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($total)."</strong></td></tr>";
print "</table>";



}

?>
