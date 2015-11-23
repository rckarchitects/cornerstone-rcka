<?php

// Perform the top-of-page security check

include "inc_files/inc_checkcookie.php";

// Preferences

include "secure/prefs.php";

// Check for any outstanding timesheets
	
		$timesheetcomplete = TimeSheetHours($_COOKIE[user],"");
		
		
		if ( $_COOKIE[timesheetcomplete] < 75) { 
		
		$timesheetaction = "<h1 class=\"heading_alert\">Timesheets</h1><p>Your timesheets are only " . $timesheetcomplete . "% complete - <a href = \"popup_timesheet.php\">please fill them out</a>!</p><p>If your timesheet drops below " . $settings_timesheetlimit . "% complete, you will not be able to access the intranet.<br / ><a href=\"index2.php?page=timesheet_incomplete_list\">Click here for a list of incomplete days</a>.";
		// echo "<a href=\"index2.php?page=timesheets_incomplete_list\">Click here</a> to view your incomplete days</a>";
		echo "</p>"; 
		
		}
		
		
		// if ($timesheet_percentage_complete < $settings_timesheetlimit) { header("Location: popup_timesheet.php"); }

// Check for any outstanding telephone messages
	
	if ($_COOKIE[phonemessageview] > 0 OR $_COOKIE[phonemessageview] == NULL) {
		$sql2 = "SELECT * FROM intranet_phonemessage WHERE message_for_user = '$_COOKIE[user]' AND message_viewed = 0";
		$result2 = mysql_query($sql2, $conn) or die(mysql_error());
		$messages_outstanding = mysql_num_rows($result2);
		if ($messages_outstanding > 0) {
			$phonemessageview = $_COOKIE[phonemessageview] + 1;
			setcookie("phonemessageview",$phonemessageview, time()+3600);
		}
	}
	
// Check for any invoices due to be issued today

		$today_day = date("j",time()); $today_month = date("n",time()); $today_year = date("Y",time());
		$day_begin = mktime(0,0,0,$today_month,$today_day,$today_year);
		$day_end = $day_begin + 86400;
		$sql3 = "SELECT invoice_id, invoice_ref, proj_name FROM intranet_timesheet_invoice, intranet_projects WHERE `invoice_date` BETWEEN '$day_begin' AND '$day_end' AND `proj_rep_black` = '$_COOKIE[user]' AND `proj_id` = `invoice_project` ORDER BY `invoice_ref` ";
		$result3 = mysql_query($sql3, $conn) or die(mysql_error());
		if (mysql_num_rows($result3) > 0) {
			$invoicemessage = "<table>";
			while ($array3 = mysql_fetch_array($result3)) {
			$invoicemessage = $invoicemessage . "<tr><td><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=" . $array3['invoice_id'] . "\">" . $array3['invoice_ref'] . "</a></td><td>" . $array3['proj_name'] . "</td></tr>";
			}
			$invoicemessage = $invoicemessage . "</table>";
		}
		
// Check for any invoices overdue

		$sql4 = "SELECT invoice_id, invoice_ref, proj_name, invoice_due FROM intranet_timesheet_invoice, intranet_projects WHERE `invoice_due` < " .time()." AND `proj_rep_black` = '$_COOKIE[user]' AND `proj_id` = `invoice_project` AND `invoice_paid` = 0 AND `invoice_baddebt` != 'yes' ORDER BY `invoice_due` ";
		$result4 = mysql_query($sql4, $conn) or die(mysql_error());
		if (mysql_num_rows($result4) > 0) {
			$invoiceduemessage = "<table>";
			while ($array4 = mysql_fetch_array($result4)) {
			$invoiceduemessage = $invoiceduemessage . "<tr><td><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=" . $array4['invoice_id'] . "\">" . $array4['invoice_ref'] . "</a></td><td>" . $array4['proj_name'] . "</td><td>Due: <a href=\"index2.php?page=datebook_view_day&amp;time=" . $array4['invoice_due'] . "\"> " . TimeFormat($array4['invoice_due']) . "</a></td></tr>";
			}
			$invoiceduemessage = $invoiceduemessage . "</table>";
		}
		
// Check for any upcoming holidays

$nowtime = time() - 43200;

		$sql5 = "SELECT user_id, user_name_first, user_name_second, holiday_date, holiday_timestamp, holiday_paid, holiday_length FROM intranet_user_details, intranet_user_holidays WHERE holiday_user = user_id AND holiday_timestamp BETWEEN $nowtime AND " . ($nowtime + (2 * 604800)) ." ORDER BY holiday_timestamp, user_name_second";
		$result5 = mysql_query($sql5, $conn) or die(mysql_error());
		if (mysql_num_rows($result5) > 0) {
			$holidaymessage = "<p>The following people have holidays within the next fortnight:</p>";
			$holidaymessage = $holidaymessage . "<p><ul>";
			$current_date = 0;
			while ($array5 = mysql_fetch_array($result5)) {
			
					if ($current_id != $user_id AND $current_id > 0) {
						$holidaymessage = $holidaymessage . "</li>";
					} 
					
					$user_id = $array5['user_id'];
					$user_name_first = $array5['user_name_first'];
					$user_name_second = $array5['user_name_second'];
					$holiday_timestamp = $array5['holiday_timestamp'];
					$holiday_length = $array5['holiday_length'];
					$holiday_paid = $array5['holiday_paid'];
					$holiday_date = $array5['holiday_date'];
					if ($current_date != $holiday_date) {
						$holidaymessage = $holidaymessage . "<li>" . TimeFormatDay($holiday_timestamp) . ": ";
					} else { 
						$holidaymessage = $holidaymessage . ", ";
					}
					
					if ($holiday_length < 1) { $holiday_length = " (Half Day)"; } else { unset($holiday_length); }
					
					$holidaymessage = $holidaymessage . $user_name_first . " " . $user_name_second . $holiday_length ;
					
					$current_date = $holiday_date;
			}
			$holidaymessage = $holidaymessage . "</li></p></ul>";
		}
		
		
		
		
		
		
		
		

$usercheck = $_POST[usercheck];
$checkform_user = $_POST[checkform_user];

// Check the IP address of the user

include("inc_files/inc_ipcheck.php");

// If there are any actions required, perform them now by including the relevant 'action' file

if ($_POST[action] != "") { include("inc_files/action_$_POST[action].php"); }
elseif ($_GET[action] != "") { include("inc_files/action_$_GET[action].php"); }

// Include the standard header file

include("inc_files/inc_header.php");

// Display an alert box if there are telephone messages outstanding

		if ($messages_outstanding > 0 AND $_GET[page] == NULL AND $phonemessageview < 2) { print "<body onload=\"PhoneMessageAlert()\">"; }
		else { print "<body>"; }

// Begin setting out the page

$logo = "skins/" . $settings_style . "/images/logo.png";

if (file_exists($logo)) {
		print "<div id=\"maintitle\"><img src=\"$logo\" alt=\"$settings_name\" /></div>";
} else {
		print "<div id=\"maintitle\">$settings_name</div>";	
}

    print "<div id=\"mainpage\">";

// Column Left

    print "<div id=\"column_left\">";
    include("inc_files/inc_col_left_1.php");
    print "</div>";
	
// Column Right

    print "<div id=\"column_right\">";
	
	// The following bit selects the appropriate right-hand column for the chosen page using the $_GET[page] variable, and defaults to the default version if  there is no page-specific menu
	
	if (substr($_GET[page],0,8) == "contacts") {
		include("inc_files/inc_col_right_contacts.php");
	} elseif (substr($_GET[page],0,7) == "drawing") {
		include("inc_files/inc_col_right_drawings.php");
	} elseif (substr($_GET[page],0,9) == "timesheet") {
		include("inc_files/inc_col_right_timesheet.php");
	} elseif (substr($_GET[page],0,7) == "project" AND $_GET[proj_id] != NULL) {
		include("inc_files/inc_col_right_project.php");
	} else {
	    include("inc_files/inc_col_right_1.php");
    }
	print "</div>";
	

    
//Column Centre

    print "<div id=\"column_centre\">";
	
	$displaytime = time() + 30; //86400;
	
	if ($timesheetaction != NULL) { echo $timesheetaction; }
	
	if ($invoiceduemessage != "" AND $_GET[page] == NULL AND $_COOKIE[invoiceduemessage] == NULL AND $_POST[action] != "invoice_due_setcookie") { echo "<h1 class=\"heading_alert\">Invoices Overdue&nbsp;</h1>$invoiceduemessage<form action=\"index2.php\" method=\"post\"><input type=\"hidden\" value=\"".time()."\" name=\"invoiceduemessage\" /><input type=\"hidden\" name=\"action\" value=\"invoice_due_setcookie\" /><input type=\"submit\" value=\"Hide for 24 hours\" /></form>"; }
	
	if ($invoicemessage != "" AND $_GET[page] == NULL) { echo "<h1 class=\"heading_alert\">Invoices To Be Issued Today</h1>$invoicemessage"; }
	
	if ($holidaymessage != "" AND $_GET[page] == NULL) { echo "<h1 class=\"heading_alert\">Holidays</h1>$holidaymessage"; }
	
	if ($alertmessage != "") { print "<h1 class=\"heading_alert\">Error</h1><p>$alertmessage</p>"; }
	
	if ($actionmessage != "") { print "<h1 class=\"heading_confirm\">Information</h1><p>$actionmessage</p>"; }
	
	
	if ($techmessage != "" AND $settings_showtech == "1" AND $usertype_status == "admin") { print "<h1 class=\"heading_centre\">Support Messages</h1><p>$techmessage</p>"; }

    if ($useraction == "defineuser") {
    include("inc_files/inc_alertuser.php");
    }

    if ($useraction != "defineuser") {
	
		// This includes the "outstanding" section, which alerts users to outstanding actions.
	   if  ($alertmessage == NULL) { include("inc_files/inc_outstanding.php"); }

		// Include the relevant page if the $_GET[page] variable is not blank, otherwise deliver the default page
        if ($page_redirect != NULL) {
			$inc_file = "inc_files/inc_".$page_redirect.".php";
		} elseif ($_GET[page] != NULL) {
            $inc_file = "inc_files/inc_".$_GET[page].".php";
		} elseif ($_POST[page] != NULL) {
            $inc_file = "inc_files/inc_".$_POST[page].".php";
        } else {
            $inc_file = "inc_files/inc_default.php";
        }
	
		
		
		if (file_exists($inc_file)) { include($inc_file); } else { include("inc_files/inc_default.php?$page_variables"); }
		}
	
	// And now print some debugging information if the option is selected within the global options page
	if ($settings_showtech > 0 AND $user_usertype_current > 3) {
	if ($sql_add != "") { print "<p>Database entry:<br /><strong>$sql_add</strong></p>"; }
	print "<h1>Technical Information</h1>";
	print "<p>Included file:<br /><strong>&nbsp;".CleanUp($inc_file)."</strong></p>";
	print "<p>Last Updated:<br /><strong>&nbsp;".date("r",filectime($inc_file))."</strong></p>";
	print "<p>Server IP Address:<br /><strong>&nbsp;".CleanUp($_SERVER["SERVER_ADDR"])."</strong></p>";
	print "<p>Server Name:<br /><strong>&nbsp;".CleanUp($_SERVER["SERVER_NAME"])."</strong></p>";
	print "<p>Client IP Address:<br /><strong>&nbsp;".CleanUp($_SERVER["REMOTE_ADDR"])."</strong></p>";
	print "<p>Script Name:<br /><strong>&nbsp;".CleanUp($_SERVER["SCRIPT_NAME"])."</strong></p>";
	print "<p>Query String:<br /><strong>&nbsp;".CleanUp($_SERVER["QUERY_STRING"])."</strong></p>";
	print "<p>PHP Version:<br /><strong>&nbsp;".phpversion ()."</strong></p>";
	print "<p>Server Software:<br /><strong>&nbsp;".CleanUp($_SERVER["SERVER_SOFTWARE"])."</strong></p>";
	if ($techmessage != NULL) { print "<p>$techmessage</p>"; }
	}

    print "</div>";
	
print "</div>";

print $alertscript;

// Finish with the standard footer

print "<div id=\"mainfooter\">powered by Cornerstone, version 0.1</div>";

print "</body>";
print "</html>";
?>


