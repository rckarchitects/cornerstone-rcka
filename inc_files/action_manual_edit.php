<?php

if (intval($_POST[manual_id]) == 0 && $_POST[manual_text] != NULL) {

		// This determines the page to show once the form submission has been successful

		//$page = "blog_view";

		// Begin to clean up the $_POST submissions

		$manual_title = addslashes($_POST[manual_title]);
		$manual_author = intval($_POST[manual_author]);
		$manual_updated = intval(time());
		$manual_text = addslashes($_POST[manual_text]);
		$manual_stage = intval($_POST[manual_stage]);
		$manual_section = addslashes($_POST[manual_section]);
		$manual_attachment = intval($_POST[manual_attachment]);

		// Construct the MySQL instruction to add these entries to the database

		$sql_add = "INSERT INTO intranet_stage_manual (
		manual_id,
		manual_title,
		manual_author,
		manual_updated,
		manual_text,
		manual_stage,
		manual_section,
		manual_attachment
		) values (
		'NULL',
		'$manual_title',
		'$manual_author',
		'$manual_updated',
		'$manual_text',
		'$manual_stage',
		'$manual_section',
		'$manual_attachment'
		)";

		$result = mysql_query($sql_add, $conn) or die(mysql_error());

		$id_added = mysql_insert_id();

		$actionmessage = "<p>Manual Entry <a href=\"index2.php?page=manual_page&amp;manual_id=$id_added\">\" " . $manual_title . "\"</a> was added successfully.</p>";

		AlertBoxInsert($_COOKIE[user],"Manual Entry Added",$actionmessage,$id_added,0);

} elseif (intval($_POST[manual_id]) > 0 && $_POST[manual_text] != NULL) {
	
		// Begin to clean up the $_POST submissions
		
		$manual_id = intval($_POST[manual_id]);
		$manual_title = addslashes($_POST[manual_title]);
		$manual_author = intval($_POST[manual_author]);
		$manual_updated = intval(time());
		$manual_text = addslashes($_POST[manual_text]);
		$manual_stage = intval($_POST[manual_stage]);
		$manual_section = addslashes($_POST[manual_section]);
		$manual_attachment = intval($_POST[manual_attachment]);

		// Construct the MySQL instruction to add these entries to the database

		$sql = "UPDATE intranet_stage_manual SET
		manual_title = '$manual_title',
		manual_author = $manual_author,
		manual_updated = $manual_updated,
		manual_text = '$manual_text',
		manual_stage = $manual_stage,
		manual_section = '$manual_section',
		manual_attachment = '$manual_attachment'
		WHERE manual_id = $manual_id
		LIMIT 1		
		";

		$result = mysql_query($sql, $conn) or die(mysql_error());

		$actionmessage = "<p>Manual Entry <a href=\"index2.php?page=manual_page&amp;manual_id=$manual_id\">\" " . $manual_title . "\"</a> was updated successfully.</p>";

		AlertBoxInsert($_COOKIE[user],"Manual Entry Updated",$actionmessage,$id_added,0);
	
}