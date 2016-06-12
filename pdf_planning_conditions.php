<?php

$format_bg_r = "0";
$format_bg_g = "0";
$format_bg_b = "0";

if ($_GET[proj_id] != NULL) { $proj_id = $_GET[proj_id]; } else { header ("Location: ../index2.php"); }

include_once "inc_files/inc_checkcookie.php";

include_once "secure/prefs.php";

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

// Functions

		function StyleBody($input){
			Global $pdf;
			Global $format_font;
			$pdf->SetFont($format_font,'',$input);
			$pdf->SetTextColor(0, 0, 0);
		}

		function Notes($input) {
			Global $pdf;
			Global $x_current;
			Global $y_current;
			$pdf->SetX($x_current);
			//$print_string = DeCode($input);
			$print_string = $input;
			if ($input != NULL) {
			StyleBody(9);
			$pdf->SetTextColor(150, 150, 150);
			$pdf->MultiCell(90,3,$print_string,0, L, false);
			$pdf->MultiCell(90,1,'',0, L, false);
			}
		}
		
		function StyleHeading($title,$text,$notes){
			Global $pdf;
			Global $x_current;
			Global $y_current;
			Global $y_left;
			Global $y_right;
			if ($y_current > 230 AND $x_current > 75) { $pdf->addPage(); $x_current = 10; $y_current = 15; }
			$pdf->SetXY($x_current,$y_current);
			$pdf->SetFont('Helvetica','b',12);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetX($x_current);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetLineWidth(0.3);
			$pdf->MultiCell(90,5,$title,B, L, false);
			$pdf->MultiCell(90,1,'',0, L, false);
			Notes($notes);
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
		

// Begin creating the page

	$project_counter = 1;
	$page_count = 1;

	$pdf->SetY(35);
	$pdf->SetFont('Helvetica','',18);

	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(0,10,"Planning Conditions Tracker");

	$pdf->SetY(50);
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

	$y_current = 70;
	$x_current = 10;

// Project Address


		$proj_address = $proj_address.AddLine($proj_address_1);
		$proj_address = $proj_address.AddLine($proj_address_2);
		$proj_address = $proj_address.AddLine($proj_address_3);
		$proj_address = $proj_address.AddLine($proj_address_town);
		$proj_address = $proj_address.AddLine($proj_address_county);
		$proj_address = $proj_address.AddLine($proj_address_postcode);
		StyleHeading("Site Address",$proj_address);

	
	// Begin the conditions array
	
	$sql_conditions = "SELECT * FROM intranet_projects_planning LEFT JOIN contacts_companylist ON company_id = condition_responsibility WHERE condition_project = $proj_id ORDER BY condition_decision_date, condition_ref, condition_number";
	$result_conditions = mysql_query($sql_conditions, $conn) or die(mysql_error());
	
		if (mysql_num_rows($result_conditions) > 0) {
			
			unset($current_ref);
			
			while ($array_conditions = mysql_fetch_array($result_conditions)) {
				
				if ($array_conditions['condition_type'] == "Informative Only") { $condition_approved = "Not approved";} elseif ($array_conditions['condition_approved'] != "0000-00-00") { $condition_approved = date( "j M Y", AssessDays ( $array_conditions['condition_approved'] ) ); } else { $condition_approved = "Not approved"; }
				
				if ($array_conditions['condition_type'] == "Informative Only") {
				$condition_submitted = "Not applicable";  $pdf->SetFillColor(225,225,225);
					} elseif ($array_conditions['condition_submitted'] != "0000-00-00") { $condition_submitted = date( "j M Y", AssessDays ( 			$array_conditions['condition_submitted'] ) ); $pdf->SetFillColor(254,240,120);
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
					
					$pdf->Cell(105,7.5,$condition_ref_print,B,0);
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
				
				if ($pdf->GetY() > 200) { $pdf->addPage();}
						
			}
			
			
		}


// and send to output

$pdf->Output();
?>
