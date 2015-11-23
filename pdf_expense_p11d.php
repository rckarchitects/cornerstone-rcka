<?php

include "inc_files/inc_checkcookie.php";

if ($user_usertype_current <= 2 OR $_POST[user_id] == NULL) { header ("Location: index2.php"); } else {

// Get the user details

$user_id = $_POST[user_id];

$sql_user = "SELECT user_name_first, user_name_second FROM intranet_user_details WHERE user_id = $user_id LIMIT 1";
	$result_user = mysql_query($sql_user, $conn) or die(mysql_error());
	while ($array_user = mysql_fetch_array($result_user)) {
	$user_name_first = $array_user['user_name_first'];
	$user_name_second = $array_user['user_name_second'];
	}
	
// Determine the status of the P11d items

if ($_POST[include_p11d] == "yes") { $include_p11d = "AND ts_expense_p11d = 1"; $title = "P11d Items"; }
elseif ($_POST[include_p11d] == "no") { $include_p11d = "AND ts_expense_p11d != 1"; $title = "Non-P11d Items"; }
else { $include_p11d = NULL; $title = "All Items"; }

// Sort out the time

if ($_POST[time_all] == "yes") { $include_time = NULL; $sheet_times = "All Time"; }
else {

$time_begin = mktime(0, 0, 0, $_POST[then_month], $_POST[then_day], $_POST[then_year]);
$time_end = mktime(23, 59, 59, $_POST[now_month], $_POST[now_day], $_POST[now_year]);

$include_time = "AND ts_expense_date BETWEEN $time_begin AND $time_end";

	$date_begin = date("j M Y",$time_begin);
	$date_end = date("j M Y",$time_end);
	$sheet_times = "Between $date_begin & $date_end";

}

$format_bg_r = "220";
$format_bg_g = "220";
$format_bg_b = "220";

$format_font = "century";
$format_font_2 = "Century.php";

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->useTemplate($tplidx, 0, 0, 210, 297);

$pdf->AddFont($format_font,'',$format_font_2);

// Begin creating the page

//Page Title
	
	$sheet_title = "$title, $user_name_first $user_name_second";
	$pdf->SetXY(10,45);
	$pdf->SetFont($format_font,'',26);
	$pdf->SetTextColor(200, 200, 200);
	$pdf->Cell(0,10,$sheet_title);

// Time between

	$pdf->SetXY(10,55);
	$pdf->SetFont($format_font,'',16);
	$pdf->SetTextColor(200, 200, 200);
	$pdf->Cell(0,10,$sheet_times);
	$pdf->SetXY(10,65);
	$pdf->SetFont($format_font,'',12);
	
		// Columnn Headings
		
	$pdf->SetTextColor(220, 220, 220);
	$pdf->SetFont($format_font,'',8);
	
		$pdf->Cell(10,4,"ID",0,0,L,0);
		$pdf->Cell(20,4,"Date",0,0,L,0);
		$pdf->Cell(70,4,"Description",0,0,L,0);
		$pdf->Cell(20,4,"Date Verified",0,0,L,0);
		$pdf->Cell(25,4,"Invoice (ID)",0,0,L,0);
		$pdf->Cell(15,4,"Net.",0,0,R,0);
		$pdf->Cell(15,4,"VAT",0,0,R,0);
		$pdf->Cell(15,4,"Gross.",0,1,R,0);

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont($format_font,'',9);
		
	$x = 10;
	$y = 70;
	
	$expense_total_value = 0;
	$expense_total_vat = 0;
	$expense_total_diff = 0;
	
	$expense_invoice_net = 0;
	$expense_noinvoice_net = 0;
	$expense_invoice_vat = 0;
	$expense_noinvoice_vat = 0;
	$expense_invoice_gross = 0;
	$expense_noinvoice_gross = 0;

	
	

// Get the relevant infomation from the Invoice Database

	$sql_expense = "SELECT * FROM intranet_timesheet_expense WHERE ts_expense_user = $user_id $include_p11d $include_time ORDER BY ts_expense_date";
	$result_expense = mysql_query($sql_expense, $conn) or die(mysql_error());
	while ($array_expense = mysql_fetch_array($result_expense)) {
	
	$pdf->SetTextColor(0, 0, 0);
	
	
		if ($y > 260) {
					$pdf->addPage();
										// Columnn Headings
											
										$pdf->SetTextColor(220, 220, 220);
										$pdf->SetFont($format_font,'',8);
										$pdf->Cell(10,4,"ID",0,0,L,0);
										$pdf->Cell(20,4,"Date",0,0,L,0);
										$pdf->Cell(70,4,"Description",0,0,L,0);
										$pdf->Cell(20,4,"Date Verified",0,0,L,0);
										$pdf->Cell(25,4,"Invoice (ID)",0,0,L,0);
										$pdf->Cell(15,4,"Net.",0,0,R,0);
										$pdf->Cell(15,4,"VAT",0,0,R,0);
										$pdf->Cell(15,4,"Gross.",0,1,R,0);

										$pdf->SetTextColor(0, 0, 0);
										$pdf->SetFont($format_font,'',9);
					$y = 15;
		}
	
	
		$pdf->SetY($y);
		
		$pdf->SetDrawColor("230");
		
		$pdf->Cell(190,3,"","B",0,"",0);
		$y = $y + 3;
	

		$pdf->SetXY($x,$y);
	
		$ts_expense_id = $array_expense['ts_expense_id'];
		$ts_expense_value = CashFormat($array_expense['ts_expense_value']);
		$ts_expense_date = TimeFormatBrief($array_expense['ts_expense_date']);
		$ts_expense_p11d = $array_expense['ts_expense_p11d'];
		$ts_expense_notes = $array_expense['ts_expense_notes'];

		$ts_expense_desc = RemoveShit($array_expense['ts_expense_desc']);
		$ts_expense_desc = html_entity_decode($ts_expense_desc);
		$ts_expense_desc = str_replace("\n\n","\n",$ts_expense_desc);
		
		
		$invoice_ref = $array_expense['invoice_ref'];
		
		$ts_expense_diff = $array_expense['ts_expense_vat'] - $array_expense['ts_expense_value'];
		
		$ts_expense_diff_print = CashFormat($ts_expense_diff);
	
		$ts_expense_invoiced = $array_expense['ts_expense_invoiced'];
		
		
		$ts_expense_user = $array_expense['ts_expense_user'];
		if ($array_expense['ts_expense_verified'] > 0 ) {$ts_expense_verified = TimeFormatBrief($array_expense['ts_expense_verified']);
			} else { $ts_expense_verified = "-"; }
		$ts_expense_value = CashFormat($array_expense['ts_expense_value']);
		$ts_expense_vat = CashFormat($array_expense['ts_expense_vat']);
		$ts_expense_receipt = $array_expense['ts_expense_receipt'];
		$ts_expense_reimburse = $array_expense['ts_expense_reimburse'];
		$proj_num = $array_expense['proj_num'];
		
		if ($ts_expense_invoiced == NULL) { $ts_expense_invoiced = 0; }
		
		if ($_POST[include_p11d] == "both" AND $ts_expense_p11d == 1) {
			$pdf->SetFillColor(255,210,150);
			$bg = 1;
		} else {
			$pdf->SetFillColor(255,255,255);
			$bg = 0;
		}
		
		$pdf->SetFont($format_font,'',7);
		$pdf->Cell(10,4,$ts_expense_id,0,0,L,$bg);
		$pdf->SetFont($format_font,'',9);
		$pdf->Cell(20,4,$ts_expense_date,0,0,L,$bg);
		$pdf->Cell(15,4,$proj_num,0,0,L,$bg);
		$pdf->MultiCell(55,4,$ts_expense_desc,0,L,$bg);
		$max_y = $pdf->GetY();
		$x = $x + 100;
		$y = $y;
		$pdf-> SetXY($x,$y);
		$pdf->Cell(20,4,$ts_expense_verified,0,0,L,$bg);
		$pdf->Cell(25,4,$invoice_ref_print,0,0,L,$bg);
		$pdf->Cell(15,4,$ts_expense_value,0,0,R,$bg);
		$pdf->Cell(15,4,$ts_expense_diff_print,0,0,R,$bg);
		$pdf->Cell(15,4,$ts_expense_vat,0,1,R,$bg);
		
		$x = 10;
		$y = $max_y;

		$expense_total_value = $expense_total_value + $array_expense['ts_expense_value'];
		$expense_total_diff = $expense_total_diff + $ts_expense_diff;
		$expense_total_vat = $expense_total_vat + $array_expense['ts_expense_vat'];
		
		
		// Work out the totals for invoiced, non-invoiced values
		
		if ($ts_expense_invoiced > 0) {
				$expense_invoice_net = $expense_invoice_net + $array_expense['ts_expense_value'];
				$expense_invoice_vat = $expense_invoice_vat + $ts_expense_diff;
				$expense_invoice_gross = $expense_invoice_gross + $array_expense['ts_expense_vat'];
		} else {
				$expense_noinvoice_net = $expense_noinvoice_net + $array_expense['ts_expense_value'];
				$expense_noinvoice_vat = $expense_noinvoice_vat + $ts_expense_diff;
				$expense_noinvoice_gross = $expense_noinvoice_gross + $array_expense['ts_expense_vat'];
		}
}

		$expense_total_value = CashFormat($expense_total_value);
		$expense_total_diff = CashFormat($expense_total_diff);
		$expense_total_vat = CashFormat($expense_total_vat);

	//Totals
	
	$y = $pdf->GetY() + 5;
	$pdf->SetXY(10,$y);
	
		$y = $pdf->GetY() + 5;
	$pdf->SetXY(10,$y);
	
		$pdf->SetFont($format_font,'',8);
		$pdf->SetDrawColor("0");
		
		
		$expense_invoice_net = CashFormat($expense_invoice_net);
		$expense_noinvoice_net = CashFormat($expense_noinvoice_net);
		$expense_invoice_vat = CashFormat($expense_invoice_vat);
		$expense_noinvoice_vat = CashFormat($expense_noinvoice_vat);
		$expense_invoice_gross = CashFormat($expense_invoice_gross);
		$expense_noinvoice_gross = CashFormat($expense_noinvoice_gross);
		
		$y = $pdf->GetY();
		$pdf->SetXY(10,$y);
		
		
		
	
		$pdf->SetFont($format_font,'',8);
		$pdf->SetDrawColor("0");

		$pdf->Cell(145,5,"TOTAL",T,0,L,0);
		$pdf->Cell(15,5,$expense_total_value,T,0,R,0);
		$pdf->Cell(15,5,$expense_total_diff,T,0,R,0);
		$pdf->Cell(15,5,$expense_total_vat,T,1,R,0);

		// And the smallprint
		
	$y = $y + 15;
		
	$smallprint = "";
		
	$pdf->SetXY(10,$y);
	$pdf->SetFont($format_font,'',9);
	$pdf->MultiCell(140,3.75,$smallprint);

// and send to output

$file_name = "Expenses_Verified_".Date("Y",$ts_expense_verified)."-".Date("m",$ts_expense_verified)."-".Date("d",$ts_expense_verified).".pdf";

$pdf->Output($file_name,I);










}

?>
