<?php




include_once "inc_files/inc_checkcookie.php";
include_once "inc_files/inc_actions_functions.php";




if ($user_usertype_current <= 3) { header ("Location: index2.php"); } else {
	
$colwidth = 10;
$rowheight = 4;

if ($_GET[timestart] == NULL) { $timestart = time(); } else { $timestart = $_GET[timestart]; }

$current_time = BeginMonth($timestart,1,2);

$capture_start = $current_time;

$format_bg_r = "0";
$format_bg_g = "0";
$format_bg_b = "0";




//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

//$pagecount = $pdf->setSourceFile("pdf/template.pdf");
//$tplidx = $pdf->ImportPage(1);

$pdf->addPage(L);
//$pdf->useTemplate($tplidx);

// Functions

	function Colors ($ratio,$factor) {
			$output = round (255 * $ratio);
			$diff = 255 - $output;
			$add = $factor * $diff;
			$output = 255 - $add;
			return $output;
		}

	function DrawGrid() {
	
		GLOBAL $current_time;
		GLOBAL $pdf;
		GLOBAL $colwidth;
		
		//$pdf->SetLineWidth(0.35);
		$x = 50;
		$y = 25;
		
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(200,200,200);
		$pdf->SetLineWidth(0.1);
		while ($x <= 280) {
			$pdf->Line($x,$y,$x,200);
			$x = $x + $colwidth;
		}
		
		$pdf->SetDrawColor(255,0,0);
		
		
		$today = BeginWeek(time()) - BeginWeek($current_time);
		$week_begin = date ("N",time());
		if ($week_begin < 6) {
		$week_elapsed = round ((time() - BeginWeek(time())) / 432000 , 2) * $colwidth;
		} else {
		$today = BeginWeek(time()) + 604800;
		}
		$today = $today / 604800;
		$today = round ( $today , 2 );
		$today = ($colwidth * $today) + 50 + $week_elapsed;
		$pdf->Line($today,32.5,$today,200);
		
		$x = 10;
		$y = 25;
		$pdf->SetXY($x,$y);	
		
		$pdf->SetFont('Helvetica','',6);
		
		$x = 50;
		$pdf->SetXY($x,$y);
		$nowtime = BeginWeek($current_time);
		while ($x <= 270) {
			$x = $x + $colwidth;
			$wb = date("j/n/y",$nowtime);
			$pdf->Cell($colwidth,3,$wb,0,0,L);
			$nowtime = $nowtime + 604800;
		}
		
		$x = 10;
		$y = 28;
		$pdf->SetXY($x,$y);
		
		$x = 50;
		$pdf->SetXY($x,$y);
		$nowtime = BeginWeek($current_time);
		while ($x <= 270) {
			$x = $x + $colwidth;
			$wb = date("M",$nowtime);
			$pdf->Cell($colwidth,3,$wb,0,0,L);
			$nowtime = $nowtime + 604800;
		}
		
		$x = 10;
		$y = 32;
		$pdf->SetXY($x,$y);	
	}
	
	function Weeks($input) {
		
		GLOBAL $colwidth;
	
		$output = $input / 604800;
		$output = $output * $colwidth;
		$output = round ($output,0);
		return $output;
	
	}
	

	
		function Datum($start,$duration,$color_array) {
		
			GLOBAL $current_time;
			GLOBAL $pdf;
			GLOBAL $colwidth;
			GLOBAL $rowheight;
			
			$y = $pdf->GetY();
			$y = $y - $rowheight;
			$pdf->SetY($y);
			$pdf->SetX(50);
			
			$pdf->SetLineWidth(0.3);
			
			$datum_start = BeginWeek(AssessDays($start));
			if ($datum_start < $current_time) { $duration = $duration - ($current_time - $datum_start); }
			$duration = round ((($duration) / 604800),0) * $colwidth;
			if ($datum_start < $current_time) { $datum_start = 0; } else { $datum_start = $datum_start - $current_time; }
			$datum_start = round (($datum_start / 604800),0);
			$datum_start = $datum_start * $colwidth;
			
			if (($datum_start + $duration) > 230) { $duration = 230 - $datum_start; }
			
			
			if ($datum_start > 0 && $datum_start < 230) { $pdf->Cell($datum_start,$rowheight,'',0,0,L,FALSE); }
			
			if ($color_array == NULL) { $pdf->SetDrawColor(100); } else { $pdf->SetDrawColor($color_array[0],$color_array[1],$color_array[2]); }
			
			if ($datum_start < 230 && ($datum_start + $duration) > 0) { $pdf->Cell($duration,$rowheight,'',T,1,L,FALSE); } else { $pdf->Cell(0,$rowheight,'',0,1,L,FALSE); }		
			
			$pdf->SetLineWidth(0.2);
			
			$pdf->SetX(10);
		
	}
	
	$array_total = array();

// Header

	$project_counter = 1;
	$page_count = 1;

	$pdf->SetY(10);
	$pdf->SetFont('Helvetica','b',14);
	
	if (date ("n",$timestart) < 4) { $quarter = "Q1"; }
	elseif (date ("n",$timestart) < 7) { $quarter = "Q2"; }
	elseif (date ("n",$timestart) < 10) { $quarter = "Q3"; }
	else { $quarter = "Q4"; }
	$quarter = $quarter . " " . date ("Y",$timestart);

	$sheet_title = "Project Resourcing, " . $quarter;
	$pdf->SetTextColor($format_bg_r, $format_bg_g, $format_bg_b);
	$pdf->Cell(169.5,10,$sheet_title,0,0);
	$pdf->SetFont('Helvetica','',8);
	$prev_month = $timestart - 7889184;
	$next_month = $timestart + 7889184;
	$prev_month = $_SERVER['HTTP_HOST'] . "/pdf_resourcing.php?timestart=$prev_month";
	$this_month = $_SERVER['HTTP_HOST'] . "/pdf_resourcing.php";
	$next_month = $_SERVER['HTTP_HOST'] . "/pdf_resourcing.php?timestart=$next_month";

	$print_date = "Date: " . date ("r",time());
	$pdf->Cell(25,4,$print_date,0,0,R,0);
	$pdf->SetTextColor(200, 200, 200);
	$pdf->SetDrawColor(200, 200, 200);
	$pdf->Cell(2.5,4,'',0,0,C,0);
	$pdf->Cell(25,4,'Previous quarter',1,0,C,0,$prev_month);
	$pdf->Cell(2.5,4,'',0,0,C,0);
	$pdf->Cell(25,4,'This quarter',1,0,C,0,$this_month);
	$pdf->Cell(2.5,4,'',0,0,C,0);
	$pdf->Cell(25,4,'Next quarter',1,1,C,0,$next_month);
	

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetY(50);
	$pdf->SetFont('Helvetica','b',18);
		
DrawGrid();

// Begin listing the projects

	//$sql_proj = "SELECT * FROM intranet_projects, intranet_timesheet_fees WHERE ts_fee_project = proj_id AND proj_active = 1 AND proj_fee_track = 1 AND ts_fee_value > 0 ORDER BY proj_num, ts_fee_commence";
	$sql_proj = "SELECT * FROM intranet_projects, intranet_timesheet_fees WHERE ts_fee_project = proj_id AND proj_fee_track = 1 AND ts_fee_prospect > 0 AND ts_fee_value > 0 AND (((UNIX_TIMESTAMP(ts_fee_commence) + ts_fee_time_end) > $capture_start) OR ((UNIX_TIMESTAMP(ts_datum_commence) + ts_datum_length) > $capture_start)) ORDER BY proj_num, ts_fee_commence";
	$result_proj = mysql_query($sql_proj, $conn) or die(mysql_error());
	
	$pdf->SetFont('Helvetica','',7);
	
	$current_proj = 0;
	$count = 0;
	$arrayname = 0;

	while ($array_proj = mysql_fetch_array($result_proj)) {
	
		unset($stage_start);
		unset($stage_width);
		unset($ts_fee_conclude);
		$x = 0;
	
		$proj_id = $array_proj['proj_id'];
		$proj_num = $array_proj['proj_num'] . " " . $array_proj['proj_name'];
		$proj_riba = $array_proj['proj_riba'];
		$ts_fee_id = $array_proj['ts_fee_id'];
		$ts_fee_commence = BeginWeek( AssessDays ( $array_proj['ts_fee_commence'] ) );
		$ts_fee_time_end = $array_proj['ts_fee_time_end'];
		$ts_fee_conclude = BeginWeek( $ts_fee_commence + $ts_fee_time_end );
		$ts_fee_text = $array_proj['ts_fee_text'];
		$ts_fee_pre = $array_proj['ts_fee_pre'];
		$ts_fee_prospect = $array_proj['ts_fee_prospect'];
		if ($ts_fee_prospect == 0) { $ts_fee_prospect = 1; } else { $ts_fee_prospect = $ts_fee_prospect / 100; }
		$ts_fee_value = ( $array_proj['ts_fee_value'] / $array_proj['ts_fee_target'] ) * $ts_fee_prospect;
		$ts_fee_profit = $array_proj['ts_fee_value'] * $ts_fee_prospect;
		
		$ts_datum_commence = $array_proj['ts_datum_commence'];
		$ts_datum_length = $array_proj['ts_datum_length'];
		
		
		$fee_weekly = round ( $ts_fee_value /( round (($ts_fee_time_end / 604800),0)) ,2 );
		$profit_weekly = round ( $ts_fee_profit /( round (($ts_fee_time_end / 604800),0)) ,2 );
		$fee_weekly_print = "£" . number_format ($fee_weekly);
		
		// Need to make sure the array continues from the very beginning of the line to count the number of columns in the right place
	
		if ($current_proj != $proj_id) { $pdf->SetFont('Helvetica','B',7); $pdf->SetTextColor(0,0,0); $pdf->Cell(50,6,$proj_num,0,1,L); }
		
		if ($ts_fee_conclude >= BeginWeek($current_time)) {
		
			$pdf->SetFont('Helvetica','',7);
			$pdf->Cell(40,$rowheight,$ts_fee_text,0,0,L);
			if ($proj_riba == $ts_fee_id) { $color = array(0.07,0.82,0.72); } else { $color = array(0.47,0.75,0.94); }
			//$color = array(0.47,0.75,0.94);
			$color1 = Colors($color[0],$ts_fee_prospect);
			$color2 = Colors($color[1],$ts_fee_prospect);
			$color3 = Colors($color[2],$ts_fee_prospect); 
			$pdf->SetFillColor($color1, $color2, $color3);
			$stage_start = $ts_fee_commence - BeginWeek($current_time);
			if ($stage_start < 0) { $stage_start = 0; $ts_fee_time_end = $ts_fee_time_end - ($current_time - $ts_fee_commence); $noborder = 1;  } else { $noborder = 0; }
			$stage_start = Weeks($stage_start);
			if ($stage_start > 0 & $stage_start < 230) {	$pdf->Cell($stage_start,$rowheight,'',0,0,L); }
			$stage_width = Weeks ($ts_fee_time_end);
			$pdf->SetFont('Helvetica','',5);
			$count = 0;
			$arraycount = ($stage_start / $colwidth);
			if ($pdf->GetX() < 280 & $stage_start < 230) {
				while ($count < $stage_width && $x < 280) {
					//if ($ts_fee_pre > 0 && $count == 0 && $noborder == 0) { $pdf->SetDrawColor(150); $pdf->SetLineWidth(1); $border = ""; } else { $pdf->SetLineWidth(0.2); $border = 0; }
					$pdf->Cell($colwidth,$rowheight,$fee_weekly_print,$border,0,R,true);
					$count = $count + $colwidth;
					$x = $pdf->GetX();
					$array_total[$arraycount] = $array_total[$arraycount] + $fee_weekly;
					$array_profit[$arraycount] = $array_profit[$arraycount] + $profit_weekly;
					$arraycount++;
				}
				
				
				$pdf->Cell(0,0,'',0,1,L);
			}
			$pdf->Cell(0,$rowheight,'',0,1,L);
			//if ((BeginWeek(AssessDays($ts_datum_commence)) + $ts_datum_length) < $ts_fee_conclude) { $color_array = array(255,0,0); } else { unset($color_array); }
			if (($array_proj['ts_fee_commence'] !=  $array_proj['ts_datum_commence']) OR ($array_proj['ts_fee_time_end'] > $array_proj['ts_datum_length'])) { $color_array = array(225,0,0); } else { unset($color_array); }
			Datum($ts_datum_commence,$ts_datum_length,$color_array);
			$pdf->Cell(0,1,'',0,1,L);
		}
		
		
		$current_proj = $proj_id;
		
		if ($pdf->GetY() > 170) { $pdf->addPage(L); DrawGrid(); }
		
	}
	
	$pdf->addPage(L);
	DrawGrid();
	
	// Now add the totals at the end
	
		$x = 0;
	
		$pdf->SetFont('Helvetica','B',8);
		$pdf->Cell(0,5,'',0,1,L);
		$pdf->Cell(40,5,"TOTAL",0,0,L);
		$pdf->SetFont('Helvetica','',6);
		$arrayname = 0;
		while ($x <= 220) {
			$x = $x + $colwidth;
			$total = "£" . number_format ( $array_total[$arrayname] );
			$pdf->Cell($colwidth,3,$total,0,0,R);
			$arrayname++;
		}
		
		$x = 0;
		$bg = 220;
		$beginweek = BeginWeek($current_time);
		$month = date ("n" , $beginweek);
		$currentmonth = date ("n" , $beginweek);
		$pdf->SetFont('Helvetica','B',8);
		$pdf->Cell(0,5,'',0,1,L);
		$pdf->Cell(40,5,"MONTH",0,0,L);
		$pdf->SetFont('Helvetica','',7);
		$pdf->SetDrawColor(100,100,100);
		$arrayname = 0;
		$monthtotal = 0;
		while ($x <= 230) {
			$pdf->SetFillColor($bg); 
			$monthtotalprint = "£" . number_format ( $monthtotal , 0);
			if ($month != $currentmonth ) {
				$pdf->Cell($colwidth,5,$monthtotalprint,0,0,R,true);
				$month = $currentmonth;
				$monthtotal = 0;
				if ($bg == 220) { $bg = 240; } else { $bg = 220; }
			} else {
				if ($x > 0) { $pdf->Cell($colwidth,5,'',0,0,R, true); } //else {  $x = $x - 10; }
			}
			$monthtotal = $monthtotal + $array_total[$arrayname];
			$arrayname++;
			$beginweek = $beginweek + 604800;
			$currentmonth = date ("n" , $beginweek);
			$x = $x + $colwidth;
		}
		
		
		if ($pdf->GetY() > 170) { $pdf->addPage(L); DrawGrid(); }
		
		
		// Add cost of staff
		
		function StaffCost($time,$prop) {
			
			GLOBAL $conn;
			$start = $time;
			$end = $time + 604800;
			$sql_staff = "SELECT user_timesheet_hours, user_user_rate, user_prop, user_prop_target FROM intranet_user_details WHERE user_user_added < $start AND ( user_user_ended > $start OR user_user_ended IS NULL OR user_user_ended = 0 ) OR (user_user_added < $start AND user_user_ended > $start)";
			$result_staff = mysql_query($sql_staff, $conn) or die(mysql_error());
			$weekly_cost = 0;
			while ($array_staff = mysql_fetch_array($result_staff)) {
				$user_timesheet_hours = $array_staff['user_timesheet_hours'];
				$user_user_rate = $array_staff['user_user_rate'];
				$user_prop = $array_staff['user_prop'];
				$user_prop_target = $array_staff['user_prop_target'];
				if ($prop != "target")	{ $user_prop_multiplier = $user_prop / $user_prop_target; } else { $user_prop_multiplier = 1; }
				if ($user_prop == 0 OR $user_prop == NULL) { $user_prop = 1; }
				$weekly_cost = $weekly_cost + (($user_timesheet_hours * $user_prop_multiplier * $user_user_rate) * ( 1 - $user_prop));
			}
			
			return($weekly_cost);
			
		}
		
	$test = StaffCost($current_time,"");
		
	$x = 0;
	$y = $pdf->GetY() + 5;
	$pdf->SetXY($x,$y);
	
	
	//$pdf->MultiCell(0,5,$test);
	
		$pdf->SetFont('Helvetica','B',6);
		$pdf->Cell(0,5,'',0,1,L);
		$pdf->Cell(40,5,"Target Staff Costs",0,0,L);
		$pdf->SetFont('Helvetica','',6);
		$beginweek = BeginWeek($current_time);
		while ($x <= 220) {
			$x = $x + $colwidth;
			$total = "£" . number_format ( StaffCost($beginweek,"target") );
			$pdf->Cell($colwidth,3,$total,0,0,R);
			$beginweek = $beginweek + 604800;
		}
		
	if ($pdf->GetY() > 170) { $pdf->addPage(L); DrawGrid(); }

		
	$x = 0;

	$pdf->SetFont('Helvetica','B',6);
		$pdf->Cell(0,5,'',0,1,L);
		$pdf->Cell(40,5,"Actual Staff Costs",0,0,L);
		$pdf->SetFont('Helvetica','',6);
		$beginweek = BeginWeek($current_time);
		$staffcost_1 = array();
		while ($x <= 220) {
			$x = $x + $colwidth;
			$staffcost = StaffCost($beginweek);
			$total = "£" . number_format ( $staffcost );
			$staffcost_1[] = $staffcost;
			$pdf->Cell($colwidth,3,$total,0,0,R);
			$beginweek = $beginweek + 604800;
		}	

	if ($pdf->GetY() > 170) { $pdf->addPage(L); DrawGrid(); }
		
	$x = 0;
	$y = $pdf->GetY() + 5;
	$pdf->SetXY($x,$y);
		
	// Fees minus costs
	
		$pdf->SetFont('Helvetica','B',6);
		$pdf->Cell(0,5,'',0,1,L);
		$pdf->Cell(40,5,"Actual surplus",0,0,L);
		$pdf->SetFont('Helvetica','',6);
		$arrayname = 0;
		$weekdiff_array = array();
		while ($x <= 220) {
			$x = $x + $colwidth;
			$weekdiff = $array_total[$arrayname] -  $staffcost_1[$arrayname];
			$weekdiff_array[] = $weekdiff;
			if ($weekdiff < 0) { $pdf->SetTextColor(255,0,0); } else { $pdf->SetTextColor(0,0,0); }
			$total = "£" . number_format ( $weekdiff );
			$pdf->Cell($colwidth,5,$total,0,0,R);
			$arrayname++;
		}
		
	if ($pdf->GetY() > 170) { $pdf->addPage(L); DrawGrid(); }
		
		$x = 0;
	$y = $pdf->GetY();
	$pdf->SetXY($x,$y);
		
	// Profits
		$pdf->SetTextColor(0);
		$pdf->SetFont('Helvetica','B',6);
		$pdf->Cell(0,5,'',0,1,L);
		$pdf->Cell(40,5,"Target Profit",0,0,L);
		$pdf->SetFont('Helvetica','',6);
		$counter = 0;
		while ($x <= 220) {
			$x = $x + $colwidth;
			$total = "£" . number_format ( $array_profit[$counter] - $array_total[$counter] );
			$pdf->Cell($colwidth,5,$total,0,0,R);
			$counter++;
		}

		if ($pdf->GetY() > 170) { $pdf->addPage(L); DrawGrid(); }
		
		$x = 0;
	$y = $pdf->GetY();
	$pdf->SetXY($x,$y);
		
		// Net Profit
		$pdf->SetTextColor(0);
		$pdf->SetFont('Helvetica','B',8);
		$pdf->Cell(0,5,'',0,1,L);
		$pdf->Cell(40,5,"Actual Profit",0,0,L);
		$pdf->SetFont('Helvetica','B',6);
		$counter = 0;
		$array_netprofit = array();
		while ($x <= 220) {
			$x = $x + $colwidth;
			$total = $weekdiff_array[$counter] + ($array_profit[$counter] - $array_total[$counter]);
			$array_netprofit[] = $total;
			if ($total < 0) { $pdf->SetTextColor(255,0,0); } else { $pdf->SetTextColor(0,0,0); }
			$total = "£" . number_format ( $total ) ;
			$pdf->Cell($colwidth,5,$total,0,0,R);
			$counter++;
		}
		
		
		// List all of the upcoming holidays for each person
		
		$start = $time;
		
		$x = 10;
		$y = $pdf->GetY() + 15;
		$pdf->SetXY($x,$y);
		
function CheckHols($date, $user_id, $start) {
	
	GLOBAL $conn;

				$sql_days = "SELECT holiday_datestamp FROM intranet_user_holidays WHERE holiday_user = $user_id AND holiday_timestamp > $start ORDER BY holiday_timestamp";
				$result_days = mysql_query($sql_days, $conn) or die(mysql_error());				
				$array_print = array();
				
				while ($array_days = mysql_fetch_array($result_days)) {
					
					$array_print[] = $array_days['holiday_datestamp'];					
					
				}
				
		if (in_array($date,$array_print)) { return "yes"; }

}
		// First create an array which extracts all bank holidays from the database

		$sql_bankholidays = "SELECT bankholidays_datestamp FROM intranet_user_holidays_bank WHERE bankholiday_timestamp > $current_time";
		$result_bankholidays = mysql_query($sql_bankholidays, $conn) or die(mysql_error());
		$array_bankholidays = mysql_fetch_array($result_bankholidays);
		
		
		$sql_holidays = "SELECT user_name_first, user_name_second, user_id, user_user_added, user_user_ended FROM intranet_user_details WHERE ( user_user_ended > $current_time OR user_user_ended IS NULL OR user_user_ended = 0 ) OR (user_user_added < $current_time AND user_user_ended > $start) ORDER BY user_name_second, user_name_first";
			$result_holidays = mysql_query($sql_holidays, $conn) or die(mysql_error());
			
			$day_width = $colwidth / 5;
			
			$pdf->SetFont('Helvetica','B',8);
			$pdf->SetTextColor(0);
			$pdf->Cell(0,5,"Holidays",0,1,L);
				
			while ($array_holidays = mysql_fetch_array($result_holidays)) {
				$user_name_first = $array_holidays['user_name_first'];
				$user_name_second = $array_holidays['user_name_second'];
				$user_id = $array_holidays['user_id'];
				$user_user_added = $array_holidays['user_user_added'];
				$user_user_ended = $array_holidays['user_user_ended'];
				$user_name = $user_name_first . " " . $user_name_second;
				
				$user_start = $current_time + 43200;
				
				$pdf->SetFont('Helvetica','',3);
				
				$pdf->SetTextColor(0);
				$pdf->SetFont('Helvetica','B',6);
				$pdf->Cell(40,5,$user_name,0,0,L);
				
				$sql_days = "SELECT holiday_datestamp FROM intranet_user_holidays WHERE holiday_user = $user_id ORDER BY holiday_timestamp";
				$result_days = mysql_query($sql_days, $conn) or die(mysql_error());
				$array_days = mysql_fetch_array($result_days);
				$print_array = print_r($array_days, true);		
				while ($pdf->GetX() < 280) {
					
					
					
					$date = date ("Y-m-d",$user_start);
					if (CheckHols($date,$user_id,$current_time) == "yes" && date("z",$time) == date("z",$user_start) ) { $pdf->SetFillColor(255,150,150);
					} elseif (CheckHols($date,$user_id,$current_time) == "yes" && date("z",$time) != date("z",$user_start) ) { $pdf->SetFillColor(220);
					} elseif (CheckHols($date,$user_id,$current_time) != "yes" && date("z",$time) == date("z",$user_start) ) { $pdf->SetFillColor(255,0,0);
					} else { $pdf->SetFillColor(175);
					}
					if ($user_user_added >= $user_start) { $pdf->SetFillColor(255); } elseif ($user_user_ended <= $user_start && $user_user_ended > 0) { $pdf->SetFillColor(255); }
					
					if ( array_search ( $date , $array_bankholidays ) > 0 ) { $pdf->SetFillColor(150); } // don't understand why this search does not find the bank holidays?
					
					if (date("N",$user_start) < 6) {
						$print_cell = date("j",$user_start);
						$pdf->Cell($day_width,5,'',L,0,L,1);
					}
					$user_start = $user_start + 86400;
					
				}
				
				
				
				$pdf->Cell(0,5,'',0,1);
				
				$pdf->SetX(10);
				
				
		
		
		
		
		
			}
		
		
		
	// Now let's  attempt a graph
	
	if ($pdf->GetY() > 150) { $pdf->addPage(L); DrawGrid(); }
	
	$axis_y_max_1 = max ($weekdiff_array);
	$axis_y_min_1 = min ($weekdiff_array);
	
	$axis_y_max_2 = max ($array_netprofit);
	$axis_y_min_2 = min ($array_netprofit);
	
	if ($axis_y_max_1 > $axis_y_max_2) { $axis_y_max = $axis_y_max_1; } else { $axis_y_max = $axis_y_max_2; }
	if ($axis_y_min_1 < $axis_y_min_2) { $axis_y_min = $axis_y_min_1; } else { $axis_y_min = $axis_y_min_2; }
	
	$pdf->SetDrawColor(0);
	
	
	$x = 50;
	$y = $pdf->GetY() + 50;
	$zero = $y;
	$pdf->SetXY($x,$y);
	$height = 40;
	$range = $axis_y_max - $axis_y_min;
	$ratio = $height / $range;
	// Datum line
	$pdf->Cell(0,5,"£0",0,0);
	$pdf->Line($x,$y,280,$y);
	
	// Maximum line
	$pdf->SetDrawColor(200);
	$start_y = $y - ($ratio * $axis_y_max);
	$pdf->Line($x,$start_y,280,$start_y);
	
	// Minimum line
	$start_y = $y - ($ratio * $axis_y_min);
	$pdf->Line($x,$start_y,280,$start_y);
	
	$color = array(0.07,0.82,0.72);
	$pdf->SetDrawColor($color[0] * 255, $color[1] * 255, $color[2] * 255);
	$pdf->SetLineWidth(0.5);
	
	$x = $x + 5;
	
		$counter = 0;
		while ($x <= 270) {
			$y_fee_start = $y - ($ratio * $weekdiff_array[$counter]);
			$y_fee_end = $y - ($ratio * $weekdiff_array[$counter + 1]);		
			$pdf->Line($x,$y_fee_start,$x + $colwidth,$y_fee_end);
			$x = $x + $colwidth;
			$counter++;
		}
	
	$x = 55;
	
	$color = array(0.47,0.75,0.94);
	$pdf->SetDrawColor($color[0] * 255, $color[1] * 255, $color[2] * 255);
	$pdf->SetLineWidth(0.1);
	
	$y = $zero;
	$pdf->SetY($zero);
	
	$counter = 0;
		while ($x <= 270) {
			$y_fee_start = $y - ($ratio * $array_netprofit[$counter]);
			$y_fee_end = $y - ($ratio * $array_netprofit[$counter + 1]);
			$pdf->Line($x,$y_fee_start,$x + $colwidth,$y_fee_end);
			$x = $x + $colwidth;
			$counter++;
		}


// and send to output

// and send to output

$file_name = date ("Y-m-d",$current_time) . "_resourcing_analysis.pdf";


$pdf->Output($file_name,I);


}
?>
