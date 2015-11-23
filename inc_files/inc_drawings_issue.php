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
		
		
		echo "<h2>Issue Details</h2>";
		$issue_reason_list = array("Comment","Preliminary","Information","Planning","Building Control","Tender","Coordination","Contract","Construction","Client Issue","Final Design","As Instructed");
		echo "<p>Reason for Issue<br /><select name=\"issue_reason\">";
		$count = 0;
		$total = count($issue_reason_list);
		while ($count < $total) {		
			echo "<option value=\"$issue_reason_list[$count]\">$issue_reason_list[$count]</option>";
			$count++;
		}
		echo "</select></p>";
		
		// Javascript to limit the type of drawing issues
		
		$issue_method_list = array("Email","CD", "Post", "Basecamp", "Woobius", "Planning Portal");
		sort($issue_method_list);
		
		$issue_format_list = array("PDF", "DGN", "DWG", "DXF", "Hard Copy","RVT");
		sort($issue_method_list);
		
			echo "<script type=\"text/javascript\"> 
			function disablefield(){ 
				if (document.getElementById('issue_method').checked == 'Hard Copy'){
						document.getElementById('Hard Copy').disabled='';
						document.getElementById('PDF').disabled='disabled';
						document.getElementById('DGN').disabled='disabled';
						document.getElementById('DWG').disabled='disabled';
						document.getElementById('DXF').disabled='disabled';
					} else{
						document.getElementById('PDF').disabled='';
						document.getElementById('DGN').disabled='';
						document.getElementById('DWG').disabled='';
						document.getElementById('DXF').disabled='';
						document.getElementById('Hard Copy').disabled='disabled'; 
				} 
			} 
		</script>";
		
		echo "<div>";
		
		
		echo "<p style=\"float: left; margin-right: 20px;\">Issue Method<br />";
		$count = 0;
		$total = count($issue_method_list);
		while ($count < $total) {		
			echo "<input type=\"radio\" name=\"issue_method\" id=\"$issue_method_list[$count]\" value=\"$issue_method_list[$count]\"";
		if ($count == "2") { echo " checked=\"checked\""; }
		echo " onChange=\"disablefield();\" />&nbsp;$issue_method_list[$count]<br />";
			$count++;
		}
		echo "</p>";
		
		echo "<p>Issue Format<br />";
		$count = 0;
		$total = count($issue_method_list);
		while ($count < $total) {		
			echo "<input type=\"radio\" name=\"issue_format\" id=\"$issue_format_list[$count]\" value=\"$issue_format_list[$count]\"";
		if ($count == "0") { echo " checked=\"checked\""; }
		echo " />&nbsp;$issue_format_list[$count]<br />";
			$count++;
		}
		echo "</p>";
		
		echo "</div>";
		
		echo "<p>Comment<br /><textarea name=\"issue_comment\" cols=\"36\" rows=\"6\"></textarea></p>";
		
		if ($issue_date != NULL) { $issue_date_day = date("j", $issue_date); } else { $issue_date_day = date("j", time()); }
		if ($issue_date != NULL) { $issue_date_month = date("n", $issue_date); } else { $issue_date_month = date("n", time()); }
		if ($issue_date != NULL) { $issue_date_year = date("Y", $issue_date); } else { $issue_date_year = date("Y", time()); }
		
		echo "<p><input type=\"hidden\" name=\"action\" value=\"drawing_issue\" /><input type=\"hidden\" name=\"issue_date\" value=\"".time()."\" /><input type=\"hidden\" value=\"$proj_id\" name=\"issue_project\" /><p>Issue Date<br />&nbsp;Day:&nbsp;<input type=\"text\" value=\"$issue_date_day\" name=\"issue_date_day\" size=\"4\" />&nbsp;Month:&nbsp;<input type=\"text\" value=\"$issue_date_month\" name=\"issue_date_month\" size=\"4\" />&nbsp;Year:&nbsp;<input type=\"text\" value=\"$issue_date_year\" name=\"issue_date_year\"  size=\"4\" /></p><p><input type=\"submit\" value=\"Issue Drawings\" /></p>";
		
		echo "</form>";

		} else {

		echo "<p>There are no drawings for this project.</p>";

		}
	
} else {

echo "<p>No project selected.</p>";

}


		
?>