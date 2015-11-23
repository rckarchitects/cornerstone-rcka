<?php

include "inc_files/inc_checkcookie.php";


$format_bg_r = "220";
$format_bg_g = "220";
$format_bg_b = "220";

$format_font = "century";
$format_font_2 = "Century.php";


if ($user_usertype_current <= 3 AND $_COOKIE[user] != $_GET[user_id]) { header ("Location: index2.php"); }

if ($_GET[user_id] == NULL) { $user_id = $_COOKIE[user_id]; } else { $user_id = $_GET[user_id]; }

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

	$sql_name = "SELECT user_name_first, user_name_second, user_initials FROM intranet_user_details WHERE user_id = $user_id LIMIT 1";
	$result_name = mysql_query($sql_name, $conn) or die(mysql_error());
	$array_name = mysql_fetch_array($result_name);
	$user_name_first = $array_name['user_name_first'];
	$user_name_second = $array_name['user_name_second'];
	$user_initials = $array_name['user_initials'];
	
	$sheet_title = "Expenses Claim, ".date("j F Y",time());
	$user_name = $user_name_first . " " . $user_name_second . " (" . $user_initials . ")";
	$pdf->SetXY(10,45);
	$pdf->SetFont($format_font,'',16);
	$pdf->SetTextColor(0);
	$pdf->Cell(0,7,$sheet_title,0,1);
	$pdf->Cell(0,6,$user_name,0,1);
	$pdf->SetFont($format_font,'',12);
	$pdf->Cell(0,6,'',0,1);
	
		// Columnn Headings
		
	$pdf->SetTextColor(150);
	$pdf->SetFont($format_font,'',8);
	
		$pdf->Cell(10,4,"ID",0,0,L,0);
		$pdf->Cell(20,4,"Date",0,0,L,0);
		$pdf->Cell(15,4,"Project",0,0,L,0);
		$pdf->Cell(100,4,"Description",0,0,L,0);
		$pdf->Cell(15,4,"Net.",0,0,R,0);
		$pdf->Cell(15,4,"VAT",0,0,R,0);
		$pdf->Cell(15,4,"Gross.",0,1,R,0);

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont($format_font,'',9);
	$pdf->SetLineWidth(0.05);
		
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

	$sql_expense = "SELECT * FROM intranet_timesheet_expense, intranet_projects WHERE ts_expense_project = proj_id AND ts_expense_user = '$user_id' AND ( ts_expense_verified = 0 OR ts_expense_verified IS NULL ) ORDER BY ts_expense_date";
	$result_expense = mysql_query($sql_expense, $conn) or die(mysql_error());
	while ($array_expense = mysql_fetch_array($result_expense)) {
	
	
	
		if ($y > 260) {
					$pdf->addPage();
										// Columnn Headings
											
										$pdf->SetTextColor(220, 220, 220);
										$pdf->SetFont($format_font,'',8);
										$pdf->Cell(10,4,"ID",0,0,L,0);
										$pdf->Cell(20,4,"Date",0,0,L,0);
										$pdf->Cell(15,4,"Project",0,0,L,0);
										$pdf->Cell(100,4,"Description",0,0,L,0);
										$pdf->Cell(15,4,"Net.",0,0,R,0);
										$pdf->Cell(15,4,"VAT",0,0,R,0);
										$pdf->Cell(15,4,"Gross.",0,1,R,0);

										$pdf->SetTextColor(0, 0, 0);
										$pdf->SetFont($format_font,'',9);
					$y = 15;
		}
	
	
		$pdf->SetY($y);
		
		$pdf->SetDrawColor(0);
		
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
		$ts_expense_reimburse = $array_expense['ts_expense_reimburse'];
		
		if ($ts_expense_p11d == "1") { $ts_expense_desc = $ts_expense_desc." (P11d Item)"; } 
		
		
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
		$proj_num = $array_expense['proj_num'];
		
		if ($ts_expense_reimburse != 1) { $pdf->SetTextColor(150); } else { $pdf->SetTextColor(0); }
		
		$pdf->SetFont($format_font,'',7);
		$pdf->Cell(10,5,$ts_expense_id,0,0,L);
		$pdf->SetFont($format_font,'',9);
		$pdf->Cell(20,5,$ts_expense_date,0,0,L);
		$pdf->Cell(15,5,$proj_num,0,0,L);
		$pdf->MultiCell(100,5,$ts_expense_desc,0,L);
		$max_y = $pdf->GetY();
		$x = $x + 145;
		$y = $y;
		$pdf-> SetXY($x,$y);
		$pdf->Cell(15,5,$ts_expense_value,0,0,R);
		$pdf->Cell(15,5,$ts_expense_diff_print,0,0,R);
		$pdf->Cell(15,5,$ts_expense_vat,0,1,R);
		
		$x = 10;
		$y = $max_y;

		if ($ts_expense_reimburse == 1) {
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
}

		$pdf->SetTextColor(0);

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
		$pdf->SetLineWidth(0.2);

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

$file_name = "Expenses_Verified_".Date("Y",$_GET[time])."-".Date("m",$_GET[time])."-".Date("d",$_GET[time])."-".Date("H",$_GET[time])."-".Date("i",$_GET[time])."-".Date("s",$_GET[time]).".pdf";

$pdf->Output($file_name,I);

?>
