<?php

unset($alertmessage);

// Begin to clean up the $_POST submissions

		$answer_id = CleanNumber($_POST[answer_id]);
		$answer_words = CleanNumber($_POST[answer_words]);
		$answer_question = $_POST[answer_question];
		$answer_ref = CleanUp($_POST[answer_ref]);
		$answer_tender_id = CleanNumber($_POST[answer_tender_id]);
		$answer_weighting = CleanUp($_POST[answer_weighting]);
		

// Check that the required values have been entered, and alter the page to show if these values are invalid

if ($answer_ref == NULL OR $answer_question == NULL) { $alertmessage = "Empty fields are not allowed"; $page_redirect = "index2.php?page=tender_view&amp;tender_id=2&amp;edit_question=$answer_id";}

if ($alertmessage == NULL) {

// Construct the MySQL instruction to add these entries to the database

if ($answer_id > 0) {

		$sql_edit = "UPDATE intranet_tender_answers SET
		answer_ref = '$answer_ref',
		answer_words = '$answer_words',
		answer_question = '$answer_question',
		answer_weighting = '$answer_weighting'
		WHERE answer_id = '$answer_id' LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Response updated successfully.";
		$techmessage = $sql_edit;		
		
} else {

		$sql_add = "INSERT INTO intranet_tender_answers (
		answer_id,
		answer_ref,
		answer_words,
		answer_wordcount,
		answer_question,
		answer_weighting,
		answer_complete,
		answer_tender_id
		) values (
		'NULL',
		'$answer_ref',
		'$answer_words',
		'0',
		'$answer_question',
		'$answer_weighting',
		'',
		'$answer_tender_id'
		)";
		
		$result = mysql_query($sql_add, $conn) or die(mysql_error());
		$actionmessage = "Response added successfully.";
		$techmessage = $sql_add;
}






}

?>
