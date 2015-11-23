<?php

	if ($check_disc == 41) { $proj_consult_here = $proj_consult_41; }
	elseif ($check_disc == 42) { $proj_consult_here = $proj_consult_42; }
	elseif ($check_disc == 43) { $proj_consult_here = $proj_consult_43; }
	elseif ($check_disc == 6) { $proj_consult_here = $proj_consult_6; }
	elseif ($check_disc == 7) { $proj_consult_here = $proj_consult_7; }
	elseif ($check_disc == 8) { $proj_consult_here = $proj_consult_8; }
	elseif ($check_disc == 9) { $proj_consult_here = $proj_consult_9; }
	elseif ($check_disc == 10) { $proj_consult_here = $proj_consult_10; }
	elseif ($check_disc == 11) { $proj_consult_here = $proj_consult_11; }
	elseif ($check_disc == 12) { $proj_consult_here = $proj_consult_12; }
	elseif ($check_disc == 13) { $proj_consult_here = $proj_consult_13; }
	elseif ($check_disc == 14) { $proj_consult_here = $proj_consult_14; }
	elseif ($check_disc == 15) { $proj_consult_here = $proj_consult_15; }
	elseif ($check_disc == 16) { $proj_consult_here = $proj_consult_16; }
	elseif ($check_disc == 17) { $proj_consult_here = $proj_consult_17; }
	elseif ($check_disc == 18) { $proj_consult_here = $proj_consult_18; }
	elseif ($check_disc == 19) { $proj_consult_here = $proj_consult_19; }

	$sql = "SELECT * FROM contacts_contactlist, contacts_companylist, contacts_disciplinelist WHERE contact_company = company_id AND contact_discipline = discipline_id AND discipline_id = '$check_consult' OR contact_company = company_id AND contact_discipline = discipline_id AND contact_id = '$proj_consult_here' ORDER BY company_name, contact_namesecond";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	print "<select class=\"inputbox\" name=\"proj_consult_$check_disc\">";
	
	print "<option value=\"\">-- None --</option>";
	
	while ($array = mysql_fetch_array($result)) {
		
		$contact_id = $array['contact_id'];
		$contact_namefirst = $array['contact_namefirst'];
		$contact_namesecond = $array['contact_namesecond'];
		$company_name = TrimLength($array['company_name'],28);
		$company_postcode = $array['company_postcode'];
		
	print "<option value=$contact_id";
	
	
	if ($proj_consult_here == $contact_id) { print " selected"; }
	
	print ">".$company_name." (".$contact_namefirst." ".$contact_namesecond.")</option>";
		
		
	}
	
	print "</select>";




?>
