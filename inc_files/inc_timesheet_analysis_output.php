<?php

if (intval ( $_POST[proj_id] ) > 0) {

	echo "<h2>Timesheet Analysis</h2>";

} else {
	
	echo "<h1>Timesheet Analysis</h1>";
	
}


function ListProjectsbyHours($start,$end) {
	
	global $conn;
	
	
	
	if ($start != "0000-00-00" && $end != "0000-00-00") {
	
			$start = CreateDays($start,1);
			$end = CreateDays($end,24);
			
			if (!$_POST[allprojects]) { $proj_fee_track = "AND proj_fee_track = 1";  unset($add_text); } else { unset($proj_fee_track);  $add_text = " (including non fee-earning projects)"; }
			
			$sql = "SELECT SUM(ts_hours) FROM intranet_timesheet LEFT JOIN intranet_projects ON proj_id = ts_project WHERE ts_entry BETWEEN $start AND $end $proj_fee_track";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			$array = mysql_fetch_array($result);
			$total_working_hours = $array['SUM(ts_hours)'] ;
			
			$period_cost = 0;
			$period_cost_factorerd = 0;
			$period_hours = 0;
			$percent_accummulative = 0;
			
			echo "<p>Total hours for period from " . TimeFormat($start) . " to " . TimeFormat($end) . ": <u>" . number_format ( round ( $total_working_hours ) ) . "</u>$add_text.</p>";
			
			

			$sql = "SELECT proj_num, proj_name, SUM(ts_hours), SUM(ts_rate * ts_hours), SUM(ts_cost_factored), proj_id FROM intranet_timesheet LEFT JOIN intranet_projects ON proj_id = ts_project WHERE ts_entry BETWEEN $start AND $end $proj_fee_track GROUP BY ts_project ORDER BY SUM(ts_hours) DESC";

			$result = mysql_query($sql, $conn) or die(mysql_error());
			
			echo "<table><tr><th>Project</th><th style=\"text-align: right; width: 15%;\">Cost (Hours)</th><th style=\"text-align: right; width: 15%;\">Cost (Factored)</th><th style=\"text-align: right; width: 15%;\">Hours</th><th style=\"text-align: right; width: 15%;\">Percentage of Total</th><th style=\"text-align: right; width: 15%;\">Percentage of Total (Accumulative)</th></tr>";
			
			while ($array = mysql_fetch_array($result)) {
				
				$period_cost = $period_cost + $array['SUM(ts_rate * ts_hours)'];
				$period_cost_factorerd = $period_cost_factorerd + $array['SUM(ts_cost_factored)'];
				$period_hours = $period_hours + $array['SUM(ts_hours)'];
				$percent_accummulative = $percent_accummulative + 100 * ( $array['SUM(ts_hours)'] / $total_working_hours );
				
				echo "<tr><td><a href=\"index2.php?page=project_view&amp;proj_id=" . $array['proj_id'] . "\">" . $array['proj_num'] . " " . $array['proj_name'] . "</a></td><td style=\"text-align: right;\">&pound;" . number_format ( round ($array['SUM(ts_rate * ts_hours)']) , 0 ) . "</td><td style=\"text-align: right;\">&pound;" . number_format ( round ($array['SUM(ts_cost_factored)']) , 0 ) . "</td><td style=\"text-align: right;\">" . number_format ( round ($array['SUM(ts_hours)']) , 0 ) . "</td><td style=\"text-align: right;\">" . number_format (  100 * ( $array['SUM(ts_hours)'] / $total_working_hours) , 2 ) . "%</td><td style=\"text-align: right;\">" . number_format (   $percent_accummulative , 2 ) . "%</td></tr>";
				
				
				
			}
			
			echo "<tr><td><strong>Total</td><td style=\"text-align: right;\"><strong>&pound;" . number_format ( round ($period_cost) , 0 ) . "</strong></td><td style=\"text-align: right;\"><strong>&pound;" . number_format ( round ($period_cost_factorerd) , 0 ) . "</strong></td><td style=\"text-align: right;\"><strong>" . number_format ( round ($period_hours) , 0 ) . "</strong></td><td style=\"text-align: right;\" colspan=\"2\"><strong>" . number_format (   $percent_accummulative , 2 ) . "%</strong></td></tr>";
			
			echo "</table>";
			
	}
	
}

function ListHoursByUser($proj_id,$start,$end) {
	
	global $conn;
	
	if ($start != "0000-00-00" && $end != "0000-00-00") {
	
			$start = CreateDays($start,1);
			$end = CreateDays($end,24);
			
			
			$sql = "SELECT SUM(ts_hours) FROM intranet_timesheet WHERE ts_project = $proj_id AND ts_project = $proj_id AND ts_entry BETWEEN $start AND $end";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			$array = mysql_fetch_array($result);
			$total_working_hours = $array['SUM(ts_hours)'] ;

			
			$period_cost = 0;
			$period_cost_factorerd = 0;
			$period_hours = 0;
			$percent_accummulative = 0;
			
			echo "<p>Total hours for period from " . TimeFormat($start) . " to " . TimeFormat($end) . ": <u>" . number_format ( round ( $total_working_hours ) ) . "</u>.</p>";
			
			

			$sql = "SELECT user_name_first, user_name_second, SUM(ts_hours), SUM(ts_rate * ts_hours), SUM(ts_cost_factored) FROM intranet_projects, intranet_timesheet  LEFT JOIN intranet_user_details ON user_id = ts_user WHERE proj_id = $proj_id AND ts_project = $proj_id AND ts_entry BETWEEN $start AND $end GROUP BY user_id ORDER BY SUM(ts_hours) DESC";
			

			$result = mysql_query($sql, $conn) or die(mysql_error());
			
			echo "<table><tr><th>User</th><th style=\"text-align: right; width: 15%;\">Cost (Hours)</th><th style=\"text-align: right; width: 15%;\">Cost (Factored)</th><th style=\"text-align: right; width: 15%;\">Hours</th><th style=\"text-align: right; width: 15%;\">Percentage of Total</th><th style=\"text-align: right; width: 15%;\">Percentage of Total (Accumulative)</th></tr>";
			
			while ($array = mysql_fetch_array($result)) {
				
				$period_cost = $period_cost + $array['SUM(ts_rate * ts_hours)'];
				$period_cost_factorerd = $period_cost_factorerd + $array['SUM(ts_cost_factored)'];
				$period_hours = $period_hours + $array['SUM(ts_hours)'];
				$percent_accummulative = $percent_accummulative + 100 * ( $array['SUM(ts_hours)'] / $total_working_hours );
				
				echo "<tr><td><a href=\"index2.php?page=project_view&amp;proj_id=" . $array['proj_id'] . "\">" . $array['user_name_first'] . " " . $array['user_name_second'] . "</a></td><td style=\"text-align: right;\">&pound;" . number_format ( round ($array['SUM(ts_rate * ts_hours)']) , 0 ) . "</td><td style=\"text-align: right;\">&pound;" . number_format ( round ($array['SUM(ts_cost_factored)']) , 0 ) . "</td><td style=\"text-align: right;\">" . number_format ( round ($array['SUM(ts_hours)']) , 0 ) . "</td><td style=\"text-align: right;\">" . number_format (  100 * ( $array['SUM(ts_hours)'] / $total_working_hours) , 2 ) . "%</td><td style=\"text-align: right;\">" . number_format (   $percent_accummulative , 2 ) . "%</td></tr>";
				
				
				
			}
			
			echo "<tr><td><strong>Total</td><td style=\"text-align: right;\"><strong>&pound;" . number_format ( round ($period_cost) , 0 ) . "</strong></td><td style=\"text-align: right;\"><strong>&pound;" . number_format ( round ($period_cost_factorerd) , 0 ) . "</strong></td><td style=\"text-align: right;\"><strong>" . number_format ( round ($period_hours) , 0 ) . "</strong></td><td style=\"text-align: right;\" colspan=\"2\"><strong>" . number_format (   $percent_accummulative , 2 ) . "%</strong></td></tr>";
			
			echo "</table>";
			
	}
	
}


if ($_POST[output] == "ListProjectsbyHours") {

	ListProjectsbyHours($_POST[period_date_start],$_POST[period_date_end]);

} elseif ($_POST[output] == "ListHoursByUser") {

	ListHoursByUser($_POST[proj_id],$_POST[period_date_start],$_POST[period_date_end]);

}


