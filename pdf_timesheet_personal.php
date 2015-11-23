<?php

include "inc_files/inc_checkcookie.php";

if ($_COOKIE[user] == NULL) { header ("Location: index2.php"); } else {


$user_id = $_COOKIE[user];

$timesheet_datum = 

		// Establish the parameters for what we are showing

$format_bg_r = "220";
$format_bg_g = "220";
$format_bg_b = "220";

if ($settings_pdffont != NULL) {
$format_font = $settings_pdffont;
$format_font_2 = $settings_pdffont.".php";
} else {
$format_font = "franklingothicbook";
$format_font_2 = "franklingothicbook.php";
}


//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->useTemplate($tplidx, 0, 0, 210, 297);

$pdf->AddFont($format_font,'',$format_font_2);

$pdf->SetY(50);

$pdf->SetFont($format_font,'',16);

// Printed by, and on...

$pdf->SetTextColor(180,180,180);

$ts_print_title = "Timesheets";

$pdf->MultiCell(0,6,$ts_print_title,0, L, 0);

$sql = "SELECT user_name_first, user_name_second FROM intranet_user_details WHERE user_id = $user_id";
$result = mysql_query($sql, $conn) or die(mysql_error());
$array = mysql_fetch_array($result);

$user_name_first = $array['user_name_first'];
$user_name_second = $array['user_name_second'];

$pdf->SetFont($format_font,'',10);

$printed_on = "Generated ".date("r")." by ".$user_name_first." ".$user_name_second;

$pdf->Cell(0,10,$printed_on,0, 1, L, 0);

$pdf->SetFillColor(220, 220, 220);

$pdf->SetTextColor(0,0,0);

// Begin the array through all users

$sql = "SELECT * FROM intranet_projects, intranet_timesheet LEFT JOIN intranet_timesheet_fees ON ts_fee_id = ts_stage_fee WHERE ts_project = proj_id AND ts_user = $user_id ORDER BY ts_entry, proj_num, ts_fee_stage ";
$result = mysql_query($sql, $conn) or die(mysql_error());

	unset ($current_day);
	unset ($current_project);

	while ($array = mysql_fetch_array($result)) {
	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];
	$proj_id = $array['proj_id'];
	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$ts_entry = TimeFormat ( $array['ts_entry'] ) ;
	$ts_desc = $array['ts_desc'];
	$ts_hours = $array['ts_hours'];
	$ts_day_complete = $array['ts_day_complete'];
	
	if ($current_day != $ts_entry) {
	$date_print = $ts_entry;
	$pdf->SetDrawColor(220,220,220);
	$pdf->Cell(0,1,'',T,1);
	} else {
	unset($date_print);
	}
	
	if ($current_project != $proj_id OR $current_day != $ts_entry) { $project_print_num = $proj_num; $project_print_name = $proj_name; } else { unset($project_print_num); unset($project_print_name); }
	
	if ( $array['ts_fee_text'] ) { $ts_fee_text = substr ( $array['ts_fee_text'] , 0 , 30); } else { $ts_fee_text = "-"; }

				
					$pdf->SetFont($format_font,'',7);
					
					if ($ts_day_complete == 0) { $pdf->SetTextColor(255,0,0); } else { $pdf->SetTextColor(0,0,0); }
			
						$pdf->Cell(15,4,$date_print,0, 0, L, 0);
						$pdf->Cell(10,4,$view_hours,0, 0, R, 0);
						$pdf->Cell(10,4,$project_print_num,R, 0, L, 0);
						$pdf->Cell(40,4,$project_print_name,R, 0, L, 0);
						$pdf->Cell(40,4,$ts_fee_text,R, 0, L, 0);
						$pdf->Cell(10,4,$ts_hours,R, 0, L, 0);
						$pdf->MultiCell(0,4,$ts_desc,L,L);
						
					$pdf->Cell(0,1,'',0,1);
					
					
		$current_day = 	$ts_entry;
		$current_project = $proj_id;	
					
	
}

$pdf->SetDrawColor(220,220,220);
$pdf->Cell(0,1,'',T,1);


// and send to output

$print_begin = $_POST[submit_begin];
$print_end = $_POST[submit_end];

$file_name = "Project_Analysis_".$proj_num . ".pdf";

$pdf->Output($file_name,I);

}
?>
