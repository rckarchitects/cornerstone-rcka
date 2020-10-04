<?php

function HolidaySchedule($year,$user_usertype_current,$working_days,$beginnning_of_this_year,$beginnning_of_next_year) {

GLOBAL $conn;

						echo "<h3 id=\"holidaysthisyear\">Holidays in " . $year . "</h3>";

						echo "<p>There were " . $working_days . " working days in " . $year . ".</p>";

						if ($user_usertype_current < 3) { $limit = "AND user_id = " . $user_id; } else { unset( $limit );}

						$sql_users = "SELECT user_id, user_name_first, user_name_second FROM intranet_user_details WHERE (
						(user_user_added BETWEEN " . $beginnning_of_this_year . " AND " . $beginnning_of_next_year . ")
						OR (user_user_ended BETWEEN " . $beginnning_of_this_year . " AND " . $beginnning_of_next_year . ")
						OR (user_user_added < " . $beginnning_of_this_year . " AND (user_user_ended = 0 OR user_user_ended IS NULL))
						) " . $limit . " ORDER BY user_name_second";


						$result_users = mysql_query($sql_users, $conn);
						echo "<table>";

						echo "<tr>
						<th style=\"width: 15%;\">Name</th>
						<th style=\"width: 10%;\">Date Started</th>
						<th style=\"width: 10%;\">Date Ended</th>
						<th style=\"width: 6%; text-align: right;\">Years<br />(to end of $year)</th>
						<th style=\"width: 10%; text-align: right;\">Annual Allowance</th>
						<th style=\"width: 6%; text-align: right;\">Total Allowance</th>
						<th style=\"width: 6%; text-align: right;\">Allowance ($year)</th>
						<th style=\"width: 6%; text-align: right;\">Days Taken ($year)</th>
						<th style=\"width: 6%; text-align: right;\">Days Unpaid ($year)</th>
						<th style=\"width: 6%; text-align: right;\">Study Leave ($year)</th>
						<th style=\"width: 6%; text-align: right;\">Jury Service ($year)</th>
						<th style=\"width: 6%; text-align: right;\">TOIL ($year)</th>
						<th style=\"text-align: right;\">Days Remaining ($year)</th></tr>";

						while ($array_users = mysql_fetch_array($result_users)) {


							

							$user_id = $array_users['user_id'];
							$user_name_first = $array_users['user_name_first'];
							$user_name_second = $array_users['user_name_second'];
							
														
							$holiday_paid_total = 0;
							$holiday_unpaid_total = 0;
							$holiday_total = 0;
							$study_leave_total = 0;
							$jury_service_total = 0;
							$toil_service_total = 0;
							$toil_total = 0;
							
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
							$unpaid_adjustment = $UserHolidaysArray[14];
							
							if ($holiday_year_remaining < 0) { $holiday_year_remaining = "<span style=\"color: red;\">" . $holiday_year_remaining . "</span>"; }
							
							if ($_GET[showuser] == $user_id) { $bg = "; font-weight: bold; background: rgba(100,100,150,0.5)\""; } else { unset($bg); }
								
							echo "
							<tr>
							<td style=\"$bg\"><a href=\"index2.php?page=holiday_approval&amp;showuser=$user_id&year=$_GET[year]#holidaysthisyear\">" . $user_name_first . " " . $user_name_second . "</a></td>
							<td style=\"$bg\">" . $listadd . "</td>
							<td style=\"$bg\">" . $listend . "</td>
							<td style=\"text-align:right; $bg\">" . $length . "</td>
							<td style=\"text-align:right; $bg\">" . $user_holidays . "</td>
							<td style=\"text-align:right; $bg\">" . $holiday_allowance . "</td>
							<td style=\"text-align:right; $bg\">" . $holiday_allowance_thisyear . "</td>
							<td style=\"text-align:right; $bg\">" . $holiday_paid_total . "</td>
							<td style=\"text-align:right; $bg\">" . $holiday_unpaid_total . "</td>
							<td style=\"text-align:right; $bg\">" . $study_leave_total . "</td>
							<td style=\"text-align:right; $bg\">" . $jury_service_total . "</td>
							<td style=\"text-align:right; $bg\">" . $toil_service_total . "</td>
							<td style=\"text-align:right; $bg\">" . $holiday_year_remaining. "</td>
							</tr>";
							
							if ($_GET['showuser'] == $user_id) {
							
									$bg = "; background: rgba(100,100,150,0.1)\"";
							
										if ($unpaid_adjustment < 1 && $_GET['showuser'] == $user_id) {
											echo "<tr><td colspan=\"13\" style=\"font-style: italic; $bg\">" .
											$user_name_first . " took " . $holiday_unpaid_total . " unpaid holidays during " . $year . ", from a total of " . $working_days . " possible working days. Available holiday has therefore been reduced to " . round (100 *  $unpaid_adjustment ) . "% of the total allowance for this year. That equates to a total allowance of " . ceil($unpaid_adjustment * $holiday_allowance_thisyear) . " paid holidays for the year (ie. " . $holiday_allowance_thisyear. " x " . number_format($unpaid_adjustment * 100) . "%).</td></tr>";
										}
							
									$bg = "; background: rgba(100,100,150,0.2)\"";
								
									$sql_totalhols = "SELECT holiday_timestamp, holiday_length, holiday_paid, holiday_assigned FROM intranet_user_holidays WHERE holiday_user = " . $user_id . " AND holiday_assigned = " . $year . " ORDER BY holiday_timestamp";
									$result_totalhols = mysql_query($sql_totalhols, $conn);

										if (mysql_num_rows($result_totalhols) > 0) {
										
										$rows = mysql_num_rows($result_totalhols);
											
												$totalhols_count = 0;
												$totalholsup_count = 0;
												
											
												while ($array_totalhols = mysql_fetch_array($result_totalhols)) {
												
												$holiday_length = $array_totalhols['holiday_length'];
												
												if ($array_totalhols['holiday_paid'] == 0 ) { $holiday_type = "Unpaid Leave"; }
												elseif ($array_totalhols['holiday_paid'] == 2 ) { $holiday_type = "Study Leave";  }
												elseif ($array_totalhols['holiday_paid'] == 3 ) { $holiday_type = "Jury Service"; }
												elseif ($array_totalhols['holiday_paid'] == 4 ) { $holiday_type = "TOIL"; }
												elseif ($array_totalhols['holiday_paid'] == 5 ) { $holiday_type = "Compassionate Leave"; }
												elseif ($array_totalhols['holiday_paid'] == 6 ) { $holiday_type = "Maternity / Paternity Leave"; }
												elseif ($array_totalhols['holiday_paid'] == 7 ) { $holiday_type = "Furloughed"; }
												else { $holiday_type = "Standard"; $totalhols_count = $totalhols_count + $holiday_length; }
												
												if ($holiday_length == 0.5) { $holiday_type = $holiday_type . " (half day)"; }

													echo "<tr><td colspan=\"4\" style=\"$bg\">" . date ( "l, j F Y", $array_totalhols['holiday_timestamp'] ) . "</td>";
													echo "<td colspan=\"3\" style=\"$bg\">" . $holiday_type . "</td>";
														
														
														echo "
														<td style=\"text-align: right; $bg\">" . $totalhols_count . "</td>
														<td style=\"$bg\" colspan=\"5\"></td>
														";
													
												echo "</tr>";
												
												}
												
												if ($_GET['showuser'] == $user_id) { $bg = "; background: rgba(100,100,150,0.35)\""; } else { unset($bg); }
												
												echo "<tr><td colspan=\"7\" style=\"$bg\"><strong>Total</strong></td><td style=\"text-align: right; $bg\"><strong>$totalhols_count</strong></td><td colspan=\"5\" style=\"$bg\"></th></tr>";
											
											
										} else {
										
												echo "<tr><td></td><td colspan=\"12\">No holidays found for " . $year . "</td></tr>";
										
										}
										
								unset($bg);
								
								
							}


						}

						echo "</table>";








}

function ChangeHolidays($year) {
	
		$year_before = $year - 1;
		$year_after = $year + 1;
		
		echo "<table class=\"HideThis\"><tr><td rowspan=\"5\">Change selected holidays</td>
		<td><input type=\"radio\" value=\"approve\" name=\"approve\" checked=\"checked\" />&nbsp;Approve</td>
		<td><input type=\"radio\" value=\"unapprove\" name=\"approve\" />&nbsp;Unapprove</td>
		<td><input type=\"radio\" value=\"delete\" name=\"approve\" />&nbsp;Delete</td>
		<td><input type=\"radio\" value=\"to_paid\" name=\"approve\" />&nbsp;Make Paid Holiday</td>
		<td><input type=\"radio\" value=\"to_unpaid\" name=\"approve\" />&nbsp;Make Unpaid Holiday</td>
		</tr><tr>
		<td><input type=\"radio\" value=\"to_studyleave\" name=\"approve\" />&nbsp;Make Study Leave</td>
		<td><input type=\"radio\" value=\"to_juryservice\" name=\"approve\" />&nbsp;Make Jury Service</td>
		<td><input type=\"radio\" value=\"to_half\" name=\"approve\" />&nbsp;Make Half Day</td>
		<td><input type=\"radio\" value=\"to_full\" name=\"approve\" />&nbsp;Make Full Day</td>
		<td><input type=\"radio\" value=\"to_toil\" name=\"approve\" />&nbsp;Make TOIL</td>
		</tr><tr>
		<td><input type=\"radio\" value=\"compassionate\" name=\"approve\" />&nbsp;Make Compassionate Leave</td>
		<td><input type=\"radio\" value=\"furloughed\" name=\"approve\" />&nbsp;Make Furloughed</td>
		<td><input type=\"radio\" value=\"$year_before\" name=\"approve\" />&nbsp;Assign to " . $year_before . "</td>
		<td><input type=\"radio\" value=\"$year\" name=\"approve\" />&nbsp;Assign to " . $year . "</td>
		<td><input type=\"radio\" value=\"$year_after\" name=\"approve\" />&nbsp;Assign to " . $year_after . "</td>
		</tr><tr>
		<td><input type=\"radio\" value=\"to_maternity\" name=\"approve\" />&nbsp;Make Maternity / Paternity Leave</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		</tr>
		<tr>
		<td colspan=\"5\"><input type=\"hidden\" value=\"$_COOKIE[user]\" name=\"user_id\" /><input type=\"submit\" value=\"Submit\" /></td>
		</tr>
		</table>
		";
		
}

function WorkingDays($year) {
	
	GLOBAL $conn;
	
	$year = intval($year);
	
	$sql = "SELECT COUNT(bankholidays_id) FROM intranet_user_holidays_bank WHERE bankholidays_year = " . $year;
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	$bankholidays = $array['COUNT(bankholidays_id)'];
	
	$thisyear = $year;
	$day = mktime(12,0,0,1,1,$year);
	$countdays = 0;
	while ($thisyear == $year) {
		
		if (date("w",$day) > 0 && date("w",$day) < 6) { $countdays++; }
		$day = $day + 86400;
		$thisyear = intval ( date("Y",$day) );

	}
	
	$workingdays = $countdays - $bankholidays;
	
	return $workingdays;
	
}


function ListHolidays($days) {

	global $conn;
	
	
	
	$nowtime = time() - 43200;
	
	if (intval ($days) == 0) { $days = 7; } else { $days = intval($days); }
	
	$time =  60 * 60 * 24 * intval ($days);
	
	echo "<h2>Upcoming Holidays - Next " . $days . " Days</h2>";

		$sql5 = "SELECT user_id, user_name_first, user_name_second, holiday_date, holiday_timestamp, holiday_paid, holiday_length, holiday_approved, holiday_datestamp FROM intranet_user_details, intranet_user_holidays WHERE holiday_user = user_id AND holiday_timestamp BETWEEN $nowtime AND " . ($nowtime + $time) ." ORDER BY holiday_timestamp, user_name_second";
		$result5 = mysql_query($sql5, $conn) or die(mysql_error());
		if (mysql_num_rows($result5) > 0) {
			$current_date = 0;
			
			$holidaymessage = $holidaymessage . "<table>";
			while ($array5 = mysql_fetch_array($result5)) {
			
					if ($current_id != $user_id AND $current_id > 0) {
						$holidaymessage = $holidaymessage . "</td></tr>";
					}
					
					$user_id = $array5['user_id'];
					$user_name_first = $array5['user_name_first'];
					$user_name_second = $array5['user_name_second'];
					$holiday_timestamp = $array5['holiday_timestamp'];
					$holiday_length = $array5['holiday_length'];
					$holiday_paid = $array5['holiday_paid'];
					$holiday_date = $array5['holiday_date'];
					$holiday_approved = $array5['holiday_approved'];
					
					$calendar_link = "index2.php?page=holiday_approval&amp;year=" . date("Y",$holiday_timestamp) . "#Week" . date("W", $holiday_timestamp);
					
					if ($holiday_approved == NULL) { $holiday_approved1 = "<span style=\"color: red;\">"; $holiday_approved2 = "</span>";  } else { unset($holiday_approved1); unset($holiday_approved2); }
					if ($current_date != $holiday_date) {
						$holidaymessage = $holidaymessage . "<tr><td>" . TimeFormatDay($holiday_timestamp) . "</td><td>";
					} else { 
						$holidaymessage = $holidaymessage . ", ";
					}
					
					if ($holiday_length < 1) { $holiday_length = " (Half Day)"; } else { unset($holiday_length); }
					
					$holidaymessage = $holidaymessage . "<a href=\"" . $calendar_link . "\">" . $holiday_approved1 . $user_name_first . " " . $user_name_second . $holiday_length . $holiday_approved2 . "</a>"; ;
					
					$current_date = $holiday_date;
			}
			
			$holidaymessage = $holidaymessage . "</td></tr></table>";
		}

	echo $holidaymessage;

}

function ListHoliday($day_begin, $color_switch) {

		if ($color_switch == 1) { SetColor1(); } else { SetColor2(); }

		GLOBAL $conn;
		GLOBAL $pdf;
		
		StyleBody(8,'Helvetica','');
		
		$day = date("D j",$day_begin);
		
		$pdf->Cell(15,10,$day);
		
		$day_begin = $day_begin + 43200;
		$date = date("Y-m-d",$day_begin);
		
		StyleBody(14,'Helvetica','B');
		
		$sql_bankhols = "SELECT bankholidays_description FROM intranet_user_holidays_bank WHERE bankholidays_datestamp = '$date'";
		$result_bankhols = mysql_query($sql_bankhols, $conn) or die(mysql_error());
		$array_bankhols = mysql_fetch_array($result_bankhols);
		if ($array_bankhols['bankholidays_description']) { $pdf->Cell(0,12,$array_bankhols['bankholidays_description'],0,0,'L',0); } else {
		
			$sql = "SELECT * FROM `intranet_user_holidays`, `intranet_user_details` WHERE user_id = holiday_user AND holiday_datestamp = '$date' ORDER BY user_initials";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			while ($array = mysql_fetch_array($result)) {
				if ($array['holiday_length'] < 1) { 
				$pdf->Cell(6,12,'',0,0,'C',1);
				$xval = $pdf->GetX() - 6;
				$pdf->SetX($xval);
				$pdf->Cell(12,12,$array['user_initials'],0,0,'C',0);
				} else {
				$pdf->Cell(12,12,$array['user_initials'],0,0,'C',1);
				}
				$pdf->Cell(2,12,'',0,0,'C',0);
				if ($pdf->GetX() < 25) { $pdf->SetX(25); }
			}
			
		}
		
		$pdf->Ln(14);


}
	
function OtherHolidaysToday($user_id,$date) {

	GLOBAL $conn;
	GLOBAL $pdf;
	
	$sql_user_holidays = "SELECT user_initials, holiday_approved FROM intranet_user_holidays LEFT JOIN intranet_user_details ON user_id = holiday_user WHERE holiday_user != $user_id AND holiday_datestamp = '$date' ORDER BY user_initials";
	$result_user_holidays = mysql_query($sql_user_holidays, $conn) or die(mysql_error());
	
	$numrows = mysql_num_rows($result_user_holidays);
	
	if ($numrows > 0) {
			$cellwidth = 75 / $numrows;
			if ($cellwidth > 10) { $cellwidth = 10; }
			
			
			
			while ($array_user_holidays = mysql_fetch_array($result_user_holidays)) {
			
				if ($array_user_holidays['holiday_approved'] > 0) { $pdf->SetTextColor(0,0,0); } else { $pdf->SetTextColor(255,0,0); }
				
				$pdf->Cell($cellwidth,7.5,$array_user_holidays['user_initials'],'B',0,L,0);		
			}
			
			$pdf->Cell(0,7.5,'','B',1,L,0);	
		
			
	} else {
	
				$pdf->SetTextColor(0,0,0);
	
				$pdf->Cell(0,7.5,$array_user_holidays['user_initials'],'B',1,C,0);
	
	}
	
	$pdf->SetTextColor(0,0,0);


}
