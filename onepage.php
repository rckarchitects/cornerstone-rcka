<?php

include_once "inc_files/inc_checkcookie.php";

function ReceiveOnepageWebhook() {
	
	//echo "<h1>Webhook</h1>";
	
	global $conn;
	
	$onepage_timestamp = intval($_POST[timestamp]);
	$onepage_data = "'" . addslashes(htmlentities($_POST[data])) . "'";
	$onepage_type = "'" . addslashes(htmlentities($_POST[type])) . "'";
	$onepage_reason = "'" . addslashes(htmlentities($_POST[reason])) . "'";
	
	$sql = "		INSERT INTO intranet_contacts_onepage (
					onepage_id,
					onepage_timestamp,
					onepage_data,
					onepage_type,
					onepage_reason
					) values (
					NULL,
					$onepage_timestamp,
					$onepage_data,
					$onepage_type,
					$onepage_reason
					)";
					
	//echo "<p>" . $sql . "</p>";

$result = mysql_query($sql, $conn) or die(mysql_error());
	
	
}

//if ($_POST[secretkey] == "q8745uyfgjhwfarhbjkger") {
	ReceiveOnepageWebhook();
//}