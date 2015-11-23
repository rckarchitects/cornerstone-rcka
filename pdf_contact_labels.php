<?php

include "inc_files/inc_checkcookie.php";

// Label size variables

$label_file = "library/labels.txt";
    if (file_exists($label_file)) {
	$label_file = file_get_contents($label_file);
	$label_array = explode("\n", $label_file);
	sort($label_array);
}

// Get the page details

include("inc_functions.php");

$label_line = explode("|", $label_array[$_POST[labeltype]]);


	$label_title = $label_line[0]; 	// This is the label title which is displayed on the dropdown list
	$label_a = $label_line[1];		// Horizontal pitch
	$label_b = $label_line[2];		// Vertical Pitch
	$label_c = $label_line[3];		// Width
	$label_d = $label_line[4];		// Height
	$label_e = $label_line[5];		// Left Margin
	$label_f = $label_line[6];		// Top Margin
	$label_g = $label_line[7];		// Columns
	$label_h = $label_line[8];		// Rows
	$label_i = $label_line[9];		// Page Size
	$label_j = $label_line[10];		// Font Size
	$label_k = $label_line[11];		// Line Height
	$label_l = $label_line[12];		// Margin
	
	$font = $_POST[font];
	
	// Set the cell margins
	$cell_margin_top = $label_l;
	$cell_margin_bottom = $label_l;
	$cell_margin_left = $label_l;
	$cell_margin_right = $label_l;

$count_across = 1;
$count_down = 1;


	$x = $label_e;
	$y = $label_f;
	
// Set up the page

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

// Determine if there's to be an uploaded template

	$label_file = "library/background.pdf";
	if (file_exists($label_file)) {
		$import_template = "1";
	} else { $import_template = NULL; }
	
	if ($import_template == "1") {
	$pdf->setSourceFile("library/background.pdf");
	$tplidx = $pdf->ImportPage(1);
	}

// Now add the first page
$pdf->addPage();

if ($import_template == "1") {
$pdf->useTemplate($tplidx, 0, 0, 210, 297);
}


$pdf->AddFont('century','','Century.php');
$pdf->AddFont('franklingothicbook','','franklingothicbook.php');
$pdf->AddFont('gillsans','','gillsans.php');

$pdf->SetMargins(0,0);
$pdf->SetAutoPageBreak("no");

// Create the sheet header

$pdf->SetFont("arial",'',$label_j);
	$label_print_date = $label_title.", created ".date("jS M y",time());
	$label_url = "http://labelstudio.redcitrus.com";
	$pdf->SetFontSize(8);
	$pdf->SetTextColor(190,190,190);
	$pdf->Cell(0,5,$label_print_date,0,1,L,0,$label_url);
$pdf->SetFont($font,'',$label_j);
$pdf->SetTextColor(0,0,0);
$pdf->SetFontSize($label_j);
	
if ($_GET[labeltype] != NULL) {
$labeltype = $_GET[labeltype];
} else {
$labeltype = 1;
}

// Begin the array

$count_cells = 0;
$count_rows = 1;
$count_columns = 1;
$total_cells = $label_g * $label_h;

$count = 1;
$page_count = 1;

// Begin the database collection

if ($_POST[marketing] != NULL) { $marketing = " WHERE ( contact_include = $_POST[marketing] ) "; } else { $marketing = NULL; }

$sql_contact = "SELECT * FROM contacts_contactlist LEFT JOIN contacts_companylist ON contact_company = company_id $marketing ORDER BY company_name, contact_namesecond, contact_namefirst";
$result_contact = mysql_query($sql_contact, $conn);

 while ($array_contact = mysql_fetch_array($result_contact)) {
 
	// establish the contact information from the array
	
	unset($address_go);
	
	$contact_namefirst = html_entity_decode ( $array_contact['contact_namefirst'] );
	$contact_namesecond = html_entity_decode ( $array_contact['contact_namesecond'] );
	$contact_company = html_entity_decode ( $array_contact['contact_company'] );
	$contact_department = html_entity_decode ( $array_contact['contact_department'] );
	$contact_address = html_entity_decode ( $array_contact['contact_address'] );
	$contact_city = html_entity_decode ( $array_contact['contact_city'] );
	$contact_county = $array_contact['contact_county'];
	$contact_postcode = $array_contact['contact_postcode'];
	$contact_include = $array_contact['contact_include'];
	$contact_email = $array_contact['contact_email'];
	
	$company_name = html_entity_decode ( $array_contact['company_name'] );
	$company_address = html_entity_decode ( $array_contact['company_address'] );
	$company_city = html_entity_decode ( $array_contact['company_city'] );
	$company_postcode = $array_contact['company_postcode'];
 
	if ($contact_namefirst != NULL) { $address_go = $contact_namefirst." ".$contact_namesecond; }
	if ($contact_department != NULL) { $address_go = $address_go."\n".$contact_department; }
	if ($company_name != NULL) { $address_go = $address_go."\n".$company_name; }
	if ($company_address != NULL) { $address_go = $address_go."\n".$company_address; } elseif ($contact_address != NULL) { $address_go = $address_go."\n".$contact_address; }
	if ($company_city != NULL) { $address_go = $address_go."\n".$company_city; } elseif ($contact_city != NULL) { $address_go = $address_go."\n".$contact_city; }
	if ($company_county != NULL) { $address_go = $address_go."\n".$company_county; } elseif ($contact_county != NULL) { $address_go = $address_go."\n".$contact_county; }
	if ($company_postcode != NULL) { $address_go = $address_go.", ".$company_postcode; } elseif ($contact_postcode != NULL) { $address_go = $address_go."\n".$contact_postcode; }

	
	if ($company_name != NULL) { $company_name_inc = $company_name . "\n"; } else { unset ( $company_name_inc ); }
	
	
	
	
	// Set current x and y

	$pdf->SetXY($x,$y);
	
	// Print Border if requested
	
		if ($_POST[borders] == "1") {
		$pdf->SetDrawColor(200,200,200);
		$pdf->Cell($label_c,$label_d,'',1);
		$pdf->SetDrawColor(0,0,0);
		}
	
	// Print Address
	
	$cell_width = $label_c - $cell_margin_left - $cell_margin_left;
	$cell_x = $x + $cell_margin_left;
	$cell_y = $y + $cell_margin_top;
	
	
	if ($_POST[marketing] == 0 AND $contact_include == 3) { $pdf->SetTextColor(0,0,200); }
	elseif ($_POST[marketing] == 0 AND $contact_include == 2 AND $contact_email != NULL ) { $address_go = $contact_namefirst . " " . $contact_namesecond . "\n" . $company_name_inc . $contact_email; }
	elseif ($_POST[marketing] == 2 AND $contact_include == 2 ) { $address_go = $contact_namefirst . " " . $contact_namesecond . "\n" . $company_name_inc . $contact_email; $pdf->SetTextColor(0,0,0); }
	elseif ($_POST[marketing] == 0 AND $contact_include == 0) { $pdf->SetTextColor(200,200,200); }
	else { $pdf->SetTextColor(0,0,0); }
	
	if ($_POST[marketing] == 2 AND $contact_email == NULL ) { $pdf->SetTextColor(255,0,0); }
	
	$pdf->SetXY($cell_x,$cell_y);
	$pdf->MultiCell($label_c,$label_k,$address_go,0,'L');

	$count_cells++;
	$count_columns++;
	
	if ($count_columns > $label_g) { $x = $label_e; $y = $y + $label_b; $count_columns = 1; } else { $x = $x + $label_a; }
	$count++;
	
	// Add new page if required
	
	if ($page_count == $total_cells) {
		$pdf->AddPage();
		
			if ($import_template == "1") {
			$pdf->setSourceFile("library/background.pdf");
			$tplidx = $pdf->ImportPage(1);
			}
			
		$pdf->SetFont("arial",'',$label_j);
		$label_print_date = $label_title.", created ".date("jS M y",time());
		$label_url = "http://labelstudio.redcitrus.com";
		$pdf->SetFontSize(8);
		$pdf->SetTextColor(190,190,190);
		$pdf->Cell(0,5,$label_print_date,0,1,L,0,$label_url);
		$pdf->SetFont($font,'',$label_j);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFontSize($label_j);
		$x = $label_e;
		$y = $label_f;
		$pdf->SetXY($x,$y);
		$page_count = 0;
	}
	
	$page_count++;
	
}

unlink($uploadfile);

$pdf->Output();

?>