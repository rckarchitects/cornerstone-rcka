<?php

if ($user_usertype_current < 3) { print "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; } else {

print "<h1>Verified Expenses</h1>";

$date_begin = mktime(0,0,$_POST[then_month],$_POST[then_day],$_POST[then_year]);
$date_end = mktime(23,59,$_POST[now_month],$_POST[now_day],$_POST[now_year]);

echo "<p>List expenses verified between $date_begin and $date_end</p>";

$sql = "SELECT ts_expense_verified,ts_expense_vat FROM intranet_timesheet_expense  order by ts_expense_verified";
$result = mysql_query($sql, $conn) or die(mysql_error());

if (mysql_num_rows($result) > 0) {

$lasttime = 0;

$proj_id_current == NULL;
$expense_total = 0;
$linecount = 0;

print "<table summary=\"List all verified expenses by date\">";
print "<tr><td colspan=\"2\"><strong>Date Verified</strong></td></td></tr>";

while ($array = mysql_fetch_array($result)) {
  
		$ts_expense_verified = $array['ts_expense_verified'];
		$ts_expense_vat = $array['ts_expense_vat'];
		
		
		if ($ts_expense_verified != $lasttime) {
		
		if ($linecount > 0) {
			echo "<td><a href=\"index2.php?page=timesheet_expense_list_verified&amp;time=$lasttime\">".MoneyFormat($expense_total)."</a></td></tr>";
			}			

		print "<tr><td><a href=\"index2.php?page=datebook_view_day&amp;time=$ts_expense_verified\">".TimeFormat($ts_expense_verified)."</a>&nbsp;<a href=\"pdf_expense_verified_list.php?time=$ts_expense_verified\" target=\"_blank\"><img src=\"images/button_pdf.png\" alt=\"PDF Output\" /></a></td>";
		
		$linecount++;
		
		$expense_total = 0;
		
		}
		
		$expense_total = $ts_expense_vat + $expense_total;
		
		$lasttime = $ts_expense_verified;
		
		
}

echo "<td><a href=\"index2.php?page=timesheet_expense_list_verified&amp;time=$ts_expense_verified\">".MoneyFormat($expense_total)."</a></td></tr>";

echo "</table>";
	

} else {

	print "<p>There are no expenses to verify.</p><p>$sql</p>";

}

print "</table>";








}

?>
