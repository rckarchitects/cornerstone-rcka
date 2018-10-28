<?php


if (intval($_GET[user_id]) > 0) { $user_id = intval($_GET[user_id]); }
elseif (intval($_POST[user_id] > 0)) { $user_id = intval($_POST[user_id]); }
else { $user_id = 0; }

function GetUserName($user_id) {
	
	GLOBAL $conn;
	GLOBAL $user_usertype_current;
	$user_id = intval($user_id);
	$sql = "SELECT * FROM intranet_user_details WHERE user_id = '$user_id' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];
	echo "<h1>Users</h1>";
	if ($user_id > 0 && $user_usertype_current > 3) { 
		echo "<h2>Edit details for " . $user_name_first . "&nbsp;" . $user_name_second . "</h2>";
	} elseif ($user_id == 0 && $user_usertype_current > 3) { 
		echo "<h2>Add New User</h2>";
	} else {
		echo "<h2>Error</h2>";
	}
}

function UserForm ($user_id) {
	
	GLOBAL $user_usertype_current;
	GLOBAL $conn;
	
	$sql = "SELECT * FROM intranet_user_details WHERE user_id = '$user_id' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$user_address_county = $array['user_address_county'];
	$user_address_postcode = $array['user_address_postcode'];
	$user_address_town = $array['user_address_town'];
	$user_address_3 = $array['user_address_3'];
	$user_address_2 = $array['user_address_2'];
	$user_address_1 = $array['user_address_1'];
	$user_name_first = $array['user_name_first'];
	$user_name_second = $array['user_name_second'];
	$user_num_extension = $array['user_num_extension'];
	$user_num_mob = $array['user_num_mob'];
	$user_num_home = $array['user_num_home'];
	$user_email = $array['user_email'];
	$user_usertype = intval ( $array['user_usertype'] );
	$user_active = $array['user_active'];
	$user_username = $array['user_username'];
	$user_user_rate = $array['user_user_rate'];
	$user_user_added = $array['user_user_added'];
	$user_user_ended = $array['user_user_ended'];
	$user_user_timesheet = $array['user_user_timesheet'];
	$user_holidays = $array['user_holidays'];
	$user_initials = $array['user_initials'];
	$user_prop_target = $array['user_prop_target'];
	$user_timesheet_hours = $array['user_timesheet_hours'];
	$user_notes = $array['user_notes'];
	
	echo "<form method=\"post\" action=\"index2.php?page=user_list\">";
	
	echo "<div><h3>Name</h3>";
	
		echo "<p>First Name<br /><input type=\"text\" name=\"user_name_first\" value=\"$user_name_first\" maxlength=\"50\" size=\"32\" required=\"required\" /></p>";
		echo "<p>Surname<br /><input type=\"text\" name=\"user_name_second\" value=\"$user_name_second\" maxlength=\"50\" size=\"32\" required=\"required\" /></p>";
		if ($user_usertype_current > 2) {
		echo "<p>Username<br /><input type=\"text\" name=\"user_username\" value=\"$user_username\" maxlength=\"50\" size=\"32\" required=\"required\" /></p>";
		} else {
		echo "<p>Username</p><p><span style=\"margin: 2px; padding: 2px; background: #fff;\">$user_username</span> (Cannot be changed)</p>";
		}
		echo "<p>Initials<br /><input type=\"text\" name=\"user_initials\" value=\"$user_initials\" maxlength=\"12\" size=\"32\" /></p>";
		echo "<p>Email<br /><input type=\"text\" name=\"user_email\" value=\"$user_email\" maxlength=\"50\" size=\"32\" type=\"email\" /></p>";
		
	echo "</div>";
	
	echo "<div><h3>Home Address</h3>";
	
		echo "<p>Address<br /><input type=\"text\" name=\"user_address_1\" value=\"$user_address_1\" maxlength=\"50\" size=\"32\" /><br />";
		echo "<input type=\"text\" name=\"user_address_2\" value=\"$user_address_2\" maxlength=\"50\" size=\"32\" /><br />";
		echo "<input type=\"text\" name=\"user_address_3\" value=\"$user_address_3\" maxlength=\"50\" size=\"32\" /></p>";
		
		echo "<p>Town / City<br /><input type=\"text\" name=\"user_address_town\" value=\"$user_address_town\" maxlength=\"50\" size=\"32\" /></p>";
		echo "<p>County<br /><input type=\"text\" name=\"user_address_county\" value=\"$user_address_county\" maxlength=\"50\" size=\"32\" /></p>";
		echo "<p>Postcode<br /><input type=\"text\" name=\"user_address_postcode\" value=\"$user_address_postcode\" maxlength=\"50\" size=\"32\" /></p>";
		
	echo "</div>";
	
	echo "<div><h3>Telephone</h3>";
	
		echo "<p>Extension<br /><input type=\"text\" name=\"user_num_extension\" value=\"$user_num_extension\" maxlength=\"50\" size=\"32\" /></p>";
		echo "<p>Mobile<br /><input type=\"text\" name=\"user_num_mob\" value=\"$user_num_mob\" maxlength=\"50\" size=\"32\" /></p>";
		echo "<p>Home<br /><input type=\"text\" name=\"user_num_home\" value=\"$user_num_home\" maxlength=\"50\" size=\"32\" /></p>";
		
	echo "</h3>";
	
	
	echo "<div><h3>Notes</h3>";
	
		echo "<textarea name=\"user_notes\" style=\"width: 95%; height: 150px;\">$user_notes</textarea>";
		
	echo "</div>";
	
	if ($user_usertype_current > 3) {
	
		echo "<div><h3>Details</h3>";
		
		
		echo "<p>User Type<br />";
		
		UserAccessType("user_usertype",0,$user_usertype,0);
		
		echo "<p><input type=\"checkbox\" name=\"user_active\" value=\"1\"";
		if ($user_active == 1 OR $user_id == NULL) { echo "checked=checked "; }
		echo "/>&nbsp;User Active</p>";
		echo "<p>Holiday Allowance<br /><input type=\"text\" name=\"user_holidays\" value=\"$user_holidays\" maxlength=\"6\" size=\"32\" type=\"number\" /></p>";
		echo "<p>Hourly Rate (excluding overheads)<br /><input name=\"user_user_rate\" value=\"$user_user_rate\" maxlength=\"12\" size=\"32\" type=\"number\" /></p>";
		echo "<p>Hours per Week<br /><input type=\"number\" name=\"user_timesheet_hours\" value=\"$user_timesheet_hours\" size=\"32\"  /></p>";
		echo "<p><input type=\"checkbox\" name=\"user_user_timesheet\" value=\"1\"";
		if ($user_user_timesheet == 1 OR $user_id == NULL) { echo "checked=checked "; }
		echo "/>&nbsp;Require Timesheets</p>";
		echo "<p>Non-Fee Earning Time Allowance<br />";
		echo "<select name=\"user_prop_target\">";
		echo "<option value=\"0\" "; if ($user_prop_target == 0) { echo "selected=\"selected\""; } ; echo ">None</option>";
		echo "<option value=\"0.05\" "; if ($user_prop_target == 0.05) { echo "selected=\"selected\""; } ; echo ">%5</option>";
		echo "<option value=\"0.1\" "; if ($user_prop_target == 0.1) { echo "selected=\"selected\""; } ; echo ">10%</option>";
		echo "<option value=\"0.15\" "; if ($user_prop_target == 0.15) { echo "selected=\"selected\""; } ; echo ">15%</option>";
		echo "<option value=\"0.2\" "; if ($user_prop_target == 0.2) { echo "selected=\"selected\""; } ; echo ">20%</option>";
		echo "<option value=\"0.25\" "; if ($user_prop_target == 0.25) { echo "selected=\"selected\""; } ; echo ">25%</option>";
		echo "<option value=\"0.3\" "; if ($user_prop_target == 0.3) { echo "selected=\"selected\""; } ; echo ">30%</option>";
		echo "<option value=\"0.35\" "; if ($user_prop_target == 0.35) { echo "selected=\"selected\""; } ; echo ">35%</option>";
		echo "<option value=\"0.4\" "; if ($user_prop_target == 0.4) { echo "selected=\"selected\""; } ; echo ">40%</option>";
		echo "<option value=\"0.45\" "; if ($user_prop_target == 0.45) { echo "selected=\"selected\""; } ; echo ">45%</option>";
		echo "<option value=\"0.5\" "; if ($user_prop_target == 0.5) { echo "selected=\"selected\""; } ; echo ">50%</option>";
		echo "<option value=\"0.55\" "; if ($user_prop_target == 0.55) { echo "selected=\"selected\""; } ; echo ">55%</option>";
		echo "<option value=\"0.60\" "; if ($user_prop_target == 0.6) { echo "selected=\"selected\""; } ; echo ">60%</option>";
		echo "<option value=\"0.65\" "; if ($user_prop_target == 0.65) { echo "selected=\"selected\""; } ; echo ">65%</option>";
		echo "<option value=\"0.70\" "; if ($user_prop_target == 0.7) { echo "selected=\"selected\""; } ; echo ">70%</option>";
		echo "<option value=\"0.75\" "; if ($user_prop_target == 0.75) { echo "selected=\"selected\""; } ; echo ">75%</option>";
		echo "<option value=\"0.80\" "; if ($user_prop_target == 0.8) { echo "selected=\"selected\""; } ; echo ">80%</option>";
		echo "<option value=\"0.85\" "; if ($user_prop_target == 0.85) { echo "selected=\"selected\""; } ; echo ">85%</option>";
		echo "<option value=\"0.9\" "; if ($user_prop_target == 0.9) { echo "selected=\"selected\""; } ; echo ">90%</option>";
		echo "<option value=\"0.95\" "; if ($user_prop_target == 0.95) { echo "selected=\"selected\""; } ; echo ">95%</option>";
		echo "<option value=\"1\" "; if ($user_prop_target == 1) { echo "selected=\"selected\""; } ; echo ">100%</option>";
		echo "</select></p>";
		echo "</div>";
	

	
	echo "<div><h3>Dates</h3>";
		
		if ($user_user_added > 0) {
			$user_user_added_print = date("Y",$user_user_added) . "-" . date("m",$user_user_added) . "-" . date("d",$user_user_added);
		} elseif ($user_id == NULL) {
			$user_user_added_print = date("Y",time()) . "-" . date("m",time()) . "-" . date("d",time());
		} else { unset($user_user_added); }
		
		if ($user_user_ended > 0) {
			$user_user_ended_print = date("Y",$user_user_ended) . "-" . date("m",$user_user_ended) . "-" . date("d",$user_user_ended);
		} else { unset($user_user_ended); }
	
		echo "<p>Date Started<br /><input type=\"date\" name=\"user_user_added\" value=\"$user_user_added_print\" /></p>";
		
		echo "<p>Date Ended<br /><input type=\"date\" name=\"user_user_ended\" value=\"$user_user_ended_print\" /></p>";
		
	echo "</div>";
	
	}
	
	echo "<div><h3>Password</h3>";
	
	if ($user_id > 0) {
	
		echo "<p><input type=\"checkbox\" name=\"update_user_password\" value=\"yes\" />&nbsp;Update Password?</p>";
		
	}
		
		echo "<p>Enter New Password<br /><input type=\"password\" name=\"user_password\" value=\"\" /></p>";
		
	echo "</div>";
	
	if ($user_id > NULL) {
	echo "<input type=\"hidden\" name=\"action\" value=\"user_update\" />";
	echo "<input type=\"hidden\" name=\"user_id\" value=\"$user_id\" />";
	echo "<input type=\"submit\" value=\"Update\" />";
	} else {
	echo "<input type=\"submit\" value=\"Submit\" />";
	echo "<input type=\"hidden\" name=\"action\" value=\"user_update\" />";
	}
	
	echo "</form></p>";
	
	
	
	
}



if ($_GET[user_add] == "true" && $user_usertype_current > 3) {
	
	unset($user_id);
	
	echo "<h1>Add New User</h1>";
	
} else {
	
	GetUserName($user_id);
	
}



if ($user_usertype_current > 3 OR intval($user_id) == intval($user_id_current)) {

	UserForm($user_id);

} else {
	
	InsufficientRights();	
	
}