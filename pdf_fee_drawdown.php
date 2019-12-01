<?php

include "inc_files/inc_checkcookie.php";

if ($_GET[proj_id] == NULL) { header ("Location: index2.php"); } else { $proj_id = intval($_GET[proj_id]); }

if ($user_usertype_current < 2) { header ("Location: index2.php"); }

include "inc_files/inc_action_functions_pdf.php";

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->useTemplate($tplidx, 0, 0, 210, 297);

$format_font = PDFFonts($settings_pdffont);


// Now run the standard functions

	
	if ($_GET['confirmed'] == 1) { $confirmed = 1; } else { unset($confirmed); }
	
	PDF_Fee_Drawdown($proj_id,$confirmed);


// If development code = "yes" (devcode = "yes") in the $_GET request, include some additional data

if ($_GET[devcode] == "yes") { $pdf->MultiCell(0,4,$sql_drawings); } 

// and send to output

$file_date = time();

	$file_name = PDF_FileName ($proj_id, "Fee Schedule");

$pdf->Output($file_name,I);
