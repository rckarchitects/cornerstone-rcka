<?php

		echo "<h1>Drawing Issue</h1>";

if ($_GET[proj_id] != NULL) {

$proj_id = CleanUp($_GET[proj_id]);

if ($_GET[drawing_packages] != NULL) {
	$drawing_packages = CleanUp($_GET[drawing_packages]);
	
	// Not sure why this statement doesn't work...
		$sql_drawing_packages = " AND drawing_packages LIKE '%" . $drawing_packages ."%'";
	} else {
		unset($sql_drawing_packages);
	}
	
	
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
	echo "<form method=\"post\" action=\"index2.php?page=drawings_issue&amp;proj_id=$proj_id&amp;drawing_class=$drawing_class&amp;drawing_type=$drawing_type\" >";
	$array_class_1 = array("","SK","PL","TD","CN","CT","FD");
	$array_class_2 = array("- All -","Sketch","Planning","Tender","Contract","Construction","Final Design");
	echo "<p>Filter: ";
	ClassList($array_class_1,$array_class_2,"drawing_class");
	echo "&nbsp;";
	$array_class_1 = array("","SV","ST","GA","AS","DE","DOC");
	$array_class_2 = array("- All -","Survey","Site Location","General Arrangement","Assembly","Detail","Document");
	ClassList($array_class_1,$array_class_2,"drawing_type");
	echo "<br /><span class=\"minitext\">(Note that changing these filters will clear anything you have selected below.)</span></p></form>";
	
	if ($drawing_class != NULL) { $drawing_class = " AND drawing_number LIKE '%-$drawing_class-%' "; } else { unset($drawing_class); }
	if ($drawing_type != NULL) { $drawing_type = " AND drawing_number LIKE '%-$drawing_type-%' "; } else { unset($drawing_type); }	
	
	
	

$sql = "SELECT * FROM intranet_drawings, intranet_drawings_scale, intranet_drawings_paper, intranet_projects WHERE proj_id = '$proj_id' AND drawing_project = '$_GET[proj_id]' AND drawing_scale = scale_id AND drawing_paper = paper_id " . $sql_drawing_packages . " $drawing_class $drawing_type ORDER BY drawing_number";
$result = mysql_query($sql, $conn) or die(mysql_error());
		

		
		echo "<h2>Drawings to Issue</h2>";


		if (mysql_num_rows($result) > 0) {
		
		echo "<form action=\"index2.php?page=drawings_list&amp;proj_id=$_GET[proj_id]\" method=\"post\">";

		echo "<table summary=\"Lists all of the drawings for the project\">";
		echo "<tr><td><strong>Drawing Number</strong></td><td><strong>Title</strong></td><td><strong>Rev.</strong></td><td><strong>Issue</strong></td></tr>";
		
		$counter = 0;

		while ($array = mysql_fetch_array($result)) {
		$drawing_id = $array['drawing_id'];
		$drawing_number = $array['drawing_number'];
		$scale_desc = $array['scale_desc'];
		$paper_size = $array['paper_size'];
		$drawing_title = $array['drawing_title'];
		$drawing_author = $array['drawing_author'];

		echo "<tr><td><a href=\"index2.php?page=drawings_detailed&amp;drawing_id=$drawing_id\">$drawing_number</a>";

						echo "</td><td>".nl2br($drawing_title)."</td><td>\n";
						
						$sql_2 = "SELECT * FROM intranet_drawings_revision WHERE revision_drawing = '$drawing_id' order by revision_letter DESC";
						$result_2 = mysql_query($sql_2, $conn) or die(mysql_error());
						if (mysql_num_rows($result_2) > 0) {
						echo "<select name=\"revision_id[$counter]\">";
						while ($array_2 = mysql_fetch_array($result_2)) {
							$revision_id = $array_2['revision_id'];
							$revision_letter = $array_2['revision_letter'];
							$revision_date = $array_2['revision_date'];
								echo "<option value=\"$revision_id\">$revision_letter - ".TimeFormat($revision_date)."</option>";
						}
						echo "<option value=\"\">- No Revision -</option>\n";
						echo "</select>";
						} else {
							echo "- No Revision -";
						}
						
		
		
		echo "<td><input type=\"hidden\" value=\"$drawing_id\" name=\"drawing_id[$counter]\" /><input type=\"checkbox\" value=\"yes\" name=\"drawing_issued[$counter]\" /></td>";


		echo "</tr>\n";
		
		$counter++;

		}

		echo "</table>";
		
		
// Drawing issued to
		
		echo "<h2>Issued To</h2>";
		
$sql_issued_to = "
SELECT * FROM contacts_disciplinelist, contacts_contactlist, intranet_contacts_project
LEFT JOIN contacts_companylist ON company_id = contact_proj_company
WHERE contact_proj_contact = contact_id
AND contact_proj_project = $proj_id
AND contact_proj_role = discipline_id
AND contact_proj_contact = contacts_contactlist.contact_id
ORDER BY discipline_order, contact_namesecond";
$result_issued_to = mysql_query($sql_issued_to, $conn) or die(mysql_error());
	
	echo "<table summary=\"Lists the contacts related to this project\">";
	
	$count = 0;
	
	while ($array_issued_to = mysql_fetch_array($result_issued_to)) {	
		
	$contact_id = $array_issued_to['contact_id'];
	$contact_namefirst = $array_issued_to['contact_namefirst'];
	$contact_namesecond = $array_issued_to['contact_namesecond'];
	$company_name = $array_issued_to['company_name'];
	$company_id = $array_issued_to['company_id'];
	$discipline_name = $array_issued_to['discipline_name'];
	
		echo "<tr><td><input type=\"checkbox\" name=\"issue_to[$count]\" value=\"yes\" /><input type=\"hidden\" name=\"contact_id[$count]\" value=\"$contact_id\" /></td><td>$contact_namefirst $contact_namesecond</td><td>$company_name<input type=\"hidden\" name=\"company_id[$count]\" value=\"$company_id\" /></td><td>$discipline_name</td></tr>\n";
		
		$count++;
	
	}
	
	echo "</table>";
		
		
		
		
// Drawing issue details
		
		
		echo "<fieldset><legend>Issue Details</legend>";
		$issue_reason_list = array("Draft","Comment","Planning","Building Control","Information","Tender","Contract","Preliminary","Coordination","Construction","Client Issue","Final Design","As Instructed");
		echo "<p>Reason for Issue<br /><select name=\"issue_reason\">";
		$count = 0;
		$total = count($issue_reason_list);
		while ($count < $total) {		
			echo "<option value=\"$issue_reason_list[$count]\">$issue_reason_list[$count]</option>";
			$count++;
		}
		echo "</select></fieldset>";
		
		echo "<fieldset><legend>Issue Method</legend>";
		
		$issue_method_list = array("Email","CD", "Post", "Basecamp", "Woobius", "Planning Portal", "Google Drive","Dropbox","FTP","4Projects");
		sort($issue_method_list);
		
		$issue_format_list = array("PDF", "DGN", "DWG", "DXF", "Hard Copy","RVT");
		sort($issue_method_list);
		
		if (count($issue_method_list) > count($issue_format_list)) { $total = count($issue_method_list); } else { $total = count($issue_format_list); }
		
		$count = 0;
		
		echo "<table style=\"width: 50%;\"><tr><th colspan=\"2\">Issue Method</th><th colspan=\"2\">Issue Format</th></tr>";
		$total = count($issue_method_list);
		while ($count < $total) {		
			echo "<tr><td style=\"width: 20px; text-align: center\">";
			
			if (count($issue_method_list) > $count) {
				echo "<input type=\"radio\" name=\"issue_method\" id=\"$issue_method_list[$count]\" value=\"$issue_method_list[$count]\" required=\"required\" />";
			}
			
			echo "<td>$issue_method_list[$count]</td><td style=\"width: 20px; text-align: center\">";
			
			if (count($issue_format_list) > $count) {
				echo "<input type=\"radio\" name=\"issue_format\" id=\"$issue_format_list[$count]\" value=\"$issue_format_list[$count]\" required=\"required\" />";
				
			}
			
			echo "<td>$issue_format_list[$count]</td></tr>";
			
			

			$count++;
		}
		
		echo "</table></fieldset>";
		
		// Add dropdown to select user that is checking drawings (excludes the current user)
		
		echo "<fieldset><legend>Checked By</legend>";
		
		
		
		$sql_checked = "SELECT user_name_first, user_name_second, user_id FROM intranet_user_details WHERE user_id != $_COOKIE[user] AND user_active = 1 ORDER BY user_name_second";

		$result_checked = mysql_query($sql_checked, $conn) or die(mysql_error());
		
		echo "<select name=\"set_checked\">";
		
		echo "<option value=\"\">-- None --</option>";
		
		while ($array_checked = mysql_fetch_array($result_checked)) {
		

			echo "<option value=\"" . $array_checked['user_id'] . "\"/>" . $array_checked['user_name_first'] . "&nbsp;" . $array_checked['user_name_second'] . "</option>";
		
		
		}
		
		echo "</select></fieldset>";

		
		echo "<fieldset><legend>Comment</legend><textarea name=\"issue_comment\" cols=\"36\" rows=\"6\"></textarea></fieldset>";
		
		echo "<fieldset><legend>Issue Date</legend>";
		
		$issue_date_value = date("Y",time()) . "-" . date("m",time()) . "-" . date("d",time());
		
		echo "<input type=\"date\" value=\"$issue_date_value\" name=\"set_date\" /></fieldset>";
		
	
		echo "<p><input type=\"submit\" value=\"Issue Drawings\" /></p>";
		
		
		echo "<input type=\"hidden\" name=\"action\" value=\"drawing_issue\" /><input type=\"hidden\" name=\"issue_date\" value=\"".time()."\" /><input type=\"hidden\" value=\"$proj_id\" name=\"issue_project\" />";
		
		echo "</form>";

		} else {

		echo "<p>There are no drawings for this project.</p>";

		}
	
} else {

echo "<p>No project selected.</p>";

}


		
?>