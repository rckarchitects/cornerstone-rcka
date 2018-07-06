<?php

// Check the IP address of the user

include("inc_files/inc_ipcheck.php");

// Perform the top-of-page security check

include "inc_files/inc_checkcookie.php";

$usercheck = $_POST[usercheck];
$checkform_user = $_POST[checkform_user];

if ($_POST[action] != "") { include_once("inc_files/inc_functions_actions.php"); include("inc_files/action_$_POST[action].php"); }
elseif ($_GET[action] != "") { include_once("inc_files/inc_functions_actions.php"); include("inc_files/action_$_GET[action].php"); }

// Check for details of any projects

		


// Include the standard header file

include("inc_files/inc_header.php");

Logo($settings_style,$settings_name);


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

	// Alert Functions

	if ($user_usertype_current > 4) { CheckExpenses(); }
	if ($user_usertype_current > 3) { CheckFutureTenders(); }
	if ($user_usertype_current > 3) { CheckInvoicesToBeIssued($_COOKIE[user]); }
	if ($user_usertype_current > 3) { CheckInvoicesOverdue($_COOKIE[user]); }
	CheckOutstandingTimesheets($_COOKIE[user]);
	CheckOutstandingTasks($_COOKIE[user]);
	CheckCheckList();
	CheckTelephoneMessages($_COOKIE[user]);

    echo "<div id=\"column_centre\">";
	
	$displaytime = time() + 30; //86400;
	
	
	if ($invoiceduemessage != "" AND $_GET[page] == NULL AND $_COOKIE[invoiceduemessage] == NULL AND $_POST[action] != "invoice_due_setcookie") { echo "<h1 class=\"heading_alert\">Invoices Overdue&nbsp;</h1>$invoiceduemessage<form action=\"index2.php\" method=\"post\"><input type=\"hidden\" value=\"".time()."\" name=\"invoiceduemessage\" /><input type=\"hidden\" name=\"action\" value=\"invoice_due_setcookie\" /><input type=\"submit\" value=\"Hide for 24 hours\" /></form>"; }
	
	if ($invoicemessage != "" AND $_GET[page] == NULL) { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Invoices To Be Issued Today</strong></p>$invoicemessage</div>"; }
	
	if ($alertmessage) { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Error</strong></p><p>$alertmessage</p></div>"; }
	
	if ($actionmessage) { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Information</strong></p><p>$actionmessage</p></div>"; }
	
	
	if ($techmessage != "" AND $settings_showtech == "1" AND $usertype_status == "admin") { $timesheetaction = $timesheetaction . "<div class=\"warning\"><p><strong>Support Messages</strong></p><p>$techmessage</p></div>"; }
	
			// This includes the "outstanding" section, which alerts users to outstanding actions.
	   
	AlertBoxShow($_COOKIE[user]);
	

    if ($useraction == "defineuser") {
		include("inc_files/inc_alertuser.php");
    }
	
	
// Check for any upcoming holidays


if ($_GET[page] == NULL) {
	
	$today = TimeFormatDay(time());

	echo "<h1>$today</h1>";

	echo "<div style=\"float: left; max-width: 720px; margin-right: 12px;\">";
	ListHolidays();
	echo "</div>";
	
	if ($module_media == 1) {
		echo "<div style=\"float: left; max-width: 720px; margin-right: 12px;\">";
		MediaLatestList();
		echo "</div>";
	}
	
	echo "<div style=\"float: left;\">";
	echo "</div>";
	
}




    if ($useraction != "defineuser") {
	
	
		// Add the project title, if there is one either through POST or GET
		
		$proj_details = ProjectTitle();
		$proj_id = $proj_details[0];
		if ($proj_id) {
			echo 
						"<script>
							function ShowProjectSwitcher() {
							var x = document.getElementById(\"project_switcher\");
							var y = document.getElementById(\"project_title\");
							if (x.style.display === \"none\") {
								x.style.display = \"block\";
								y.style.display = \"none\";
							} else {
								x.style.display = \"none\";
								
							}
						}
						</script>
			
			";
			$proj_title = "<h1 id=\"project_title\"><a href=\"#\" onclick=\"ShowProjectSwitcher()\">$proj_details[1] $proj_details[2]</a></h1>";
		} else {
			unset($proj_title);
		}
		
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
	


    echo "</div>";
	
echo "</div>";

echo $alertscript;

// Finish with the standard footer

FooterBar();

echo "</body>";
echo "</html>";
?>


