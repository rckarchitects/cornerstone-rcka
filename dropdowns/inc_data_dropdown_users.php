<?php

	//$sql = "SELECT user_id, user_name_first, user_name_second FROM intranet_user_details WHERE user_active = 1 ORDER BY user_name_second";
	$sql = "SELECT user_id, user_active, user_name_first, user_name_second FROM intranet_user_details ORDER BY user_active DESC, user_name_second";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	print "<select class=\"inputbox\" name=\"$data_user_var\">";

	unset($user_active_test);
	
	while ($array = mysql_fetch_array($result)) {


		$user_id = $array['user_id'];
		$user_active = $array['user_active'];
		$user_name_first = $array['user_name_first'];
		$user_name_second = $array['user_name_second'];
		
		if ( $user_active != $user_active_test) {
				if ($user_active == 0) { echo "<option disabled></option><option disabled><i>Inactive Users</i></option><option disabled>------------------------</option>"; } else { echo "<option disabled><i>Active Users</i></option><option disabled>------------------------</option>"; } 
		$user_active_test = $user_active; }
		
            print "<option value=\"$user_id\"";
            if ($user_id == $data_user_id) { print " selected"; }
            print ">".$user_name_first."&nbsp;".$user_name_second."</option>";
		}

	print "</select>";

?>
