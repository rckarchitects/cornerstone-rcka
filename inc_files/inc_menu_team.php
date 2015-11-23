<?php

$sql = "SELECT * FROM intranet_user_details WHERE user_active = 1 order by user_name_second";
$result = mysql_query($sql, $conn) or die(mysql_error());

print "<ul class=\"button_left\">";

while ($array = mysql_fetch_array($result)) {
$user_name_first = $array['user_name_first'];
$user_name_second = $array['user_name_second'];
$user_num_mob = $array['user_num_mob'];
$user_num_home = $array['user_num_home'];
$user_num_extension = $array['user_num_extension'];
$user_email = $array['user_email'];
$user_id = $array['user_id'];


print "<li><a href=\"index2.php?page=user_view&amp;user_id=$user_id \">";
if ($user_id == $user_id_current) { print "<b>"; }
print $user_name_first." ".$user_name_second;
if ($user_id == $user_id_current) { print "</b>"; }
print "</a>&nbsp;";

if ($user_num_extension != NULL) { echo "[" . $user_num_extension . "]&nbsp;"; }

if ($user_usertype_current > 3 OR $user_id == $user_id_current) {
print "<a href=\"index2.php?page=user_edit&amp;user_id=$user_id\"><img src=\"images/button_edit.png\" alt=\"Edit User\" /></a>";
}

if ($user_id == $user_id_current) {
	print "<br /><span class=\"minitext\"><a href=\"index2.php?page=user_password\">[change my password]</a></span>";
}

if ($user_num_mob != NULL) {
print "<br />M $user_num_mob";
}

// Let's check for outstanding messages

	$nowtime = time();

	$sql2 = "SELECT * FROM intranet_phonemessage WHERE message_for_user = '$user_id' AND message_viewed = 0";
	$result2 = mysql_query($sql2, $conn) or die(mysql_error());
	$messages_outstanding = mysql_num_rows($result2);
	$array_messages = mysql_fetch_array($result2);
	$message_for_user = $array_messages['message_for_user'];
	

	if ($messages_outstanding == 1) {
	$message_value = "message";
	} else {
	$message_value = "messages";
	}

if ($messages_outstanding > 0) {
	if ($message_for_user == $user_id) {
	print "<br /><a href=\"index2.php?page=phonemessage_view&amp;user_id=$user_id\">$messages_outstanding $message_value to be read.</a>";
	} elseif ($user_usertype_current > 3) {
	print "<br /><a href=\"index2.php?page=phonemessage_view&amp;user_id=$user_id\">$messages_outstanding $message_value to be read.</a>";
	} else {
	print "<br />$messages_outstanding $message_value to be read.";
	}
}

// Now that's done, let's check for outstanding tasks

if ($tasks_outstanding > 0 AND $user_id == $_COOKIE[user]) {
	print "<br />Tasks: <a href=\"index2.php?page=tasklist_view&amp;subcat=user\">".$tasks_outstanding."</a>";
} elseif ($tasks_outstanding > 0) {
  	print "<br />Tasks: ".$tasks_outstanding;
}

if ($tasks_overdue > 0) {
	print ", Overdue: $tasks_overdue";
}

print "</li>";

// OK, finished. Now repeat the array.

}

print "</ul>";

echo "<p style=\"background: white; padding: 3px;\"><a href=\"index2.php?page=page_ccomm\">IT Technical Support:<br />CComm IT<br />T 01727 754 180</a></p>";

?>
