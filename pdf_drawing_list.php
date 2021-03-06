<?php

include_once "inc_files/inc_checkcookie.php";
include_once "inc_files/inc_action_functions_pdf.php";
include_once "secure/prefs.php";

function PDFTruncateText($width,$string) {
		
		global $pdf;
				
		if ($pdf->GetStringWidth($string) > $width) {
				
				while ($pdf->GetStringWidth($string) > ($width - 2)) {
					
					$string = substr($string, 0, -1);
					
				}
				
				$string = $string . "...";
				
		}
		
		return $string;
		
}

if ($_GET[proj_id] == NULL) { header ("Location: index2.php"); } else { $proj_id = intval($_GET[proj_id]); }

if ($user_usertype_current < 2) { header ("Location: index2.php"); }

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->useTemplate($tplidx, 0, 0, 210, 297);

$format_font = PDFFonts($settings_pdffont);


$format_bg_r = "0";
$format_bg_g = "0";
$format_bg_b = "0";

$format_ln_r = "0";
$format_ln_g = "0";
$format_ln_b = "0";



$current_date = TimeFormat(time());
$proj_id = CleanUp($_GET[proj_id]);

// Begin creating the page

//Page Title

PDFHeader ($proj_id,"Drawing List");
	
	
	
// And now the list of drawings issued

unset($current_drawing);

//$sql_drawings = "SELECT * FROM intranet_drawings_issued, intranet_drawings LEFT JOIN intranet_drawings_revision ON revision_drawing = drawing_id WHERE issue_set = $set_id AND issue_drawing = drawing_id ORDER BY drawing_number";

$sql_drawings = "SELECT * FROM intranet_drawings, intranet_drawings_scale, intranet_drawings_paper WHERE drawing_project = $proj_id AND drawing_scale = scale_id AND drawing_paper = paper_id order by drawing_number";

$result_drawings = mysql_query($sql_drawings, $conn) or die(mysql_error());

$y = $pdf->GetY() + 10;
$pdf->SetY($y);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetLineWidth(0.3);
$pdf->SetFont("Helvetica",'B',6);
$pdf->Cell(40,5,"Drawing Number",B,0,L,0);
$pdf->Cell(10,5,"Size",B,0,L,0);
$pdf->Cell(20,5,"Scale",B,0,L,0);
$pdf->Cell(60,5,"Drawing Title",B,0,L,0);
$pdf->Cell(10,5,"Status",B,0,L,0);
$pdf->Cell(15,5,"Target Date",B,0,L,0);
$pdf->Cell(15,5,"Current Rev.",B,0,R,0);
$pdf->Cell(20,5,"Date",B,1,L,0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont($format_font,'',7);
$pdf->SetFillColor(240,240,240);

$fill = 0;
unset($current_type);

while ($array_drawings = mysql_fetch_array($result_drawings)) {

	$drawing_number = $array_drawings['drawing_number'];
	$drawing_id = $array_drawings['drawing_id'];
	$drawing_title = str_replace("\n",", ",$array_drawings['drawing_title']);
	$drawing_title = preg_replace('/[^(\x20-\x7F)]*/','', $drawing_title);
	$drawing_date = $array_drawings['drawing_date'];
	$drawing_targetdate = $array_drawings['drawing_targetdate'];
	$drawing_comment = $array_drawings['drawing_comment'];
	$scale_desc = $array_drawings['scale_desc'];
	$paper_size = $array_drawings['paper_size'];
	$drawing_status = $array_drawings['drawing_status'];
	
	$this_type_array = explode("-",$drawing_number);
	$this_type = $this_type_array[2];
	
	if ($current_type != $this_type && $current_type != NULL) { $pdf->SetLineWidth(0.1); $border = "T"; } else { $border = "0"; }
	
	
		$sql_rev = "SELECT * FROM intranet_drawings_revision WHERE revision_drawing = '$drawing_id' ORDER BY revision_letter DESC LIMIT 1";
		$result_rev = mysql_query($sql_rev, $conn) or die(mysql_error());
		$array_rev = mysql_fetch_array($result_rev);
		if ($array_rev['revision_letter'] != NULL) { $revision_letter = strtoupper($array_rev['revision_letter']); } else { $revision_letter = " - "; }
		if ($array_rev['revision_date'] != NULL) { $revision_date = TimeFormat($array_rev['revision_date']); }
		elseif ($drawing_date > 0) { $revision_date = TimeFormat($drawing_date); }
		else { $revision_date = " - "; }

		if ($pdf->GetStringWidth($drawing_title) > 100) { $drawing_title = substr($drawing_title,0,80) . "..."; }
		
		$sql_issued = "SELECT issue_id FROM intranet_drawings_issued WHERE issue_drawing = '$drawing_id' LIMIT 1";
		$result_issued = mysql_query($sql_issued, $conn) or die(mysql_error());
		if (mysql_num_rows($result_issued) > 0) { $pdf->SetTextColor(0, 0, 0); } else { $pdf->SetTextColor(150, 150, 150); $not_issued = "Drawings shown in light grey have not yet been issued."; $drawing_number = $drawing_number . "*"; }
		
		
		$link = $pref_location . "/public_drawing_issue.php?drawing_id=" . $drawing_id . "&hash=" . md5($drawing_number);
	

	if ($current_drawing != $drawing_id) {
		
		$drawing_title = PDFTruncateText(60,$drawing_title);

		$pdf->Cell(40,4.5,$drawing_number,$border,0,L,$fill,$link);
		$pdf->Cell(10,4.5,$paper_size,$border,0,L,$fill);
		$pdf->Cell(20,4.5,$scale_desc,$border,0,L,$fill);
		if ($drawing_targetdate != "0000-00-00") {
			$pdf->Cell(60,4.5,$drawing_title,$border,0,L,$fill);
			$pdf->Cell(15,4.5,$drawing_status,$border,0,L,$fill);
			$pdf->Cell(15,4.5,$drawing_targetdate,$border,0,L,$fill);
		} else {
			$pdf->Cell(60,4.5,$drawing_title,$border,0,L,$fill);
			$pdf->Cell(30,4.5,$drawing_status,$border,0,L,$fill);
		}
		$pdf->Cell(10,4.5,$revision_letter,$border,0,R,$fill);
		$pdf->Cell(20,4.5,$revision_date,$border,1,L,$fill);
		
		$y = $pdf->GetY();
		
		// Cross out this drawing if now obsolete
		if ($revision_letter == "*") { $y = $y - 3.25; $pdf->SetY($y); $pdf->SetLineWidth(0.1); $pdf->SetDrawColor(0,0,0); $pdf->Cell(0,1,'',B,1); $y = $y + 3.25; $pdf->SetY($y); $pdf->SetDrawColor(200, 200, 200); $obsolete_message = "* Drawing obsolete";  }

		if ($drawing_comment != NULL && $revision_letter != "*") {
			$pdf->Cell(25,4.5,'',$border,0,L,$fill,$link);
			$pdf->Cell(0,4.5,$drawing_comment,$border,1,L,$fill);
		}
		
		if ($fill == 1) { $fill = 0; } else { $fill = 1; }

	}
	

	
	$current_drawing = $drawing_id;
	
	$current_type = $this_type;

}

$pdf->SetLineWidth(0.3);
$pdf->Cell(0,5,'',T,1,L,0);

if ($not_issued != NULL) { $pdf->SetX(10); $pdf->Cell(0,5,$not_issued,0,1,L); }

if ($obsolete_message != NULL) { $pdf->MultiCell(0,5,$obsolete_message,0,1,L); }


// If development code = "yes" (devcode = "yes") in the $_GET request, include some additional data

if ($_GET[devcode] == "yes") { $pdf->MultiCell(0,4,$sql_drawings); } 

// and send to output

$file_date = time();

$file_name = $proj_num."_2.05_".Date("Y",$file_date)."-".Date("m",$file_date)."-".Date("d",$file_date)."_Drawing_Schedule.pdf";

$pdf->Output($file_name,I);

?>
