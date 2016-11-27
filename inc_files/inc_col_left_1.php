<?php


echo "<div class=\"maintitle\"><a href=\"index2.php\" class=\"image\">";

if (file_exists($logo)) {
		echo "<img src=\"$logo\" alt=\"$settings_name\" style=\"text-align: center;\" />";
} else {
		echo $settings_name;
}

echo "</a></div>";

// Menu - System

	$array_pages = array("index2.php","index2.php?page=admin_settings","backup.php","logout.php");
	$array_title = array("Home","Configuration","Backup Database","Log Out");
	$array_images = array("button_home.png","button_settings.png","button_save.png","button_logout.png");
	$array_access = array(0,4,4,0);

	SideMenu ("System", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	

// Menu - Contacts

if ($module_contacts == "1") {

	$array_pages = array("index2.php?page=contacts_edit&amp;status=add","index2.php?page=contacts_company_edit&amp;status=add","index2.php?page=contacts_discipline_list","index2.php?page=contacts_pdf_labels");
	$array_title = array("Add Contact","Add Company","List Disciplines","Marketing Labels");
	$array_images = array("button_contact.png","button_contact.png","button_list.png","button_print.png");
	$array_access = array(1,1,1,3);

	SideMenu ("Contacts", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
}

// Menu - Datebook

	$array_pages = array("index2.php?page=datebook_view_day");
	$array_title = array("Today");
	$array_images = array("button_date.png");
	$array_access = array(1);

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

	$array_pages = array("popup_timesheet.php","index2.php?page=timesheet_analysis","pdf_timesheet_analysis.php","pdf_resourcing.php","pdf_timesheet_personal.php","index2.php?page=timesheet_incomplete_list");
	$array_title = array("Timesheets","Analysis","Projects","Resourcing","My Timesheets","List incomplete");
	$array_images = array("button_fullscreen.png","button_analysis.png","button_pdf.png","button_pdf.png","button_pdf.png","button_list.png");
	$array_access = array(1,4,4,4,1,1);

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

// Menu - Expenses

if ($module_expenses == "1") {

	$array_pages = array("index2.php?page=timesheet_expense_edit","index2.php?page=timesheet_expense_list","index2.php?page=timesheet_expense_list&&amp;user_id=$_COOKIE[user]");
	$array_title = array("Add Expenses","Oustanding Expenses","List My Expenses");
	$array_images = array("button_new.png","button_list.png","button_list.png");
	$array_access = array(1,4,1);

	SideMenu ("Expenses", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);

}

// Menu - Library

if ($module_library == 1) {

	$array_pages = array("index2.php?page=library_index");
	$array_title = array("Document Library");
	$array_images = array("button_library.png");
	$array_access = array(1);

	SideMenu ("Library", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);

}

// Menu - Tenders

if ($module_tenders == 1) {

	$array_pages = array("index2.php?page=tender_list");
	$array_title = array("Tenders");
	$array_images = array("button_list.png");
	$array_access = array(3);

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

// Menu - System Information

	$array_pages = array("index2.php?page=info_usertypes");
	$array_title = array("User Types");
	$array_images = array("button_list.png");
	$array_access = array(5);

	SideMenu ("System Information", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);


?>
