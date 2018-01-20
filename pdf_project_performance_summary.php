<?php

include "inc_files/inc_checkcookie.php";

if ($user_usertype_current < 4) { header ("Location: index2.php"); }

include "inc_files/inc_action_functions_pdf.php";

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage(L);

if ($settings_pdffont != NULL) {
$format_font = $settings_pdffont;
$format_font_2 = $settings_pdffont.".php";
} else {
$format_font = "franklingothicbook";
$format_font_2 = "franklingothicbook.php";
}

$pdf->AddFont($format_font,'',$format_font_2);


$format_bg_r = "0";
$format_bg_g = "0";
$format_bg_b = "0";

$format_ln_r = "0";
$format_ln_g = "0";
$format_ln_b = "0";



$current_date = TimeFormat(time());

// Begin creating the page




function EstablishStageCost($ts_fee_id) {
	
		global $conn;
		
		$ts_fee_id = intval($ts_fee_id);
		
		$sql = "SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE ts_stage_fee = $ts_fee_id";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		$output = floatval($array['SUM(ts_cost_factored)']);
		return $output;
		
}

function PDFStageAnalysis($proj_id,$proj_value) {

	global $conn;
	global $pdf;
	//global $format_font;
	
	$proj_id = intval($proj_id);
	
	$average_weekly_cost = 1800;
	
	$pdf->SetFont($format_font,'',7);
	$pdf->Cell(100,5,"Fee Stage",0,0);
	$pdf->Cell(25,5,"Fee Value",0,0,'R');
	$pdf->Cell(25,5,"Start Date",0,0,'R');
	$pdf->Cell(20,5,"Length (weeks)",0,0,'R');
	$pdf->Cell(15,5,"Target Profit",0,0,'R');
	$pdf->Cell(15,5,"Staff / Week",0,0,'R');
	$pdf->Cell(25,5,"Target / Actual Cost",0,0,'R');
	$pdf->Cell(0,5,"Stage Fee %",0,1,'R');
	
	$sql = "SELECT * FROM intranet_timesheet_fees LEFT JOIN intranet_timesheet_group ON ts_fee_group = group_id WHERE ts_fee_project = $proj_id ORDER BY ts_fee_commence";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	$fee_total = 0;
	$fee_target_total = 0;
	$cost_actual = 0;
	$target_fee_percentage_actual_total = 0;
	$start_date = 0;
	
	while ($array = mysql_fetch_array($result)) {
			
			$ts_fee_text = $array['ts_fee_text'];			
			if ($array['group_code']) { $ts_fee_text = $array['group_code'] . ". " . $ts_fee_text; }
		
			
			$pdf->SetFont('Helvetica','',9);
			
			$stage_length_actual = round ( $array['ts_fee_time_end'] / 604800);
			$stage_length_datum = round ( $array['ts_datum_length'] / 604800 );
			
			$ts_fee_target = ((1 - $array['ts_fee_target']) * 100 ) * -1;
			$ts_fee_target_percent = $ts_fee_target . "%";
			
			$ts_fee_target_actual = $array['ts_fee_value'] / $array['ts_fee_target'];
			$ts_fee_target_actual_print = PDFCurrencyFormat ($ts_fee_target_actual);
			
			$ts_fee_value_print = PDFCurrencyFormat($array['ts_fee_value']);
			
			$staff_per_week = number_format ( 	($array['ts_fee_value'] / $array['ts_fee_target']) / ( $array['ts_datum_length'] / 604800 ) / $average_weekly_cost	,2);
			
			$ts_fee_actual = EstablishStageCost($array['ts_fee_id']);
			$ts_fee_actual_print = PDFCurrencyFormat($ts_fee_actual);
			
			$target_fee_percentage_target = $array['ts_fee_value'] / $proj_value;
			$target_fee_percentage_actual = ($array['ts_fee_target'] * $ts_fee_actual) / $proj_value;
			
			$target_fee_percentage_target_print = number_format ((100 * $target_fee_percentage_target),2) . "%" ;
			$target_fee_percentage_actual_print = number_format ((100 * $target_fee_percentage_actual),2) . "%" ;
			
			$staff_per_week_actual_print = number_format ($ts_fee_actual / ($array['ts_fee_time_end'] / 604800) / $average_weekly_cost	,2);
			
			$ts_datum_commence_print = TimeFormat(AssessDays($array['ts_datum_commence'],12));
			$ts_fee_commence_print = TimeFormat(AssessDays($array['ts_fee_commence'],12));
			
			$pdf->Cell(100,10,$ts_fee_text,'B',0);
			$pdf->SetLineWidth(0.1);
			$pdf->Cell(25,10,$ts_fee_value_print,0,0,'R');
			$pdf->Cell(25,5,$ts_datum_commence_print,'B',0,'R');
			$pdf->Cell(20,5,$stage_length_datum,'B',0,'R');
			$pdf->Cell(15,5,$ts_fee_target_percent,'B',0,'R');
			$pdf->Cell(15,5,$staff_per_week,'B',0,'R');
			$pdf->Cell(25,5,$ts_fee_target_actual_print,'B',0,'R');
			$pdf->Cell(0,5,$target_fee_percentage_target_print,'B',1,'R');

			$pdf->SetX(135);
			if ($array['ts_fee_commence'] > $array['ts_datum_commence']) { $pdf->SetTextColor(255,0,0); } else { $pdf->SetTextColor(0,0,0); }
			$pdf->Cell(25,5,$ts_fee_commence_print,0,0,'R');
			if ($array['ts_fee_time_end'] > $array['ts_datum_length']) { $pdf->SetTextColor(255,0,0); } else { $pdf->SetTextColor(0,0,0); }
			$pdf->Cell(20,5,$stage_length_actual,0,0,'R');
			$pdf->SetTextColor(0,0,0); 
			$pdf->Cell(15,5,$ts_fee_target_percent,'B',0,'R');
			$pdf->Cell(15,5,$staff_per_week_actual_print,'B',0,'R');
			if ($ts_fee_actual > $ts_fee_target_actual) { $pdf->SetTextColor(255,0,0); } else { $pdf->SetTextColor(0,0,0); }
			$pdf->Cell(25,5,$ts_fee_actual_print,'B',0,'R');
			$pdf->Cell(0,5,$target_fee_percentage_actual_print,'B',1,'R');
			$pdf->SetTextColor(0,0,0);			
			$pdf->SetLineWidth(0.5);
			$pdf->Cell(0,1,'','T',1);
			
			
			$fee_total = $fee_total + $array['ts_fee_value'];
			$fee_target_total = $fee_target_total + $ts_fee_target_actual;
			$cost_actual = $cost_actual + $ts_fee_actual;
			$target_fee_percentage_actual_total = $target_fee_percentage_actual_total + $target_fee_percentage_actual;
	}

	
	$fee_total_print = PDFCurrencyFormat( $fee_total );
	$fee_target_total_print = PDFCurrencyFormat( $fee_target_total );
	$cost_actual_print = PDFCurrencyFormat( $cost_actual );
	
	if ($proj_value) { $fee_total_print = $fee_total_print . " [" . number_format( ($fee_total / $proj_value * 100),2) . "%]"; }
	if ($proj_value) { $fee_target_percent_total_print = number_format( ($fee_target_total / $proj_value * 100),2) . "%"; }
	if ($proj_value) { $cost_actual_percent_print = number_format( ($target_fee_percentage_actual_total * 100),2) . "%"; }
	
	$pdf->Ln(2);
	$pdf->SetLineWidth(0.75);
	$pdf->SetFont('Helvetica','B',10);
	$pdf->Cell(100,10,"Total Fee",'B',0);
	$pdf->Cell(25,10,$fee_total_print,'B',0,'R');
	$pdf->SetFont('Helvetica','',9);
	$pdf->Cell(50,5,"Target Fee",0,0,'R');
	$pdf->SetFont('Helvetica','B',10);
	if ($fee_target_total > $fee_total) { $pdf->SetTextColor(255,0,0); } else { $pdf->SetTextColor(0,0,0); }
	$pdf->Cell(50,5,$fee_target_total_print,0,0,'R');
	$pdf->Cell(0,5,$fee_target_percent_total_print,0,1,'R');
	$pdf->SetX(135);
	$pdf->SetFont('Helvetica','',9);
	$pdf->Cell(50,5,"Actual Fee",'B',0,'R');
	$pdf->SetFont('Helvetica','B',10);
	if ($cost_actual > $fee_target_total) { $pdf->SetTextColor(255,0,0); } else { $pdf->SetTextColor(0,0,0); }
	$pdf->Cell(50,5,$cost_actual_print,'B',0,'R');
	$pdf->Cell(0,5,$cost_actual_percent_print,'B',1,'R');
	
	$pdf->SetTextColor(0,0,0);
	
}


function PDFProjectArray($proj_id) {

	global $conn;
	global $pdf;
	
	$pdf->SetTextColor(0,0,0);
	
	if (intval($proj_id) > 0) { $proj_id_filter = "AND proj_id = " . intval($proj_id); } else { unset($proj_id_filter); } 
	
	//global $format_font;
		$sql = "SELECT proj_id, proj_num, proj_name, proj_type, proj_value FROM intranet_projects WHERE proj_active = 1 AND proj_fee_track = 1 $proj_id_filter ORDER BY proj_num";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$current_project = 0;
		while ($array = mysql_fetch_array($result)) {

			if ($current_project != $array['proj_id']) {
				if ($current_project > 0) { $pdf->addPage(L); }
				$proj_num = $array['proj_num'] . " " . $array['proj_name']; $current_project = $array['proj_id'];
				$pdf->SetFont('Helvetica','B',16);
				$pdf->Cell(150,10,$proj_num,0,0);
				$pdf->Cell(0,10,"Project Performance Summary",0,1,'R');
				$pdf->Ln(2.5);
				$pdf->SetFont($format_font,'',7);
				$pdf->Cell(75,5,"Project Type",'T',0);
				$pdf->Cell(50,5,"Contract Value",'T',0,'R');
				$pdf->Cell(75,5,"Procurement Method",'T',0,'R');
				$pdf->Cell(0,5,"",'T',1);
				$pdf->SetFont($format_font,'B',10);
				$pdf->Cell(75,5,$array['proj_type'],'B',0);
				$proj_value = "£" . number_format ($array['proj_value'], 2);
				$proj_value = utf8_decode($proj_value);
				$pdf->Cell(50,5,$proj_value,'B',0,'R');
				$pdf->Cell(75,5,$array['proj_procure'],'B',0,'R');
				$pdf->Cell(0,5,"",'B',1);
			}
			
			
			PDFStageAnalysis($array['proj_id'],$array['proj_value']);

			
		}
}

PDFProjectArray($_GET[proj_id]);
	

// and send to output

$file_date = time();

$file_name = $proj_num."_2.05_".Date("Y",$file_date)."-".Date("m",$file_date)."-".Date("d",$file_date)."_Drawing_Schedule.pdf";

$pdf->Output($file_name,I);
