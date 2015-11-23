<?php

$format_bg_r = "150";
$format_bg_g = "150";
$format_bg_b = "150";

if ($_GET[proj_id] != NULL) { $proj_id = $_GET[proj_id]; } else { header ("Location: ../index2.php"); }

include "inc_files/inc_checkcookie_logincheck.php";

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
			$pdf->SetFont('Helvetica','b',13);
			$pdf->SetTextColor(150, 150, 150);
			$pdf->SetX($x_current);
			$pdf->SetDrawColor(220,220,220);
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
	$pdf->SetFont('Helvetica','b',24);

	$pdf->SetTextColor($format_bg_r, $format_bg_g, $format_bg_b);
	$pdf->Cell(0,10,"Project Summary Sheet");

	$pdf->SetTextColor(0, 0, 0);
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

	$pdf->SetFillColor(220, 220, 220);
	$pdf->MultiCell(0,8,$print_title,0, L, 1);

// Printed by, and on...

	$pdf->SetFont($format_font,'',10);

	$sql = "SELECT user_name_first, user_name_second FROM intranet_user_details WHERE user_id = $_COOKIE[user]";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);

	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];

	$printed_on = "Generated ".date("r")." by ".$user_name_first." ".$user_name_second;

	$pdf->Cell(0,6,$printed_on,0, 1, L, 1);

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
	
// Project Description
		if ($proj_desc == NULL) { $proj_desc = "-- None --"; }
		StyleHeading("Project Description",$proj_desc);
	
	// Client details
	
	$sql_client = "SELECT * FROM contacts_contactlist LEFT JOIN contacts_companylist ON company_id = contact_company WHERE contact_id = '$proj_client_contact_id' LIMIT 1";
	$result_client = mysql_query($sql_client, $conn) or die(mysql_error());
	$array_client = mysql_fetch_array($result_client);

	$contact_namefirst = $array_client['contact_namefirst'];
	$contact_namesecond = $array_client['contact_namesecond'];
	$contact_title = $array_client['contact_title'];
	$contact_address = $array_client['contact_address'];
	$contact_city = $array_client['contact_city'];
	$contact_county = $array_client['contact_county'];
	$contact_postcode = $array_client['contact_postcode'];
	$contact_company = $array_client['contact_company'];
	$contact_mobile = $array_client['contact_mobile'];
	
	$company_name = $array_client['company_name'];
	$company_address = $array_client['company_address'];
	$company_city = $array_client['company_city'];
	$company_county = $array_client['company_county'];
	$company_postcode = $array_client['company_postcode'];
	
	$contact_name = $contact_namefirst." ".$contact_namesecond;
	$contact = $contact.AddLine($contact_name);
	$contact = $contact.AddLine($contact_title);
	
	if ($contact_company > 0) {
		$contact = $contact.AddLine($company_name);
		$contact = $contact.AddLine($company_address);
		$contact = $contact.AddLine($company_city);
		$contact = $contact.AddLine($company_county);
		$contact = $contact.AddLine($company_postcode);
	} else {
		$contact = $contact.AddLine($contact_address);
		$contact = $contact.AddLine($contact_city);
		$contact = $contact.AddLine($contact_county);
		$contact = $contact.AddLine($contact_postcode);
	}
	
	$contact = html_entity_decode($contact);
	//StyleHeading("Invoice Address",$contact);	
	
	// Add project particulars
	
	if ($proj_planning_ref != NULL OR $proj_buildingcontrol_ref != NULL) { SplitBag("Project Particulars"); }
	
	
	if ($proj_planning_ref != NULL) {
	StyleHeading("Planning Reference",$proj_planning_ref);
	}
	
	if ($proj_buildingcontrol_ref != NULL) {
	StyleHeading("Building Control Reference",$proj_buildingcontrol_ref);
	}
	
	
	// Add contact heading
	//SplitBag("Project Contacts");
	
	// Begin the contact array
	
	$sql_contacts = "SELECT * FROM  contacts_disciplinelist, contacts_contactlist, intranet_contacts_project LEFT JOIN contacts_companylist ON contact_proj_company = company_id WHERE contact_proj_project = '$proj_id' AND contact_id = contact_proj_contact AND discipline_id = contact_discipline ORDER BY discipline_order, discipline_ref,  company_name, contact_namesecond";
	$result_contacts = mysql_query($sql_contacts, $conn) or die(mysql_error());
	
		$count = 0;
	
		while ($array_contacts = mysql_fetch_array($result_contacts)) {

			$contact_namefirst = $array_contacts['contact_namefirst'];
			$contact_namesecond = $array_contacts['contact_namesecond'];
			$contact_title = $array_contacts['contact_title'];
			$contact_mobile = $array_contacts['contact_mobile'];
			$contact_phone = $array_contacts['contact_phone'];
			$contact_email = $array_contacts['contact_email'];
			
			$contact_company = $array_contacts['contact_company'];
			$company_name = $array_contacts['company_name'];
			$company_address = $array_contacts['company_address'];
			$company_city = $array_contacts['company_city'];
			$company_county = $array_contacts['company_county'];
			$company_postcode = $array_contacts['company_postcode'];
			$company_phone = $array_contacts['company_phone'];
			$company_web = $array_contacts['company_web'];
			
			$discipline_ref = $array_contacts['discipline_ref'];
			$discipline_name = $array_contacts['discipline_name'];
			
			if ($discipline_ref != NULL) { $discipline_name = $discipline_ref." ".$discipline_name; }
		
			$contact_proj_note = $array_contacts['contact_proj_note'];
			
			$contact = $contact_namefirst." ".$contact_namesecond."\n";
			$contact = $contact.AddLine($contact_title);
		
			if ($contact_company > 0) {
			$contact = $contact.AddLine($company_name);
			$contact = $contact.AddLine($company_address);
			$contact = $contact.AddLine($company_city);
			$contact = $contact.AddLine($company_county);
			$contact = $contact.AddLine($company_postcode);
			$company_phone_print = "T. ".$company_phone;
			$contact = $contact.AddLine($company_phone_print);
			} else {
			$contact = $contact.AddLine($contact_address);
			$contact = $contact.AddLine($contact_city);
			$contact = $contact.AddLine($contact_county);
			$contact = $contact.AddLine($contact_postcode);
			$contact_phone_print = "T. ".$contact_phone;
			$contact = $contact.AddLine($contact_phone_print);
			}
			
			$contact_mobile_print = "M. ".$contact_mobile;
			$contact = $contact.AddLine($contact_mobile_print);
			
			$contact_email_print = "E. ".$contact_email;
			$contact = $contact.AddLine($contact_email_print);
			
			$contact_web_print = "W. ".$company_web;
			$contact = $contact.AddLine($contact_web_print);
			
			StyleHeading($discipline_name,$contact,$contact_proj_note);
			
			$count++;
			
				
		}		


// and send to output

$pdf->Output();
?>
