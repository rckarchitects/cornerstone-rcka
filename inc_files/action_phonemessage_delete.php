<?php


$message_id = CleanNumber($_GET[message_id]);

if ($message_id == NULL) {
	$page_redirect = "phonemessage_view";
	$alertmessage = "The message you tried to delete does not exist.";

} else {

$sql_delete = "DELETE FROM intranet_phonemessage WHERE message_id = $message_id LIMIT 1";
		$result = mysql_query($sql_delete, $conn) or die(mysql_error());
		$page_redirect = "phonemessage_view";
		$actionmessage = "Message deleted successfully.";
		$techmessage = $sql_delete;

}

?>