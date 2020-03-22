<?php

// Check the IP address of the user

include_once("inc_files/inc_ipcheck.php");

// Perform the top-of-page security check

include_once("inc_files/inc_checkcookie.php");

$usercheck = $_POST[usercheck];
$checkform_user = $_POST[checkform_user];

if ($_POST['action'] != "") { include_once("inc_files/functions_actions.php"); include("inc_files/action_$_POST[action].php"); }
elseif ($_GET['action'] != "") { include_once("inc_files/functions_actions.php"); include("inc_files/action_$_GET[action].php"); }

// Include the standard header file

include_once("inc_files/inc_header.php");

Logo($settings_style,$settings_name);


    echo "<div id=\"mainpage\">";

// Column Left

    
    include("inc_files/inc_col_left_1.php");

	
    
//Column Centre

	// Alert Functions

	if ($user_usertype_current > 4) { CheckExpenses(); }
	if ($user_usertype_current > 3) { CheckFutureTenders(); }
	if ($user_usertype_current > 3) { CheckInvoicesToBeIssued($_COOKIE['user']); }
	if ($user_usertype_current > 3) { CheckInvoicesOverdue($_COOKIE['user']); }
	CheckOutstandingTimesheets($_COOKIE['user']);
	CheckOutstandingTasks($_COOKIE['user']);
	CheckCheckList();
	CheckTelephoneMessages($_COOKIE['user']);

    echo "<div id=\"column_centre\">";
	
	$displaytime = time() + 30; //86400;
	
	
	if ($invoiceduemessage != "" AND $_GET['page'] == NULL AND $_COOKIE['invoiceduemessage'] == NULL AND $_POST['action'] != "invoice_due_setcookie") { echo "<h1 class=\"heading_alert\">Invoices Overdue&nbsp;</h1>$invoiceduemessage<form action=\"index2.php\" method=\"post\"><input type=\"hidden\" value=\"".time()."\" name=\"invoiceduemessage\" /><input type=\"hidden\" name=\"action\" value=\"invoice_due_setcookie\" /><input type=\"submit\" value=\"Hide for 24 hours\" /></form>"; }
	
	if ($invoicemessage != "" AND $_GET['page'] == NULL) { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Invoices To Be Issued Today</strong></p>$invoicemessage</div>"; }
	
	if ($alertmessage) { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Error</strong></p><p>$alertmessage</p></div>"; }
	
	if ($actionmessage) { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Information</strong></p><p>$actionmessage</p></div>"; }
	
	
	if ($techmessage != "" AND $settings_showtech == "1" AND $usertype_status == "admin") { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Support Messages</strong></p><p>$techmessage</p></div>"; }
	
			// This includes the "outstanding" section, which alerts users to outstanding actions.
	   
	AlertBoxShow($_COOKIE['user']);
	

    if ($useraction == "defineuser") {
		include("inc_files/inc_alertuser.php");
    }
	
	
// Check for any upcoming holidays


if (!$_GET['page']) {
	
	$today = TimeFormatDay(time());

	echo "<h1>" . $today . "</h1>";

	echo "<div id=\"HolidayList\" class=\"FloatBoxHalf\">";
	ListHolidays();
	if ($prefs_nonworking) {
		UserLocationList($prefs_nonworking);
	}
	echo "</div>";
	
	if ($module_media == 1) {
		echo "<div id=\"MediaList\" class=\"FloatBoxHalf\">";
		MediaLatestList();
		echo "</div>";
	}

	echo "<div id=\"DateList\" class=\"FloatBoxHalf\">"; DateList(1); echo "</div>";
	
	if ($module_tasks == 1) {
	
		echo "<div id=\"TaskList\" class=\"FloatBoxHalf\"><h2>My Tasks</h2>"; MiniTaskList($_COOKIE['user']); echo "</div>";
	
	}
	
}

	if ($proj_id > 0) { $proj_id = intval($proj_id); }
	elseif ($_GET['proj_id'] > 0) { $proj_id = intval($_GET['proj_id']); }
	elseif ($_POST['proj_id'] > 0) { $proj_id = intval($_POST['proj_id']); }
	else { unset($proj_id); }

    if ($useraction != "defineuser") {
	
	
		// Add the project title, if there is one either through POST or GET
		
		$proj_details = ProjectTitle();
		$proj_id = $proj_details[0];
		if ($proj_id) {
			$proj_title = "<h1 id=\"project_title\"><a href=\"#\" onclick=\"ShowProjectSwitcher()\">$proj_details[1] $proj_details[2]</a></h1>";
		} else {
			unset($proj_title);
		}
		
		if ($proj_title) { echo $proj_title; }
	


		// Include the relevant page if the $_GET[page] variable is not blank, otherwise deliver the default page
        if ($page_redirect != NULL) {
			$inc_file = "inc_files/inc_".$page_redirect.".php";
		} elseif ($_GET['page'] != NULL) {
            $inc_file = "inc_files/inc_".$_GET['page'].".php";
		} elseif ($_POST['page'] != NULL) {
            $inc_file = "inc_files/inc_".$_POST['page'].".php";
        } else {
            $inc_file = "inc_files/inc_default.php";
        }
		
		if (file_exists($inc_file)) { include($inc_file); } else { include("inc_files/inc_default.php?$page_variables"); }
		}
	


    echo "</div>";
	
echo "</div>";

echo $alertscript;

// Finish with the standard footer

FooterBar();

echo "</body>";
echo "</html>";


