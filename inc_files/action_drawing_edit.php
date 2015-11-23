<?php
 
// Begin to clean up the $_POST submissions

$drawing_id = $_POST[drawing_id];
$drawing_number = CleanUp($_POST[drawing_number]);
$drawing_number_1 = CleanUp($_POST[drawing_number_1]);
$drawing_number_2 = CleanUp($_POST[drawing_number_2]);
$drawing_number_3 = CleanUp($_POST[drawing_number_3]);
$drawing_number_4 = CleanUp($_POST[drawing_number_4]);
$drawing_project = CleanUp($_POST[drawing_project]);
$drawing_author = $_POST[drawing_author];
$drawing_scale = CleanNumber($_POST[drawing_scale]);
$drawing_paper = $_POST[drawing_paper];
$drawing_orientation = CleanUp($_POST[drawing_orientation]);
$drawing_title = CleanUp($_POST[drawing_title]);
$drawing_date_day = CleanNumber($_POST[drawing_date_day]);
$drawing_date_month = CleanNumber($_POST[drawing_date_month]);
$drawing_date_year = CleanNumber($_POST[drawing_date_year]);
$drawing_checked = CleanUp($_POST[drawing_checked]);
$drawing_package_list = $_POST[drawing_package_list];
$drawing_total_packages = $_POST[drawing_total_packages];
$drawing_targetdate = $_POST[drawing_targetdate];
$drawing_comment = CleanUp($_POST[drawing_comment]);

$counter = 0;
while ($counter < $drawing_total_packages) {
	if ($drawing_package_list[$counter] != NULL) {
		$drawing_packages = $drawing_package_list[$counter] . "," . $drawing_packages;
	}
	$counter++;
}

if ($drawing_number == NULL) {

		$drawing_number = $drawing_number_1 . "-" . $drawing_number_2;
		if ($drawing_number_3 != NULL) { $drawing_number = $drawing_number . "-" . $drawing_number_3; }
		$drawing_number = $drawing_number . "-" . $drawing_number_4;

}

// Check the date input

if (checkdate($drawing_date_month, $drawing_date_day, $drawing_date_year) != TRUE) {
	$alertmessage = "The date entered is invalid."; $page_redirect = "drawings_edit";
}

// Check that the required values have been entered, and alter the page to show if these values are invalid

elseif ($drawing_number == "") { $alertmessage = "The drawing number was left empty."; $page_redirect = "drawings_edit"; }

elseif ($drawing_title == "") { $alertmessage = "The drawing title was left empty."; $page_redirect = "drawings_edit"; }

else {

// Convert the date to a time

$drawing_date = mktime ( 12, 0, 0, $drawing_date_month, $drawing_date_day, $drawing_date_year );

// Construct the MySQL instruction to add these entries to the database

if ($drawing_id > 0) {

		$sql_edit = "UPDATE intranet_drawings SET
		drawing_number = '$drawing_number',
		drawing_author = '$drawing_author',
		drawing_scale = '$drawing_scale',
		drawing_paper = '$drawing_paper',
		drawing_orientation = '$drawing_orientation',
		drawing_title = '$drawing_title',
		drawing_project = '$drawing_project',
		drawing_date = '$drawing_date',
		drawing_checked = '$drawing_checked',
		drawing_packages = '$drawing_packages',
		drawing_targetdate = '$drawing_targetdate',
		drawing_comment = '$drawing_comment'
		WHERE drawing_id = '$drawing_id' LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Drawing updated successfully.";
		$techmessage = $sql_edit;
		
		//echo "<p>$sql_edit</p>";

		$drawing_affected = mysql_affected_rows();		
		
} else {

		$sql_add = "INSERT INTO intranet_drawings (
		drawing_id,
		drawing_number,
		drawing_author,
		drawing_scale,
		drawing_paper,
		drawing_orientation,
		drawing_title,
		drawing_project,
		drawing_date,
		drawing_checked,
		drawing_packages,
		drawing_targetdate,
		drawing_comment
		) values (
		'NULL',
		'$drawing_number',
		'$drawing_author',
		'$drawing_scale',
		'$drawing_paper',
		'$drawing_orientation',
		'$drawing_title',
		'$drawing_project',
		'$drawing_date',
		'$drawing_checked',
		'$drawing_packages',
		'$drawing_targetdate',
		'$drawing_comment'
		)";
		
		$result = mysql_query($sql_add, $conn) or die(mysql_error());
		$actionmessage = "Drawing added successfully.";
		$techmessage = $sql_add;
		
		$drawing_affected = mysql_affected_rows();
}	

		$page_variables = "proj_id=$drawing_project";

}

?>
