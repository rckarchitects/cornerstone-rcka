<?php

function RiskList($proj_id) {
	
	global $conn;
	global $pref_practice;
	
	$sql = "SELECT * FROM intranet_project_risks WHERE risk_project = " . intval($proj_id) . " ORDER BY risk_drawing DESC, risk_category, risk_id";

	$result = mysql_query($sql, $conn);
	$counter_1 = 0;
	$counter_2 = 1;
	
	if (mysql_num_rows($result) > 0) {
		
			echo "<div class=\"page\"><table>";
			
			echo "<tr><th rowspan=\"2\">Number</th><th rowspan=\"2\">Description</th><th rowspan=\"2\">Level</th><th rowspan=\"2\">Score</th><th rowspan=\"2\" style=\"padding-left: 6px;\">Analysis</th><th rowspan=\"2\">Warning Signs</th><th colspan=\"4\" style=\"text-align: center;\">Risk Resolution Strategy</th><th rowspan=\"2\">Responsibility</th><th colspan=\"2\" style=\"text-align: right;\" rowspan=\"2\">Date Identified</th></tr>";
			echo "<tr><th>Mitigation</th><th style=\"text-align: center;\">Transfer</th><th style=\"text-align: center;\">Eliminate</th><th style=\"text-align: center;\">Accept</th></tr>";
			
			$current_category = NULL;
			
			$current_drawing = NULL;
			
			while ($array = mysql_fetch_array($result)) {
				
				$company_name = RiskGetCompany($array['risk_responsibility']);
				
				if (!$company_name) { $company_name = $pref_practice; }
				
			if ($current_drawing != $array['risk_drawing']) { echo "</table><h2>" . $array['risk_drawing'] . "</h2><table>"; $counter_1 = 0; $counter_2 = 0; $current_drawing = $array['risk_drawing']; }
				
			if ($current_category != $array['risk_category']) { $current_category = $array['risk_category']; $counter_1++;  echo "<tr><td colspan=\"13\"><strong>" . $counter_1 . ".0 " . $array['risk_category'] . "</strong></td></tr>";  $counter_2 = 1; } else { $counter_2++; }
			
			if ($array['risk_level'] == "green") { $background = " style=\"background: green; min-width: 25px;\""; }
			elseif ($array['risk_level'] == "amber") { $background = " style=\"background: orange; min-width: 25px;\""; }
			elseif ($array['risk_level'] == "red") { $background = " style=\"background: red; min-width: 25px;\""; }
			
			if ($array['risk_score'] == "low") { $background2 = " style=\"background: rgb(200,200,200); min-width: 25px;\""; }
			elseif ($array['risk_score'] == "medium") { $background2 = " style=\"background: rgb(100,100,100); min-width: 25px;\""; }
			elseif ($array['risk_score'] == "high") { $background2 = " style=\"background: rgb(50,50,50); min-width: 25px;\""; }
			
			if ($array['risk_management'] == "transfer") { $management_1 = "X"; unset($management_2); unset($management_3); }
			elseif ($array['risk_management'] == "eliminate") { $management_2 = "X"; unset($management_3); unset($management_1); }
			elseif ($array['risk_management'] == "accept") { $management_3 = "X"; unset($management_1); unset($management_2); }
			
			if (intval($array['risk_resolved']) == 1) { $style = "style=\"text-decoration: line-through;\""; } else { unset($style); }
				
				echo "<tr " . $style . "><td rowspan=\"2\">" . $counter_1 . "." . $counter_2 . "</td><td><strong>" . ucwords($array['risk_title']) . "</strong></td><td " . $background . "><td " . $background2 . "></td></td><td style=\"padding-left: 6px;\" rowspan=\"2\">" . $array['risk_analysis'] . "</td><td colspan=\"2\"></td><td style=\"text-align: center;\">" . $management_1 . "</td><td style=\"text-align: center;\">" . $management_2 . "</td><td style=\"text-align: center;\">" . $management_3 . "</td><td>" . $company_name . "</td><td style=\"text-align: right;\">" . $array['risk_date'] . "</td></tr>";
				
				echo "<tr " . $style . "><td colspan=\"3\">" . nl2br($array['risk_description']) . "&nbsp; <a href=\"index2.php?page=project_risks&amp;risk_id=" . $array['risk_id'] . "\" /><img src=\"images/button_edit.png\" alt=\"Edit\"></a></p></td><td colspan=\"4\">" . $array['risk_warnings'] . "</td><td colspan=\"3\">" . $array['risk_mitigation'] . "</td></tr>";
				
			}
			echo "</table></div>";
	
	}
	
}

function RiskGetCompany($company_id) {
	
	global $conn;
	$company_id = intval($company_id);
	
	if ($company_id > 0) {
		
		$sql = "SELECT company_name FROM contacts_companylist WHERE company_id = " . $company_id;
		
		$result = mysql_query($sql, $conn);
		$array = mysql_fetch_array($result);
		$company_name = $array['company_name'];
		
		return $company_name;
		
	}
	
}

function RiskResponsbilitySelect($proj_id,$risk_responsibility) {
	
	global $conn;
	global $pref_practice;
	$sql = "SELECT DISTINCT company_name, company_id FROM intranet_contacts_project, contacts_companylist WHERE contact_proj_project = " . $proj_id . " AND company_id = contact_proj_company ORDER BY company_name";
	$result = mysql_query($sql, $conn);
	echo "<select name=\"risk_responsibility\">";
	echo "<option value=\"0\">" . $pref_practice . "</option>";
	while ($array = mysql_fetch_array($result)) {

		echo "<option value=\"" . $array['company_id'] . "\" ";
		
		if ($risk_responsibility == $array['company_id']) { echo " selected=\"selected\"" ; }
		
		echo ">" . $array['company_name'] . "</option>";
	}
	echo "</select>";
	
}

function RiskEdit($risk_id,$proj_id) {
	
	global $conn;
	$risk_id = intval($risk_id);
	
	if ($risk_id > 0) {
		
		$sql = "SELECT * FROM intranet_project_risks WHERE risk_id= " . $risk_id . " ORDER BY risk_category, risk_date";
		$result = mysql_query($sql, $conn);
		$array = mysql_fetch_array($result);
		
	}
	
	if (!$array['risk_date']) { $risk_date = DisplayDay(time()); } else { $risk_date = $array['risk_date']; }
	
	if ($array['risk_management'] == "transfer") { $risk_management_transfer = " checked=\"checked\""; }
	elseif ($array['risk_management'] == "eliminate") { $risk_management_eliminate = " checked=\"checked\""; }
	elseif ($array['risk_management'] == "accept") { $risk_management_accept = " checked=\"checked\""; }
	
	if ($array['risk_level'] == "green") { $risk_level_green = " checked=\"checked\""; }
	elseif ($array['risk_level'] == "amber") { $risk_level_amber = " checked=\"checked\""; }
	elseif ($array['risk_level'] == "red") { $risk_level_red = " checked=\"checked\""; }
	
	if ($array['risk_score'] == "low") { $risk_score_low = " checked=\"checked\""; }
	elseif ($array['risk_score'] == "medium") { $risk_score_medium = " checked=\"checked\""; }
	elseif ($array['risk_score'] == "high") { $risk_score_high = " checked=\"checked\""; }
	
	echo "<div>
			<div>
				<div><h3>Add New Risk</h3>
				<form action=\"index2.php?page=project_risks&amp;proj_id=" . $proj_id . "\" method=\"post\">
				<div class=\"float\"><h4>Risk Category</h4><p><input type=\"text\" list=\"risk_category\" name=\"risk_category\" value=\"" . $array['risk_category'] . "\" class=\"inputbox\" required=\"required\" maxlength=\"50\" /></p></div>";
	
	DataList("risk_category","intranet_project_risks");
	
	echo "	
				<div class=\"float\"><h4>Risk Title</h4><p><input type=\"text\" name=\"risk_title\" maxlength=\"75\" value=\"" . $array['risk_title'] . "\" required=\"required\" /><br /><span class=\"minitext\">Enter a brief title for this risk</span></p></div>
				<div class=\"float\"><h4>Description of risk</h4><p><textarea name=\"risk_description\" class=\"inputbox\" style=\"width: 75%; height: 150px;\">" . $array['risk_description'] . "</textarea><br /><span class=\"minitext\">Provide a brief description of the risk</span></p></div>
			";
			
	echo "		<div class=\"float\"><h4>Resolved</h4>";
	
				if ($array['risk_resolved'] == 1) { $resolved = "checked=\"checked\""; } else { unset($resolved); }
	
	echo		"<input type=\"checkbox\" name=\"risk_resolved\" value=\"1\" " . $resolved . " />&nbsp;Risk Resolved";
	
	echo "		</div>
	
				</div>";
			
	echo
			"<div>
				<div class=\"float\"><h4>Risk Level</h4>
					<p><span class=\"minitext\">Rate the potential impact of this risk should it occur</span></p>
					<p><span style=\"padding: 6px; margin: 8px; background: green; display: block;\"><input type=\"radio\" value=\"green\" name=\"risk_level\" " . $risk_level_green . " class=\"inputbox\" required=\"required\" />&nbsp;Green</span></p>
					<p><span style=\"padding: 6px; margin: 8px; background: orange;  display: block;\"><input type=\"radio\" value=\"amber\" name=\"risk_level\" " . $risk_level_amber . " class=\"inputbox\" required=\"required\" />&nbsp;Amber</span></p>
					<p><span style=\"padding: 6px; margin: 8px; background: red; display: block;\"><input type=\"radio\" value=\"red\" name=\"risk_level\" " . $risk_level_red . " class=\"inputbox\" required=\"required\" />&nbsp;Red</span></p>
				</div>
				<div class=\"float\"><h4>Risk Score</h4><p><span class=\"minitext\">Rate the likelihood of this risk occurring</span></p>
					<p><span style=\"padding: 6px; margin: 8px; background: rgba(0,0,0,0.1); display: block;\"><input type=\"radio\" value=\"low\" name=\"risk_score\" " . $risk_score_low . " class=\"inputbox\" required=\"required\" />&nbsp;Low</span></p>
					<p><span style=\"padding: 6px; margin: 8px; background: rgba(0,0,0,0.3); display: block;\"><input type=\"radio\" value=\"medium\" name=\"risk_score\" " . $risk_score_medium . " class=\"inputbox\" required=\"required\" />&nbsp;Medium</span></p>
					<p><span style=\"padding: 6px; margin: 8px; background: rgba(0,0,0,0.5); display: block;\"><input type=\"radio\" value=\"high\" name=\"risk_score\" " . $risk_score_high . " class=\"inputbox\" required=\"required\" />&nbsp;High</span></p>
				</div>
				<div class=\"float\"><h4>Risk Analysis</h4><p><textarea name=\"risk_analysis\"  class=\"inputbox\" style=\"width: 75%; height: 150px;\">" . $array['risk_analysis'] . "</textarea><br /><span class=\"minitext\">Provide a brief analysis of the risk</span></p></div>
			</div>
			<div>			
				<div class=\"float\"><h4>Risk Warning</h4><p><textarea name=\"risk_warnings\" class=\"inputbox\" style=\"width: 75%; height: 150px;\">" . $array['risk_warnings'] . "</textarea><br /><span class=\"minitext\">Describe any warning signs which may indicate this risk is about to occur</span></p></div>
				<div class=\"float\"><h4>Mitigation Measures</h4><p><textarea name=\"risk_mitigation\" class=\"inputbox\" style=\"width: 75%; height: 150px;\">" . $array['risk_mitigation'] . "</textarea><br /><span class=\"minitext\">Describe any measures might be taken to mitigate the likelihood of this risk occurring</span></p></div>
				<div class=\"float\"><h4>Management Strategy</h4><p><span class=\"minitext\">Choose how this risk should be dealt with</span></p>
					<p><span style=\"padding: 6px; margin: 8px; background: rgba(0,0,0,0.1); display: block;\"><input type=\"radio\" value=\"transfer\" name=\"risk_management\" " . $risk_management_transfer . " class=\"inputbox\" />&nbsp;Transfer</span></p>
					<p><span style=\"padding: 6px; margin: 8px; background: rgba(0,0,0,0.3); display: block;\"><input type=\"radio\" value=\"eliminate\" name=\"risk_management\" " . $risk_management_eliminate . " class=\"inputbox\" />&nbsp;Eliminate</span></p>
					<p><span style=\"padding: 6px; margin: 8px; background: rgba(0,0,0,0.5); display: block;\"><input type=\"radio\" value=\"accept\" name=\"risk_management\" " . $risk_management_accept . " class=\"inputbox\" />&nbsp;Accept</span></p>
				</div>
			</div>";
	
	if ($array['risk_drawing']) { $risk_drawing = $array['risk_drawing']; } else { $risk_drawing = GetRiskDrawing($array['risk_project']); }
	
	
	echo "	<div>
				<div class=\"float\"><h4>Document Number</h4><p><input type=\"text\" name=\"risk_drawing\" list=\"risk_drawing\" maxlength=\"200\" value=\"" . $risk_drawing . "\" /><br /><span class=\"minitext\">Enter the document reference number</span></p>";
	
				DataList("risk_drawing","intranet_project_risks",$risk_project,$risk_project);
				
				echo "<p><input type=\"checkbox\" name=\"update_drawing\" value=\"1\" checked=\checked\"/>&nbsp;Update all documents with this reference?</p>";
	
	echo "		</div>";
	
	echo "		<div class=\"float\"><h4>Responsibility</h4>";
	
				RiskResponsbilitySelect($proj_id,$array['risk_responsibility']);
	
	echo "		</div>";
	
	echo "		<div class=\"float\"><h4>Date Identified</h4><p><input type=\"date\" name=\"risk_date\" value=\"" . $risk_date . "\" class=\"inputbox\" /></p></div>
			</div>
			<div>
				<div><input type=\"submit\" /></div>
					<input type=\"hidden\" name=\"risk_id\" value=\"" . $risk_id . "\" />
					<input type=\"hidden\" name=\"risk_project\" value=\"" . $proj_id . "\" />
					<input type=\"hidden\" name=\"proj_id\" value=\"" . $proj_id . "\" />
					<input type=\"hidden\" name=\"current_drawing\" value=\"" . urlencode($risk_drawing) . "\" />
					<input type=\"hidden\" name=\"action\" value=\"risk_edit\" />
					</form>
				</div>
			</div>";
	
}

function GetRiskDrawing($risk_project) {
	
	global $conn;
	$risk_project = intval($risk_project);
	$sql = "SELECT risk_drawing FROM intranet_project_risks WHERE risk_project = $risk_project ORDER BY risk_drawing DESC LIMIT 1";
	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);
	return $array['risk_drawing'];
	
}