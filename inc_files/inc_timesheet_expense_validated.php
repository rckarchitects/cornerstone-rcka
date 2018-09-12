<?php

$list_length = 1000;

if ($_GET[list_begin] == "") { $list_begin = 0; } else { $list_begin = $_GET[list_begin] ; }


if ($user_usertype_current <= 3 AND $_GET[user_id] != $_COOKIE[user]) { echo "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; }

elseif ($user_usertype_current <= 3 AND $_GET[user_id] == NULL) { echo "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; }

else {

echo "<h1>Expenses</h1>";
echo "<h2>Validated Expenses by Date</h2>";

// Determine the date a week ago

if ($_GET[user_filter] > 0) { $user_filter = " AND ts_expense_user = '$user_filter' "; } else { $user_filter = NULL; }


$sql = "SELECT DISTINCT ts_expense_verified, SUM(ts_expense_vat) FROM intranet_timesheet_expense WHERE ts_expense_verified > 0 GROUP BY ts_expense_verified ORDER BY ts_expense_verified DESC";


$result = mysql_query($sql, $conn) or die(mysql_error());

if (mysql_num_rows($result) > 0) {

$counter = 1;

$proj_id_current == NULL;
$expense_total = 0;

echo "<table summary=\"List of expenses for all projects\">";


while ($array = mysql_fetch_array($result)) {

		$ts_expense_verified = $array['ts_expense_verified'];
		$ts_expense_vat_total = "&pound;" . number_format ( $array['SUM(ts_expense_vat)'], 2);
		echo "<tr><td><a href=\"index2.php?page=timesheet_expense_list_verified&amp;timestamp=$ts_expense_verified\">".TimeFormatDetailed($ts_expense_verified)."</a></td><td style=\"text-align: right;\">$ts_expense_vat_total</td><td><a href=\"". $pref_location ."/pdf_expense_verified_list.php?timestamp=$ts_expense_verified\"><img src=\"images/button_pdf.png\" alt=\"Export as PDF\" /></a></td><tr>";

	}
	
echo "</table>";
	

} else {

	echo "<p>There are no expenses that fit this criteria.</p>";

}



echo "</form>";








}

?>
