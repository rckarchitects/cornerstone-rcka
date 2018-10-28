<?php

function PhoneMessageEdit($message_id, $user_id) {
	
	global $conn;
	global $user_usertype_current;
	global $user_id_current;

if ($_GET[message_id] != NULL) { $message_id = CleanNumber($_GET[message_id]); }

echo "<h1>Telephone Messages</h1>";

if($message_id > 0) {

	$sql = "SELECT * FROM intranet_phonemessage where message_id = '$message_id'";
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);

	$message_id = $array['message_id'];
	$message_from_id = $array['message_from_id'];
	$message_from_name = $array['message_from_name'];
	$message_from_company = $array['message_from_company'];
	$message_from_number = $array['message_from_number'];
	$message_for_user = $array['message_for_user'];
	$message_text = $array['message_text'];
	$message_viewed = $array['message_viewed'];
	$message_date = $array['message_date'];
	$message_project = $array['message_project'];
	$message_action = $array['message_action'];
	
	
	echo "<h2>Edit Existing Message</h2>";
	echo "<form method=\"post\" action=\"index2.php?page=project_view&amp;message_id=$message_id&amp;project_id=$message_project\">";
	echo "<input type=\"hidden\" name=\"message_id\" value=\"$message_id\" />";
	
} else {
	
	$message_id = $_POST[message_id];
	$message_from_id = $_POST[message_from_id];
	$message_from_name = $_POST[message_from_name];
	$message_from_company = $_POST[message_from_company];
	$message_from_number = $_POST[message_from_number];
	$message_for_user = $_POST[message_for_user];
	$message_text = $_POST[message_text];
	$message_viewed = $_POST[message_viewed];
	$message_date = $_POST[message_date];
	$message_project = $_POST[message_project];
	$message_action = $_POST[message_action];

	echo "<h2>Add New Message</h2>";
	echo "<form method=\"post\" action=\"index2.php?page=phonemessage_list\">";

}

echo "<h3>Details of Caller</h3>";

//echo "<p>Choose from the following contacts<br />";
	//$data_contact_var = "message_from_id";
	//$data_contact_id = $message_from_id;
	//include("dropdowns/inc_data_dropdown_contacts.php");
//echo "</p>";

//echo "<p>Or enter the details manually.</p>";

echo "<p>Name<br /><input type=\"text\" name=\"message_from_name\" size=\"48\" value=\"$message_from_name\" /></p>";
echo "<p>Company<br /><input type=\"text\" name=\"message_from_company\" size=\"48\" value=\"$message_from_company\" /></p>";
echo "<p>Telephone Number<br /><input type=\"text\" name=\"message_from_number\" size=\"48\" value=\"$message_from_number\" />";

echo "<h3>Message</h3>";

echo "<p>Message For<br />";
	$sql = "SELECT user_id, user_name_first, user_name_second FROM intranet_user_details WHERE user_id != $_COOKIE[user] ORDER BY user_name_second";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	echo "<select class=\"inputbox\" name=\"message_for_user\">";

	while ($array = mysql_fetch_array($result)) {

		$user_id = $array['user_id'];
		$user_name_first = $array['user_name_first'];
		$user_name_second = $array['user_name_second'];
		
            echo "<option value=\"$user_id\"";
            if ($user_id == $_GET[user_id]) { echo " selected"; }
            echo ">".$user_name_first."&nbsp;".$user_name_second."</option>";
		}

	echo "</select>";
echo "</p>";

echo "<p>Message Text<br /><textarea name=\"message_text\" cols=\"48\" rows=\"8\">$message_text</textarea></p>";
echo "<p>Send email to user?<br /><input type=\"checkbox\" value=\"yes\" name=\"message_email\" checked=\"checked\" /></p>";
echo "<p><input type=\"submit\" value=\"Submit\" class=\"inputsubmit\" /></p>";
echo "<input type=\"hidden\" name=\"action\" value=\"phonemessage_edit\" />";

if ($message_taken > 0) {
	echo "<input type=\"hidden\" name=\"message_taken\" value=\"$message_taken\" />"; }
	else { echo "<input type=\"hidden\" name=\"message_taken\" value=\"$_COOKIE[user]\" />"; }

echo "</form>";

}

if (intval($_GET[message_id]) > 0) { $message_id = intval($_GET[message_id]); }
elseif (intval($_POST[message_id] > 0)) { $message_id = intval($_POST[message_id]); }
else { $message_id = 0; }

if (intval($_GET[user_id]) > 0) { $user_id = intval($_GET[user_id]); }
elseif (intval($_POST[user_id] > 0)) { $user_id = intval($_POST[user_id]); }
else { $user_id = 0; }

PhoneMessageEdit($message_id, $user_id);

