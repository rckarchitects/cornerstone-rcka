<?php

print "<h1>Telephone Messages</h1>";


	$message_id = $_GET[message_id];

	$sql = "SELECT * FROM intranet_phonemessage WHERE message_id = '$message_id' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
		print "<p class=\"menu_bar\">";
		print "<a href=\"index2.php?page=phonemessage_view&amp;status=outstanding\" class=\"menu_tab\">Oustanding Messages</a>";
		print "<a href=\"index2.php?page=phonemessage_view&amp;status=all\" class=\"menu_tab\">All Messages</a>";
		print "<a href=\"index2.php?page=phonemessage_view&amp;status=user\" class=\"menu_tab\">Messages for Others</a>";
		print "</p>";

if (mysql_num_rows($result) > 0) {


		$array = mysql_fetch_array($result);
  
		$message_id = $array['message_id'];
		$message_for_user = $array['message_for_user'];
		$message_taken = $array['message_taken'];
		$message_from_id = $array['message_from_id'];
		$message_from_name = $array['message_from_name'];
		$message_from_company = $array['message_from_company'];
		$message_from_number = $array['message_from_number'];
		$message_project = $array['message_project'];
		$message_viewed = $array['message_viewed'];
		$message_date = $array['message_date'];
		$message_text = $array['message_text'];
		
		print "<p class=\"submenu_bar\">";
		print "<a href=\"index2.php?page=phonemessage_edit&amp;status=new\" class=\"submenu_bar\">Add New</a>";
		print "<a href=\"index2.php?page=phonemessage_edit&amp;status=edit&amp;message_id=$message_id\" class=\"submenu_bar\">Edit</a>";
		if ($message_for_user == $_COOKIE[user] AND $message_viewed == 0) { print "<a href=\"index2.php?page=phonemessage_view_detailed&amp;action=phonemessage_viewed&amp;message_id=$message_id\" class=\"submenu_bar\">Mark As Read</a>"; }
		if ($message_taken == $_COOKIE[user]) { print "<a href=\"index2.php?page=phonemessage_edit&amp;action=phonemessage_delete&amp;message_id=$message_id\" class=\"submenu_bar\" onClick=\"javascript:return confirm('Are you sure you want to delete this entry?')\">Delete</a>"; }
		print "</p>";

		print "<h2>View Message Details</h2>";
		
		print "<table summary=\"View telephone message\">";
		print "<tr><td><strong>Date</strong></td><td><a href=\"index2.php?page=datebook_view_day&amp;time=$message_date\">".TimeFormatDetailed($message_date)."</a></td></tr>";
		$data_contact = $message_from_id;
		if ($message_from_id > 0) {
			print "<tr><td><strong>From</strong></td><td>"; include("dropdowns/inc_data_contacts_name.php"); print "</td></tr>"; }
		elseif ($message_from_name != "") {
			print "<tr><td><strong>From</strong></td><td>".$message_from_name;
				if ($message_from_company != "") { print "<br />".$message_from_company;}
			print "</td></tr>"; }
			
		if ($message_from_number > 0) {
			print "<tr><td><strong>Telephone</strong></td><td>$message_from_number</td></tr>"; }
		
		print "<tr><td><strong>Message Details</strong></td><td>".$message_text."</td></tr>";
		
		print "<tr><td><strong>Message Viewed</strong></td><td>";
		if ($message_viewed > 0) {
			print TimeFormatDetailed($message_viewed); } else { print "No"; }
		print "</td></tr>";

		print "<tr><td><strong>Message For</strong></td><td>";
			$data_user_id = $message_for_user;
			include("dropdowns/inc_data_user_name.php");
		print "</td></tr>";
		
		print "<tr><td><strong>Message Taken By</strong></td><td>";
			$data_user_id = $message_taken;
			include("dropdowns/inc_data_user_name.php");
		print "</td></tr>";
		
		print "</table>";

} else {

print "<p>The telephone message you have requested does not exist.</p>";

}

?>