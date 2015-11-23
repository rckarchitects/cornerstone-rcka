<?php


// Check that the required values have been entered, and alter the page to show if these values are invalid

if ($_POST[tasklist_id] == NULL) { $alertmessage = "Incorrect values entered."; $page = $_SERVER[QUERY_STRING]; }

else {



// Construct the MySQL instruction to add these entries to the database

$tasklist_comment = CleanUp($_POST[tasklist_comment]);
$tasklist_percentage = CleanUp($_POST[tasklist_percentage]);

$sql_edit = "UPDATE intranet_tasklist SET
tasklist_comment = '$tasklist_comment', tasklist_percentage = '$tasklist_percentage' WHERE tasklist_id = '$_POST[tasklist_id]' LIMIT 1
";

$result = mysql_query($sql_edit, $conn) or die(mysql_error());

$actionmessage = "The task has been added successfully.";

}

?>
