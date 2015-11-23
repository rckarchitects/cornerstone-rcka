<?php


include "inc_files/inc_checkcookie.php";

$then_day = CleanNumber($_POST[then_day]);
$then_month = CleanNumber($_POST[then_month]);
$then_year = CleanNumber($_POST[then_year]);

$now_day = CleanNumber($_POST[now_day]);
$now_month = CleanNumber($_POST[now_month]);
$now_year = CleanNumber($_POST[now_year]);

$date_begin = mktime(0,0,0,$then_month,$then_day,$then_year);
$date_end = mktime(24,0,0,$now_month,$now_day,$now_year);

if ($date_end <= $date_begin OR checkdate($then_month,$then_day,$then_year) != "TRUE" OR checkdate($now_month,$now_day,$now_year) != "TRUE" ){
	$redirect = "Location:index2.php?page=timesheet_expense_analysis&then_day=$then_day&then_month=$then_month&then_year=$then_year&now_day=$now_day&now_month=$now_month&now_year=$now_year";

	header($redirect);
	
	}
	
if ($_POST[sorted_by] == "project") { $expense_sortorder = "proj_num"; }
elseif ($_POST[sorted_by] == "id") { $expense_sortorder = "ts_expense_id"; }
elseif ($_POST[sorted_by] == "ts_expense_vat") { $expense_sortorder = "ts_expense_vat"; }
else { $expense_sortorder = "ts_expense_date"; }

if ($user_usertype_current <= 3) { header ("Location: index2.php"); }


// Begin creating the page

echo "<table><tr><td>ID</td><td>Date</td><td>Project</td><td>Description</td><td>Category</td><td>User</td><td>Date Verified</td><td>Invoice (ID) / Personal</td><td>Net.</td><td>VAT</td><td>Gross</td></tr>";

	
	$expense_invoice_net = 0;
	$expense_noinvoice_net = 0;
	$expense_invoice_vat = 0;
	$expense_noinvoice_vat = 0;
	$expense_invoice_gross = 0;
	$expense_noinvoice_gross = 0;
	
	

// Get the relevant infomation from the Invoice Database

	$sql_expense = "SELECT * FROM intranet_timesheet_expense, intranet_projects, intranet_user_details LEFT JOIN intranet_timesheet_expense_category ON ts_expense_category = expense_cat_id WHERE ts_expense_user = user_id AND ts_expense_project = proj_id AND ts_expense_date BETWEEN $date_begin AND $date_end ORDER BY $expense_sortorder";
	
	$result_expense = mysql_query($sql_expense, $conn) or die(mysql_error());
	while ($array_expense = mysql_fetch_array($result_expense)) {
	
		$ts_expense_id = $array_expense['ts_expense_id'];
		$ts_expense_value = round($array_expense['ts_expense_value'],2);
		$expense_cat_name = $array_expense['expense_cat_name'];
		
		$ts_expense_date = TimeFormatBrief($array_expense['ts_expense_date']);

		$ts_expense_desc = RemoveShit($array_expense['ts_expense_desc']);
		$ts_expense_desc = html_entity_decode($ts_expense_desc);
		$ts_expense_desc = str_replace("\n\n","\n",$ts_expense_desc);
		$ts_expense_p11d = $array_expense['ts_expense_p11d'];
		$user_name_first = $array_expense['user_name_first'];
		$user_name_second = $array_expense['user_name_second'];
		$user_name = $user_name_first . "&nbsp;" . $user_name_second;
		
		$invoice_ref = $array_expense['invoice_ref'];
		
		$ts_expense_diff = $array_expense['ts_expense_vat'] - $array_expense['ts_expense_value'];
		
		$ts_expense_diff_print = round($ts_expense_diff,2);
	
		$ts_expense_invoiced = $array_expense['ts_expense_invoiced'];
		
		if ($ts_expense_invoiced > 0) {
			$sql_invoice = "SELECT invoice_ref, invoice_id FROM intranet_timesheet_invoice WHERE invoice_id = $ts_expense_invoiced LIMIT 1";
			$result_invoice = mysql_query($sql_invoice, $conn) or die(mysql_error());
			$array_invoice = mysql_fetch_array($result_invoice);
			$invoice_ref = $array_invoice['invoice_ref'];
			$invoice_id = $array_invoice['invoice_id'];
			
			$invoice_ref_print = $invoice_ref." (".$invoice_id.")";
			
		} else {
			if ($ts_expense_p11d > 0) { $invoice_ref_print = "Personal"; } else { $invoice_ref_print = "Office Expense"; }
		}
		
		
		$ts_expense_user = $array_expense['ts_expense_user'];
		if ($array_expense['ts_expense_verified'] > 0 ) {$ts_expense_verified = TimeFormatBrief($array_expense['ts_expense_verified']);
			} else { $ts_expense_verified = "-"; }
		$ts_expense_value = round($array_expense['ts_expense_value'],2);
		$ts_expense_vat = round($array_expense['ts_expense_vat'],2);
		$ts_expense_receipt = $array_expense['ts_expense_receipt'];
		$ts_expense_reimburse = $array_expense['ts_expense_reimburse'];
		$proj_num = $array_expense['proj_num'];
		
		if ($ts_expense_invoiced == NULL) { $ts_expense_invoiced = 0; }
		
echo "<tr><td>".$ts_expense_id."</td><td>".$ts_expense_date."</td><td>".$proj_num."</td><td>".$ts_expense_desc."</td><td>". $expense_cat_name ."</td><td>".$user_name."</td><td>".$ts_expense_verified."</td><td>".$invoice_ref_print."</td><td>".$ts_expense_value."</td><td>".$ts_expense_diff_print."</td><td>".$ts_expense_vat."</td></tr>";
}

echo "</table>";

?>
