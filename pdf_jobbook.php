<?php

include "inc_files/inc_checkcookie.php";

if ($user_usertype_current < 2) { header ("Location: index2.php"); }

include "inc_files/inc_action_functions_pdf.php";

//  Use FDPI to get the template

require('fpdf/fpdf.php');

$pdf = new FPDF('L','mm',array(594,841));

$pdf->addPage();
$pdf->SetFont('Helvetica','B',20);
$pdf->Cell(0,10,'Job Book',0,1);

function PDFJobBookGetData () {

	global $conn;
	global $pdf;
	
	$column_width = ColumnWidth();
	
	$pdf->SetFont('Helvetica','',0);
	
	$sql = "SELECT DISTINCT book_title, book_title FROM intranet_timesheet_group_jobbook GROUP BY book_title ORDER BY book_order";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	if (mysql_num_rows($result) > 0) {
		
		$pdf->SetFont('Helvetica','B',14);
		
		PDFStagesHeader($column_width);
		
		while ($array = mysql_fetch_array($result)) {
			
			PDFRowArray($array['book_title'],$column_width);
			
			$pdf->SetX(10);
		
		}
		
	}
	
	
}

function PDFCountStages() {
	
	global $conn;
	$sql = "SELECT COUNT(group_id) FROM intranet_timesheet_group WHERE group_active = 1 AND group_project = 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	
	$output = intval ( $array['COUNT(group_id)'] );
	
	return $output;
	
}

function ColumnWidth() {
	
	$page_width = 841;
	$page_width = $page_width - 125;
	$column_width = $page_width / PDFCountStages();
	$column_width = round($column_width);
	
	return $column_width;
	
}

function PDFStagesHeader($column_width) {
	
	global $conn;
	global $pdf;
	
	$sql = "SELECT * FROM intranet_timesheet_group WHERE group_active = 1 AND group_project = 1 ORDER BY group_order";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	if (mysql_num_rows($result) > 0) {
		
		$pdf->Cell(100,20,'',0,0);
		
		while ($array = mysql_fetch_array($result)) {
			
			if ($array['group_color']) {
				
				$y = $pdf->GetY(); $x = $pdf->GetX();
				$pageheight = 594; // For some reason $pdf->GetPageHeight() doesn't seem to work, although according to the manual it should
				$boxheight = $pageheight - $y - 30;
				$cellcolor = ExplodeRGB($array['group_color']);
				
				$pdf->SetFillColor($cellcolor[0],$cellcolor[1],$cellcolor[2]);
				$pdf->Cell($column_width,$boxheight,NULL,0,0,'L',1);
				
				$pdf->SetX($x);
			}
			
			$x = $pdf->GetX();
			$y = $pdf->GetY();

			$pdf->Cell($column_width,10,PDFTidyText($array['group_code']),0,1,'C');
			$pdf->SetX($x);
			
			$column_width_buffer = $column_width - 2;
			
			PDF_TextShrinker(14,$array['group_description'],$column_width_buffer,'Helvetica','B');
			
			$pdf->Cell($column_width,10,PDFTidyText($array['group_description']),0,0,'C');
			$x = $x + $column_width;
			$pdf->SetXY($x,$y);
		
		}
		
		
		$pdf->Ln(20);
		
	}
	
}

function PDFRowArray($book_title,$column_width) {
	
	global $conn;
	global $pdf;
	
	$book_title = addslashes($book_title);
	
	$sql = "SELECT * FROM intranet_timesheet_group WHERE group_active = 1 AND group_project = 1 ORDER BY group_order";

	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	if (mysql_num_rows($result) > 0) {
		
		$pdf->SetFont('Helvetica','B',10);

		$pdf->Cell(0,5,NULL,'T',0);
		$pdf->Ln(5);
		
		$pdf->Cell(100,3,strip_tags($book_title),0,0,'L');
		
		$sql = "SELECT * FROM intranet_timesheet_group LEFT JOIN intranet_timesheet_group_jobbook ON book_stage = group_id AND book_title = '" . addslashes($book_title) . "' AND group_active = 1 AND group_project = 1 ORDER BY group_order";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$maxy = $y;
		
		if ($pdf->GetY() > $maxy) { $maxy = $pdf->GetY(); } $pdf->SetXY($x,$y);
		
		while ($array = mysql_fetch_array($result)) {
			
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->MultiCell($column_width,3,PDFTidyText($array['book_description']),0,'L',0);
			
			if ($pdf->GetY() > $maxy) { $maxy = $pdf->GetY(); } $x = $x + $column_width; $pdf->SetXY($x,$y);
		
		}
		
		$newy = $maxy + 5;
		$pdf->SetY($newy);

		
	}	
	
	
}

function ExplodeRGB($input) {
	
	$output = explode(",", $input);
	
	return $output;
	
}

function PDFTidyText ($text) {
	
	$text = str_replace("<li>","- ",$text);
	
	$output = html_entity_decode(trim(strip_tags($text)));
	
	return $output;
	
}


PDFJobBookGetData();


$pdf->Output();