<?php

// Check the IP address of the user

include("inc_files/inc_ipcheck.php");

// Perform the top-of-page security check

include "inc_files/inc_checkcookie.php";

$usercheck = $_POST[usercheck];
$checkform_user = $_POST[checkform_user];



// Preferences

include_once "secure/prefs.php";

//Check for hiding the alerts box

if ($_POST[hidealerts]) { setcookie("timesheethide",$_POST[hidealerts]); $hidealerts = "yes"; }

// Check for details of any projects

		
		$proj_details = ProjectTitle();
		$proj_id = $proj_details[0];
		if ($proj_id) {
			$proj_title = "<h1><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_details[1] $proj_details[2]</a></h1>";
		} else {
			unset($proj_title);
		}

// Check for any outstanding timesheets
	
		$timesheetcomplete = TimeSheetHours($_COOKIE[user],"");
		
		
		if ( $_COOKIE[timesheetcomplete] < 75) {
		
			$timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Timesheets</strong></p><p>Your timesheets are only " . $timesheetcomplete . "% complete - <a href = \"popup_timesheet.php\">please fill them out</a>. If your timesheet drops below " . $settings_timesheetlimit . "% complete, you will not be able to access the intranet.</p></div>";
		
		}
		
	
		
		
	//if ($timesheet_percentage_complete < $settings_timesheetlimit) { header("Location: popup_timesheet.php"); }

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
		

		
		
	// Check for any checklist deadlines today
	
		$today_date = date("Y-m-d", time());

		$sql5 = "SELECT * FROM intranet_projects, intranet_project_checklist LEFT JOIN intranet_project_checklist_items ON checklist_item = item_id  WHERE proj_id = checklist_project AND checklist_deadline = '$today_date' ORDER BY item_group, item_order, checklist_date, item_name";
		$result5 = mysql_query($sql5, $conn) or die(mysql_error());
		if (mysql_num_rows($result5) > 0) {
			$checklist_today = "<table>";
			while ($array5 = mysql_fetch_array($result5)) {
			$checklist_today = $checklist_today . "<tr><td style=\"width: 30%;\"><a href=\"index2.php?page=project_checklist&amp;proj_id=" . $array5['proj_id'] . "#" . $array5['item_id'] . "\">" . $array5['item_name'] . "</a></td><td>" . $array5['proj_num'] . " " . $array5['proj_name'] . "</td></tr>";
			}
			$checklist_today = $checklist_today . "</table>";
		} else {
			unset($checklist_today);			
		}
		
		
		
		
		



// If there are any actions required, perform them now by including the relevant 'action' file

if ($_POST[action] != "") { include("inc_files/action_$_POST[action].php"); }
elseif ($_GET[action] != "") { include("inc_files/action_$_GET[action].php"); }

// Include the standard header file

include("inc_files/inc_header.php");

// Display an alert box if there are telephone messages outstanding

		if ($messages_outstanding > 0 AND $_GET[page] == NULL AND $phonemessageview < 2) { echo "<body onload=\"PhoneMessageAlert()\">"; }
		else { echo "<body>"; }

// Begin setting out the page

$logo = "skins/" . $settings_style . "/images/logo.png";

echo "<div id=\"maintitle\">";

		echo "<a href=\"index2.php\" class=\"image\">";

		if (file_exists($logo)) {
				echo "<img src=\"$logo\" alt=\"$settings_name\" style=\"text-align: center;\" />";
		} else {
				echo $settings_name;
		}

		echo "</a>";

echo "</div>";


    echo "<div id=\"mainpage\">";

// Column Left

    echo "<div id=\"column_left\">";
    include("inc_files/inc_col_left_1.php");
    echo "</div>";
	
// Column Right

    echo "<div id=\"column_right\">";
	
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
	echo "</div>";
	

    
//Column Centre

    echo "<div id=\"column_centre\">";
	
	$displaytime = time() + 30; //86400;
	
	
	if ($invoiceduemessage != "" AND $_GET[page] == NULL AND $_COOKIE[invoiceduemessage] == NULL AND $_POST[action] != "invoice_due_setcookie") { echo "<h1 class=\"heading_alert\">Invoices Overdue&nbsp;</h1>$invoiceduemessage<form action=\"index2.php\" method=\"post\"><input type=\"hidden\" value=\"".time()."\" name=\"invoiceduemessage\" /><input type=\"hidden\" name=\"action\" value=\"invoice_due_setcookie\" /><input type=\"submit\" value=\"Hide for 24 hours\" /></form>"; }
	
	if ($invoicemessage != "" AND $_GET[page] == NULL) { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Invoices To Be Issued Today</strong></p>$invoicemessage</div>"; }
	
	if ($checklist_today) { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Checklist Deadlines Today</strong></p>$checklist_today</div>"; }
	
	if ($alertmessage) { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Error</strong></p><p>$alertmessage</p></div>"; }
	
	if ($actionmessage) { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Information</strong></p><p>$actionmessage</p></div>"; }
	
	
	if ($techmessage != "" AND $settings_showtech == "1" AND $usertype_status == "admin") { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Support Messages</strong></p><p>$techmessage</p></div>"; }
	
			// This includes the "outstanding" section, which alerts users to outstanding actions.
	   if  ($alertmessage == NULL) { include("inc_files/inc_outstanding.php"); }
	
	

    if ($useraction == "defineuser") {
		include("inc_files/inc_alertuser.php");
    }
	
	if (($timesheetaction && $_COOKIE[timesheethide] < time() && $hidealerts != "yes") ) { echo "<div>" . $timesheetaction . "</div>"; }
	
// Check for any upcoming holidays

if ($_GET[page] == NULL) { 
	
	$today = TimeFormatDay(time());

	echo "<h1>$today</h1>";

	ListHolidays();
	
	
	
}



    if ($useraction != "defineuser") {
	
	
		// Add the project title, if there is one either through POST or GET
		
		if ($proj_title) { echo $proj_title; }
	


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
	
	// And now echo some debugging information if the option is selected within the global options page
	if ($settings_showtech > 0 AND $user_usertype_current > 3) {
	if ($sql_add != "") { echo "<p>Database entry:<br /><strong>$sql_add</strong></p>"; }
	echo "<h1>Technical Information</h1>";
	echo "<p>Included file:<br /><strong>&nbsp;".CleanUp($inc_file)."</strong></p>";
	echo "<p>Last Updated:<br /><strong>&nbsp;".date("r",filectime($inc_file))."</strong></p>";
	echo "<p>Server IP Address:<br /><strong>&nbsp;".CleanUp($_SERVER["SERVER_ADDR"])."</strong></p>";
	echo "<p>Server Name:<br /><strong>&nbsp;".CleanUp($_SERVER["SERVER_NAME"])."</strong></p>";
	echo "<p>Client IP Address:<br /><strong>&nbsp;".CleanUp($_SERVER["REMOTE_ADDR"])."</strong></p>";
	echo "<p>Script Name:<br /><strong>&nbsp;".CleanUp($_SERVER["SCRIPT_NAME"])."</strong></p>";
	echo "<p>Query String:<br /><strong>&nbsp;".CleanUp($_SERVER["QUERY_STRING"])."</strong></p>";
	echo "<p>PHP Version:<br /><strong>&nbsp;".phpversion ()."</strong></p>";
	echo "<p>Server Software:<br /><strong>&nbsp;".CleanUp($_SERVER["SERVER_SOFTWARE"])."</strong></p>";
	if ($techmessage != NULL) { echo "<p>$techmessage</p>"; }
	}

    echo "</div>";
	
echo "</div>";

echo $alertscript;

// Finish with the standard footer

FooterBar();

echo "</body>";
echo "</html>";
?>


