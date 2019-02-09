<?php

include "inc_files/inc_checkcookie.php";

if ($_GET[proj_id] == NULL) { header ("Location: index2.php"); } else { $proj_id = intval($_GET[proj_id]); }

include "secure/prefs.php";

include "inc_files/inc_action_functions_pdf.php";

function PDFTaskDisplay($proj_id) {
	
	global $conn;
	global $pref_practice;
	global $pdf;
	
	if ($_GET[filter]) { $filter = " AND tasklist_category = '" . addslashes( urldecode ( $_GET[filter] )) . "'"; } else { unset($filter); }
	
	$proj_id = intval($proj_id);
	
	$sql = "SELECT * FROM intranet_tasklist WHERE tasklist_project = " . $proj_id . $filter . " AND tasklist_completed IS NULL ORDER BY tasklist_category, tasklist_due DESC";

	$result = mysql_query($sql, $conn);
	
	$current_category = NULL;

	
	if (mysql_num_rows($result) > 0) {
		
			$pdf->Ln(10);
		
			$current_category = NULL;
			
			while ($array = mysql_fetch_array($result)) {
				
					if ($pdf->GetY() > 260) { $pdf->addPage(); }
					
					if (($array['tasklist_category'] != $current_category) && $array['tasklist_category'] != NULL) {
						
						$pdf->Ln(1);
						$pdf->SetFont('Helvetica','B',11);
						$pdf->Cell(0,6,$array['tasklist_category'],'',1);
						$current_category = $array['tasklist_category'];
						$pdf->Ln(1);
					}
					
					$pdf->Ln(1);
					$current_y = $pdf->GetY();

					$pdf->SetLineWidth(0.25);
					$pdf->SetDrawColor(0,0,0);
					
					if ($array['tasklist_due'] < time()) { $fill = 1; SetBarRed(); }
					elseif (($array['tasklist_due'] - 604800) < time()) { $fill = 1; SetBarOrange(); }
					else { $fill = 0; }
					
					$pdf->SetFont('Helvetica','',10);
					$pdf->MultiCell(115,5,$array['tasklist_notes'],'T','L');
					$next_y = $pdf->GetY();
					$pdf->SetXY(125,$current_y);
					$pdf->Cell(5,5,'','T',0,'R');
					$pdf->Cell(40,5,GetUserNameOnly($array['tasklist_person']),'T',0);
					$pdf->Cell(30,5,TimeFormat($array['tasklist_due']),'T',0,'R',$fill);
					$pdf->SetXY(10,$next_y);
					
					$pdf->Ln(2);
				

				
			}
	
	}
	
}

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->useTemplate($tplidx, 0, 0, 210, 297);

if ($settings_pdffont != NULL) {
$format_font = $settings_pdffont;
$format_font_2 = $settings_pdffont.".php";
} else {
$format_font = "franklingothicbook";
$format_font_2 = "franklingothicbook.php";
}

$pdf->AddFont($format_font,'',$format_font_2);

if ($user_usertype_current < 2) { header ("Location: index2.php"); }

PDFHeader ($proj_id,"Project Tasks");

	$pdf->SetXY(10,70);
	
	$pdf->SetFont($format_font,'',11);
	$pdf->SetTextColor(0, 0, 0);
	
PDFTaskDisplay($proj_id);

// and send to output
$file_name = $proj_num."_".Date("Y",$blog_date)."-".Date("m",$blog_date)."-".Date("d",$blog_date)."_".$blog_type.".pdf";
$pdf->Output($file_name,I);