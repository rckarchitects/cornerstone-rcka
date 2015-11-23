<?php

function PercentBar($category_total,$percent_total) {

$cat_percent = round((($category_total / $percent_total) * 100), 2);
$cat_width = round($cat_percent *4);
echo "<td><img src=\"images/bar_percent.gif\" alt=\"$cat_percent\" height=\"20px\" width=\"".$cat_width."px\" />&nbsp;<span class=\"minitext\">$cat_percent%</span></td>";

}

if ($user_usertype_current <= 3) { echo "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; } else {

$year_now = $_GET[year];
$year_before = $_GET[year] - 1;


$date_begin = mktime(0,0,0,5,1,$year_before);
$date_end = mktime(23,59,59,4,30,$year_now);

echo "<h1>Expense Analysis for $year_before - $year_now</h1>";
echo "<p>List expenses dated between ".date("j F Y",$date_begin)." and ".date("j F Y",$date_end)."<br />This excludes personal and invoices items, but includes VAT.</p>";

//$sql = "SELECT ts_expense_vat, expense_cat_name, expense_cat_id FROM intranet_timesheet_expense LEFT JOIN (intranet_timesheet_expense_category, intranet_projects) ON ts_expense_category = expense_cat_id AND ts_expense_project = proj_id WHERE proj_fee_track = 1 AND ts_expense_date BETWEEN $date_begin and $date_end AND ts_expense_invoiced = 0 AND ts_expense_p11d != 1 ORDER BY expense_cat_name";
$sql = "SELECT ts_expense_vat, expense_cat_name, expense_cat_id FROM intranet_timesheet_expense LEFT JOIN intranet_timesheet_expense_category ON ts_expense_category = expense_cat_id WHERE ts_expense_date BETWEEN $date_begin and $date_end AND ts_expense_invoiced = 0 AND ts_expense_p11d != 1 ORDER BY expense_cat_name";
$result = mysql_query($sql, $conn) or die(mysql_error());
$result_total = mysql_query($sql, $conn) or die(mysql_error());
$percent_total = 0;
while ($array_total = mysql_fetch_array($result_total)) {
$ts_expense_vat = $array_total['ts_expense_vat'];
$percent_total = $percent_total + $ts_expense_vat;
}

// Include personal expenses

$sql_personal = "SELECT ts_expense_vat FROM intranet_timesheet_expense WHERE ts_expense_date BETWEEN $date_begin and $date_end AND ts_expense_p11d = 1";
$result_total_personal = mysql_query($sql_personal, $conn) or die(mysql_error());
$total_personal = 0;
while ($array_total_personal = mysql_fetch_array($result_total_personal)) {
$total_personal = $total_personal + $array_total_personal['ts_expense_vat'];
}

$result_total = $result_total + $total_personal;

$category_current = NULL;
$category_total = 0;
$grand_total = 0;

echo "<table>";

echo "<tr><td><a href=\"index2.php?page=timesheet_expense_category_view&amp;year=$_GET[year]&amp;expense_cat_id=$expense_cat_id&amp;expense_category=".urlencode($expense_cat_name)."\">-- Not classified --</a></td>";

while ($array = mysql_fetch_array($result)) {

$expense_cat_name = $array['expense_cat_name'];
$ts_expense_vat = $array['ts_expense_vat'];
$expense_cat_id = $array['expense_cat_id'];

if ($expense_cat_name != $category_current ) {
$cat_percent = round((($category_total / $percent_total) * 100), 2);
$cat_width = round($cat_percent *4);
echo "<td><img src=\"images/bar_percent.gif\" alt=\"$cat_percent\" height=\"20px\" width=\"".$cat_width."px\" />&nbsp;<span class=\"minitext\">$cat_percent%</span></td>";

echo "<td style=\"text-align: right;\">".MoneyFormat($category_total)."</td></tr>"; $category_total = 0; }

if ($expense_cat_name != $category_current  ) {
echo "<tr><td style=\"width: 35%;\"><a href=\"index2.php?page=timesheet_expense_category_view&amp;year=$_GET[year]&amp;expense_cat_id=$expense_cat_id&amp;expense_category=".urlencode($expense_cat_name)."\">$expense_cat_name</a></td>"; }

$category_total = $category_total + $ts_expense_vat;
$grand_total = $grand_total + $ts_expense_vat;

$category_current = $expense_cat_name;

}

PercentBar($category_total,$percent_total);

echo "<td style=\"text-align: right;\">".MoneyFormat($category_total)."</td></tr>";

// Personal total

if ($total_personal > 0) {
	echo "<tr><td>Total Personal Expenses</td>";
	PercentBar($total_personal,$percent_total);
	echo "<td style=\"text-align: right;\">".MoneyFormat($total_personal)."</td></tr>";
}

echo "<tr><td colspan=\"2\"><strong>Total</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($grand_total)."</strong></td></tr>";



echo "</table>";
}



?>
