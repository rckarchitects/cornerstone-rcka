<?php

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

function PDFCurrencyFormat($input) {
	
	$output = utf8_decode( "£". number_format (($input),2) );
	
	return $output;
	
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

function PDFHeader ($proj_id,$sheet_title) {

		$current_date = TimeFormat(time());
		
		$proj_id = intval($proj_id);

		GLOBAL $pdf;
		GLOBAL $conn;
		GLOBAL $format_font;
		GLOBAL $format_font_2;
		
		if ($proj_id > 0) {

						$sql_proj = "SELECT * FROM intranet_projects WHERE proj_id = $proj_id LIMIT 1";
						$result_proj = mysql_query($sql_proj, $conn) or die(mysql_error());
						$array_proj = mysql_fetch_array($result_proj);
						$proj_num = $array_proj['proj_num'];
						$proj_name = $array_proj['proj_name'];
		}
							
							if (!$sheet_title) { $sheet_title = "Project Checklist"; }
							$pdf->SetXY(10,45);
							$pdf->SetFont('Helvetica','B',24);
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetDrawColor(0, 0, 0);
							$pdf->Cell(0,10,$sheet_title);
							$pdf->SetXY(10,55);
							
							
							$sheet_date = "Current at ". $current_date;
							if ($proj_id > 0) { $pdf->SetFont('Helvetica','',14); $sheet_subtitle = $proj_num." ".$proj_name; $pdf->Cell(0,7.5,$sheet_subtitle,0,1,L,0); }
							$pdf->SetFont('Helvetica','',12);
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
		
		$sql = "SELECT ts_fee_commence FROM intranet_timesheet_fees WHERE ts_fee_project = $proj_id AND ts_fee_value > 5 AND ts_fee_prospect > 0 ORDER BY ts_fee_commence";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		$ts_fee_commence = $array['ts_fee_commence'];
		
		if ($ts_fee_commence > date("Y-m-d",time())) { $title = "Proposed Fee Drawdown";	} else { $title = "Current Fee Status"; }
		
		
		ProjectHeading($proj_id,$title);
	
		$sql = "SELECT * FROM intranet_timesheet_fees WHERE ts_fee_project = $proj_id AND ts_fee_value > 5 AND ts_fee_prospect = 100 ORDER BY ts_fee_commence";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
			if (mysql_num_rows($result) > 0) {
			
				$total_fee = 0;
			
				while ($array = mysql_fetch_array($result)) {
					
					if ($pdf->GetY() > 250) { $pdf->addPage();  }
				
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
		else { $rounder = 50; }
		
		while ($stage_counter < $stage_time_end) {
		
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
				
				
				$pdf->Ln(3);
		

}

function PDF_Fee_Invoice_Reduce($stage_fee, $fee_id) {
		
		global $conn;
		global $pdf;
		global $format_font;
		global $format_font_2;
		$pdf->SetLineWidth(0.1);

		$fee_id	= intval($fee_id);	
		
		$sql = "SELECT SUM(invoice_item_novat), invoice_ref, invoice_paid FROM intranet_timesheet_invoice, intranet_timesheet_invoice_item WHERE invoice_item_invoice = invoice_id AND invoice_item_stage = $fee_id GROUP BY invoice_id";
		
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		$fee_remaining = $stage_fee;
		
		while ($array = mysql_fetch_array($result)) {
			
		$invoice_ref = $array['invoice_ref'];
		
		if ($array['invoice_paid']) { $fee_remaining = $fee_remaining - $array['SUM(invoice_item_novat)']; }
		
		
		$fee_remaining_print = utf8_decode( "£" . number_format ( $fee_remaining , 2 ) );
		$invoice_paid = TimeFormat ( $array['invoice_paid'] );
		$pdf->SetFont($format_font,'',8);
		
		

		if ($array['invoice_paid']) {
				$invoice_ref_print = "Less invoice ref. " . $invoice_ref . " (" . utf8_decode( "£" . number_format ( $array['SUM(invoice_item_novat)'] , 2 ) );
				$invoice_ref_print = $invoice_ref_print . ", paid " . $invoice_paid . ")";
		}  else {
				$invoice_ref_print = "Invoice ref. " . $invoice_ref . " (" . utf8_decode( "£" . number_format ( $array['SUM(invoice_item_novat)'] , 2 ) );
				$invoice_ref_print = $invoice_ref_print . ")";
		}
		
		
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

function PDF_HolidayPanel($begin_week) {

		GLOBAL $pdf;

		StyleBody(16,'Helvetica','B');
		$pdf->Cell(0,10,'Holidays',0,1);

		$day_begin = $begin_week;
		$counter = 0;
		$color_switch = 1;
		while ($counter < 5) {

			ListHoliday($day_begin, $color_switch);
			
			$day_begin = $day_begin + 86400;

			$counter++;
			
			if ($color_switch == 1) { $color_switch = 2; } else { $color_switch = 1; }

		}

		$pdf->Ln(5);

}

function PDF_TextShrinker($font_size,$textstring,$width_available,$face,$style) {
	
	global $pdf;
	
	StyleBody($font_size,$face,$style);
	
	$current_text_width = $pdf->GetStringWidth($textstring);
	
	if ($current_text_width < $width_available) { StyleBody($font_size,$face,$style);  }
	
	else {
		
		$output_font_size = $font_size;
		
		while ($current_text_width >= $width_available) {
			
			$output_font_size = $output_font_size * 0.95;
			
			$current_text_width = $pdf->GetStringWidth($textstring);
			StyleBody($output_font_size,$face,$style);
		}
		
	}
	
	
}

function PDF_ListTenders($day_begin) {

		GLOBAL $conn;
		GLOBAL $pdf;
		
		StyleBody(14,'Helvetica','B');
		
			$time_end = BeginWeek ( $day_begin + 1296000 );
		
			$sql = "SELECT * FROM `intranet_tender` WHERE tender_date BETWEEN $day_begin AND ($day_begin + 1209600) AND tender_result != 3 ORDER BY tender_date";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			
			if (mysql_num_rows($result) > 0) {
			
			StyleBody(16,'Helvetica','B');
			$pdf->Cell(0,10,'Tenders Due (Next Fortnight)',0,1);
			$color_swich = 1;
			while ($array = mysql_fetch_array($result)) {
			$tender_name = date("G:i",$array['tender_date']) . ": " . $array['tender_name'] . " (" . $array['tender_client'] . ")";
				if ($color_switch == 1) { SetColor2(); $color_switch = 2; } else { SetColor3(); $color_switch = 1; }
				StyleBody(10,'Helvetica','');
				$day = date("D j",$array['tender_date']);
				if ($current_day != $day) { $pdf->Cell(15,10,$day);  } else { $pdf->Cell(15,10,''); }
				
				
				PDF_TextShrinker(13,$tender_name,260,'Helvetica','B');
				
				$width = $pdf->GetStringWidth($tender_name) + 5;
				$pdf->Cell(2,12,'',0,0,'L',1);
				$pdf->Cell($width,12,$tender_name,0,1,'L',1);
				$pdf->Ln(2);
				$current_day = $day;
				
			}
		
		$pdf->Ln(5);
		
		}


}

function PDF_TaskDeadlines($day_begin) {
	
	GLOBAL $conn;
	GLOBAL $pdf;
	
		$sql = "SELECT * FROM  `intranet_projects`, `intranet_tasklist` LEFT JOIN intranet_user_details ON user_id = tasklist_person WHERE tasklist_project = proj_id AND tasklist_percentage < 100 AND tasklist_due > 0 AND tasklist_due < ($day_begin + 604800) AND (tasklist_access <= 3 OR tasklist_access IS NULL) ORDER BY DATE(from_unixtime(tasklist_due)), proj_num";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result) > 0) {

			StyleBody(16,'Helvetica','B');
			$pdf->Cell(0,10,'Deadlines',0,1);
			SetColor2();
			$color_switch = 2;
			
			while ($array = mysql_fetch_array($result)) {
				
				$day = date("d.n.y",$array['tasklist_due']);
				if ($current_day != $day) {
					if ($array['tasklist_due'] >= $day_begin) { StyleBody(11,'Helvetica','B'); $day_print = date("D",$array['tasklist_due']); } else { StyleBody(8,'Helvetica',''); $day_print = $day; }
					$pdf->Cell(15,10,$day_print); if ($color_switch == 1) { SetColor1(); $color_switch = 2; } else { SetColor2(); $color_switch = 1; } } else { $pdf->Cell(15,10,'');
				}
				
				
				
				StyleBody(13,'Helvetica','B');
				$proj_name = $array['proj_num'] . " " . $array['proj_name'];
				$width = $pdf->GetStringWidth($proj_name) + 5;
				$pdf->Cell(2,10,'',0,0,'L',1);
				$pdf->Cell($width,10,$proj_name,0,0,'L',1);
				StyleBody(13,'Helvetica','');
				$pdf->Cell(1,10,'',0,0,'L',0);
				if (strlen($array['tasklist_notes']) > 70) { $tasklist_notes = substr($array['tasklist_notes'],0,65) . "..."; } else { $tasklist_notes = $array['tasklist_notes']; }
				$width = $pdf->GetStringWidth($tasklist_notes) + 5;
				$pdf->Cell(2,10,'',0,0,'L',1);
				if ($array['tasklist_due'] < $day_begin ) { $pdf->SetTextColor(255,0,0); } else { $pdf->SetTextColor(0,0,0); }
				$pdf->Cell($width,10,$tasklist_notes,0,0,'L',1);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(1,$row_height,'');
				StyleBody(10,'Helvetica','B');
				$user_initial_project = $array['user_initials'];
				$pdf->Cell(10,10,$user_initial_project,0,1,'C',1);
				$pdf->Ln(2);
				$current_day = $day;				
			}
			
			
		}
				
}

function PDF_ImportantDates($week_begin) {
	
	GLOBAL $conn;
	GLOBAL $pdf;
	
	$period_start = DisplayDay(intval($week_begin));
	$period_end = DisplayDay(intval($week_begin) + 1209600);
	
		$sql = "SELECT * FROM `intranet_datebook` WHERE date_day >= '" . $period_start . "' AND date_day <= '" . $period_end . "' ORDER BY date_day ASC";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result) > 0) {

			StyleBody(16,'Helvetica','B');
			$pdf->Cell(0,10,'Important Dates',0,1);
			SetColor2();
			$color_switch = 2;
			
			while ($array = mysql_fetch_array($result)) {
				
				$date_time = DisplayDate($array['date_day']);
				$day = date("d.n.y",$date_time);
				StyleBody(8,'Helvetica','');
				$pdf->Cell(15,10,$day); if ($color_switch == 1) { SetColor1(); $color_switch = 2; } else { SetColor2(); $color_switch = 1; }
				StyleBody(13,'Helvetica','B');
				$proj_name = $array['date_category'];
				$width = $pdf->GetStringWidth($proj_name) + 5;
				$pdf->Cell(2,10,'',0,0,'L',1);
				$pdf->Cell($width,10,$proj_name,0,0,'L',1);
				StyleBody(13,'Helvetica','');
				$pdf->Cell(1,10,'',0,0,'L',0);
				if (strlen($array['date_description']) > 70) { $tasklist_notes = substr($array['date_description'],0,65) . "..."; } else { $tasklist_notes = $array['date_description']; }
				$width = $pdf->GetStringWidth($tasklist_notes) + 5;
				$pdf->Cell(2,10,'',0,0,'L',1);
				$pdf->Cell($width,10,$tasklist_notes,0,1,'L',1);
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell(1,$row_height,'');
				$pdf->Ln(2);
				$current_day = $day;				
			}
			
			
		}
				
}

function PDF_ListStages($day_begin) {


		GLOBAL $conn;
		GLOBAL $pdf;
		
		StyleBody(14,'Helvetica','B');
		
			$date = date("Y-m-d",$day_begin);
			$weekend = date("Y-m-d",($day_begin + 518400));
			
			$sql = "SELECT DATE_ADD(ts_fee_commence, INTERVAL ts_fee_time_end SECOND), group_id, group_code, group_description, proj_num, proj_name, proj_rep_black, ts_fee_text, user_initials, ts_fee_commence, ts_fee_time_end FROM `intranet_projects`, `intranet_timesheet_fees` LEFT JOIN intranet_timesheet_group ON ts_fee_group = group_id LEFT JOIN intranet_user_details ON user_id = group_leader WHERE ts_fee_project = proj_id AND ts_fee_commence <= '$weekend' AND (DATE_ADD(ts_fee_commence, INTERVAL ts_fee_time_end SECOND) >= '$date') AND ts_fee_prospect = 100 AND proj_active = 1 ORDER BY group_order, proj_num";
			$result = mysql_query($sql, $conn) or die(mysql_error());

			
			$sql2 = "SELECT COUNT(group_id) FROM `intranet_projects`, `intranet_timesheet_fees` LEFT JOIN intranet_timesheet_group ON ts_fee_group = group_id WHERE ts_fee_project = proj_id AND ts_fee_commence <= '$date' AND (DATE_ADD(ts_fee_commence, INTERVAL ts_fee_time_end SECOND) >= '$date') AND ts_fee_prospect = 100 AND proj_active = 1 ORDER BY group_order, proj_num";
			$result2 = mysql_query($sql2, $conn) or die(mysql_error());
			$array2 = mysql_fetch_array($result2);
			$groups = $array2['COUNT(group_id)'];
			$space_needed_for_headings = $groups * 12;
			
			
			$totalrows = mysql_num_rows($result);
			$current_y = $pdf->GetY();
			$remaining = 420 - $current_y;
			$space_needed_for_rows = $remaining - $space_needed_for_headings;
			$row_height = $space_needed_for_rows / $totalrows * 1.4;
			if ($row_height > 10) { $row_height = 10; }
			$row_height = 10;
			

			if ($totalrows > 0) {
			
			StyleBody(16,'Helvetica','B');
			
			$pdf->Cell(0,10,'Project Stages',0,1);
			
			SetColor1(); $color_switch = 1;
		
			while ($array = mysql_fetch_array($result)) {
				
				$group_name = $array['group_description'];
				
				$sql3 = "SELECT user_initials FROM intranet_user_details WHERE user_id = '" . $array['proj_rep_black'] . "' LIMIT 1";
				$result3 = mysql_query($sql3, $conn) or die(mysql_error());
				$array3 = mysql_fetch_array($result3);
				$user_initial_project = $array3['user_initials'];
				
				
				if ($array['user_initials']) { $group_name = $group_name . " (Stage Leader: " . $array['user_initials'] . ")"; }
				$proj_name = $array['proj_num'] . " " . $array['proj_name'];
				$stage_name = $array['ts_fee_text'];
				if ($array['group_id'] != $current_group_code) {
					if ($pdf->GetY() > 360) { $pdf->AddPage(); }
					if ($current_group_code != NULL) { $pdf->Ln(2); }
					StyleBody(16,'Helvetica','B'); $pdf->Cell(15,7,$array['group_code']); StyleBody(14,'Helvetica',''); $pdf->Cell(0,7,$group_name); $current_group_code = $array['group_id']; $pdf->Ln(9);
					if ($color_switch == 1) { SetColor4(); $color_switch = 2; } else { SetColor1(); $color_switch = 1; }
				}
				StyleBody(9,'Helvetica','B');
				$pdf->Cell(15,$row_height,'');
				$font_height = floor($row_height * 1.6);
				StyleBody($font_height,'Helvetica','B');
				$width = $pdf->GetStringWidth($proj_name) + 2;
				$pdf->Cell(2,$row_height,'',0,0,'L',1);
				$pdf->Cell($width,$row_height,$proj_name,0,0,'L',1);
				StyleBody($font_height,'Helvetica','');
				$width = $pdf->GetStringWidth($stage_name) + 5;
				$pdf->Cell(2,$row_height,'',0,0,'L',1);
				$pdf->Cell($width,$row_height,$stage_name,0,0,'L',1);
				$pdf->Cell(1,$row_height,'');
				
				//Dates
				StyleBody(7,'Helvetica','');
				$pdf->SetDrawColor(255,255,255);
				$pdf->SetLineWidth(0.5);
				$date_present_start = TimeFormatBrief(AssessDays($array['ts_fee_commence']));
				$date_present_end = TimeFormatBrief(AssessDays($array['ts_fee_commence']) + $array['ts_fee_time_end']);
				if ($pdf->GetStringWidth($date_present_start) > $pdf->GetStringWidth($date_present_end)) {
				$date_width = $pdf->GetStringWidth($date_present_start) + 2;
				} else {
				$date_width = $pdf->GetStringWidth($date_present_end) + 2;	
				} 
				$current_y = $pdf->GetY();
				$pdf->Cell($date_width,($row_height/2),$date_present_start,'B',2,'C',1);
				if ((AssessDays($array['ts_fee_commence']) + $array['ts_fee_time_end']) < (time() + 604800)) { SetColor3();}
				$pdf->Cell($date_width,($row_height/2),$date_present_end,'T',0,'C',1);
				$current_x = $pdf->GetX();
				$pdf->SetXY($current_x,$current_y);
				
				if ($color_switch == 1) { SetColor1(); } else { SetColor4();  }
				
				//Project Leader
				$pdf->Cell(1,$row_height,'');
				StyleBody(12,'Helvetica','B');
				if (($row_height - 2) < $pdf->GetStringWidth($user_initial_project)) { $square_width = $pdf->GetStringWidth($user_initial_project) + 2; } else { $square_width = $row_height; }
				$pdf->Cell($square_width,$row_height,$user_initial_project,0,0,'C',1);
				$line_height = $row_height + 2;
				$pdf->Ln($line_height);
			}
			

		
		$pdf->Ln(14);
		
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

function PDF_FileName ($proj_id, $file_name) {
	
	$$proj_id = intval($proj_id);
	
	global $conn;
	
	$sql = "SELECT proj_num FROM intranet_projects WHERE proj_id = $proj_id";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$proj_num = $array['proj_num'];
	
	$file_name_output = $proj_num."_".Date("Y",time())."-".Date("m",time())."-".Date("d",time()). "_" . $file_name . ".pdf";
	
	return $file_name_output;
	
}

function StageTotal($stage_total, $hours_total, $running_cost_nf, $ts_fee_target ) {
	
		GLOBAL $pdf;
		
		$invoice_cost = $stage_total * $ts_fee_target;
		$invoice_cost_print = PDFCurrencyFormat($invoice_cost);
		$running_cost_nf_print =  "(" . PDFCurrencyFormat($running_cost_nf) . ")";
		
				$stage_total_print = PDFCurrencyFormat($stage_total);
				$pdf->Cell(0,1,'',T,1);
				$pdf->Cell(20,4,'Stage Total', 0, 0, L);
				$pdf->Cell(8,4,$hours_total, 0, 0, R);
				$pdf->Cell(5,4,'hrs', 0, 0);
				$pdf->Cell(38,4,'', 0, 0);
				$pdf->Cell(20,4,$stage_total_print, 0, 0, R);
				$pdf->Cell(20,4,$running_cost_nf_print, 0, 1, R);
				$pdf->Ln();
				$stage_total = 0;
				$hours_total= 0;
				$running_cost_nf = 0;
				
				$ts_fee_target_print = ($ts_fee_target - 1) * 100;
				$ts_fee_target_print = "Actual Fee (including profit at " . number_format($ts_fee_target_print,2) . "%)";
				
				$pdf->Cell(0,1,'',T,1);
				$pdf->Cell(71,4,$ts_fee_target_print, 0, 0, L);
				$pdf->Cell(20,4,$invoice_cost_print, 0, 1, R);
				$pdf->Ln();
	
		}
		
function StageFee($ts_fee_id) {
			
			if ($ts_fee_id > 0) {
				GLOBAL $conn;
				GLOBAL $pdf;
				$sql = "SELECT ts_fee_value FROM intranet_timesheet_fees WHERE ts_fee_id = $ts_fee_id";
				$result = mysql_query($sql, $conn) or die(mysql_error());
				$array = mysql_fetch_array($result);
				$ts_fee_value_print = "Stage Fee (including profit) " . number_format($array['ts_fee_value'],2);
				$pdf->Cell(0,8,$ts_fee_target_print, 'T', 0, L);
				
			}
		}

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
	
	$sql = "SELECT * FROM intranet_timesheet_fees LEFT JOIN intranet_timesheet_group ON ts_fee_group = group_id WHERE ts_fee_project = $proj_id AND ts_fee_prospect = 100 ORDER BY ts_fee_commence";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	$fee_total = 0;
	$fee_target_total = 0;
	$cost_actual = 0;
	$target_fee_percentage_actual_total = 0;
	$start_date = 0;
	
	while ($array = mysql_fetch_array($result)) {
			
			$ts_fee_text = $array['ts_fee_text'];			
			if ($array['group_code']) { $ts_fee_text = $array['group_code'] . " " . ltrim( trim( $ts_fee_text ),$array['group_code']); }
		
			
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
	
	if ($fee_total > 0) { $fee_total_profit = number_format( (1 - ($fee_target_total / $fee_total)) * 100) . "%"; }
	if ($cost_actual > 0) { $cost_total_profit = number_format( (1 - ($cost_actual / $fee_total)) * 100) . "%"; }
	
	$pdf->Ln(1);
	$pdf->SetLineWidth(0.5);
	$pdf->SetFont('Helvetica','B',10);
	$pdf->Cell(100,10,"Total Fee",'B',0);
	$pdf->Cell(25,10,$fee_total_print,'B',0,'R');
	$pdf->SetFont('Helvetica','',9);
	$pdf->Cell(25,5,"Target Cost",0,0,'R');
	$pdf->Cell(35,5,$fee_total_profit,0,0,'R');
	$pdf->SetFont('Helvetica','B',10);
	if ($fee_target_total > $fee_total) { $pdf->SetTextColor(255,0,0); } else { $pdf->SetTextColor(0,0,0); }
	$pdf->Cell(40,5,$fee_target_total_print,0,0,'R');
	$pdf->Cell(0,5,$fee_target_percent_total_print,0,1,'R');
	$pdf->SetX(135);
	$pdf->SetFont('Helvetica','',9);
	$pdf->Cell(25,5,"Actual Cost",'B',0,'R');
	$pdf->Cell(35,5,$cost_total_profit,'B',0,'R');
	$pdf->SetFont('Helvetica','B',10);
	if ($cost_actual > $fee_target_total) { $pdf->SetTextColor(255,0,0); } else { $pdf->SetTextColor(0,0,0); }
	$pdf->Cell(40,5,$cost_actual_print,'B',0,'R');
	$pdf->Cell(0,5,$cost_actual_percent_print,'B',1,'R');
	
	$pdf->SetTextColor(0,0,0);
	
}

function PDFProjectArray($proj_id) {

	global $conn;
	global $pdf;
	
	$pdf->SetTextColor(0,0,0);
	$pdf->SetLineWidth(0.25);
	
	if (intval($proj_id) > 0) { $proj_id_filter = "AND proj_id = " . intval($proj_id); } else { unset($proj_id_filter); } 
	
	//global $format_font;
		$sql = "SELECT proj_id, proj_num, proj_name, proj_type, proj_value, proj_procure FROM intranet_projects WHERE proj_active = 1 AND proj_fee_track = 1 $proj_id_filter ORDER BY proj_num";
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
				$pdf->SetLineWidth(0.5);
				$pdf->SetFont($format_font,'B',10);
				$pdf->Cell(75,5,$array['proj_type'],'B',0);
				$proj_value = "£" . number_format ($array['proj_value'], 2);
				$proj_value = utf8_decode($proj_value);
				$pdf->Cell(50,5,$proj_value,'B',0,'R');
				
				//THIS NEEDS UPDATING TO OUTPUT PROJECT PROCUREMENT TYPE
				//$proj_procure = html_entity_decode(ProjectProcurement($array['proj_procure'],$proj_id));
				
				$pdf->Cell(75,5,$proj_procure,'B',0,'R');
				$pdf->Cell(0,5,"",'B',1);
			}
			
			
			PDFStageAnalysis($array['proj_id'],$array['proj_value']);

			
		}
}

function PDFTimeSheetProject($proj_id,$ts_stage_fee,$running_cost,$total_cost_nf) {
	
	global $conn;
	global $pdf;
	
	// Establish the parameters for what we are showing

		$time_submit_begin = intval($_POST[submit_begin]);
		$time_submit_end = intval($_POST[submit_end]);
		if ($_POST[submit_project] > 0) { $proj_submit = $_POST[submit_project]; } elseif ($_GET[proj_id] > 0) { $proj_submit = $_GET[proj_id]; } else { header ("Location: index2.php"); }

		if ($time_submit_begin == NULL) { $time_submit_begin = 0; }
		if ($time_submit_end == NULL) { $time_submit_end = time(); }
	
		$sql = "SELECT * FROM intranet_user_details, intranet_timesheet LEFT JOIN intranet_timesheet_fees ON ts_project = ts_fee_stage WHERE ts_user = user_id AND ts_project = '$proj_submit' AND ts_stage_fee = " . $ts_stage_fee . " AND ts_entry BETWEEN '$time_submit_begin' AND '$time_submit_end' ORDER BY ts_fee_commence, ts_stage_fee, ts_fee_time_begin, ts_entry, ts_id";
		$result = mysql_query($sql, $conn) or die(mysql_error());


			$current_fee_stage = NULL;
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
				if ($_POST[separate_pages] != 1) {
					$ts_fee_text = $array_fee_text['ts_fee_text'] . " (Fee target: " . number_format((100 * ($ts_fee_target - 1))) . "%)";
				}
				

				
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
						
				
					$current_y = $pdf->GetY();
					if ($current_y > 240) { $pdf->addPage(); }
					
					$pdf->SetFont('Helvetica','B',12);
					$pdf->Ln(3);
					$pdf->Cell(0,8,$ts_fee_text, 0, 1, L);
					$pdf->Ln(2);
					$pdf->SetFont($format_font,'',6);
					$pdf->Cell(20,4,'Date',0,0,L);
					$pdf->Cell(13,4,'Hours',0,0,R);
					$pdf->Cell(15,4,'Cost (Fa)',0,0,R);
					$pdf->Cell(15,4,'Cost (Ho)',0,0,R);
					$pdf->Cell(8,4,'Init',0,0,C);
					$pdf->Cell(20,4,'Cost (Fa Acc)',0,0,R);
					$pdf->Cell(20,4,'Cost (Ho Acc)',0,0,R);
					$pdf->Cell(0,4,'Description',0,0,L);
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
								
								$pdf->SetDrawColor(0,0,0);
								$pdf->Cell(0,1,'',T,1);
								
								$pdf->SetLineWidth(0.1);
								
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
									$pdf->Cell(40,4,$user_name,0, 0, L, 0);
									}
									
									$pdf->MultiCell(0,4,$ts_desc,0,L);
									
								$pdf->Cell(0,1,'',0,1);
								
								$pdf->SetFont($format_font,'',8);
								
				
			}

						if ($_POST[separate_pages] != 1) {
							StageTotal($stage_total, $hours_total, $running_cost_nf, $ts_fee_target );
							StageFee($ts_stage_fee);
						}
						
						
						
						
						
						




			
			
		$return_array = array($stage_total,$running_cost_nf);
		return $return_array;
	
}

function PDF_ArrayProjectStages($proj_id) {
	
	global $conn;
	global $pdf;
	
	$count = 0;
	
	$sql = "SELECT proj_id,ts_fee_id FROM intranet_projects, intranet_timesheet_fees WHERE proj_id = " . $proj_id . " AND ts_fee_project = proj_id ORDER BY ts_fee_time_begin";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
		if ($count > 0) { $pdf->AddPage(); }
		$running_cost_array = PDFTimeSheetProject($proj_id,$array['ts_fee_id'],$running_cost,$total_cost_nf);
		$running_cost = $running_cost + $running_cost_array[0];
		$total_cost_nf = $total_cost_nf + $running_cost_array[1];
		$count++;
		
			if ($_POST[separate_pages] != 1) {

						$cost_total_print = PDFCurrencyFormat($running_cost);
						
						$total_cost_nf_print = "(" . PDFCurrencyFormat($total_cost_nf) . ")";

						$pdf->SetFont($format_font,'B',8);
						$pdf->Ln(0.1);
						$pdf->SetFillColor(240,240,240);
						$pdf->SetDrawColor(0,0,0);
						$pdf->SetLineWidth(0.5);
						$pdf->Cell(71,7,'Total Cost (excluding profit)','TB', 0, L, 0);
						$pdf->Cell(20,7,$cost_total_print,'TB', 0, R, 0);
						$pdf->Cell(20,7,$total_cost_nf_print,'TB', 0, R, 0);
						$pdf->Cell(0,7,'','TB', 1, R, 0);
				
			}
		
	}
	
		$running_cost_array = PDFTimeSheetProject($proj_id,0,$running_cost,$total_cost_nf);
		$running_cost = $running_cost + $running_cost_array[0];
		$total_cost_nf = $total_cost_nf + $running_cost_array[1];
		
			if ($_POST[separate_pages] != 1) {

						$cost_total_print = PDFCurrencyFormat($running_cost);
						
						$total_cost_nf_print = "(" . PDFCurrencyFormat($total_cost_nf) . ")";

						$pdf->SetFont($format_font,'B',8);
						$pdf->Ln(0.1);
						$pdf->SetFillColor(240,240,240);
						$pdf->SetDrawColor(0,0,0);
						$pdf->SetLineWidth(0.5);
						$pdf->Cell(71,7,'Total Cost (excluding profit)','TB', 0, L, 0);
						$pdf->Cell(20,7,$cost_total_print,'TB', 0, R, 0);
						$pdf->Cell(20,7,$total_cost_nf_print,'TB', 0, R, 0);
						$pdf->Cell(0,7,'','TB', 1, R, 0);
				
			}
			
}


function StyleBody($size,$font,$bold){
			Global $pdf;
			Global $format_font;
			if (!$font) { $font = $format_font; }
			$pdf->SetFont('Helvetica',$bold,$size);
			
}
		
function StyleHeading($title,$text,$notes){
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
			Notes($notes);
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
	
	