<?php

print "<h1>Timesheets</h1>";

print "
<p class=\"submenu_bar\">
<a href=\"index2.php?page=timesheet_edit\" class=\"submenu_bar\">New Entry</a>
</p>";

print "<h2>Week List</h2>";


// First, deal with any error messages that appear

if ($_GET[error] == "overdue") {
print "<p class=\"alert\">You have timesheets outstanding - these must be completed before you are able to log in to the timesheet system.</p>";
} elseif ($error != NULL) {
print "<p class=\"alert\">$error</p>";
}

// Begin drawing the main table

print "<table summary=\"List of recent weeks\">";

// Calculate the current time and the time 8 weeks ago

	$time_now = time();
	$time_now_day = date("w", $time_now);

	$time_to_weekbegin = $time_now_day - 1;
	$time_to_weekbegin = $time_to_weekbegin * 86400;
	$time_weekbegin = $time_now - $time_to_weekbegin;
	$date_weekbegin_date = date("j",$time_weekbegin);
	$date_weekbegin_month = date("n",$time_weekbegin);
	$date_weekbegin_year = date("Y",$time_weekbegin);

	$time_weekbegin = mktime(12,0,0,$date_weekbegin_month, $date_weekbegin_date, $date_weekbegin_year);
	// $time_prev_begin = $time_weekbegin - 4838400;
	$time_prev_begin = $time_weekbegin - 4881599;


	$currentweek = NULL;



// Begin the table showing week beginnings
print "<tr>";
print "<td class=\"color\">Week Beginning<br />(Monday)</td>";

if ($user_usertype_current > 2) { print "<td class=\"color\">Expenses</td>"; }

print "<td class=\"color\">Week Ending<br />(Friday)</td>";
print "</tr>";

// Array through the weeks

for ($counter = 1; $counter<=10; $counter++) {

	// Check for the current week
	if ( $time_prev_begin < $time_now && $time_prev_begin > ($time_now-604800) ) {
		$class_currentweek = "class=\"nocolor\" bgcolor=\"#BDCDBB\"";
	} else {
		$class_currentweek = "class=\"color\"";
	}

	$date_prev_begin = date("jS F Y",$time_prev_begin);
	$time_prev_end = $time_prev_begin+345600;
	$date_prev_end = date("jS F Y",$time_prev_end);

print "<tr>";
print "<td $class_currentweek><a href=\"index2.php?page=timesheet_edit&amp;week=$time_prev_begin\">$date_prev_begin</a></td>";

if ($user_usertype_current > 2) { print "<td $class_currentweek><a href=\"index2.php?page=expenses_add&amp;week=$time_prev_begin\">Add</a></td>"; }


print "<td $class_currentweek><a href=\"index2.php?page=timesheet_edit&amp;week=$time_prev_begin\">$date_prev_end</a></td>";



print "</tr>";

	$time_prev_begin = $time_prev_begin + 604800;
}

print "</table>";

?>


