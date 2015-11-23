<?php

// Construct the MySQL instruction to add these entries to the database

$message_id = CleanNumber($_GET[message_id]);

if ($message_id > 0) {

		$sql_edit = "UPDATE intranet_phonemessage SET
		message_viewed = ".time()."
		WHERE message_id = '$message_id'
		LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Telephone message updated successfully.";
		$techmessage = $sql_edit;
		
}

?>
