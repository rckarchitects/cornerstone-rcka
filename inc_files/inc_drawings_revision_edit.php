<?php

$sql_drawing = "SELECT * FROM intranet_drawings WHERE drawing_id = '$_GET[drawing_id]' LIMIT 1";
$result_drawing = mysql_query($sql_drawing, $conn) or die(mysql_error());
		
		$array_drawings = mysql_fetch_array($result_drawing);
		$drawing_number = $array_drawings['drawing_number'];
		$drawing_id = $array_drawings['drawing_id'];
		$drawing_title = $array_drawings['drawing_title'];

if ($_GET[revision_id] > 0) {
	
		$revision_id = intval ($_GET[revision_id]);

		$sql_revision = "SELECT * FROM intranet_drawings_revision WHERE revision_id = $revision_id LIMIT 1";
		$result_revision = mysql_query($sql_revision, $conn) or die(mysql_error());
		$array_revision = mysql_fetch_array($result_revision);
		$revision_letter = $array_revision['revision_letter'];
		$revision_desc = $array_revision['revision_desc'];
		$revision_author = $array_revision['revision_author'];
		
		
		echo "<h1>Edit Drawing Revision for $drawing_number</h1>";
	
} else {
		
		$sql_revision = "SELECT * FROM intranet_drawings_revision WHERE revision_drawing = $drawing_id ORDER BY revision_letter DESC";
		$result_revision = mysql_query($sql_revision, $conn) or die(mysql_error());
		$array_revision = mysql_fetch_array($result_revision);
		$revision_letter = $array_revision['revision_letter'];
		
		echo "<h1>Add Drawing Revision for $drawing_number</h1>";
		
}

$revision_letters = array("-","a","b","c","d","e","f","g","h","j","k","l","m","n","p","q","r","s","t","u","v","w","x","y","z","*","a1","a2","c1","c2","p1","p2");
$revision_code = array("-","Alpha","Bravo","Charlie","Delta","Echo","Foxtrot","Golf","Hotel","Juliet","Kilo","Lima","Mike","November","Papa","Quebec","Romeo","Sierra","Tango","Uniform","Victor","Whisky","X-Ray","Yankee","Zulu","Obsolete","-Non Standard-","-Non Standard-","-Non Standard-","-Non Standard-","-Non Standard-","-Non Standard-");



print "<form method=\"post\" action=\"index2.php?page=drawings_detailed&amp;drawing_id=$_GET[drawing_id]&amp;proj_id=$_GET[proj_id]\">";



$rev_count = array_keys($revision_letters, $revision_letter);

if ($_GET[revision_id] > 0) { $rev_begin = $rev_count[0]; } else { $rev_begin = $rev_count[0] + 1; }

$rev_total = count($revision_letters);

echo "<p>Revision Letter (Currently: " . strtoupper ( $revision_letter ) . ")<br /> ";
echo "<select name=\"revision_letter\">";
while ($rev_begin < $rev_total) {
	if ($revision_letter == $revision_letters[$rev_begin]) { $selected = " selected=\"selected\" "; } else { unset($selected); } 
echo "<option value=\"$revision_letters[$rev_begin]\" $selected >" . strtoupper($revision_letters[$rev_begin]) . " " . ($revision_code[$rev_begin]) . "</option>";
$rev_begin++;
}
echo "</select></p>";

print "<p>";
print "Revision Description<br />";
print "<textarea name=\"revision_desc\" rows=\"4\" cols=\"42\">$revision_desc</textarea>";
print "</p>";

print "<p>";
print "Revision By<br />";
$data_user_var = "revision_author";
if ($revision_author > 0) { $data_user_id = $revision_author; } else { $data_user_id = $_COOKIE[user]; }
include("dropdowns/inc_data_dropdown_users.php");
print "</p>";

print "<p>";
print "Date of Revision<br />";
if ($revision_date != NULL) { $revision_date_day = date("j", $revision_date); } else { $revision_date_day = date("d", time()); }
if ($revision_date != NULL) { $revision_date_month = date("n", $revision_date); } else { $revision_date_month = date("m", time()); }
if ($revision_date != NULL) { $revision_date_year = date("Y", $revision_date); } else { $revision_date_year = date("Y", time()); }

$revision_date_value = $revision_date_year . "-" . $revision_date_month . "-" . $revision_date_day;

echo "<input type=\"date\" value=\"$revision_date_value\" name=\"revision_date_value\" />";

print "</p>";

print "<p>";
print "<input type=\"submit\" />";
print "<input type=\"hidden\" name=\"action\" value=\"revision_edit\"  />";

if ($revision_id > 0) {
	print "<input type=\"hidden\" name=\"revision_id\" value=\"$revision_id\"  />";
}

print "<input type=\"hidden\" name=\"revision_drawing\" value=\"$drawing_id\"  />";

print "</form>";



?>