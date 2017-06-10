<?php

if ($user_usertype_current < 4) {
	

NotAllowed();
	
	
} else {
	
function ListUsers($active) {
	
	GLOBAL $conn;
	
			if ($_GET[list_active] != 1) {
				
				echo "<p>Showing active users only. <a href=\"index2.php?page=user_list&amp;list_active=1\">Click here to show all users</a>.</p>";
				$showactive = " WHERE user_active = '1' ";
				
				
			} else {
				
				echo "<p>Showing all users. <a href=\"index2.php?page=user_list\">Click here to show active users only</a>.</p>";
				unset($showactive);
			}

			$sql = "SELECT * FROM intranet_user_details $showactive ORDER BY user_active DESC, user_name_second";
			$result = mysql_query($sql, $conn);
			
			
			echo "<table><tr><th>Name</th><th>Initials</th><th>Date Started</th><th>Date Ended</th><th>Mobile</th><th>Email</th><th style=\"text-align: right;\" colspan=\"2\">Hourly Rate (Cost)</th></tr>";
			
			
			while ($array = mysql_fetch_array($result)) {
				
					$user_id = $array['user_id'];
					$user_name_first = $array['user_name_first'];
					$user_name_second = $array['user_name_second'];
					$user_initials = $array['user_initials'];
					$user_num_mob = $array['user_num_mob'];
					$user_email = $array['user_email'];
					$user_active = $array['user_active'];
					$user_rate = $array['user_rate'];
					if ($array['user_user_added'] > 0) { $user_user_added = TimeFormatDay($array['user_user_added']); } else { $user_user_added = "-"; }
					if ($array['user_user_ended'] > 0) { $user_user_ended = TimeFormatDay($array['user_user_ended']); } else { $user_user_ended = "-"; }
					$user_user_rate = "&pound;" . number_format($array['user_user_rate'],2);
					
					if ($user_active == "1") { $user_active_print = "Active Users"; } else { $user_active_print = "Inactive Users"; }
					
					if ($current_active != $user_active) { echo "<tr><td colspan=\"8\"><strong>$user_active_print</strong></td></tr>"; $current_active = $user_active;  }
					
					echo "<tr><td><a href=\"index2.php?page=user_view&amp;user_id=$user_id\">$user_name_first $user_name_second</a></td><td>$user_initials</td><td>$user_user_added</td><td>$user_user_ended</td><td>$user_num_mob</td><td>$user_email</td><td style=\"text-align: right;\">$user_user_rate</td><td><a href=\"index2.php?page=user_edit&amp;status=edit&user_id=$user_id\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a></td></tr>";

								
			}

			echo "</table>";
		
						
}


echo "<h1>All Users</h1>";

ListUsers();


}



