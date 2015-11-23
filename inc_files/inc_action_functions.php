<?php

$removestrings_all = array("<",">","|");
$removestrings_phone = array("+44","(",")");

$currency_symbol = array("£","€");
$currency_text = array("&pound;","&euro;");
$currency_junk = array("£","€");

$text_remove = array("Ã","Â");

function CreateDays($date,$hour) {

		//Take the date and explode it into an array
		//$date_array = explode("-",$date);
		//$d = $date_array[2];
		//$m = $date_array[1];
		//$y = $date_array[0];

		//if ($date == "0000-00-00") { $output = NULL; } else { $output = mktime($hour,0,0,$m,$d,$y); }
		
		//return $output;
	
}

function CleanUp($input) {
	// global $currency_symbol;
	// global $currency_text;
	global $removestrings_all;
	// $input = str_replace($removestrings_all, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = addslashes($input);
	// $input = str_replace($currency_junk,$currency_text,$input);
	return($input);
}

function CleanUpAddress($input) {
	$input = str_replace($removestrings_all, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = htmlentities($input);
	return($input);
}

function DeCode($input) {
	global $currency_symbol;
	global $currency_text;
	global $currency_junk;
	$input = html_entity_decode($input);
	return($input);
}

function PresentText($input) {
	global $currency_symbol;
	global $currency_text;
	global $currency_junk;
	$input = str_replace($currency_symbol,$currency_junk,$input);
	//$input = htmlentities($input);
	$input = nl2br($input);
	$input = trim($input);
	$string = $input;
	$input = wordwrap($input, 40, "\n", true);
	$input = ereg_replace('[a-zA-Z]+://(([.]?[a-zA-Z0-9_/-?&%\'])*)','<u>\\1</u> <a href="\\0" target="_blank"><img src="images/button_internet.png" /></a>',$input);
	return $input;
	}


function CleanUpNames($input) {
	$input = str_replace($removestrings_all, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = htmlentities($input);
	return($input);
}

function CleanUpEmail($input) {
	$input = strtolower($input);
	$input = str_replace($removestrings_all, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = htmlentities($input);
	return($input);
}

function CleanUpPhone($input) {
	$input = strtolower($input);
	$input = str_replace($removestrings_phone, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = htmlentities($input);
	return($input);
} 

function CleanUpPostcode($input) {
	$input = ucwords(strtoupper($input));
	$input = str_replace($removestrings_all, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = htmlentities($input);
	return($input);
}

function CleanNumber($input) {
	return($input);
}

function PostcodeFinder($input) {
	$spaces = " ";
	$input = str_replace($spaces, "+", $input);
	$input = "http://google.com/maps?q=$input";
	// $input = "http://www.streetmap.co.uk/streetmap.dll?postcode2map?$input";
	return($input);
}

function TimeFormat($input) {
	$input = gmdate("j M Y", $input);
	return($input);
}

function TimeFormatBrief($input) {
	$input = gmdate("j.n.Y", $input);
	return($input);
}

function TimeFormatDetailed($input) {
	$input = gmdate("g.ia, j F Y", $input);
	return($input);
}

function TimeFormatDay($input) {
	$input = gmdate("l, j F Y", $input);
	return($input);
}

function TrimLength($input,$max) {
	if (strlen($input) > $max) {
	  $input = substr($input,0,$max-3)."...";
	}
	return($input);
  }

function MoneyFormat($input) {  
	$input =  "&pound;".number_format($input, 2);
	return($input);
}

function CashFormat($input) {
		$input = "£".number_format($input,2,'.',',');
		return($input);
		}
		
function RemoveShit($input) {
$remove_symbols = array("Â","Ã");
$swap_1 = array("â‚¬", "\n");
$replace_1 = array("€", "\n");
		$output = str_replace($remove_symbols, "", $input);
		$output = str_replace($swap_1, $replace_1, $output);
return $output;
}

function NumberFormat($input) {
	$input = number_format($input, 2, '.', '');
	return($input);
}

function BeginWeek($input) {
	$dayofweek = date("w", $input);
	if ($dayofweek == 1) { $dayofweek = 0; }
	elseif ($dayofweek == 2) { $dayofweek = 1; }
	elseif ($dayofweek == 3) { $dayofweek = 2; }
	elseif ($dayofweek == 4) { $dayofweek = 3; }
	elseif ($dayofweek == 5) { $dayofweek = 4; }
	elseif ($dayofweek == 6) { $dayofweek = 5; }
	elseif ($dayofweek == 0) { $dayofweek = 6; }
	$daysofweek = (($dayofweek) * 86400 ) - 7200;
	$today = mktime(0, 0, 0, date("n", $input), date("j", $input), date("Y", $input));
	$monday = ( $today - $daysofweek );
	return($monday);
}

function BeginMonth($time,$week,$backwards) {
	//"backwards" means how many weeks to go back - assume none
	if ($backwards > 0) { $time = $time - ($backwards * 604800); } 
	$month = date("n", $time);
	$year = date("Y", $time);
	$firstday = mktime(12,0,0,$month,1,$year);
	if ($week != NULL) { $firstday = BeginWeek($firstday); }
	return($firstday);
}

function TextPresent($input) {
	$input = htmlentities($input);
	$input = nl2br($input);
	return($input);
}

function UserDetails($input) {
	return($input);
}

function DateDropdown($input, $timecode) {

		$date_day = array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
		$date_month_display = array("January","February","March","April","May","June","July","September","October","November","December");
		$date_month = array("1","2","3","4","5","6","7","8","9","10","11","12");
		$date_year = array("2000","2001","2002","2003","2004","2006","2007","2008","2009","2010");
		print "Day:&nbsp;";
		print "<select name=\"".$input."_day\">";
		print "<option value=\"\">-- N/A --";
		$counter = 0;
		while ($counter < count($date_day)) {
			print "<option value=\"$date_day[$counter]\">$date_day[$counter]</option>";
			if (date("j", $timecode) == $date_month[$counter]) { print " selected "; }
			$counter++;
		}
		print "</select>";
		print "&nbsp;Month:&nbsp;";
		print "<select name=\"".$input."_month\">";
		print "<option value=\"\">-- N/A --";
		$counter = 0;
		while ($counter < count($date_month)) {
			print "<option value=\"$date_month[$counter]\">$date_month_display[$counter]</option>";
			if (date("n", $timecode) == $date_month[$counter]) { print " selected "; }
			$counter++;
		}
		print "</select>";
		print "&nbsp;Year:&nbsp;";
		print "<select name=\"".$input."_year\">";
		print "<option value=\"\">-- N/A --";
		$counter = 0;
		while ($counter < count($date_year)) {
			print "<option value=\"$date_year[$counter]\">$date_year[$counter]</option>";
			if (date("Y", $timecode) == $date_month[$counter]) { print " selected "; }
			$counter++;			
		}
}

function VATDown($input, $input2) {
	$input2 = $input2 / 100;
	$input2 = $input2 + 1;
	$input2 = 1 / $input2;
	$input = $input * $input2;
	return($input);
}

function InvoiceDueDays($invoice_text, $invoice_due, $invoice_date) {
	$invoice_due_days = $invoice_due - $invoice_date;
	$invoice_due_days = $invoice_due_days / 86400;
	settype($invoice_due_days, "integer");
	$invoice_text = str_replace("[due]", $invoice_due_days, $invoice_text);
	return $invoice_text;
}


function AssessDays($input,$hour) {
	
		if ($hour == NULL) { $hour = 12; }

		$date_array = explode("-",$input);
		$d = $date_array[2];
		$m = $date_array[1];
		$y = $date_array[0];
		
		$time = mktime($hour, 0, 0, $m ,$d, $y);
		
		return $time ;

}

function KeyWords($input) { 
				
	$keywords = explode(",", $input);
	$count = 0;
	$total = count($keywords);
	while ($count < $total)
	{
	$keyword = trim($keywords[$count]);
		if (strlen($keywords[$count]) > 3) {
		$output = $output . "&nbsp;<a href=\"index2.php?page=tender_search&amp;tender_keyword=$keyword\">$keyword</a>"; }
		$count++;
	$output = $output . "</a>,";
	}
	$output = rtrim($output,",");
	echo $output;
}

function TenderWords($input) {
	$input = str_replace(" & "," and ",$input);
	$keyword_array = 
	"housing standard,hca,quality standard,quality management,design standard,communit,consultant,consultation,value,communication,customer service,customer satisfaction,partnering,collaboration,experience,resident involvement,participation,environmental,structure,training,development,turnover,accreditation,achievement,award,competition,budget constraint,contract,certification,innovation,personnel,improvement,design team,approach,diverse,stakeholder,design and build,SMART,cabe,detailing,construction,kpis,scale,performance,tenures,geographical area,multi-use,mixed-use,new-build,new build,good design,special needs,complaint,sustainab,refurb,engage,planner,resident,planning,communicate,decent homes,collaborative,lifetime homes,building for life,standards,diversity,equality";
$keyword_explode = explode(",",$keyword_array);
$counter = 0;
$total = count($keyword_explode);
		while ($counter < $total) {
		$keyword_explode_padded = $keyword_explode[$counter];
		$replace = "<a href=\"index2.php?page=tender_search&amp;tender_keyword=$keyword_explode[$counter]\">".$keyword_explode[$counter]."</a>";
		$input = str_replace($keyword_explode_padded,$replace,$input);
		$counter++;
		}

echo $input;

}

		function WordCount($input) {
		$output = str_word_count(strip_tags($input));
		return $output;
		}
		

function ShowSkins($input) {
$input = "/".$input;
$array_skins = scandir($input);
return $array_skins;
}

function DayLink($input) {
	
	$output = "<a href=\"index2.php?page=datebook_view_day&amp;time=" . $input . "\">" . TimeFormat($input) . "</a>";
	return $output;

}






function TimeSheetHours($user_id,$display) {

// $display variable: if NULL, then checks the user_id and returns the percentage completed, if "list" then returns a formatted list showing incomplete days, and if "return", just returns the total percentage instead.

GLOBAL $database_location;
GLOBAL $database_username;
GLOBAL $database_password;
GLOBAL $database_name;
GLOBAL $settings_timesheetstart;

$conn = mysql_connect("$database_location", "$database_username", "$database_password");
mysql_select_db("$database_name", $conn);
$sql_user = "SELECT user_timesheet_hours, user_user_added, user_user_ended FROM intranet_user_details WHERE user_id = '$user_id' LIMIT 1";
$result_user = mysql_query($sql_user, $conn) or die(mysql_error());
$array_user = mysql_fetch_array($result_user);
$user_user_added = $array_user['user_user_added'];
$user_user_ended = $array_user['user_user_ended'];
$user_timesheet_hours = $array_user['user_timesheet_hours'];


if ($user_user_added > $settings_timesheetstart) { $timesheet_datum = $user_user_added; } else { $timesheet_datum = $settings_timesheetstart; }

if ($user_user_ended > 0) { $end_time = $user_user_ended; } else { $end_time = time(); }


		$startweek = BeginWeek($timesheet_datum);

		$this_week = BeginWeek(time());

		$sql4 = " SELECT ts_id, ts_user, ts_day, ts_month, ts_year, ts_entry FROM intranet_timesheet WHERE ts_entry > $startweek AND ts_entry < $end_time AND ts_entry < $this_week AND ts_day_complete = 1 AND ts_user = $user_id ORDER BY ts_entry";
		
		$current_day_check = 0;
		
		$day_complete_total = 0;
		
		if ($display == "list") { echo "<ul>"; }
		
		$result4 = mysql_query($sql4, $conn) or die(mysql_error());
		while ($array4 = mysql_fetch_array($result4)) {

		$ts_hours = $array4['ts_hours'];
		$ts_entry = BeginWeek($array4['ts_entry']);
		//$ts_beginweek = BeginWeek($array4['ts_entry']);
		$ts_check = $array4['ts_day'] . "-" .  $array4['ts_month'] . "-" . $array4['ts_year'];
		$ts_id = $array4['ts_id'];
		
		$dayofweek = date("w",$array4['ts_entry']);
		
		if	($ts_check != $current_day_check AND $dayofweek > 0 AND $dayofweek < 6 ) {
				
				$day_complete_total = $day_complete_total + 1;
				
				if ($display == "list") { echo "<li><a href=\"popup_timesheet.php?week=$ts_entry&amp;user_view=$user_id\">" .TimeFormat($array4['ts_entry']) . "</li>"; }
				
				
				$current_day_check = $ts_check;
				
			}
			
				
		
		
		
		}
		
		if ($display == "list") { echo "</ul>"; }
		
		
		// Now work out number of possible days since start
		
		$total_days = floor((5/7) * ((BeginWeek(time()) - BeginWeek($timesheet_datum)) / 86400));
		
		$timesheet_percentage_complete = round(100 * ($day_complete_total/$total_days));
		
		if ($display == NULL) { setcookie(timesheetcomplete, $timesheet_percentage_complete, time() + 86400); return $timesheet_percentage_complete; }
		
		if ($display == "return") { return $timesheet_percentage_complete; }
		
		$sql_update_completion = "UPDATE intranet_user_details SET user_timesheet_completion = $timesheet_percentage_complete WHERE user_id = $user_id LIMIT 1";
		mysql_query($sql_update_completion, $conn) or die(mysql_error());
		

}


function UserHolidays($user_id,$text) {

	GLOBAL $database_location;
	GLOBAL $database_username;
	GLOBAL $database_password;
	GLOBAL $database_name;
	GLOBAL $settings_timesheetstart;
	

	$conn = mysql_connect("$database_location", "$database_username", "$database_password");
	mysql_select_db("$database_name", $conn);
	
	// Establish the beginning of the year
		
	$this_year = date("Y",time());
	$next_year = $this_year + 1;
	$beginning_of_year = mktime(0,0,0,1,1,$this_year);
	$end_of_year = mktime(0,0,0,1,1,$next_year);
	
	$holiday_datum = mktime(0,0,0,1,1,2012);
	
	$sql_user_details = "SELECT user_user_added, user_user_ended, user_holidays FROM intranet_user_details WHERE user_id = $user_id";
	$result_user_details = mysql_query($sql_user_details, $conn) or die(mysql_error());
	$array_user_details = mysql_fetch_array($result_user_details);
	$user_user_added = $array_user_details['user_user_added'];
	if ($user_user_added == NULL OR $user_user_added < $holiday_datum ) { $user_user_added = $holiday_datum; }
	$user_user_ended = $array_user_details['user_user_ended'];
	$user_holidays = $array_user_details['user_holidays'];
	
	$sql_user_holidays = "SELECT SUM(holiday_length) FROM intranet_user_holidays WHERE holiday_user = $user_id AND holiday_paid = 1 AND holiday_timestamp < $end_of_year AND holiday_timestamp > $user_user_added";
	$result_user_holidays = mysql_query($sql_user_holidays, $conn) or die(mysql_error());
	$array_user_holidays = mysql_fetch_array($result_user_holidays);
	$user_holidays_taken = $array_user_holidays['SUM(holiday_length)'];
	
	//if ($user_user_added == NULL OR $user_user_added == 0) { $user_user_added = $settings_timesheetstart; }
	$begin_count = $user_user_added;
	
	if ($end_of_year > $user_user_ended AND $user_user_ended > 0) { $end_of_year = $user_user_ended; $ended = " (your employment ended on " . TimeFormat($user_user_ended) . ") "; }

	$seconds_to_end_of_year = $end_of_year - $begin_count;
	
	$years_total = $seconds_to_end_of_year / (365 * 60 * 60 * 24);
	
	$total_holidays_allowed = round($user_holidays * $years_total) - $user_holidays_taken;
	
	//$years_to_now = $seconds_to_end_of_year / (60 * 60 * 24 * 365);
	//$total_holidays_allowed =  ( round ( $user_holidays * $years_to_now ) ) - $user_holidays_taken;
	
	
	
	if ($text != NULL) {
	echo "<p>Your annual holiday allowance is <strong>" . $user_holidays . "</strong> days.</p><p>You have been employed since " . TimeFormat($begin_count). $ended . " and are therefore entitled to <strong>" . $total_holidays_allowed . " days</strong> (" . round ($years_total,2) . " years x " . $user_holidays . " days, less " . $user_holidays_taken . " days already taken) before the end of the year.</p>";
	}
	
	return $total_holidays_allowed;
	
}
















function TimeSheetListIncomplete($user_id) {

GLOBAL $database_location;
GLOBAL $database_username;
GLOBAL $database_password;
GLOBAL $database_name;
GLOBAL $settings_timesheetstart;
GLOBAL $user_user_added;

if ($user_user_added > $settings_timesheetstart) { $timesheet_datum = $user_user_added; } else { $timesheet_datum = $settings_timesheetstart; }

$conn = mysql_connect("$database_location", "$database_username", "$database_password");
mysql_select_db("$database_name", $conn);

		$startweek = BeginWeek($timesheet_datum);

		$this_week = BeginWeek(time());

		$sql4 = " SELECT ts_id, ts_user, ts_day, ts_month, ts_year, ts_entry FROM intranet_timesheet WHERE ts_entry > $startweek AND ts_entry < $this_week AND ts_day_complete != 1 AND ts_user = $user_id ORDER BY ts_entry";
		
		$current_day_check = 0;
		
		if ($display == "list") { echo "<ul>"; }
		
		$result4 = mysql_query($sql4, $conn) or die(mysql_error());
		while ($array4 = mysql_fetch_array($result4)) {

		$ts_hours = $array4['ts_hours'];
		$ts_entry = BeginWeek($array4['ts_entry']);
		$ts_check = $array4['ts_day'] . "-" .  $array4['ts_month'] . "-" . $array4['ts_year'];
		$ts_id = $array4['ts_id'];
		
		$dayofweek = date("w",$array4['ts_entry']);
		
		if	($ts_check != $current_day_check AND $dayofweek > 0 AND $dayofweek < 6 ) {
				
				echo "<li><a href=\"popup_timesheet.php?week=$ts_entry&amp;user_view=$user_id\">" .TimeFormat($array4['ts_entry']) . "</li>";
				
				$current_day_check = $ts_check;
				
			}	
		
		
		}
		echo "</ul>";
	
	

}











function UserDropdown($input_user) {

GLOBAL $conn;

	$sql = "SELECT user_id, user_active, user_name_first, user_name_second FROM intranet_user_details ORDER BY user_active DESC, user_name_second";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	echo "<select class=\"inputbox\" name=\"user_id\">";

	unset($user_active_test);
	
	while ($array = mysql_fetch_array($result)) {


		$user_id = $array['user_id'];
		$user_active = $array['user_active'];
		$user_name_first = $array['user_name_first'];
		$user_name_second = $array['user_name_second'];
		
		if ( $user_active != $user_active_test) {
				if ($user_active == 0) { echo "<option disabled></option><option disabled><i>Inactive Users</i></option><option disabled>------------------------</option>"; } else { echo "<option disabled><i>Active Users</i></option><option disabled>------------------------</option>"; } 
		$user_active_test = $user_active; }
		
            echo "<option value=\"$user_id\"";
            if ($user_id == $input_user) { echo " selected"; }
            echo ">".$user_name_first."&nbsp;".$user_name_second."</option>";
		}

	echo "</select>";
	
}


function TextAreaEdit_OLD() {

				echo "	<script type=\"text/javascript\" src=\"tiny_mce\tiny_mce.js\"></script>
						<script type=\"text/javascript\">
						tinyMCE.init({
						mode : \"textareas\",
						theme : \"advanced\",
						theme_advanced_layout_manager : \"SimpleLayout\",
						theme_advanced_toolbar_align : \"left\",
						theme_advanced_toolbar_location : \"top\",
						theme_advanced_disable : \"styleselect,formatselect,image,anchor,help,code,cleanup,hr,removeformat,charmap,visualaid,sub,sup,separator\",
						content_css : \"tiny_mce/editor.css\",
						theme_advanced_buttons3 : \"\" });
						</script>";

}

function TextAreaEdit() {

				echo "<script src=\"//tinymce.cachefly.net/4.1/tinymce.min.js\"></script>
					<script type=\"text/javascript\">
					tinymce.init({
					selector: \"textarea\",
					plugins: [
						\"advlist autolink lists link charmap print preview anchor textcolor\"
					],
					menubar: false,
					toolbar: \"undo redo | bold italic underline strikethrough | bullist numlist outdent indent | link unlink | forecolor \",
					autosave_ask_before_unload: false,
					height : 300,
					max_height: 1000,
					min_height: 160
				});
				</script>";

}

function EditForm($answer_id,$answer_question,$answer_ref,$answer_words,$answer_weighting,$tender_id) {

				TextAreaEdit();
						
				echo "<a name=\"$answer_id\"></a><form action=\"index2.php?page=tender_view&amp;tender_id=$tender_id#$answer_id\" method=\"post\">
					<tr><th style=\"width: 10%;\" name=\"$answer_id\">";
					echo "Ref: <br />";
					echo "<input type=\"text\" name=\"answer_ref\" value=\"$answer_ref\" size=\"4\"></th><th>";
					if ($answer_id == NULL) { echo "Add question:<br />"; } else { echo "Edit question below:<br />"; }
					echo "<textarea style=\"width: 100%; height: 360px;\" name=\"answer_question\">$answer_question</textarea>
					<br />Words allowed:&nbsp;<input type=\"text\" maxlength=\"4\" name=\"answer_words\" value=\"$answer_words\" />&nbsp;Weighting:<input type=\"text\" maxlength=\"10\" name=\"answer_weighting\" value=\"$answer_weighting\" /> 
					<br /><input type=\"submit\" />
					<input type=\"hidden\" name=\"answer_id\" value=\"$answer_id\" />
					<input type=\"hidden\" name=\"answer_tender_id\" value=\"$tender_id\" />
					<input type=\"hidden\" name=\"action\" value=\"tender_question_edit\" />
					</form>
					<form action=\"index2.php?page=tender_view&amp;tender_id=$tender_id#$answer_id\" method=\"post\"><input type=\"submit\" value=\"Cancel\" /></form>
				";
}


?>
