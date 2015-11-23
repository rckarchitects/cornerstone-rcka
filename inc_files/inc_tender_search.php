<?php

// Get the list of projects from the database

$search = CleanUp($_GET[tender_keyword]);

$sql = "SELECT * FROM intranet_tender_answers, intranet_tender WHERE tender_id = answer_tender_id AND answer_question LIKE '%$search%' ORDER BY tender_date DESC, answer_ref";
$result = mysql_query($sql, $conn) or die(mysql_error());

	$counter = 0;
		
		while ($array = mysql_fetch_array($result)) {
		
		$answer_id = $array['answer_id'];
		$answer_ref = $array['answer_ref'];
		$answer_question = $array['answer_question'];
		$answer_response = nl2br($array['answer_response']);
		$answer_words = $array['answer_words'];
		$answer_weighting = $array['answer_weighting'];
		$answer_rating = $array['answer_rating'];
		$tender_id = $array['tender_id'];
		$tender_name = $array['tender_name'];
		$tender_date = $array['tender_date'];
		$tender_type = $array['tender_type'];
		
		if ($tender_date > time() AND $_GET[edit_question] != $answer_id) { $answer_ref = $answer_ref . "&nbsp;<a href=\"index2.php?page=tender_view&amp;tender_id=$tender_id&amp;edit_question=$answer_id#$answer_id\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a>"; }
		
		if ($counter == 0) {
			echo "<h1>Searching: <i>$search</i></h1>";
		print "<table summary=\"Lists of questions and responses\">";
		if ($_GET[question] == "add") { EditForm('','','','','',$tender_id); echo "</th></tr>"; }
		}
		
		if ($answer_response == NULL) { $answer_response = "-- Not yet answered --"; }

			echo "<tr><th style=\"width: 10%;\" >$answer_ref</th><th>";
			$answer_question = nl2br($answer_question);
			TenderWords($answer_question);

		echo "</th></tr>";
		echo "<tr><td colspan=\"2\">$answer_response</td></tr>";
		echo "<tr><td colspan=\"2\" class=\"minitext\"><a href=\"index2.php?page=tender_view&amp;tender_id=$tender_id\">$tender_name</a> "; if ($tender_type != NULL) { echo " (".$tender_type.")"; } echo ", ". TimeFormat($tender_date); if ($answer_rating > 0) { echo ", Rating: ".$answer_rating; }
		echo "</td></tr>";
		
		$counter++;
		}

		print "</table>";
		
?>
