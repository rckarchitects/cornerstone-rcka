<?php

function CheckHolidays($start, $back, $insert, $user, $length, $paid) {

//echo "<p>Dates requested: " . date("l j F Y", $start) . " to " . date("l j F Y", $back) . "</p>";

GLOBAL $conn;
	
$counter_time = $start;
	$counter_days = 0;
	echo "<ul>";
	$inserted = 0;
	while ($counter_time < $back) {
	
		$sql_bankholidays = "SELECT bankholidays_datestamp FROM intranet_user_holidays_bank WHERE bankholidays_datestamp = '" . date("Y-m-d",$counter_time) . "'";
		$result_bankholidays = mysql_query($sql_bankholidays, $conn);
		$holidays_checked = mysql_num_rows($result_bankholidays);
				
		$sql_existingholidays = "SELECT holiday_datestamp FROM intranet_user_holidays WHERE holiday_datestamp = '" . date("Y-m-d",$counter_time) . "' AND holiday_user = " . $user;
		$result_existingholidays = mysql_query($sql_existingholidays, $conn);
		$holidays_existingholidays = mysql_num_rows($result_existingholidays);
		
		//echo "<p>$sql_existingholidays</p>";
		
		if ( $holidays_checked  > 0 ) { $bank_holiday = "yes"; } else { $bank_holiday = "no"; }
		if ( $holidays_existingholidays  > 0 ) { $existing_holiday = "yes"; } else { $existing_holiday = "no"; }
		if ( date("w",$counter_time) == 0 OR date("w",$counter_time) == 6) { $weekend = "yes"; } else { $weekend = "no"; }
		
		//echo "<p>" . date("Y-m-d",$counter_time) . " (Weekend: " . $weekend . ", Bank Holiday: " . $bank_holiday . ", Existing: " . $existing_holiday . ")</p>";
		
		if ($weekend == "no" AND $bank_holiday == "no" AND $holidays_existingholidays == "no") { $inserted = $inserted++; }
		
		if ($weekend == "no" AND $bank_holiday == "no" AND $holidays_existingholidays == "no" AND $insert == "yes") {
			
			$sql_holidays = "INSERT INTO intranet_user_holidays (holiday_id, holiday_user, holiday_date, holiday_month, holiday_year, holiday_approved, holiday_timestamp, holiday_length, holiday_paid, holiday_datestamp) VALUES ( NULL, $user, " . date("j",$counter_time) . "," . date("n",$counter_time) . "," . date("Y",$counter_time) . ", NULL," . $counter_time . "," . $length . "," . $paid . ",'" . date("Y-m-d",$counter_time) . "')";
			
			$result_holidays = mysql_query($sql_holidays, $conn);
			echo "<li>" . date("l j F Y", $counter_time) . " </li>";
		} elseif ($weekend == "no" AND $bank_holiday == "no" AND $holidays_existingholidays == "no" AND $insert != "yes") { 
			echo "<li>" . date("l j F Y", $counter_time) . "</li>";
			$inserted = $inserted++;
		} elseif ($bank_holiday == "yes") { echo "<li>" . date("l j F Y", $counter_time) . " is a bank holiday</li>";
		} elseif ($holidays_existingholidays == "yes" && $weekend == "no") { echo "<li>" . Date("l j F Y", $counter_time) . " is already booked as holiday</li>";
		}
		
		if ($weekend == "no" && $bank_holiday == "no" && $existing_holiday == "no") { $counter_days++; }
		
		$counter_time = $counter_time + 86400;
	}
	
	
	
	echo "</ul>";
	
	//if ($inserted == 0) { echo "<p>No holidays requested.</p>"; }

	return $counter_days;

}

$holiday_begin = $_POST[holiday_day_start];
$time_begin = AssessDays($holiday_begin);

$holiday_back = $_POST[holiday_day_back];
$time_back = AssessDays($holiday_back);



?>