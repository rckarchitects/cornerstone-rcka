<?php

	$sql = "SELECT * FROM contacts_contactlist, contacts_relationlist WHERE contacts_contactlist.contact_relation = contacts_relationlist.relation_id AND contacts_relationlist.relation_id = 2 AND contact_namesecond != NULL AND contact_namefirst != NULL ORDER BY contact_namesecond";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	print "<select class=\"inputbox\" name=\"proj_client_contact_id\">";

	print "<option value=\"\">-- None --</option>";

	while ($array = mysql_fetch_array($result)) {

		$contact_id = $array['contact_id'];
		$contact_namefirst = $array['contact_namefirst'];
		$contact_namesecond = $array['contact_namesecond'];
		$contact_company = $array['contact_company'];

		if ($contact_company > 0) {
            $sql2 = "SELECT * FROM contacts_companylist WHERE company_id = $contact_company LIMIT 1";
            $result2 = mysql_query($sql2, $conn) or die(mysql_error());
            $array2 = mysql_fetch_array($result2);
            $company_name = $array2['company_name'];
            $company_postcode = $array2['company_postcode'];
            
            if ($company_postcode != NULL) {
              $print_company_details = " [".$company_name.", ".$company_postcode."]";
              } else {
              $print_company_details = " [".$company_name."]";
              }

            

            } else {

            unset($print_company_details);

            }

            print "<option value=\"$contact_id\"";
            if ($contact_id == $proj_client_contact_id) { print " selected"; }
            print ">".$contact_namesecond.", ".$contact_namefirst.$print_company_details."</option>";



	}

	print "</select>";

?>
