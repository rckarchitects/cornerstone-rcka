<?php


// Lock the answer to prevent other simultaneous edits

if ($_GET[lock_answer] > 0) {

		$answer_lock = $_GET[lock_answer];
		$sql_lock = "UPDATE intranet_tender_answers SET answer_lock = $_COOKIE[user] WHERE answer_id = '$answer_lock' LIMIT 1";		
		$result = mysql_query($sql_lock, $conn) or die(mysql_error());

} elseif ($_GET[unlock_answer] > 0) {

		$answer_lock = $_GET[unlock_answer];
		$sql_lock = "UPDATE intranet_tender_answers SET answer_lock = '' WHERE answer_id = '$answer_lock' LIMIT 1";		
		$result = mysql_query($sql_lock, $conn) or die(mysql_error());

}

if ($_GET[answer_id] > 0) { $answer_id = " AND answer_id = '$_GET[answer_id]' "; } elseif ($_GET[edit_answer] > 0) { $answer_id = " AND answer_id = '$_GET[edit_answer]' "; } else { $answer_id = NULL; }

// First get the tender information

function Tender_GetInformation($tender_id) {
	
	global $conn;
	$tender_id = intval($tender_id);
	
	echo "<h1>Tenders</h1>";
		
$sql = "SELECT * FROM intranet_tender WHERE tender_id = $tender_id LIMIT 1";
$result = mysql_query($sql, $conn) or die(mysql_error());

$array = mysql_fetch_array($result);


		$tender_id = $array['tender_id'];
		$tender_client = $array['tender_client'];
		$tender_name = $array['tender_name'];
		$tender_date = $array['tender_date'];
		$tender_type = $array['tender_type'];
		$tender_source = $array['tender_source'];
		$tender_description = $array['tender_description'];
		$tender_instructions = $array['tender_instructions'];
		$tender_notes = $array['tender_notes'];
		$tender_responsible = $array['tender_responsible'];
		$tender_added_by = $array['tender_added_by'];
		$tender_added_time = $array['tender_added_time'];
		
		if ($tender_type) { $tender_name = $tender_name . "&nbsp;(" . $tender_type . ")"; }

			echo "<h2>" . $tender_name . "</h2>";
		
			
			echo "<div class=\"submenu_bar\">";
			//if ($user_usertype_current > 2) {
				echo "<a href=\"index2.php?page=tender_view&amp;question=add&amp;tender_id=$tender_id\" class=\"submenu_bar\">Add Question</a>";
			//}
			
			if ($_GET[edit_question] == NULL) { echo "<a href=\"popup_tender.php?tender_id=$tender_id\" class=\"submenu_bar\">Printable View</a>"; }
			
			echo "<a href=\"index2.php?page=tender_edit&amp;tender_id=$tender_id\" class=\"submenu_bar\">Edit Tender</a>";
			
			echo "</div>";
			
			echo "<div class=\"page\">";
		
			if ($tender_description != NULL AND $_GET[edit_question] == NULL AND $_GET[edit_answer] == NULL) { echo "<h3>Description</h3><p>". nl2br($tender_description) . "</p>"; }
			
			if ($tender_instructions != NULL AND $_GET[edit_question] == NULL AND $_GET[edit_answer] == NULL) { echo "<h3>Submission Instructions</h3><p>". nl2br($tender_instructions) . "</p>"; }
			
			if ($tender_client != NULL AND $_GET[edit_question] == NULL AND $_GET[edit_answer] == NULL) { echo "<h3>Client</h3><p>". $tender_client . "</p>"; }
			
			if ($tender_source != NULL AND $_GET[edit_question] == NULL AND $_GET[edit_answer] == NULL) { echo "<h3>Source of Tender</h3><p>". TextPresent($tender_source) . "</p>"; }
			
			if ($tender_responsible) { echo "<h3>Responsible</h3><p>" . UserDetails($tender_responsible) . "</p>"; }
			
			if ($tender_added_by && $tender_added_time) { echo "<h3>Added</h3><p>By " . UserDetails($tender_added_by) . ", on " . TimeFormatDetailed($tender_added_time) . "</p>"; }
			
			if ($tender_notes && !$_GET[edit_question] && !$_GET[edit_answer]) { echo "<h3>Notes</h3><p>". nl2br($tender_notes) . "</p>"; }

			if ($tender_date) { $countdown = $tender_date - time(); }
			if ($countdown > 0) { $countdown_print = "&nbsp;(" . DeadlineTime($countdown) . ")"; } else { unset($countdown_print); }
			
			echo "<h3>Submission Deadline</h3><p>".TimeFormatDetailed($tender_date) . $countdown_print ."</p>";

			echo "</div>";

}


Tender_GetInformation($_GET[tender_id]);

$sql = "SELECT * FROM intranet_tender, intranet_tender_answers LEFT JOIN intranet_user_details ON user_id = answer_id_edited WHERE tender_id = answer_tender_id AND tender_id = '$_GET[tender_id]' $answer_id ORDER BY answer_ref";
$result = mysql_query($sql, $conn) or die(mysql_error());


$tender_id = intval ( $_GET[tender_id] );

		$counter = 0;
		
		$word_count_total = 0;
		
		while ($array = mysql_fetch_array($result)) {
		
		$answer_id = $array['answer_id'];
		$answer_ref = $array['answer_ref'];
		$answer_question = $array['answer_question'];
		$answer_response = $array['answer_response'];
		$answer_words = $array['answer_words'];
		$answer_wordcount = $array['answer_wordcount'];
		$answer_weighting = $array['answer_weighting'];
		$answer_rating = $array['answer_rating'];
		$answer_complete = $array['answer_complete'];
		$answer_feedback = $array['answer_feedback'];
		$answer_id_edited = $array['answer_id_edited'];
		$answer_time_edited = $array['answer_time_edited'];
		$answer_lock = $array['answer_lock'];
		$user_name_first = $array['user_name_first'];
		$user_name_second = $array['user_name_second'];
		
		$word_count_total = $word_count_total + $answer_wordcount;
		
		
		if ($tender_date > time() OR $user_usertype_current > 3) { $tender_editable = "yes"; } else { $tender_editable = "no"; }
		
		if ($answer_words != NULL) { $word_count = WordCount($answer_words); } else { $word_count = "0"; }
				
		if ($tender_editable == "yes" AND $answer_id != $_GET[edit_question] AND $_GET[edit_answer] == NULL AND $_GET[edit_question] == NULL) { $answer_ref = "<strong>" . $answer_ref . "</strong>&nbsp;<a href=\"index2.php?page=tender_view&amp;tender_id=$_GET[tender_id]&amp;edit_question=$answer_id#$answer_id\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a>"; }
		
		if ($counter == 0) {
			
			echo "<h3>Responses</h3>";
			
			echo "<div class=\"page\"><table summary=\"Lists of questions and responses\">";
			if ($_GET[question] == "add") { EditForm('','','','','',$tender_id); echo "</th></tr>"; }
		}
		
		if ($answer_response == NULL) { $answer_response = "-- Not answered --"; $bgcolor="background-color: red"; $message = "Incomplete";
		} elseif (($answer_wordcount / $answer_words) < 0.75 AND $answer_words > 0) { $bgcolor="background-color: orange"; $message = "Too short?";
		} elseif (($answer_wordcount / $answer_words) > 1 AND $answer_words > 0) { $bgcolor="background-color: orange"; $message = "Too long?";
		} else {
		$bgcolor = "background-color: green"; $message = "";
		}
		
		if ($answer_complete != 1) { $bgcolor="background-color: red"; $message = "Incomplete"; }
		
		if ($_GET[edit_question] == $answer_id AND $_GET[edit_question] != NULL) {

		EditForm($answer_id,$answer_question,$answer_ref,$answer_words,$answer_weighting,$tender_id);
		
		} else {
			echo "<tr><td style=\"width: 10%;\" rowspan=\"3\" ><div style=\"" . $bgcolor . "; border-radius: 20px; padding: 10px; margin-right: 15px;\">$answer_ref";
			if ($message != NULL) { echo "<p>(".$message.")</p>"; }
			echo "</div></td><td>";
			TenderWords($answer_question);
		}
		echo "</td></tr>";
		
		

		
		if ($tender_editable == "yes" AND $_GET[edit_question] == NULL AND $_GET[edit_answer] == NULL AND (($answer_lock == 0 OR $answer_lock == NULL))) { $answer_response = $answer_response . "&nbsp;<a href=\"index2.php?page=tender_view&amp;tender_id=$_GET[tender_id]&amp;edit_answer=$answer_id&amp;lock_answer=$answer_id#$answer_id\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a>"; } elseif ($answer_lock > 0) { $answer_response = "- This response is currently being edited by user $answer_lock. <a href=\"index2.php?page=tender_view&amp;tender_id=$tender_id&amp;action=tender_force_unlock&amp;answer_id=$answer_id\">Click here to force unlock.</a> -"; }
		
		if ($_GET[edit_answer] == $answer_id AND $_GET[edit_answer] != NULL) {
		

		echo "<tr><td colspan=\"2\">";
		TextAreaEdit();
		echo "<p><form action=\"index2.php?page=tender_view&amp;tender_id=$tender_id#$answer_id\" method=\"post\"><textarea style=\"width: 100%; height: 800px;\" name=\"answer_response\">".$array['answer_response']."</textarea>";
		echo "<input type=\"checkbox\" value=\"1\" name=\"answer_complete\"";
		if ($answer_complete == 1) { echo " checked=\"checked\" "; }
		echo "/>&nbsp;Complete?<br /><input type=\"checkbox\" value=\"yes\" name=\"clear_format\" onClick=\"return confirm('Are you sure you want to clear formatting?')\" />&nbsp;Clear formatting";
		echo "<br /><input type=\"hidden\" name=\"action\" value=\"tender_response_edit\" /><input type=\"hidden\" name=\"answer_id\" value=\"$answer_id\" /><input type=\"hidden\" name=\"answer_tender_id\" value=\"$tender_id\" />";
		
		// include a section for feedback if the tender is in the past
		
		$nowtime = time();
		if ($tender_date < $nowtime AND $answer_complete == 1) {
		echo "<h3>Enter any comments received from the assessor</h3><textarea name=\"answer_feedback\" style=\"width: 100%; height: 60px;\">$answer_feedback</textarea>";
		}
		
		echo "<input type=\"submit\" value=\"Save answer\" /></form><form action=\"index2.php?page=tender_view&amp;unlock_answer=$answer_id&amp;tender_id=$tender_id#$answer_id\" method=\"post\"><input type=\"submit\" value=\"Cancel\"  onClick=\"return confirm('Are you sure you want to cancel? All changes since your last save will be lost.')\" /></form></p>";
		echo "</td></tr>";
		} else {
		echo "<tr><td colspan=\"2\"><blockquote onClick=\"SelectAll('address')\">$answer_response</blockquote></td></tr>";
			if ($answer_feedback != NULL) {
			echo "<tr><td><h3>Assessor's Feedback</h3>" . $answer_feedback . "</td></tr>";
			}
		}

		
		echo "<tr><td colspan=\"2\">";
		if ($answer_rating > 0) { echo "Rating: $answer_rating, "; }
		if ($answer_words == NULL OR $answer_words == 0) { $answer_words = "Not specified"; }
		if ($answer_time_edited > 0) { $time_edited = ", last updated: " . TimeFormatDetailed($answer_time_edited); } else { unset($time_edited); }
		if ($answer_id_edited > 0) { $person_edited = ", by " . $user_name_first . " " . $user_name_second; } else { unset($person_edited); }
		echo "Word count: $answer_wordcount, allowed: $answer_words, weighting: $answer_weighting" . $time_edited . $person_edited . "</td></tr><a name=\"$answer_id\"></a>";
		
		$counter++;
		}
		
		if ($word_count_total > 0) { echo "<tr><td><strong>Total Word Count:</strong></td><td><strong>" . number_format($word_count_total) . "</strong></td></tr>"; }

		echo "</table>";
		
		if (mysql_num_rows($result) == 0) {
		$sql = "SELECT * FROM intranet_tender WHERE tender_id = '$_GET[tender_id]' LIMIT 1";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		$tender_id = $array['tender_id'];
		$tender_name = $array['tender_name'];
		$tender_date = $array['tender_date'];
		echo "<h2>Tender Questions</h2>";
		echo "<p>There are currently no responses on the system. Get started by adding the first question below.</p>";
		echo "<table summary=\"Lists of questions and responses\">";
		EditForm($answer_id,$answer_question,$answer_ref,$answer_words,$answer_weighting,$tender_id);
		echo "</table></div>";
		
		}
