<?php

echo "<h2>Drawing Schedule</h2>";

ProjectSubMenu($proj_id,$user_usertype_current,"project_drawings",1);

if ($_GET[listorder] != NULL) { $listorder = $_GET[listorder]; } else { $listorder = "proj_num"; }

if ($_GET[desc] == "desc") { $desc = "desc"; $showdesc = ""; } else { $desc = ""; $showdesc = "desc";  }

//Determine what we're showing, and set the SQL query accordingly

		if ($_GET[projects] != "all") {
		echo "<p class=\"submenu_bar\">";
		echo "<a href=\"index2.php?page=drawings_view&amp;listorder=$listorder&amp;projects=all\" class=\"submenu_bar\">All Projects</a></p>";
		echo "<h2>Current Projects</h2>";
		$sql_projects = "AND proj_active = 1"; 
		} else {
		echo "<p class=\"submenu_bar\">";
		echo "<a href=\"index2.php?page=drawings_view&amp;listorder=$listorder&amp;projects=active\" class=\"submenu_bar\">Current Projects</a></p>";
		echo "<h2>All Projects</h2>";
		$sql_projects = "";
		}

$sql = "SELECT * FROM intranet_projects INNER JOIN intranet_drawings ON proj_id = drawing_project AND proj_fee_track = 1 AND drawing_project = proj_id $sql_projects order by $listorder $desc";

$result = mysql_query($sql, $conn) or die(mysql_error());	


		if (mysql_num_rows($result) > 0) {

		echo "<table summary=\"Lists all of the current, active projects\">";
		echo "<tr><td>Ref.</td>";
		echo "<td colspan=\"4\">Project</td>";
		echo "</tr>";
		
		$current_proj = "";
		$count = 0;

		while ($array = mysql_fetch_array($result)) {
		$proj_num = $array['proj_num'];
		$proj_name = $array['proj_name'];
		$proj_id = $array['proj_id'];
		
		if ($current_proj != $proj_id) {

					echo "<tr><td><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num</a></td><td>$proj_name</td>";
					echo "<td><a href=\"index2.php?page=drawings_list&amp;proj_id=$proj_id\">Click to view</a></td></td>";
					echo "<td><a href=\"index2.php?page=drawings_issue&amp;proj_id=$proj_id\">Drawing Issue</a></td></td>";
					echo "<td><a href=\"pdf_drawing_list.php?proj_id=$proj_id\">Drawing Schedule&nbsp;<img src=\"images/button_pdf.png\" alt=\"Drawing Schedule\" /></a></td></td>";
					echo "</tr>";
					
		}

		
		$current_proj = $proj_id;
		$count = $count++;
		}

		echo "</table>";

		} else {

		echo "There are no live projects on the system";

		}