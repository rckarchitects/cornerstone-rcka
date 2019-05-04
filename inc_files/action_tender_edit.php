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
$tender_added_time = time();
$tender_added_by = intval($_POST['tender_added_by']);
$tender_responsible = intval($_POST['tender_responsible']);

if (intval($_POST['tender_linked']) == 0 ) { $tender_linked = "NULL"; } else { $tender_linked = intval($_POST['tender_linked']); }

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
		tender_notes,
		tender_linked,
		tender_added_time,
		tender_added_by,
		tender_responsible
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
		'$tender_notes',
		$tender_linked,
		$tender_added_time,
		$tender_added_by,
		$tender_responsible
		)";
		
		$tender_id = mysql_insert_id();
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());

		$actionmessage = "<p>Tender for <a href=\"index2.php?page=tender_view&amp;tender_id=" . $tender_id . "\">" . $tender_name . "</a> added successfully.</p>";
		AlertBoxInsert($_COOKIE[user],"Tender Added",$actionmessage,$tender_id,0,0);

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
		tender_notes = '$tender_notes',
		tender_linked = $tender_linked,
		tender_added_time = $tender_added_time,
		tender_added_by = $tender_added_by,
		tender_responsible = $tender_responsible
		WHERE
		tender_id = $tender_id
		LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());

		$actionmessage = "<p>Tender for <a href=\"index2.php?page=tender_view&amp;tender_id=" . $tender_id . "\">" . $tender_name . "</a> updated successfully.</p>";
		AlertBoxInsert($_COOKIE[user],"Tender Edited",$actionmessage,$tender_id,0,0);
}


