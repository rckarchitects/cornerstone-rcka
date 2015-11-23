<?php

// Determine the name and number of the project chosen

	$sql = "SELECT proj_num, proj_name FROM intranet_projects WHERE proj_id = '$_POST[invoice_project]' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	$proj_num_invoice = $array['proj_num'];
	$proj_name_invoice = $array['proj_name'];

// And now the array of invoices for that project

	$sql = "SELECT * FROM intranet_timesheet_invoice WHERE invoice_project = '$_POST[invoice_project]' order by 'invoice_date' DESC";
	$result_invoice = mysql_query($sql, $conn) or die(mysql_error());
		
?>