<?php

if ($_GET[standard_section_id] == NULL) {

	$sql_standards = "SELECT * FROM intranet_standards_sections ORDER BY standard_section_number";
	$result_standards = mysql_query($sql_standards, $conn);
	
	echo "<h1>Office Standards</h1>";

	while ($array_standards = mysql_fetch_array($result_standards)) {

	$standard_section_id = $array_standards['standard_section_id'];
	$standard_section_number = $array_standards['standard_section_number'];
	$standard_section_title = $array_standards['standard_section_title'];
	$standard_section_description = $array_standards['standard_section_description'];

	echo "<h2><a href=\"index2.php?page=standards&amp;standard_section_id=$standard_section_id\">" . $standard_section_number . ".&nbsp;" . $standard_section_title . "</a></h2>";

	echo "<p>$standard_section_description</p>";

	}
	
	$count = 1;

} else {

$para_count = 1;

function AddPara($a,$b,$input) {

	global $para_count;
	$num = $a . "." . $b . "." . $para_count . ".&nbsp;";
	$output = str_replace("#",$num,$input);
	$para_count++;
	return $output;

}

	$sql_standards = "SELECT * FROM intranet_standards_sections, intranet_standards_clauses, intranet_standards_paras WHERE standard_clause_section = '$_GET[standard_section_id]' AND standard_clause_section = standard_section_id AND standard_para_clause = standard_clause_id ORDER BY standard_section_number, standard_clause_number, standard_para_number";
	$result_standards = mysql_query($sql_standards, $conn);
	
	$count = 0;
	$clause = 0;

	while ($array_standards = mysql_fetch_array($result_standards)) {

	$standard_section_id = $array_standards['standard_section_id'];
	$standard_section_number = $array_standards['standard_section_number'];
	$standard_section_title = $array_standards['standard_section_title'];
	$standard_clause_id = $array_standards['standard_clause_id'];
	$standard_clause_title = $array_standards['standard_clause_title'];
	$standard_clause_number = $array_standards['standard_clause_number'];
	$standard_clause_text = $array_standards['standard_clause_text'];
	$standard_para_id = $array_standards['standard_para_id'];
	$standard_para_number = $array_standards['standard_para_number'];
	$standard_para_title = $array_standards['standard_para_title'];
	$standard_para_text = $array_standards['standard_para_text'];
	
	$standard_clause_text = AddPara($standard_section_number,$standard_clause_number,$standard_clause_text);
	
	$clause = $standard_clause_id;
	
	if ($count == 0) {
		echo "<h1>" . $standard_section_number . ".&nbsp;" . $standard_section_title . "</h1>";
	}

	if ($clause != $standard_clause_id) {
	echo "<h2><a href=\"index2.php?page=standards&amp;standard_section_id=$standard_section_id&amp;standard_clause_id=$standard_clause_id\">" . $standard_section_number . "." . $standard_clause_number . ".&nbsp;" . $standard_clause_title . "</a></h2>";
	$clause = $standard_clause_id;
	}
	
	echo "<h3>" . $standard_section_number . "." . $standard_clause_number . "." . $standard_para_number . "." . $standard_para_title . "</h3>";
	
	echo $standard_clause_text;

	if ($standard_para_text) { echo "<p>$standard_para_text</p>"; }
	
	$count++;

	}

}

if ($count == 0) { echo "<h1>Office Standards</h1>"; echo "<p>No standards have been found for this heading.</p>";  }


?>
