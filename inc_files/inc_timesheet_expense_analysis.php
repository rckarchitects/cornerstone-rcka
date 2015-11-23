<?php

print "<h1>Expenses Analysis</h1>";

if ($user_usertype_current <= 3 AND $_GET[user_id] != $_COOKIE[user]) { print "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; }

elseif ($user_usertype_current <= 3 AND $_GET[user_id] == NULL) { print "<h1 class=\"heading_alert\">Permission Denied</h1><p>You do not have permission to view this page.</p>"; }

else {

// Select period month to view

print "<fieldset><legend>View Expenses</legend>";

print "<p>The following form allows you to output a PDF file which lists the expenses between the chosen dates.</p>";

print "<form method=\"post\" action=\"pdf_expense_schedule.php\">";

if ($_GET[then_day] == NULL) { $then_day = date("j", time()); } else { $then_day = CleanNumber($_GET[then_day]); }
if ($_GET[then_month] == NULL) { $then_month = date("n", time()); } else { $then_month = CleanNumber($_GET[then_month]); }
if ($_GET[then_year] == NULL) { $then_year = date("Y",time()) - 1; } else { $then_year = CleanNumber($_GET[then_year]); }

print "<p>Date Begin<br />Day&nbsp;<input type=\"text\" name=\"then_day\" value=\"$then_day\" />&nbsp;Month&nbsp;<input type=\"text\" name=\"then_month\" value=\"$then_month\" />&nbsp;Year&nbsp;<input type=\"text\" name=\"then_year\" value=\"$then_year\" /></p>";


if ($_GET[now_day] == NULL) { $now_day = date("j", time());} else { $now_day = CleanNumber($_GET[now_day]); }
if ($_GET[now_month] == NULL) { $now_month = date("n", time());} else { $now_month = CleanNumber($_GET[now_month]); }
if ($_GET[now_year] == NULL) { $now_year = date("Y",time());} else { $now_year = CleanNumber($_GET[now_year]); }

print "<p>Date End<br />Day&nbsp;<input type=\"text\" name=\"now_day\" value=\"$now_day\" />&nbsp;Month&nbsp;<input type=\"text\" name=\"now_month\" value=\"$now_month\" />&nbsp;Year&nbsp;<input type=\"text\" name=\"now_year\" value=\"$now_year\" /></p>";

print "
<p>
<input type=\"radio\" name=\"include_invoiced\" value=\"1\" />&nbsp;Exclude invoiced items?&nbsp;
<input type=\"radio\" name=\"include_invoiced\" value=\"2\" />&nbsp;Exclude non-invoiced items?&nbsp;
<input type=\"radio\" name=\"include_invoiced\" value=\"\" checked=\"checked\" />&nbsp;Show all items?
</p>";

print "
<p>Sort by&nbsp;
<input type=\"radio\" name=\"sorted_by\" value=\"project\" />&nbsp;Project?&nbsp;
<input type=\"radio\" name=\"sorted_by\" value=\"date\" checked=\"checked\" />&nbsp;Date&nbsp;
<input type=\"radio\" name=\"sorted_by\" value=\"id\" />&nbsp;Expense ID&nbsp;
<input type=\"radio\" name=\"sorted_by\" value=\"ts_expense_vat\" />&nbsp;Cost (Gross)&nbsp;
</p>";

echo "<p>Include Users:&nbsp;<select name=\"user_id_only\"><option value=\"\">- All -</option>";

	$sql_users = "SELECT * FROM intranet_user_details ORDER BY user_name_second";
	$result_users = mysql_query($sql_users, $conn) or die(mysql_error());
	while ($array_users = mysql_fetch_array($result_users)) {
	echo "<option value=\"".$array_users['user_id']."\">".$array_users['user_name_first']." ".$array_users['user_name_second']."</option>";
	}

echo "</select></p>";

echo "<p><input type=\"checkbox\" value=\"1\" name=\"show_p11d_only\" /> Show P11d Items Only?</p>";

print "<p><input type=\"submit\" /></p>";



print "</form>";
print "</fieldset>";


// Or output a CSV file

print "<fieldset><legend>View Expenses</legend>";

print "<p>Alternatively, you can output the same file in CSV format.</p>";

print "<form method=\"post\" action=\"csv_expense_schedule.php\">";

if ($_GET[then_day] == NULL) { $then_day = date("j", time()); } else { $then_day = CleanNumber($_GET[then_day]); }
if ($_GET[then_month] == NULL) { $then_month = date("n", time()); } else { $then_month = CleanNumber($_GET[then_month]); }
if ($_GET[then_year] == NULL) { $then_year = date("Y",time()) - 1; } else { $then_year = CleanNumber($_GET[then_year]); }

print "<p>Date Begin<br />Day&nbsp;<input type=\"text\" name=\"then_day\" value=\"$then_day\" />&nbsp;Month&nbsp;<input type=\"text\" name=\"then_month\" value=\"$then_month\" />&nbsp;Year&nbsp;<input type=\"text\" name=\"then_year\" value=\"$then_year\" /></p>";


if ($_GET[now_day] == NULL) { $now_day = date("j", time());} else { $now_day = CleanNumber($_GET[now_day]); }
if ($_GET[now_month] == NULL) { $now_month = date("n", time());} else { $now_month = CleanNumber($_GET[now_month]); }
if ($_GET[now_year] == NULL) { $now_year = date("Y",time());} else { $now_year = CleanNumber($_GET[now_year]); }

print "<p>Date End<br />Day&nbsp;<input type=\"text\" name=\"now_day\" value=\"$now_day\" />&nbsp;Month&nbsp;<input type=\"text\" name=\"now_month\" value=\"$now_month\" />&nbsp;Year&nbsp;<input type=\"text\" name=\"now_year\" value=\"$now_year\" /></p>";

print "
<p>
<input type=\"radio\" name=\"include_invoiced\" value=\"1\" />&nbsp;Exclude invoiced items?&nbsp;
<input type=\"radio\" name=\"include_invoiced\" value=\"2\" />&nbsp;Exclude non-invoiced items?&nbsp;
<input type=\"radio\" name=\"include_invoiced\" value=\"\" checked=\"checked\" />&nbsp;Show all items?
</p>";

print "
<p>Sort by&nbsp;
<input type=\"radio\" name=\"sorted_by\" value=\"project\" />&nbsp;Project?&nbsp;
<input type=\"radio\" name=\"sorted_by\" value=\"date\" checked=\"checked\" />&nbsp;Date&nbsp;
<input type=\"radio\" name=\"sorted_by\" value=\"id\" />&nbsp;Expense ID&nbsp;
<input type=\"radio\" name=\"sorted_by\" value=\"ts_expense_vat\" />&nbsp;Cost (Gross)&nbsp;
</p>";

print "<p><input type=\"submit\" /></p>";



print "</form>";
print "</fieldset>";

// Or output a CSV file

print "<fieldset><legend>View Verified Expenses</legend>";

print "<p>This allows you to list all verified expenses by date, where you can print off a PDF schedule for each entry.</p>";

print "<form method=\"post\" action=\"index2.php?page=timesheet_expense_verified\">";

if ($_GET[then_day] == NULL) { $then_day = date("j", time()); } else { $then_day = CleanNumber($_GET[then_day]); }
if ($_GET[then_month] == NULL) { $then_month = date("n", time()); } else { $then_month = CleanNumber($_GET[then_month]); }
if ($_GET[then_year] == NULL) { $then_year = date("Y",time()) - 1; } else { $then_year = CleanNumber($_GET[then_year]); }

print "<p>Date Begin<br />Day&nbsp;<input type=\"text\" name=\"then_day\" value=\"$then_day\" />&nbsp;Month&nbsp;<input type=\"text\" name=\"then_month\" value=\"$then_month\" />&nbsp;Year&nbsp;<input type=\"text\" name=\"then_year\" value=\"$then_year\" /></p>";


if ($_GET[now_day] == NULL) { $now_day = date("j", time());} else { $now_day = CleanNumber($_GET[now_day]); }
if ($_GET[now_month] == NULL) { $now_month = date("n", time());} else { $now_month = CleanNumber($_GET[now_month]); }
if ($_GET[now_year] == NULL) { $now_year = date("Y",time());} else { $now_year = CleanNumber($_GET[now_year]); }

print "<p>Date End<br />Day&nbsp;<input type=\"text\" name=\"now_day\" value=\"$now_day\" />&nbsp;Month&nbsp;<input type=\"text\" name=\"now_month\" value=\"$now_month\" />&nbsp;Year&nbsp;<input type=\"text\" name=\"now_year\" value=\"$now_year\" /></p>";

print "<p><input type=\"submit\" /></p>";



print "</form>";
print "</fieldset>";

// Category Analysis

print "<fieldset><legend>View Categories</legend>";

print "<p>This outputs a schedule of expenses categories.</p>";

print "<form method=\"post\" action=\"index2.php?page=timesheet_expense_category\">";

$yearend_day = 31;
$yearend_month = 4;

echo "<p>Day:&nbsp;<input type=\"text\" name=\"yearend_day\" value=\"$yearend_day\" size=\"4\" />&nbsp;Month:&nbsp;<input type=\"text\" name=\"yearend_month\" value=\"$yearend_month\" size=\"4\" />&nbsp;";

$yearend_year = date("Y",time());
$yearend_year_next = $yearend_year + 1;
$counter = 0;

echo "Year:&nbsp;<select name=\"yearend_year\">";

while ($counter <= 10) {
if ($yearend_year == $yearend_year_next) { $select = "selected"; } else { $select = ""; }
echo "<option value=\"$yearend_year_next\" $select>$yearend_year_next</option>";
$yearend_year_next = $yearend_year_next - 1;
$counter++;
}

echo "</select>";

print "<p><input type=\"submit\" /></p>";



print "</form>";
print "</fieldset>";

// P11d Analysis for individual users

print "<fieldset><legend>P11d Items</legend>";

print "<p>Produces a list of P11d items for any user between the dates specified.</p>";

print "<form method=\"post\" action=\"pdf_expense_p11d.php\" target=\"_blank\">";

if ($_GET[then_day] == NULL) { $then_day = date("j", time()); } else { $then_day = CleanNumber($_GET[then_day]); }
if ($_GET[then_month] == NULL) { $then_month = date("n", time()); } else { $then_month = CleanNumber($_GET[then_month]); }
if ($_GET[then_year] == NULL) { $then_year = date("Y",time()) - 1; } else { $then_year = CleanNumber($_GET[then_year]); }

echo "<p><strong>User</strong><br /><select name=\"user_id\">";
	$sql_user = "SELECT * FROM intranet_user_details ORDER BY user_name_second, user_name_first";
	$result_user = mysql_query($sql_user, $conn) or die(mysql_error());
	while ($array_user = mysql_fetch_array($result_user)) {
		$user_id = $array_user['user_id'];
		$user_name_first = $array_user['user_name_first'];
		$user_name_second = $array_user['user_name_second'];
		echo "<option value=\"$user_id\">$user_name_first&nbsp;$user_name_second</option>";
	}

echo "</select></p>";

echo "<p><strong>All Dates?</strong><br />Yes<input type=\"radio\" name=\"time_all\" value=\"yes\" checked=\"checked\" />&nbsp;Dates as follows:<input type=\"radio\" name=\"time_all\" value=\"no\" /></p>";

print "<p>Date Begin<br />Day&nbsp;<input type=\"text\" name=\"then_day\" value=\"$then_day\" size=\"4\" />&nbsp;Month&nbsp;<input type=\"text\" name=\"then_month\" value=\"$then_month\" size=\"4\" />&nbsp;Year&nbsp;<input type=\"text\" name=\"then_year\" value=\"$then_year\" size=\"4\" /></p>";


if ($_GET[now_day] == NULL) { $now_day = date("j", time());} else { $now_day = CleanNumber($_GET[now_day]); }
if ($_GET[now_month] == NULL) { $now_month = date("n", time());} else { $now_month = CleanNumber($_GET[now_month]); }
if ($_GET[now_year] == NULL) { $now_year = date("Y",time());} else { $now_year = CleanNumber($_GET[now_year]); }

print "<p>Date End<br />Day&nbsp;<input type=\"text\" name=\"now_day\" value=\"$now_day\" size=\"4\" />&nbsp;Month&nbsp;<input type=\"text\" name=\"now_month\" value=\"$now_month\" size=\"4\" />&nbsp;Year&nbsp;<input type=\"text\" name=\"now_year\" value=\"$now_year\" size=\"4\" /></p>";

echo "<p><strong>Include P11d Items?</strong><br />Yes<input type=\"radio\" name=\"include_p11d\" value=\"yes\" checked=\"checked\" />&nbsp;No<input type=\"radio\" name=\"include_p11d\" value=\"no\" />&nbsp;Both<input type=\"radio\" name=\"include_p11d\" value=\"both\" /></p>";

print "<p><input type=\"submit\" /></p>";



print "</form>";
print "</fieldset>";




print "<fieldset><legend>Verify or View P11d Items</legend>";

function MonthRow($user) {
$current_year = date("Y",time());
$year_start = $current_year - 4;
$year_now = $year_start;
	while ( $year_now <= $current_year ) {
		$year_next = $year_now + 1;
		echo "<td><a href=\"csv_expense_user.php?user=$user&amp;year=$year_now\">$year_now - $year_next</a></td>";
		$year_now++;
	}
	echo "<td><a href=\"csv_expense_user.php?user=$user&year=all\">All</a></td>";
}

echo "<table><tr><td style=\"width: 20%\">User</td><td colspan=\"6\">Year (May to April)</td></tr>";
	$sql_user = "SELECT * FROM intranet_user_details ORDER BY user_name_second, user_name_first";
	$result_user = mysql_query($sql_user, $conn) or die(mysql_error());
	while ($array_user = mysql_fetch_array($result_user)) {
		$user_id = $array_user['user_id'];
		$user_name_first = $array_user['user_name_first'];
		$user_name_second = $array_user['user_name_second'];
		echo "<tr><td><a href=\"index2.php?page=timesheet_expense_p11d&amp;user_id=$user_id\">$user_name_first $user_name_second</a></td>";
		MonthRow($user_id);
		echo "</tr>";
	}
	echo "</table>";
	
print "</ol></fieldset>";


}



?>