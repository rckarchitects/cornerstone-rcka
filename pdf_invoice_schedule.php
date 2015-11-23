<?php

if ($_POST[date_type] == "invoice_paid") { $date_type = "invoice_paid"; }
elseif ($_POST[date_type] == "invoice_due") { $date_type = "invoice_due"; }
else { $date_type = "invoice_date"; }

$format_bg_r = "220";
$format_bg_g = "220";
$format_bg_b = "220";

$format_font = "century";
$format_font_2 = "Century.php";

include "inc_files/inc_checkcookie.php";

if ($user_usertype_current <= 3) { header ("Location: index2.php"); } else {

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

$project_counter = 1;
$page_count = 1;
$month_count = 1;
	
	$x = 10;
	$y = 50;
	$pdf->SetXY($x,$y);
	
// Reset all the counters

	$invoice_value_novat_month_all = 0;
	$invoice_value_vat_month_all = 0;
	
	$invoice_value_novat_total_all = 0;
	$invoice_value_vat_total_all = 0;
	
	$thismonth = 0;
	$thisyear = 0;
	
	$invoice_year_novat = 0;
	$invoice_year_vat = 0;
	
	$total_12month_novat = array("0","0","0","0","0","0","0","0","0","0","0","0");
	$total_12month_vat = array("0","0","0","0","0","0","0","0","0","0","0","0");
	
		
//Page Title
	
	$sheet_title = "Invoice Summary, ".date("j F Y",time());
	$pdf->SetXY(10,45);
	$pdf->SetFont($format_font,'',26);
	$pdf->SetTextColor(200, 200, 200);
	$pdf->Cell(0,10,$sheet_title);
	$pdf->SetXY(10,60);
	
		
// Get the relevant infomation from the Invoice Database

	$sql_invoice = "SELECT * FROM intranet_timesheet_invoice ORDER BY $date_type, invoice_ref";
	$result_invoice = mysql_query($sql_invoice, $conn) or die(mysql_error());
	while ($array_invoice = mysql_fetch_array($result_invoice)) {
	
		$invoice_id = $array_invoice['invoice_id'];
		$invoice_ref = $array_invoice['invoice_ref'];
		$invoice_date = $array_invoice['invoice_date'];
		$invoice_due = $array_invoice['invoice_due'];
		$invoice_project = $array_invoice['invoice_project'];
		$invoice_paid = $array_invoice['invoice_paid'];
		$invoice_account = $array_invoice['invoice_account'];
		
		$invoice_month = date("n",$invoice_date);
		$invoice_year = date("Y",$invoice_date);
		
		$invoice_id_print = $invoice_id;
		$invoice_ref_print = strtoupper($invoice_ref);
		$invoice_date_print = TimeFormat($invoice_date);
		$invoice_due_print = TimeFormat($invoice_due);
		
		if ($invoice_paid > 0) { $invoice_paid_print = TimeFormat($invoice_paid); } else { $invoice_paid_print = "-"; }
		
		$pdf->SetFillColor(255,150,150);
		if ($invoice_paid == 0 AND $invoice_due < time()) { $highlight = 1; } else { $highlight = 0; }
		
	if ($invoice_month != $thismonth AND $thismonth > 0) {
	
	
			// Month Total
			$pdf->SetXY($x,$y);
			$month_total_print = $invoice_date - 2592000;
			$total_print = "Total for ".$month;
			$pdf->SetTextColor(150, 150, 150);
			$pdf->Cell(130,6,$total_print,'T');
			$invoice_novat_month_print = CashFormat($invoice_novat_month);
			$invoice_vat_month_print = CashFormat($invoice_vat_month);
			$pdf->Cell(30,6,$invoice_novat_month_print,'T',0,'R');
			$pdf->Cell(30,6,$invoice_vat_month_print,'T',0,'R');
			
			// 12 Month Total
			$time_now = mktime(0,0,0,$month_now,1,date("Y",$invoice_timestamp));
			$year_last = date("Y",$invoice_timestamp) - 1;
			$month_lastyear = mktime(0,0,0,$month_now,1,$year_last);
			$invoice_12month_novat = 0;
			$invoice_12month_vat = 0;
			$sql_12month = "SELECT invoice_item_novat, invoice_item_vat FROM intranet_timesheet_invoice_item, intranet_timesheet_invoice WHERE '$date_type' BETWEEN '$month_lastyear' AND '$time_now' AND invoice_item_invoice = invoice_id ORDER BY invoice_date";
			$result_12month = mysql_query($sql_12month, $conn) or die(mysql_error());
			while ($array_12month = mysql_fetch_array($result_12month)) {
			$invoice_12month_novat = $array_12month['invoice_item_novat'] + $invoice_12month_novat;
			$invoice_12month_vat = $array_12month['invoice_item_vat'] + $invoice_12month_vat;
			}

			// print $month_lastyear." - ".$time_now."<br />";
			
			$total_12month_novat_print = CashFormat($invoice_12month_novat);
			$total_12month_vat_print = CashFormat($invoice_12month_vat);
			$x = 10;
			$y = $y + 4;
			$pdf->SetXY($x,$y);
			$pdf->Cell(130,6,"12 Month Total",'');
			$pdf->Cell(30,6,$total_12month_novat_print,'',0,'R');
			$pdf->Cell(30,6,$total_12month_vat_print,'',0,'R');
			
			// Running Total
			$x = 10;
			$y = $y + 4;
			$pdf->SetXY($x,$y);
			$pdf->Cell(130,6,"Running Total (All Time)",'');
			$invoice_value_novat_total_all_print = CashFormat($invoice_value_novat_total_all);
			$invoice_value_vat_total_all_print = CashFormat($invoice_value_vat_total_all);
			$pdf->Cell(30,6,$invoice_value_novat_total_all_print,'',0,'R');
			$pdf->Cell(30,6,$invoice_value_vat_total_all_print,'',0,'R');
			
			if ($y > 250) { $pdf->addPage(); $x = 10; $y = 15; $pdf->SetXY($x,$y);}
			
			$pdf->SetXY($x,$y);
			$invoice_novat_month = 0;
			$invoice_vat_month = 0;
		}

	if ($invoice_year != $thisyear) {	
	
		if ($invoice_year_novat > 0) {
			// Year Total
			$y = $y + 10;
			$pdf->SetXY($x,$y);	
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont($format_font,'',10);
			$year = date("Y",$invoice_date) - 1;
			$year = $year." Total";
			$pdf->Cell(130,5,$year,'T');
			$invoice_year_novat_print = CashFormat($invoice_year_novat);
			$invoice_year_vat_print = CashFormat($invoice_year_vat);
			$pdf->Cell(30,5,$invoice_year_novat_print,'T',0,'R');
			$pdf->Cell(30,5,$invoice_year_vat_print,'T',0,'R');
			$invoice_year_novat = 0;
			$invoice_year_vat = 0;
		}
	
		// Year Heading
		$y = $y + 10;
		$pdf->SetXY($x,$y);	
		$pdf->SetFillColor(200, 200, 200);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont($format_font,'',16);
		$year = date("Y",$invoice_date);
		$pdf->Cell(190,7,$year,'','','',1);
		$pdf->SetXY($x,$y);
		$thisyear = date("Y",$invoice_date);
		
		
		// Column headings

		$x = 10;
		$y = $y + 7;
		$pdf->SetXY($x,$y);

		$pdf->SetTextColor(100, 100, 100);
		$pdf->SetFont($format_font,'',8);
		$pdf->Cell(15,5,"ID");
		$pdf->Cell(25,5,"Invoice Number");
		$pdf->Cell(30,5,"Invoice Date");
		$pdf->Cell(30,5,"Invoice Due");
		$pdf->Cell(30,5,"Invoice Paid");
		$pdf->Cell(30,5,"Total Excl. VAT",0,0,'R');
		$pdf->Cell(30,5,"Total Inc. VAT",0,0,'R');
		
		$y = $y + 1;
		$pdf->SetXY($x,$y);
		}
		
	if ($invoice_month != $thismonth) {	
		$y = $y + 10;
		$pdf->SetXY($x,$y);	
		$pdf->SetTextColor(100, 100, 100);
		$pdf->SetFont($format_font,'',12);
		$month = date("F Y",$invoice_date);
		$pdf->Cell(190,7,$month,'B');
		$y = $y + 7;
		$pdf->SetXY($x,$y);
		$thismonth = date("n",$invoice_date);
		}
		
// Set the correct font

	if ($invoice_account > 0) { $invoice_id_print = $invoice_id_print." [".$invoice_account."]"; }
	
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont($format_font,'',9);
	$pdf->Cell(15,5,$invoice_id_print,0,0,'L',$highlight);
	$pdf->Cell(25,5,$invoice_ref_print,0,0,'L',$highlight);
	$pdf->Cell(30,5,$invoice_date_print,0,0,'L',$highlight);
	$pdf->Cell(30,5,$invoice_due_print,0,0,'L',$highlight);
	$pdf->Cell(30,5,$invoice_paid_print,0,0,'L',$highlight);
	
	
// Get the invoice values for the invoice

	$invoice_item_novat = 0;
	$invoice_item_vat = 0;
	$sql_values = "SELECT invoice_item_novat, invoice_item_vat FROM intranet_timesheet_invoice_item WHERE invoice_item_invoice = '$invoice_id'";
	$result_values = mysql_query($sql_values, $conn) or die(mysql_error());
	
	while ($array_values = mysql_fetch_array($result_values)) {
		$invoice_item_novat = $array_values['invoice_item_novat'] + $invoice_item_novat;
		$invoice_item_vat = $array_values['invoice_item_vat'] + $invoice_item_vat;
	}
		$invoice_item_novat_print = CashFormat($invoice_item_novat);
		$invoice_item_vat_print = CashFormat($invoice_item_vat);
		$pdf->Cell(30,5,$invoice_item_novat_print,0,0,'R',$highlight);
		$pdf->Cell(30,5,$invoice_item_vat_print,0,0,'R',$highlight);
		
	$invoice_novat_month = $invoice_novat_month + $invoice_item_novat;
	$invoice_vat_month = $invoice_vat_month + $invoice_item_vat;
	
	$invoice_value_novat_total_all = $invoice_value_novat_total_all + $invoice_item_novat;
	$invoice_value_vat_total_all = $invoice_value_vat_total_all + $invoice_item_vat;
	
	// Running Totals
	$total_running_novat = $total_running_novat + $invoice_item_novat;
	$total_running_vat = $total_running_vat + $invoice_item_vat;
	
	$invoice_year_novat = $invoice_year_novat + $invoice_item_novat;
	$invoice_year_vat = $invoice_year_vat + $invoice_item_vat;
	
	$invoice_item_novat = 0;
	$invoice_item_vat = 0;

	$y = $y + 5;
	if ($y > 250) { $pdf->addPage(); $x = 10; $y = 15;}
	$pdf->SetXY($x,$y);
	
	$month_now = date("n",$invoice_date) + 1;
	$invoice_timestamp = $invoice_date;
}



			$month_total_print = $invoice_date - 2592000;
			$total_print = "Total for ".$month;
			$pdf->SetTextColor(150, 150, 150);
			$pdf->Cell(130,5,$total_print,'T');
			$invoice_novat_month_print = CashFormat($invoice_novat_month);
			$invoice_vat_month_print = CashFormat($invoice_vat_month);
			$pdf->Cell(30,5,$invoice_novat_month_print,'T',0,'R');
			$pdf->Cell(30,5,$invoice_vat_month_print,'T',0,'R');
			$y = $y + 7;
			$pdf->SetXY($x,$y);
			
		if ($invoice_year_novat > 0) {
			// Year Total
			$y = $y + 10;
			$pdf->SetXY($x,$y);	
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont($format_font,'',10);
			$year = date("Y",$invoice_date). " Total";
			$pdf->Cell(130,5,$year,'T');
			$invoice_year_novat_print = CashFormat($invoice_year_novat);
			$invoice_year_vat_print = CashFormat($invoice_year_vat);
			$pdf->Cell(30,5,$invoice_year_novat_print,'T',0,'R');
			$pdf->Cell(30,5,$invoice_year_vat_print,'T',0,'R');
			$invoice_year_novat = 0;
			$invoice_year_vat = 0;
			$y = $y + 10;
			$pdf->SetXY($x,$y);
		}

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont($format_font,'',10);
	$pdf->Cell(130,5,"TOTAL",'T');
	$invoice_item_novat_total_all_print = CashFormat($invoice_value_novat_total_all);
	$invoice_item_vat_total_all_print = CashFormat($invoice_value_vat_total_all);
	$pdf->Cell(30,5,$invoice_item_novat_total_all_print,'T',0,'R');
	$pdf->Cell(30,5,$invoice_item_vat_total_all_print,'T',0,'R');
	
		// And the smallprint
		
	$y = $y + 15;
		
	$smallprint = "Notes:\nOverdue invoices are highlighted in red.\nAll figures exclude expenses.";
		
	$pdf->SetXY(10,$y);
	$pdf->SetFont($format_font,'',9);
	$pdf->MultiCell(140,3.75,$smallprint);

// and send to output

$file_name = "Invoice_Schedule_".Date("Y",time())."-".Date("m",time())."-".Date("d",time()).".pdf";

$pdf->Output($file_name,I);

}
?>
