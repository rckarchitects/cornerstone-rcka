<?php

function TaskListViewIndividual($tasklist_id) {
	
	global $conn;
	$tasklist_id = intval($tasklist_id);

		// Determine the date a week ago

		$date_lastweek = time() - 604800;

		$sql = "SELECT * FROM intranet_tasklist, intranet_projects WHERE tasklist_project = proj_id  AND tasklist_id = $tasklist_id LIMIT 1";


		$result = mysql_query($sql, $conn) or die(mysql_error());

		if (mysql_num_rows($result) > 0) {

			$array = mysql_fetch_array($result);  

			$tasklist_id = $array['tasklist_id'];
			$proj_id = $array['tasklist_project'];
			$tasklist_notes = $array['tasklist_notes'];
			$tasklist_percentage = $array['tasklist_percentage'];
			$tasklist_completed = $array['tasklist_completed'];
			$tasklist_comment = $array['tasklist_comment'];
			$tasklist_user = $array['tasklist_user'];
			$tasklist_added = $array['tasklist_added'];
			$tasklist_due = $array['tasklist_due'];
			$tasklist_access = $array['tasklist_access'];
			$tasklist_project = $array['tasklist_project'];

		echo "<h1>Tasks</h1>";
		ProjectTitle(1,$proj_id);

		// Menu bar

		echo "<div class=\"menu_bar\"><a href=\"index2.php?page=tasklist_edit&amp;proj_id=$tasklist_project\" class=\"menu_tab\">Add New Task</a>";
		//if ($user_usertype_current > 3 ) { // OR $_COOKIE[user] == $tasklist_user ) {
		echo "<a href=\"index2.php?page=tasklist_edit&amp;tasklist_id=$tasklist_id&amp;proj_id=$tasklist_project\" class=\"menu_tab\">Edit This Task</a>";
		//}
		echo "</div>";
		
		ProjectSubMenu($proj_id,$user_usertype_current,"tasklist_view",2);
		
		echo "<div class=\"page\">";

		// Only echo if the task is not complete or was completed within the last week

							if ($tasklist_due > 0) { $tasklist_due_date = "due ".date("jS M Y", $tasklist_due); } else { $tasklist_due_date = ""; }
							$tasklist_person = $array['tasklist_person'];
							$proj_num = $array['proj_num'];
							$proj_name = $array['proj_name'];
							$proj_fee_track = $array['proj_fee_track'];
							
							

							
									
							echo "<h3>Project</h3><p><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a></p>";
							
							
							$proj_id_repeat = $proj_id;
							
							$checktime = time() - 43200;
							
							if ($checktime > $tasklist_due AND $tasklist_due > 0 AND $tasklist_due != NULL) {
							$format = "class=\"alert\"";
							}
							
							
							$sql2 = "SELECT user_name_first, user_name_second, user_id FROM intranet_user_details WHERE user_id = '$tasklist_person' ";
							$result2 = mysql_query($sql2, $conn) or die(mysql_error());
							
							$array2 = mysql_fetch_array($result2);
							$user_name_first = $array2['user_name_first'];
							$user_name_second = $array2['user_name_second'];
							$user_id = $array2['user_id'];
							
							echo "<h3>Description</h3><p>$tasklist_notes</p>";
						
							echo "<p>";
							echo "<span class=\"minitext\"><a href=\"index2.php?page=user_view&amp;user_id=".$user_id."\">".$user_name_first."&nbsp;".$user_name_second."</a><a href=\"index2.php?page=datebook_view_day&amp;timestamp=" . $tasklist_due . "\">, ".$tasklist_due_date."</a>".$tasklist_percentage_desc;
							
							// If completed, put the completed date down
							
							if ($tasklist_percentage == 100 AND $tasklist_completed > 0) {
							echo ", completed <a href=\"index2.php?page=datebook_view_day&amp;timestamp=" . $tasklist_completed . "\">".TimeFormat($tasklist_completed)."</a>";
							}
							
							echo "</span></p>";
							
							if ($tasklist_percentage == 100 && intval($tasklist_completed) > 0) {
								echo "<h3>Completed " . TimeFormat($tasklist_completed) . "</h3>";
							} elseif ($tasklist_percentage == 100 && intval($tasklist_completed) == 0) {
								echo "<h3>Completed</h3>";
							} else {
								echo "<h3>Incomplete</h3>";
							}
							
							echo "<h3>Added</h3><p><a href=\"index2.php?page=datebook_view_day&amp;timestamp=" . $tasklist_added . "\">".TimeFormat($tasklist_added)."</a></p>";
							

		}

		  
		  echo "<div>";
		  
		  if ($tasklist_comment) { echo "<h3>Comments</h3>" . $tasklist_comment . "</div>"; }
		  
		
	echo "</div>";


}

TaskListViewIndividual( intval ( $_GET[tasklist_id] ) );
