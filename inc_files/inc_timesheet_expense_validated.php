<?php

$list_length = 1000;

if ($_GET[list_begin] == "") { $list_begin = 0; } else { $list_begin = $_GET[list_begin] ; }


if ($user_usertype_current <= 3 AND $_GET[user_id] != $_COOKIE[user]) { print "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; }

elseif ($user_usertype_current <= 3 AND $_GET[user_id] == NULL) { print "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; }

else {

print "<h1>View Validated Expenses by Date</h1>";

// Determine the date a week ago

if ($_GET[user_filter] > 0) { $user_filter = " AND ts_expense_user = '$user_filter' "; } else { $user_filter = NULL; }


$sql = "SELECT DISTINCT ts_expense_verified FROM intranet_timesheet_expense WHERE ts_expense_verified > 0 ORDER BY ts_expense_verified";


$result = mysql_query($sql, $conn) or die(mysql_error());

if (mysql_num_rows($result) > 0) {

$counter = 1;

$proj_id_current == NULL;
$expense_total = 0;

print "<table summary=\"List of expenses for all projects\">";

$cola = 1;
$colb = 1;

while ($array = mysql_fetch_array($result)) {
		if ($cola == 1) { echo "<tr>"; $cola = 2; } else { $cola = 1; }
		$ts_expense_verified = $array['ts_expense_verified'];
		echo "<td><a href=\"index2.php?page=timesheet_expense_list_verified&amp;time=$ts_expense_verified\">".TimeFormatDetailed($ts_expense_verified)."</a>&nbsp;<a href=\"http://intranet.rcka.co.uk/pdf_expense_verified_list.php?time=$ts_expense_verified\"><img src=\"images/button_pdf.png\" alt=\"Export as PDF\" /></a></td>";
		if ($colb == 2) { echo "</tr>"; $colb = 1; } else { $colb = 2; }
	}
	

} else {

	print "<p>There are no expenses that fit this criteria.</p>";

}

print "</table>";

print "</form>";








}

?>
