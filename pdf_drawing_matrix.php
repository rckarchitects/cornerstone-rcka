<?php

include "inc_files/inc_checkcookie.php";

if ($_GET[proj_id] == NULL) { header ("Location: index2.php"); }

if ($user_usertype_current < 2) { header ("Location: index2.php"); }

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->useTemplate($tplidx, 0, 0, 210, 297);

$format_font = "century";
$format_font_2 = "Century.php";

$pdf->AddFont($format_font,'',$format_font_2);


$format_bg_r = "220";
$format_bg_g = "220";
$format_bg_b = "220";

$format_ln_r = "220";
$format_ln_g = "220";
$format_ln_b = "220";



$current_date = TimeFormat(time());
$proj_id = CleanUp($_GET[proj_id]);

// Begin creating the page

//Page Title

$sql_proj = "SELECT * FROM intranet_projects WHERE proj_id = $proj_id LIMIT 1";
$result_proj = mysql_query($sql_proj, $conn) or die(mysql_error());
$array_proj = mysql_fetch_array($result_proj);
$proj_num = $array_proj['proj_num'];
$proj_name = $array_proj['proj_name'];

$sheet_subtitle = $proj_num." ".$proj_name.", ". $current_date;


function SheetTitle() {

	GLOBAL $pdf;
	GLOBAL $sheet_subtitle;
	GLOBAL $format_font;
	
	$page_number_current = $pdf->PageNo();
	$page_number_total = $pdf->AliasNbPages();
	
	$sheet_subtitle_print = $sheet_subtitle . ", Sheet " . $page_number_current; // . " of " . $page_number_total ;
	
	$sheet_title = "Drawing Issue Matrix";
	$pdf->SetFont($format_font,'',16);
	$pdf->SetTextColor(150, 150, 150);
	$pdf->SetDrawColor(150, 150, 150);
	$pdf->Cell(0,8,$sheet_title,0,1);
	$pdf->SetFont($format_font,'',12);
	$pdf->Cell(0,6,$sheet_subtitle_print,0,1);
	
	$pdf->SetLineWidth(0.5);
	
}

$pdf->SetXY(10,45);
	

	
// And now the list of drawings issued

unset($current_drawing);

$y = $pdf->GetY();
$pdf->SetY($y);

function HeadingLine() {

		GLOBAL $pdf;
		GLOBAL $proj_id;
		GLOBAL $conn;
		GLOBAL $format_font;
		
		$array_issued = array();

						$pdf->SetTextColor(200, 200, 200);
						$pdf->SetLineWidth(0.15);
						$pdf->SetFont("Helvetica",'B',6);
						$pdf->Cell(20,8,"Drawing",B,0,L,0);
						$pdf->Cell(60,8,"Drawing Title",BR,0,L,0);

						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont($format_font,'',5);

						$current_y = $pdf->GetY();

						$count = 1;

						$sql_issues = "SELECT set_date, set_id FROM intranet_drawings_issued_set WHERE set_project = $proj_id ORDER BY set_date DESC LIMIT 22";
						$result_issues = mysql_query($sql_issues, $conn) or die(mysql_error());
						while ($array_issues = mysql_fetch_array($result_issues)) {
						$set_id = $array_issues['set_id'];
						$set_date = $array_issues['set_date'];
						$day = date("j",$set_date);
						$month = date("M",$set_date);
						$year = date("Y",$set_date);
						$pdf->Cell(5,3,$day,TR,2,C,0);
						$pdf->Cell(5,2,$month,R,2,C,0);
						$pdf->Cell(5,3,$year,RB,0,C,0);
						$current_x = $pdf->GetX();
						$pdf->SetXY($current_x,$current_y);
						$count++;
						array_push ($array_issued, $set_id);
						}

						if ($count < 22) {
						$pdf->Cell(0,3,'',1,0,C,0);
						}


						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont($format_font,'',5);
						$pdf->SetFillColor(240,240,240);

						$pdf->SetX(0);
						$current_y = $pdf->GetY() + 8;
						$pdf->SetY($current_y);
						
			return $array_issued;

}



function Recipients($x,$y) {

				GLOBAL $pdf;
				GLOBAL $proj_id;
				GLOBAL $conn;
				GLOBAL $format_font;
				GLOBAL $issues_array;
				
				GLOBAL $x;
				GLOBAL $y;

				// How many recipients?

				$sql_recipients = "
				SELECT contact_id, contact_namefirst, contact_namesecond, company_name
FROM contacts_contactlist,  intranet_drawings_issued
INNER JOIN contacts_companylist
ON company_id = issue_company
WHERE issue_project = $proj_id
AND issue_contact = contact_id
GROUP BY contact_id
ORDER BY contact_namesecond";
				$result_recipients = mysql_query($sql_recipients, $conn) or die(mysql_error());
				$recipients = mysql_num_rows($result_recipients);
				
				$temp_y = 265;
				$temp_x = 10;
				
				$height = ( $temp_y - ($recipients * 5)) + 2;
				
				$pdf->SetXY($temp_x,$height);
				$pdf->SetTextColor(200, 200, 200);
				$pdf->SetFont("Helvetica",'B',6);
						$pdf->Cell(0,5,"Recipients",B,1);
						

						$pdf->SetTextColor(0, 0, 0);
						
				
				while ($array_recipients = mysql_fetch_array($result_recipients)) {
				$contact_id = $array_recipients['contact_id'];
				$contact_namefirst = $array_recipients['contact_namefirst'];
				$contact_namesecond = $array_recipients['contact_namesecond'];
				$company_name = $array_recipients['company_name'];
				$contact_name = $contact_namefirst . " " . $contact_namesecond;
				
				$company_name = str_replace("&amp;","&",$company_name);
				
				$pdf->SetFont($format_font,'',6);
				$pdf->Cell(35,5,$contact_name,LT,0);
				$pdf->Cell(45,5,$company_name,LT,0);
				
				$count_issues = count($issues_array);
				$counter_issues = 0;
				
							while ( $counter_issues < $count_issues) {
								
								$sql_check_issue = "SELECT issue_id FROM intranet_drawings_issued WHERE issue_contact = $contact_id AND issue_set = '$issues_array[$counter_issues]' LIMIT 1";
								$result_check_issue = mysql_query($sql_check_issue, $conn) or die(mysql_error());
								$check_issue = mysql_num_rows($result_check_issue);
								
								$pdf->SetFont('ZapfDingbats','',5); $revision_letter = "l";								
								if ($check_issue > 0) { $tick = "l"; } else { unset($tick); }
								$pdf->Cell(5,5,$tick,LB,0,C);
								
								$counter_issues++;
							
							}
				
					$pdf->Cell(5,5,'',L,1);
				
				}
				$pdf->Cell(0,1,'',T,1);
				
				$height = $height - 5;
				
				return $height;
}

SheetTitle();	
$issues_array = HeadingLine();
$max_height = Recipients( 10, $pdf->GetY() );
$pdf->SetY(67);


$fill = 0;
unset($current_type);

$sql_drawings = "SELECT * FROM intranet_drawings WHERE drawing_project = $proj_id ORDER BY drawing_number";
$result_drawings = mysql_query($sql_drawings, $conn) or die(mysql_error());


// Array through each of the drawings one by one

while ($array_drawings = mysql_fetch_array($result_drawings)) {

	$drawing_number = $array_drawings['drawing_number'];
	$drawing_id = $array_drawings['drawing_id'];
	$drawing_title = str_replace("\n",", ",$array_drawings['drawing_title']);
	$drawing_title = preg_replace('/[^(\x20-\x7F)]*/','', $drawing_title);
	$drawing_date = $array_drawings['drawing_date'];
	
	$this_type_array = explode("-",$drawing_number);
	$this_type = $this_type_array[2];
	
	if ($pdf->GetStringWidth($drawing_title) > 55) { $drawing_title = substr($drawing_title,0,65) . "..."; }
		
		$sql_issued = "SELECT issue_id FROM intranet_drawings_issued WHERE issue_drawing = '$drawing_id' LIMIT 1";
		$result_issued = mysql_query($sql_issued, $conn) or die(mysql_error());
		if (mysql_num_rows($result_issued) > 0) { $pdf->SetTextColor(0, 0, 0); } else { $pdf->SetTextColor(150, 150, 150); $not_issued = "*Drawings shown in light grey have not yet been issued."; $drawing_number = $drawing_number . "*"; }
		
		
		$link = "http://intranet.rcka.co.uk/public_drawing_issue.php?drawing_id=" . $drawing_id . "&hash=" . md5($drawing_number);
	

	if ($current_drawing != $drawing_id) {

		$pdf->SetFont($format_font,'',6);
		$pdf->Cell(20,4.5,$drawing_number,TBL,0,L,$fill,$link);
		$pdf->SetFont($format_font,'',5);
		$pdf->Cell(60,4.5,$drawing_title,TB,0,L,$fill);
		
		
		// Drawing Issues by date
		
					
					$counter = 0;
					$count_issues = count($issues_array);
					while ($counter < $count_issues) {
					
							$sql_issues = "SELECT revision_letter FROM intranet_drawings_issued LEFT JOIN intranet_drawings_revision ON issue_revision = revision_id WHERE issue_set = $issues_array[$counter] AND issue_drawing = $drawing_id LIMIT 1";
							$result_issues = mysql_query($sql_issues, $conn) or die(mysql_error());	
							$array_set = mysql_fetch_array($result_issues);							
							$revision_letter = strtoupper($array_set['revision_letter']);
							
							if ($revision_letter == NULL AND mysql_num_rows($result_issues) > 0 ) { $pdf->SetFont('ZapfDingbats','',5);$revision_letter = "l"; } else { $pdf->SetFont('Helvetica','',6); }
							
							//if ($_GET[set_id] == $issues_array[$counter]) { $fill = 1; $pdf->SetFillColor(255, 150, 150); } else { $fill = 0; $pdf->SetFillColor(150, 150, 150); }
							
							
							if ($count_issues - $counter == 1 ) {
							$pdf->Cell(5,4.5,$revision_letter,1,1,C,$fill);
							} else {
							$pdf->Cell(5,4.5,$revision_letter,1,0,C,$fill);
							}
							
							$pdf->SetFont($format_font,'',5);
							
							$counter++;
					
					}
					
					
				
					
		
		$y = $pdf->GetY();
		
		// Cross out this drawing if now obsolete
		if ($revision_letter == "*") { $y = $y - 3.25; $pdf->SetY($y); $pdf->SetLineWidth(0.1); $pdf->SetDrawColor(0,0,0); $pdf->Cell(0,1,'',B,1); $y = $y + 3.25; $pdf->SetY($y); $pdf->SetDrawColor(200, 200, 200); $obsolete_message = "* Drawing obsolete";  }

		if ($fill == 1) { $fill = 0; } else { $fill = 1; }

	}
	
	$current_drawing = $drawing_id;
	
	$current_type = $this_type;

			if ($pdf->GetY() > $max_height) {
			

				
				$pdf->addPage();
				SheetTitle();
				HeadingLine();
				Recipients( 10, $pdf->GetY() );
				
				$x = 10;
				$y = 32;
				$pdf->SetXY($x,$y);
				
			
			}
	
}

$pdf->SetLineWidth(0.3);
$pdf->Cell(0,5,'',T,1,L,0);

if ($not_issued != NULL) { $pdf->Cell(0,5,$not_issued,0,1,R); }

if ($obsolete_message != NULL) { $pdf->MultiCell(0,5,$obsolete_message,0,1,R); }


// If development code = "yes" (devcode = "yes") in the $_GET request, include some additional data

if ($_GET[devcode] == "yes") { $pdf->MultiCell(0,4,$sql_drawings); } 

// and send to output

$file_date = time();

$file_name = $proj_num."_2.05_".Date("Y",$file_date)."-".Date("m",$file_date)."-".Date("d",$file_date)."_Drawing_Schedule.pdf";

$pdf->Output($file_name,I);

?>
