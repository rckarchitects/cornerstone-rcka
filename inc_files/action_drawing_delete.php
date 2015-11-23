<?php

// Begin to clean up the $_POST submissions

$drawing_id = CleanUp($_GET[drawing_id]);
$proj_id = CleanUp($_GET[proj_id]);


if ($drawing_id != NULL && $proj_id != NULL) {

		$sql_delete_drawing = "DELETE from intranet_drawings WHERE drawing_id = '$drawing_id' LIMIT 1";
		$result = mysql_query($sql_delete_drawing, $conn) or die(mysql_error());
		
		$sql_delete_revision = "DELETE from intranet_drawings_revision WHERE revision_drawing = '$drawing_id'";
		$result = mysql_query($sql_delete_revision, $conn) or die(mysql_error());
		
		$actionmessage = "Drawing deleted successfully.";
		$techmessage = $sql_delete_drawing . "<br />" . $sql_delete_revision;

}


?>
