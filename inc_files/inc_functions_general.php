<?php

$removestrings_all = array("<",">","|");
$removestrings_phone = array("+44","(",")");

$currency_symbol = array("£","€");
$currency_text = array("&pound;","&euro;");
$currency_junk = array("£","€");

$text_remove = array("Ã","Â");

function PresentCost($input) { 
		$output = "&pound;" . numberformat($input, 2);
		return $output;
}	


function StageTabs ($group_id_selected, $proj_id, $page, $filter) {
	GLOBAL $conn;
	
	if ($filter == "edit") {
	$sql_group = "SELECT group_id, group_code, group_description FROM intranet_timesheet_group WHERE group_project = 1 AND group_active = 1 ORDER BY group_order";
	} else {
	$sql_group = "SELECT * FROM intranet_timesheet_group, intranet_project_checklist_items LEFT JOIN intranet_project_checklist ON item_id = checklist_item WHERE group_project = 1 AND group_active = 1 AND checklist_project = $proj_id AND item_stage = group_id GROUP BY group_id ORDER BY group_order";
	}
	$result_group = mysql_query($sql_group, $conn) or die(mysql_error());
	if (mysql_num_rows($result_group) > 0) {
		echo "<p class=\"submenu_bar\"><span class=\"minitext\">Project Stage: </span>";
			while ($array_group = mysql_fetch_array($result_group)) {
				$group_id = $array_group['group_id'];
				$group_code = $array_group['group_code'];
				if ($group_id_selected == $group_id) { $group_code = "<strong>$group_code</strong>";
					echo "<span class=\"submenu_bar\" style=\"background: #eee;\">$group_code</span>"; $group_description = $group_code . ": " . $array_group['group_description'];
				} else {
					echo "<a href=\"" . $page . "group_id=" . $group_id . "\" class=\"submenu_bar\">$group_code</a>";
				}
			}
		echo "</p>";
		
		echo "<h3>" . $group_description . "</h3>";
	}
}


function SelectStage($item_stage, $bg) {

		GLOBAL $conn;

		$sql_stages = "SELECT * FROM intranet_timesheet_group WHERE group_active = 1 ORDER BY group_order";
		$result_stages = mysql_query($sql_stages, $conn) or die(mysql_error());
		
		
		echo "Select Project Stage: <select name=\"item_stage\">";
		
		echo "<option value=\"\">-- None --</option>";
		
		while ($array_stages = mysql_fetch_array($result_stages)) {
			
			if ($item_stage == $array_stages['group_id'] ) { $selected = " selected=\"selected\" "; } else { unset($selected); }
			
			echo "<option value=\"" . $array_stages['group_id'] . "\"" . $selected . ">" . $array_stages['group_code'] . ": " . $array_stages['group_description'] . "</option>";
		}
				
		echo "</select>";
		


}

function GetProjectInfo($proj_id) {
					if ($proj_id != NULL) {
						GLOBAL $conn;
						$sql = "SELECT proj_num, proj_name FROM intranet_projects WHERE proj_id = $proj_id";
						$result = mysql_query($sql, $conn) or die(mysql_error());
						$array = mysql_fetch_array($result);
						$proj_num = $array['proj_num'];
						$proj_name = $array['proj_name'];
						$proj_title = $proj_num . " " . $proj_name;
						echo "<a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">" . $proj_title . "</a>";
					}
}

function SearchPanel($user_usertype_current) {
	
	echo "<span class=\"heading_side_right\">Search</span>";
	echo "<div id=\"searchform\">";
	
	echo "<form action=\"index2.php?page=search\" method=\"post\">";
	
	if ($_POST[tender_search] == "yes") { $checked = " checked = \"checked\" "; } else { unset($checked) ; }

	echo "<p><input type=\"search\" name=\"keywords\" value=\"$_POST[keywords]\" id=\"txtfld\" onClick=\"SelectAll('txtfld');\" />&nbsp;<input type=\"submit\" value=\"Go\" /></p>";
	
	if ($user_usertype_current > 1) {
		echo "<p><input type=\"checkbox\" name=\"tender_search\" value=\"yes\" $checked />&nbsp;<span class=\"minitext\">Search tenders?</span>";
	}
	
	echo "</form></div>";
	
	
}

function ProjectTitle() {

	GLOBAL $conn;
	
	if ($_GET[proj_id] > 0) { $proj_id = intval($_GET[proj_id]); }
	elseif ($_POST[proj_id] > 0) { $proj_id = intval($_POST[proj_id]); }
	else { unset($proj_id); }
	
	if ($proj_id > 0) {
	
		$sql = "SELECT proj_name, proj_num FROM intranet_projects WHERE proj_id = $proj_id LIMIT 1";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		
		$output = array($proj_id,$proj_num,$proj_name);
		return $output;
		
	}

	


}

function ProjectSelect($proj_id_select,$field_name) {
	
		GLOBAL $conn;
	
		print "<select name=\"" . $field_name .  "\">";
		$sql = "SELECT * FROM intranet_projects order by proj_num DESC";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		while ($array = mysql_fetch_array($result)) {
				$proj_num = $array['proj_num'];
				$proj_name = $array['proj_name'];
				$proj_id = $array['proj_id'];
				print "<option value=\"$proj_id\" class=\"inputbox\"";
				if ($proj_id_select == $proj_id) { print " selected";}
				elseif ($proj_id == $proj_id_page) { print " selected";}
				print ">$proj_num $proj_name</option>";
		}
		print "</select>";
	
	
}

function ProjectSwitcher ($page,$proj_id, $proj_active, $proj_fee) {
	
	GLOBAL $conn;

	echo "<form action=\"index2.php\" method=\"get\">";
	echo "<p><input type=\"hidden\" name=\"page\" value=\"$page\" />";
	
	if ($proj_active == 1) { $proj_active_switch = "WHERE proj_active > 0"; }
	if ($proj_fee == 1 && $proj_active == 1) { $proj_fee_switch = "AND proj_fee_track > 0"; }
	elseif ($proj_fee != 1 && $proj_active == 1) { $proj_fee_switch = "WHERE proj_fee_track > 0"; }

	$sql_switcher = "SELECT proj_id, proj_name, proj_num FROM intranet_projects $proj_active_switch $proj_fee_switch ORDER BY proj_num DESC";
	$result_switcher = mysql_query($sql_switcher, $conn) or die(mysql_error());
	echo "<select onchange=\"this.form.submit()\" name=\"proj_id\">";
	while ($array_switcher = mysql_fetch_array($result_switcher)) {
		$proj_id_switcher = $array_switcher['proj_id'];
		$proj_num_switcher = $array_switcher['proj_num'];
		$proj_name_switcher = $array_switcher['proj_name'];
		if ($proj_id == $proj_id_switcher) { $select = " selected=\"selected\" "; } else { unset($select); }
		echo "<option value=\"$proj_id_switcher\" $select>$proj_num_switcher $proj_name_switcher</option>";
	}
	echo "</select>";
	echo "</p>";


	echo "</form>";

}

function CreateDays($date,$hour) {

		$date_array = explode("-",$date);
		$d = $date_array[2];
		$m = $date_array[1];
		$y = $date_array[0];

		if ($date == "0000-00-00") { $output = NULL; } else { $output = mktime($hour,0,0,$m,$d,$y); }
		
		return $output;
	
}

function CreateTimeFromDetailedTime($time,$date) {

		$time_array = explode(":",$time);
		$date_array = explode("-",$date);

		$hour = intval($time_array[0]);
		$minute = intval($time_array[1]);
		$second = 0;
		$month = intval($date_array[1]);
		$day = intval($date_array[2]);
		$year = intval($date_array[0]);
		
		$output = mktime($hour,$minute,0,$month,$day,$year);

		return $output;

}


function CreateDateFromTimestamp($timestamp) {

		$output = date("Y-m-d",$timestamp);
		
		return $output;

}

function CreateTimeFromTimestamp($timestamp) {

		$output = date("H:i",$timestamp);
		
		return $output;

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
	$input = addslashes($input);
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
	//$input = preg_replace('/\[(.*?)\]\s*\((.*?)\)/', '<a href="$2">$1</a>', '[text](url)');
	return $input;
	}

function CleanUpNames($input) {
	$input = str_replace($removestrings_all, "", $input);
	$input = strip_tags($input);
	$input = trim($input);
	$input = htmlentities($input);
	$input = addslashes($input);
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
	$input = gmdate("j.n.y", $input);
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

function SideMenu ($title, $array_pages, $array_title, $array_access, $user_usertype_current, $array_images, $align) {

$current_page = $_SERVER['QUERY_STRING'];

	$min_level = min($array_access);
	
	if ($align == "r") { $class = "_right"; } else { $class = "_left"; }
	
	if ($min_level <= $user_usertype_current ) {

			
			$count = 0;
			
			echo "<span id=\"heading_$title\" class=\"heading_side$class\"
				onMouseUp=\"document.getElementById('" . $title . $count . "').style.display='block'; document.getElementById('heading_" . $title . "').style.display='none'; document.getElementById('subheading_" . $title . "').style.display='block'\" style=\"cursor: pointer;\">$title</span>";
			echo "<span id=\"subheading_$title\" class=\"heading_side$class\"
				onMouseUp=\"document.getElementById('" . $title . $count . "').style.display='none'; document.getElementById('heading_" . $title . "').style.display='block'; document.getElementById('subheading_" . $title . "').style.display='none'\" style=\"display: none; cursor: pointer;\">$title</span>";
			echo "<ul id=\"" . $title . $count . "\" class=\"menu_side$class\" style=\"display: none;\">";
			foreach ($array_pages as $page) {
				if (($user_usertype_current >= $array_access[$count]) && ( $current_page != $array_pages[$count] )) {
					if ($array_images[$count]) { $image = "<img src=\"images/$array_images[$count]\" alt=\"$array_title[$count]\" />&nbsp;"; } else { unset($image); } 
					if ($array_pages[$count]) { $link = "<a class=\"menu_side$class\" href=\"$array_pages[$count]\">" . $image . $array_title[$count] . "</a>"; } else { unset($link); } 					
					echo "<li>" . $link . "</li>";
				} elseif ($user_usertype_current >= $array_access[$count]) {
					echo "<li><span class=\"menu_side$class\">$array_title[$count]</span></li>";
				}
				$count++;
			}
			echo "</ul>";
			
	}

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

function UserHolidays($user_id,$text,$year) {

	GLOBAL $database_location;
	GLOBAL $database_username;
	GLOBAL $database_password;
	GLOBAL $database_name;
	GLOBAL $settings_timesheetstart;
	
	if (!$year) { $year = date("Y",time()); }
	

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
	
		$workingdays = WorkingDays($year);
		
		$user_holiday_array = UserHolidaysArray($user_id,$year,$workingdays);
		//$array = array($length,$user_holidays,$holiday_allowance,$holiday_allowance_thisyear,$holiday_paid_total,$holiday_unpaid_total,$study_leave_total,$jury_service_total,$toil_service_total,$holiday_year_remaining,$listadd,$listend,$user_name_first, $user_name_second);
	
	echo "<p>Your annual holiday allowance is <strong>" . $user_holiday_array[1] . "</strong> days.</p><p>You are entitled to <strong>" . $user_holiday_array[9] . " days</strong> before the end of " . $year . "</p>";
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
					<tr><td style=\"width: 10%;\" name=\"$answer_id\">";
					echo "Ref: <br />";
					echo "<input type=\"text\" name=\"answer_ref\" value=\"$answer_ref\" size=\"4\" required=\"required\"></td><td>";
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

function ListHolidays($days) {

	global $conn;
	
	
	
	$nowtime = time() - 43200;
	
	if (intval ($days) == 0) { $days = 7; } else { $days = intval($days); }
	
	$time =  60 * 60 * 24 * intval ($days);
	
	echo "<h2>Upcoming Holidays - Next $days Days</h2>";

		$sql5 = "SELECT user_id, user_name_first, user_name_second, holiday_date, holiday_timestamp, holiday_paid, holiday_length, holiday_approved FROM intranet_user_details, intranet_user_holidays WHERE holiday_user = user_id AND holiday_timestamp BETWEEN $nowtime AND " . ($nowtime + $time) ." ORDER BY holiday_timestamp, user_name_second";
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
					
					$holidaymessage = $holidaymessage . "<a href=\"$calendar_link\">" . $holiday_approved1 . $user_name_first . " " . $user_name_second . $holiday_length . $holiday_approved2 . "</a>"; ;
					
					$current_date = $holiday_date;
			}
			
			$holidaymessage = $holidaymessage . "</td></tr></table>";
		}

	echo $holidaymessage;


}

function FooterBar() {
	
	echo "<div id=\"mainfooter\">powered by <a href=\"https://github.com/rckarchitects/cornerstone-rcka/wiki/Welcome-to-Cornerstone\">RCKa Cornerstone</a></div>";
	
}

function StyleBody($size,$font,$bold){
			Global $pdf;
			Global $format_font;
			if (!$font) { $font = $format_font; }
			$pdf->SetFont($font,$bold,$size);
			
		}
		
function StyleHeading($title,$text,$notes){
			Global $pdf;
			Global $x_current;
			Global $y_current;
			Global $y_left;
			Global $y_right;
			if ($y_current > 230 AND $x_current > 75) { $pdf->addPage(); $x_current = 10; $y_current = 15; }
			$pdf->SetXY($x_current,$y_current);
			$pdf->SetFont('Helvetica','b',10);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetX($x_current);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetLineWidth(0.3);
			$pdf->MultiCell(90,5,$title,B, L, false);
			$pdf->MultiCell(90,1,'',0, L, false);
			Notes($notes);
			$pdf->Ln(1);
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

function UserHolidaysArray($user_id,$year,$working_days) {
	
	GLOBAL $conn;

			$sql_user = "SELECT user_user_added, user_user_ended, user_holidays, user_name_first, user_name_second FROM intranet_user_details WHERE user_id = $user_id LIMIT 1";
			$result_user = mysql_query($sql_user, $conn);
			$array_user = mysql_fetch_array($result_user);
			$user_user_added = $array_user['user_user_added'];
			$user_user_ended = $array_user['user_user_ended'];
			$user_name_first = $array_user['user_name_first'];
			$user_name_second = $array_user['user_name_second'];
			
			$user_holidays = $array_user['user_holidays'];
			
			$holiday_datum = mktime(0,0,0,1,1,2012);
			
			$nextyear = $year + 1;
			
			if ($user_user_added == NULL OR $user_user_added < $holiday_datum ) { $user_user_added = $holiday_datum; $listadd = "-"; } else { $listadd = date ( "d M Y", $user_user_added ); }
			
			if ($user_user_ended == NULL OR $user_user_ended == 0) { $user_user_ended = mktime(0,0,0,1,1,$nextyear); $listend = "-"; } else { $listend = date ( "d M Y", $user_user_ended ); }
			
	
							$sql_count = "SELECT * FROM intranet_user_holidays WHERE holiday_user = $user_id AND holiday_assigned = $year ORDER BY holiday_timestamp";
							$result_count = mysql_query($sql_count, $conn);
							while ($array_count = mysql_fetch_array($result_count)) {
							

								$holiday_year = $array_count['holiday_year'];
								$holiday_length = $array_count['holiday_length'];
								$holiday_paid = $array_count['holiday_paid'];
								
								$holiday_allowance = $user_user_ended - $user_user_added;
							$yearlength = 365.242 * 24 * 60 * 60;
							$holiday_allowance = ( $holiday_allowance / $yearlength ) * $user_holidays;
							$holiday_allowance = round($holiday_allowance);
							
							$holiday_allowance_thisyear = $user_user_ended - mktime(0,0,0,1,1,$year);
							if ($user_user_added > mktime(0,0,0,1,1,$year)) { $holiday_allowance_thisyear = $holiday_allowance_thisyear - ($user_user_added - mktime(0,0,0,1,1,$year)); }
							
							
							
							$holiday_allowance_thisyear = $holiday_allowance_thisyear / (365.242 * 24 * 60 * 60) ;
							
							if ($holiday_allowance < $user_holidays) { $year_allowance = $holiday_allowance; } else { $year_allowance = $user_holidays; }
							
					
							$holiday_allowance_thisyear = round ($user_holidays * $holiday_allowance_thisyear);
								
											
											if ($holiday_paid == 1) { $holiday_paid_total = $holiday_paid_total + $holiday_length; }
											elseif ($holiday_paid == 2) { $study_leave_total = $study_leave_total + $holiday_length; }
											elseif ($holiday_paid == 3) { $jury_service_total = $jury_service_total + $holiday_length; }
											elseif ($holiday_paid == 4) { $toil_service_total = $toil_service_total + $holiday_length; $holiday_paid_total = $holiday_paid_total - $holiday_length;  }
											elseif ($holiday_paid == 5) {   }
											else { $holiday_unpaid_total = $holiday_unpaid_total + $holiday_length; }
											
											

											if ($holiday_paid == 1) { $holiday_total = $holiday_total + $holiday_length; }
											
								
								}
								
							// Calculate any adjustments for unpaid holiday	
								
							$unpaid_adjustment = ($working_days - $holiday_unpaid_total) / $working_days;

							$holiday_allowance_thisyear = ceil ($unpaid_adjustment * $holiday_allowance_thisyear);
							
							$length = round ((($user_user_ended - $user_user_added) / 31556908.8), 2);
							
							$holiday_allowance = (ceil($length * $user_holidays * 2) / 2);
							
							// Temporary
							// if ($length > 1) {
							// $holiday_allowance_thisyear = $user_holidays;
							// } else {
							// $holiday_allowance_thisyear = ceil ($length * $user_holidays * 2) / 2;
							// }
							// End Temporary
							
							$holiday_year_remaining = $holiday_allowance_thisyear - $holiday_paid_total;
							
							$array = array($length,$user_holidays,$holiday_allowance,$holiday_allowance_thisyear,$holiday_paid_total,$holiday_unpaid_total,$study_leave_total,$jury_service_total,$toil_service_total,$holiday_year_remaining,$listadd,$listend,$user_name_first, $user_name_second,$unpaid_adjustment);
	
							return $array;
	
}

function WorkingDays($year) {
	
	GLOBAL $conn;
	
	$year = intval($year);
	
	$sql = "SELECT COUNT(bankholidays_id) FROM intranet_user_holidays_bank WHERE bankholidays_year = $year";
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



function HolidaySchedule($year,$user_usertype_current,$working_days,$beginnning_of_this_year,$beginnning_of_next_year) {

GLOBAL $conn;

						echo "<h2 id=\"holidaysthisyear\">Holidays in $year</h2>";

						echo "<p>There were $working_days working days in $year.</p>";

						if ($user_usertype_current < 3) { $limit = "AND user_id = $user_id"; } else { unset( $limit );}

						$sql_users = "SELECT user_id, user_name_first, user_name_second FROM intranet_user_details WHERE (
						(user_user_added BETWEEN $beginnning_of_this_year AND $beginnning_of_next_year)
						OR (user_user_ended BETWEEN $beginnning_of_this_year AND $beginnning_of_next_year)
						OR (user_user_added < $beginnning_of_this_year AND (user_user_ended = 0 OR user_user_ended IS NULL))
						) $limit ORDER BY user_name_second";


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
							<td style=\"$bg\"><a href=\"index2.php?page=holiday_approval&amp;showuser=$user_id&year=$_GET[year]#holidaysthisyear\">$user_name_first $user_name_second</a></td>
							<td style=\"$bg\">" . $listadd . "</td>
							<td style=\"$bg\">" . $listend . "</td>
							<td style=\"text-align:right; $bg\">$length</td>
							<td style=\"text-align:right; $bg\">$user_holidays</td>
							<td style=\"text-align:right; $bg\">$holiday_allowance</td>
							<td style=\"text-align:right; $bg\">$holiday_allowance_thisyear</td>
							<td style=\"text-align:right; $bg\">$holiday_paid_total</td>
							<td style=\"text-align:right; $bg\">$holiday_unpaid_total</td>
							<td style=\"text-align:right; $bg\">$study_leave_total</td>
							<td style=\"text-align:right; $bg\">$jury_service_total</td>
							<td style=\"text-align:right; $bg\">$toil_service_total</td>
							<td style=\"text-align:right; $bg\">$holiday_year_remaining</td>
							</tr>";
							
							if ($_GET[showuser] == $user_id) {
							
									$bg = "; background: rgba(100,100,150,0.1)\"";
							
										if ($unpaid_adjustment < 1 && $_GET[showuser] == $user_id) {
											echo "<tr><td colspan=\"13\" style=\"font-style: italic; $bg\">
											$user_name_first took $holiday_unpaid_total unpaid holidays during $year, from a total of $working_days possible working days. Available holiday has therefore been reduced to " . round (100 *  $unpaid_adjustment ) . "% of the total allowance for this year.
											</td></tr>";
										}
							
									$bg = "; background: rgba(100,100,150,0.2)\"";
								
									$sql_totalhols = "SELECT holiday_timestamp, holiday_length, holiday_paid, holiday_assigned FROM intranet_user_holidays WHERE holiday_user = $user_id AND holiday_assigned = $year ORDER BY holiday_timestamp";
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
												else { $holiday_type = "Standard"; $totalhols_count = $totalhols_count + $holiday_length; }
												
												if ($holiday_length == 0.5) { $holiday_type = $holiday_type . " (half day)"; }

													echo "<tr><td colspan=\"4\" style=\"$bg\">" . date ( "l, j F Y", $array_totalhols['holiday_timestamp'] ) . "</td>";
													echo "<td colspan=\"3\" style=\"$bg\">$holiday_type</td>";
														
														
														echo "
														<td style=\"text-align: right; $bg\">$totalhols_count</td>
														<td style=\"$bg\" colspan=\"5\"></td>
														";
													
												echo "</tr>";
												
												}
												
												if ($_GET[showuser] == $user_id) { $bg = "; background: rgba(100,100,150,0.35)\""; } else { unset($bg); }
												
												echo "<tr><td colspan=\"7\" style=\"$bg\"><strong>Total</strong></td><td style=\"text-align: right; $bg\"><strong>$totalhols_count</strong></td><td colspan=\"5\" style=\"$bg\"></th></tr>";
											
											
										} else {
										
												echo "<tr><td></td><td colspan=\"12\">No holidays found for $year</td></tr>";
										
										}
										
								unset($bg);
								
								
							}


						}

						echo "</table>";








}


function ChangeHolidays($year) {
	
		$year_before = $year - 1;
		$year_after = $year + 1;
		
		echo "<table><tr><td rowspan=\"4\">Change selected holidays</td>
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
		<td><input type=\"radio\" value=\"$year_before\" name=\"approve\" />&nbsp;Assign to " . $year_before . "</td>
		<td><input type=\"radio\" value=\"$year\" name=\"approve\" />&nbsp;Assign to " . $year . "</td>
		<td><input type=\"radio\" value=\"$year_after\" name=\"approve\" />&nbsp;Assign to " . $year_after . "</td>
		<td><input type=\"hidden\" value=\"$_COOKIE[user]\" name=\"user_id\" /><input type=\"submit\" value=\"Submit\" /></td>
		</tr>
		</table>
		";
		
}

function TenderList() {

		GLOBAL $conn;
		GLOBAL $user_usertype_current ;
		
		$submitted_total = 0;
		$successful_total = 0;

		$nowtime = time();

		if ($_GET[detail] == "yes") { $detail = "yes"; }

		if (intval($_GET[tender_submitted]) == 1) {
			$sql = "SELECT * FROM intranet_tender WHERE tender_submitted = 1 ORDER BY tender_date DESC";
			echo "<h2>List of all submitted tenders</h2>";
		} elseif (intval($_GET[tender_pending]) == 1) {
			$sql = "SELECT * FROM intranet_tender WHERE tender_submitted = 1 AND (tender_result = 0 OR tender_result IS NULL) ORDER BY tender_date DESC";
			echo "<h2>List of all pending tenders</h2>";
		} else {
			$sql = "SELECT * FROM intranet_tender ORDER BY tender_date DESC";
			echo "<h2>List of all tenders</h2>";
		}
		
		$result = mysql_query($sql, $conn) or die(mysql_error());

				echo "<div class=\"submenu_bar\">";
							
					if (intval($_GET[tender_submitted]) == 1 OR intval($_GET[tender_pending]) == 1) {
						echo "<a href=\"index2.php?page=tender_list\" class=\"submenu_bar\">List All Tenders</a>";
					}
				
				
					if (intval($_GET[tender_submitted]) != 1) {
						echo "<a href=\"index2.php?page=tender_list&amp;tender_submitted=1\" class=\"submenu_bar\">List Only Submitted Tenders</a>";
					}
					
					if (intval($_GET[tender_pending]) != 1) {
						echo "<a href=\"index2.php?page=tender_list&amp;tender_pending=1\" class=\"submenu_bar\">List Only Pending Tenders</a>";
					}
					
					if ($user_usertype_current > 3) {
						echo "<a href=\"index2.php?page=tender_edit\" class=\"submenu_bar\">Add Tender <img src=\"images/button_new.png\" alt=\"Add New Tender\" /></a>";
					}

					
				echo "</div>";
				
				


				if (mysql_num_rows($result) > 0) {
				
				$time_line = NULL;

			
				while ($array = mysql_fetch_array($result)) {
				
				$tender_id = $array['tender_id'];
				$tender_name = $array['tender_name'];
				if ($array['tender_type']) { $tender_type = "<br />". $array['tender_type']; }
				if ($array['tender_procedure']) { $tender_type = $tender_type . "<br /><span class=\"minitext\">". $array['tender_procedure'] . "</span>"; }
				$tender_date = $array['tender_date'];
				$tender_client = $array['tender_client'];
				$tender_description = nl2br($array['tender_description']);
				$tender_keywords = $array['tender_keywords'];
				$tender_submitted = $array['tender_submitted'];
				$tender_result = $array['tender_result'];
				
				if ($tender_submitted == 1) { $submitted_total++; }
				if ($tender_result == 1) { $successful_total++; }
				
				if ((($tender_date - $nowtime) < 86400) && (($tender_date - $nowtime) > 0)) {
					$style = "style=\"background: rgba(255,130,0,0.5); border: solid 1px rgba(255,130,0,0.8);\"";
				} elseif ((($tender_date - $nowtime) < 604800) && (($tender_date - $nowtime) > 0)) {
					$style = "style=\"background: rgba(255,130,0,0.5); border: solid 1px rgba(255,130,0,0.8);\"";
				} elseif ($tender_date > time()) {
					$style = "style=\"background: rgba(175,213,0,0.3); border: solid 1px rgba(175,213,0,0.8);\"";
				} elseif ($tender_submitted == 1 && $tender_result == 1) {
					$style = "style=\"background: rgba(0,0,255,0.3); border: solid 1px rgba(0,0,255,0.8);\"";
				} elseif ($tender_submitted == 1 && $tender_result == 2) {
					$style = "style=\"background: rgba(255,0,0,0.3); border: 1px solid rgba(255,0,0,0.8);\"";
				} elseif ($tender_submitted == 1 && $tender_result == 0) {
					$style = "style=\"background: rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.8);\"";
				} elseif ($tender_date < time()) {
					$style = "style=\"background: rgba(0,0,0,0.1); border: solid 1px rgba(0,0,0,0.25); color: #ccc;\"";

				} else {
					$style = "style=\"background: rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.8);\"";
				}
				
				if ($tender_date > time()) {
					$deadline = " (" . DeadlineTime($tender_date - $nowtime) . ")";
				} else {
					unset($deadline);					
				}
				
				if (($nowtime > $tender_date) && ($nowtime < $time_line)) { echo "<div class=\"bodybox\" style=\"background: white; color: rgba(255,0,0,1); border: solid 1px rgba(255,0,0,0.8); font-size: 2em;\"><strong><span class=\"minitext\">Today is</span><br />" . TimeFormat($nowtime) . "</strong></div>"; }
										
				
				echo "<div class=\"bodybox\" $style><a href=\"index2.php?page=tender_edit&tender_id=$tender_id\" style=\"float: right; margin: 0 0 5px 5px;\"><img src=\"images/button_edit.png\" alt=\"Edit Tender\" /></a><p><strong><a href=\"index2.php?page=tender_view&amp;tender_id=$tender_id\">$tender_name</a></strong>$tender_type</p>";
				echo "<p>Deadline: ". date("d M Y",$tender_date) . $deadline . "<br /><span class=\"minitext\">" . $tender_client . "</span></p>";
				
				$time_line = $tender_date;
				
				echo "</div>";

				}

				} else {

				echo "There are no tenders on the system.";

				}
				
				if ($submitted_total > 0 && (intval($_GET[tender_pending]) != 1)) {
				
					$success_rate = number_format ( 100 * ($successful_total / $submitted_total), 0 );
					
					echo "<div class=\"bodybox\"><p><strong>Statistics</strong></p><p>You have submitted $submitted_total tenders with a " . $success_rate . "% success rate.</p></div>";
					
				}
				
}

function NotAllowed() {
	
	echo "<h1>Access Denied</h1><p>You have insufficient privileges to view this page.</p>";
	
}

function NewPage() {

	GLOBAL $pdf;
	$pdf->addPage();
	$current_y = $pdf->GetY();
	$new_y = $current_y + 50;
	$pdf->SetY($new_y);

}


function Paragraph ($input) {
	
	GLOBAL $pdf;
	GLOBAL $format_font;
	
	$text_array = explode ("\n",$input);
	
	$header = 1;
	
	foreach ($text_array AS $para ) {
		
		$para = trim($para);
		
		
		
		$pdf->SetTextColor(0);
		if (substr($para,0,3) == "-- ") {
			$pdf->SetFont('ZapfDingbats','',4);
			$para = trim($para,"-- ");
			$pdf->SetX(0);
			$pdf->Cell(35,4,'n',0,0,R,0);
			$pdf->SetX(35);
			$pdf->SetFont($format_font,'',10);
			$pdf->MultiCell(145,4,$para,0,L);
		} elseif (substr($para,0,2) == "- ") {
			$pdf->SetFont('ZapfDingbats','',5);
			$para = trim($para,"- ");
			$pdf->SetX(0);
			$pdf->Cell(30,4,'l',0,0,R,0);
			$pdf->SetX(30);
			$pdf->SetFont($format_font,'',10);
			$pdf->MultiCell(145,4,$para,0,L);
		} elseif (substr($para,0,1) == "|") {
			if ($header == 1) { $pdf->SetLineWidth(0.5); $header = 0; } else { $pdf->SetLineWidth(0.2); }
			$row = explode ("|",$para);
			$delete = array_shift($row);
			foreach ($row AS $cell ) {
				$cell_width = 150 / count($row);
				$pdf->SetFont($format_font,'',10);
				$pdf->Cell($cell_width,7,$cell,1,0,L,0);
				$pdf->SetFont($format_font,'',10);
			}
			$pdf->Ln(7);
			$pdf->SetX(25);
		} else {
		$pdf->SetX(25);
		$pdf->SetFont($format_font,'',10);
		$pdf->MultiCell(150,4,$para,0,L);
		}
		
		
	
	}
	
	
}


function UpDate ($qms_date) {
						
						GLOBAL $pdf;
						
						$current_x = $pdf->GetX();
						$current_y = $pdf->GetY();
						$new_y = $pdf->GetY() + 2;
					
						$pdf->SetXY(180,$new_y);
						$pdf->SetTextColor(180);
						$pdf->SetDrawColor(180);
						$pdf->SetFont('Helvetica','',5);
						$pdf->Cell(0,2,$qms_date,0,0);
						$pdf->SetTextColor(0);
						
						$pdf->SetXY($current_x,$current_y);
					
					}
					
function AddBullets($input) {
	
		GLOBAL $pdf;
		
		if (substr($input,2) == "- ") {
			
			
		} else {
			
			
		}
	
	
}

function ProjectData($proj_id, $type) {
	
	GLOBAL $conn;
	$proj_id = intval($proj_id);
	$sql_proj = "SELECT * FROM intranet_projects WHERE proj_id = $proj_id LIMIT 1";
	$result_proj = mysql_query($sql_proj, $conn) or die(mysql_error());
	$array_proj = mysql_fetch_array($result_proj);
	
	if ($type = "name") {	
	$output = $array_proj['proj_num'] . " " . $array_proj['proj_name'];
	}
	
	return $output;
	
}

function ChecklistDate($proj_id, $checklist_item) {
	
	GLOBAL $conn;
	$proj_id = intval($proj_id);
	$checklist_item = intval (trim ($checklist_item,"#") );
	if ($proj_id > 0 AND $checklist_item > 0){
		
		$sql_checklist_date = "SELECT checklist_date FROM intranet_project_checklist WHERE checklist_project = $proj_id AND checklist_item = $checklist_item ORDER BY checklist_date DESC LIMIT 1";
		$result_checklist_date = mysql_query($sql_checklist_date, $conn) or die(mysql_error());
		$array_checklist_date = mysql_fetch_array($result_checklist_date);
		
		if ($array_checklist_date['checklist_date'] != "0000-00-00" && $array_checklist_date['checklist_date'] != NULL) {
			$output = strtotime( $array_checklist_date['checklist_date'] );
			$output = date("j F Y",$output);
		}
		
		return $output;
	
	}
	
}

function FindClause($qms_text) {
	
		GLOBAL $conn;
		if (strpbrk($qms_text,"^")) {
		
			$text_section = explode("^",$qms_text);
			$text_section = explode(" ",$text_section[1]);
			$text_section = intval($text_section[0]);
			if ($text_section > 0)
			$sql_checklist_ref = "SELECT qms_id,qms_toc1, qms_toc2,qms_toc3,qms_toc4 FROM intranet_qms WHERE qms_id = $text_section";
			$result_checklist_ref = mysql_query($sql_checklist_ref, $conn) or die(mysql_error());
			$array_checklist_ref = mysql_fetch_array($result_checklist_ref);
			$qms_id = $array_checklist_ref['qms_id'];
			
			$qms_clause = $array_checklist_ref['qms_toc1'];
			if ($array_checklist_ref['qms_toc2'] > 0) { $qms_clause = $qms_clause . "." . $array_checklist_ref['qms_toc2']; }
			if ($array_checklist_ref['qms_toc3'] > 0) { $qms_clause = $qms_clause . "." . $array_checklist_ref['qms_toc3']; }
			if ($array_checklist_ref['qms_toc4'] > 0) { $qms_clause = $qms_clause . "." . $array_checklist_ref['qms_toc4']; }
			
			$finder = "^" . $qms_id;
			
			$qms_text = str_replace($finder,$qms_clause,$qms_text);
			
		}
	
	return $qms_text;
	
}

function ClauseCrossReference($qms_text) {
	
		$test = 0;
	
		while ($test != 1) {
			
			if (substr_count($qms_text,"^") > 0) { 
				$qms_text = FindClause($qms_text);
				$test = 0;
			} else {
				$test = 1;
			}
			
		}

		return $qms_text;
		
}

function DrawingStatusDropdown ($current_status,$variable_name) {
	
	$drawing_status_array = array("","S1","S2","S3","S4");
	sort($drawing_status_array);


echo "<select name=\"$variable_name\">";
		foreach ($drawing_status_array AS $drawing_status_list) {
		if ($drawing_status_list == $current_status) { $select = "selected=\"selected\""; } else { unset($select); }
		echo "<option value=\"$drawing_status_list\" $select>$drawing_status_list</option>";
	}
echo "</select>";

	
}

function DeadlineTime($time) {
	
	if ($time < 86400) {
		
		$output = round ($time / 3600) . " hours";
		
	} elseif ($time < 129600) {
		
		$output = round ($time / 86400) . " day";
		
		
	} elseif ($time < 1209600) {
		
		$output = round ($time / 86400) . " days";
		
	} elseif ($time < 4838400) {
		
		$output = round ($time / 604800) . " weeks";
	
	} else {
		
		$output = round ($time / 18396000) . " months";
	
	}
	
	return $output;
	
}

function ListProjectJournalEntries($proj_id) {
	
		global $conn;

					print "<h2>Journal Entries</h2>";

					$sql = "SELECT * FROM intranet_projects_blog, intranet_projects, intranet_user_details WHERE blog_proj = proj_id AND proj_id = '$proj_id' AND blog_user = user_id order by blog_date DESC";
					$result = mysql_query($sql, $conn) or die(mysql_error());
					$result_project = mysql_query($sql, $conn) or die(mysql_error());
					$array_project = mysql_fetch_array($result_project);
					$proj_num = $array_project['proj_num'];
					$proj_name = $array_project['proj_name'];
					$user_name_first = $array_project['user_name_first'];
					$user_name_second = $array_project['user_name_second'];
					$user_id = $array_project['user_id'];

					// Include a bar to navigate through the pages

							print "<p class=\"submenu_bar\">";

							$items_to_view = 10;

							if ($_GET[limit] == NULL) {$limit = 0; } else { $limit = $_GET[limit]; }
							$total_items = mysql_num_rows($result);
							$page_prev = $limit - $items_to_view;
							$page_next = $limit + $items_to_view;
							
							if ($limit > 0) { print "<a href=\"index2.php?page=project_blog_list&amp;proj_id=$proj_id&amp;limit=$page_prev\" class=\"submenu_bar\">Previous Page</a>"; }
							if ($page_next < $total_items) { print "<a href=\"index2.php?page=project_blog_list&amp;proj_id=$proj_id&amp;limit=$page_next\" class=\"submenu_bar\">Next Page</a>"; }
							print "</p>";


					$nowtime = time();

					if (mysql_num_rows($result) > 0) {

					print "<table summary=\"List of Journal Entries for $proj_num $proj_name\">";

					$counter = 0;
					$title = NULL;
					$type = 0;

					while ($array = mysql_fetch_array($result)) {

							$blog_id = $array['blog_id'];
							$blog_title = $array['blog_title'];
							$blog_date = $array['blog_date'];
							$blog_type = $array['blog_type'];
							$blog_user = $array['blog_user'];
							$blog_user_name_first = $array['user_name_first'];
							$blog_user_name_second = $array['user_name_second'];
						
						if ($blog_type == "phone") { $blog_type_view = "Telephone Call"; $type++; }
						elseif ($blog_type == "filenote") { $blog_type_view = "File Note"; $type++; }
						elseif ($blog_type == "meeting") { $blog_type_view = "Meeting Note"; $type++; }
						elseif ($blog_type == "email") { $blog_type_view = "Email Message"; $type++; }
						else { $blog_type_view = NULL; $type = 0; }
						
						$blog_type_list = array("phone","filenote","meeting","email");
						
					 if ($counter >= $limit AND $counter < $page_next) {
							$counter_title++;
							print "<tr>";
							print "<td>$type.</td><td><a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id&amp;proj_id=$proj_id\">".$blog_title."</a>&nbsp;<a href=\"pdf_journal.php?blog_id=$blog_id\"><img src=\"images/button_pdf.png\" /></a></td>";
							print "<td style=\"width: 20%;\"><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date\">".TimeFormat($blog_date)."</a></td>";
							print "<td><a href=\"index2.php?page=user_view&amp;user_id=$user_id\">".$blog_user_name_first."&nbsp;".$blog_user_name_second."</a></td>";
							print "<td style=\"width: 20%;\"><span class=\"minitext\">$blog_type_view</span></td>";
							print "</tr>";
					}

					$title = $blog_type;
					$counter++;

					}


					print "</table>";

					} else {

					print "<p>There are no journal entries on the system for this project.</p>";

					}

}

function SearchTerms($search_text,$search_field) {
		$counter = 0;
		$max_count = count($search_text);
		while($counter < $max_count) {
		if ($counter > 0) { $searching_blog = $searching_blog." AND $search_field LIKE "; }
		$searching_blog = $searching_blog."'%".$search_text[$counter]."%'";
		$counter++;
		}
		$searching_blog = "$search_field LIKE ".$searching_blog;
		return($searching_blog);
}		

function AlertBoxShow($user_id) {
	
		global $conn;
		$sql = "SELECT * FROM intranet_alerts WHERE alert_timestamp < " . time() . " AND alert_user = " . $user_id . " AND (alert_status = 0 OR alert_status = NULL) ORDER BY alert_timestamp DESC";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		if (mysql_num_rows($result) > 0) {
			echo "<div>";
			while ($array = mysql_fetch_array($result)) {
				$alert_id = $array['alert_id'];
				$alert_category = $array['alert_category'];
				$alert_message = $array['alert_message'];
				echo "<div class=\"warning\" style=\"height: 160px;\" id=\"target_" . $alert_id . "\"><form><input type=\"checkbox\" value=\"" . $alert_id . "\" class=\"alert_delete\" style=\"float: right; margin: 5px 5px 10px 10px;\" /></form><p><strong>" . $alert_category . "</strong></p>" . $alert_message . "</div>";
			}
			echo "</div>";
		}
}

function AlertBoxInsert($user_id,$alert_category,$alert_message,$alert_entryref,$snoozetime,$verbose) {
	
		global $conn;
		
		$verbose = intval($verbose);
		
		$sql = "SELECT * FROM intranet_alerts WHERE alert_timestamp > " . (time() - $snoozetime) . " AND alert_user = " . $user_id . " AND alert_category = '" . $alert_category . "' AND alert_entryref = " . $alert_entryref . " LIMIT 1";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		if (mysql_num_rows($result) == 0) {
			$sql_add = "INSERT INTO intranet_alerts (alert_id, alert_user, alert_category, alert_message, alert_timestamp, alert_status, alert_entryref) VALUES (NULL, " . $user_id . ",'" . $alert_category . "','" . $alert_message . "'," . time() . "," . $verbose . ", " . $alert_entryref . ")";
			$result_add = mysql_query($sql_add, $conn) or die(mysql_error());
		}
}

function CheckOutstandingTasks($user_id) {
	
		global $conn;

			$futuretime = time() - 43200;
			$sql3 = "SELECT tasklist_id FROM intranet_tasklist WHERE tasklist_person = '" . $user_id . "' AND tasklist_percentage < '100' ";
			$sql4 = "SELECT * FROM intranet_tasklist WHERE tasklist_person = '$_COOKIE[user]' AND tasklist_percentage < '100' AND tasklist_due < $futuretime AND tasklist_due > 0 ";
			$result3 = mysql_query($sql3, $conn) or die(mysql_error());
			$result4 = mysql_query($sql4, $conn) or die(mysql_error());
			$tasks_outstanding = mysql_num_rows($result3);
			$tasks_overdue = mysql_num_rows($result4);
			
		if ($tasks_overdue > 0 AND substr($_GET[page],0,8) != "tasklist") {
			
			if ($tasks_overdue > 1) { $tasks_plural = "tasks"; } else { $tasks_plural = "task"; }
			
			$outstanding = 1;
			$outstanding_tasks =  "<p class=\"body\">You have ".$tasks_overdue." ".$tasks_plural." outstanding. <a href=\"index2.php?page=tasklist_view&amp;subcat=user\">Click here</a> to view current task list.</p>";
			
			AlertBoxInsert($_COOKIE[user],"Tasks",$outstanding_tasks,0,86400);
			
		}

}

function CheckExpenses() {
	
	global $conn;

			

						$sql5 = "SELECT ts_expense_id FROM intranet_timesheet_expense WHERE ts_expense_verified = 0";
						$result5 = mysql_query($sql5, $conn) or die(mysql_error());
						$expenses_overdue = mysql_num_rows($result5);
						
					if ($expenses_overdue > 0 AND substr($_GET[page],0,17) != "timesheet_expense") {
						
						if ($expenses_overdue > 1) { $expenses_plural = "expenses claims"; } else { $expenses_plural = " expenses claim"; }
						
						$outstanding = 1;
						$outstanding_expenses = "<p class=\"body\">You have ".$expenses_overdue."&nbsp;".$expenses_plural." awaiting validation. <a href=\"index2.php?page=timesheet_expense_list\">Click here</a> to view oustanding items.</p>";
						
						AlertBoxInsert($_COOKIE[user],"Expenses",$outstanding_expenses,0,86400);
					}

}

function CheckFutureTenders() {
	
	global $conn;

		$weeks = 2;
		$seconds = 60 * 60 * 24 * 7 * $weeks;

		if ($user_usertype_current > 2 AND substr($_GET[page],0,6) != "tender") {

						$sql6 = "SELECT * FROM intranet_tender WHERE tender_date > '" . time() . "' AND (tender_date - " . time() . " < $seconds) ORDER BY tender_date";
						$result6 = mysql_query($sql6, $conn) or die(mysql_error());
						$tenders_soon = mysql_num_rows($result6);

						while ($array6 = mysql_fetch_array($result6)) {
							$tender_id = $array6['tender_id'];
							$tender_name = $array6['tender_name'];
							$tender_date = $array6['tender_date'];
							$days_to_go = ($tender_date - $nowtime) / 86400;
							$days_to_go = round($days_to_go);
							$outstanding_tender = "<p><a href=\"index2.php?page=tender_view&amp;tender_id=$tender_id\">$tender_name</a><br /><i> ".TimeFormatDetailed($tender_date)."&nbsp;(".$days_to_go."&nbsp;days to go)</i></p>";
							
							AlertBoxInsert($_COOKIE[user],"Tenders",$outstanding_tender,$tender_id,86400);

						}		
		}

}

function CheckCheckList() {
	
		global $conn;
		
		$today_date = date("Y-m-d", time());

		$sql5 = "SELECT * FROM intranet_projects, intranet_project_checklist LEFT JOIN intranet_project_checklist_items ON checklist_item = item_id  WHERE proj_id = checklist_project AND checklist_deadline = '$today_date' ORDER BY item_group, item_order, checklist_date, item_name";
		$result5 = mysql_query($sql5, $conn) or die(mysql_error());
		if (mysql_num_rows($result5) > 0) {
			while ($array5 = mysql_fetch_array($result5)) {
			$checklist_today = "<p><a href=\"index2.php?page=project_checklist&amp;proj_id=" . $array5['proj_id'] . "#" . $array5['item_id'] . "\">" . $array5['item_name'] . "</a></td><td>" . $array5['proj_num'] . " " . $array5['proj_name'] . "</p>";
			}
			AlertBoxInsert($_COOKIE[user],"Checklist",$checklist_today,$array5['item_id'],86400);
		}
	
}

function CheckInvoicesToBeIssued() {
	
	global $conn;
	
			$today_day = date("j",time()); $today_month = date("n",time()); $today_year = date("Y",time());
		$day_begin = mktime(0,0,0,$today_month,$today_day,$today_year);
		$day_end = $day_begin + 86400;
		$sql3 = "SELECT invoice_id, invoice_ref, proj_name FROM intranet_timesheet_invoice, intranet_projects WHERE `invoice_date` BETWEEN '$day_begin' AND '$day_end' AND `proj_rep_black` = '$_COOKIE[user]' AND `proj_id` = `invoice_project` ORDER BY `invoice_ref` ";
		$result3 = mysql_query($sql3, $conn) or die(mysql_error());
		if (mysql_num_rows($result3) > 0) {
			while ($array3 = mysql_fetch_array($result3)) {
			$invoicemessage = "<p<a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=" . $array3['invoice_id'] . "\">" . $array3['invoice_ref'] . "</a></td><td>" . $array3['proj_name'] . "</p>";
			AlertBoxInsert($_COOKIE[user],"Invoices To Be Issued",$invoicemessage,$array3['invoice_id'],86400);
			}
			
		}
	
}

function CheckInvoicesOverdue($user_id) {
	
		global $conn;

		$sql4 = "SELECT invoice_id, invoice_ref, proj_name, invoice_due FROM intranet_timesheet_invoice, intranet_projects WHERE `invoice_due` < " .time()." AND `proj_rep_black` = $user_id AND `proj_id` = `invoice_project` AND `invoice_paid` = 0 AND `invoice_baddebt` != 'yes' ORDER BY `invoice_due` ";
		$result4 = mysql_query($sql4, $conn) or die(mysql_error());
		if (mysql_num_rows($result4) > 0) {
			$invoiceduemessage = "<table>";
			while ($array4 = mysql_fetch_array($result4)) {
			$invoiceduemessage = "<p><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=" . $array4['invoice_id'] . "\">" . $array4['invoice_ref'] . "</a></td><td>" . $array4['proj_name'] . "</td><td>Due: <a href=\"index2.php?page=datebook_view_day&amp;time=" . $array4['invoice_due'] . "\"> " . TimeFormat($array4['invoice_due']) . "</a></p>";
			AlertBoxInsert($_COOKIE[user],"Invoices Overdue",$invoicemessage,$array3['invoice_id'],86400);
			}
		}
		
}

function CheckOutstandingTimesheets($user_id) {
	
	global $conn;
	
			$timesheetcomplete = TimeSheetHours($user_id,"");
		
		
		if ( $_COOKIE[timesheetcomplete] < 75) {
		
			$timesheetaction = "<p>Your timesheets are only " . $timesheetcomplete . "% complete - <a href = \"popup_timesheet.php\">please fill them out</a>. If your timesheet drops below " . $settings_timesheetlimit . "% complete, you will not be able to access the intranet.</p>";
			
			AlertBoxInsert($_COOKIE[user],"Timesheets",$timesheetaction,0,86400);
		
		}
	
}

function CheckTelephoneMessages($user_id) {
	
	global $conn;
	
		if ($_COOKIE[phonemessageview] > 0 OR $_COOKIE[phonemessageview] == NULL) {
		$sql = "SELECT * FROM intranet_phonemessage WHERE message_for_user = '$user_id' AND message_viewed = 0";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$messages_outstanding = mysql_num_rows($result);
		if ($messages_outstanding > 0) {
			while ($array = mysql_fetch_array($result)) {
				$telephonemessage = "<p>Call from " . $array['message_from_name'] . "";
				if ($array['message_from_name']) { $telephonemessage = $telephonemessage . ", " . $array['message_from_name']; }
				$telephonemessage = $telephonemessage . ". ";
				if ($array['message_text']) { $telephonemessage = $telephonemessage . "<br />Message: " . rtrim($array['message_text'],".") . "."; }
				if ($array['message_from_number']) { $telephonemessage = $telephonemessage . "<br />Number " . $array['message_from_number']; }
				if ($array['message_date']) { $telephonemessage = $telephonemessage . "<br /><i>Taken " . TimeFormat($array['message_date']) . "</i>"; }
				$telephonemessage = $telephonemessage . "</p>";
				AlertBoxInsert($_COOKIE[user],"Telephone Message",$telephonemessage,0,86400);
			}
		}
		}
	
}

function AlertDelete($alert_id, $user_id) {

	global $conn;
	
			
			if (intval($alert_id) > 0 && intval($user_id) > 0) {
		
				$sql_update = "UPDATE intranet_alerts SET alert_status = 1, alert_updated = " . time() . " WHERE alert_id = " . $alert_id . " AND alert_user = " . $user_id . " LIMIT 1";
		
				$result = mysql_query($sql_update, $conn) or die(mysql_error());
				
			}
			
}

function AlertsList($user_id) {

	global $conn;
	
	$user_id = intval($user_id);

		$sql = "SELECT * FROM intranet_alerts WHERE alert_user = $user_id ORDER BY alert_timestamp DESC";
		
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result) > 0) {
			
			echo "<table>";
			
			echo "<tr><th>ID</th><th style=\"width: 20%;\">Subject</th><th style=\"width: 50%;\">Content</th><th style=\"text-align: right;\">Date</th><th style=\"text-align: right;\">Updated</th></tr>";
		
			while ($array = mysql_fetch_array($result)) {
			
				echo "<tr><td>" . $array['alert_id'] . "</td><td>" . $array['alert_category'] . "</td><td>" . $array['alert_message'] . "</td><td style=\"text-align: right;\">" . TimeFormat($array['alert_timestamp'])  . "</td><td style=\"text-align: right;\">" . TimeFormat($array['alert_updated'])  . "</td></tr>";
			
			}
			
			echo "</table>";
			
		
		} else {
			
			echo "<p>No log entries found.</p>";
			
		}

}