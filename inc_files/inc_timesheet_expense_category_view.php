<?php

if ($user_usertype_current <= 3) { echo "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; } else {

$year_now = $_GET[year];
$year_before = $_GET[year] - 1;

$expense_category = urldecode($_GET[expense_category]);


$date_begin = mktime(0,0,0,5,1,$year_before);
$date_end = mktime(23,59,59,4,30,$year_now);

echo "<h1>Expense Analysis for $year_before - $year_now: $expense_category</h1>";
echo "<p>List expenses dated between ".date("j F Y",$date_begin)." and ".date("j F Y",$date_end)."<br />This excludes personal and invoices items, but includes VAT.</p>";

$sql = "SELECT ts_expense_desc, ts_expense_vat, ts_expense_id, ts_expense_date, user_name_first, user_name_second FROM intranet_timesheet_expense LEFT JOIN  intranet_user_details ON ts_expense_user = user_id WHERE ts_expense_date BETWEEN $date_begin and $date_end AND ts_expense_invoiced = 0 AND ts_expense_p11d != 1 AND ts_expense_category = '$_GET[expense_cat_id]' ORDER BY ts_expense_date";
$result = mysql_query($sql, $conn) or die(mysql_error());

echo "<table>";

while ($array = mysql_fetch_array($result)) {

$ts_expense_id = $array['ts_expense_id'];
$ts_expense_vat = $array['ts_expense_vat'];
$ts_expense_desc = $array['ts_expense_desc'];
$ts_expense_date = $array['ts_expense_date'];
$user_name_first = $array['user_name_first'];
$user_name_second = $array['user_name_second'];

$grand_total = $grand_total + $ts_expense_vat;

	echo "<tr><td style=\"width: 40%;\"><a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\">$ts_expense_desc</a></td><td>$user_name_first $user_name_second</td><td><a href=\"index2.php?page=datebook_view_day&amp;time=$ts_expense_date\">".TimeFormat($ts_expense_date)."</a></td><td style=\"text-align: right;\">".MoneyFormat($ts_expense_vat)."</td></tr>";
	$category_current = $expense_cat_name; $category_total = 0;
}

echo "<tr><td colspan=\"3\"><strong>Total</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($grand_total)."</strong></td></tr>";

}

echo "</table>";

?>
