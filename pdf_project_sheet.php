<?php

include_once "inc_files/inc_checkcookie.php";
include_once "inc_files/inc_action_functions_pdf.php";
include_once "secure/prefs.php";

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

$format_font = PDFFonts($settings_pdffont);


// Begin creating the page

	$project_counter = 1;
	$page_count = 1;
	
$current_date = TimeFormat(time());
$proj_id = CleanUp($_GET[proj_id]);

$sql_proj = "SELECT * FROM intranet_projects WHERE proj_id = $proj_id LIMIT 1";
$result_proj = mysql_query($sql_proj, $conn) or die(mysql_error());
$array_proj = mysql_fetch_array($result_proj);
$proj_num = $array_proj['proj_num'];
$proj_name = $array_proj['proj_name'];
$proj_desc = $array_proj['proj_desc'];
	
	PDFHeader ($proj_id,"Project Information Sheet");
	

	$pdf->SetXY(10,70);
	
	$pdf->SetLineWidth(0.5);

	$y_current = 80;
	$x_current = 10;

// Project Address

	$proj_address_1 = $array_proj['proj_address_1'];
	$proj_address_2 = $array_proj['proj_address_2'];
	$proj_address_3 = $array_proj['proj_address_3'];
	$proj_address_town = $array_proj['proj_address_town'];
	$proj_address_county = $array_proj['proj_address_county'];
	$proj_address_postcode = $array_proj['proj_address_postcode'];
	$proj_client_contact_id = $array_proj['proj_client_contact_id'];
	$proj_planning_ref = $array_proj['proj_planning_ref'];
	$proj_buildingcontrol_ref = $array_proj['proj_buildingcontrol_ref'];
	
	$proj_info = $array_proj['proj_info'];

	$proj_address = $proj_address.AddLine($proj_address_1);
	$proj_address = $proj_address.AddLine($proj_address_2);
	$proj_address = $proj_address.AddLine($proj_address_3);
	$proj_address = $proj_address.AddLine($proj_address_town);
	$proj_address = $proj_address.AddLine($proj_address_county);
	$proj_address = $proj_address.AddLine($proj_address_postcode);
	StyleHeading("Site Address",$proj_address);
	
// Project Description
		if ($proj_info) { StyleHeading("Key Information",$proj_info); }
		
		if ($proj_desc == NULL) { $proj_desc = "-- None --"; }
		StyleHeading("Project Description",$proj_desc);
	
	// Client details
	
	$sql_client = "SELECT * FROM contacts_contactlist, intranet_contacts_project LEFT JOIN contacts_companylist ON company_id = contact_proj_company WHERE contact_proj_project = $proj_id AND contact_id = '$proj_client_contact_id' LIMIT 1";
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
	
	$sql_contacts = "SELECT * FROM  contacts_disciplinelist, contacts_contactlist, intranet_contacts_project LEFT JOIN contacts_companylist ON contact_proj_company = company_id WHERE contact_proj_project = '$proj_id' AND contact_id = contact_proj_contact AND discipline_id = contact_proj_role ORDER BY discipline_order, discipline_ref,  company_name, contact_namesecond";
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
