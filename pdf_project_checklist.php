<?php

include "inc_files/inc_checkcookie.php";

if ($_GET[proj_id] == NULL) { header ("Location: index2.php"); }

if ($user_usertype_current < 2) { header ("Location: index2.php"); }

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->useTemplate($tplidx, 0, 0, 210, 297);

$format_font = "century";
$format_font_2 = "Century.php";

$pdf->AddFont($format_font,'',$format_font_2);


$format_bg_r = "220";
$format_bg_g = "220";
$format_bg_b = "220";

$format_ln_r = "220";
$format_ln_g = "220";
$format_ln_b = "220";



$current_date = TimeFormat(time());
$proj_id = CleanUp($_GET[proj_id]);

// Begin creating the page

//Page Title

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
	

$sql_checklist = "SELECT * FROM intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON checklist_item = item_id AND checklist_project = $proj_id WHERE checklist_required != 1 ORDER BY item_group, item_order, checklist_date DESC, item_name";
$result_checklist = mysql_query($sql_checklist, $conn) or die(mysql_error());


$y = $pdf->GetY() + 10;
$pdf->SetY($y);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetLineWidth(0.3);
$pdf->SetFont("Helvetica",'B',7);
$pdf->Cell(65,5,"Item",B,0,L,0);
$pdf->Cell(15,5,"Required",B,0,L,0);
$pdf->Cell(30,5,"Date Completed",B,0,L,0);
$pdf->Cell(80,5,"Comment",B,1,L,0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont($format_font,'',7);
$pdf->SetFillColor(240,240,240);

$pdf->SetLineWidth(0.1);

$fill = 0;

$group = NULL;

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
	
		if ($checklist_required == 2) { $checklist_required_print = "Yes"; $pdf->SetFont($format_font,'',7); }
		elseif ($checklist_required == 1) { $checklist_required_print = "No"; $pdf->SetFont($format_font,'',7); }
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
				$pdf->SetFont($format_font,'',7);
		}
		
		$border = T;
		
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
		$pdf->Cell(60,5,$item_name,$border,0,L,$fill,$checklist_link);
		$pdf->Cell(15,5,$checklist_required_print,$border,0,L,$fill);
		$pdf->Cell(30,5,$checklist_date_print,$border,0,L,$fill);
		$pdf->Cell(0,1,'',$border,2);
		$pdf->MultiCell(80,3,$checklist_comment,0,L,$fill);
		$pdf->Cell(0,1,'',0,1);
		
		$y = $pdf->GetY();
		
	$group = $item_group;
		
}

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



// If development code = "yes" (devcode = "yes") in the $_GET request, include some additional data

if ($_GET[devcode] == "yes") { $pdf->MultiCell(0,4,$sql_drawings); } 

// and send to output

$file_date = time();

$file_name = $proj_num."_2.05_".Date("Y",$file_date)."-".Date("m",$file_date)."-".Date("d",$file_date)."_Project_Checklist.pdf";

$pdf->Output($file_name,I);

?>
