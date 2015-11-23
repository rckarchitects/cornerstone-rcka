<?php

	$sql = "SELECT contact_id, contact_namefirst, contact_namesecond, contact_company FROM contacts_contactlist WHERE contact_id = '$data_contact' LIMIT 1";
	$result_data = mysql_query($sql, $conn) or die(mysql_error());

		$array_data = mysql_fetch_array($result_data);

		$contact_id = $array_data['contact_id'];
		$contact_namefirst = $array_data['contact_namefirst'];
		$contact_namesecond = $array_data['contact_namesecond'];
		$contact_company = $array_data['contact_company'];

		if ($contact_company > 0) {
            $sql2_data = "SELECT company_name FROM contacts_companylist WHERE company_id = $contact_company LIMIT 1";
            $result2_data = mysql_query($sql2_data, $conn) or die(mysql_error());
            $array2_data = mysql_fetch_array($result2_data);
            $company_name = $array2_data['company_name'];
			
            } else {

            unset($print_company_details);

            }
	
	print "<a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">$contact_namefirst&nbsp;$contact_namesecond</a>";

?>
