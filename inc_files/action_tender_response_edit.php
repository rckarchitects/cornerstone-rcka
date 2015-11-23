<?php


		// Let's archive the current answer before we get started
		
		$sql_recover = "SELECT answer_id, answer_response, answer_time_edited, answer_id_edited FROM intranet_tender_answers WHERE answer_id = '$_GET[edit_answer]' LIMIT 1";
		$result_recover = mysql_query($sql_recover, $conn) or die(mysql_error());
		$array_recover = mysql_fetch_array($result_recover);
		
		$answer_id_recover = $array_recover['answer_id'];
		$answer_response_recover = addslashes ( $array_recover['answer_response'] );
		$answer_id_edited_recover = $array_recover['answer_id_edited'];
		$answer_time_edited_recover = $array_recover['answer_time_edited'];
						
						if ($answer_id  > 0 AND $answer_response == NULL) {
						
						$sql_archive = "INSERT INTO intranet_tender_answers_archive (
						archive_id,
						archive_text,
						archive_user,
						archive_time,
						archive_response
						) values (
						'NULL',
						'$answer_response_recover',
						'$answer_id_edited_recover',
						'$answer_time_edited_recover',
						'$answer_id_recover'
						)";
						
						$result_archive = mysql_query($sql_archive, $conn) or die(mysql_error());
						
						}
						
						
						
						

unset($alertmessage);

// Begin to clean up the $_POST submissions

		$answer_id = CleanNumber($_POST[answer_id]);
		$answer_response = addslashes ( strip_tags($_POST[answer_response],"<p><ul><li><ol><strong><b><i><italic><u>") );
		$answer_feedback = addslashes ( strip_tags($_POST[answer_feedback],"<p><ul><li><ol><strong><b><i><italic><u>") );
		$word_count = WordCount(strip_tags($answer_response));
		
		$answer_complete = $_POST[answer_complete];
		
		$answer_response = str_replace("<p></p>","",$answer_response);
		// $answer_response = preg_replace('/\s\s+/', ' ',$answer_response);
		$answer_response = addslashes ( preg_replace('/(<p.+?)+(<\/>)/i', '<p>', $answer_response) );
		
		$answer_feedback = str_replace("<p></p>","",$answer_feedback);
				
		if ($_POST[clear_format] == "yes") { $answer_response = strip_tags($answer_response,"<p>"); }
		
		if ($word_count == 0 OR $word_count == NULL) { unset($answer_time_edited); unset($answer_id_edited); } else { $answer_time_edited = time(); $answer_id_edited = $_COOKIE[user]; }
		

if ($alertmessage == NULL) {

// Construct the MySQL instruction to add these entries to the database

if ($answer_id > 0) {

if ($answer_feedback != NULL) { $feedback_inc = ", answer_feedback = '$answer_feedback'"; } else { $feedback_inc = ""; }

		$sql_edit = "UPDATE intranet_tender_answers SET
		answer_response = '$answer_response',
		answer_complete = '$answer_complete',
		answer_time_edited = '$answer_time_edited',
		answer_wordcount = '$word_count',
		answer_id_edited = '$answer_id_edited',
		answer_lock = ''
		$feedback_inc
		WHERE answer_id = '$answer_id' LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Response updated successfully.";
		$techmessage = $sql_edit;
		
}






}

?>
