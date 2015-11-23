<?php

include "inc_files/inc_checkcookie.php";

if ($user_usertype_current <= 3) { header ("Location: index2.php"); } else {

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

$pdf->SetY(50);

$pdf->SetFont($format_font,'',14);

// Determine name of project

$sql = "SELECT proj_num, proj_name FROM intranet_projects WHERE proj_id = '$proj_submit'";
$result = mysql_query($sql, $conn) or die(mysql_error());
$array = mysql_fetch_array($result);

$proj_num = $array['proj_num'];
$proj_name = $array['proj_name'];

$print_submit_begin = date("l, jS F Y",$_POST[submit_begin]);
$print_submit_begin = "from ".$print_submit_begin;

if ($_POST[submit_end] > 0 ) { $print_submit_end = $_POST[submit_end]; } else { $print_submit_end = time(); }

$print_submit_end = date("l jS F Y",$print_submit_end);

if ($_POST[submit_begin] == NULL OR $_POST[submit_begin] == 0) { $print_submit_begin = ""; }

$ts_print_title = "Schedule for ".$proj_num." ".$proj_name.",".$print_submit_begin." to ".$print_submit_end;

$pdf->SetFont($format_font,'',12);
$pdf->MultiCell(0,6,$ts_print_title,0, L, 0);

// Printed by, and on...

$pdf->SetFont($format_font,'',12);
$pdf->SetTextColor(180,180,180);

$sql = "SELECT user_name_first, user_name_second FROM intranet_user_details WHERE user_id = $_COOKIE[user]";
$result = mysql_query($sql, $conn) or die(mysql_error());
$array = mysql_fetch_array($result);

$user_name_first = $array['user_name_first'];
$user_name_second = $array['user_name_second'];

$pdf->SetFont($format_font,'',7);

$printed_on = "Generated ".date("r")." by ".$user_name_first." ".$user_name_second;

$pdf->Cell(0,10,$printed_on,0, 1, L, 0);

$pdf->SetFillColor(220, 220, 220);

$pdf->SetTextColor(0,0,0);

// Begin the array through all users

$sql = "SELECT * FROM intranet_user_details, intranet_timesheet LEFT JOIN intranet_timesheet_fees ON ts_project = ts_fee_stage WHERE ts_user = user_id AND ts_project = '$proj_submit' AND ts_entry BETWEEN '$time_submit_begin' AND '$time_submit_end' ORDER BY ts_stage_fee, ts_entry ";
$result = mysql_query($sql, $conn) or die(mysql_error());

$current_fee_stage = NULL;
$running_cost = 0;
$current_id = NULL;
$stage_total = 0;
$hours_total = 0;


		function StageTotal() {
		GLOBAL $pdf;
		GLOBAL $stage_total;
		GLOBAL $hours_total;
		GLOBAL $running_cost_nf;
		GLOBAL $ts_fee_target;
		
		$invoice_cost = $stage_total * $ts_fee_target;
		$invoice_cost_print = "£" . number_format($invoice_cost,2);
		
				$stage_total_print = "£" . number_format($stage_total,2);
				$pdf->Cell(0,1,'',T,1);
				$pdf->Cell(20,8,'Stage Total', 0, 0, L);
				$pdf->Cell(20,8,$hours_total, 0, 0, R);
				$pdf->Cell(48,8,$stage_total_print, 0, 1, R);
				$pdf->Cell(0,3,'',0,1);
				$stage_total = 0;
				$hours_total= 0;
				$running_cost_nf = 0;
				
				$ts_fee_target_print = ($ts_fee_target - 1) * 100;
				$ts_fee_target_print = "Stage Cost (including profit at " . number_format($ts_fee_target_print,2) . "%)";
				
				$pdf->Cell(0,1,'',T,1);
				$pdf->Cell(40,8,$ts_fee_target_print, 0, 0, L);
				$pdf->Cell(48,8,$invoice_cost_print, 0, 1, R);
				$pdf->Cell(0,3,'',0,1);
	
		}
		
		function StageFee($ts_fee_id) {
			
			if ($ts_fee_id > 0) {
				GLOBAL $conn;
				GLOBAL $pdf;
				$sql = "SELECT ts_fee_value FROM intranet_timesheet_fees WHERE ts_fee_id = $ts_fee_id";
				$result = mysql_query($sql, $conn) or die(mysql_error());
				$array = mysql_fetch_array($result);
				$ts_fee_value_print = "Stage Fee (including profit) " . number_format($array['ts_fee_value'],2);
				$pdf->Cell(0,1,'',T,1);
				$pdf->Cell(0,8,$ts_fee_target_print, 0, 0, L);
				
			}
		}

	while ($array = mysql_fetch_array($result)) {
	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];
	$user_name =  $user_name_first . " " . $user_name_second;
	$user_id = $array['user_id'];
	$user_initials = $array['user_initials'];
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
	//$ts_rate = $array['ts_cost_factored'];
	$ts_cost_factored = $array['ts_cost_factored'];
	
	$ts_cost_nf = $ts_rate * $ts_hours;
	
	$sql_fee_text = "SELECT ts_fee_id, ts_fee_text, ts_fee_target FROM intranet_timesheet_fees WHERE ts_fee_id = '$ts_stage_fee' LIMIT 1";
	$result_fee_text = mysql_query($sql_fee_text, $conn) or die(mysql_error());
	$array_fee_text = mysql_fetch_array($result_fee_text);
	$ts_fee_text = $array_fee_text['ts_fee_text'];
	$ts_fee_target = $array_fee_text['ts_fee_target'];
	//$ts_stage_fee = $array_fee_text['ts_stage_fee'];
	
	if ( $ts_fee_target == NULL) { $ts_fee_target = 1.3; }
	
	if ($ts_stage_fee == 0) { $ts_fee_text = "Unassigned"; }
	

	
	// Add stage header if this is the first of the stage entries
	
	
	if ($current_fee_stage != $ts_stage_fee) {
	
		// Add the stage total if necessary
		
			if ($current_fee_stage != NULL AND $_POST[separate_pages] != 1) {
				StageTotal($stage_total);
				StageFee($ts_stage_fee);
			} elseif ($current_fee_stage != NULL) {
				$pdf->SetFont($format_font,'',10);
				$pdf->Cell(20,7,'Total', T, 0, L);
				$hours_total_print = number_format($hours_total);
				$pdf->Cell(8,7,$hours_total_print, T, 0, R);
				$pdf->Cell(0,7,'hours', T, 1, L);
				$hours_total = 0;
			}
			
		if ($_POST[separate_pages] == 1 && $current_fee_stage != NULL) { $pdf->AddPage(); }
		
		$current_y = $pdf->GetY();
		if ($current_y > 260) { $pdf->addPage(); }
		
		$pdf->SetFont($format_font,'',10);
		$pdf->Ln();
		$pdf->Cell(0,7,$ts_fee_text, 0, 1, L, true);
		$current_fee_stage = $ts_stage_fee;
	}

		$hours_total = $hours_total + $ts_hours;
	
					
					
					$entry_day = $ts_day." / ".$ts_month." / ".$ts_year; // . ",";
					
					$entry_cost = $ts_cost_factored ;
					
					$line_cost = "= £" . number_format($entry_cost * $ts_hours,2);
					
					$entry_cost_print = "£" . number_format($entry_cost,2);
					$ts_cost_nf_print = "(£" . number_format($ts_cost_nf,2) . ")";
					
					
					
					$view_hours = $ts_hours; //  . ",";
					
					$running_cost = $running_cost + $ts_cost_factored;
					$running_cost_nf = $running_cost_nf + $ts_cost_nf;
					
					$total_cost_nf = $total_cost_nf + $ts_cost_nf;
					
					$stage_total = $stage_total + ($ts_cost_factored);
					
					$running_cost_print = "£" . number_format($stage_total,2);
					$running_cost_nf_print = "(£" . number_format($running_cost_nf,2) . ")";
					
					$pdf->SetFont($format_font,'',8);
					$pdf->SetTextColor(0,0,0);
					
					$pdf->SetDrawColor(220,220,220);
					$pdf->Cell(0,1,'',T,1);
					
					$pref_location = "http://intranet.rcka.co.uk/";
					
					$ts_link = $pref_location . "popup_timesheet.php?week=" . BeginWeek($ts_entry) . "&ts_id=" . $ts_id . "&user_view=" . $user_id;
					
						$pdf->Cell(20,4,$entry_day,0, 0, L, 0, $ts_link);
						$pdf->Cell(8,4,$view_hours,0, 0, R, 0);
						if ($_POST[separate_pages] != 1) {
						$pdf->Cell(5,4,'hrs',0, 0, C, 0);
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
					
					$pdf->SetFont($format_font,'',10);
					
	
}



			if ($_POST[separate_pages] != 1) {
				StageTotal($stage_total);
				StageFee($ts_stage_fee);
			} else {
				$pdf->SetFont($format_font,'',10);
				$pdf->Cell(20,7,'Total', T, 0, L);
				$hours_total_print = number_format($hours_total);
				$pdf->Cell(8,7,$hours_total_print, T, 0, R);
				$pdf->Cell(0,7,'hours', T, 1, L);
				$hours_total = 0;
			}
			
			
			
			
			
			



	if ($_POST[separate_pages] != 1) {

			StageTotal($stage_total);

			$cost_total_print = "£".number_format($running_cost,2);
			
			$total_cost_nf_print = "(£".number_format($total_cost_nf,2) . ")";

			$pdf->SetFont($format_font,'',12);
			$pdf->Cell(0,2,'',0, 1, L, 0);	
			$pdf->SetFillColor(240,240,240);
			$pdf->Cell(68,7,'Total Cost',0, 0, L, 0);
			$pdf->Cell(20,7,$cost_total_print,0, 1, R, 0);
			$pdf->Cell(20,7,$total_cost_nf_print,0, 1, R, 0);
			
			StageFee($ts_stage_fee);
	
	}
	

// and send to output

$print_begin = $_POST[submit_begin];
$print_end = $_POST[submit_end];

$file_name = "Project_Analysis_".$proj_num . ".pdf";

$pdf->Output($file_name,I);

}
?>
