<?php

include "inc_files/inc_checkcookie.php";

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

function NewPage() {

	GLOBAL $pdf;
	$pdf->addPage();
	$current_y = $pdf->GetY();
	$new_y = $current_y + 50;
	$pdf->SetY($new_y);

}


function Paragraph ($input) {
	
	GLOBAL $pdf;
	GLOBAL $format_font;
	
	$text_array = explode ("\n",$input);
	
	$header = 1;
	
	foreach ($text_array AS $para ) {
		
		$para = trim($para);
		
		
		
		$pdf->SetTextColor(0);
		if (substr($para,0,2) == "- ") {
			$pdf->SetFont('ZapfDingbats','',4);
			$para = trim($para,"- ");
			$pdf->SetX(0);
			$pdf->Cell(30,5,'l',0,0,R,0);
			$pdf->SetX(30);
			$pdf->SetFont($format_font,'',11);
			$pdf->MultiCell(145,5,$para,0,L);
		} elseif (substr($para,0,1) == "|") {
			if ($header == 1) { $pdf->SetLineWidth(0.5); $header = 0; } else { $pdf->SetLineWidth(0.2); }
			$row = explode ("|",$para);
			$delete = array_shift($row);
			foreach ($row AS $cell ) {
				$cell_width = 150 / count($row);
				$pdf->SetFont($format_font,'',10);
				$pdf->Cell($cell_width,7,$cell,1,0,L,0);
				$pdf->SetFont($format_font,'',11);
			}
			$pdf->Ln(7);
			$pdf->SetX(25);
		} else {
		$pdf->SetX(25);
		$pdf->SetFont($format_font,'',11);
		$pdf->MultiCell(150,5,$para,0,L);
		}
		
		
	
	}
	
	
}

function UpDate ($qms_date) {
						
						GLOBAL $pdf;
						
						$current_x = $pdf->GetX();
						$current_y = $pdf->GetY();
						$new_y = $pdf->GetY() + 2;
					
						$pdf->SetXY(180,$new_y);
						$pdf->SetTextColor(180);
						$pdf->SetDrawColor(180);
						$pdf->SetFont('Helvetica','',5);
						$pdf->Cell(0,2,$qms_date,0,0);
						$pdf->SetTextColor(0);
						
						$pdf->SetXY($current_x,$current_y);
					
					}
					
function AddBullets($input) {
	
		GLOBAL $pdf;
		
		if (substr($input,2) == "- ") {
			
			
		} else {
			
			
		}
	
	
}

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->SetMargins(10,15,10);
$pdf->useTemplate($tplidx, 0, 0, 210, 297);

if ($settings_pdffont != NULL) {
$format_font = $settings_pdffont;
$format_font_2 = $settings_pdffont.".php";
} else {
$format_font = "franklingothicbook";
$format_font_2 = "franklingothicbook.php";
}

if ($_GET[s1] > 0) {
	
	$s1 = intval($_GET[s1]);
	$s2 = intval($_GET[s2]);
	$sql_firstpage = "SELECT qms_text FROM intranet_qms WHERE qms_toc1 = $s1 AND qms_toc2 = $s2";
	$result_firstpage = mysql_query($sql_firstpage, $conn) or die(mysql_error());
	$array_firstpage = mysql_fetch_array($result_firstpage);
	$qms_firstpage = strip_tags ( $array_firstpage['qms_text'] );
	
}

	if ($_GET[s1] > 0) { $s1 = intval($_GET[s1]); $section = "Section " . $_GET[s1] . " only - " . $qms_firstpage; $s1 = " WHERE qms_toc1 = $s1 "; } else { unset($s1); }
	if ($_GET[s2] > 0) { $s2 = intval($_GET[s2]); $section = "Section " . $_GET[s1] . "." . $_GET[s2] . " only - " . $qms_firstpage; $s2 = " AND qms_toc2 = $s2 "; } else { unset($s2); }

$pdf->AddFont($format_font,'',$format_font_2);

	$pdf->SetXY(10,175);
	
	$pdf->SetTextColor(0, 0, 0);
	
	$pdf->SetFillColor(220,220,220);
	
	$pdf->SetFont('Helvetica','',18);
	$pdf->MultiCell(0,10,$settings_companyname,0,L);
	$pdf->SetFont('Helvetica','B',32);
	$pdf->MultiCell(0,20,'Quality Management System',0,L);
	$pdf->SetFont($format_font,'',14);
	$printed_date = "Current at " . date("g.ia, jS F Y",time());
	$pdf->MultiCell(0,25,'',0,L);
	
	$pdf->MultiCell(0,8,$printed_date,0,L);
	$pdf->SetLineWidth(0.1);
	$pdf->SetFont($format_font,'',11);
	$width = $pdf->GetStringWidth($printed_date) + 20;
	if ($s1 != NULL OR $s2 != NULL) { $pdf->MultiCell(0,8,$section,T,L); }
	



$sql = "SELECT * FROM intranet_qms $s1 $s2 ORDER BY qms_toc1, qms_toc2, qms_toc3, qms_toc4";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
					

			while ($array = mysql_fetch_array($result)) {
				
					$qms_id = $array['qms_id'];
					$qms_toc1 = $array['qms_toc1'];
					$qms_toc2 = $array['qms_toc2'];
					$qms_toc3 = $array['qms_toc3'];
					$qms_toc4 = $array['qms_toc4'];
					$qms_type = $array['qms_type'];
					$qms_text = strip_tags ( $array['qms_text'] );
					$qms_timestamp = $array['qms_timestamp'];
					$qms_date = date("d M Y",$qms_timestamp);
					
					$number = $qms_toc4;
					
					

						
					if ($qms_toc4 > 0 && $qms_type == "code") { if ($pdf->GetY() > 270) { $pdf->addPage(); } UpDate ($qms_date); $pdf->SetTextColor(180); $pdf->SetFont('Helvetica','',5); $pdf->Cell(15,5,$number,0,0,R); $pdf->SetFont('Courier','',10); $pdf->SetTextColor(0); $pdf->Cell(150,2,'',0,2,'',1); $pdf->MultiCell(150,4.5,$qms_text,0,'',true); $pdf->SetX(25); $pdf->Cell(150,2,'',0,2,'',1); $pdf->Cell(0,3,'',0,1); }
					
					elseif ($qms_toc4 > 0 && $qms_type == "comp") { if ($pdf->GetY() > 260) { $pdf->addPage(); } UpDate ($qms_date); $pdf->SetTextColor(180); $pdf->SetFont('Helvetica','',5); $pdf->Cell(15,5,$number,0,0,R); $pdf->SetTextColor(0); $pdf->SetLineWidth(0.5); $pdf->SetDrawColor(100);$pdf->Cell(1,3,'',0,0); $pdf->Cell(149,15,$qms_text,1,1); $pdf->Cell(0,3,'',0,1); }
					
					elseif ($qms_toc4 > 0 && $qms_type == "image") { $max_width = 150; $image = "images/" . $qms_text ; $image_size = GetImagesize($image); $image_height = $image_size[1]; $image_width = $image_size[0]; $height = ($image_height / $image_width) * $max_width;  if ($pdf->GetY() + $height > 270) { $pdf->addPage(); } UpDate ($qms_date); $pdf->SetTextColor(180); $pdf->SetFont('Helvetica','',5); $pdf->Cell(15,5,$number,0,0,R); $x = $pdf->GetX(); $y = $pdf->GetY();  $pdf->Image($image,$x,$y,$max_width,$height); $y = ( $pdf->GetY() + $height + 2 ); $pdf->SetY($y); unset($x); unset($y); }
					
					elseif ($qms_toc4 > 0 && $qms_type == "check") { if ($pdf->GetY() > 260) { $pdf->addPage(); } UpDate ($qms_date); $pdf->SetTextColor(180); $pdf->SetFont('Helvetica','',5); $pdf->Cell(15,5,$number,0,0,R); $pdf->SetTextColor(0); $pdf->SetLineWidth(0.5); $pdf->SetDrawColor(100); $pdf->Cell(1,3,'',0,0); $pdf->Cell(10,6,'',1,0); $pdf->Cell(2,3,'',0,0); $pdf->SetFont($format_font,'',11); $pdf->MultiCell(135,5,$qms_text,0,L); $pdf->Cell(0,3,'',0,1); }
					
					elseif ($qms_toc4 > 0 && $qms_type == NULL) { if ($pdf->GetY() > 270) { $pdf->addPage(); } UpDate ($qms_date); $pdf->SetTextColor(180); $pdf->SetFont('Helvetica','',5); $pdf->SetTextColor(180); $pdf->Cell(15,5,$number,0,0,R); Paragraph($qms_text); $pdf->Cell(0,4,'',0,1); }

					elseif ($qms_toc3 > 0) { if ($pdf->GetY() > 210) { $pdf->addPage(); } UpDate ($qms_date);  $pdf->Cell(0,6,'',0,1); $pdf->SetFont('Helvetica','',12); $number = $qms_toc1 . "." .  $qms_toc2 . "." .  $qms_toc3; $pdf->Cell(15,6,$number,0,0,R); $pdf->Cell(150,6,$qms_text,0,2); $pdf->Cell(0,4,'',0,1); }

					elseif ($qms_toc2 > 0) {  $pdf->Cell(0,8,'',0,1); if ($pdf->GetY() > 210) { $pdf->addPage(); } UpDate ($qms_date); $pdf->SetFont('Helvetica','',14); $number = $qms_toc1 . "." .  $qms_toc2; $pdf->Cell(15,8,$number,0,0,R); $pdf->Cell(150,8,$qms_text,0,2); $pdf->Cell(0,5,'',0,1);  }

					elseif ($qms_toc1 > 0) {  NewPage(); UpDate ($qms_date); $pdf->SetFont('Helvetica','B',16); $pdf->Cell(15,10,$qms_toc1,0,0,R); $pdf->Cell(150,10,$qms_text,0,2); $pdf->Cell(0,5,'',0,1); }
					
					
					
					
}

	




// and send to output

$file_name = $proj_num."_".Date("Y",$blog_date)."-".Date("m",$blog_date)."-".Date("d",$blog_date)."_".$blog_type.".pdf";

$pdf->Output($file_name,I);

?>
