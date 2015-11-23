<?php

if ($_GET[proj_id] != NULL) { $proj_id = CleanUp($_GET[proj_id]); } elseif ($_POST[proj_id] != NULL) { $proj_id = CleanUp($_POST[proj_id]); }



$sql_project = "SELECT proj_num, proj_name FROM intranet_projects WHERE proj_id = $proj_id LIMIT 1";
$result_project = mysql_query($sql_project, $conn) or die(mysql_error());
$array_project = mysql_fetch_array($result_project);
$proj_num = $array_project['proj_num'];
$proj_name = $array_project['proj_name'];

echo "<p class=\"submenu_bar\"><a href=\"pdf_drawing_list.php?proj_id=$proj_id\" class=\"submenu_bar\">Drawing Schedule&nbsp;<img src=\"images/button_pdf.png\" alt=\"Download drawing list as PDF\" /></a><a href=\"pdf_drawing_matrix.php?proj_id=$proj_id\" class=\"submenu_bar\">Drawing Matrix&nbsp;<img src=\"images/button_pdf.png\" alt=\"Download drawing matrix as PDF\" /></a></p>";

print "<h2>Drawing List</h2>";

if ($proj_id == NULL) {

	echo"<p>No project selected.</p>";

} else {
	
	
	
	
	
	

	
	function ClassList($array_class_1,$array_class_2,$type) {
	GLOBAL $proj_id;
	GLOBAL $drawing_class;
	GLOBAL $drawing_type;
	
	echo "<select name=\"$type\" onchange=\"this.form.submit()\">";
	$array_class_count = 0;
	foreach ($array_class_1 AS $class) {
		echo "<option value=\"$class\"";
		
		if ($drawing_class == $class && $type == "drawing_class" ) { echo " selected=\"selected\" "; }
		elseif ($drawing_type == $class && $type == "drawing_type" ) { echo " selected=\"selected\" "; }
		
		echo ">";		
		echo $array_class_2[$array_class_count];
		echo "</option>";
		$array_class_count++;
		}
		echo "</select>";
		
	}
	
	$drawing_class = $_POST[drawing_class];
	$drawing_type = $_POST[drawing_type];
	echo "<form method=\"post\" action=\"index2.php?page=drawings_list&amp;proj_id=$proj_id&amp;drawing_class=$drawing_class&amp;drawing_type=$drawing_type\" >";
	$array_class_1 = array("","SK","PL","TD","CN","CT","FD");
	$array_class_2 = array("- All -","Sketch","Planning","Tender","Contract","Construction","Final Design");
	echo "<p>Filter: ";
	ClassList($array_class_1,$array_class_2,"drawing_class");
	echo "&nbsp;";
	$array_class_1 = array("","SV","ST","GA","AS","DE","DOC");
	$array_class_2 = array("- All -","Survey","Site Location","General Arrangement","Assembly","Detail","Document");
	ClassList($array_class_1,$array_class_2,"drawing_type");
	echo "</p></form>";
	
	if ($drawing_class != NULL) { $drawing_class = " AND drawing_number LIKE '%-$drawing_class-%' "; } else { unset($drawing_class); }
	if ($drawing_type != NULL) { $drawing_type = " AND drawing_number LIKE '%-$drawing_type-%' "; } else { unset($drawing_type); }

$sql = "SELECT * FROM intranet_drawings, intranet_drawings_scale, intranet_drawings_paper WHERE drawing_project = $proj_id AND drawing_scale = scale_id AND drawing_paper = paper_id $drawing_class $drawing_type order by drawing_number";
$result = mysql_query($sql, $conn) or die(mysql_error());


		if (mysql_num_rows($result) > 0) {

		print "<table summary=\"Lists all of the drawings for the project\">";
		print "<tr><td><strong>Drawing Number</strong></td><td><strong>Title</strong></td><td><strong>Rev.</strong></td><td><strong>Scale</strong></td><td><strong>Paper</strong></td></tr>";

		while ($array = mysql_fetch_array($result)) {
		$drawing_id = $array['drawing_id'];
		$drawing_number = $array['drawing_number'];
		$scale_desc = $array['scale_desc'];
		$paper_size = $array['paper_size'];
		$drawing_title = $array['drawing_title'];
		$drawing_author = $array['drawing_author'];
		
		$sql_rev = "SELECT * FROM intranet_drawings_revision WHERE revision_drawing = '$drawing_id' ORDER BY revision_letter DESC LIMIT 1";
		$result_rev = mysql_query($sql_rev, $conn) or die(mysql_error());
		$array_rev = mysql_fetch_array($result_rev);
		if ($array_rev['revision_letter'] != NULL) { $revision_letter = strtoupper($array_rev['revision_letter']); } else { $revision_letter = " - "; }
		
		if ($revision_letter == "*") { $strikethrough = "; text-decoration: strikethrough"; } else { unset($strikethrough); }
		
		if ($drawing_id == $drawing_affected) { $background = " style=\"bgcolor: red; $strikethrough\""; } else { unset($background); }		

		echo "<tr><td $background><a href=\"index2.php?page=drawings_detailed&amp;drawing_id=$drawing_id&proj_id=$proj_id\">$drawing_number</a>";
		
		if ($drawing_author == $_COOKIE[user] OR $user_usertype_current > 2) {
			print "&nbsp;<a href=\"index2.php?page=drawings_edit&amp;drawing_id=$drawing_id&amp;proj_id=$proj_id&amp;drawing_edit=yes\"><img src=\"images/button_edit.png\" alt=\"Edit this drawing\" /></a>";
		}

		print "</td><td $background>".nl2br($drawing_title)."</td><td $background>$revision_letter</td><td $background>$scale_desc</td><td $background>$paper_size</td>";


		print "</tr>";

		}

		print "</table>";
		
		

		} else {

		print "<p>No drawings found.</p>";

		}
	
}

include_once("inc_drawings_edit.php");


		
?>