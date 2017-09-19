<?php


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


function PDFHeader ($proj_id) {

		$current_date = TimeFormat(time());

		GLOBAL $pdf;
		GLOBAL $conn;
		GLOBAL $format_font;
		GLOBAL $format_font_2;

						$sql_proj = "SELECT * FROM intranet_projects WHERE proj_id = $proj_id LIMIT 1";
						$result_proj = mysql_query($sql_proj, $conn) or die(mysql_error());
						$array_proj = mysql_fetch_array($result_proj);
						$proj_num = $array_proj['proj_num'];
						$proj_name = $array_proj['proj_name'];
							
							$sheet_title = "Project Checklist";
							$pdf->SetXY(10,45);
							$pdf->SetFont($format_font,'',24);
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetDrawColor(0, 0, 0);
							$pdf->Cell(0,10,$sheet_title);
							$pdf->SetXY(10,55);
							$pdf->SetFont($format_font,'',14);
							
							$sheet_subtitle = $proj_num." ".$proj_name;
							$sheet_date = "Current at ". $current_date;
							$pdf->Cell(0,7.5,$sheet_subtitle,0,1,L,0);
							$pdf->Cell(0,7.5,$sheet_date,0,1,L,0);
							$pdf->SetXY(10,70);
							
							$pdf->SetLineWidth(0.5);

}


function TableHeader() {		

		GLOBAL $pdf;
		GLOBAL $format_font;
		GLOBAL $format_font_2;

						$y = $pdf->GetY() + 0;
						$pdf->SetY($y);

						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetLineWidth(0.3);
						$pdf->SetFont("Helvetica",'',7);
						$pdf->Cell(75,3,"Item",'',0,L,0);
						$pdf->Cell(15,3,"Required",'',0,L,0);
						$pdf->Cell(30,3,"Date Completed",'',0,L,0);
						$pdf->Cell(70,3,"Comment",'',1,L,0);

						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont($format_font,'',7);
						$pdf->SetFillColor(240,240,240);

						$pdf->SetLineWidth(0.1);
						
}


function TaskArrays($proj_id, $group_id) {

		GLOBAL $conn;
		GLOBAL $pdf;
		GLOBAL $format_font;
		GLOBAL $format_font_2;

						$sql_checklist = "SELECT * FROM intranet_timesheet_group, intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id AND checklist_project = $proj_id WHERE  item_stage = group_id AND group_id = $group_id ORDER BY item_group, item_order, checklist_date DESC, item_name";
						$result_checklist = mysql_query($sql_checklist, $conn) or die(mysql_error());

						$fill = 0;

						$group = NULL;
						
						if (mysql_num_rows($result_checklist) > 0) {
						
						TableHeader();

										while ($array_checklist = mysql_fetch_array($result_checklist)) {


											$item_id = $array_checklist['item_id'];
											$item_name = $array_checklist['item_name'];
											$item_date = $array_checklist['item_date'];
											$item_group = $array_checklist['item_group'];
											$item_required = $array_checklist['item_required'];
											$item_notes = $array_checklist['item_notes'];
											
											$checklist_id = $array_checklist['checklist_id'];
											$checklist_required = $array_checklist['checklist_required'];
											$checklist_date	= $array_checklist['checklist_date'];
											$checklist_comment = htmlentities ( $array_checklist['checklist_comment']);
											$checklist_user = $array_checklist['checklist_user'];
											$checklist_link = $array_checklist['checklist_link'];
											$checklist_timestamp = time();
											$checklist_project = $_GET[proj_id];
											
											$pdf->SetDrawColor(0,0,0);
											$pdf->SetLineWidth(0.05);
											
												if ($checklist_required == 2) { $checklist_required_print = "Yes"; $pdf->SetFont($format_font,'',8); }
												elseif ($checklist_required == 1) { $checklist_required_print = "No"; $pdf->SetFont($format_font,'',8); }
												else { $checklist_required_print = "?"; }
												
												if ($checklist_date == "0000-00-00" OR $checklist_date == NULL) { $checklist_date_print = "-"; } else {
													$date_array = explode("-",$checklist_date);
													$checklist_day = $date_array[2];
													$checklist_month = $date_array[1];
													$checklist_year = $date_array[0];
													$checklist_date_print = $checklist_day . " / " . $checklist_month . " / " . $checklist_year;
												}
												
												if ($item_group != $group) {
														$pdf->SetFont($format_font,'',8);
														$current_y = $pdf->GetY(); if ($current_y > 250) { $pdf->addPage(); }
														$pdf->Cell(0,3,'',0,1,L,0); $pdf->Cell(0,5,$item_group,B,1,L,0);
														$pdf->SetFont($format_font,'',8);
												} else {
													
														if ($current_y > 250) { $pdf->addPage(); }
														
												}
												
												$border = 0;
												
												$current_x = $pdf->GetX() + 1;
												$current_y = $pdf->GetY() + 1;
												
												if ($checklist_required == "2" AND $checklist_date == "0000-00-00" ) { $pdf->SetFillColor(245,72,72); } // Red
												elseif ($checklist_required == "2" AND $checklist_date == NULL) { $pdf->SetFillColor(245,72,72); } // Red
												elseif ($checklist_required == "0") { $pdf->SetFillColor(245,190,72); } // Red
												elseif ($checklist_required == NULL) { $pdf->SetFillColor(245,190,72); } // Orange
												elseif ($checklist_required == "1") { $pdf->SetFillColor(220,220,220); } // Grey
												else { $pdf->SetFillColor(173,233,28); }  // Green
												
												$pdf->Rect($current_x, $current_y, 3, 3 , F);
												
												$pdf->Cell(5,5,"",$border,0,L,$fill);
												$pdf->Cell(70,5,$item_name,$border,0,L,$fill,$checklist_link);
												$pdf->Cell(15,5,$checklist_required_print,$border,0,L,$fill);
												$pdf->Cell(30,5,$checklist_date_print,$border,0,L,$fill);
												$pdf->Cell(0,1,'',$border,2);
												$pdf->MultiCell(70,3,$checklist_comment,0,L,$fill);
												$pdf->Cell(0,1,'','B',1);
												
												$y = $pdf->GetY();
												
											$group = $item_group;
											
										//if ($pdf->GetY() > 250) { $pdf->AddPage(); }
												
										}
					} else { $pdf->SetFont($format_font,'',9); $pdf->Cell(0,1,'No checklist items for this stage',$border,2);  }
}


function Footer() {

		GLOBAL $pdf;

						$pdf->SetLineWidth(0.3);
						$pdf->Cell(0,5,'',T,1,L,0);

						$pdf->SetFont($format_font,'',8);
						$pdf->Cell(0,5,'Key:',0,1);

						$pdf->SetLineWidth(1.5);
						$pdf->SetDrawColor(255,255,255);

						$pdf->SetFont($format_font,'',7);

						$pdf->SetFillColor(245,190,72);
						$pdf->Cell(5,5,'',1,0,L,true);
						$pdf->Cell(25,5,'To be confirmed',0,0);

						$pdf->SetFillColor(245,72,72);
						$pdf->Cell(5,5,'',1,0,L,true);
						$pdf->Cell(25,5,'Not yet completed',0,0);

						$pdf->SetFillColor(173,233,28);
						$pdf->Cell(5,5,'',1,0,L,true);
						$pdf->Cell(25,5,'Complete',0,0);

						$pdf->SetFillColor(220,220,220);
						$pdf->Cell(5,5,'',1,0,L,true);
						$pdf->Cell(25,5,'Not required',0,1);
						
						
}


function StageArrays($proj_id) {

	GLOBAL $conn;
	GLOBAL $pdf;
	
	
	
	$sql_group = "SELECT group_id, group_code, group_description FROM intranet_timesheet_group WHERE group_project = 1 AND group_active = 1 ORDER BY group_order";
	$result_group = mysql_query($sql_group, $conn) or die(mysql_error());
	if (mysql_num_rows($result_group) > 0) {
			while ($array_group = mysql_fetch_array($result_group)) {
			
				if ($pdf->GetY() > 200) { $pdf->AddPage(); }
				
				$group_id = $array_group['group_id'];
				$group_code = $array_group['group_code'];
				$group_description = $array_group['group_description'];
				$group_title = $group_code . ": " . $group_description;
				$pdf->SetFont("Helvetica",'B',12);
				$pdf->Cell(0,15,'','',1,L,0);
				$pdf->Cell(0,10,$group_title,'',1,L,0);
				$pdf->Cell(0,2,'','',1,L,0);
				
				TaskArrays($proj_id, $group_id);
				
			}
			
			
		
	}

}

function ProjectHeading($proj_id,$heading) {
	
	GLOBAL $conn;
	GLOBAL $pdf;
	
	$current_y = $pdf->GetY() + 35;
	
	$pdf->SetY($current_y);
	$pdf->SetFont('Helvetica','',18);

	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(0,10,$heading);

	$pdf->Ln(10);
	$pdf->SetFont('Helvetica','b',18);
	

	
// Determine name of project

	$sql = "SELECT * FROM intranet_projects WHERE proj_id = '$proj_id'";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);

	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$proj_desc = $array['proj_desc'];
	$proj_address_1 = $array['proj_address_1'];
	$proj_address_2 = $array['proj_address_2'];
	$proj_address_3 = $array['proj_address_3'];
	$proj_address_town = $array['proj_address_town'];
	$proj_address_county = $array['proj_address_county'];
	$proj_address_postcode = $array['proj_address_postcode'];
	$proj_client_contact_id = $array['proj_client_contact_id'];

	$proj_planning_ref = $array['proj_planning_ref'];
	$proj_buildingcontrol_ref = $array['proj_buildingcontrol_ref'];

	$print_title = $proj_num." ".$proj_name;

	$pdf->MultiCell(0,8,$print_title,0, L, 0);

// Printed by, and on...

	$pdf->SetFont($format_font,'',10);

	$sql = "SELECT user_name_first, user_name_second FROM intranet_user_details WHERE user_id = $_COOKIE[user]";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);

	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];

	$printed_on = "Current at ". date("g:ia, j F Y");

	$pdf->Cell(0,6,$printed_on,0, 1, L, 0);

	$pdf->SetFillColor(255, 255, 255);
	
	$y_current = 60;
	$x_current = 10;
	$pdf->SetXY($x_current,$y_current);
	
	$pdf->Ln(15);





}

		
function Notes($input) {
			Global $pdf;
			Global $x_current;
			Global $y_current;
			$pdf->SetX($x_current);
			$print_string = $input;
			if ($input != NULL) {
			StyleBody(9);
			$pdf->SetTextColor(150, 150, 150);
			$pdf->MultiCell(90,3,$print_string,0, L, false);
			$pdf->MultiCell(90,1,'',0, L, false);
			}
		}
	
	
function AddLine($input) {
			if ($input != NULL AND $input != '0' AND strlen($input) > 3) { $input = $input."\n"; return $input; }
		}
	
	
function SplitBag($input) {
			Global $pdf;
			Global $x_current;
			Global $y_current;
			Global $y_left;
			Global $y_right;
			if ($y_left > $y_right) { $y_current = $y_left + 5; } else { $y_current = $y_right + 5; }
			$x_current = 10;
			$pdf->SetXY($x_current,$y_current);
			StyleBody(10);
			$pdf->SetFillColor(220, 220, 220);
			$pdf->Cell(0,5,$input,0, 2,L, true);
			$pdf->Cell(0,5,'',0, 2,L, false);
			$x_current = 10;
			$y_current = $pdf->GetY();
		}
	
	
function ChecklistSummary($proj_id) {
	
 	GLOBAL $conn;
	GLOBAL $pdf;
	
	$pdf->SetX(10);
	
	$sql_group = "SELECT group_id, group_code, group_description, checklist_required, checklist_date FROM intranet_timesheet_group, intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id WHERE group_project = 1 AND group_active = 1 AND item_stage = group_id AND checklist_project = $proj_id ORDER BY group_order, item_order";
	$result_group = mysql_query($sql_group, $conn) or die(mysql_error());
	if (mysql_num_rows($result_group) > 0) {
		
		$pdf->Ln(15);
		$pdf->SetFont("Helvetica",'B',12);
		$pdf->Cell(0,5,"Summary",0,1);
		$pdf->SetFont("Helvetica",'',9); 
		
		while ($array_group = mysql_fetch_array($result_group)) {
			$group_code = $array_group['group_code'];
			$checklist_required = $array_group['checklist_required'];
			$checklist_date = $array_group['checklist_date'];
			$pdf->SetDrawColor(255,255,255);
			
			if ($checklist_required == "2" AND $checklist_date == "0000-00-00" ) { $pdf->SetFillColor(245,72,72); } // Red
			elseif ($checklist_required == "2" AND $checklist_date == NULL) { $pdf->SetFillColor(245,72,72); } // Red
			elseif ($checklist_required == "0") { $pdf->SetFillColor(245,190,72); } // Red
			elseif ($checklist_required == NULL) { $pdf->SetFillColor(245,190,72); } // Orange
			elseif ($checklist_required == "1") { $pdf->SetFillColor(220,220,220); } // Grey
			else { $pdf->SetFillColor(173,233,28); }  // Green
			
			if ($current_code != $array_group['group_id']) { $pdf->Ln(5); $pdf->Cell(8,3,$group_code,0,0); $current_code = $array_group['group_id']; }
			$pdf->Cell(3,3,'',1,0,'',true);
			
		}
		
		
	}
	
}

		function PDF_Notes($input) {
			Global $pdf;
			Global $x_current;
			Global $y_current;
			$pdf->SetX($x_current);
			//$print_string = DeCode($input);
			$print_string = $input;
			if ($input != NULL) {
			StyleBody(9);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->MultiCell(90,3,$print_string,0, L, false);
			$pdf->MultiCell(90,1,'',0, L, false);

			}
		}
		
		function StyleHeading1($title,$text,$notes){
			Global $pdf;
			Global $x_current;
			Global $y_current;
			Global $y_left;
			Global $y_right;
			if ($y_current > 230 AND $x_current > 75) { $pdf->addPage(); $x_current = 10; $y_current = 15; }
			$pdf->SetXY($x_current,$y_current);
			$pdf->SetFont('Helvetica','b',10);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetX($x_current);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetLineWidth(0.3);
			$pdf->MultiCell(90,5,$title,B, L, false);
			$pdf->MultiCell(90,1,'',0, L, false);
			PDF_Notes($notes);
			$pdf->Ln(1);
			StyleBody(10);
			$pdf->SetX($x_current);
			$pdf->MultiCell(90,4,$text,0, L, false);
			
			if ($x_current < 75) {
				$x_current = 105;
				$y_left = $pdf->GetY();
			} else {
				$x_current = 10;
				$y_right = $pdf->GetY();
				if ($y_left > $y_right) { $y_current = $y_left + 5; } else { $y_current = $y_right + 5; }
				if ($y_current > 220) { $pdf->addPage(); $y_current = 20; }
			}
			
			
		}
		
		function AddLine1($input) {
			if ($input != NULL AND $input != '0' AND strlen($input) > 3) { $input = $input."\n"; return $input; }
		}
		
		function SplitBag1($input) {
			Global $pdf;
			Global $x_current;
			Global $y_current;
			Global $y_left;
			Global $y_right;
			if ($y_left > $y_right) { $y_current = $y_left + 5; } else { $y_current = $y_right + 5; }
			$x_current = 10;
			$pdf->SetXY($x_current,$y_current);
			StyleBody(10);
			$pdf->SetFillColor(220, 220, 220);
			$pdf->Cell(0,5,$input,0, 2,L, true);
			$pdf->Cell(0,5,'',0, 2,L, false);
			$x_current = 10;
			$y_current = $pdf->GetY();
		}
		
		
function PDF_Fee_Drawdown ($proj_id) {
	
		global $conn;
		global $pdf;
		global $format_font;
		
		$proj_id = intval($proj_id);
		
		$sql = "SELECT ts_fee_commence FROM intranet_timesheet_fees WHERE ts_fee_project = $proj_id AND ts_fee_value > 5 ORDER BY ts_fee_commence";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		$ts_fee_commence = $array['ts_fee_commence'];
		
		if ($ts_fee_commence > date("Y-m-d",time())) { $title = "Proposed Fee Drawdown";	} else { $title = "Current Fee Status"; }
		
		
		ProjectHeading($proj_id,$title);
	
		$sql = "SELECT * FROM intranet_timesheet_fees WHERE ts_fee_project = $proj_id AND ts_fee_value > 5 ORDER BY ts_fee_commence";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
			if (mysql_num_rows($result) > 0) {
			
				$total_fee = 0;
			
				while ($array = mysql_fetch_array($result)) {
					
				
					$pdf->SetLineWidth(0.5);
					$pdf->SetFont('Helvetica','',10);
					
					$ts_fee_id = $array['ts_fee_id'];
					$ts_fee_text = $array['ts_fee_text'];
					$ts_fee_commence = TimeFormat ( BeginWeek ( AssessDays($array['ts_fee_commence'],12) ) );
					$ts_fee_time_end = TimeFormat ( BeginWeek ( $array['ts_fee_time_end'] + AssessDays($array['ts_fee_commence'], 12) ) );
					
					$ts_fee_value = utf8_decode( "£" . number_format ( $array['ts_fee_value'] , 2 ) );
					
					$from_to = "From " . $ts_fee_commence . " to " . $ts_fee_time_end ;
					
					$pdf->Cell(0,7,$ts_fee_text,0,1);
					$pdf->SetFont($format_font,'',8);
					$pdf->Cell(0,4,$from_to,'B',1);
					
					$total_fee = $total_fee + $array['ts_fee_value'];
					
						PDF_Fee_Split ( BeginWeek (  AssessDays($array['ts_fee_commence'] ) ),$array['ts_fee_time_end'],$array['ts_fee_value'], $ts_fee_id);
					
				}
				
			$total_fee = utf8_decode( "£" . number_format ( $total_fee , 2 ) );
				
			$pdf->SetLineWidth(0.5);
			$pdf->SetFont('Helvetica','B',10);
				
			$pdf->Cell(75,7,"Total Fee",'T',0);
			$pdf->Cell(0,7,$total_fee,'T',1,'R');
			
			
		}
	
	
}

function PDF_Fee_Split ($stage_commence, $stage_length, $stage_fee, $ts_fee_id) {

		global $pdf;
		global $format_font;
		global $format_font_2;
		$pdf->SetLineWidth(0.1);
		
		$pdf->SetFont($format_font,'',8);
		
		$stage_time_begin = $stage_commence ;
		
		$stage_time_end = $stage_commence + $stage_length ;
		
		$start_this_month_number = date ( "n" , $stage_time_begin);
		$start_day_of_month = date ( "j", $stage_time_begin);
		$start_days_in_month = date ( "t", $stage_time_begin);
		$start_percent_through_month = number_format ($start_day_of_month / $start_days_in_month, 2);
		
		$end_this_month_number = date ( "n" , $stage_time_end);
		$end_day_of_month = date ( "j", $stage_time_end);
		$end_days_in_month = date ( "t", $stage_time_end);
		$end_percent_through_month = number_format ($end_day_of_month / $end_days_in_month, 2);
		
	
		$working_days_in_period = WorkingDaysInPeriod ($stage_time_begin, $stage_time_end);
		
		$daily_fee = round ( $stage_fee / $working_days_in_period, 2);
		
		$stage_counter = $stage_time_begin;
		$stage_fee_added = 0;
		$stage_fee_total = 0;
		$stage_percent_total = 0;
		$stage_fee_percent_total = 0;
		
	
		$pdf->Cell(100,5,"Invoice Date","B",0);
		$pdf->Cell(50,5,"%","B",0,"R");
		$pdf->Cell(0,5,"Invoice Value (net)","B",1,"R");
		
		// How much to round to?
		
		if ($stage_fee > 100000) { $rounder = 500; }
		elseif ($stage_fee > 20000) { $rounder = 250; }
		elseif ($stage_fee > 10000) { $rounder = 100; }
		else { $rounder = 10; }
		
		while ($stage_counter <= $stage_time_end) {
		
			$pdf->SetFont($format_font,'',9);
		
			if ( date ("w", $stage_counter) > 0 && date ("w", $stage_counter) < 6) { $stage_fee_added = $stage_fee_added + $daily_fee; }
			
			
			
			if ( date("j",$stage_counter) == date("t",$stage_counter)) {
			
				$stage_fee_added = ConsolidateFee($stage_fee_added,$rounder);
				
				$fee_percent = 100 * ($stage_fee_added / $stage_fee);
				$fee_present_total = $fee_present_total + $fee_percent;
				$fee_percent_present = number_format ( $fee_present_total , 2 ) . "%";
				
				$pdf->Cell(125,5,TimeFormat(LastDayofMonth($stage_counter)),0,0);
				$pdf->Cell(25,5,$fee_percent_present,0,0,"R");
				$pdf->Cell(0,5,CashPresent($stage_fee_added,2),0,1,"R");
				
				$stage_fee_total = $stage_fee_total + $stage_fee_added;
				
				$stage_fee_added = 0;
			}
			
			$stage_counter = $stage_counter + 86400;
		
		}
		
				// Remaining figures
				
				$stage_fee_remain = $stage_fee - $stage_fee_total;

				$pdf->Cell(125,5,TimeFormat(LastDayofMonth($stage_counter)),0,0);
				$pdf->Cell(25,5,"100.00%",0,0,"R");
				$pdf->Cell(0,5,CashPresent($stage_fee_remain,2),0,1,"R");
		
				$pdf->SetLineWidth(0.5);
				$pdf->Cell(125,5,"Stage Total","T",0);
				$pdf->Cell(0,5,CashPresent($stage_fee,2),"T",1,"R");
				
				if ($_GET[showinvoices]) {
				
					PDF_Fee_Invoice_Reduce($stage_fee, $ts_fee_id);
				
				}
				
				
				$pdf->Ln(7);
		

}

function PDF_Fee_Invoice_Reduce($stage_fee, $fee_id) {
		
		global $conn;
		global $pdf;
		global $format_font;
		global $format_font_2;
		$pdf->SetLineWidth(0.1);

		$fee_id	= intval($fee_id);	
		
		$sql = "SELECT SUM(invoice_item_novat), invoice_ref FROM intranet_timesheet_invoice, intranet_timesheet_invoice_item WHERE invoice_item_invoice = invoice_id AND invoice_item_stage = $fee_id AND invoice_paid > 0 GROUP BY invoice_id";
		
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		$fee_remaining = $stage_fee;
		
		while ($array = mysql_fetch_array($result)) {
			
		$invoice_ref = $array['invoice_ref'];
		$fee_remaining = $fee_remaining - $array['SUM(invoice_item_novat)'];
		$fee_remaining_print = utf8_decode( "£" . number_format ( $fee_remaining , 2 ) );
		$pdf->SetFont($format_font,'',8);
		
		$invoice_ref_print = "Less invoice ref. " . $invoice_ref . " (" . utf8_decode( "£" . number_format ( $array['SUM(invoice_item_novat)'] , 2 ) ) . ")";
		
		
		$pdf->Cell(125,4,$invoice_ref_print,0,0);
		$pdf->Cell(0,4,$fee_remaining_print,0,1,'R',1);

			
		}
		
		if ($fee_remaining > 0 && $fee_remaining != $stage_fee) {
		
		$pdf->SetTextColor(255,0,0);
		$fee_remaining_print = utf8_decode( "£" . number_format ( $fee_remaining , 2 ) );
		$pdf->Cell(125,4,"Outstanding to be paid",'TB',0);
		$pdf->Cell(0,4,$fee_remaining_print,'TB',1,'R',1);	
		$pdf->SetTextColor(0,0,0);	
		}
		
}


function WorkingDaysInPeriod ($period_start, $period_end, $daily_fee) {

		$start_period_count = $period_start;
		$workdays_count = 0;
		
		while ($start_period_count <= $period_end) {
		
			$start_period_count = $start_period_count + 86400;
			
		
			if ( date ("w", $start_period_count) > 0 && date ("w", $start_period_count) < 6) { $workdays_count++; }
				
		}
		
		return $workdays_count;

}

function CashPresent ($value,$decimal_places) {

		$output = "£" . number_format ($value,$decimal_places) ;
		$output = utf8_decode( $output ) ;
		return $output;

}

function LastDayofMonth($time) {

		$month = date ( "n" , $time);
		$lastday = date ("t", $time);
		$year = date("Y",$time);
		
		$timecheck = mktime (12,0,0,$month,$lastday,$year);
		
		if ( date ( "w", $timecheck ) == 0) { $timecheck = $timecheck - 172808; }
		elseif ( date ( "w", $timecheck ) == 6) { $timecheck = $timecheck - 86400; }
		
		return $timecheck;

}

function ConsolidateFee ($value,$factor) {

		$output = $value / $factor;
		$output = round ($output);
		$output = $output * $factor;
		return $output;

}