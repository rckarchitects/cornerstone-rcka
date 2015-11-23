<?php

$settings_file = '/secure/database.inc';

if (file_exists($settings_file) != TRUE) {
header ("Location: login.php");
}
 
if ($_COOKIE[user] == "invalid") {
header ("Location: login.php");
}
if ($_COOKIE[user] != NULL) {
header ("Location: index2.php?cookie=$_COOKIE[user]");
}

if ($_COOKIE[user] == NULL) {
header ("Location: login.php");
}
?>
