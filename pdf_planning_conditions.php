<?php

$format_bg_r = "0";
$format_bg_g = "0";
$format_bg_b = "0";

if ($_GET[proj_id] != NULL) { $proj_id = $_GET[proj_id]; } else { header ("Location: ../index2.php"); }



include_once "inc_files/inc_checkcookie.php";

include_once "secure/prefs.php";

include "inc_files/inc_action_functions_pdf.php";

$proj_num = GetProjectNum($proj_id);

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$format_font = "century";
$format_font_2 = "Century.php";
$pdf->AddFont($format_font,'',$format_font_2);

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->useTemplate($tplidx);

// Begin creating the page

	$project_counter = 1;
	$page_count = 1;


	


ProjectHeading($proj_id, "Planning Conditions Tracker");




	
	// Begin the conditions array
	
	$sql_conditions = "SELECT * FROM intranet_projects_planning LEFT JOIN contacts_companylist ON company_id = condition_responsibility WHERE condition_project = $proj_id ORDER BY condition_decision_date, condition_ref, condition_number";
	$result_conditions = mysql_query($sql_conditions, $conn) or die(mysql_error());
	
		if (mysql_num_rows($result_conditions) > 0) {
			
			unset($current_ref);
			
			while ($array_conditions = mysql_fetch_array($result_conditions)) {
				
				if ($pdf->GetY() > 200) { $pdf->addPage();}
				
				if ($array_conditions['condition_type'] == "Informative Only") { $condition_approved = "Not required";} elseif ($array_conditions['condition_approved'] != "0000-00-00") { $condition_approved = date( "j M Y", AssessDays ( $array_conditions['condition_approved'] ) ); } elseif ($array_conditions['condition_submitted'] != "0000-00-00") { $condition_approved = "Submitted"; } else { $condition_approved = "Not approved"; }
				
				if ($array_conditions['condition_type'] == "Informative Only") {
				$condition_submitted = "Not applicable";  $pdf->SetFillColor(225,225,225);
					} elseif ($array_conditions['condition_submitted'] != "0000-00-00") { $condition_submitted = date( "j M Y", AssessDays ( 			$array_conditions['condition_submitted'] ) ); $pdf->SetFillColor(254,240,120);
					} elseif ($array_conditions['condition_type'] == "Pre-Occupation" && $array_conditions['condition_submitted'] != "0000-00-00") { $condition_submitted = date( "j M Y", AssessDays ( $array_conditions['condition_submitted'] ) );   $pdf->SetFillColor(255,255,120);
					} elseif ($array_conditions['condition_type'] == "Pre-Occupation" && $array_conditions['condition_submitted'] == "0000-00-00") { $condition_submitted = "- None -";  $pdf->SetFillColor(255,255,120);
					} else {
						$condition_submitted = "Not submitted"; $pdf->SetFillColor(254,120,120);
					}
					
					
				if ($array_conditions['condition_submitted'] != "0000-00-00" && $array_conditions['condition_approved'] != "0000-00-00") { $pdf->SetFillColor(200,254,150); }

				
			
				if ($array_conditions['condition_decision_date'] > 0) {
					$condition_decision_date = AssessDays ( $array_conditions['condition_decision_date'] );
				}
				
				if ($array_conditions['condition_decision_date'] == "0000-00-00") { $condition_decision_date = "- None -"; } else { $condition_decision_date = "Approval Date: " . date( "j M Y", $condition_decision_date ); }
			
				if ($array_conditions['company_name'] != NULL) { $company_name = $array_conditions['company_name'];	} elseif ($array_conditions['condition_type'] == "Informative Only") { unset($company_name); } else { $company_name = $pref_practice; }
				
				if ($current_ref != $array_conditions['condition_ref']) {
					
					$pdf->ln(10);
					
					$pdf->SetFont('Helvetica','b',12);
					
					$condition_ref_print = "Planning Reference: " . $array_conditions['condition_ref'];
					if ($array_conditions['condition_link'] != NULL) {
					$pdf->Cell(105,7.5,$condition_ref_print,B,0,L,false,$array_conditions['condition_link']);
					} else {
					$pdf->Cell(105,7.5,$condition_ref_print,B,0);	
					}
					$pdf->Cell(0,7.5,$condition_decision_date,B,1);
					
					StyleBody(10);
					
				
				}
				
				$pdf->SetFont('Helvetica',B,10);
				$pdf->Cell(15,5,$array_conditions['condition_number'],T,0,'',true);
				$pdf->SetFont('Helvetica','',10);
				$pdf->Cell(45,5,$condition_submitted,T,0,'',true);
				$pdf->Cell(45,5,$condition_approved,T,0,'',true);
				$pdf->Cell(0,5,$array_conditions['condition_type'],T,1,'',true);
				
				$pdf->SetFont('Helvetica','',9);
				$pdf->Cell(15,5,'',B,0,'',true);
				if ($company_name != NULL) {
					$company_name = "Responsibility: " . $company_name;
				}
				$pdf->Cell(90,5,$company_name,B,0,'',true);
				if ($array_conditions['condition_submitted_ref']) { $condition_submitted_ref = "Ref: " . $array_conditions['condition_submitted_ref']; } else { unset($condition_submitted_ref); }
				$pdf->Cell(0,5,$condition_submitted_ref,B,1,'',true);
				
				
				StyleBody(10);
				$pdf->ln(2.5);
				
				$pdf->SetX(25);
				$pdf->MultiCell(150,5,$array_conditions['condition_text'],0,L);

				if ($array_conditions['condition_note'] != NULL) {
					$pdf->ln(2.5);
					$pdf->SetX(25);
					$pdf->SetFont('Helvetica',B,10);
					$pdf->Cell(0,7.5,"Notes:",T,1);
					$pdf->SetX(25);
					StyleBody(10);
					$pdf->MultiCell(150,5,$array_conditions['condition_note'],0,L);
				}

				$pdf->SetX(15);
				
				$pdf->SetX(10);
				
				$pdf->ln(5);
				
				$current_ref = $array_conditions['condition_ref'];
				
						
			}
			
			
		}


// and send to output

$file_date = time();

$file_name = $proj_num."_".Date("Y",$file_date)."-".Date("m",$file_date)."-".Date("d",$file_date)."_Planning_Tracker.pdf";

$pdf_title = $pref_practice . " Planning Tracker: " . $proj_num . " " . $proj_name . "_" . Date("Y",$file_date)."-".Date("m",$file_date)."-".Date("d",$file_date);

$pdf->SetTitle($pdf_title);

$pdf->Output($file_name,I);