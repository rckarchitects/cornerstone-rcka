<?php

include "inc_files/inc_checkcookie.php";

include("inc_files/inc_header.php");

echo "<body>";

// Get the list of projects from the database

$sql = "SELECT * FROM intranet_tender, intranet_tender_answers LEFT JOIN intranet_user_details ON answer_id_edited = user_id WHERE tender_id = answer_tender_id AND tender_id = '$_GET[tender_id]' $answer_id ORDER BY answer_ref";
$result = mysql_query($sql, $conn) or die(mysql_error());

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
		$tender_id = $array['tender_id'];
		$tender_name = $array['tender_name'];
		$tender_date = $array['tender_date'];
		$tender_type = $array['tender_type'];
		$tender_source = $array['tender_source'];
		$tender_instructions = $array['tender_instructions'];
		$user_name_first = $array['user_name_first'];
		$user_name_second = $array['user_name_second'];
		
		 $word_count_total = $word_count_total + $answer_wordcount;
		
		if ($counter == 0) {
			echo "<h1>$tender_name ($tender_type)</h1>";
			echo "<h2>Submission Deadline: ".TimeFormatDetailed($tender_date)."</h2>";
			}
		
		if ($answer_response == NULL) { $bgcolor="color: red"; $message = "[Not answered]";
		} elseif (($answer_wordcount / $answer_words) < 0.75 AND $answer_words > 0) { $bgcolor="color: orange"; $message = "[Too short]";
		} elseif (($answer_wordcount / $answer_words) > 1 AND $answer_words > 0) { $bgcolor="color: orange"; $message = "[Too long]";
		} else {
		$bgcolor = "color: green";
		}
		if ($answer_complete != 1) { $bgcolor="color: red"; $message = "[Incomplete]"; }
		
		echo "<h2 style=\"$bgcolor\">" . $answer_ref . " " . $message ."</h2>";
		echo "<h3>Question</h3>";
		echo $answer_question;
		echo "<h3>Response</h3>";
		echo $answer_response;
		
		// include a section for feedback if the tender is in the past
		
		$nowtime = time();
		if ($tender_date < $nowtime AND $answer_complete == 1 AND $answer_feedback != NULL) {
		echo "<h3>Assessor's Comments</h3>$answer_feedback";
		}
		
		

		
		if ($answer_words == NULL OR $answer_words == 0) { $answer_words = "not specified"; }
		if ($answer_weighting == NULL OR $answer_weighting == 0) { $answer_weighting = "not specified"; }
		
		if ($answer_time_edited > 0) { $time_edited = ", last updated: " . TimeFormatDetailed($answer_time_edited); } else { unset($time_edited); }
		if ($answer_id_edited > 0) { $person_edited = ", by " . $user_name_first . " " . $user_name_second; } else { unset($person_edited); }
		
		echo "<h3>Word count: $answer_wordcount, allowed: $answer_words, weighting: " . $answer_weighting . $time_edited . $person_edited . "</h3>";
		
		echo "<hr />";
		
		$counter++;
		}
		
		if ($word_count_total > 0) { echo "<h2>Total Word Count: " . number_format($word_count_total) . "</h2>"; }

		
		if (mysql_num_rows($result) == 0) {
		$sql = "SELECT * FROM intranet_tender WHERE tender_id = '$_GET[tender_id]' LIMIT 1";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		$tender_id = $array['tender_id'];
		$tender_name = $array['tender_name'];
		$tender_date = $array['tender_date'];
		echo "<h1>$tender_name</h1>";
		echo "<h2>".TimeFormatDetailed($tender_date)."</h2>";
		echo "<p>There are currently no responses on the system.</p>";	
		}
		
		echo "</body>";
		
?>
