<?php

// Check to see if a message is being added to the system

if ($_POST[action] == "addmessage") {

include("secure_passwords/inc_passwords.inc");
$conn = mysql_connect("$database_location", "$database_username", "$database_password");
mysql_select_db("$database_name", $conn);
$sql = "INSERT INTO phonemessage (id, name_from, name_for, name_taken, time_taken, date_taken, project_reference, message_viewed, message_text, message_status, message_importance) values ('NULL', '$_POST[name_from]', '$_POST[name_for]', '$_POST[name_taken]', '$_POST[time_taken]', '$_POST[date_taken]', '$_POST[project_reference]',  '$_POST[message_viewed]',  '$_POST[message_text]',  '$_POST[message_status]', '$_POST[message_importance]')";
mysql_query($sql, $conn);
$show = "viewnew"; 
mysql_close($conn);

} 

?>