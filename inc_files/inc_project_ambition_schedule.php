<?php

function ProjectListAmbitions($input) {
	
		if ($input == "proj_lpa") { $field = "proj_lpa"; echo "<h1>List of Local Planning Authorities</h1>"; }
		elseif ($input == "project_ambition") { $field = "proj_ambition_internal"; echo "<h1>List of Project Ambitions</h1>"; }
		elseif ($input == "client_ambition") { $field = "proj_ambition_client"; echo "<h1>List of Client Ambitions</h1>"; }
		elseif ($input == "project_information") { $field = "proj_info"; echo "<h1>List of Project Information</h1>"; }
		elseif ($input == "project_marketing") { $field = "proj_ambition_marketing"; echo "<h1>List of Marketing Ambitions</h1>"; }
		elseif ($input == "project_location") { $field = "proj_location"; echo "<h1>List of Project File Locations</h1>"; }
		elseif ($input == "project_type") { $field = "proj_type"; echo "<h1>List of Project Types</h1>"; }
		else { unset($input); }
		
		if ($input) {
			
			
	
				GLOBAL $conn;

				$sql = "SELECT proj_num, proj_name, proj_id, " . $field . " FROM intranet_projects WHERE proj_active = 1 AND $field IS NOT NULL ORDER BY proj_num, proj_name";
				$result = mysql_query($sql, $conn) or die(mysql_error());

				
				if (mysql_num_rows($result) > 0) {

					echo "<table>";

						while ($array = mysql_fetch_array($result)) {
							if ($array[$field]) {
								echo "<tr><td style=\"width: 35%;\"><a href=\"index2.php?page=project_view&amp;proj_id=" . $array['proj_id'] . "\">" . $array['proj_num'] . "&nbsp;" . $array['proj_name'] . "</a></td><td>" . nl2br($array[$field]) . "</td></tr>";
							}
						}

					echo "</table>";
				
				}
				
		}

}

$input = $_GET[type];

ProjectListAmbitions($input);