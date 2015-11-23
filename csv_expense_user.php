<?php

include "inc_files/inc_checkcookie.php";

if ($user_usertype_current <= 2 OR $_GET[user] == NULL) { header ("Location: index2.php"); } else {

echo "<?xml version=\"1.0\" encoding=\"iso-8859-15\"?>
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>RCKa Intranet System - P11d Schedule</title>
<link rel=\"shortcut icon\" href=\"favicon.ico\" />
<meta http-equiv=\"pragma\" content=\"no-cache\" />
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-15\" />
<style type=\"text/css\">
<!--
  body {font-family: Arial, Helvetica, sans-serif; margin: 20px; padding: 0px; background-color: #fff; color: #000;}
  table { border-style:solid; border-width:1px; border-color: #ccc }
  p { font-size: 9pt;}
  h1 { font-size: 13pt;}
  th { font-size: 8pt; padding: 2px; font-weight: bold; color: #666; text-align: left; background-color: #ddd; border-style:solid; border-width: 1px}
  td { font-size: 9pt; padding: 2px; border-style:dotted; border-width: 1px; margin: 0;  border-color: #ccc; vertical-align: top }
  td.null { text-decoration: line-through }
  tr:hover { background-color: #cedae9; }
  td.total {font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 0.7em; padding: 2px; font-weight: bold; text-align: left}
//-->
</style>
</head><body>

";

function ColumnHead($input) {
echo "<tr><th>ID</th><th>Date</th><th colspan=\"2\">Description</th><th>Category</th><th>Value</th><th>Paid by $input <br />but not yet reimbursed</th><th>Paid by $input<br />and already reimbursed</th><th>Paid by RCKa directly</th></tr>";
}




	if ($_GET[year] == "all") {
	$year_filter = "";
	} else {
	$year_start = $_GET[year];
	$year_end = $year_start + 1;
	$time_begin = mktime(0,0,0,5,1,$year_start);
	$time_end = mktime(23,59,59,4,30,$year_end);
	$year_filter = "BETWEEN '$time_begin' AND '$time_end'";
	}
	
	$counter = 0;

	// This isolates P11d items only
	
	// $sql_expense = "SELECT * FROM intranet_timesheet_expense, intranet_user_details WHERE user_id = ts_expense_user AND ts_expense_user = '$_GET[user]' AND ts_expense_p11d = 1 AND ts_expense_date BETWEEN '$time_begin' AND '$time_end' ORDER BY ts_expense_date";
	
	if ($_GET[show_non_personal] != NULL) { $show_non_personal = "AND ts_expense_p11d = 1"; } else { $show_non_personal = NULL; }
	
	$sql_expense = "SELECT * FROM  intranet_user_details, intranet_projects, intranet_timesheet_expense LEFT JOIN intranet_timesheet_expense_category ON ts_expense_category = expense_cat_id WHERE proj_id = ts_expense_project AND user_id = ts_expense_user AND ts_expense_user = '$_GET[user]' AND ts_expense_date $year_filter $show_non_personal ORDER BY ts_expense_date";
	
	$result_expense = mysql_query($sql_expense, $conn) or die(mysql_error());
	$array_name = mysql_fetch_array($result_expense);
	$user_name_first = $array_name['user_name_first'];
	$user_name_second = $array_name['user_name_second'];
	$user_initials = $array_name['user_initials'];
	$proj_fee_track = $array_name['proj_fee_track'];
	
	if ($_GET[year] == "all") {
	$period_display = "all time";
	} else {
	$period_display = "period ".date("d M Y",$time_begin)." to ".date("d M Y",$time_end);
	}
	
	
	if ($show_non_personal == NULL) {
		echo "<h1>Complete Expenses for $user_name_first $user_name_second, for $period_display</h1>";
		echo "<p>Note: Items shown with a strike-through have been identified as <u>non-personal</u> expenses, and are shown for information only. <a href=\"csv_expense_user.php?user=$_GET[user]&amp;year=$_GET[year]&amp;show_non_personal=no\">Click here</a> to hide. ";
	} else {
		echo "<h1>Personal Expenses for $user_name_first $user_name_second, for $period_display</h1>";
		echo "<p>Note: Non-personal items are not shown - <a href=\"csv_expense_user.php?user=$_GET[user]&amp;year=$_GET[year]\">click here</a> to display. ";
	}
	
	echo "Highlighted items need further attention, as they are shown as non-reimbursable expenses which have not yet been verified.</p>";
	
	
	echo "<table style=\"width: 100%\">";
	
	ColumnHead($user_initials);
	
	$col1 = 0;
	$col2 = 0;
	$col3 = 0;
	
	//$array_duplicate = array('vat' => NULL,'id' => NULL);
	$array_duplicate = array();
	
	$total_queried = 0;
	
	$result_expense = mysql_query($sql_expense, $conn) or die(mysql_error());
	while ($array_expense = mysql_fetch_array($result_expense)) {
	
		$ts_expense_date_duplicate = $ts_expense_date;
		$ts_expense_vat_duplicate = $ts_expense_vat;
		
		// if ($ts_expense_vat > 50) { array_push($array_duplicate['vat'],$ts_expense_vat); array_push($array_duplicate['id'],$ts_expense_id); }
		if ($ts_expense_vat > 20) { $array_duplicate[] = $ts_expense_vat; }
	
		$ts_expense_id = $array_expense['ts_expense_id'];
		$ts_expense_value = round($array_expense['ts_expense_value'],2);
		$ts_expense_date = TimeFormatBrief($array_expense['ts_expense_date']);
		$ts_expense_desc = DeCode(RemoveShit($array_expense['ts_expense_desc']));
		$ts_expense_vat = $array_expense['ts_expense_vat'];
		$ts_expense_verified = $array_expense['ts_expense_verified'];
		$ts_expense_reimburse = $array_expense['ts_expense_reimburse'];
		$ts_expense_invoice = $array_expense['ts_expense_invoice'];
		$ts_expense_p11d = $array_expense['ts_expense_p11d'];
		$proj_fee_track = $array_expense['proj_fee_track'];
		$proj_num = $array_expense['proj_num'];
		$expense_cat_name = $array_expense['expense_cat_name'];
		
		//check for duplicates
		if(($ts_expense_vat == $ts_expense_vat_duplicate AND $ts_expense_verified == 0 AND $show_non_personal == NULL) OR (array_search($ts_expense_vat,$array_duplicate) == TRUE AND $ts_expense_verified == 0 AND $show_non_personal == NULL)) { $ts_expense_desc = $ts_expense_desc." - <strong>DUPLICATE ENTRY?</strong>"; }
		
		if ($array_expense['ts_expense_notes'] != NULL) { $ts_expense_desc = $ts_expense_desc."<br />[Note: ".$array_expense['ts_expense_notes']."]"; }
		
		$ts_expense_desc = DeCode(RemoveShit($ts_expense_desc));
		
		if ($ts_expense_p11d == 1) {
		
		if ($ts_expense_reimburse == 0 AND $ts_expense_verified == 0) { $style_add = "background-color: #f6d1d1; color: #e24141"; $total_queried = $total_queried + $ts_expense_vat; } else { $style_add = NULL; }
		
		echo "<tr><td style=\"$style_add\"><a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\">$ts_expense_id</a></td><td style=\"$style_add\">$ts_expense_date</td><td colspan=\"2\" style=\"$style_add\">$ts_expense_desc</td><td style=\"$style_add\">$expense_cat_name</td>";
		
		echo "<td style=\"text-align: right; width: 15%; $style_add\">".CashFormat($ts_expense_vat)."</td>";
		
			$col1 = $col1 + $ts_expense_vat;
			
		 if ($ts_expense_reimburse > 0 AND $ts_expense_verified == 0) {
		 echo "<td style=\"text-align: right; width: 15%; $style_add\">".CashFormat($ts_expense_vat)."</td>";
		 $col2 = $col2 + $ts_expense_vat;
		 } else { echo "<td style=\"text-align: right; width: 15%; $style_add\"> - </td>"; }
		
		 if ($ts_expense_reimburse > 0 AND $ts_expense_verified > 0 ) {
		 echo "<td style=\"text-align: right; width: 15%; $style_add\">".CashFormat($ts_expense_vat)."</td>";
		 $col3 = $col3 + $ts_expense_vat;
		 } else { echo "<td style=\"text-align: right; width: 15%; $style_add\"> - </td>"; }
		 
		 if ($ts_expense_reimburse == 0 AND $ts_expense_verified > 0) {
		 echo "<td style=\"text-align: right; width: 15%; $style_add\">".CashFormat($ts_expense_vat)."</td>";
		 $col4 = $col4 + $ts_expense_vat;
		 } else { echo "<td style=\"text-align: right; width: 15%; $style_add\"> - </td>"; }
		
		echo "</tr>";
		
		
		
		} else {
		
		//if (($ts_expense_reimburse == 0 AND $ts_expense_verified == 0) { $style_add = "background-color: #f6d1d1; color: #e24141"; $total_queried = $total_queried + $ts_expense_vat; } else { $style_add = NULL; }
		
		if ($ts_expense_reimburse == 0 AND $ts_expense_verified == 0) { $style_add = "background-color: #f6d1d1; color: #e24141"; $total_queried = $total_queried + $ts_expense_vat; } else { $style_add = NULL; }
		
		
		echo "<tr><td class=\"null\" style=\"$style_add\">$ts_expense_id</td><td class=\"null\" style=\"$style_add; width:35px;\">$ts_expense_date</td><td class=\"null\" style=\"$style_add\">$proj_num</td><td class=\"null\" style=\"$style_add\">$ts_expense_desc</td><td style=\"$style_add\">$expense_cat_name</td>";
		echo "<td class=\"null\" style=\"text-align: right; width: 15%; $style_add\">".CashFormat($ts_expense_vat)."</td>";
		
		$col_alt_1 = $col_alt_1 + $ts_expense_vat;
			
		 if ($ts_expense_reimburse > 0 AND $ts_expense_verified == 0) {
		 echo "<td class=\"null\"  style=\"text-align: right; width: 15%; $style_add\">".CashFormat($ts_expense_vat)."</td>";
		 $col_alt_2 = $col_alt_2 + $ts_expense_vat;
		 } else { echo "<td class=\"null\" style=\"text-align: right; width: 15%; $style_add\"> - </td>"; }
		
		 if ($ts_expense_reimburse > 0 AND $ts_expense_verified > 0 ) {
		 echo "<td class=\"null\" style=\"text-align: right; width: 15%; $style_add\">".CashFormat($ts_expense_vat)."</td>";
		 $col_alt_3 = $col_alt_3 + $ts_expense_vat;
		 } else { echo "<td class=\"null\" style=\"text-align: right; width: 15%; $style_add\"> - </td>"; }
		 
		 if ($ts_expense_reimburse == 0 AND $ts_expense_verified > 0) {
		 echo "<td class=\"null\" style=\"text-align: right; width: 15%; $style_add\">".CashFormat($ts_expense_vat)."</td>";
		 $col_alt_4 = $col_alt_4 + $ts_expense_vat;
		 } else { echo "<td class=\"null\" style=\"text-align: right; width: 15%; $style_add\"> - </td>"; }
		
		echo "</tr>";
		
		
		}
	
	$counter++;
	
	
	}
	
	ColumnHead($user_initials);
	
	if ($total_queried > 0) {
		echo "<tr><th colspan=\"9\">Queried Items</th></tr>";
		$style_add = "background-color: #f6d1d1; color: #e24141";
		echo "<tr><td class=\"total\" colspan=\"4\" style=\"$style_add\">Total (Queried)</td><td class=\"total\" style=\"text-align: right;$style_add\">".CashFormat($total_queried)."</td><td colspan=\"4\" style=\"$style_add\"></td></tr>";
	}
	
	echo "<tr><th colspan=\"9\">Personal Items</th></tr>";
	
	echo "<tr><td class=\"total\" colspan=\"7\">Sub Total (Personal)</td><td class=\"total\" style=\"text-align: right;\">".CashFormat($col3)."</td><td class=\"total\" style=\"text-align: right;\">".CashFormat($col4)."</td></tr>";
	
		echo "<tr><td class=\"total\" colspan=\"5\">Total (Personal)</td><td class=\"total\" style=\"text-align: right;\">".CashFormat($col1 - $total_queried)."</td><td class=\"total\" style=\"text-align: right;\">".CashFormat($col2)."</td><td class=\"total\" style=\"text-align: right;\" colspan=\"3\">".CashFormat($col3 + $col4)."</td></tr>";
		
		if ($show_non_personal == NULL) {
		
		// Non P11d Items
		
		echo "<tr><th colspan=\"9\">Non-Personal Items</th></tr>";
		
			echo "<tr><td class=\"total\" colspan=\"7\">Sub Total (non-Personal)</td><td class=\"total\" style=\"text-align: right;\">".CashFormat($col_alt_3)."</td><td class=\"total\" style=\"text-align: right;\">".CashFormat($col_alt_4)."</td></tr>";
	
		echo "<tr><td class=\"total\" colspan=\"5\">Total (non-Personal)</td><td class=\"total\" style=\"text-align: right;\">".CashFormat($col_alt_1)."</td><td class=\"total\" style=\"text-align: right;\">".CashFormat($col_alt_2)."</td><td class=\"total\" style=\"text-align: right;\" colspan=\"3\">".CashFormat($col_alt_3 + $col_alt_4)."</td></tr>";
	



		// Reimbursements only

		echo "<tr><th colspan=\"9\">Reimbursements Only</th></tr>";
		
		$total_col2 = $col2 + $col_alt_2;
		$total_col3 = $col3 + $col_alt_3;
	
		echo "<tr><td class=\"total\" colspan=\"6\">Total (Reimbursements)</td><td class=\"total\" style=\"text-align: right;\">".CashFormat($total_col2)."</td><td class=\"total\" style=\"text-align: right;\">".CashFormat($total_col3)."</td><td></td></tr>";
		
		}



	echo "</table>";

}

if ($_GET[year] == "all") { $show_period = "From the beginning of time, "; } else { $show_period = "In financial year beginning ".date("j M Y",$time_begin).", "; }

echo "<h1>Summary</h1><p style=\"font-size: 13pt\">$show_period $user_name_first $user_name_second has <strong>".CashFormat($col1 - $total_queried)."</strong> of personal expenses, <strong>".CashFormat($col3 + $col4)."</strong> of which has already been paid by RCKa, leaving <strong>".CashFormat($col2)."</strong> outstanding to be reimbursed.";

if ($total_queried > 0) { echo "There is <strong>".CashFormat($total_queried)."</strong> of expenses which have been queried, which are excluded from these figures. "; }

echo "</p>";

if ($show_non_personal == NULL) {

echo "<p style=\"font-size: 13pt\">In addition, there is <strong>".CashFormat($col_alt_2)."</strong> of non-personal expenses outstanding to be reimbursed, resulting in a total to be reimbursed of <strong>".CashFormat($col2 + $col_alt_2)."</strong></p>";

echo "</p>";

}

?>

</body>
</html>