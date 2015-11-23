<?php

// Begin to clean up the $_GET submissions
$invoice_id = CleanNumber($_GET[invoice_id]);
$invoice_ref = CleanNumber($_GET[invoice_ref]);



if ($invoice_id != NULL) {

						$sql_edit = "DELETE from intranet_timesheet_invoice
						WHERE invoice_id = '$invoice_id' LIMIT 1";

			$result = mysql_query($sql_edit, $conn) or die(mysql_error());
			$actionmessage = "Invoice <strong>$invoice_ref</strong> deleted successfully.";
			$techmessage = $sql_edit;

}

?>
