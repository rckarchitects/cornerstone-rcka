<?php

// Begin to clean up the $_POST submissions

$set_id = CleanUp($_GET[set_id]);
$proj_id = CleanUp($_GET[proj_id]);


if ($set_id != NULL && $proj_id != NULL) {

		$sql_delete_set= "DELETE from intranet_drawings_issued_set WHERE set_id = '$set_id' LIMIT 1";
		$result_set = mysql_query($sql_delete_set, $conn) or die(mysql_error());
		
		$sql_delete_issued = "DELETE from intranet_drawings_issued WHERE issue_set = '$set_id'";
		$result_issued = mysql_query($sql_delete_issued, $conn) or die(mysql_error());
		
		$techmessage = $sql_delete_set . "<br />" . $sql_delete_issued;
		
		$actionmessage = "<p>Drawing issue set ref. $set_id deleted successfully.</p>";
		AlertBoxInsert($_COOKIE[user],"Drawing Issue Deleted",$actionmessage,$set_id,0,0);

}	


?>
