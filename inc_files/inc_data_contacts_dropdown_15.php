<?php

	$sql = "SELECT * FROM contacts_contactlist, contacts_companylist, contacts_relationlist WHERE contact_company = company_id AND contact_relation = relation_id AND relation_id = '2' OR contact_company = company_id AND contact_relation = relation_id AND contact_id = '$proj_consult_15' ORDER BY company_name, contact_namesecond";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	print "<select class=inputbox name=proj_tenant_1>";
	
	print "<option value=\"\">-- None --</option>";
	
	while ($array = mysql_fetch_array($result)) {
		
		$contact_id = $array['contact_id'];
		$contact_namefirst = $array['contact_namefirst'];
		$contact_namesecond = $array['contact_namesecond'];
		$company_name = TrimLength($array['company_name'],28);
		$company_postcode = $array['company_postcode'];
		
	print "<option value=\"$contact_id\"";
	
	if ($proj_tenant_1 == $contact_id) { print " selected"; }
	
	print ">".$company_name." (".$contact_namefirst." ".$contact_namesecond.")</option>";
		
		
	}
	
	print "</select>";




?>
