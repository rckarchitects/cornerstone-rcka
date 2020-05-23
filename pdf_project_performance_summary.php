<?php

include_once "inc_files/inc_checkcookie.php";
include_once "inc_files/inc_action_functions_pdf.php";
include_once "secure/prefs.php";

if ($user_usertype_current < 4) { header ("Location: index2.php"); }

$proj_id = intval($_GET['proj_id']);

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage('L');

$format_font = PDFFonts($settings_pdffont);

$format_bg_r = "0";
$format_bg_g = "0";
$format_bg_b = "0";

$format_ln_r = "0";
$format_ln_g = "0";
$format_ln_b = "0";



$current_date = TimeFormat(time());

// Begin creating the page

PDFProjectArray($proj_id);

PDFProjectAnalysis($proj_id);
	

// and send to output

$file_date = time();

$file_name = GetProjectNum($proj_id) ."_" . DisplayDay(time()) . "_Project_Performance_Summary.pdf";

$pdf->Output($file_name,'I');
