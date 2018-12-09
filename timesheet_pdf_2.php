<?php

include_once "inc_files/inc_checkcookie.php";

$proj_id = intval($_POST[submit_project]);


if ($user_usertype_current <= 3) { header ("Location: index2.php"); } else {
	
include_once "inc_files/inc_action_functions_pdf.php";

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

ProjectHeading($proj_id,"Timesheet Analysis");

// Printed by, and on...


				if (intval($_POST[submit_begin]) > 0 OR (intval($_POST[submit_end]) > 0)) {
					
					$date_period = "Between " . TimeFormat($$time_submit_end) . " and " . TimeFormat($$time_submit_end) . ".";
					$pdf->Multicell(0,4,$date_period);
					
				}

$pdf->SetFont($format_font,'',12);
$pdf->SetTextColor(0,0,0);


$pdf->SetFont($format_font,'',6);
$pdf->Cell(0,4,$printed_on,0, 1, L, 0);
$pdf->Ln();



$legend = "Cost (Fa) = Factored cost\nCost (Ho) = Hourly cost (not factored)\nCost (Fa Acc) = Factored accumulative cost\nCost (Ho Acc) = Hourly accumulative cost (not factored)\n";

$pdf->MultiCell(0,3,$legend);

$pdf->SetFillColor(220, 220, 220);

PDF_ArrayProjectStages($proj_id);

$file_name = "Project_Analysis_".$proj_num . ".pdf";

$pdf->Output($file_name,I);

}
