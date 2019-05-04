<?php

echo "<h1>Tenders</h1>";

if (!$tender_id && $_GET[tender_id])  { $tender_id = intval($_GET[tender_id]); }


function Tender_Form($tender_id) {

	echo "<form action=\"index2.php?page=tender_list\" method=\"post\">";
	
	if ($tender_id > 0) {
	
		global $conn;
		$sql = "SELECT * FROM intranet_tender WHERE tender_id = $tender_id LIMIT 1";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$array = mysql_fetch_array($result);
		$tender_id = $array['tender_id'];
		$tender_name = $array['tender_name'];
		$tender_client = $array['tender_client'];
		$tender_date = $array['tender_date'];
		$tender_type = $array['tender_type'];
		$tender_procedure = $array['tender_procedure'];
		$tender_description = $array['tender_description'];
		$tender_keywords = $array['tender_keywords'];
		$tender_source = $array['tender_source'];
		$tender_instructions = $array['tender_instructions'];
		$tender_result = $array['tender_result'];
		$tender_submitted = $array['tender_submitted'];
		$tender_notes = $array['tender_notes'];
		$tender_responsible = $array['tender_responsible'];
		
		echo "<h2>" . $tender_name . "</h2>";
		
	} else { echo "<h2>Add Tender</h2>"; }
		
		if (!$tender_date) { $tender_date = time(); }
		
		$tender_date_day = CreateDateFromTimestamp($tender_date);
		$tender_date_time = CreateTimeFromTimestamp($tender_date);
	
		if (!$tender_responsible) { $tender_responsible = intval($_COOKIE[user]); }
	
	$tender_type_array = array("","Invitation to Tender","Pre-Qualification Questionnaire","Expression of Interest","Design Competition");
	sort($tender_type_array);
	
	$tender_procedure_array = array("","Open Procedure","Restricted Procedure (Two-Stage)","Invited Procedure","Negotiated Procedure");
	sort($tender_procedure_array);
	
	echo "<div><p>Name of Tender</p><p><input type=\"text\" value=\"$tender_name\" name=\"tender_name\" maxlength=\"500\" size=\"" . strlen($tender_name) . "\" required=\"required\" class=\"mainform\" /></p></div>";
	
	echo "<div><p>Name of Client</p><p><input type=\"text\" value=\"$tender_client\" name=\"tender_client\" maxlength=\"100\" size=\"" . strlen($tender_client) . "\" required=\"required\" class=\"mainform\" list=\"tender_client\" /></p></div>";
	
	DataList('tender_client','intranet_tender');
	
	echo "<div><p>Submission Time &amp; Date</p><p><input type=\"time\" name=\"tender_date_time\" value=\"$tender_date_time\" />&nbsp;<input type=\"date\" value=\"$tender_date_day\" name=\"tender_date_day\" required=\"required\" /></p></div>";
	
	echo "<div><p>Tender Type</p><p><select name=\"tender_type\" class=\"mainform\" />";
	
	foreach ($tender_type_array AS $tender_type_list) {
		if ($tender_type_list == $tender_type) { $select = "selected=\"selected\""; } else { unset($select); }
			echo "<option value=\"$tender_type_list\" $select>$tender_type_list</option>";
	}
	
	echo "</select></p></div>";
	
	echo "<div><p>Tender Procedure</p><p><select name=\"tender_procedure\" class=\"mainform\" />";
	
	foreach ($tender_procedure_array AS $tender_procedure_list) {
		if ($tender_procedure_list == $tender_procedure) { $select = "selected=\"selected\""; } else { unset($select); }
			echo "<option value=\"$tender_procedure_list\" $select>$tender_procedure_list</option>";
	}
	
	echo "</select></p></div>";
	
	echo "<div><p>Tender Description</p><p><textarea name=\"tender_description\" class=\"mainform\"/>$tender_description</textarea></p></div>";
	
	echo "<div><p>Linked to</p><p>";
		TenderSelect($array['tender_linked']);
	echo "</p></div>";
	
	echo "<div><p>Tender Source</p><p><input type=\"text\" value=\"$tender_source\" name=\"tender_source\" maxlength=\"500\" size=\"" . strlen($tender_source) . "\" class=\"mainform\" /></p></div>";
	
	echo "<div><p>Tender Instructions</p><p><textarea name=\"tender_instructions\" class=\"mainform\"/>$tender_instructions</textarea></p></div>";
	
	echo "<div><p>Notes</p><p><textarea name=\"tender_notes\" class=\"mainform\"/>$tender_notes</textarea></p></div>";
	
	if ($tender_result == 0) { $select0 = "checked=\"checked\""; } else { unset($select0); }
	if ($tender_result == 1) { $select1 = "checked=\"checked\""; } else { unset($select1); } 
	if ($tender_result == 2) { $select2 = "checked=\"checked\""; } else { unset($select2); }
	if ($tender_result == 3) { $select3 = "checked=\"checked\""; } else { unset($select3); } 
	
	echo "	<div><p>
			<input type=\"radio\" name=\"tender_result\" value=\"0\" $select0 />&nbsp;Awaiting Result&nbsp;
			<input type=\"radio\" name=\"tender_result\" value=\"2\" $select2 />&nbsp;Stage Unsuccessful&nbsp;
			<input type=\"radio\" name=\"tender_result\" value=\"1\" $select1 />&nbsp;Stage Successful&nbsp;
			<input type=\"radio\" name=\"tender_result\" value=\"3\" $select3 />&nbsp;Stage Declined&nbsp;
			</p></div>";
			
	echo "	<div><p>";
			UserDropdown($tender_responsible,"tender_responsible");
	echo "	</p></div>";
	
	if ($tender_submitted == 1) { $select = "checked=\"checked\""; } else { unset($select); } 
	echo "<div><p><input type=\"checkbox\" name=\"tender_submitted\" value=\"1\" $select />&nbsp;Tender Submitted</p></div>";
	
	echo "<div><p><input type=\"hidden\" value=\"tender_edit\" name=\"action\" /><input type=\"hidden\" value=\"" . $_COOKIE[user] . "\" name=\"tender_added_by\" /><input type=\"hidden\" value=\"$tender_id\" name=\"tender_id\" /><input type=\"Submit\" /></p></div>";
	
	echo "</form>";

}


function Tender_Edit($tender_id) {
	
	global $conn;
	
	echo "<h2>Edit Tender: </h2>";

	Tender_Form($tender_id);


}


function Tender_Add() {

	echo "<h2>Add Tender</h2>";

	
	Tender_Form($tender_id);


}




if ($tender_id > 0) {
	Tender_Form($tender_id);
} else {
	Tender_Form($tender_id);
}

function TenderSelect($tender_linked) {
	
	global $conn;
	
	$sql = "SELECT * FROM intranet_tender WHERE tender_submitted = 1 ORDER BY tender_date DESC";
	
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	if (mysql_num_rows($result) > 0) {
		
		echo "<select name=\"tender_linked\">";
		
		echo "<option value=\"\">- None -</option>";
	
			while ($array = mysql_fetch_array($result)) {
				
				if ($tender_linked == $array['tender_id']) { $selected = "selected=\"selected\""; } else { unset ($selected); }
			
				echo "<option value=\"". $array['tender_id'] . "\" " . $selected . ">" . $array['tender_name'] . " (" . $array['tender_client'] .")</option>";
			
			}
			
		echo "</select>";
	
	}
	
}