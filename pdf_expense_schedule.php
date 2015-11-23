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


$format_bg_r = "220";
$format_bg_g = "220";
$format_bg_b = "220";

$format_font = "century";
$format_font_2 = "Century.php";

if ($user_usertype_current <= 3) { header ("Location: index2.php"); }

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
	
	$sheet_title = "Expenses Summary, ".date("j F Y",time());
	$pdf->SetXY(10,45);
	$pdf->SetFont($format_font,'',26);
	$pdf->SetTextColor(200, 200, 200);
	$pdf->Cell(0,10,$sheet_title);
	$pdf->SetXY(10,55);
	$pdf->SetFont($format_font,'',12);
	
	$sheet_subtitle = "For period ".TimeFormat($date_begin)." to ".TimeFormat($date_end);
	$pdf->Cell(0,6,$sheet_subtitle,0,1,L,0);
	
	if ($_POST[show_p11d_only] == "1") {
	$pdf->Cell(0,6,"Showing P11d Items Only",0,1,L,0);
	$p11d = " AND ts_expense_p11d = 1 ";
	}
	
	$x = 10;
	$y = $pdf->GetY() + 10;
	$pdf->SetY($y);
	
		// Columnn Headings
		
	$pdf->SetTextColor(220, 220, 220);
	$pdf->SetFont($format_font,'',8);
	
		$pdf->Cell(10,4,"ID",0,0,L,0);
		$pdf->Cell(20,4,"Date",0,0,L,0);
		$pdf->Cell(15,4,"Project",0,0,L,0);
		$pdf->Cell(55,4,"Description",0,0,L,0);
		$pdf->Cell(20,4,"Date Verified",0,0,L,0);
		$pdf->Cell(25,4,"Invoice (ID)",0,0,L,0);
		$pdf->Cell(15,4,"Net.",0,0,R,0);
		$pdf->Cell(15,4,"VAT",0,0,R,0);
		$pdf->Cell(15,4,"Gross.",0,1,R,0);

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont($format_font,'',9);
	
	$expense_total_value = 0;
	$expense_total_vat = 0;
	$expense_total_diff = 0;
	
	$expense_invoice_net = 0;
	$expense_noinvoice_net = 0;
	$expense_invoice_vat = 0;
	$expense_noinvoice_vat = 0;
	$expense_invoice_gross = 0;
	$expense_noinvoice_gross = 0;
	
	$expense_p11d_total = 0;
	
	$y = $pdf->GetY();
	$pdf->SetY($y);

// Get the relevant infomation from the Invoice Database

if ($_POST[user_id_only] != NULL) { $user_id_only = " AND user_id = ".$_POST[user_id_only]." "; }

if ($_POST[user_id_only] != NULL) { $user_id_only = " AND user_id = ".$_POST[user_id_only]." "; }

	$sql_expense = "SELECT * FROM intranet_timesheet_expense, intranet_projects, intranet_user_details WHERE ts_expense_project = proj_id AND ts_expense_date BETWEEN $date_begin AND $date_end AND user_id = ts_expense_user $show_verified_only $user_id_only $p11d ORDER BY $expense_sortorder";
	$result_expense = mysql_query($sql_expense, $conn) or die(mysql_error());
	while ($array_expense = mysql_fetch_array($result_expense)) {
	
	
		if ($y > 250) {
					$pdf->addPage();
										// Columnn Headings
											
										$pdf->SetTextColor(220, 220, 220);
										$pdf->SetFont($format_font,'',8);
										$pdf->Cell(10,4,"ID",0,0,L,0);
										$pdf->Cell(20,4,"Date",0,0,L,0);
										$pdf->Cell(15,4,"Project",0,0,L,0);
										$pdf->Cell(55,4,"Description",0,0,L,0);
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

		$ts_expense_desc = RemoveShit($array_expense['ts_expense_desc']);
		$ts_expense_desc = html_entity_decode($ts_expense_desc);
		$ts_expense_desc = str_replace("\n\n","\n",$ts_expense_desc);		
		
		$invoice_ref = $array_expense['invoice_ref'];
		
		$ts_expense_diff = $array_expense['ts_expense_vat'] - $array_expense['ts_expense_value'];
		
		$ts_expense_diff_print = CashFormat($ts_expense_diff);
	
		$ts_expense_invoiced = $array_expense['ts_expense_invoiced'];
		
		if ($ts_expense_invoiced > 0) {
			$sql_invoice = "SELECT invoice_ref, invoice_id FROM intranet_timesheet_invoice WHERE invoice_id = $ts_expense_invoiced LIMIT 1";
			$result_invoice = mysql_query($sql_invoice, $conn) or die(mysql_error());
			$array_invoice = mysql_fetch_array($result_invoice);
			$invoice_ref = $array_invoice['invoice_ref'];
			$invoice_id = $array_invoice['invoice_id'];
			
			$invoice_ref_print = $invoice_ref." (".$invoice_id.")";
			
		} else {
			$invoice_ref_print = "-";
		}
		
		
		$ts_expense_user = $array_expense['ts_expense_user'];
		if ($array_expense['ts_expense_verified'] > 0 ) {$ts_expense_verified = TimeFormatBrief($array_expense['ts_expense_verified']);
			} else { $ts_expense_verified = "-"; }
		$ts_expense_value = CashFormat($array_expense['ts_expense_value']);
		$ts_expense_vat = CashFormat($array_expense['ts_expense_vat']);
		$ts_expense_receipt = $array_expense['ts_expense_receipt'];
		$ts_expense_reimburse = $array_expense['ts_expense_reimburse'];
		$ts_expense_p11d = $array_expense['ts_expense_p11d'];
		$user_intials = $array_expense['user_initials'];
		$proj_num = $array_expense['proj_num'];
		
		if ($ts_expense_p11d == 1) { $expense_p11d_total = $expense_p11d_total + $array_expense['ts_expense_vat']; }
		
		if ($ts_expense_invoiced == NULL) { $ts_expense_invoiced = 0; }
		
		if ($ts_expense_p11d == 1 AND $ts_expense_invoiced > 0) { $bg = 1; $pdf->SetFillColor(255,0,0); }
		elseif ($ts_expense_p11d == 1) { $bg = 1; $pdf->SetFillColor(180,230,130); }
		elseif ($ts_expense_invoiced > 0 ) { $bg = 1; $pdf->SetFillColor(255,255,200); }
		else { $bg = 0; }
		
		$pdf->SetX(55);
		$pdf->MultiCell(45,4,$ts_expense_desc,0,L,$bg);
		$max_y = $pdf->GetY();
		
		$cellheight = $max_y - $y;
		
		$pdf->SetXY(10,$y);
		
		$pdf->SetFont($format_font,'',7);
		$pdf->Cell(10,$cellheight,$ts_expense_id,0,0,L,$bg,"http://intranet.rcka.co.uk/index2.php?page=timesheet_expense_view&ts_expense_id=$ts_expense_id");
		$pdf->SetFont($format_font,'',9);
		$pdf->Cell(20,$cellheight,$ts_expense_date,0,0,L,$bg);
		$pdf->Cell(15,$cellheight,$proj_num,0,0,L,$bg);
		$x = $x + 90;
		$pdf-> SetXY($x,$y);
		$pdf->Cell(10,$cellheight,$user_intials,0,0,L,$bg);
		$pdf->Cell(20,$cellheight,$ts_expense_verified,0,0,L,$bg);
		$pdf->Cell(25,$cellheight,$invoice_ref_print,0,0,L,$bg);
		$pdf->Cell(15,$cellheight,$ts_expense_value,0,0,R,$bg);
		$pdf->Cell(15,$cellheight,$ts_expense_diff_print,0,0,R,$bg);
		$pdf->Cell(15,$cellheight,$ts_expense_vat,0,1,R,$bg);
		
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
	
		$pdf->Cell(145,5,"Non-Invoiced Totals",T,0,L,0);
		$pdf->Cell(15,5,$expense_noinvoice_net,T,0,R,0);
		$pdf->Cell(15,5,$expense_noinvoice_vat,T,0,R,0);
		$pdf->Cell(15,5,$expense_noinvoice_gross,T,1,R,0);
		
		$y = $pdf->GetY();
		$pdf->SetXY(10,$y);

		$pdf->Cell(145,5,"Invoiced Totals",0,0,L,0);
		$pdf->Cell(15,5,$expense_invoice_net,0,0,R,0);
		$pdf->Cell(15,5,$expense_invoice_vat,0,0,R,0);
		$pdf->Cell(15,5,$expense_invoice_gross,0,1,R,0);
		
		
		
	
		$pdf->SetFont($format_font,'',8);
		$pdf->SetDrawColor("0");

		$pdf->Cell(145,5,"TOTAL",T,0,L,0);
		$pdf->Cell(15,5,$expense_total_value,T,0,R,0);
		$pdf->Cell(15,5,$expense_total_diff,T,0,R,0);
		$pdf->Cell(15,5,$expense_total_vat,T,1,R,0);
		
		$y = $pdf->GetY() + 5;
		
		$pdf->SetY($y);
		
		if ($expense_p11d_total > 0) {
		
				$expense_p11d_total_print = CashFormat($expense_p11d_total);
		
				$pdf->Cell(175,5,"P11d TOTAL",T,0,L,0);
				$pdf->Cell(15,5,$expense_p11d_total_print,T,1,R,0);
				
		}

// and send to output

$file_name = "Expenses_Schedule_".Date("Y",time())."-".Date("m",time())."-".Date("d",time()).".pdf";

$pdf->Output($file_name,I);

?>
