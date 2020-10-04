<?php



function ListAllProjects($team_id,$active) {
	
	global $conn;
	global $user_usertype_current;
	
	$filter_array = array();
	
	if (intval($team_id) > 0) { $filter_array[] = "proj_team =" . intval($team_id) ; $title = GetTeamName(intval($team_id)); } else { $title = "All Projects"; }
	
	if (!$active OR intval($active) == 1) { $filter_array[] = "proj_active = 1"; $filter_array[] = "proj_fee_track = 1"; $filter_array[] = "ts_fee_prospect = 100"; $filter_array[] = "ts_fee_commence <= '" . date("Y-m-d",time()) . "'"; $filter_array[] = "(ts_fee_time_end + UNIX_TIMESTAMP(ts_fee_commence) >= " . time() . ")"; } else { $filter_array[] = "proj_active = 0"; }
	
	$filter = "WHERE " . implode(" AND ",$filter_array);

		if (!$active OR intval($active) == 1) {
			$sql = "SELECT * FROM intranet_projects LEFT JOIN intranet_team ON team_id = proj_team LEFT JOIN intranet_timesheet_fees ON proj_riba = ts_fee_id " . $filter . " ORDER BY proj_num DESC";
		} else {
			$sql = "SELECT * FROM intranet_projects LEFT JOIN intranet_team ON team_id = proj_team LEFT JOIN intranet_timesheet_fees ON proj_riba = ts_fee_id " . $filter . " ORDER BY proj_num DESC";
		}
		
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		//echo "<p>" . $sql . "</p>";

		$today = TimeFormat(time());


		echo "<h1>Projects</h1><h2>" . $title . "</h2>";
		
		ProjectSubMenu(NULL,$user_usertype_current,"project_list",1);
		ProjectSubMenu(NULL,$user_usertype_current,"project_list",2);
		
		echo "<div class=\"page\">";

		if (mysql_num_rows($result) > 0) {

				echo "<table summary=\"List of all projects\">";

				while ($array = mysql_fetch_array($result)) {
				$proj_num = $array['proj_num'];
				$proj_name = $array['proj_name'];
				$proj_rep_black = $array['proj_rep_black'];
				$proj_client_contact_name = $array['proj_client_contact_name'];
				$proj_contact_namefirst = $array['proj_contact_namefirst'];
				$proj_contact_namesecond = $array['proj_contact_namesecond'];
				$proj_company_name = $array['proj_company_name'];
				$proj_id = $array['proj_id'];
				$proj_fee_track = $array['proj_fee_track'];

				echo "<tr><td width=\"20\" class=\"color\">";

				if ($proj_fee_track > 0) {
					echo "<a href=\"index2.php?page=project_view&amp;proj_id=" . $proj_id . "\">" . $proj_num . "</a>";
				} else {
					echo $proj_num;
				}

				echo "</td><td>";
				
				if ($proj_fee_track > 0) {
					echo "<a href=\"index2.php?page=project_view&amp;proj_id=" . $proj_id . "\">" . $proj_name . "</a>";
				} else {
					echo $proj_name;
				}
				
				echo "</td><td>";
				
				if ($array['ts_fee_text']) {
					echo "<a href=\"index2.php?page=project_fees&amp;proj_id=" . $array['proj_id'] . "\">" . $array['ts_fee_text'] . "</a>";
				} else {
					echo "- Prospect -";
				}
				
				echo "</td><td>";
				
				if ($array['proj_rep_black']) {
					echo GetUserNameOnly($array['proj_rep_black']);
				} else {
					echo "- Not assigned -";
				}
				
				echo "</td><td>";
				
				if ($array['team_id']) {
					echo "<a href=\"index2.php?page=project_all&amp;team=" . $array['team_id'] . "\">" . $array['team_name'] . "</a>";
				} else {
					echo "- Not assigned -";
				}

				echo "</td><td width=\"24\" align=\"center\" class=\"color\">";

				if ($user_usertype_current > 4 OR $user_id_current == $proj_rep_black) {
					echo "<a href=\"index2.php?page=project_edit&amp;status=edit&amp;proj_id=" . $proj_id . "\"><img src=\"images/button_edit.png\" alt=\"Edit Project\" /></a>&nbsp;";
				}

				echo "</td></tr>";

				}

				echo "</table>";

			} else {

			echo "There are no live projects on the system";

			}
		
		echo "</div>";

}

