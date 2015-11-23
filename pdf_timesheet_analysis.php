<?php

$profit = 1.25;

function InsertPage($input) {
}

include_once "inc_files/inc_checkcookie.php";
include_once "inc_files/inc_actions_functions.php";


if ($user_usertype_current <= 3) { header ("Location: index2.php"); } else {

$remove_symbols = array("Â","Ã");
$swap_1 = array("â‚¬", "\n", "&amp;");
$replace_1 = array("€", ", ", "&");

$format_bg_r = "220";
$format_bg_g = "220";
$format_bg_b = "220";

// Define the colours for the bars
function SetBarDBlue() { GLOBAL $pdf; $pdf->SetFillColor(120, 190, 240); }
function SetBarOrange() { GLOBAL $pdf; $pdf->SetFillColor(255, 200, 0); }
function SetBar2($alert) { GLOBAL $pdf; if ($alert == "red") { $pdf->SetFillColor(250, 0, 0); } elseif ($alert == "orange") { $pdf->SetFillColor(250, 190, 0); } else { $pdf->SetFillColor(200, 225,115); } }
function SetBarLBlue() { GLOBAL $pdf; $pdf->SetFillColor(190, 220, 240); }
function SetBar4() { GLOBAL $pdf; $pdf->SetFillColor(180, 250, 100); }
function SetBarLGray() { GLOBAL $pdf; $pdf->SetFillColor(240, 240, 240); }
function SetBarDGray() { GLOBAL $pdf; $pdf->SetFillColor(175, 175, 175); }
function SetBarRed() { GLOBAL $pdf; $pdf->SetFillColor(255, 0, 0); }
function SetBar7($alert) { GLOBAL $pdf; if ($alert == "red") { $pdf->SetFillColor(255, 180,180); } elseif ($alert == "orange") { $pdf->SetFillColor(250, 220, 130); } else { $pdf->SetFillColor(200, 250, 100); }  }
function SetBarDGreen() { GLOBAL $pdf; $pdf->SetFillColor(200, 250, 100); }
function SetBarPurple() { GLOBAL $pdf; $pdf->SetFillColor(175, 125, 200); }
function SetBarYellow() { GLOBAL $pdf; $pdf->SetFillColor(255, 255, 75); }

// Functions for page separations
function PageBreak() {
// GLOBAL $pdf; $y = $pdf->GetY(); if ($y > 250) { $pdf->addPage(); $pdf->SetY(10); }
}

function TotalCost($proj_id, $bar_scale, $bar_width_standard) {

		GLOBAL $pdf;
		GLOBAL $bar_width_standard;
		GLOBAL $conn;
				
		$sql_cost_proj = " SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE `ts_project` = $proj_id ";
		$result_cost_proj = mysql_query($sql_cost_proj, $conn) or die(mysql_error());
		$array_cost_proj = mysql_fetch_array($result_cost_proj);
		$cost_proj = $array_cost_proj['SUM(ts_cost_factored)'];
		
		$sql_fee_proj = "SELECT SUM(ts_fee_value) FROM intranet_timesheet_fees LEFT JOIN intranet_timesheet ON ts_fee_stage = ts_stage_fee WHERE `ts_fee_project` = $proj_id AND ts_id > 0";
		//$sql_fee_proj = " SELECT SUM(ts_fee_value), SUM(ts_fee_value * (1/ts_fee_target)) FROM intranet_timesheet_fees WHERE `ts_fee_project` = $proj_id AND ts_fee_prospect = 100 ";
		$result_fee_proj = mysql_query($sql_fee_proj, $conn) or die(mysql_error());
		$array_fee_proj = mysql_fetch_array($result_fee_proj);
		$fee_proj = $array_fee_proj['SUM(ts_fee_value)'];
		$fee_target = $array_fee_proj['SUM(ts_fee_value * (1/ts_fee_target))'];
		
		$fee_profit = $fee_proj - $fee_target;
		
		$bar_proj_target = ($fee_target / $bar_scale) * $bar_width_standard;
		$bar_proj_fee = ($fee_proj / $bar_scale) * $bar_width_standard;
		
		$bar_fee_profit = $bar_proj_fee - $bar_proj_target;
		
		if ($bar_fee_profit == 0) { $bar_fee_profit = 0.1; }
		
		$fee_proj_print = "£" . number_format($fee_proj,2);
		
		$bar_proj_cost = ($cost_proj / $bar_scale) * $bar_width_standard;
		
		$cost_proj_print = "£" . number_format($cost_proj,2);
		
		$pdf->SetTextColor(150,150,150);
		$pdf->Cell(0,1,'',0,1);
		$pdf->Cell(13,4,'',0,R,0);
		SetBarDGray();
		$pdf->SetDrawColor(255,255,255);
		$pdf->SetLineWidth(1);
		$pdf->Cell($bar_proj_target,4,'',R, 0, L, 1, true);
		$pdf->Cell($bar_fee_profit,4,'',R, 0, L, 1, true);
		$pdf->SetFillColor(255,0,0);
		
		if ($cost_proj < $fee_proj) {
		$pdf->Cell(0,4,$cost_proj_print,0, 1, L); 
		} else { $pdf->Cell(0,4,'',0, 1, L); }

		$y = $pdf->GetY() - 4;
		$pdf->SetY($y);
		
		if ($cost_proj > $fee_proj) {
		SetBarRed();
		} elseif ($cost_proj > $fee_target) {
		SetBarOrange();
		} else {
		SetBarDGreen();
		}
		
		$pdf->SetTextColor(150,150,150);
		$pdf->Cell(13,4,$fee_proj_print,0,R,0);
		$pdf->SetDrawColor(255,255,255);
		$pdf->SetLineWidth(0.25);
		$pdf->Cell($bar_proj_cost,2,'',RB, 0, L, 1, true);
		$pdf->SetFillColor(255,0,0);
		if ($cost_proj > $fee_proj) {
		$pdf->Cell(0,4,$cost_proj_print,0, 1, L); 
		} else { $pdf->Cell(0,4,'',0, 1, L); }
		$pdf->Cell(0,1,'',0,1);
	
		$pdf->SetLineWidth(0.2);
}

function Checklist($proj_id) {

		GLOBAL $pdf;
		GLOBAL $bar_width_standard;
		GLOBAL $conn;

		$sql_checklist = "SELECT checklist_required, checklist_date, item_group FROM intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id AND checklist_project = $proj_id ORDER BY item_group, item_order, item_name";
		$result_checklist = mysql_query($sql_checklist, $conn) or die(mysql_error());
		
		$rows = mysql_num_rows($result_checklist);
		$width = (190 / $rows) - 0.75;
		
		unset($group);
		
		while ($array_checklist = mysql_fetch_array($result_checklist)) {
		
				$checklist_required = $array_checklist['checklist_required'];
				$checklist_date = $array_checklist['checklist_date'];
				$item_group = $array_checklist['item_group'];
				$group_rows = $array_checklist['COUNT[item_group]'];
				
		if ($checklist_required == "2" AND $checklist_date == "0000-00-00" ) { $pdf->SetFillColor(245,72,72); } // Red
		elseif ($checklist_required == "2" AND $checklist_date == NULL) { $pdf->SetFillColor(245,72,72); } // Red
		elseif ($checklist_required == "0") { $pdf->SetFillColor(245,190,72); } // Red
		elseif ($checklist_required == NULL) { $pdf->SetFillColor(245,190,72); } // Orange
		elseif ($checklist_required == "1") { $pdf->SetFillColor(220,220,220); } // Grey
		else { $pdf->SetFillColor(173,233,28); }  // Green
						
						$pdf->SetDrawColor(255,255,255);
						
						if ($checklist_required != 1) {
							if ($group != NULL && $group != $item_group) { $pdf->Cell(0.75,2,'',0,0,C,false); }
							$pdf->Cell($width,2,'',1,0,C,true);
						}
						
			$group = $item_group;		
		
		}
		
			$current_y = $pdf->GetY() + 4;
			
			$pdf->SetX(0);
			$pdf->SetY($current_y);
}


//if ($settings_pdffont != NULL) {
//$format_font = $settings_pdffont;
//$format_font_2 = $settings_pdffont.".php";
//} else {
$format_font = "Helvetica";
$format_font_2 = "franklingothicbook.php";
//}

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);
$pdf->addPage();
$pdf->useTemplate($tplidx);

$pdf->SetAutoPageBreak(0,1.5);

$pdf->AddFont($format_font,'',$format_font_2);

// Page headers

	$project_counter = 1;
	$page_count = 1;

	$pdf->SetY(35);
	$pdf->SetFont('Helvetica','b',24);

	$pdf->SetTextColor($format_bg_r, $format_bg_g, $format_bg_b);
	$pdf->Cell(0,10,"Timesheet Analysis");

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetY(50);
	$pdf->SetFont('Helvetica','b',18);

	$print_title = "Generated " . TimeFormatDetailed(time());
	$print_envelope = "Timesheet Datum: " . TimeFormat($settings_timesheetstart);
	
	$pdf->SetFillColor(220, 220, 220);
	$pdf->MultiCell(0,8,$print_title,0, L, 0);
	
	$pdf->SetFont('Helvetica','b',12);
	$pdf->MultiCell(0,5,$print_envelope,0, L, 0);
	$pdf->MultiCell(0,5,$print_profit_text,0, L, 0);
	$pdf->Cell(0,5,'',0,2);
	
	$pdf->SetFont($format_font,'',10);
	$pdf->SetTextColor(0, 0, 0);

// Array through the projects and fee stages

	$bar_width_standard = 130;
	$effectively_zero = 0.1; // Sets the minimum bar size if the quantity is zero
	$page_number = 0;
	$complete_profit_total = 0;
	$complete_fee_total = 0;
	$complete_cost_total = 0;
	
// Establish the maximum fee for all projects by sorting an array

	$sql_fee_max = "SELECT SUM(ts_fee_value) FROM intranet_timesheet_fees WHERE ts_fee_value > 0 AND ts_fee_prospect = 100 GROUP BY ts_fee_project ORDER BY `SUM(ts_fee_value)` DESC";
	$result_fee_max_array = mysql_query($sql_fee_max, $conn) or die(mysql_error());
	$array_fee_max = mysql_fetch_array($result_fee_max_array);
	$maximum_total_fee = $array_fee_max[0];
	
// Now construct the main array through the projects and fee stages	

	$sql_projects = "SELECT * FROM intranet_projects LEFT JOIN intranet_timesheet_fees ON ts_fee_project = proj_id WHERE proj_fee_track = 1 AND proj_active = 1 AND ts_fee_prospect = 100 ORDER BY proj_num, ts_fee_time_begin";
	$result_projects = mysql_query($sql_projects, $conn) or die(mysql_error());


	
	while ($array_projects = mysql_fetch_array($result_projects)) {
	PageBreak();
	
	$proj_id = $array_projects['proj_id'];
	$ts_fee_id = $array_projects['ts_fee_id'];
	$ts_fee_text = $array_projects['ts_fee_text'];
	$ts_fee_value = $array_projects['ts_fee_value'];
	$ts_fee_target = $array_projects['ts_fee_target'];
	
	$proj_title = $array_projects['proj_num'] . " - " . $array_projects['proj_name'];
	$proj_riba = $array_projects['proj_riba'];
	
	$sql_fee_total = "SELECT SUM(ts_fee_value) FROM intranet_timesheet_fees WHERE ts_fee_project = $proj_id AND ts_fee_prospect = 100 GROUP BY ts_fee_project";
	$result_fee_total = mysql_query($sql_fee_total, $conn) or die(mysql_error());
	$array_fee_total = mysql_fetch_array($result_fee_total);
	$fee_total = $array_fee_total['SUM(ts_fee_value)'];
	
	$sql_cost_total = "SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE ts_project = '$proj_id'";
	$result_cost_total = mysql_query($sql_cost_total, $conn) or die(mysql_error());
	$array_cost_total = mysql_fetch_array($result_cost_total);
	$cost_total = $array_cost_total['SUM(ts_cost_factored)'];
	
	if ($cost_total > $fee_total) { $this_bar_width = $bar_width_standard * ( $cost_total / $maximum_total_fee); } else { $this_bar_width = $bar_width_standard * ( $fee_total / $maximum_total_fee); } 
	
	$project_profit = $fee_total * $profit;
	
	if ($cost_total > $fee_total) {
		$alert = "orange";
	} else {
		
		unset($alert);
	}
	
	if (($cost_total * (1 - $profit)) >= $fee_total) {
		$alert = "red";
	}
	
	// $maximum_total_fee : The largest fee from the database
	// $bar_width_standard : The maximum bar width (currently 130mm)
	// $fee_total : The total fees available for the project
	// $cost_total : The costs so far
	// $this_bar_width : The datum level using the standard width as a maximum
	// $bar_width_fee : Overall bar width for the total fee (mm)
	
	
	$bar_width_fee = ( $fee_total / $maximum_total_fee ) * $this_bar_width; // 
	$bar_width_cost = ( $cost_total / $maximum_total_fee) * $this_bar_width;
	
	
	if ($cost_total > 0 && $fee_total > 0) {
	
		$cost_total_print = str_replace( "&pound;" , "£" , MoneyFormat($cost_total) );
		$fee_total_print = str_replace( "&pound;" , "£" , MoneyFormat($fee_total) );
		
		

		
	
		if ($current_project != $proj_id) {
		
			if ($project_cost_total > 0) {
					
					
						$project_cost_total = 0;
						$project_fee_total_target = 0;
						$project_fee_total_profit = 0;
			
			}
		
			// Check that all of the project data will fit on this page by establishing the number of fee stages
			$sql_fee_stage_quantity = "SELECT ts_fee_stage FROM intranet_timesheet_fees WHERE ts_fee_project = $proj_id AND ts_fee_prospect = 100";
			$result_fee_stage_quantity = mysql_query($sql_fee_stage_quantity, $conn) or die(mysql_error());
			$fee_stage_quantity = mysql_num_rows($result_fee_stage_quantity);
			$height_fee_stage = 45 + ( $fee_stage_quantity * 6) ;
			$current_location_y = $pdf->GetY();
			if (($height_fee_stage + $current_location_y) > 285 && $height_fee_stage < 275) {
			$height_fee_stage = 0;
			$pdf->addPage();			
			}
			
			$proj_link = "http://intranet.rcka.co.uk/timesheet_pdf_2.php?proj_id=" . $proj_id;
			
			$pdf->Cell(0,8,'',0,1);
			
			
		
			
			
			
			$current_y = $pdf->GetY() + 1;
			
			
			
			
			
			
			
			
			
			
			// Create light grey bar
			SetBarLGray();
			$pdf->SetDrawColor(175,175,175);
			$pdf->SetTextColor(175,175,175);
			$pdf->Cell(0,7,'',BT,1,L,true);
			
			
		
			

			$pdf->SetDrawColor(175,175,175);
			$pdf->SetTextColor(175,175,175);
			
			if ($cost_total > $fee_total) { $bar_scale = $cost_total; } else { $bar_scale = $fee_total; }
			
			// Scale bar for costs
			if ($bar_scale < 10000) { $unit = 1000; } elseif ($bar_scale< 20000) { $unit = 2000; } elseif ($bar_scale< 50000) { $unit = 5000; } elseif ($bar_scale< 50000) { $unit = 10000; } elseif ($bar_scale< 100000) { $unit = 20000; } elseif ($bar_scale < 300000) { $unit = 25000; } else { $unit = 50000; }
			$unit_scale = 0;
			// Establish whether scale bar should be related to profit or cost
			$separator = ($bar_width_standard / $bar_scale) * $unit;
			$separator_count = 0;
			$pdf->SetFont('Helvetica','',5);
			$pdf->Cell(13,3,'Fee',R,0);
			while ($separator_count < $bar_width_standard) {
				$unit_scale_print = "£" . number_format($unit_scale);
				$pdf->Cell($separator,3,$unit_scale_print,R,0);
				$separator_count = $separator_count + $separator;
				$unit_scale = $unit_scale + $unit;
			}
			$unit_scale_print = "£" . number_format($unit_scale);
			$pdf->Cell(0,3,$unit_scale_print,0,1);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetTextColor(0,0,0);
			
			$pdf->SetY($current_y);
			$pdf->SetFont($format_font,'',9);
			$pdf->Cell(0,5,$proj_title,0, 1, L, 0, $proj_link);
			$pdf->SetFont($format_font,'',6);

			$pdf->SetX(10);
			$pdf->Cell(0,6,'',0,1);
			SetBar2("no");
			
					

			
			// Insert a line showing the PROJECT TOTAL if this is a new project we're about to start	
			TotalCost($proj_id, $bar_scale, $bar_width_standard);
			
			// Now a bar which shows the unassigned work
			
			$sql_cost_unassigned = " SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE `ts_stage_fee` = 0 AND `ts_project`= $proj_id";
				$result_cost_unassigned = mysql_query($sql_cost_unassigned, $conn) or die(mysql_error());
				$array_cost_unassigned = mysql_fetch_array($result_cost_unassigned);
				$cost_unassigned = $array_cost_unassigned['SUM(ts_cost_factored)'];
				
				$project_cost_total = $project_cost_total + $cost_unassigned;
				

			
			// if ($cost_unassigned = 0) { $cost_unassigned = $effectively_zero; }
			
			if ($cost_unassigned > 0) {
			
				$bar_width = ($cost_unassigned / $bar_scale) * $bar_width_standard;
				
		
				$pdf->SetTextColor(150,150,150);
				
				$cost_unassigned = str_replace( "&pound;" , "£" , MoneyFormat($cost_unassigned) )  . " [Unassigned]";
				$pdf->SetFont($format_font,'',6);
				$pdf->Cell(0,0.5,'',0,1);
				$pdf->Cell(13,4,'',0,0);		
				SetBarPurple();
				$pdf->Cell($bar_width,4,'',0, 0, R, 1, true);
				$pdf->Cell(0,4,$cost_unassigned,0, 1, L); 
				$pdf->Cell(0,1,'',0,1);
				
					
			
			} else {
			
			$pdf->Cell(0,1,'',0,1);
			
			}
		
					// Now include the project checklist completion
			
			$pdf->Cell(13,2,'Checklist',0,0,R);
			Checklist($proj_id);
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
			// This prints the fee stages
			// First establish the width of the fee stage bar
			$sql_fee_stage = "SELECT ts_fee_value, ts_fee_stage, ts_fee_commence, ts_fee_time_end FROM intranet_timesheet_fees WHERE ts_fee_id = $ts_fee_id AND ts_fee_prospect = 100 ";
			$result_fee_stage = mysql_query($sql_fee_stage, $conn) or die(mysql_error());
			$array_fee_stage = mysql_fetch_array($result_fee_stage);
			$fee_stage = $array_fee_stage['ts_fee_value'];
			$fee_commence = AssessDays ( $array_fee_stage['ts_fee_commence'] );
			$fee_time_end = $array_fee_stage['ts_fee_time_end'] + $fee_commence;
			if ( ($fee_commence < time() ) && ( $fee_time_end > time() ) ) {
				//$percent_complete = (time() - $fee_commence) / ($fee_time_end);
				//$percent_complete = round ($percent_complete,0) . "%";
			} else { 
				//$percent_complete = $fee_time_end;
			}
			
		
			
			// Now establish the width of the timesheet hours to date for this fee stage only
			$sql_cost_total = " SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE `ts_stage_fee` = $ts_fee_id AND `ts_project` = $proj_id = 100";
			$result_cost_total = mysql_query($sql_cost_total, $conn) or die(mysql_error());
			$array_cost_total = mysql_fetch_array($result_cost_total);
			$cost_total = $array_cost_total['SUM(ts_cost_factored)'];
			
			$sql_cost_stage = " SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE `ts_stage_fee` = $ts_fee_id AND `ts_project` = $proj_id  ";
			$result_cost_stage = mysql_query($sql_cost_stage, $conn) or die(mysql_error());
			$array_cost_stage = mysql_fetch_array($result_cost_stage);
			$cost_stage = $array_cost_stage['SUM(ts_cost_factored)'];

			$sql_cost_stage_uf = " SELECT SUM(ts_rate * ts_hours) FROM intranet_timesheet WHERE `ts_stage_fee` = $ts_fee_id AND `ts_project` = $proj_id ";
			$result_cost_stage_uf = mysql_query($sql_cost_stage_uf, $conn) or die(mysql_error());
			$array_cost_stage_uf = mysql_fetch_array($result_cost_stage_uf);
			$cost_stage_uf = $array_cost_stage_uf['SUM(ts_rate * ts_hours)'];
			
			
			$cost_total = $cost_stage;
			
			$bar_units = $bar_width_standard / $bar_scale;
			$this_bar_width = $fee_stage * $bar_units;
			$cost_bar_width = $cost_total * $bar_units;
			
			if ($this_bar_width == 0) { $this_bar_width = $effectively_zero; }
			if ($cost_bar_width == 0) { $cost_bar_width = $effectively_zero; }
			
			if ($this_bar_width > 0 OR $cost_bar_width > 0) {
			
			$fee_stage_print = str_replace( "&pound;" , "£" , MoneyFormat($fee_stage) ); 
			$cost_total = str_replace( "&pound;" , "£" , MoneyFormat($cost_total) )  . " [" . $ts_fee_text . "]" . $percent_complete;
			// $cost_total = $ts_fee_text . "(";
			$cost_total = RemoveShit($cost_total);
			SetBarLBlue();
			
			
			// Now make sure the project header appears if necessary on the next loop
			$current_project = $proj_id;
			
		
			if ($proj_riba == $ts_fee_id) { $pdf->SetTextColor(0,0,0); } else { $pdf->SetTextColor(150,150,150); }
			$pdf->Cell(13,8,$fee_stage_print,0,R,0);
			
			$this_bar_width_1 = $this_bar_width / $ts_fee_target;
			$this_bar_width_2 = $this_bar_width - $this_bar_width_1;
			
			// Bar showing unfactored amount
			$cost_unfactored = $cost_stage_uf * $bar_units;
			if ($cost_unfactored > $this_bar_width) { SetBarRed(); }
			elseif ($cost_unfactored > $this_bar_width_1 ) { SetBarOrange(); }
			else { SetBarDBlue(); }
			if ($cost_unfactored == 0) { $cost_unfactored = 0.1; }
			$pdf->Cell(0,1,'',0,2);
			$pdf->Cell($cost_unfactored,0.5,'',0,2,'',true);
			$pdf->Cell(0,0.5,'',0,2);
			SetBarLBlue();
			
			if ($this_bar_width_1 > 0 AND $this_bar_width_2 > 0) {
			$pdf->Cell($this_bar_width_1,4,'',0, L, 0, true);
			$pdf->SetDrawColor(255,255,255);
			$pdf->Cell($this_bar_width_2,4,'',L, L, 0, true);
			} else {
			$pdf->Cell($this_bar_width,4,'',0, L, 0, true);
			}
			
			
			
			$bar_maximum = $pdf->GetX();
			//$pdf->Cell(0,4,$cost_total,0,L,1);
			// if ($cost_bar_width > 0) {
				$current_y = $pdf->GetY(); // + 2;
				$pdf->SetY($current_y);
				$pdf->SetX(23);
				if ($cost_bar_width > $this_bar_width) { SetBarRed(); }
				elseif ($cost_bar_width > $this_bar_width_1 ) { SetBarOrange(); }
				else { SetBarDBlue(); }
				$pdf->Cell($cost_bar_width,2,'',0, L, 0, true);
				$cost_bar_location = $pdf->GetX();
				$pdf->Cell('',2,'',0, L, 0);
				if ($cost_bar_location > $bar_maximum) { $pdf->SetX($cost_bar_location); } else { $pdf->SetX($bar_maximum); }
				//$new_y = $pdf->GetY() - 2;
				//$pdf->SetY($new_y);
				// Add an extra box at the end if there's a cost overrun
				$pdf->Cell(0,3,$cost_total,0,L,1);
				$current_y = $current_y + 6;
				$pdf->SetY($current_y);
			// }  else {
			//	$pdf->SetX(10);
			//	$current_y = $pdf->GetY() + 6;
			//	$pdf->SetY($current_y);
			//}
			$pdf->SetX($bar_maximum);
			$current_y = $pdf->GetY();
			$current_y = $current_y - 2;
			$pdf->SetY($current_y);
			$pdf->Cell(0,0.5,'',0,2);
			unset($cost_bar_width);
			
					
			
			
					
			
			
			
			}
			
		
			
		}
	
	if ($pdf->GetY() > 280) { $pdf->addPage(); }

	}
	
$pdf->addPage();

// Now create a new page which shows how complete people's timesheets are

$pdf->SetFont('Helvetica','b',24);
$pdf->SetTextColor($format_bg_r, $format_bg_g, $format_bg_b);
$pdf->MultiCell(0,10,"Timesheet Completion",0,1,L);
$pdf->Cell(0,4,'',0,1);

	$sql_userhours = "SELECT *, SUM(ts_hours) FROM intranet_timesheet LEFT JOIN intranet_user_details ON ts_user = user_id WHERE user_active = 1 AND user_active = 1 AND ts_entry > $settings_timesheetstart GROUP BY ts_user ORDER BY user_name_second, user_name_first";
	$result_userhours = mysql_query($sql_userhours, $conn) or die(mysql_error());
	

	
	while ($array_userhours = mysql_fetch_array($result_userhours)) {
		
		if ($pdf->GetY() > 280) { $pdf->addPage(); }
		
		$user_id = $array_userhours['user_id'];
		$user_name_first = $array_userhours['user_name_first'];
		$user_name_second = $array_userhours['user_name_second'];
		$user_user_added = $array_userhours['user_user_added'];
		$user_user_rate = $array_userhours['user_user_rate'];
		$ts_hours = $array_userhours['SUM(ts_hours)'];
		
		if ($user_user_added > $settings_timesheetstart) { $timesheet_datum_user = $user_user_added; } else { $timesheet_datum_user = $settings_timesheetstart; }
		
		$percent_complete = TimeSheetHours($user_id,$display) / 100 ;
	
		
		// Calculate how much time has been spent on non-project hours
		
			$sql_nonproject = "SELECT SUM(ts_hours) FROM intranet_timesheet, intranet_projects WHERE ts_project = proj_id AND ts_user = $user_id AND proj_fee_track != 1 AND ts_entry >= $timesheet_datum_user";
			$result_nonproject = mysql_query($sql_nonproject, $conn) or die(mysql_error());
			$array_nonproject = mysql_fetch_array($result_nonproject);
			$hours_nonproject = $array_nonproject['SUM(ts_hours)'];
			
			$sql_total = "SELECT SUM(ts_hours) FROM intranet_timesheet, intranet_projects WHERE ts_project = proj_id AND ts_user = $user_id AND ts_entry >= $timesheet_datum_user";
			$result_total = mysql_query($sql_total, $conn) or die(mysql_error());
			$array_total = mysql_fetch_array($result_total);
			$hours_total = $array_total['SUM(ts_hours)'];
			
			$percent_nonproject = $hours_nonproject / $hours_total;
			
			if ($percent_nonproject > 1) { $percent_nonproject = 1; }
			
			$sql_nonproj = "UPDATE intranet_user_details SET user_prop = " . round( $percent_nonproject, 2 ) . " WHERE user_id = $user_id LIMIT 1";
			$result_nonproj = mysql_query($sql_nonproj, $conn) or die(mysql_error());
		
		if ($percent_complete > 1) { $percent_complete = 1; }
		if ($percent_complete == 0) { $percent_complete = 0.01; }
		
		$name_width = 35;
		
		$bar_width_available = $bar_width_standard - $name_width;
		$bar_width_user = $bar_width_available * $percent_complete;
		$bar_hours_nonproject = $bar_width_available * $percent_nonproject;
		$percent_nonproject_print = number_format($percent_nonproject * 100) . "%";
		
		SetBarLGray();
		
		$print_username = $user_name_first . " " . $user_name_second;

		$percent_complete_print = number_format (( 100 * $percent_complete ) ) . "% Complete | Hourly rate: £" . number_format($user_user_rate)  ;
		
				$pdf->SetFont($format_font,'',9);
				$pdf->SetTextColor(0, 0, 0);
				$y = $pdf->GetY();
				$pdf->SetFont($format_font,'B',8);
				$pdf->Cell($name_width,5,$print_username,0,0,R);
				
				$bar_width_10 = $bar_width_available / 10;
				$counter = 1;
				while ($counter < 10) {
				$pdf->SetLineWidth(0.5);
				$pdf->Cell($bar_width_10,10,'',R,0,R,1);
				$counter++;
				}
				$pdf->Cell($bar_width_10 + 0.25,10,'',R,0,R,1);				
				$pdf->SetFont($format_font,'',6);
				$pdf->Cell(0,5,$percent_complete_print,0,1,L,0);
				
				
				$pdf->SetY($y + 2.5);
				$x = $pdf->GetX() + $name_width;
				$pdf->SetX($x);
				if ($percent_complete <= 0.5) {
				SetBarRed();
				} elseif ($percent_complete <= 0.75) {
				SetBarOrange();
				} elseif ($percent_complete < 1) {
				SetBarYellow();
				} else {
				SetBarDGreen();
				}
				$pdf->SetLineWidth(0.25);
				$pdf->Cell($bar_width_user,2.5,'',TRB,1,R,1);
				$pdf->Cell(0,0.5,'',0,1,L);
				if ($percent_nonproject > 0) {
					SetBarDBlue();
					$pdf->Cell($name_width,2.5,'Non-Project Hours',0,0,R);
					$pdf->Cell($bar_hours_nonproject,2.5,'',TRB,0,R,1);
					$pdf->Cell(0,2.5,$percent_nonproject_print,0,1,L,0);
					}
				
				// Display projects each person has been working on
				
				$four_weeks_ago = time() - 2419200;
				
				$sql_user_proj = "SELECT SUM(ts_hours), proj_num FROM intranet_timesheet, intranet_projects WHERE ts_project = proj_id AND ts_entry > " .  $four_weeks_ago . " AND ts_user = $user_id GROUP BY ts_project ORDER BY proj_num";
				$result_user_proj = mysql_query($sql_user_proj, $conn) or die(mysql_error());
				
				$sql_user_hours_total = "SELECT SUM(ts_hours) FROM intranet_timesheet WHERE ts_entry > " .  $four_weeks_ago . " AND ts_user = $user_id";
				$result_user_hours_total = mysql_query($sql_user_hours_total, $conn) or die(mysql_error());
				$array_user_hours_total = mysql_fetch_array($result_user_hours_total);
				$cost_user_hours_total = $array_user_hours_total['SUM(ts_hours)'];
				
				$total_hours = $cost_user_hours_total;
				
				$total_hours_print = $total_hours . " hours";
				
					if ($total_hours > 0) {
				
							$pdf->Cell(0,0.5,'',0,1,L);
							
							$pdf->Cell($name_width,4,'Last 28 Days',0,0,R);
							$pdf->SetDrawColor(255,255,255);
							$pdf->SetLineWidth(0.5);
							$pdf->SetFillColor(200,200,200);
							
							$current_y = $pdf->GetY();
							$pdf->Cell($bar_width_available,4,'',0,0,'',1);
							$pdf->Cell(0,4,$total_hours_print,0,1,'',0);
							$pdf->SetY($current_y);
							$pdf->Cell($name_width,4,'',0,0,R);
							$pdf->SetFillColor(150,150,150);
							
							$pdf->SetTextColor(255,255,255);
							
							while ($array_user_proj = mysql_fetch_array($result_user_proj)) {
							$user_hours_proj = $array_user_proj['SUM(ts_hours)'];
							$proj_num = $array_user_proj['proj_num'];
							$percent_proj = $user_hours_proj / $total_hours;
							$bar_width_proj = ($bar_width_available * $percent_proj) - 0.5;
							if ($bar_width_proj < 4) { $pdf->SetFont($format_font,'B',3.5); } elseif ($bar_width_proj < 6) { $pdf->SetFont($format_font,'B',5); } elseif ($bar_width_proj < 15) { $pdf->SetFont($format_font,'B',5); } else { $pdf->SetFont($format_font,'B',6); $proj_num = $proj_num . " (" . $user_hours_proj . "hrs)";  }
							if ($percent_proj > 0.04) {
							$pdf->Cell($bar_width_proj,4,$proj_num,0,0,C,1);
							$pdf->SetFillColor(255,255,255);
							$pdf->Cell(0.5,4,'',0,0,C,0);
							$pdf->SetFillColor(150,150,150);
							}
							
					}
				$pdf->Cell(0,4,'',0,1,R);
			}
			
		// Now examine holidays taken and entitlement
		
			$pdf->SetFont('Helvetica','',6);
			$pdf->SetTextColor(0);
			$pdf->Cell($name_width,4,'Holidays Remaining',0,0,R);
			$pdf->SetDrawColor(255,255,255);
			$pdf->SetLineWidth(0.5);
			$pdf->SetFillColor(200,200,200);
			
			$holidays_allowed = UserHolidays($user_id);
			
			$pdf->Cell(0,4,$holidays_allowed,0);
			
			
		$pdf->Cell(0,6,'',0,1,L);
		
		
				
	}

	
	
	
	
	
	
	
function HolidayCalendar($year) {	
	
						GLOBAL $pdf;
						GLOBAL $conn;
							

						$pdf->addPage();

						// New page with upcoming holidays, etc.
						
						$page_title = "Holidays " . $year;

						$pdf->SetFont('Helvetica','b',24);
						$pdf->SetTextColor($format_bg_r, $format_bg_g, $format_bg_b);
						$pdf->MultiCell(0,8,$page_title,0,1,L);
						$pdf->Cell(0,2,'',0,1);


						$pdf->SetFont('Helvetica','b',18);

						// $beginweek = BeginWeek(time()) + 43200 - 2419200;

						$beginweek = BeginWeek ( mktime( 0, 0, 0, 1, 1, $year ) );

						$daycounter = 0;

						$coord_x = $pdf->GetX();
						$coord_y = $pdf->GetY();

						$days_to_show = 264; // Must be a multiple of 7
						$days_to_show = 406; // Must be a multiple of 7

						while ($daycounter < $days_to_show) {

							

							$today = ( $daycounter * 86400 ) + $beginweek;
							$date = date("j",$today);
							
							$this_day = date("w",$today);
							
							$day = date("j",$today) ;
							
							$month = substr( date("M",$today) , 0, 2 );
							
							$week = date("W",$today);
							
							// Add a square showing the month if this is the first...
							if (date("j",$today) == 1) { $pdf->SetTextColor(255); $pdf->SetFont('Helvetica','b',6); SetBarPurple(); $pdf->Cell(5,4.5,$month,TBL,0,L,1); $month_begin = 1; }
							
							if ( date("n",time()) == date("n",$today) && date("j",time()) == date("j",$today) && date("Y",time()) == date("Y",$today) ) {
							SetBarDBlue();
							} elseif (date("W",time()) == date("W",$today) && date("Y",time()) == date("Y",$today)) {
							SetBarLBlue();
							} elseif ( date("n",time()) == date("n",$today) ) {
							$pdf->SetFillColor(160);
							} elseif (date("n",$today) == 2 OR date("n",$today) == 4 OR date("n",$today) == 6 OR date("n",$today) == 8 OR date("n",$today) == 10 OR date("n",$today) == 12)  {
							$pdf->SetFillColor(190);
							} else {
							$pdf->SetFillColor(220);
							}
							
							unset($holiday_list);
							$sql_holidays = "SELECT user_initials, holiday_length, holiday_paid, holiday_approved FROM intranet_user_holidays, intranet_user_details  WHERE user_id = holiday_user AND holiday_date = " . date("j",$today) . " AND holiday_month = " . date("n",$today) . " AND holiday_year = " . date("Y",$today) . " ORDER BY user_initials DESC";
							$result_holidays = mysql_query($sql_holidays, $conn) or die(mysql_error());
							while ($array_holidays = mysql_fetch_array($result_holidays)) {
							if ($array_holidays['holiday_length'] == 0.5) { $holiday_length = " (Half day)"; } else { unset($holiday_length); }
							if ($array_holidays['holiday_approved'] == NULL) { $holiday_approved = "*"; } else { unset($holiday_approved); }
							if ($array_holidays['holiday_paid'] != 1) { $holiday_paid_1 = "["; $holiday_paid_2 = "]";  } else { unset($holiday_paid_1); unset($holiday_paid_2); }
							$holiday_list = $holiday_paid_1 . $array_holidays['user_initials'] . $holiday_approved . $holiday_paid_2 . $holiday_length . ", " . $holiday_list ;
							}
							
							$holiday_list = rtrim ( $holiday_list , ", " );
							
							$sql_bankholidays = "SELECT bankholiday_timestamp FROM intranet_user_holidays_bank WHERE bankholidays_day = " . date("j",$today) . " AND  bankholidays_month = " . date("n",$today) . " AND bankholidays_year = " . date("Y",$today) . "  LIMIT 1";
							$result_bankholidays = mysql_query($sql_bankholidays, $conn);

							if (mysql_num_rows($result_bankholidays) > 0) {
							SetBarOrange();	
							}
							
							if ($daycounter == 0) { $pdf->SetTextColor(0); $pdf->SetFont('Helvetica','',6); $pdf->Cell(6,4.5,$week - 1,TRB,0,L,0); }
							
							if ( $this_day > 0 AND $this_day <= 5 ) {
							$pdf->SetTextColor(255); $pdf->SetFont('Helvetica','b',9);
							$pdf->Cell(5,4.5,$day,LTB,0,L,1);
							$pdf->SetTextColor(0);
							$pdf->SetFont('Helvetica','',5);
							}

							
							if ( $this_day > 0 AND $this_day < 5 ) {
							if ($month_begin == 1) { $cell_width = 26; unset($month_begin); } else { $cell_width = 31; }
							$pdf->Cell($cell_width,4.5,$holiday_list,TRB,0,L,1);
							} elseif ( $this_day == 5 )  {
							if ($month_begin == 1) { $cell_width = 26; unset($month_begin); } else { $cell_width = 31; }
							$pdf->Cell($cell_width,4.5,$holiday_list,TRB,1,L,1);
							if ($daycounter < ($days_to_show - 6)) { $pdf->Cell(6,4.5,$week,TRB,0,L,0); }
							}
							
							// $pdf->MultiCell(0,5,$sql_holidays);
							
							$daycounter++;

						}
							
							$pdf->SetTextColor(0);
							$pdf->SetFont('Helvetica','',7);
							
							$holiday_notes = "* Pending Approval.\nInitials shown in square brackets indicate non-paid holiday.";
							
							$pdf->MultiCell(0,4,$holiday_notes,0,L);
							
}

$thisyear = date ("Y", time());
$startyear = 2012;

while ($startyear <= $thisyear) {
	HolidayCalendar($startyear);
	$startyear++;
}

// and send to output

$file_name = "timesheet_analysis.pdf";


$pdf->Output($file_name,I);

}
?>
