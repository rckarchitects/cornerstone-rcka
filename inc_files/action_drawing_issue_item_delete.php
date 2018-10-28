<?php

// Begin to clean up the $_POST submissions

$set_id = CleanUp($_GET[set_id]);
$issue_drawing = CleanUp($_GET[issue_drawing]);
$proj_id = CleanUp($_GET[proj_id]);


if ($set_id > 0 && $proj_id > 0 && $issue_drawing > 0) {

		$sql_delete_drawing = "DELETE from intranet_drawings_issued WHERE issue_drawing = '$issue_drawing' AND issue_set = '$set_id'";
		//$result = mysql_query($sql_delete_drawing, $conn) or die(mysql_error());
		echo "<p>" . $sql_delete_drawing . "</p>";
		
		$actionmessage = "<p>Drawing issue set ref. $set_id deleted successfully.</p>";
		$techmessage = $sql_delete_drawing . "<br />" . $sql_delete_revision;
		AlertBoxInsert($_COOKIE[user],"Drawing issue ref. " . $set_id . " deleted",$actionmessage,$set_id,0,0,$proj_id);

}
