<?php

include "inc_files/inc_checkcookie.php";

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->addPage();
$pdf->useTemplate($tplidx, 0, 0, 210, 297);

//$format_font = "Helvetica";
//$format_font_2 = "franklingothicbook.php";

//$pdf->AddFont($format_font,'',$format_font_2);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY(30);
StyleBody(22,'Helvetica','B');
$pdf->Cell(0,17,'Holiday Request',0,1);
StyleBody(18,'Helvetica','B');

$time = time();
$user_id = $_GET[user_id];
$loopcheck = 0;


// Begin creating the page

//Page Title

		$year = date("Y",time());
		
		$working_days = WorkingDays($year);

		
		$UserHolidaysArray = UserHolidaysArray($user_id,$year,$working_days);
		
							$length = $UserHolidaysArray[0];
							$user_holidays = $UserHolidaysArray[1];
							$holiday_allowance = $UserHolidaysArray[2];
							$holiday_allowance_thisyear = $UserHolidaysArray[3];
							$holiday_paid_total = $UserHolidaysArray[4];
							$holiday_unpaid_total = $UserHolidaysArray[5];
							$study_leave_total = $UserHolidaysArray[6];
							$jury_service_total = $UserHolidaysArray[7];
							$toil_service_total = $UserHolidaysArray[8];
							$holiday_year_remaining = $UserHolidaysArray[9];
							$listadd = $UserHolidaysArray[10];
							$listend = $UserHolidaysArray[11];
							$user_name_first = $UserHolidaysArray[12];
							$user_name_second = $UserHolidaysArray[13];
							
		$applicant_name = $user_name_first." ".$user_name_second;
		$pdf->Cell(0,7.5,$applicant_name,0,1,L,0);
		$loopcheck++;
		StyleBody(11,'Helvetica','B');
		$pdf->Ln(2);
		$pdf->SetLineWidth(0.5);
		$pdf->Cell(110,7.5,'Days Requested','B',0,L,0);
		$pdf->Cell(0,7.5,'Other Holidays','B',1,L,0);
		StyleBody(11,'Helvetica','');
		$pdf->SetLineWidth(0.25);

$sql_user_holidays = "SELECT * FROM intranet_user_holidays WHERE holiday_user = $user_id AND holiday_timestamp > $time AND (holiday_approved IS NULL OR holiday_approved = 0) ORDER BY holiday_datestamp";
$result_user_holidays = mysql_query($sql_user_holidays, $conn) or die(mysql_error());


while ($array_user_holidays = mysql_fetch_array($result_user_holidays)) {

	$holiday_datestamp = $array_user_holidays['holiday_datestamp'];
	$holiday_paid = $array_user_holidays['holiday_paid'];
	$holiday_length = $array_user_holidays['holiday_length'];
	
		if ($holiday_paid == 1) { $holiday_paid = "Paid"; }
		elseif ($holiday_paid == 2) { $holiday_paid = "Study Leave"; }
		elseif ($holiday_paid == 3) { $holiday_paid = "Jury Service"; }
		elseif ($holiday_paid == 4) { $holiday_paid = "TOIL"; }
		elseif ($holiday_paid == 5) { $holiday_paid = "Compassionate Leave"; }
		else { $holiday_paid = "Unpaid"; }
		
		if ($holiday_length < 1) { $holiday_length_text = "Half Day"; } else { $holiday_length_text = "Full Day"; }
		
		StyleBody(10,'Helvetica','');
		$date = TimeFormatDay ( CreateDays($holiday_datestamp,12) );
		$pdf->Cell(50,7.5,$date,'B',0,L,0);
		$pdf->Cell(40,7.5,$holiday_paid,'B',0,L,0);
		$pdf->Cell(20,7.5,$holiday_length_text,'B',0,L,0);
		
		OtherHolidaysToday($user_id,$holiday_datestamp);
	
}

		$pdf->Ln(5);

		StyleBody(11,'Helvetica','B');
		$pdf->Cell(0,5,'Type',0,1,L,0);
		
		$pdf->Ln(5);
		StyleBody(11,'Helvetica','');
		$pdf->Cell(5,5,'',1,0);
		$pdf->Cell(45,5,'Paid',0,0,L,0);
		$pdf->Cell(5,5,'',1,0);
		$pdf->Cell(45,5,'Unpaid',0,0,L,0);
		$pdf->Cell(5,5,'',1,0);
		$pdf->Cell(45,5,'Study Leave',0,1,L,0);
		$pdf->Ln(2.5);
		$pdf->Cell(5,5,'',1,0);
		$pdf->Cell(45,5,'Jury Service',0,0,L,0);
		$pdf->Cell(5,5,'',1,0);
		$pdf->Cell(45,5,'Compassionate Leave',0,0,L,0);
		$pdf->Cell(5,5,'',1,0);
		$pdf->Cell(45,5,'TOIL',0,1,L,0);

// Holiday Allowance
		
		$pdf->Ln(5);

		StyleBody(11,'Helvetica','B');
		$pdf->Cell(0,5,'Notes',0,1,L,0);
							
		StyleBody(10,'Helvetica','');
		
		if ($holiday_allowance_thisyear > 0 ) {
			
			$holiday_paid_total = ceil ($holiday_paid_total * 2) / 2;
		
			$helptext = $user_name_first . " has a total paid holiday allowance of " . $holiday_allowance_thisyear . " days for " . $year . ".\n" . $user_name_first . " has booked " . $holiday_paid_total . " days to date (including this request)";
			
			if ($holiday_unpaid_total > 0) { $helptext = $helptext . " of these, in addition to " . $holiday_unpaid_total . " unpaid days,"; }
			
			$helptext = $helptext . " and therefore has " . $holiday_year_remaining . " day(s) holiday remaining before the end of the year." ;

			$pdf->MultiCell(150,5,$helptext);
		
		}
		

		
		$nowdate = TimeFormat(time());

		$pdf->Ln(5);
		StyleBody(11,'Helvetica','B');
		$pdf->Cell(0,5,'Holiday Approved',0,1,L,0);
		
		$pdf->Cell(120,25,'',1,1,L,0);
		$pdf->Ln(2.5);
		StyleBody(10,'Helvetica','');
		$pdf->Cell(0,5,$nowdate,0,1,L,0);
		
		


// and send to output

$file_name = $proj_num."_".Date("Y",$blog_date)."-".Date("m",$blog_date)."-".Date("d",$blog_date)."_".$blog_type.".pdf";

$pdf->Output($file_name,I);

?>
