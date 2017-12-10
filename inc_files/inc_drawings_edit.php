<?php

if ($_GET[drawing_id] != NULL && $_GET[drawing_edit] == "yes") {
echo "<h2>Edit Existing Drawing</h2>";
$sql = "SELECT * FROM intranet_drawings, intranet_projects WHERE drawing_id = '$_GET[drawing_id]' AND drawing_project = proj_id LIMIT 1";
$result = mysql_query($sql, $conn) or die(mysql_error());
$array = mysql_fetch_array($result);
$drawing_id = $array['drawing_id'];
$drawing_name = $array['drawing_name'];
$drawing_project = $array['drawing_project'];
$drawing_number = $array['drawing_number'];
$drawing_title = $array['drawing_title'];
$drawing_author = $array['drawing_author'];
$drawing_scale = $array['drawing_scale'];
$drawing_orientation = $array['drawing_orientation'];
$drawing_date = $array['drawing_date'];
$drawing_paper = $array['drawing_paper'];
$drawing_packages = $array['drawing_packages'];
$drawing_targetdate = $array['drawing_targetdate'];
$drawing_comment = $array['drawing_comment'];
$drawing_status = $array['drawing_status'];
$proj_id = $drawing_project;
$proj_num = $array['proj_num'];
} else {
echo "<h2>Add New Drawing</h2>";

$sql = "SELECT * FROM intranet_projects WHERE proj_id = '$_GET[proj_id]' LIMIT 1";
$result = mysql_query($sql, $conn) or die(mysql_error());
$array = mysql_fetch_array($result);
$proj_num = $array['proj_num'];

unset($drawing_id);
unset($drawing_name);
unset($drawing_project);
$drawing_number = $proj_num;
unset($drawing_title);
unset($drawing_author);
unset($drawing_scale);
unset($drawing_orientation);
unset($drawing_date);
unset($drawing_paper);
unset($drawing_packages);
unset($drawing_targetdate);
unset($drawing_comment);
unset($drawing_status);
$proj_id = $_GET[proj_id];
}

if ($_GET[proj_id] == NULL && $drawing_project == NULL) { echo "<p>No project selected</p>"; } else {

if ($drawing_project == NULL) { $proj_id = $_GET[proj_id]; }

echo "<form method=\"post\" action=\"index2.php?page=drawings_list&amp;proj_id=$proj_id\" id=\"form1\">";

echo "<p>";
echo "Drawing Number<br />";

if ($_GET[drawing_id] == NULL) {
	echo "<input type=\"radio\" name=\"choose_drawing_name\" id=\"radio_preset\" value=\"preset\" onChange=\"disablefield();\" checked=\"checked\" />&nbsp;";
}
		if ($_GET[drawing_id] == NULL) {
		echo "<input type=\"text\" value=\"$drawing_number\" name=\"drawing_number_1\" maxlength=\"50\" style=\"text-align: right; width: 45px;\" id=\"text1.1\" disabled=\"disabled\" readonly=\"readonly\" />";
		echo "-";
		echo "<select name=\"drawing_number_2\" id=\"text1.2\" disabled=\"disabled\" >";
		
		
		$sql_tier1 = "SELECT class_num, class_name FROM intranet_drawings_standards_class WHERE class_tier = 1 ORDER BY class_order";
		$result_tier1 = mysql_query($sql_tier1, $conn) or die(mysql_error());
		while ($array_tier1 = mysql_fetch_array($result_tier1)) {
		
			echo "<option value=\"" . $array_tier1['class_num'] . "\">" . $array_tier1['class_num'] . " (" . str_replace ("\n", " - ", $array_tier1['class_name']) . ")</option>";
			
		}
		
		echo "</select>";
		echo "-";
		
		
		
		// Establish how many of the tier 3 drawing types we are expected to show.
		
		$sql_class = "SELECT class_num FROM intranet_drawings_standards_class WHERE class_tier = 2 ORDER BY class_order";
		$result_class = mysql_query($sql_class, $conn) or die(mysql_error());
		$array_class_count = array();
		while ($array_class = mysql_fetch_array($result_class)){
		$array_class_count[] = $array_class['class_num'];
		}
		

		
		
		
		// This is where we need to insert a <script> that filters the subsequent <select>
		
		
		function HiddenTier4($array,$current) {
			foreach ($array AS $sub) {
				if ($sub == $current) {
					echo "document.getElementById('text1.4" . $sub . "').style.display='';\n";
				} else {
					echo "document.getElementById('text1.4" . $sub . "').style.display='none';\n";
				}
			}
		}	
	
		function HideScript($array) {
			$counter = 1;
			echo "<script type=\"text/javascript\"> 
				function hideClass(t){\n";
				
			foreach ($array AS $sub) {
					if ($counter == 1) {
						echo "if (document.getElementById('text1.3').value == \"$sub\"){\n";
					} else {
						echo "} else if (document.getElementById('text1.3').value == \"$sub\"){\n";
					}
					HiddenTier4($array,$sub);					
					$counter++;
			}
			

			echo "
						} else {
						";
			
			echo "	
						}
						} 
						</script>";

		}
		
		HideScript($array_class_count);
		
		echo "<select name=\"drawing_number_3\"  id=\"text1.3\" disabled=\"disabled\" onChange = \"hideClass(this);\">";
		
		$sql_tier2 = "SELECT class_num, class_name FROM intranet_drawings_standards_class WHERE class_tier = 2 ORDER BY class_order";
		$result_tier2 = mysql_query($sql_tier2, $conn) or die(mysql_error());
		
		echo "<option value=\"\">- Select -</option>";
		
		while ($array_tier2 = mysql_fetch_array($result_tier2)) {
		
			echo "<option value=\"" . $array_tier2['class_num'] . "\">" . $array_tier2['class_num'] . " (" . str_replace ("\n", " - ", $array_tier2['class_name']) . ")</option>";
			
		}
		echo "</select>";
		
		echo "-";
		
		$sql_tier0 = "SELECT standard_num, standard_desc, standard_paper, standard_scale, standard_orient, standard_class FROM intranet_drawings_standards_all ORDER BY standard_num";
		$result_tier0 = mysql_query($sql_tier0, $conn) or die(mysql_error());
	
	
	
		echo "
		<script>
        function changeDrawing(t) {
            var otionValue = t.value;
            if (otionValue == \"\") {
              document.getElementById('drawing_title').innerHTML = \"\";
            } ";
			
		while ($array_tier0 = mysql_fetch_array($result_tier0)) {
		
			echo "
				
				else if (otionValue == \"" . $array_tier0['standard_class'] . "-" . $array_tier0['standard_num'] . "\") {
					document.getElementById('drawing_title').innerHTML = \"" . str_replace ("|", "\\n", $array_tier0['standard_desc']) . "\"
					document.getElementById('drawing_scale').value = " . $array_tier0['standard_scale'] . "
					document.getElementById('drawing_orientation').value = \"" . $array_tier0['standard_orient'] . "\"
					document.getElementById('drawing_paper').value = " . $array_tier0['standard_paper'] . ";
				}
				";
				
			}
			
		echo "
        }; 
		</script>
		";
		
		
		$sql_tier3 = "SELECT standard_num, standard_desc, standard_class FROM intranet_drawings_standards_all ORDER BY standard_class,standard_num";
		$result_tier3 = mysql_query($sql_tier3, $conn) or die(mysql_error());
		unset($standard_class);
		
		
							
					while ($array_tier3 = mysql_fetch_array($result_tier3)) {
						if ($standard_class != $array_tier3['standard_class'] && $standard_class != NULL) { echo "</select>"; }
						if ($standard_class != $array_tier3['standard_class']) { echo "<select name=\"drawing_number_4". $array_tier3['standard_class'] ."\" id=\"text1.4" . $array_tier3['standard_class'] . "\" disabled=\"disabled\"  onChange = \"changeDrawing(this);\" style=\"display: none\">"; $standard_class = $array_tier3['standard_class']; echo "<option value=\"\">- Select -</option>";}
						echo "<option value=\"" . $array_tier3['standard_class']."-".$array_tier3['standard_num'] . "\">" . $array_tier3['standard_num'] . " (" . str_replace ("|", " - ", $array_tier3['standard_desc']) . ")</option>";
						
					}
					echo "</select>";		
				
		// Function to enable or disable tier 4 drawing numbers depending on tier 3 selection
		function ExcludeTier4($array,$disabled) {
			$counter = 1;
			foreach ($array AS $sub) {
				if ($disabled > 0) {
					echo "document.getElementById('text1.4$sub').disabled='';\n";
				} else {
					echo "document.getElementById('text1.4$sub').disabled='disabled';\n";
				}
			}
		}		
		
		
					
		
		echo "<script type=\"text/javascript\"> 
			function disablefield(){ 
				if (document.getElementById('radio_custom').checked == 1){ 
						document.getElementById('text1.1').disabled='disabled';
						document.getElementById('text1.2').disabled='disabled';
						document.getElementById('text1.3').disabled='disabled';\n";
						ExcludeTier4($array_class_count);
						echo "document.getElementById('text3').disabled='disabled';
						document.getElementById('text2').disabled='';
				} else if (document.getElementById('radio_standard').checked == 1){ 
						document.getElementById('text1.1').disabled='disabled';
						document.getElementById('text1.2').disabled='disabled';
						document.getElementById('text1.3').disabled='disabled';\n";
						ExcludeTier4($array_class_count);
						echo "document.getElementById('text2').disabled='disabled';
						document.getElementById('text3').disabled='';
				} else {
						document.getElementById('text1.1').disabled='';
						document.getElementById('text1.2').disabled='';
						document.getElementById('text1.3').disabled='';\n";
						ExcludeTier4($array_class_count,1);
						echo "document.getElementById('text2').disabled='disabled';
						document.getElementById('text3').disabled='disabled';
						
				} 
			} 
		</script>";
		
		if ($_GET[drawing_id] == NULL) {
			echo "<br /><input type=\"radio\" name=\"choose_drawing_name\"  checked=\"checked\" id=\"radio_custom\" value=\"custom\" onChange=\"disablefield();\" />&nbsp;";
			echo "<input type=\"text\" name=\"drawing_number\" required=\"required\" id=\"text2\" />";
		}
		
		if ($_GET[drawing_id] == NULL) {
			echo "<br /><input type=\"radio\" name=\"choose_drawing_name\"  id=\"radio_standard\" value=\"standard\" onChange=\"disablefield();\" />&nbsp;";
			echo "<select name=\"standard_drawing_name\" id=\"text3\" value=\"preset\"  disabled=\"disabled\" />";
			echo "<option disabled>Planning Documents</option>";
			echo "<option value=\"$proj_num-PL-DOC-001\" id=\"radio_preset\" />$proj_num-PL-DOC-001 (Design &amp; Access Statement)</option>";
			echo "<option disabled></option><option disabled>Other Documents</option>";
			echo "<option value=\"$proj_num-DOC-100\" id=\"radio_preset\" />$proj_num-DOC-001 (Accommodation Schedule)</option>";
			echo "</select>";
		}	
		
		} else { echo "<input type=\"text\" name=\"drawing_number\" value=\"$drawing_number\" required=\"required\" />"; }
echo "</p>";

//	Drawing packages

		echo "<div style=\"float: right; width: 60%\">";

		echo "<fieldset><legend>Work Packages</legend>";
		
		$array_code = array("D20,D30,D40,D50", "F10,F30", "F31", "G20", "H30", "H51, H60", "J42", "K10,M20", "K40", "N10,N11", "R10","R11","R12", "S12", "T32", "U10", "V90","W40,W44,W90", "X10");
		$array_desc = array("Groundworks", "Carpentry / Timber Framing / First Fixing","Brick / Block Masonry", "Precast Concrete Sills, Lintels, Copings, Features" , "Cladding", "Roofing", "Single-Ply Membranes", "Dry Linings / Partitions / Plastering", "Suspended Ceilings", "Joinery / Fixtures / Kitches","General Lighting &amp; Power", "Rainwater Pipework / Gutters", "Foul Drainage Above Ground","Foul Drainage Below Ground", "Hot &amp; Cold Water", "Low-Temperature Hot Water Heating", "General Ventilation", "Access Control, CCTV, Communications &amp; Security", "Vertical Transport Systems");
		$counter = 1;
		$total = count($array_code);
		
		if (strpos($drawing_packages,"all") > 0) { $checked = "checked=\"checked\""; } else { unset($checked); }
		
		//Search the string for the relevant NBS code and return "checked" if found.
		
		while ($counter < $total) {
		
			if (strpos($drawing_packages,$array_code[$counter]) === false) { unset($checked); } else { $checked = "checked=\"checked\""; }
		
			echo "<input type=\"checkbox\" name=\"drawing_package_list[$counter]\" $checked value=\"$array_code[$counter]\" />&nbsp;$array_desc[$counter]&nbsp; ($array_code[$counter])<br />";	
			$counter++;
		}
		echo "<input type=\"hidden\" name=\"drawing_total_packages\" value=\"" . count($array_code) . "\" />";
		echo "</fieldset>";

		echo "</div>"; 

echo "<p>";
echo "Drawing Title<br />";
echo "<textarea name=\"drawing_title\" id=\"drawing_title\" rows=\"4\" cols=\"42\" required=\"required\">$drawing_title</textarea>";
echo "</p>";

echo "<p>";
echo "Drawing By<br />";
$data_user_var = "drawing_author";
if ($drawing_author > 0) { $data_user_id = $drawing_author; } else { $data_user_id = $_COOKIE[user]; }
include("dropdowns/inc_data_dropdown_users.php");
echo "</p>";

echo "<p>";
echo "Drawing Scale<br />";
$result_data = "drawing_scale";
include("dropdowns/inc_data_dropdown_drawings_scale.php");
echo "</p>";

echo "<p>";
echo "Drawing Paper<br />";
$result_data = "drawing_paper";
include("dropdowns/inc_data_dropdown_drawings_paper.php");
echo "</p>";

echo "<p>";
echo "Drawing Orientation<br />";
echo "<input type=\"radio\" name=\"drawing_orientation\" id=\"drawing_orientation\" value=\"l\"";
	if ($drawing_orientation == "l" OR $drawing_orientation != "p") { echo " checked "; }
echo " />&nbsp;Landscape<br />";
echo "<input type=\"radio\" name=\"drawing_orientation\" value=\"p\"";
	if ($drawing_orientation == "p") { echo " checked ";}
echo "/>&nbsp;Portrait";
echo "</p>";


echo "<p>";
echo "Status<br />";

	DrawingStatusDropdown ($drawing_status,"drawing_status");
	
echo "</p>";


echo "<p>";
echo "Date of Drawing (DD/MM/YYYY)<br />";
if ($drawing_date != NULL) { $drawing_date_day = date("j", $drawing_date); } else { $drawing_date_day = date("j", time()); }
if ($drawing_date != NULL) { $drawing_date_month = date("n", $drawing_date); } else { $drawing_date_month = date("n", time()); }
if ($drawing_date != NULL) { $drawing_date_year = date("Y", $drawing_date); } else { $drawing_date_year = date("Y", time()); }
echo "<input type=\"text\" name=\"drawing_date_day\" value=\"$drawing_date_day\" maxlength=\"2\" size=\"4\" required=\"required\" />&nbsp;Day&nbsp;"; 
echo "<input type=\"text\" name=\"drawing_date_month\" value=\"$drawing_date_month\" maxlength=\"2\" size=\"4\" required=\"required\" />&nbsp;Month&nbsp;"; 
echo "<input type=\"text\" name=\"drawing_date_year\" value=\"$drawing_date_year\" maxlength=\"4\" size=\"4\" required=\"required\" />&nbsp;Year"; 
echo "</p>";


echo "<p>";
echo "Target Date for Drawing Issue (DD/MM/YYYY)<br />";
echo "<input type=\"date\" name=\"drawing_targetdate\" value=\"$drawing_targetdate\"/>";
echo "</p>";

echo "<p>";
echo "Comment<br />";
echo "<input type=\"text\" name=\"drawing_comment\" value=\"$drawing_comment\" maxlength=\"200\"/>";
echo "</p>";

echo "<p>";
echo "<input type=\"submit\" />";
echo "<input type=\"hidden\" name=\"action\" value=\"drawing_edit\"  />";
echo "<input type=\"hidden\" name=\"drawing_project\" value=\"$proj_id\"  />";
echo "</p>";


if ($drawing_id != NULL) {
	echo "<input type=\"hidden\" name=\"drawing_id\" value=\"$drawing_id\"  />";
}

echo "</form>";

}

?>