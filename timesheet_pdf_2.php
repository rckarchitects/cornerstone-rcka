<?php

include_once "inc_files/inc_checkcookie.php";

if ($user_usertype_current <= 3) { header ("Location: index2.php"); } else {
	
	
include_once "inc_files/inc_action_functions_pdf.php";


// Functions for use on this pagecount

	// Establish the parameters for what we are showing

		$time_submit_begin = $_POST[submit_begin];
		$time_submit_end = $_POST[submit_end];
		if ($_POST[submit_project] > 0) { $proj_submit = $_POST[submit_project]; } elseif ($_GET[proj_id] > 0) { $proj_submit = $_GET[proj_id]; } else { header ("Location: index2.php"); }

		if ($time_submit_begin == NULL) { $time_submit_begin = 0; }
		if ($time_submit_end == NULL) { $time_submit_end = time(); }

$format_bg_r = "220";
$format_bg_g = "220";
$format_bg_b = "220";

if ($settings_pdffont != NULL) {
$format_font = $settings_pdffont;
$format_font_2 = $settings_pdffont.".php";
} else {
$format_font = "franklingothicbook";
$format_font_2 = "franklingothicbook.php";
}


//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->useTemplate($tplidx, 0, 0, 210, 297);

$pdf->AddFont($format_font,'',$format_font_2);

// Determine name of project

ProjectHeading($proj_submit,"Timesheet Analysis");

// Printed by, and on...

$pdf->MultiCell(0,5,'Use this form with care - the figures are not yet updating properly and the profit targets are not showing correctly.');

$pdf->SetFont($format_font,'',12);
$pdf->SetTextColor(0,0,0);


$pdf->SetFont($format_font,'',6);
$pdf->Cell(0,4,$printed_on,0, 1, L, 0);
$pdf->Ln();



$legend = "Cost (Fa) = Factored cost\nCost (Ho) = Hourly cost (not factored)\nCost (Fa Acc) = Factored accumulative cost\nCost (Ho Acc) = Hourly accumulative cost (not factored)\n";

$pdf->MultiCell(0,3,$legend);

$pdf->SetFillColor(220, 220, 220);


// Begin the array through all users

$sql = "SELECT * FROM intranet_user_details, intranet_timesheet LEFT JOIN intranet_timesheet_fees ON ts_project = ts_fee_stage WHERE ts_user = user_id AND ts_project = '$proj_submit' AND ts_entry BETWEEN '$time_submit_begin' AND '$time_submit_end' ORDER BY ts_fee_commence, ts_stage_fee, ts_fee_time_begin, ts_entry, ts_id";
$result = mysql_query($sql, $conn) or die(mysql_error());


$current_fee_stage = NULL;
$running_cost = 0;
$current_id = NULL;
$stage_total = 0;
$hours_total = 0;




	while ($array = mysql_fetch_array($result)) {
	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];
	$user_name =  $user_name_first . " " . $user_name_second;
	$user_id = $array['user_id'];
	$user_initials = $array['user_initials'];
	$user_prop = $array['user_prop'];
	$user_prop_target = $array['user_prop_target'];
	
	$user_prop_actual = $user_prop;
	
	//if ($user_prop) { $user_prop_actual = $user_prop; } elseif ($user_prop_target) { $user_prop_actual = $user_prop_target; } else { $user_prop_actual = 0; }
	$user_prop_actual = (1 - $user_prop_actual);
	
	$ts_id = $array['ts_id'];
	$ts_entry = $array['ts_entry'];
	$ts_day = $array['ts_day'];
	$ts_month = $array['ts_month'];
	$ts_year = $array['ts_year'];
	$ts_user = $array['ts_user'];
	$ts_hours = $array['ts_hours'];
	$ts_desc = ucwords($array['ts_desc']);
	$ts_rate = $array['ts_rate'];
	$ts_overhead = $array['ts_overhead'];
	$ts_projectrate = $array['ts_projectrate'];
	$ts_fee_id = $array['ts_fee_id'];
	$ts_stage_fee = $array['ts_stage_fee'];

	//$ts_cost_factored = $array['ts_cost_factored'] * (1 - $user_prop_target);
	//$ts_cost_factored = $ts_cost_factored * ((1 - $user_prop) / (1 - $user_prop_target));
	
	$ts_cost_factored = $array['ts_cost_factored'];
	
	$ts_cost_nf = $ts_rate * $ts_hours ;
	
	$sql_fee_text = "SELECT ts_fee_id, ts_fee_text, ts_fee_target FROM intranet_timesheet_fees WHERE ts_fee_id = '$ts_stage_fee' LIMIT 1";

	
	$result_fee_text = mysql_query($sql_fee_text, $conn) or die(mysql_error());
	$array_fee_text = mysql_fetch_array($result_fee_text);
	
	$ts_fee_target = $array_fee_text['ts_fee_target'];
	if ( $ts_fee_target == NULL) { $ts_fee_target = 1; }
	$ts_fee_text = $array_fee_text['ts_fee_text'] . " (" . $ts_fee_target . ")";
	//$ts_stage_fee = $array_fee_text['ts_stage_fee'];
	
	
	
	if ($ts_stage_fee == 0) { $ts_fee_text = "Unassigned"; }
	

	
	// Add stage header if this is the first of the stage entries
	
	
	if ($current_fee_stage != $ts_stage_fee) {
	
		// Add the stage total if necessary
		
			if ($current_fee_stage != NULL AND $_POST[separate_pages] != 1) {
				StageTotal($stage_total, $hours_total, $running_cost_nf, $ts_fee_target );
				StageFee($ts_stage_fee);
			} elseif ($current_fee_stage != NULL) {
				$pdf->SetFont($format_font,'',8);
				$pdf->Cell(20,7,'Total', T, 0, L);
				$hours_total_print = number_format($hours_total);
				$pdf->Cell(8,7,$hours_total_print, T, 0, R);
				$pdf->Cell(0,7,'hours', T, 1, L);
				$hours_total = 0;
			}
			
		if ($current_fee_stage != NULL) { $pdf->AddPage(); }
		
		$current_y = $pdf->GetY();
		if ($current_y > 240) { $pdf->addPage(); }
		
		$pdf->SetFont('Helvetica','B',9, true);
		$pdf->Ln();
		$pdf->Cell(0,6,$ts_fee_text, 0, 1, L, true);
		$pdf->SetFont($format_font,'',6, true);
		$pdf->Cell(20,4,'Date',0,0,L, true);
		$pdf->Cell(13,4,'Hours',0,0,R, true);
		$pdf->Cell(15,4,'Cost (Fa)',0,0,R, true);
		$pdf->Cell(15,4,'Cost (Ho)',0,0,R, true);
		$pdf->Cell(8,4,'Init',0,0,C, true);
		$pdf->Cell(20,4,'Cost (Fa Acc)',0,0,R, true);
		$pdf->Cell(20,4,'Cost (Ho Acc)',0,0,R, true);
		$pdf->Cell(0,4,'Description',0,0,L, true);
		$pdf->Ln();
		$current_fee_stage = $ts_stage_fee;
	}

		$hours_total = $hours_total + $ts_hours;
	
					
					
					$entry_day = $ts_day." / ".$ts_month." / ".$ts_year; // . ",";
					
					$entry_cost = $ts_cost_factored ;
					
					$line_cost = "= " . PDFCurrencyFormat($entry_cost * $ts_hours);
					
					$entry_cost_print = PDFCurrencyFormat($entry_cost);
					$ts_cost_nf_print = "(" . PDFCurrencyFormat($ts_cost_nf) . ")";
					
					
					
					$view_hours = $ts_hours; //  . ",";
					
					$running_cost = $running_cost + $ts_cost_factored;
					$running_cost_nf = $running_cost_nf + $ts_cost_nf;
					
					$total_cost_nf = $total_cost_nf + $ts_cost_nf;
					
					$stage_total = $stage_total + ($ts_cost_factored);
					
					$running_cost_print = PDFCurrencyFormat($stage_total);
					$running_cost_nf_print = "(" . PDFCurrencyFormat($running_cost_nf) . ")";
					
					$pdf->SetFont($format_font,'',8);
					$pdf->SetTextColor(0,0,0);
					
					$pdf->SetDrawColor(220,220,220);
					$pdf->Cell(0,1,'',T,1);
					
					$ts_link = $pref_location . "popup_timesheet.php?week=" . BeginWeek($ts_entry) . "&ts_id=" . $ts_id . "&user_view=" . $user_id;
					
						$pdf->Cell(20,4,$entry_day,0, 0, L, 0, $ts_link);
						$pdf->Cell(8,4,$view_hours,0, 0, R, 0);
						if ($_POST[separate_pages] != 1) {
						$pdf->Cell(5,4,'hrs',0, 0, R, 0);
						$pdf->Cell(15,4,$entry_cost_print,0, 0, R, 0);
						$pdf->Cell(15,4,$ts_cost_nf_print,0, 0, R, 0);
						$pdf->Cell(8,4,$user_initials,0, 0, C, 0);
						$pdf->Cell(20,4,$running_cost_print,0, 0, R, 0);
						$pdf->Cell(20,4,$running_cost_nf_print,0, 0, R, 0);
						} else {
						if ($view_hours <= 1) { $print_hours = "hour"; } else { $print_hours = "hours"; }
						$pdf->Cell(15,4,$print_hours,R, 0, L, 0);
						$pdf->Cell(40,4,$user_name,R, 0, L, 0);
						}
						
						$pdf->MultiCell(0,4,$ts_desc,L,L);
						
					$pdf->Cell(0,1,'',0,1);
					
					$pdf->SetFont($format_font,'',8);
					
	
}

			if ($_POST[separate_pages] != 1) {
				StageTotal($stage_total, $hours_total, $running_cost_nf, $ts_fee_target );
				StageFee($ts_stage_fee);
			} else {
				$pdf->SetFont($format_font,'',8);
				$pdf->Cell(20,5,'Total', T, 0, L);
				$hours_total_print = number_format($hours_total);
				$pdf->Cell(8,5,$hours_total_print, T, 0, R);
				$pdf->Cell(0,5,'hours', T, 1, L);
				$hours_total = 0;
			}
			
			
			
			
			
			



	if ($_POST[separate_pages] != 1) {

			$cost_total_print = PDFCurrencyFormat($running_cost);
			
			$total_cost_nf_print = "(" . PDFCurrencyFormat($total_cost_nf) . ")";

			$pdf->SetFont($format_font,'',8);
			$pdf->Ln();	
			$pdf->SetFillColor(240,240,240);
			$pdf->SetLineWidth(0.5);
			$pdf->Cell(71,5,'Total Cost',0, 0, L, 0);
			$pdf->Cell(20,5,$cost_total_print,0, 0, R, 0);
			$pdf->Cell(20,5,$total_cost_nf_print,0, 1, R, 0);
			
			StageFee($ts_stage_fee);
	
	}
	

// and send to output

$print_begin = $_POST[submit_begin];
$print_end = $_POST[submit_end];

$file_name = "Project_Analysis_".$proj_num . ".pdf";

$pdf->Output($file_name,I);

}
?>
