<?php


// Determine the date a week ago

$date_lastweek = time() - 604800;

$sql = "SELECT * FROM intranet_tasklist, intranet_projects WHERE tasklist_project = proj_id  AND tasklist_id = $_GET[tasklist_id] LIMIT 1";


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


print "<h1>";
print $array['proj_num'];
print "&nbsp;";
print $array['proj_name'];
print "</h1>";

// Menu bar

print "<p class=\"menu_bar\"><a href=\"index2.php?page=tasklist_edit\" class=\"menu_tab\">Add New Task</a>";
if ($user_usertype_current > 3 OR $_COOKIE[user] == $tasklist_user ) {
print "<a href=\"index2.php?page=tasklist_edit&amp;tasklist_id=$tasklist_id\" class=\"menu_tab\">Edit This Task</a>";
}
print "</p>";

// Only print if the task is not complete or was completed within the last week

					if ($tasklist_due > 0) { $tasklist_due_date = "due ".date("jS M Y", $tasklist_due); } else { $tasklist_due_date = ""; }
					$tasklist_person = $array['tasklist_person'];
					$proj_num = $array['proj_num'];
					$proj_name = $array['proj_name'];
					$proj_fee_track = $array['proj_fee_track'];
					
					

					print "<h2>Task Details</h2>";
							
					print "<p><strong>Project</strong><br /><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a></p>";
					
					
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
					
					print "<p><strong>Description</strong></p><p>$tasklist_notes</p>";
				
					print "<p>";
					print "<span class=\"minitext\"><a href=\"index2.php?page=user_view&amp;user_id=".$user_id."\">".$user_name_first."&nbsp;".$user_name_second."</a><a href=\"index2.php?page=datebook_view_day&amp;time=$tasklist_due\">, ".$tasklist_due_date."</a>".$tasklist_percentage_desc;
					
					// If completed, put the completed date down
					
					if ($tasklist_percentage == 100 AND $tasklist_completed != "") {
					print ", completed <a href=\"index2.php?page=datebook_view_day&time=$tasklist_completed\">".TimeFormat($tasklist_completed)."</a>";
					}
					
					print "</span></p><p><strong>Percentage Complete</strong></p><p>";
					
					// Insert the percentage bar	
						
					if ($tasklist_percentage == 0 ) { $tasklist_percentage_graph = "tasklist_percent_000.gif"; }
					elseif ($tasklist_percentage == 10 ) { $tasklist_percentage_graph = "tasklist_percent_010.gif"; }
					elseif ($tasklist_percentage == 20 ) { $tasklist_percentage_graph = "tasklist_percent_020.gif"; }
					elseif ($tasklist_percentage == 30 ) { $tasklist_percentage_graph = "tasklist_percent_030.gif"; }
					elseif ($tasklist_percentage == 40 ) { $tasklist_percentage_graph = "tasklist_percent_040.gif"; }
					elseif ($tasklist_percentage == 50 ) { $tasklist_percentage_graph = "tasklist_percent_050.gif"; }
					elseif ($tasklist_percentage == 60 ) { $tasklist_percentage_graph = "tasklist_percent_060.gif"; }
					elseif ($tasklist_percentage == 70 ) { $tasklist_percentage_graph = "tasklist_percent_070.gif"; }
					elseif ($tasklist_percentage == 80 ) { $tasklist_percentage_graph = "tasklist_percent_080.gif"; }
					elseif ($tasklist_percentage == 90 ) { $tasklist_percentage_graph = "tasklist_percent_090.gif"; }
					elseif ($tasklist_percentage == 100 ) { $tasklist_percentage_graph = "tasklist_percent_100.gif"; }
					
					// Print the bar chart and make it clickable if it belongs to the current user
					
					if ($user_id == $_COOKIE[user] OR $user_usertype_current > 3) {
					
					  print "<form method=\"post\" action=\"index2.php?page=tasklist_detail&amp;tasklist_id=$tasklist_id\">";
					
							if ($_GET[time] > 0) { $page_return = "index2.php?page=datebook_view_day&amp;time=$_GET[time]"; } else { $page_return = "index2.php?page=tasklist_view" ; }
					
							function PercentClick($input) {
								Global $tasklist_percentage;
								echo "<input type=\"radio\" value=\"$input\" name=\"tasklist_percentage\"";
								if ($input == $tasklist_percentage) { echo " checked=\"checked\" "; }
								echo "/>".$input."%&nbsp;";
							}
							
							$count = 0;
							echo "<p>";
							while ($count <=100) {
								PercentClick($count);
								$count = $count + 10;
							}
							echo "</p>";
							
					} else {
					
							print "
							<img src=\"images/$tasklist_percentage_graph\" width=\"225\" height=\"17\" alt=\"Percentage Complete\" />";
					
					}
					
					print "</p>";
					print "<p><strong>Added</strong><br /><a href=\"index2.php?page=datebook_view_day&amp;time=$tasklist_added\">".TimeFormat($tasklist_added)."</a></p>";
					

}


if ($user_id == $_COOKIE[user] OR $user_usertype_current > 3) {
  
  print "<p>";
  print "<textarea name=\"tasklist_comment\" rows=\"8\" cols=\"36\" class=\"inputbox\">$tasklist_comment</textarea>";
  print "</p>";
  
  print "<p>";
  print "<input class=\"inputsubmit\" type=\"submit\" value=\"Submit\" />";
  print "</p>";
  
  print "<input type=\"hidden\" name=\"tasklist_id\" value=\"$_GET[tasklist_id]\" />";
  print "<input type=\"hidden\" name=\"action\" value=\"tasklist_change_comment\" />";
  
  print "</form>";
   
} elseif ($tasklist_comment != NULL)  {
  
  print "<h2>Comments</h2>";
  
  print "<p>";
  print $tasklist_comment;
  print "</p>";
  
}


?>
