<?php

// Timesheet Settings & Analysis

SearchPanel();


if ($module_timesheets == 1) {

// Menu - Timesheet Admin

	$array_pages = array("index2.php?page=timesheet_analysis","index2.php?page=timesheet_settings","index2.php?page=timesheet_rates_hourly","index2.php?page=timesheet_rates_overhead","index2.php?page=timesheet_rates_project");
	$array_title = array("Analysis Sheets","Timesheet Settings","Hourly Rates","Overhead Rates","Project Rates");
	$array_images = array("button_list.png","button_list.png","button_list.png","button_list.png","button_list.png");
	$array_access = array(2,3,3,3,3);
	


	SideMenu ("Timesheet Administration", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}

	

// Invoices


if ($module_invoices == 1) {

	$array_pages = array("index2.php?page=timesheet_invoice","index2.php?page=timesheet_invoice_edit","index2.php?page=timesheet_invoice_view_outstanding&amp;status=paid","index2.php?page=timesheet_invoice_view_outstanding","index2.php?page=timesheet_invoice_view_outstanding&amp;status=current","index2.php?page=timesheet_invoice_view_outstanding&amp;status=future","index2.php?page=timesheet_invoice_view_month");
	$array_title = array("Invoices","Add Invoice","Paid Invoices","Oustanding Invoices","Current Invoices","Future Invoices","Invoices by Month");
	$array_images = array("button_list.png","button_list.png","button_list.png","button_list.png","button_list.png","button_list.png","button_list.png");
	$array_access = array(3,3,3,3,3,3,3);

	SideMenu ("Invoice Administration", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}

if ($module_invoices == 1) {

	$nowyear = date("Y");
	$nowmonth = 1;

	$array_pages = array();
	$array_title = array("January $nowyear","February $nowyear","March $nowyear","April $nowyear","May $nowyear","June $nowyear","July $nowyear","August $nowyear","September $nowyear","October $nowyear","November $nowyear","December $nowyear");
	$array_images = array();
	$array_access = array(3,3,3,3,3,3,3,3,3,3,3,3);

	while ($nowmonth <= 12) {

		$array_pages[] = "index2.php?page=timesheet_invoice_view_month&amp;month=$nowmonth&amp;year=$nowyear&amp;type=date";
		$nowmonth++;

	}
	
	SideMenu ("Invoice by Month", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);

}

// Expenses

if ($module_expenses == 1) {

	$array_pages = array("index2.php?page=timesheet_expense_edit","index2.php?page=timesheet_expense_list","index2.php?page=timesheet_expense_analysis","index2.php?page=timesheet_expense_validated");
	$array_title = array("Add Expenses","Validate Expenses","Expenses Analysis","Validated Expenses by Date");
	$array_images = array("button_list.png","button_list.png","button_list.png","button_list.png");
	$array_access = array(1,3,3,3);

	SideMenu ("Expenses Administration", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
	
	
	function EarliestExpense() {

	GLOBAL $conn;
		
		$sql = "SELECT ts_expense_date FROM intranet_timesheet_expense WHERE ts_expense_date > 0 order by ts_expense_date LIMIT 1";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		return date("Y",$array['ts_expense_date']);

	}

	$year_start = EarliestExpense();
	$year_now = date("Y",time()) + 1;
	
	$array_pages = array();
	$array_title = array();
	$array_images = array();
	$array_access = array();
	
	while ($year_now > $year_start) {
	
		$array_pages[] = "index2.php?page=timesheet_expense_annual&amp;year=$year_now";
		$array_title[] = "Analysis for ".($year_now - 1)."-".($year_now);
		$array_images[] = "";
		$array_access[] = 3;
		$year_now--;
		
	}
	
	SideMenu ("Expenses by Year", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	

}



