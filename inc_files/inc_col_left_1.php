<?php

echo "<div id=\"menu_mobile_button\">";

echo "

<script>

function ShowMobileMenu() {
    var x = document.getElementById(\"column_left\");
    if (x.style.display === \"block\") {
        x.style.display = \"none\";
    } else {
        x.style.display = \"block\";
    }
}

</script>

";

echo "<a href=\"#\" onclick=\"ShowMobileMenu()\"><img src=\"images/button_menu.png\" alt=\"Menu\" style=\"width: 100%; height: 100%;\" /></a></div>";

echo "</div>";

echo "<div id=\"column_left\">";




// Menu - System

	$array_pages = array("index2.php","index2.php?page=admin_settings","backup.php","index2.php?page=alert_list","index2.php?page=system_php_info","logout.php");
	$array_title = array("Home","Configuration","Backup Database","Activity Log","System Information","Log Out");
	$array_images = array("button_home.png","button_settings.png","button_save.png","button_settings.png","button_settings.png","button_logout.png");
	$array_access = array(0,4,4,1,4,0);

	SideMenu ("System", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	

	
// Menu - Weekly Summary

	$nextweek = time() + 604800;
	$lastweek = time() - 604800;
	$array_pages = array("pdf_weekly_summary.php","pdf_weekly_summary.php?beginweek=$lastweek","pdf_weekly_summary.php?beginweek=$nextweek");
	$array_title = array("Weekly Summary (PDF)","Last Week Summary (PDF)","Next Week Summary (PDF)");
	$array_images = array("button_pdf.png","button_pdf.png","button_pdf.png");
	$array_access = array(2,2,2);

	SideMenu ("Weekly Summary", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	

// Menu - Contacts

if ($module_contacts == "1") {

	$array_pages = array("index2.php?page=contacts_edit&amp;status=add","index2.php?page=contacts_company_edit&amp;status=add","index2.php?page=contacts_discipline_list","index2.php?page=contacts_view","index2.php?page=contacts_pdf_labels");
	$array_title = array("Add Contact","Add Company","List Disciplines","All Contacts","Marketing Labels");
	$array_images = array("button_contact.png","button_contact.png","button_list.png","button_list.png","button_print.png");
	$array_access = array(2,2,1,1,3);
	
	$sql = "SELECT target_id FROM intranet_contacts_targetlist WHERE target_user = $_COOKIE[user] LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
	
		$array_pages[] = "index2.php?page=contacts_targetlist";
		$array_title[] = "My List";
		$array_images[] = "button_list.png";
		$array_access[] = 2;
		
	}

	SideMenu ("Contacts", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}

// Menu - Datebook

	$array_pages = array("index2.php?page=datebook_view_day","index2.php?page=date_list","index2.php?page=date_edit");
	$array_title = array("Today","List Important Dates","Add Important Date");
	$array_images = array("button_date.png","button_list.png","button_new.png");
	$array_access = array(1,1,2);

	SideMenu ("Datebook", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);


// Menu - Tasks

if ($module_tasks == "1") {

	$array_pages = array("index2.php?page=tasklist_view");
	$array_title = array("View My Tasks");
	$array_images = array("button_list.png");
	$array_access = array(1);

	SideMenu ("Tasks", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);

}

// Menu - Holidays

if ($module_holidays == "1") {
	$array_pages = array("index2.php?page=holiday_request","index2.php?page=holiday_approval");
	$array_title = array("Holiday Request","Holiday Calendar");
	$array_images = array("button_calendar.png","button_calendar.png");
	$array_access = array(1,1);
	
	$sql_user_holidays = "SELECT holiday_length FROM intranet_user_holidays WHERE holiday_user = $_COOKIE[user] AND holiday_approved IS NULL";
	$result_user_holidays = mysql_query($sql_user_holidays, $conn) or die(mysql_error());
	if (mysql_num_rows($result_user_holidays) > 0) {
		$array_pages[] = "pdf_holiday_request.php?user_id=$_COOKIE[user]";
		$array_title[] = "Holiday Request";
		$array_images[] = "button_pdf.png";
		$array_access[] = 1;
	}

	SideMenu ("Holidays", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
}

// Menu - QMS

if ($module_qms == "1") {

	$array_pages = array("index2.php?page=qms_view","index2.php?page=qms_edit");
	$array_title = array("View QMS","Edit QMS");
	$array_images = array("button_list.png","button_edit.png");
	$array_access = array(1,2);

	SideMenu ("Quality Management", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}

// Menu - Timesheets

if ($module_timesheets == "1") {

	$array_pages = array("index2.php?page=timesheet","index2.php?page=timesheet_analysis","index2.php?page=timesheet_factored","pdf_timesheet_analysis.php","pdf_resourcing.php","pdf_timesheet_personal.php","index2.php?page=timesheet_incomplete_list","pdf_project_performance_summary.php");
	$array_title = array("Timesheets","Analysis","Factored Timesheets","Projects","Resourcing","My Timesheets","List incomplete","Project Performance Summary");
	$array_images = array("button_list.png","button_analysis.png","button_list.png","button_pdf.png","button_pdf.png","button_pdf.png","button_list.png","button_pdf.png");
	$array_access = array(1,4,4,4,4,1,1,4);

	SideMenu ("Timesheets", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}

// Menu - Invoices

if ($module_invoices == "1") {

	$array_pages = array("index2.php?page=timesheet_invoice");
	$array_title = array("Invoices");
	$array_images = array("button_list.png");
	$array_access = array(4);

	SideMenu ("Invoices", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}

// Menu - Media

if ($module_media == 1) {

	$array_pages = array("index2.php?page=media","index2.php?page=media&amp;action=upload");
	$array_title = array("Browse Library","Upload");
	$array_images = array("button_library.png","button_list.png");
	$array_access = array(0,3);

	SideMenu ("Media Library", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}

// Menu - Expenses

if ($module_expenses == "1") {

	$array_pages = array("index2.php?page=timesheet_expense_edit","index2.php?page=timesheet_expense_list","index2.php?page=timesheet_expense_list&&amp;user_id=$_COOKIE[user]","pdf_expense_claim.php?user_id=$_COOKIE[user]","index2.php?page=timesheet_expense_validated","index2.php?page=expenses_analysis","index2.php?page=timesheet_expense_list");
	$array_title = array("Add Expenses","Oustanding Expenses","List My Expenses","Expenses Claim","Validated Expenses","Expense Analysis","Validate Expenses");
	$array_images = array("button_new.png","button_list.png","button_list.png","button_pdf.png","button_list.png","button_analysis.png","button_list.png");
	$array_access = array(1,3,1,2,3,3,3);

	SideMenu ("Expenses", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);

}

// Menu - Library

if ($module_library == 1) {

	$array_pages = array("index2.php?page=library_index");
	$array_title = array("Document Library");
	$array_images = array("button_library.png");
	$array_access = array(1);

	//SideMenu ("Library", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);

}

// Menu - Tenders

if ($module_tenders == 1) {

	$array_pages = array("index2.php?page=tender_list","index2.php?page=tender_edit");
	$array_title = array("List Tenders","Add Tender");
	$array_images = array("button_list.png","button_new.png");
	$array_access = array(3,3);

	SideMenu ("Tenders", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}

// Menu - Phone Messages

if ($module_phonemessages == 1) {

	$array_pages = array("index2.php?page=phonemessage_edit&amp;status=new","index2.php?page=phonemessage_view&amp;status=view");
	$array_title = array("New Message","View All");
	$array_images = array("button_message.png","button_list.png");
	$array_access = array(2,2);

	SideMenu ("Messages", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}

// Menu - Office Manuals

if ($module_manual == 1) {

	$array_pages = array("index2.php?page=manual_page","index2.php?page=manual_page&amp;action=add");
	$array_title = array("Contents","Add New");
	$array_images = array("button_list.png","button_new.png");
	$array_access = array(0,4);

	SideMenu ("Office Manual", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}

// Menu - Web Feeds

if ($module_webfeeds == 1) {

	$array_pages = array("index2.php?page=feeds&type=news","index2.php?page=feeds&type=competitions");
	$array_title = array("BD News","BD Competitions");
	$array_images = array("button_news.png","button_news.png");
	$array_access = array(0,0);
				
	SideMenu ("News Feeds", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}

// Menu - Journal

	$array_pages = array("index2.php?page=project_blog_edit&amp;status=add");
	$array_title = array("Add Journal Entry");
	$array_images = array("button_new.png");
	$array_access = array(1);
				
	SideMenu ("Journal", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
// Menu - Team

	TeamMenu($user_usertype_current);
	
//Menu - Pinned Blog Entries
	
	PinnedJournalEntries($user_usertype_current);

// Menu - System Information

	$array_pages = array("index2.php?page=info_usertypes");
	$array_title = array("User Types");
	$array_images = array("button_list.png");
	$array_access = array(5);

	SideMenu ("System Information", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);

	
	SearchPanel($user_usertype_current);
	
   echo "</div>";
