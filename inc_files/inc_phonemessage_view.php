<?php

if ($_GET[user_id] != NULL) { $user_id_view = $_GET[user_id]; } else { $user_id_view = $_COOKIE[user]; }

print "<h1>Telephone Messages</h1>";

if ($_GET[status] == "all") {
	$sql = "SELECT * FROM intranet_phonemessage WHERE message_for_user = '$user_id_view' ORDER BY message_date DESC";
	$heading_h2 = "All Messages";
} elseif ($_GET[status] == "user") {
	$sql = "SELECT * FROM intranet_phonemessage WHERE message_taken = '$user_id_view' ORDER BY message_date DESC";
	$heading_h2 = "Messages for Others";
} else {
	$sql = "SELECT * FROM intranet_phonemessage WHERE message_for_user = '$user_id_view' AND message_viewed = 0 ORDER BY message_date DESC";
	$heading_h2 = "Outstanding Messages";
}

$result = mysql_query($sql, $conn) or die(mysql_error());


		print "<p class=\"menu_bar\">";
		print "<a href=\"index2.php?page=phonemessage_view&amp;status=outstanding&amp;user_id=$user_id_view\" class=\"menu_tab\">Oustanding Messages</a>";
		print "<a href=\"index2.php?page=phonemessage_view&amp;status=all&amp;user_id=$user_id_view\" class=\"menu_tab\">All Messages</a>";
		print "<a href=\"index2.php?page=phonemessage_view&amp;status=user&amp;user_id=$user_id_view\" class=\"menu_tab\">Messages for Others</a>";
		print "</p>";
		
		print "<h2>$heading_h2";
			if ($_GET[user]) { $data_user_id = $_GET[user]; include("dropdowns/inc_data_user_name.php"); }
		print "</h2>";


		if (mysql_num_rows($result) > 0) {

		print "<table summary=\"Lists all telephone messages\">";

		while ($array = mysql_fetch_array($result)) {
		$message_id = $array['message_id'];
		$message_taken = $array['message_taken'];
		$message_from_id = $array['message_from_id'];
		$message_from_name = $array['message_from_name'];
		$message_from_company = $array['message_from_company'];
		$message_from_number = $array['message_from_number'];
		$message_project = $array['message_project'];
		$message_viewed = $array['message_viewed'];
		$message_date = $array['message_date'];
		$message_text = $array['message_text'];
		
		if ($message_viewed > 0) { $highlight = NULL;} else {  $highlight = "background-color: ".$settings_alertcolor."; font-weight: bold;";}
		if ($message_from_number != "") { $rowspan = "rowspan=\"2\" "; } else { $rowspan = ""; }
		
		print "<tr>";
		print "<td style=\"width: 25%;$highlight\" $rowspan><a href=\"index2.php?page=datebook_view_day&amp;time=$message_date\">".TimeFormatDetailed($message_date)."</a>";
		if ($message_viewed > 0) { echo "<br /><span class=\"minitext\">Viewed: ".TimeFormatDetailed($message_date)."</span>"; }
		echo "</td><td style=\"$highlight\">";
		
		if ($message_from_name != NULL) { print $message_from_name."<br />".$message_from_company; }
		else { $data_contact = $message_from_id; include("dropdowns/inc_data_contacts_name.php"); }
		
		print "</td><td style=\"width: 40%;$highlight\" $rowspan><a href=\"index2.php?page=phonemessage_view_detailed&amp;message_id=$message_id\">".$message_text."</a></td>";

		if ($message_from_number != "") { print "</tr><tr><td style=\"$highlight\">".$message_from_number."</td></tr>"; }
		
		}

		print "</table>";

		} else {

		print "<p>There are no live messages on the system</p>";

		}
		
?>