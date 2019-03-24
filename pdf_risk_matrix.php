<?php

include "inc_files/inc_checkcookie.php";

if ($_GET[proj_id] == NULL) { header ("Location: index2.php"); } else { $proj_id = intval($_GET[proj_id]); }

include "secure/prefs.php";

include "inc_files/inc_action_functions_pdf.php";

function GetCompanyName($company_id) {
	
	global $conn;
	global $pref_practice;
	$company_id = intval($company_id);
	$sql = "SELECT company_name FROM contacts_companylist WHERE company_id = " . $company_id;
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	
	if ( $array['company_name'] ) { return $array['company_name']; } else { return $pref_practice; }
}

function TableHeadings() {
	
	global $pdf;
	
			$pdf->SetFont('Helvetica','',7);
			$pdf->Cell(10,4,'#','',0);
			$pdf->Cell(50,4,'Risk','',0);
			$pdf->Cell(60,4,'Description','',0);
			$pdf->Cell(60,4,'Analysis','',0);
			$pdf->Cell(20,4,'Score','',0,'C');
			$pdf->Cell(20,4,'Level','',0,'C');
			$pdf->Cell(60,4,'Mitigation','',0);
			$pdf->Cell(25,4,'Date Identified','',0);
			$pdf->Cell(25,4,'Management','',0);
			$pdf->Cell(25,4,'Responsibility','',0);
			$pdf->Cell(15,4,'Transfer','',0,'C');
			$pdf->Cell(15,4,'Eliminate','',0,'C');
			$pdf->Cell(15,4,'Accept','',0,'C');
			$pdf->Cell(0,4,'','',1);
}

function GetHeight($text_array,$font_size,$cell_width_array,$line_height_array) {
	
	global $pdf;
	$pdf->SetFont('Helvetica','',$font_size);
	
	$max_height = 0;
	
	$count = 0;
	
	foreach($text_array AS $text) {
	
		$length = $pdf->GetStringWidth($text);
		
		$cell_width = $cell_width_array[$count];		
		
		$height = ($length / $cell_width) * $line_height_array[$count];

		if ($height >= $max_height) { $max_height = $height; }
		
		$count++;
	
	}
	
	return floatval($max_height);
	
}

function PDFRiskMatrix($proj_id) {
	
	global $conn;
	global $pref_practice;
	global $pdf;
	
	$sql = "SELECT * FROM intranet_project_risks WHERE risk_project = " . intval($proj_id) . " ORDER BY risk_drawing DESC, risk_category, risk_timestamp";

	$result = mysql_query($sql, $conn);
	$counter_1 = 0;
	$counter_2 = 1;
	

	
	if (mysql_num_rows($result) > 0) {
		
		
			$current_category = NULL;
			$new_y = $pdf->GetY();
			$current_y = $pdf->GetY();
			
			$current_drawing = NULL;
			
			$pdf->Ln(5);
			
			$count = 0;
			
			
			while ($array = mysql_fetch_array($result)) {
				
				if ($array['risk_drawing'] && ($current_drawing != $array['risk_drawing'])) { $drawing_ref = "Document Reference: " . $array['risk_drawing']; $pdf->SetFont('Helvetica','B',11); $pdf->Cell(0,5,$drawing_ref); $pdf->Ln(7); TableHeadings(); $download_name_array[] =  urlencode($array['risk_drawing']); $count++; $current_drawing = $array['risk_drawing']; }
				
				//elseif ($current_drawing != $array['risk_drawing'] && $current_drawing != NULL) { $pdf->addPage(); TableHeadings(); $current_drawing = $array['risk_drawing']; $download_name_array[] =  urlencode($array['risk_drawing']); $count++; }
				
				elseif ($count == 0) { TableHeadings(); $count++; }
				
				$text_array = array($array['risk_title'],$array['risk_description'],$array['risk_analysis'],$array['risk_mitigation']);
				$cell_width_array = array(50,60,60,60);
				$line_height_array = array(4,4,4,4);
				
				$max_add = GetHeight($text_array,9,$cell_width_array,$line_height);
				
				if (($pdf->GetY() + $max_add) > 230) { $pdf->Ln(2); $pdf->Cell(0,2,'','T',1); $pdf->addPage(); $current_y = 15; $new_y = 15;  $pdf->SetY($current_y);  TableHeadings(); $count = 1;  }

					
				$company_name = RiskGetCompany($array['risk_responsibility']);
				
				if (!$company_name) { $company_name = $pref_practice; }
					
				if ($current_category != $array['risk_category']) { $pdf->SetLineWidth(0.5); $pdf->SetFont('Helvetica','b',10); $current_category = $array['risk_category']; $counter_1++; $print = $counter_1 . ".0 " ; $pdf->Ln(2); $pdf->Cell(0,2,'','T',1); $pdf->Cell(10,6,$print,0,0); $pdf->Cell(0,6,$array['risk_category'],0,1); $counter_2 = 1; $pdf->Ln(2); } else { $counter_2++; $pdf->Ln(2.5); }

				$pdf->SetLineWidth(0.25);
				$pdf->SetDrawColor(0,0,0);
				
				
				$pdf->Cell(0,2,'','T',1);
				
				
				$current_y = $pdf->GetY();
				
				$pdf->SetFont('Helvetica','',9);
				$counterprint = $counter_1 . "." . $counter_2;
				$pdf->Cell(10,4,$counterprint,0,0);
				
				$pdf->MultiCell(50,4,$array['risk_title'],0,'l');
				if ($pdf->GetY() > $new_y) { $new_y = $pdf->GetY(); }
				$pdf->SetXy(70,$current_y);
				
				$pdf->MultiCell(60,4,$array['risk_description'],0,'l');
				if ($pdf->GetY() > $new_y) { $new_y = $pdf->GetY(); }
				$pdf->SetXy(130,$current_y);
				
				$pdf->MultiCell(60,4,$array['risk_analysis'],0,'l');
				if ($pdf->GetY() > $new_y) { $new_y = $pdf->GetY(); }
				$pdf->SetXy(190,$current_y);
				
				if ( $array['risk_level'] == "red" ) { SetBarRed(); $pdf->SetTextColor(255,255,255); } elseif ($array['risk_level'] == "amber" ) { SetBarOrange(); } else { SetBarDGreen(); }
				$pdf->Cell(20,5,ucwords($array['risk_level']),0,0,'C',1);
				
				if ( $array['risk_score'] == "high" ) { $pdf->SetFillColor(50,50,50); $pdf->SetTextColor(255,255,255); } elseif ($array['risk_score'] == "medium" ) { $pdf->SetFillColor(100,100,100); $pdf->SetTextColor(255,255,255); } else { $pdf->SetFillColor(200,200,200); }
				$pdf->Cell(20,5,ucwords($array['risk_score']),0,0,'C',1);
				$pdf->SetTextColor(0,0,0);
				
				$pdf->MultiCell(60,4,$array['risk_mitigation'],0,'l');
				if ($pdf->GetY() > $new_y) { $new_y = $pdf->GetY(); }
				$pdf->SetXy(290,$current_y);
				
				$pdf->Cell(25,5,TimeFormatBrief(DisplayDate($array['risk_date'])),0,0);
				$pdf->Cell(25,5,ucwords($array['risk_management']),0,0);
				$pdf->Cell(25,5,GetCompanyName($array['risk_responsibility']),0,0);
				
				$pdf->SetFont('ZapfDingbats','', 10);
				
				$symbol = "6";
				
				if ($array['risk_management'] == "accept") { $manage1 = ""; $manage2 = ""; $manage3 = $symbol; }
				elseif ($array['risk_management'] == "eliminate") { $manage1 = ""; $manage2 = $symbol; $manage3 = ""; } 
				elseif ($array['risk_management'] == "transfer") { $manage1 = $symbol; $manage2 = ""; $manage3 = ""; } 
				
				$pdf->Cell(15,4,$manage1,'',0,'C');
				$pdf->Cell(15,4,$manage2,'',0,'C');
				$pdf->Cell(15,4,$manage3,'',0,'C');
				
				$pdf->Cell(0,4,'','',1);
				
				$pdf->SetY($new_y);
			
	
				
			}
			
			$pdf->SetLineWidth(0.5); $pdf->Ln(2); $pdf->Cell(0,2,'','T',1);
	
	}
	
	return $download_name_array;
	
}

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf = new fpdi('L','mm','A3');

$pagecount = $pdf->setSourceFile("pdf/template_A3_landscape.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->useTemplate($tplidx);

if ($settings_pdffont != NULL) {
$format_font = $settings_pdffont;
$format_font_2 = $settings_pdffont.".php";
} else {
$format_font = "franklingothicbook";
$format_font_2 = "franklingothicbook.php";
}

$pdf->AddFont($format_font,'',$format_font_2);

if ($user_usertype_current < 2) { header ("Location: index2.php"); }

PDFHeader ($proj_id,"Project Risk Matrix");

	$pdf->SetXY(10,70);
	
	$pdf->SetFont($format_font,'',11);
	$pdf->SetTextColor(0, 0, 0);
	
	$pdf->Ln(5);
	
$download_name_array = PDFRiskMatrix($proj_id);


if (count($download_name_array) > 0) { 

	foreach($download_name_array AS $drawing_title) {
		
		$drawing_name = $drawing_name . $drawing_title . "+";
		
	}
	
	$drawing_name  = rtrim($drawing_name,"+");
	
} else {
	
	$drawing_name = $array['risk_project'];
	
}

// and send to output
$file_name = $drawing_name . "_".Date("Y",time())."-".Date("m",time())."-".Date("d",time())."_(Risk Matrix).pdf";
$pdf->Output($file_name,I);