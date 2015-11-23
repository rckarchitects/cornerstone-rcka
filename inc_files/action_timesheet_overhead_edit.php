<?php

// Check that the required values have been entered, and alter the page to show if these values are invalid

if ($_POST[overhead_rate] == "") { $alertmessage = "The overhead rate was left empty."; $page = "timesheet_rates_overhead"; }

else {

// Begin to clean up the $_POST submissions

$overhead_rate = CleanUp($_POST[overhead_rate]);
$overhead_date = time();

// Construct the MySQL instruction to add these entries to the database

$sql_add = "INSERT INTO intranet_timesheet_overhead (overhead_id, overhead_rate, overhead_date) values ('NULL', '$overhead_rate', '$overhead_date')";

$result = mysql_query($sql_add, $conn) or die(mysql_error());

$actionmessage = "The updated overhead cost was added successfully.";

$techmessage = $sql_add;

}

?>
