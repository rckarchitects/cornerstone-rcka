<?php

$answer_id = htmlentities ($_GET[answer_id]);

$sql_unlock = "UPDATE intranet_tender_answers SET answer_lock = NULL WHERE answer_id = $answer_id LIMIT 1";
$result_unlock = mysql_query($sql_unlock, $conn) or die(mysql_error());







?>