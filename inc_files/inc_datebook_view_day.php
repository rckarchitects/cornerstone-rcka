<?php

$hour_begin = 7;
$hour_end = 13;

print "<h1>".TimeFormatDay($time)."</h1>";

print "
<p class=\"menu_bar\">
<a class=\"menu_tab\" href=\"index2.php?page=datebook_view_day&amp;time=".($time - 31536000)."\"><< ".Date("Y",($time - 31536000))."</a>
<a class=\"menu_tab\" href=\"index2.php?page=datebook_view_day&amp;time=".($time - 604800)."\"><< ".TimeFormat($time - 604800)."</a>
<a class=\"menu_tab\" href=\"index2.php?page=datebook_view_day&amp;time=".($time - 86400)."\">< ".TimeFormat($time - 86400)."</a>
<a class=\"menu_tab\" href=\"index2.php?page=datebook_view_day&amp;time=".time()."\">Today</a>
<a class=\"menu_tab\" href=\"index2.php?page=datebook_view_day&amp;time=".($time + 86400)."\">".TimeFormat($time + 86400)." ></a>
<a class=\"menu_tab\" href=\"index2.php?page=datebook_view_day&amp;time=".($time + 604800)."\">".TimeFormat($time + 604800)." >></a>
<a class=\"menu_tab\" href=\"index2.php?page=datebook_view_day&amp;time=".($time + 31536000)."\">".Date("Y",($time + 31536000))." >></a>
</p>";

$startday_day = date("j", $time);
$startday_month = date("n", $time);
$startday_year = date("Y", $time);

$startday = mktime($hour_begin, 0, 0, $startday_month, $startday_day, $startday_year);
$endday = $startday + 86400;

// Invoices issued today

		$sql_invoices = "SELECT invoice_id, invoice_ref, invoice_paid, invoice_due, proj_name, proj_num, proj_id FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_date BETWEEN '$startday' AND '$endday' AND invoice_project = proj_id ORDER BY invoice_ref";
		$result_invoices = mysql_query($sql_invoices, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result_invoices) > 0 AND $user_usertype_current > 2) {
		
			print "<h2>Invoices Issued Today</h2>";
			print "<table summary=\"Invoices due on ".TimeFormat($time)."\">";
		
		while ($array_invoices = mysql_fetch_array($result_invoices)) {
			$invoice_id = $array_invoices['invoice_id'];
			$invoice_ref = $array_invoices['invoice_ref'];
			$invoice_paid = $array_invoices['invoice_paid'];
			$invoice_due = $array_invoices['invoice_due'];
			$proj_name = $array_invoices['proj_name'];
			$proj_num = $array_invoices['proj_num'];
			$proj_id = $array_invoices['proj_id'];

			
			if ($invoice_due < time() AND $invoice_paid == 0) { $highlight = "background-color: #$settings_alertcolor"; } else { $highlight = NULL; }
			
			print "<tr><td style=\"width: 100px; $highlight\"><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">$invoice_ref</a>";
			print "</td><td style=\"$highlight\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a>";
			if ($invoice_paid > 0) { print " (paid <a href=\"index2.php?page=datebook_view_day&amp;time=$invoice_paid\">".TimeFormat($invoice_paid)."</a>)"; }
			print "</td></tr>";
		}
			print "</table>";
		}

// Invoices due today

		$sql_invoices = "SELECT invoice_id, invoice_ref, invoice_paid, invoice_due, proj_name, proj_num, proj_id FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_due BETWEEN '$startday' AND '$endday' AND invoice_project = proj_id ORDER BY invoice_ref";
		$result_invoices = mysql_query($sql_invoices, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result_invoices) > 0 AND $user_usertype_current > 2) {
		
			print "<h2>Invoices Due Today</h2>";
			print "<table summary=\"Invoices due on ".TimeFormat($time)."\">";
		
		while ($array_invoices = mysql_fetch_array($result_invoices)) {
			$invoice_id = $array_invoices['invoice_id'];
			$invoice_ref = $array_invoices['invoice_ref'];
			$invoice_paid = $array_invoices['invoice_paid'];
			$invoice_due = $array_invoices['invoice_due'];
			$proj_name = $array_invoices['proj_name'];
			$proj_num = $array_invoices['proj_num'];
			$proj_id = $array_invoices['proj_id'];

			
			if ($invoice_due < time() AND $invoice_paid == 0) { $highlight = "background-color: #$settings_alertcolor"; } else { $highlight = NULL; }
			
			print "<tr><td style=\"width: 100px; $highlight\"><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">$invoice_ref</a>";
			print "</td><td style=\"$highlight\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a>";
			if ($invoice_paid > 0) { print " (paid <a href=\"index2.php?page=datebook_view_day&amp;time=$invoice_paid\">".TimeFormat($invoice_paid)."</a>)"; }
			print "</td></tr>";
		}
			print "</table>";
		}
		
// Invoices paid today

		$sql_invoices = "SELECT invoice_id, invoice_ref, invoice_date, invoice_due, proj_name, proj_num, proj_id FROM intranet_timesheet_invoice, intranet_projects WHERE invoice_paid BETWEEN '$startday' AND '$endday' AND invoice_project = proj_id ORDER BY invoice_ref";
		$result_invoices = mysql_query($sql_invoices, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result_invoices) > 0 AND $user_usertype_current > 2) {
		
			print "<h2>Invoices Paid Today</h2>";
			print "<table summary=\"Invoices paid on ".TimeFormat($time)."\">";
		
		while ($array_invoices = mysql_fetch_array($result_invoices)) {
			$invoice_id = $array_invoices['invoice_id'];
			$invoice_ref = $array_invoices['invoice_ref'];
			$invoice_date = $array_invoices['invoice_date'];
			$invoice_due = $array_invoices['invoice_due'];
			$proj_name = $array_invoices['proj_name'];
			$proj_num = $array_invoices['proj_num'];
			$proj_id = $array_invoices['proj_id'];

			
			print "<tr><td style=\"width: 100px; $highlight\"><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">$invoice_ref</a>";
			print "</td><td style=\"$highlight\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a>";
			print " (issued <a href=\"index2.php?page=datebook_view_day&amp;time=$invoice_date\">".TimeFormat($invoice_date)."</a>)";
			print "</td></tr>";
		}
			print "</table>";
		}
		
// Expenses added today

		$sql_expense = "SELECT ts_expense_date, ts_expense_desc, ts_expense_id, ts_expense_vat, user_initials FROM intranet_timesheet_expense, intranet_user_details WHERE user_id = ts_expense_user AND ts_expense_date BETWEEN '$startday' AND '$endday' ORDER BY ts_expense_date";
		$result_expense = mysql_query($sql_expense, $conn) or die(mysql_error());
	
		if (mysql_num_rows($result_expense) > 0 AND $user_usertype_current > 3) {
		
			print "<h2>Expenses Added</h2>";
			print "<table summary=\"Expenses added on ".TimeFormat($time)."\">";
			
			$list1 = 0;
			$count = 1;
		
		while ($array_expense = mysql_fetch_array($result_expense)) {
			$ts_expense_date = $array_expense['ts_expense_date'];
			$ts_expense_desc = $array_expense['ts_expense_desc'];
			$ts_expense_id = $array_expense['ts_expense_id'];
			$ts_expense_vat = $array_expense['ts_expense_vat'];
			$user_initials = $array_expense['user_initials'];	
			echo "<tr><td><a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\">".$ts_expense_desc." [".$ts_expense_id."]</a><td>".MoneyFormat($ts_expense_vat)."</td></td><td>$user_initials</td></tr>";
			
		}
			print "</table>";
		}
		
// Expenses verified today

		$sql_expense = "SELECT ts_expense_verified FROM intranet_timesheet_expense WHERE ts_expense_verified BETWEEN '$startday' AND '$endday' ORDER BY ts_expense_verified";
		$result_expense = mysql_query($sql_expense, $conn) or die(mysql_error());
	
		if (mysql_num_rows($result_expense) > 0 AND $user_usertype_current > 3) {
		
			print "<h2>Expenses Verified Today</h2>";
			print "<table summary=\"Expenses verified on ".TimeFormat($time)."\">";
			
			$list1 = 0;
			$count = 1;
		
		while ($array_expense = mysql_fetch_array($result_expense)) {
			$ts_expense_verified = $array_expense['ts_expense_verified'];			
			
			if ($ts_expense_verified != $list1) {
					print "<tr><td><a href=\"index2.php?page=timesheet_expense_list_verified&amp;time=$ts_expense_verified\">Group $count: ".TimeFormatDetailed($ts_expense_verified)."</a>&nbsp;<a href=\"pdf_expense_verified_list.php?time=$ts_expense_verified\"><img src=\"images/button_pdf.png\" alt=\"PDF Output\" /></a></td></tr>";
					$list1 = $ts_expense_verified;
					$count++;
			}
			
		}
			print "</table>";
		}

// Drawings issued today

		$sql_drawings = "SELECT set_id, set_project, set_date, set_reason, proj_id, proj_num, proj_name FROM intranet_drawings_issued_set, intranet_projects WHERE set_date BETWEEN '$startday' AND '$endday' AND set_project = proj_id ORDER BY set_date DESC, proj_num";
		
		$result_drawings = mysql_query($sql_drawings, $conn) or die(mysql_error());
	
		if (mysql_num_rows($result_drawings) > 0) {
		
			print "<h2>Drawings Issued</h2>";
			print "<table summary=\"Drawings issued on ".TimeFormat($time)."\">";

		
		while ($array_drawings = mysql_fetch_array($result_drawings)) {
			$proj_id = $array_drawings['proj_id'];
			$proj_num = $array_drawings['proj_num'];
			$proj_name = $array_drawings['proj_name'];
			$set_id = $array_drawings['set_id'];
			$set_reason = $array_drawings['set_reason'];
			$drawing_id = $array_drawings['drawing_id'];
					print "<tr><td style=\"width: 40%;\"><a href=\"index2.php?page=drawings_issue_list&amp;set_id=$set_id&amp;proj_id=$proj_id\">$proj_num $proj_name</a>&nbsp;<a href=\"pdf_drawing_issue.php?issue_set=$set_id&amp;proj_id=$proj_id\"><img src=\"images/button_pdf.png\"></a></td><td>$set_reason</td><td>Issue ID: $set_id</td></tr>";

			$issue_count = $issue_date;
			
		}
			print "</table>";
		}

// Tasks due today

$count = $hour_begin;

		$sql_tasks = "SELECT tasklist_id, tasklist_project, tasklist_notes, tasklist_percentage, tasklist_due, tasklist_completed, proj_num, proj_id, user_id, user_name_first, user_name_second FROM intranet_tasklist, intranet_projects, intranet_user_details WHERE tasklist_person = user_id AND tasklist_project = proj_id AND tasklist_due BETWEEN '$startday' AND '$endday' ORDER BY proj_num";
		$result_tasks = mysql_query($sql_tasks, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result_tasks) > 0) {
		
			print "<h2>Tasks Due Today</h2>";
			print "<table summary=\"Tasks due on ".TimeFormat($time)."\">";
		
		while ($array_tasks = mysql_fetch_array($result_tasks)) {
			$tasklist_id = $array_tasks['tasklist_id'];
			$tasklist_notes = $array_tasks['tasklist_notes'];
			$tasklist_percentage = $array_tasks['tasklist_percentage'];
			$tasklist_due = $array_tasks['tasklist_due'];
			$tasklist_completed = $array_tasks['tasklist_completed'];
			$user_id = $array_tasks['user_id'];
			$user_name_first = $array_tasks['user_name_first'];
			$user_name_second = $array_tasks['user_name_second'];
			$proj_id = $array_tasks['proj_id'];
			$proj_num = $array_tasks['proj_num'];
			
			if ($tasklist_percentage < 100 AND $tasklist_due < time()) { $highlight = "background-color: #$settings_alertcolor"; } else { $highlight = NULL; }
			
			if ($tasklist_completed > 0) {
				$tasklist_completed_text = "(Completed ".TimeFormat($tasklist_completed).")";
				$strike = "<span style=\"text-decoration: line-through;\">";
				$strike2 = "</span>";
			} else {
				$tasklist_completed_text = NULL;
				$strike = NULL;
				$strike2 = NULL;
			}
			
			print "<tr><td style=\"width: 50px; $highlight\">$strike<a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a>$strike2</td><td style=\"$highlight\">$strike<a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=$tasklist_id&amp;time=$tasklist_due\">$tasklist_notes</a>$strike2&nbsp;$tasklist_completed_text</td><td style=\"$highlight\">$strike<a href=\"index2.php?page=user_view&amp;user_id=$user_id\">$user_name_first&nbsp;$user_name_second</a>$strike2</td></tr>";
		}
			print "</table>";
		}
		
// Journal entries

print "<h2>Journal Entries &amp; Telephone Messages</h2>";

print "<table summary=\"Datebook for ".TimeFormat($time)."\">";

while($count < ($hour_begin + $hour_end)) {

	$startday_next = $startday + 3599;

	print "<tr><td colspan=\"3\"><strong>";
	print date("g.00a",$startday);
	print "</strong></td></tr>";
	
			$type_find = array("phone","filenote","meeting","email");
			$type_replace = array("Telephone Call","File Note","Meeting Note","Email Message");
	
		$sql_blog = "SELECT blog_id, blog_title, blog_date, blog_type, proj_num, proj_id FROM intranet_projects_blog, intranet_projects WHERE proj_id = blog_proj AND blog_date BETWEEN '$startday' AND '$startday_next' ORDER BY '$blog_date'";
		$result_blog = mysql_query($sql_blog, $conn) or die(mysql_error());
		while ($array_blog = mysql_fetch_array($result_blog)) {
			$blog_id = $array_blog['blog_id'];
			$blog_title = $array_blog['blog_title'];
			$blog_date = $array_blog['blog_date'];
			$blog_type = $array_blog['blog_type'];
			$proj_id = $array_blog['proj_id'];
			$proj_num = $array_blog['proj_num'];
			$blog_type = str_replace($type_find,$type_replace,$blog_type);
			print "<tr><td style=\"width: 50px;\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a></td><td>$blog_type</td><td><a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id\">$blog_title</a></td></tr>";
		}
		
		// Telephone Messages
		$sql_phone = "SELECT message_id, message_for_user, message_from_id, message_from_name, message_text FROM intranet_phonemessage WHERE message_date BETWEEN '$startday' AND '$startday_next' ORDER BY message_date";
		$result_phone = mysql_query($sql_phone, $conn) or die(mysql_error());
		while ($array_phone = mysql_fetch_array($result_phone)) {
			$message_id = $array_phone['message_id'];
			$message_for_user = $array_phone['message_for_user'];
			$message_from_id = $array_phone['message_from_id'];
			$message_from_name = $array_phone['message_from_name'];
			$message_text = $array_phone['message_text'];
			print "<tr><td colspan=\"2\">Telephone Message for ";
			$data_user_id = $message_for_user; include("dropdowns/inc_data_user_name.php");
			print "</td><td>Message from ";
			if ($message_from_id > 0) { $data_contact = $message_from_id; include("dropdowns/inc_data_contacts_name.php"); } else { print $message_from_name; }
			print "<br /><span class=\"minitext\"><a href=\"index2.php?page=phonemessage_view_detailed&amp;message_id=$message_id\">$message_text</a></span></td></tr>";
		}

$startday = $startday + 3600;
$count++;	
	
}

		$sql_blog = "SELECT blog_id, blog_title, blog_date, blog_type, proj_num, proj_id FROM intranet_projects_blog, intranet_projects WHERE proj_id = blog_proj AND blog_date BETWEEN '$startday_next' AND '$endday' ORDER BY '$blog_date'";
		$result_blog = mysql_query($sql_blog, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result_blog) > 0) {
		
		print "<tr><td colspan=\"3\"><strong>Later</strong></td></tr>";
		
		while ($array_blog = mysql_fetch_array($result_blog)) {
			$blog_id = $array_blog['blog_id'];
			$blog_title = $array_blog['blog_title'];
			$blog_date = $array_blog['blog_date'];
			$blog_type = $array_blog['blog_type'];
			$proj_id = $array_blog['proj_id'];
			$proj_num = $array_blog['proj_num'];
			$blog_type = str_replace($type_find,$type_replace,$blog_type);
			print "<tr><td width=\"50\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a></td><td>$blog_type</td><td><a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id\">$blog_title</a></td></tr>";
		}
		
}

print "</table>";