<?php

include_once "inc_files/inc_checkcookie.php";
include_once "inc_files/inc_action_functions_pdf.php";
include_once "secure/prefs.php";

if ($_GET[proj_id] == NULL) { header ("Location: index2.php"); } else { $proj_id = intval($_GET[proj_id]); }


function GetCompanyName($company_id) {
	
	global $conn;
	global $pref_practice;
	$company_id = intval($company_id);
	$sql = "SELECT company_name FROM contacts_companylist WHERE company_id = " . $company_id;
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	
	if ( $company_id > 0 ) { return $array['company_name']; } else { return $pref_practice; }
}

function PDFRiskDisplay($proj_id) {
	
	global $conn;
	global $pref_practice;
	global $pdf;
	
	$sql = "SELECT * FROM intranet_project_risks WHERE risk_project = " . intval($proj_id) . " ORDER BY risk_drawing DESC, risk_category, risk_id";

	$result = mysql_query($sql, $conn);
	$counter_1 = 0;
	$counter_2 = 1;
	
	if (mysql_num_rows($result) > 0) {
		
		
			$current_category = NULL;
			
			while ($array = mysql_fetch_array($result)) {
				
			if ($pdf->GetY() > 200) { $pdf->addPage(); }
			$new_y = 0;
			$current_y = 0;
				
			$company_name = RiskGetCompany($array['risk_responsibility']);
				
			if (!$company_name) { $company_name = $pref_practice; }
				
			if ($current_category != $array['risk_category']) { $pdf->SetFont('Helvetica','b',14); $current_category = $array['risk_category']; $counter_1++; $print = $counter_1 . ".0 " . $array['risk_category']; $pdf->Ln(5); $pdf->Cell(0,7,$print,'b',1); $counter_2 = 1; $pdf->Ln(2); } else { $counter_2++; $pdf->Ln(2.5); }
			
			//if ($array['risk_management'] == "transfer") { $management_1 = "X"; unset($management_2); unset($management_3); }
			//elseif ($array['risk_management'] == "eliminate") { $management_2 = "X"; unset($management_3); unset($management_1); }
			//elseif ($array['risk_management'] == "accept") { $management_3 = "X"; unset($management_1); unset($management_2); }
			
			if (intval($array['risk_resolved']) != 1) {
			
					
					$pdf->SetLineWidth(0.25);
					$pdf->SetDrawColor(0,0,0);
					
					$pdf->SetFont('Helvetica','B',10);
					$counterprint = $counter_1 . "." . $counter_2;
					$pdf->Cell(10,5,$counterprint,'B',0);
					$pdf->Cell(0,5,ucwords($array['risk_title']),'B',1);
					$pdf->SetFont('Helvetica','',7);
					
					if ( $array['risk_level'] == "red" ) { SetBarRed(); } elseif ($array['risk_level'] == "amber" ) { SetBarOrange(); } else { SetBarDGreen(); }
					
					$pdf->Cell(5,5,'',1,0,0,1);
					$pdf->Cell(5,5,'',0);
					$pdf->Cell(90,5,'Description','',0);
					$pdf->Cell(90,5,'Analysis','',1);
					
					$pdf->SetFont('Helvetica','',9);
					
					
					$pdf->Ln(1);
					$current_y = $pdf->GetY();
					$pdf->Cell(10,5,'','',0);
					$pdf->MultiCell(90,4,$array['risk_description'],'b','l');
					$new_y = $pdf->GetY();
					$pdf->SetXY(110,$current_y);
					$pdf->MultiCell(90,4,$array['risk_analysis'] ,'b','l');
					if ($pdf->GetY() > $new_y) { $pdf->SetX(10); } else { $pdf->SetXY(10,$new_y); }
					$pdf->Ln(3);
					$pdf->Cell(10,5,'',0);
					$pdf->SetLineWidth(0.1);
					$pdf->SetFont('Helvetica','',7);
					$pdf->Cell(20,5,'Likelihood','TB',0);
					$pdf->SetFont('Helvetica','',9);
					$pdf->Cell(70,5,ucwords($array['risk_score']),'TB',0);
					$pdf->SetFont('Helvetica','',7);
					$pdf->Cell(20,5,'Impact','TB',0);
					$pdf->SetFont('Helvetica','',9);
					$pdf->Cell(70,5,ucwords($array['risk_level']),'TB',1);
					$pdf->Ln(2);
					
					$pdf->SetFont('Helvetica','',10);
					$pdf->Cell(10,5,'','',0);
					$pdf->SetFont('Helvetica','',7);
					$pdf->Cell(90,5,'Warning Signs','',0);
					$pdf->Cell(90,5,'Mitigation Strategy','',1);
					$pdf->SetFont('Helvetica','',9);
					$pdf->Ln(1);
					
					$current_y = $pdf->GetY();
					$pdf->Cell(10,5,'','',0);
					$pdf->MultiCell(90,4,$array['risk_warnings'],'b','l');
					$new_y = $pdf->GetY();
					$pdf->SetXY(110,$current_y);
					$pdf->MultiCell(90,4,$array['risk_mitigation'] ,0,'l');
					if ($pdf->GetY() > $new_y) { $pdf->SetX(10); } else { $pdf->SetXY(10,$new_y); }
					$pdf->Ln(3);
					
					$pdf->Cell(10,5,'',0);
					$pdf->SetLineWidth(0.1);
					$pdf->SetFont('Helvetica','',7);
					$pdf->Cell(20,5,'Date Identified','TB',0);
					$pdf->SetFont('Helvetica','',9);
					$pdf->Cell(25,5,TimeFormatBrief(DisplayDate($array['risk_date'])),'TB',0);
					$pdf->SetFont('Helvetica','',7);
					$pdf->Cell(20,5,'Management','TB',0);
					$pdf->SetFont('Helvetica','',9);
					$pdf->Cell(25,5,ucwords($array['risk_management']),'TB',0);

					$pdf->SetFont('Helvetica','',7);
					$pdf->Cell(20,5,'Owner','TB',0);
					$pdf->SetFont('Helvetica','',9);
					$pdf->Cell(0,5,GetCompanyName($array['risk_responsibility']),'TB',0);
					$pdf->SetFont('Helvetica','',7);

					$pdf->Ln(3);
			
			}
			
			// . "</td><td>" . $array['risk_warnings'] . "</td><td>" . $array['risk_mitigation'] . "</td><td style=\"text-align: center;\">" . $management_1 . "</td><td style=\"text-align: center;\">" . $management_2 . "</td><td style=\"text-align: center;\">" . $management_3 . "</td><td>" . $company_name . "</td><td style=\"text-align: right;\">" . $array['risk_date'] . "</td></tr>";
			
			$pdf->SetLineWidth(0.25);		
				
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

$format_font = PDFFonts($settings_pdffont);

if ($user_usertype_current < 2) { header ("Location: index2.php"); }

PDFHeader ($proj_id,"Project Risk Register");

	$pdf->SetXY(10,70);
	
	$pdf->SetFont($format_font,'',11);
	$pdf->SetTextColor(0, 0, 0);
	
PDFRiskDisplay($proj_id);

// and send to output
$file_name = $proj_num."_".Date("Y",$blog_date)."-".Date("m",$blog_date)."-".Date("d",$blog_date)."_".$blog_type.".pdf";
$pdf->Output($file_name,I);