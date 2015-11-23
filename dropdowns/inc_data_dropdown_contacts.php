<?php

	$sql = "SELECT contact_id, contact_namefirst, contact_namesecond, contact_company FROM contacts_contactlist ORDER BY contact_namesecond";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	print "<select class=\"inputbox\" name=\"$data_contact_var\">";

	print "<option value=\"\">-- None --</option>";

	while ($array = mysql_fetch_array($result)) {

		$contact_id = $array['contact_id'];
		$contact_namefirst = $array['contact_namefirst'];
		$contact_namesecond = $array['contact_namesecond'];
		$contact_company = $array['contact_company'];

		if ($contact_company > 0) {
            $sql2 = "SELECT company_name, company_postcode FROM contacts_companylist WHERE company_id = $contact_company LIMIT 1";
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
            if ($contact_id == $data_contact_id) { print " selected"; }
            print ">".$contact_namesecond.", ".$contact_namefirst.$print_company_details."</option>";



	}

	print "</select>";

?>
