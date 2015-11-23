<?php
include("inc_menu_settings.php");

print "<h1 class=\"heading_side\">Contacts</h1>";
print "<ul class=\"button_left\">";
// print "<li><a href=\"index2.php?page=contacts_view\">View Contacts</a></li>";
print "<li><span class=\"minitext\"><a href=\"index2.php?page=contacts_edit&amp;status=add\">[Add Contact]</a>&nbsp;<a href=\"index2.php?page=contacts_company_edit&amp;status=add\">[Add Company]</a><br /><a href=\"index2.php?page=contacts_discipline_list\">[List Disciplines]</a></span></li>";

if ($user_usertype_current > 2) { 
print "<li><span class=\"minitext\"><a href=\"index2.php?page=contacts_pdf_labels\">[Marketing Labels]</a></span></li>";
}

print "</ul>";

print "<h1 class=\"heading_side\">Datebook</h1>";
print "<ul class=\"button_left\">";
print "<li><a href=\"index2.php?page=datebook_view_day\">Today</a></li>";
if ($_COOKIE[lastdayview] > 0) { print "<li><a href=\"index2.php?page=datebook_view_day&amp;time=$_COOKIE[lastdayview]\">".TimeFormat($_COOKIE[lastdayview])."</a></li>"; }
print "</ul>";

if ($module_tasks == "1") {
print "<h1 class=\"heading_side\">Task Management</h1>";
print "<ul class=\"button_left\">";
print "<li><a href=\"index2.php?page=tasklist_view\">View Tasks</a></li>";
print "</ul>";
}

if ($module_holidays == "1") {
print "<h1 class=\"heading_side\">Holidays</h1>";
print "<ul class=\"button_left\">";
print "<li><a href=\"index2.php?page=holiday_request\">Holiday Request</a></li>";
echo "<li><a href=\"index2.php?page=holiday_approval\">Holiday Calendar</a></li>";
print "</ul>";
}

if ($module_qms == "1") {
print "<h1 class=\"heading_side\">Quality Management</h1>";
print "<ul class=\"button_left\">";
echo "<li><a href=\"index2.php?page=qms_view\">View QMS</a>&nbsp;<a href=\"pdf_qms.php\"><img src=\"images/button_pdf.png\" alt=\"PDF Version of QMS\" /></a></li>";
if ($user_usertype_current > 4) { 
	echo "<li><a href=\"index2.php?page=qms_edit\">Edit QMS</a></li>";
}
print "</ul>";
}

if ($module_timesheets == "1") {
print "<h1 class=\"heading_side\">Timesheets</h1>";
print "<ul class=\"button_left\">";
print "<li><a href=\"popup_timesheet.php\">Timesheets (Full Screen)</a></li>";
if ($user_usertype_current > 3) { 
print "<li><span class=\"minitext\"><a href=\"index2.php?page=timesheet_analysis\">Timesheet Analysis</a></span></li>";
print "<li><span class=\"minitext\"><a href=\"pdf_timesheet_analysis.php\">Project Analysis (PDF)</a></span></li>";
print "<li><span class=\"minitext\"><a href=\"pdf_resourcing.php\">Resourcing Analysis (PDF)</a></span></li>";
}
echo "<li><span class=\"minitext\"><a href=\"pdf_timesheet_personal.php\">My Timesheets (PDF)</a></span></li>";
//echo "<li><span class=\"minitext\"><a href=\"index2.php?page=holiday_request\">Holiday Request</a></span></li>";
echo "<li><span class=\"minitext\"><a href=\"index2.php?page=timesheet_incomplete_list\">List incomplete timesheets</a></span></li>";
print "</ul>";
}

if ($module_invoices == "1") {


if ($user_usertype_current > 3) { print "<h1 class=\"heading_side\">Invoices</h1>"; print "<ul class=\"button_left\">"; print "<li><a href=\"index2.php?page=timesheet_invoice\">Invoices</a></li></ul>"; }

print "<h1 class=\"heading_side\">Expenses</h1>";
print "<ul class=\"button_left\">";
print "<li><a href=\"index2.php?page=timesheet_expense_edit\">Add Expenses</a>&nbsp;<a href=\"popup_expenses.php\">[Quick Add]</a></li>";
print "<li><a href=\"index2.php?page=timesheet_expense_list&amp;user_id=$user_id_current\">List My Expenses</a>";

	$sql_expense = "SELECT ts_expense_id FROM intranet_timesheet_expense WHERE ts_expense_user = '$_COOKIE[user]' AND ( ts_expense_verified = 0 OR ts_expense_verified IS NULL )";
	$result_expense = mysql_query($sql_expense, $conn) or die(mysql_error());
	if (mysql_num_rows($result_expense) > 0){ echo "&nbsp;<a href=\"pdf_expense_claim.php?user_id=$_COOKIE[user]\"><img src=\"images/button_pdf.png\" alt=\"Expenses Claim\" /></a>"; }
echo "</li>";
print "</ul>";
}


if ($module_standards == "1") {

echo "<h1 class=\"heading_side\">Office Standards</h1>";
	print "<ul class=\"button_left\">";
	$sql_standards = "SELECT * FROM intranet_standards_sections ORDER BY standard_section_number";
	$result_standards = mysql_query($sql_standards, $conn);
	while ($array_standards = mysql_fetch_array($result_standards)) {
	$standard_section_id = $array_standards['standard_section_id'];
	$standard_section_number = $array_standards['standard_section_number'];
	$standard_section_title = $array_standards['standard_section_title'];
	echo "<li><a href=\"index2.php?page=standards&amp;standard_section_id=" . $standard_section_id . "\">" . $standard_section_number . ".&nbsp;" . $standard_section_title . "</a></li>";
}
	
	echo "<li><span class=\"minitext\"><a href=\"index2.php?page=standards\">View all</a></span></li>";
	echo "</ul>";

echo "<h1 class=\"heading_side\">Health &amp; Safety</h1>";
	print "<ul class=\"button_left\">";
	echo "<a href=\"library/lawleaflet.pdf\">Health & Safety Law:<br />What you need to know</a>";
	echo "</ul>";
	
}


if ($module_drawings == 1) {
print "<h1 class=\"heading_side\">Drawings</h1>";
print "<ul class=\"button_left\">";
print "<li><a href=\"index2.php?page=drawings_view\">Manage Drawings</a></li>";
print "</ul>";
}

if ($module_library == 1) {
print "<h1 class=\"heading_side\">Library</h1>";
print "<ul class=\"button_left\">";
echo "<li><a href=\"index2.php?page=library_index\">Document Library</a></li>";
print "</ul>";
}

if ($module_tenders == 1) {
print "<h1 class=\"heading_side\">Tenders</h1>";
print "<ul class=\"button_left\">";
echo "<li><a href=\"index2.php?page=tender_list\">Tenders</a></li>";
print "</ul>";
}

if ($user_usertype_current > 3) {
	print "<h1 class=\"heading_side\">Miscellaneous</h1>";
	print "<ul class=\"button_left\">";
	print "<li><a href=\"index2.php?page=info_usertypes\">User Types</a></li>";
	if ($user_usertype_current == 5) { print "<li><a href=\"index2.php?page=php_info\">System Configuration</a></li>"; }
	print "</ul>";
}

if ($module_phonemessage == 1) {
include("inc_files/inc_menu_phonemessage.php");
}

?>
