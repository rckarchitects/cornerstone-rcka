<?php

if ($user_usertype_current > 4) {

print "<h1>Add New User</h1>";

print "<form method=\"post\" action=\"index2.php?page=team_list\">";

print "<fieldset><legend>Login Details</legend>";

			print "<p>Username<br />";
			print "<input type=\"text\" name=\"user_username\" maxlength=\"100\" class=\"inputbox\" size=\"44\" value=\"$user_username\" />";
			print "</p>";
			
			print "<p>Password<br />";
			print "<input type=\"password\" name=\"user_password_1\" maxlength=\"100\" class=\"inputbox\" value=\"$user_password_1\" size=\"44\" />";
			print "</p>";
			
			print "<p>Confirm Password<br />";
			print "<input type=\"password\" name=\"user_password_2\" maxlength=\"25\" class=\"inputbox\" size=\"44\" value=\"$user_password_2\" />";
			print "</p>";

print "</fieldset>";

print "<fieldset><legend>System Information</legend>";

			print "<p>Active User?<br />";
			print "<input type=\"checkbox\" name=\"user_active\" maxlength=\"25\" class=\"inputbox\" value=\"1\" size=\"44\" ";
			if ($user_active != "0") { print "checked"; }
			print " />";
			print "</p>";
			
			print "<p>Require Timesheet?<br />";
			print "<input type=\"checkbox\" name=\"user_user_timesheet\" maxlength=\"25\" class=\"inputbox\" value=\"1\" size=\"44\" ";
			if ($user_user_timesheet != "0") { print "checked"; }
			print " />";
			print "</p>";
			
			print "<p>Hourly Rate<br />";
			print "<input type=\"text\" name=\"user_user_rate\" maxlength=\"100\" class=\"inputbox\" size=\"44\" value=\"$user_user_rate\" />";
			print "</p>";
			
			print "<p>Holiday Allowance<br />";
			print "<input type=\"text\" name=\"user_holidays\" maxlength=\"2\" class=\"inputbox\" size=\"44\" value=\"$user_holidays\" />";
			print "</p>";
			
			print "<p>User Type<br />";
			print "<select name=\"user_usertype\" class=\"inputbox\">";
			print "<option value=\"0\">[0] Guest</option>";
			print "<option value=\"1\">[1] Standard User</option>";
			print "<option value=\"2\">[2] Project Leader</option>";
			print "<option value=\"3\">[3] Director</option>";
			print "<option value=\"4\">[4] Administrator</option>";
			print "</select></p>";

print "</fieldset>";

print "<fieldset><legend>Personal Details</legend>";

			print "<p>First Name<br />";
			print "<input type=\"text\" name=\"user_name_first\" maxlength=\"75\" class=\"inputbox\" size=\"44\" value=\"$user_name_first\" />";
			print "</p>";
			
			print "<p>Last Name<br />";
			print "<input type=\"text\" name=\"user_name_second\" maxlength=\"75\" class=\"inputbox\" size=\"44\" value=\"$user_name_second\" />";
			print "</p>";
			
			print "<p>Address Line 1<br />";
			print "<input type=\"text\" name=\"user_address_1\" maxlength=\"99\" class=\"inputbox\" size=\"44\" value=\"$user_address_1\" />";
			print "</p>";
			
			print "<p>Address Line 2<br />";
			print "<input type=\"text\" name=\"user_address_2\" maxlength=\"99\" class=\"inputbox\" size=\"44\" value=\"$user_address_2\" />";
			print "</p>";
			
			print "<p>Address Line 3<br />";
			print "<input type=\"text\" name=\"user_address_3\" maxlength=\"99\" class=\"inputbox\" size=\"44\" value=\"$user_address_3\" />";
			print "</p>";
			
			print "<p>Town / City<br />";
			print "<input type=\"text\" name=\"user_address_town\" maxlength=\"99\" class=\"inputbox\" size=\"44\" value=\"$user_address_town\" />";
			print "</p>";
			
			print "<p>County<br />";
			print "<input type=\"text\" name=\"user_address_county\" maxlength=\"99\" class=\"inputbox\" size=\"44\" value=\"$user_address_county\" />";
			print "</p>";

			print "<p>Postcode<br />";
			print "<input type=\"text\" name=\"user_address_postcode\" maxlength=\"99\" class=\"inputbox\" size=\"44\" value=\"$user_address_postcode\" />";
			print "</p>";

print "</fieldset>";

print "<fieldset><legend>Contact Details</legend>";

			print "<p>Home Telephone Number<br />";
			print "<input type=\"text\" name=\"user_num_home\" maxlength=\"50\" class=\"inputbox\" size=\"44\" value=\"$user_num_home\" />";
			print "</p>";

			print "<p>Mobile Telephone Number<br />";
			print "<input type=\"text\" name=\"user_num_mob\" maxlength=\"50\" class=\"inputbox\" size=\"44\" value=\"$user_num_mob\" />";
			print "</p>";
			
			print "<p>Office Extension<br />";
			print "<input type=\"text\" name=\"user_num_extension\" maxlength=\"50\" class=\"inputbox\" size=\"44\" value=\"$user_num_extension\" />";
			print "</p>";
			
			print "<p>Email Address<br />";
			print "<input type=\"text\" name=\"user_email\" maxlength=\"99\" class=\"inputbox\" size=\"44\" value=\"$user_email\" />";
			print "</p>";

print "</fieldset>";

	  		print "<p><input type=\"hidden\" name=\"action\" value=\"team_add\" /></p>";
	  		print "<p><input type=\"submit\" value=\"Add User\" class=\"inputsubmit\" /></p>";

print "</form>";

} else {

print "<h1>Access Denied</h1>";

print "<p>You do not have access rights to this page.</p>";

}

?>
