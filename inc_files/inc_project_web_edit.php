<?php

echo "<p>Test</p>";

include_once("secure/database_website.inc");

// Check if we're updating the database

if ($_POST[action] == "edit") {

		$project_num = CleanUp($_POST[project_num]);
		$project_title = CleanUp($_POST[project_title]);
		$project_location = CleanUp($_POST[project_location]);
		$project_desc = addslashes ( $_POST[project_desc] );
		$project_type = CleanUp($_POST[project_type]);
		$project_date = CleanUp($_POST[project_date]);
		$project_keyword = CleanUp($_POST[project_keyword]);
		$project_time = CleanUp($_POST[project_time]);
		$project_id = $_POST[project_id];

		$sql_edit = "UPDATE rcka_projects SET
		project_num = '$project_num',
		project_title = '$project_title',
		project_location = '$project_location',
		project_desc = '$project_desc',
		project_type = '$project_type',
		project_date = '$project_date',
		project_keyword = '$project_keyword',
		project_time = '$project_time'
		WHERE project_id = '$project_id' LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn_web) or die(mysql_error());
		$actionmessage = "Website entry updated successfully.";
		$techmessage = $sql_edit;	

}

// List the current project as a form

if ($_POST[project_num] != NULL) {$project_num = html_entity_decode($_POST[project_num]); } else { $project_num = html_entity_decode($_GET[project_num]); }

$sql_web = "SELECT * FROM rcka_projects where project_num = '$project_num' LIMIT 1";
$result_web = mysql_query($sql_web, $conn_web);
$array_web = mysql_fetch_array($result_web);
$project_id = $array_web['project_id'];
$project_num = $array_web['project_num'];
$project_title = $array_web['project_title'];
$project_location = $array_web['project_location'];
$project_desc = $array_web['project_desc'];
$project_date = $array_web['project_date'];
$project_keyword = $array_web['project_keyword'];
$project_time = $array_web['project_time'];
$project_type = $array_web['project_type'];

$project_types_array = array("current", "completed", "publications", "competitions", "awards");

echo "
<fieldset><legend>Edit Details</legend>
<form method=\"post\" action=\"index2.php?page=project_web_edit&amp;project_num=$project_num\">
<p><input type=\"text\" value=\"$project_title\" name=\"project_title\" /> Project Title</p>
<p><input type=\"text\" value=\"$project_num\" name=\"project_num\" /> Project Reference</p>
<p><input type=\"text\" value=\"$project_location\" name=\"project_location\" /> Location</p>
<p>Description<br /><textarea name=\"project_desc\" cols=\"58\" rows=\"28\">$project_desc</textarea>
<p><input type=\"text\" value=\"$project_date\" name=\"project_date\" /> Project Date</p>
<p><input type=\"text\" value=\"$project_time\" name=\"project_time\" /> Project Time</p>
<p><input type=\"text\" value=\"$project_type\" name=\"project_type\" /> Project Type</p>
<p><input type=\"text\" value=\"$project_keyword\" name=\"project_keyword\" maxlength=\"50\" size=\"50\" /> Project Keywords</p>
<p><input type=\"submit\" value=\"Update\" />
<input type=\"hidden\" name=\"action\" value=\"edit\" />
<input type=\"hidden\" name=\"project_id\" value=\"$project_id\" />
</form>
</fieldset>



";



?>