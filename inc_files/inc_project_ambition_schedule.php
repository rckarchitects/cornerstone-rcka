<?php

function ProjectListData($input,$proj_active) {
	
	echo "<h1>Project Data</h1>";
	
		if ($input == "proj_lpa") { $field = "proj_lpa"; echo "<h2>List of Local Planning Authorities</h2>"; TopMenu ("project_ambition_schedule",2,''); }
		elseif ($input == "project_ambition") { $field = "proj_ambition_internal"; echo "<h2>List of Project Ambitions</h2>"; }
		elseif ($input == "client_ambition") { $field = "proj_ambition_client"; echo "<h2>List of Client Ambitions</h2>"; }
		elseif ($input == "project_information") { $field = "proj_info"; echo "<h2>List of Project Information</h2>"; }
		elseif ($input == "project_marketing") { $field = "proj_ambition_marketing"; echo "<h2>List of Marketing Ambitions</h2>"; TopMenu ("project_ambition_schedule",2,'');}
		elseif ($input == "project_location") { $field = "proj_location"; echo "<h2>List of Project File Locations</h2>"; }
		elseif ($input == "project_type") { $field = "proj_type"; echo "<h2>List of Project Types</h2>"; }
		else { $field = "proj_ambition_internal"; unset($input); TopMenu ("project_ambition_schedule",2,''); }
		
		if ($input) {
			
			$proj_active = intval($proj_active);
	
				GLOBAL $conn;
				
				if ($_GET[filter] == "all") { unset($filter); }
				else { $filter = " proj_active = 1 AND "; } 

				$sql = "SELECT proj_num, proj_name, proj_id, " . $field . " FROM intranet_projects WHERE $filter $field IS NOT NULL ORDER BY proj_num DESC";
				$result = mysql_query($sql, $conn) or die(mysql_error());
				
				echo "<p>$sql</p>";

				
				if (mysql_num_rows($result) > 0) {

					echo "<table>";

						while ($array = mysql_fetch_array($result)) {
							if ($array[$field]) {
								echo "<tr><td style=\"width: 35%;\"><a href=\"index2.php?page=project_view&amp;proj_id=" . $array['proj_id'] . "\">" . $array['proj_num'] . "&nbsp;" . $array['proj_name'] . "</a></td><td>" . nl2br($array[$field]) . "</td></tr>";
							}
						}

					echo "</table>";
				
				} else {
					
					echo "<p>None found</p>";
					
				}
				
		}

}

$input = $_GET[type];

ProjectListData($input);