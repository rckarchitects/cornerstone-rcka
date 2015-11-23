<?php

if ($_POST[proj_id]) { $proj_id = $_POST[proj_id]; } elseif ($_POST[invoice_project]) { $proj_id = $_POST[invoice_project]; }

print "<h1>Invoices</h1>";

if ($proj_id != NULL) { 

	include("inc_files/inc_project_invoices.php");

} else {

print "<p>The project reference is empty.</p>";

}
	
?>