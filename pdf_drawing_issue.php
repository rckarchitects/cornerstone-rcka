<?php

include_once "inc_files/inc_checkcookie.php";
include_once "inc_files/inc_action_functions_pdf.php";
include_once "secure/prefs.php";

if ($_GET[proj_id] == NULL) { header ("Location: index2.php"); }

elseif ($_GET[issue_set] == NULL) { header ("Location: index2.php?proj_id=$_GET[proj_id]"); } else { $issue_set = $_GET[issue_set]; $proj_id = $_GET[proj_id]; }


//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->useTemplate($tplidx, 0, 0, 210, 297);

$format_font = PDFFonts($settings_pdffont);


$format_bg_r = "220";
$format_bg_g = "220";
$format_bg_b = "220";

$format_ln_r = "0";
$format_ln_g = "0";
$format_ln_b = "0";

if ($user_usertype_current < 2 OR $_GET[issue_set] == NULL) { header ("Location: index2.php"); }

$issue_date = CleanUp($_GET[issue_date]);
$proj_id = CleanUp($_GET[proj_id]);

// Begin creating the page

//Page Title

$sql_set = "SELECT * FROM intranet_projects, intranet_user_details, intranet_drawings_issued_set WHERE set_id = $issue_set AND set_project = proj_id AND user_id = set_user LIMIT 1";
$result_set = mysql_query($sql_set, $conn) or die(mysql_error());
$array_set = mysql_fetch_array($result_set);
$proj_num = $array_set['proj_num'];
$proj_name = $array_set['proj_name'];
$set_id = $array_set['set_id'];
$set_date = TimeFormat($array_set['set_date']);
$file_date = $array_set['set_date'];
$set_reason = $array_set['set_reason'];
$set_method = $array_set['set_method'];
$set_format = $array_set['set_format'];
$set_comment = $array_set['set_comment'];
$set_checked = $array_set['set_checked'];


if ($set_checked > 0) {

$sql_set_checked = "SELECT user_initials FROM intranet_user_details WHERE user_id = $set_checked LIMIT 1";
$result_set_checked = mysql_query($sql_set_checked, $conn) or die(mysql_error());
$array_set_checked = mysql_fetch_array($result_set_checked);
$user_checked_initials = $array_set_checked['user_initials'];

} else { $user_checked_initials = "None"; }

$user_name = $array_set['user_initials'];


	
	PDFHeader ($proj_id,"Drawing Issue Sheet");
	
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFont("Helvetica",'B',8);	
	$pdf->Cell(40,5,"Purpose of Issue",0,0,L,0);
	$pdf->Cell(30,5,"Method of Issue",0,0,L,0);
	$pdf->Cell(30,5,"Format",0,0,L,0);
	$pdf->Cell(30,5,"Issued By",0,0,L,0);
	$pdf->Cell(30,5,"Checked By",0,0,L,0);
	$pdf->Cell(30,5,"ID",0,1,L,0);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetLineWidth(0.4);
	$pdf->SetFont($format_font,'',10);	
	$pdf->Cell(40,7.5,$set_reason,T,0,L,0);
	$pdf->Cell(30,7.5,$set_method,T,0,L,0);
	$pdf->Cell(30,7.5,$set_format,T,0,L,0);
	$pdf->Cell(30,7.5,$user_name,T,0,L,0);
	if ($user_name_checked == "None") { $pdf->SetTextColor(255,0,0); }
	$pdf->Cell(30,7.5,$user_checked_initials,T,0,L,0);
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(30,7.5,$set_id,T,1,L,0);
	$pdf->Cell(0,0.5,'',T,1,L,0);
	$pdf->SetLineWidth(0.4);
	
	
	
// And now the list of drawings issued

unset($current_drawing);

//$sql_drawings = "SELECT * FROM intranet_drawings_issued, intranet_drawings LEFT JOIN intranet_drawings_revision ON revision_drawing = drawing_id WHERE issue_set = $set_id AND issue_drawing = drawing_id ORDER BY drawing_number";

$sql_drawings = "SELECT * FROM intranet_drawings, intranet_drawings_issued LEFT JOIN intranet_drawings_revision ON revision_id = issue_revision WHERE drawing_id = issue_drawing AND issue_set = $set_id ORDER BY drawing_number";

$result_drawings = mysql_query($sql_drawings, $conn) or die(mysql_error());

$y = $pdf->GetY() + 5;
$pdf->SetY($y);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Helvetica','B',12);
$pdf->Cell(0,7.5,"Drawings Issued",0,1,L,0);
$pdf->SetFont("Helvetica",'B',8);
$pdf->Cell(50,5,"Drawing Number",0,0,L,0);
$pdf->Cell(15,5,"Status",0,0,L,0);
$pdf->Cell(10,5,"Rev.",0,0,L,0);
$pdf->Cell(25,5,"Date",0,0,L,0);
$pdf->Cell(0,5,"Drawing Title",0,1,L,0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont($format_font,'',9);
$pdf->SetLineWidth(0.3);
$pdf->SetFillColor(240,240,240);

$fill = 0;

while ($array_drawings = mysql_fetch_array($result_drawings)) {



	$drawing_number = $array_drawings['drawing_number'];
	$drawing_id = $array_drawings['drawing_id'];
	$drawing_title = str_replace("\n",", ",$array_drawings['drawing_title']);
	$drawing_title = $output = preg_replace('/[^(\x20-\x7F)]*/','', $drawing_title);
	$drawing_date = TimeFormat($array_drawings['drawing_date']);
	$revision_letter = strtoupper($array_drawings['revision_letter']);
	$revision_desc = $array_drawings['revision_desc'];
	$drawing_status = $array_drawings['drawing_status'];
	if (!$drawing_status) { $drawing_status = "-"; }
	if ($revision_letter == NULL) { $revision_letter = "-"; }
	if ($array_drawings['revision_date'] != 0) { $revision_date = TimeFormat($array_drawings['revision_date']); } else { $revision_date = "-"; }
	

	if ($current_drawing != $drawing_id) { 
		
		if ($revision_letter == "*") { $pdf->SetTextColor(150, 150, 150); } else { $pdf->SetTextColor(0, 0, 0); }
		
		$line_height = $pdf->GetStringWidth($drawing_title);
		$lines = 5 * (ceil($line_height / 105));
		
		$pdf->Cell(0,$lines,'',T,0,L,$fill);
		
		$pdf->SetX(10);
		
		$pdf->Cell(50,5,$drawing_number,0,0,L,0);
		$pdf->Cell(25,5,$drawing_status,0,0,L,0);
		$pdf->Cell(25,5,$drawing_date,0,0,L,0);
		$pdf->MultiCell(0,5,$drawing_title,0,L,0);
		
		
		if ($revision_letter == "*") {	
				$back_y = $pdf->GetY() - 2.5;
				$back_x = $pdf->GetX();
				$new_x = $back_x + 190;
				$pdf->SetDrawColor(150, 150, 150);
				$pdf->SetLineWidth(0.1);
				$pdf->Line($back_x,$back_y,$new_x,$back_y);
				$pdf->SetDrawColor(0, 0, 0);
				$new_y = $back_y + 2.5;
				$pdf->SetXY($back_x,$new_y);
				$pdf->SetLineWidth(0.3);
		}
		
		if ($revision_letter != "-") {  $pdf->SetFont($format_font,'',8); $pdf->Cell(65,4,'',0,0,L,$fill); $pdf->Cell(10,4,$revision_letter,0,0,L,$fill); $pdf->Cell(25,4,$revision_date,0,0,L,$fill); $pdf->MultiCell(0,4,$revision_desc,0,L,$fill); $pdf->SetFont($format_font,'',9); }
		
		//if ($fill == 0) { $fill = 1; } else { $fill = 0; }


	}
	
	
	
	$current_drawing = $drawing_id;
	
	$pdf->SetLineWidth(0.1);

}

$pdf->Cell(0,1,'',T,1,L,0);

// And now the list of recipients

unset($current_contact);

$sql_contacts = "SELECT * FROM contacts_disciplinelist, contacts_contactlist, intranet_contacts_project, intranet_drawings_issued
LEFT JOIN contacts_companylist
ON company_id = issue_company
WHERE issue_set = $set_id
AND issue_contact = contact_id
AND contact_proj_role = discipline_id
AND contact_proj_contact = contacts_contactlist.contact_id
AND contact_proj_project = $proj_id
ORDER BY discipline_order, company_name, contact_namesecond
";

$result_contacts = mysql_query($sql_contacts, $conn) or die(mysql_error());

$y = $pdf->GetY() + 5;
$pdf->SetY($y);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Helvetica','B',12);
$pdf->Cell(0,7.5,"Issued To",0,1,L,0);
$pdf->SetFont("Helvetica",'B',8);
$pdf->Cell(60,5,"Name",0,0,L,0);
$pdf->Cell(70,5,"Company",0,0,L,0);
$pdf->Cell(0,5,"Role",0,1,L,0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont($format_font,'',8);
$pdf->SetLineWidth(0.3);

while ($array_contacts = mysql_fetch_array($result_contacts)) {


	$contact_name = $array_contacts['contact_namefirst'] . " " . $array_contacts['contact_namesecond'];
	$contact_name = html_entity_decode($contact_name);
	$contact_id = $array_contacts['contact_id'];	
	$company_name = html_entity_decode($array_contacts['company_name']);
	$discipline_name = $array_contacts['discipline_name'];

	if ($current_contact != $contact_id) {

		$pdf->Cell(60,5,$contact_name,T,0,L,0);
		$pdf->Cell(70,5,$company_name,T,0,L,0);
		$pdf->Cell(0,5,$discipline_name,T,1,L,0);
		
		$pdf->SetLineWidth(0.1);

	}
	
	$current_contact = $contact_id;

}

$pdf->Cell(0,1,'',T,1,L,0);



if ($set_comment) {
$pdf->Cell(0,10,'',0,1,L,0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Helvetica','B',12);
$pdf->Cell(0,7.5,"Notes",0,1,L,0);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont($format_font,'',10);
$pdf->Cell(0,3,'',0,1,L,0);
$pdf->MultiCell(0,3,$set_comment);
$pdf->Cell(0,5,'',0,1,L,0);
}



if ($set_format == "DWG" OR $set_format == "DGN") {
$disclaimer = "\nAt your request we are providing you with CAD drawings.\nBecause the CAD information stored in electronic form can be modified by other parties intentionally or otherwise, without notice or indication of said modifications, " . $pref_practice . " reserves the right to remove all indices of its ownership and/or involvement in material from each electronic medium not held in its possession. This material shall not be used by you or transferred to any other party for use in any other projects, additions to the current project or for any other purpose for which the material was not strictly intended by " . $pref_practice . " without our express written permission.\nAny unauthorised modification or reuse of the material shall be at your sole risk, and you agree to defend, indemnify, and hold " . $pref_practice . " harmless for all claims, injuries, damages, losses, expenses and legal fees arising out of the unauthorized modification or use of these materials.\nThe recipient understands that the use of any project-related computer data constitutes acceptance of the above conditions. This CAD information is being provided to you 'as is' and " . $pref_practice . " cannot accept any liability for its use whatsoever. \nBy opening or accessing the file(s) provided by us and listed above you are accepting these terms without exception. On this basis, " . $pref_practice . " is pleased to be able to provide CAD files related to the project." . $disclaimer;
} elseif ($set_format == "RVT") {
$disclaimer = "\nAt your request we are providing you with a Revit model.\nBecause the information stored in electronic form can be modified by other parties intentionally or otherwise, without notice or indication of said modifications, " . $pref_practice . " reserves the right to remove all indices of its ownership and/or involvement in material from each electronic medium not held in its possession. This material shall not be used by you or transferred to any other party for use in any other projects, additions to the current project or for any other purpose for which the material was not strictly intended by " . $pref_practice . " without our express written permission.\nAny unauthorised modification or reuse of the material shall be at your sole risk, and you agree to defend, indemnify, and hold " . $pref_practice . " harmless for all claims, injuries, damages, losses, expenses and legal fees arising out of the unauthorized modification or use of these materials.\nThe recipient understands that the use of any project-related computer data constitutes acceptance of the above conditions. This Revit model is being provided to you 'as is' and " . $pref_practice . " cannot accept any liability for its use whatsoever. \nBy opening or accessing the file(s) provided by us and listed above you are accepting these terms without exception. On this basis, " . $pref_practice . " is pleased to be able to provide a Revit model related to the project." . $disclaimer;
}

if ($set_reason == "As Instructed") {
$disclaimer = "\nThe information provided on this drawing constitutes the final construction issue and includes changes to original design information / details where these have been confirmed in writing by the contractor. The information does not constitute a post-construction survey and as such, this information should be utilised in conjunction with site checks to satisfy the user that all information is sufficiently accurate for their purposes." . $disclaimer;
} elseif ($set_reason == "Final Design") {
$disclaimer = "\nThe information provided on this drawing constitutes the final construction issue. The information does not constitute a post-construction survey and as such, this information should be utilised in conjunction with site checks to satisfy the user that all information is sufficiently accurate for their purposes." . $disclaimer;
}

if ($disclaimer != NULL) {
$pdf->Cell(0,5,'',0,1,L,0);
$disclaimer = "Important Note:" . $disclaimer;
$pdf->MultiCell(0,4,$disclaimer);
}

// If development code = "yes" (devcode = "yes") in the $_GET request, include some additional data

if ($_GET[devcode] == "yes") { $pdf->MultiCell(0,4,$sql_drawings); } 

// and send to output

$file_name = $proj_num."_2.05_".Date("Y",$file_date)."-".Date("m",$file_date)."-".Date("d",$file_date)."_Drawing_Issue_" . $set_id . ".pdf";

$pdf_title = $pref_practice . " Drawing Issue Sheet " . $set_id . ": " . $proj_num . " " . $proj_name . Date("Y",$file_date)."-".Date("m",$file_date)."-".Date("d",$file_date);

$pdf->SetTitle($pdf_title);

$pdf->Output($file_name,I);

?>
