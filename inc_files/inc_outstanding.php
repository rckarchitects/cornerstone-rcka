<?php

// First, deal with any outstanding tasks

	$futuretime = time() - 43200;
	$sql3 = "SELECT tasklist_id FROM intranet_tasklist WHERE tasklist_person = '$_COOKIE[user]' AND tasklist_percentage < '100' ";
	$sql4 = "SELECT * FROM intranet_tasklist WHERE tasklist_person = '$_COOKIE[user]' AND tasklist_percentage < '100' AND tasklist_due < $futuretime AND tasklist_due > 0 ";
	$result3 = mysql_query($sql3, $conn) or die(mysql_error());
	$result4 = mysql_query($sql4, $conn) or die(mysql_error());
	$tasks_outstanding = mysql_num_rows($result3);
	$tasks_overdue = mysql_num_rows($result4);
	
if ($tasks_overdue > 0 AND substr($_GET[page],0,8) != "tasklist") {
	
	if ($tasks_overdue > 1) { $tasks_plural = "tasks"; } else { $tasks_plural = "task"; }
	
	$outstanding = 1;
	$outstanding_tasks =  "<p class=\"body\">You have ".$tasks_overdue." ".$tasks_plural." outstanding. <a href=\"index2.php?page=tasklist_view&amp;subcat=user\">Click here</a> to view current task list.</p>";
}


// Now deal with any outstanding expenses claims (if the user has the correct credentials)

if ($user_usertype_current > 3) {

				$sql5 = "SELECT ts_expense_id FROM intranet_timesheet_expense WHERE ts_expense_verified = 0";
				$result5 = mysql_query($sql5, $conn) or die(mysql_error());
				$expenses_overdue = mysql_num_rows($result5);
				
			if ($expenses_overdue > 0 AND substr($_GET[page],0,17) != "timesheet_expense") {
				
				if ($expenses_overdue > 1) { $expenses_plural = "expenses claims"; } else { $expenses_plural = " expenses claim"; }
				
				$outstanding = 1;
				$outstanding_expenses = "<p class=\"body\">You have ".$expenses_overdue."&nbsp;".$expenses_plural." awaiting validation. <a href=\"index2.php?page=timesheet_expense_list\">Click here</a> to view oustanding items.</p>";
			}

}

// Check if there are any upcoming tenders (within 4 weeks) (if the user has the correct credentials)

$weeks = 3;
$seconds = 60 * 60 * 24 * 7 * $weeks;

if ($user_usertype_current > 2 AND substr($_GET[page],0,6) != "tender") {
				$nowtime = time();

				$sql6 = "SELECT * FROM intranet_tender WHERE tender_date > '$nowtime' AND (tender_date - $nowtime < $seconds) ORDER BY tender_date";
				$result6 = mysql_query($sql6, $conn) or die(mysql_error());
				$tenders_soon = mysql_num_rows($result6);
				
			if (mysql_num_rows($result6) > 0) {
					
				$outstanding_tender = "<p>The following tenders are due within the next $weeks weeks:</p><table summary=\"List of upcoming tenders\">";

				while ($array6 = mysql_fetch_array($result6)) {
					$tender_id = $array6['tender_id'];
					$tender_name = $array6['tender_name'];
					$tender_date = $array6['tender_date'];
					$days_to_go = ($tender_date - $nowtime) / 86400;
					$days_to_go = round($days_to_go);
					$outstanding_tender = $outstanding_tender . "<tr><td><a href=\"index2.php?page=tender_view&amp;tender_id=$tender_id\">$tender_name</a></td><td>".TimeFormatDetailed($tender_date)."&nbsp;(".$days_to_go."&nbsp;days to go)</td></tr>";

				}				
				$outstanding_tender = $outstanding_tender . "</table>";
				$outstanding = 1;		
			}

}

if ($outstanding != NULL) {

	print "<h1 class=\"heading_alert\">Outstanding Items</h1>";
	
	echo $outstanding_tasks;
	
	echo $outstanding_expenses;

	echo $outstanding_tender;
	
} else {

unset($outsanding);

}
	
	

?>
