<?php

include "inc_files/inc_checkcookie_logincheck.php";

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdf.php');

$pdf=new FPDF('L','mm','A4');

$project_counter = 1;
$page_count = 1;
$pdf->AddPage();

// $pdf->Image('fpdf/logo_black.png',10,10,40);

$pdf->SetY(30);

$pdf->SetFont('Helvetica','',15);

// Establish the first date in the timesheet system

		$sql = "SELECT ts_day, ts_month, ts_year, ts_entry FROM intranet_timesheet ORDER BY ts_entry DESC";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		
		$ts_first_day = $array['ts_day'];
		$ts_first_month = $array['ts_month'];
		$ts_first_year = $array['ts_year'];
		$ts_first_entry = $array['ts_entry'];
		
		
// Establish the date 12 months ago

		$nowtime = time() - 3600;
		$thentime = $nowtime - 31536000;	
		$ts_then_day = date("j",$thentime);
		$ts_then_month = date("n",$thentime);
		$ts_then_year = date("Y",$thentime);
		
		$ts_begintime = mktime(0,0,0,$ts_then_month, $ts_then_day, $ts_then_year);
		
		$ts_begindate = date("r",$ts_begintime);

// Print the title details

	$ts_print_title = "Summary for all projects (Page $page_count)";
		
	$pdf->SetFillColor(161, 213, 166);
	
	$pdf->Cell(0,10,$ts_print_title,0, 1, L, 1);

	// Printed by, and on...

		$pdf->SetFont('Helvetica','',12);

		$sql = "SELECT user_name_first, user_name_second FROM intranet_user_details WHERE user_id = $_COOKIE[user]";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
	
		$user_name_first = $array['user_name_first'];
		$user_name_second = $array['user_name_second'];
		
		$printed_on = "Generated ".date("g:ia, j F Y", $nowtime)." by ".$name_first." ".$name_second;
	
		$pdf->Cell(0,10,$printed_on,0, 1, L, 1);
	
		$pdf->SetFillColor(202, 159, 245);

		$pdf->Cell(190,2,'',0, 1);
		
// Column Headings

			$pdf->SetFont('Helvetica','',8);
			$pdf->SetFillColor(202, 159, 245);
			$pdf->Cell(49,3.5,'Project',0, 0, L, 1);
			$pdf->Cell(1,3.5,'',0, 0);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(14,3.5,'Baseline*',0, 0, C, 1);
			$pdf->Cell(1,3.5,'',0, 0);
			
			$ts_count_months = $ts_then_month;
			$ts_count_year = $ts_then_year;
			$counter = 0;
			
			while ($counter <= 12) {
			$count_date = $ts_count_months.".".$ts_count_year;
			$print_date = mktime(0,0,0,$ts_count_months,1,$ts_count_year);
			$print_date = date("M, y",$print_date);
			if ($counter == 12) { $print_date = $print_date."†"; }
			$pdf->Cell(14,3.5,$print_date,0, 0, C, 1);
			$pdf->Cell(1,3.5,'',0, 0);
			$counter++;
			$ts_count_months++;
			if ($ts_count_months > 12) {$ts_count_months = 1; $ts_count_year++; }
			}
			$pdf->Cell(1,3.5,'',0, 0);
			
			$pdf->SetFont('Helvetica','',8);
			$pdf->Cell(0,3.5,'TOTAL',0, 1, C, 1);
			$pdf->SetFont('Helvetica','',8);
			$pdf->Cell(0,2,'',0, 1);
			
		

	

include("inc_files/inc_pdf_timesheet_1.php");

// Add the footnotes

	
	$pdf->Cell(0,5,'',0, 1, L, 0);	
	$pdf->SetFont('Helvetica','',8);
	$pdf->SetTextColor('0','0','0');
	$pdf->Cell(0,4,'Notes:',0, 1, L, 0);
	$pdf->SetFont('Helvetica','',8);
	$pdf->Cell(0,4,'All figures given above include expenses (excluding unverified expenses), staff hours and overheads',0, 1, L, 0);
	$pdf->Cell(0,4,'Displayed invoice figures exclude VAT, and indicate invoices issued, not invoices paid',0, 1, L, 0);
	$pdf->Cell(0,3,'',0, 1, L, 0);
	$pdf->Cell(0,5,'* Figures given in the Baseline column include costs and expenses before the first month listed, as well as cost datums entered for each project',0, 1, L, 0);
	$pdf->Cell(0,5,'† The column for the most recent month is excluded from the Total column to avoid incomplete figures for the most recent month',0, 1, L, 0);
	
$sql = "SELECT * FROM riba_stages order by riba_letter";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($array = mysql_fetch_array($result)) {
$riba_id = $array['riba_id'];
$riba_letter = $array['riba_letter'];
$riba_desc = $array['riba_desc'];

$list_procure = $list_procure.$riba_letter.": ".$riba_desc." | ";

}

	$pdf->Cell(0,5,'',0, 1, L, 0);
	$pdf->SetFont('Helvetica','',8);
	$pdf->Cell(0,4,'RIBA Work Stages:',0, 1, L, 0);
	$pdf->SetFont('Helvetica','',8);
	$pdf->Cell(0,4,'(indicated in brackets after project title where applicable)',0, 1, L, 0);
	$pdf->MultiCell(175,4,$list_procure);
		
// and send to output

$pdf->Output();
?>
