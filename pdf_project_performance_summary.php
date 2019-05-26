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

$pdf->addPage('L');

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

PDFProjectArray(intval($_GET['proj_id']));

PDFProjectAnalysis(intval($_GET['proj_id']));
	

// and send to output

$file_date = time();

$file_name = $proj_num."_2.05_".Date("Y",$file_date)."-".Date("m",$file_date)."-".Date("d",$file_date)."_Drawing_Schedule.pdf";

$pdf->Output($file_name,I);
