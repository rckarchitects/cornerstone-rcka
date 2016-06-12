<?php


if ($_GET[proj_id] != NULL OR $_POST[proj_id] != NULL) {
	
	if ($_GET[proj_id] > 0) { $proj_id = intval ( $_GET[proj_id] ); } elseif ($_POST[proj_id] > 0) { $proj_id = intval ( $_POST[proj_id] ); }
	
	if ( $_GET[condition_id] > 0) { $condition_id = intval ( $_GET[condition_id] ) ; }
		

	
	include_once("inc_project_planningcondition_list.php");
	
	if ($_GET[condition_id] > 0) {
		
		echo "<h2>Edit Planning Condition</h2>";

		$condition_id = intval ( $_GET[condition_id] );
		
			$sql_condition = "SELECT * FROM intranet_projects_planning WHERE condition_id = $condition_id LIMIT 1";
			//echo "<p>" . $sql_condition . "</p>";
			$result_condition = mysql_query($sql_condition, $conn) or die(mysql_error());
			if (mysql_num_rows($result_condition) > 0) {
				$array_condition = mysql_fetch_array($result_condition);
				$condition_id = $array_condition[condition_id];
				$condition_project = $array_condition[condition_project];
				$condition_ref = $array_condition[condition_ref];
				$condition_number = $array_condition[condition_number];
				$condition_decision_date_edit = $array_condition[condition_decision_date];
				$condition_type = $array_condition[condition_type];
				$condition_text = $array_condition[condition_text];
				$condition_responsibility = $array_condition[condition_responsibility];
				$condition_added_date = $array_condition[condition_added_date];
				$condition_added_user = $array_condition[condition_added_user];
				$condition_note = $array_condition[condition_note];
				$condition_submitted = $array_condition[condition_submitted];
				$condition_approved = $array_condition[condition_approved];
				$condition_link = $array_condition[condition_link];			
				
			}
	
		
	} else {
		
		echo "<h2>Add New Planning Condition</h2>";
		
	}
	
	

	
	
	
	
	
	if ($condition_decision_date_edit == NULL) { $condition_decision_date_edit = $_POST[condition_decision_date]; }
	if ($condition_ref == NULL) { $condition_ref = addslashes ( $_POST[condition_ref] ); }
	if ($condition_link == NULL) {  $condition_link = addslashes ( $_POST[condition_link] ); }
	if ($condition_type == NULL) {  $condition_type = $_POST[condition_type]; }
	if ($_POST[condition_number] != NULL && $condition_id == NULL) { $condition_number = $_POST[condition_number] + 1; }
	
	echo "<form method=\"post\" action=\"index2.php?page=project_planningcondition_edit&amp;proj_id=$proj_id\">";
	
	echo "<fieldset><legend>Application Details</legend>";
	
	echo "<h3>Planning Application Reference:</h3>";
	
	$sql_ref = "SELECT DISTINCT condition_ref FROM intranet_projects_planning WHERE condition_project = $proj_id ORDER BY condition_ref";
	$result_ref = mysql_query($sql_ref, $conn) or die(mysql_error());
	
	echo "<p><input list=\"condition_ref_list\" name=\"condition_ref\" value=\"$condition_ref\" required>";
	echo "<datalist id=\"condition_ref_list\">";
	
	while ($array_ref = mysql_fetch_array($result_ref)) {
		
		if (addslashes($condition_ref) != $array_ref['condition_ref']) { $selected = " selected=\"selected\" "; }
		else { unset($selected); }
	
		echo "<option value=\"" . $array_ref['condition_ref'] . "\" $selected /></option>";
	
	}
	
	echo "</datalist></input></p>";
	
	echo "<h3>Planning Decision date</h3>";
	
	echo "<p><input type=\"date\" value=\"$condition_decision_date_edit\" name=\"condition_decision_date\" required /></p>";
	
	echo "<h3>Link to Decision Notice</h3>";
	
	echo "<p><input type=\"text\" value=\"$condition_link\" name=\"condition_link\" required maxlength=\"250\" style=\"width: 95%\" /></p>";
	
	echo "</fieldset>";

	echo "<fieldset><legend>Condition Details</legend>";

	echo "<h3>Condition Number</h3>";
	echo "<p><input type=\"number\" value=\"$condition_number\" name=\"condition_number\" /></p>";

	echo "<h3>Condition Description</h3>";
	echo "<p><textarea name=\"condition_text\" required style=\"width:95%;height: 200px;\" />$condition_text</textarea></p>";
	
	echo "<h3>Type of Condition</h3>";
	echo "<p>";
	
		echo "<select name=\"condition_type\">";
		echo "<option value=\"Pre-Commencement (Of Any Part)\""; if ($condition_type == "Pre-Commencement (Of Any Part)") { echo " selected=\"selected\""; } echo ">Pre-Commencement (Of Any Part)</option>";
		echo "<option value=\"Pre-Commencement (Of Affected Part)\""; if ($condition_type == "Pre-Commencement (Of Affected Part)") { echo " selected=\"selected\""; } echo ">Pre-Commencement (Of Affected Part)</option>";
		echo "<option value=\"Pre-Occupation\""; if ($condition_type == "Pre-Occupation") { echo " selected=\"selected\""; } echo ">Pre-Occupation</option>";
		echo "<option value=\"Post-Occupation\""; if ($condition_type == "Post-Occupation") { echo " selected=\"selected\""; } echo ">Post-Occupation</option>";
		echo "<option value=\"Informative Only\""; if ($condition_type == "Informative Only") { echo " selected=\"selected\""; } echo ">Informative Only</option>";
		echo "</select>";
	
	echo "</p>";
	
	echo "<h3>Responsibility</h3>";
	
	echo "<p><select name=\"condition_responsibility\">";
	echo "<option value=\"\">$pref_practice</option>";
	echo "<optgroup label=\"External Organisations\">";
	$sql_responsible = "SELECT DISTINCT company_id, company_name FROM contacts_companylist, intranet_contacts_project WHERE contact_proj_company = company_id AND contact_proj_project = $proj_id ORDER BY company_name";
	$result_responsible = mysql_query($sql_responsible, $conn) or die(mysql_error());
	while ($array_responsible = mysql_fetch_array($result_responsible)) {
		
		if ($condition_responsibility == $array_responsible[company_id]) { $selected = " selected=\"selected\""; } else { unset($selected); }
		
		echo "<option value=\"" . $array_responsible[company_id] . "\" $selected>" . $array_responsible[company_name] . "</option>";
		
	}
	
	echo "</optgroup></select></p>";
	
	
	echo "</fieldset>";

	echo "<fieldset><legend>Comments</legend>";
	echo "<p><textarea name=\"condition_note\" style=\"width:95%;height: 100px;\" />$condition_note</textarea></p>";	
	echo "<input type=\"hidden\" value=\"$_COOKIE[user]\" name=\"condition_added_user\" />";
	echo "<input type=\"hidden\" value=\"" . time() . "\" name=\"condition_added_date\" />";
	echo "<input type=\"hidden\" value=\"planningcondition_edit\" name=\"action\" />";
	echo "<input type=\"hidden\" value=\"$condition_id\" name=\"condition_id\" />";
	echo "<input type=\"hidden\" value=\"$proj_id\" name=\"condition_project\" />";
	echo "</fieldset>";
	
	
	echo "<fieldset><legend>Progress</legend>";
	
	echo "<h3>Condition Submitted Date</h3>";
	
	echo "<p><input type=\"date\" value=\"$condition_submitted\" name=\"condition_submitted\" /></p>";
	
	echo "<h3>Condition Approved Date</h3>";
	
	echo "<p><input type=\"date\" value=\"$condition_approved\" name=\"condition_approved\" /></p>";

	echo "</fieldset>";
	
	echo "<input type=\"submit\" /></form>";

} else {
	
	echo "<h1>Error</h1>";
	echo "<p>No project found</p>";
	
}


?>