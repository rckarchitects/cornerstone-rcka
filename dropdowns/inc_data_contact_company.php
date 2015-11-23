<?php

	$sql = "SELECT company_name, company_id FROM contacts_companylist WHERE company_id = '$id' LIMIT 1";
	$result_data = mysql_query($sql, $conn) or die(mysql_error());

		$array_data = mysql_fetch_array($result_data);

		$company_id = $array_data['company_id'];
		$company_name = $array_data['company_name'];
	
	print "<a href=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">$company_name</a>";

?>
