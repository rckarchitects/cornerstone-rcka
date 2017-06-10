<?php

include "inc_files/inc_checkcookie_logincheck.php";

include "inc_files/inc_checkcookie.php";

//  Use FDPI to get the template

if ($_GET[beginweek]) { $begin_week = BeginWeek(intval($_GET[beginweek])); } else { $begin_week = BeginWeek(time()); }

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf = new fpdi('P','mm','A3');

$format_font = "century";
$format_font_2 = "Century.php";
$pdf->AddFont($format_font,'',$format_font_2);



$pdf->setSourceFile("pdf/template_A3.pdf");
$tplidx = $pdf->ImportPage(1);
$size = $pdf->getTemplateSize($tplidx);

$pdf->addPage('P','a3');
$pdf->useTemplate($tplidx);

$pdf->SetTextColor(0, 0, 0);

// Functions

function SetColor1() { GLOBAL $pdf; $pdf->SetFillColor(254,224,80); }
function SetColor2() { GLOBAL $pdf; $pdf->SetFillColor(255,164,180); }
function SetColor3() { GLOBAL $pdf; $pdf->SetFillColor(255,131,0); }
function SetColor4() { GLOBAL $pdf; $pdf->SetFillColor(177,227,227); }


// Begin creating the page

	$pdf->SetY(19);

	$week_name = TimeFormatDay($begin_week);
	StyleBody(30,'Helvetica','B');
	$pdf->Cell(0,17,$week_name,0,1);
	
	$printed_time = "Printed " . TimeFormatDetailed(time());
	StyleBody(12,'Helvetica','');
	$pdf->Cell(0,10,$printed_time,0,1);

	$pdf->Ln();

	$pdf->SetY(48);

// List this week's holidays




HolidayPanel($begin_week);


//Tenders



	ListTenders($begin_week);

	
// Fee Stages
	
	ListStages($begin_week);
	
// Tasks Due This Week

	TaskDeadlines($begin_week);


// and send to output
$date = date("Y-m-d",$begin_week);
$file_name = $date . "_Weekly_Summary.pdf";


$pdf->Output($file_name,I);

?>
