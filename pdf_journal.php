<?php

include_once "inc_files/inc_checkcookie.php";
include_once "inc_files/inc_action_functions_pdf.php";
include_once "secure/prefs.php";

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
$blog_id = CleanUp($_GET[blog_id]);
// Begin creating the page
//Page Title
$sql_project = "SELECT * FROM intranet_projects_blog, intranet_user_details, intranet_projects WHERE blog_id = $blog_id AND blog_proj = proj_id AND blog_user = user_id LIMIT 1";
$result_project = mysql_query($sql_project, $conn) or die(mysql_error());
$array_project = mysql_fetch_array($result_project);
$proj_id = $array_project['proj_id'];
$proj_num = $array_project['proj_num'];
$proj_name = $array_project['proj_name'];
$blog_title = $array_project['blog_title'];
$blog_date = $array_project['blog_date'];
$blog_type = $array_project['blog_type'];
$blog_text = explode ("</p>",$array_project['blog_text']);

$user_name_first = $array_project['user_name_first'];
$user_name_second = $array_project['user_name_second'];


	if ($blog_type == "phone") { $blog_type_view = "Telephone Call";}
	elseif ($blog_type == "filenote") { $blog_type_view = "File Note"; }
	elseif ($blog_type == "meeting") { $blog_type_view = "Meeting Note";}
	elseif ($blog_type == "email") { $blog_type_view = "Email Message"; }
	else { $blog_type_view = NULL; $type = 0; }
	
	$blog_type_view = $blog_type_view." - ".$user_name_first." ".$user_name_second;
	ProjectHeading($proj_id,"File Note");
	$pdf->SetXY(10,65);
	$pdf->Cell(0,7.5,$sheet_subtitle,0,1,L,0);
	$pdf->Cell(0,7.5,$blog_type_view,0,1,L,0);
	$pdf->SetFont($format_font,'',14);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->MultiCell(0,8,$blog_title,0,L);
	
	
	$pdf->SetXY(40,95);
	
	$pdf->SetFont($format_font,'',11);
	$pdf->SetTextColor(0, 0, 0);
	foreach ($blog_text AS $line) {
		$pdf->SetX(10);
		$line = strip_tags(nl2br(RemoveShit($line)));
		$pdf->MultiCell(0,5,$line,0,L);
	}
// and send to output
$file_name = $proj_num."_".Date("Y",$blog_date)."-".Date("m",$blog_date)."-".Date("d",$blog_date)."_".$blog_type.".pdf";
$pdf->Output($file_name,I);
?>