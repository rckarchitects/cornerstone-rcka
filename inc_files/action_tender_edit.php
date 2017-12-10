<?php

// Begin to clean up the $_POST submissions


$tender_id = intval($_POST['tender_id']);
$tender_name = CleanUp($_POST['tender_name']);
$tender_client = CleanUp($_POST['tender_client']);
$tender_date = CreateTimeFromDetailedTime($_POST['tender_date_time'],$_POST['tender_date_day']);
$tender_type = CleanUp($_POST['tender_type']);
$tender_procedure = CleanUp($_POST['tender_procedure']);
$tender_description = CleanUp($_POST['tender_description']);
$tender_keywords = $_POST['tender_keywords'];
$tender_source = CleanUp($_POST['tender_source']);
$tender_instructions = CleanUp($_POST['tender_instructions']);
$tender_result = intval($_POST['tender_result']);
$tender_submitted = intval($_POST['tender_submitted']);
$tender_notes = CleanUp($_POST['tender_notes']);

// Construct the MySQL instruction to add these entries to the database

if ($tender_id == 0) {

		$sql_edit = "INSERT INTO intranet_tender (
		tender_id,
		tender_name,
		tender_client,
		tender_date,
		tender_type,
		tender_procedure,
		tender_description,
		tender_keywords,
		tender_source,
		tender_instructions,
		tender_result,
		tender_submitted,
		tender_notes
		) values (
		'NULL',
		'$tender_name',
		'$tender_client',
		'$tender_date',
		'$tender_type',
		'$tender_procedure',
		'$tender_description',
		NULL,
		'$tender_source',
		'$tender_instructions',
		$tender_result,
		$tender_submitted,
		'$tender_notes'
		)";

		$actionmessage = "Tender added successfully.";

} else {

		$sql_edit = "UPDATE intranet_tender SET
		tender_name = '$tender_name',
		tender_client = '$tender_client',
		tender_date = '$tender_date',
		tender_type = '$tender_type',
		tender_procedure = '$tender_procedure',
		tender_description = '$tender_description',
		tender_keywords = NULL,
		tender_source = '$tender_source',
		tender_instructions = '$tender_instructions',
		tender_result = $tender_result,
		tender_submitted = $tender_submitted,
		tender_notes = '$tender_notes'
		WHERE
		tender_id = $tender_id
		LIMIT 1";

		$actionmessage = "Tender updated successfully.";
}


$result = mysql_query($sql_edit, $conn) or die(mysql_error());



$techmessage = $sql_edit;

?>
