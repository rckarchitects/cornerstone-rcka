<?php


	$sql_proj = "SELECT proj_id, proj_num, proj_name FROM intranet_projects WHERE proj_id = $proj_id LIMIT 1";
	$result_proj = mysql_query($sql_proj, $conn) or die(mysql_error());
	$array_proj = mysql_fetch_array($result_proj);
	$proj_id = $array_proj['proj_id'];
	$proj_num = $array_proj['proj_num'];
	$proj_name = $array_proj['proj_name'];
	
	if (intval ($_GET[showdetail]) > 0) {
		
		$showdetail = intval ( $_GET[showdetail] );
		
	}
	
	echo "<h1>Planning Conditions for <a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a></h1>";
	

	
	$sql_conditions = "SELECT * FROM intranet_projects_planning LEFT JOIN contacts_companylist ON company_id = condition_responsibility WHERE condition_project = $proj_id ORDER BY condition_ref, condition_number";
	$result_conditions = mysql_query($sql_conditions, $conn) or die(mysql_error());
	
		echo "<p class=\"submenu_bar\">";
		if ($_GET[showdetail] == NULL && mysql_num_rows($result_conditions) > 0) {
			echo "<a href=\"index2.php?page=project_planningcondition_list&amp;proj_id=$proj_id&amp;showdetail=1\" class=\"submenu_bar\">Detailed List</a>";
		}
		echo "<a href=\"index2.php?page=project_planningcondition_edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add Planning Condition&nbsp;<img src=\"images/button_new.png\" alt=\"Add Planning Condition\" /></a>";
		echo "</p>";
	
	if (mysql_num_rows($result_conditions) > 0) {
		
		
		echo "<table><tr><th colspan=\"2\">Condition Reference</th><th>Condition Number</th><th>Responsibility</th><th>Condition Type</th><th>Date Submitted</th><th>Date Discharged</th></tr>";
		while ($array_conditions = mysql_fetch_array($result_conditions)) {
			
			unset($background);

			if ($array_conditions['condition_type'] == "Informative Only") { $condition_approved = "- Not Applicable -";} elseif ($array_conditions['condition_approved'] != "0000-00-00") { $condition_approved = date( "j M Y", AssessDays ( $array_conditions['condition_approved'] ) ); $background = " style=\"background: rgba(255,0,0,0.5);\" ";  } else { $condition_approved = "- None -"; $background = " style=\"background: rgba(255,255,0,0.5);\" "; }
			
			if ($array_conditions['condition_type'] == "Informative Only") {
				$condition_submitted = "- Not Applicable -"; 
			} elseif ($array_conditions['condition_submitted'] != "0000-00-00") { $condition_submitted = date( "j M Y", AssessDays ( $array_conditions['condition_submitted'] ) ); 
			} else {
				$condition_submitted = "- None -"; $background = " style=\"background: rgba(255,0,0,0.5);\" ";
			}
			
			if ($array_conditions['condition_submitted'] != "0000-00-00" && $array_conditions['condition_approved'] != "0000-00-00") { $background = " style=\"background: rgba(0,255,0,0.5);\" "; }
			
			if ($array_conditions['company_name'] != NULL) { $company_name = $array_conditions['company_name'];	} else { $company_name = $pref_practice; } 		
			echo "<tr id=\"$showdetail\"><td $background><a href=\"" . $array_conditions['condition_link'] . "\">" . $array_conditions['condition_ref'] . "</a></td><td $background><a href=\"index2.php?page=project_planningcondition_edit&amp;proj_id=$proj_id&amp;condition_id=" . $array_conditions['condition_id'] . "\"><img src=\"images/button_edit.png\" alt=\"Edit Planning Condition\" /></a></td><td $background><a href=\"index2.php?page=project_planningcondition_list&amp;proj_id=$proj_id&amp;showdetail=" . $array_conditions['condition_id'] . "#" . $array_conditions['condition_id'] . "\">" . $array_conditions['condition_number'] . "</a></td><td $background>" . $company_name . "</td><td $background>" . $array_conditions['condition_type'] . "</td><td $background>" . $condition_submitted . "</td><td $background>" . $condition_approved . "</td></tr>";
			
			if ($showdetail > 0 && $array_conditions['condition_text'] != NULL) { echo "<tr><td>Details:</td><td colspan=\"6\">" . nl2br ( $array_conditions['condition_text'] ) . "</td></tr>"; }
			if ($showdetail > 0 && $array_conditions['condition_note'] != NULL) { echo "<tr><td>Notes:</td><td colspan=\"6\"><span class=\"minitext\">" . nl2br ( $array_conditions['condition_note'] ) . "</span></td></tr>"; }
		}
		echo "</table>";
	} else {
		
		echo "<table><tr><td>No planning conditions found for this project.</td></tr></table>";
		
	}
	
?>