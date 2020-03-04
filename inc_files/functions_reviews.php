<?php

function ReviewList($proj_id,$review_id) {
	
	global $conn;
	
	$sql = "SELECT * FROM intranet_project_reviews LEFT JOIN intranet_timesheet_fees ON ts_fee_id = review_stage WHERE review_proj = " . intval($proj_id) . " ORDER BY ts_fee_stage ASC, review_date ASC";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	if (mysql_num_rows($result) > 0) {
			echo "<div class=\"page\"><table>";
			
			echo "<tr><th>Stage</th><th>Type</th><th>Added By</th><th>Date</th><th>Complete?</th></tr>";
			
			while ($array = mysql_fetch_array($result)) {
				
				echo "<tr><td>" . $array['ts_fee_text'] . "&nbsp;<a href=\"index2.php?page=project_reviews&amp;proj_id=" . intval($array['review_proj']) . "&amp;review_id=" . intval($array['review_id']) . "\"><img src=\"images/button_edit.png\" class=\"button\" alt=\"Edit\" / ></a></td><td><a href=\"index2.php?page=project_reviews_detailed&amp;proj_id=" . intval($array['review_proj']) . "&amp;review_id=" . intval($array['review_id']) . "\">" . $array['review_type'] . "</a></td><td>" . UserDetails($array['review_user']) . "</td><td>" . TimeFormat(CreateDays($array['review_date'],12)) . "</td><td>";

					if ($array['review_complete'] == 1) { echo "Yes"; } else { echo "No"; }

				echo "</td></tr>";

			}
			
			echo "</table></div>";

	}
			
}

function ReviewDetails($review_id) {
	
	
	global $conn;
	global $user_usertype_current;
	
	$sql = "SELECT * FROM intranet_project_reviews, intranet_projects LEFT JOIN intranet_timesheet_fees ON ts_fee_project = proj_id WHERE review_proj = proj_id AND review_id = " . intval($review_id);
	
	$result = mysql_query($sql, $conn) or die(mysql_error());	
	
	$array = mysql_fetch_array($result);
	
	echo "<h2>" . $array['review_type'] . " (" . TimeFormat(CreateDays($array['review_date'],12)) . ")</h2>";
	
	ProjectSubMenu($array['review_proj'],$user_usertype_current,"project_view",1);
	ProjectSubMenu($array['review_proj'],$user_usertype_current,"project_reviews",2);
	
	echo "<div class=\"page\">";
	
	echo "<h3>Stage</h3>";
	
	echo "<p>" . $array['ts_fee_text'] . "</p>";
	
	echo "<h3>Added By</h3>";
	
	echo "<p>" . UserDetails($array['review_user']) . ", " . TimeFormatDay($array['review_timestamp']) . "</p>";

	echo "<h3>Review Date</h3>";
	
	echo "<p>" . TimeFormat(CreateDays($array['review_date'],12)) . "</p>";
	
	echo "<h3>Review Held?</h3>";
	
	if ($array['review_complete'] == 1) { echo "<p>Yes</p>"; } else { echo "<p>No</p>"; }
	
	echo "<h3>Notes</h3>";
	
	echo $array['review_notes'];
	
	
	
	echo "</div>";

	
	
}


function ReviewAdd($proj_id,$current_stage,$review_id) {
	
	if (intval($review_id) > 0) {
		
		global $conn;
		$sql = "SELECT * FROM intranet_project_reviews WHERE review_id = " . intval($review_id);
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		
	}
	
	$review_type_array = array("Stage Commencement Review","Mid-Stage Review","Stage End Review");
	
	global $conn;
	
	echo "<form action=\"index2.php?page=project_reviews&amp;proj_id=" . intval($proj_id) . "\" method=\"post\">";
	
	echo "<div><div>";
	
	echo "<div class=\"float\"><h3>Project Stage</h3>";
	
	ReviewSelectProjectStages($proj_id,$array['review_stage']);
	
	echo "</div><div class=\"float\"><h3>Date of Review</h3>";
	
	echo "<p><input type=\"date\" value=\"" . $array['review_date'] . "\" name=\"review_date\" /></p>";
	
	if ($array['review_complete'] == 1) { $checked = "checked=\"checked\""; } else { unset($checked); }
	
	echo "<p><input type=\"checkbox\" value=\"1\" name=\"review_complete\" " . $checked . " />&nbsp;Review Complete?</p></div>";
	
	ReviewType("review_type",$review_type_array,$array['review_type']);
	
	echo "</div><div class=\"float\"><h3>Review Description</h3>";
	
	TextAreaEdit();
	
	echo "<p><textarea name=\"review_notes\">" . $array['review_notes'] . "</textarea></p>";
	
	echo "<input type=\"hidden\" name=\"review_proj\" value=\"" . intval($proj_id) . "\" />";
	echo "<input type=\"hidden\" name=\"action\" value=\"project_reviews\" />";
	echo "<input type=\"hidden\" name=\"review_id\" value=\"" . $review_id . "\" />";
	
	if ($review_id > 0) {
		echo "<input type=\"submit\" value=\"Update\" />";
	} else {
		echo "<input type=\"submit\" value=\"Add\" />";
	}
	
	echo "</div>";
	
	echo "</form>";
	
	
}

function ReviewType($name,$array,$current) {
	
	echo "<div class=\"float\"><h3>Review Type</h3><p><select name=\"" . $name . "\">";
	
	foreach	($array AS $type) {
		
		if ($type == $current) { $selected = "selected=\"selected\""; } else { unset($selected); } 
		
		echo "<option value=\"" . $type . "\" " . $selected . ">";
		
		echo $type;
		
		echo "</option>";		
		
	}
	
	echo "</select></p></div>";
	
}

function ReviewSelectProjectStages($proj_id,$current_stage) {
	
	global $conn;
	
	if (intval($proj_id) > 0) {
	
		$sql = "SELECT * FROM intranet_timesheet_fees LEFT JOIN intranet_timesheet_group ON group_id = ts_fee_stage WHERE ts_fee_project = " . intval($proj_id) . " ORDER BY ts_fee_commence";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		if (mysql_num_rows($result) > 0) {
			
			echo "<select name=\"review_stage\">";
		
			while ($array = mysql_fetch_array($result)) {
				
				if ($current_stage == $array['ts_fee_id']) { $selected = "selected=\"selected\""; } else { unset($selected); } 
				
				echo "<option value=\"" . $array['ts_fee_id'] . "\" " . $selected . ">" . $array['ts_fee_text'] . "</option>";
				
			}
			
			echo "</select>";
		
		}
	
	}
	
}