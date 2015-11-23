<?php


  			$sql = "SELECT * FROM contacts_contactlist, contacts_relationlist WHERE contact_relation = relation_id AND contact_namefirst LIKE '%$_POST[searchstring]%' OR contact_relation = relation_id AND contact_namesecond LIKE '%$_POST[searchstring]%' order by contact_namesecond LIMIT $listbegin, $listmax ";

		$techmessage = $sql;
		

			$result = mysql_query($sql, $conn) or die(mysql_error());
			$result_num = mysql_num_rows($result);
			
			if ($result_num > 0) {
			
				if ($result_num == 1) { print "<p>$result_num result found</p>"; }
				if ($result_num > 1) { print "<p>$result_num results found</p>"; }

            print "<table cellpadding=\"2\" width=\"100%\">";
			
			$replacestring = "<font class=\"hilight\">".$_POST[searchstring]."</font>";

			while ($array = mysql_fetch_array($result)) {
			$contact_id = $array['contact_id'];
			$contact_prefix = $array['contact_prefix'];
			$contact_namefirst = $array['contact_namefirst'];
			$contact_namesecond = $array['contact_namesecond'];
			$contact_company = $array['contact_company'];
			$contact_relation = $array['contact_relation'];
			
			// Select the company if relevant
			
			$sql_company = "SELECT * FROM contacts_companylist WHERE company_id = $contact_company LIMIT 1";
			$result_company = mysql_query($sql_company, $conn) or die(mysql_error());
			$array_company  = mysql_fetch_array($result_company );
			
			$company_id = $array_company['company_id'];
			$company_name = $array_company['company_name'];
			$company_address = $array_company['company_address'];
			$company_city = $array_company['company_city'];
			$company_county = $array_company['company_county'];
			$company_postcode = $array_company['company_postcode'];
			$company_phone = $array_company['company_phone'];
			$company_fax = $array_company['company_fax'];
			$company_web = $array_company['company_web'];
			$contact_mobile = $array['contact_mobile'];


			// Select Title
			$sql2 = "SELECT * FROM contacts_titlelist WHERE title_id = '$contact_title' LIMIT 1";
			$result2 = mysql_query($sql2, $conn) or die(mysql_error());
			$array2 = mysql_fetch_array($result2);
			$title_name = $array2['title_name'];

			// Select Sector
			$sql3 = "SELECT * FROM contacts_sectorlist WHERE sector_id = '$contact_sector' LIMIT 1";
			$result3 = mysql_query($sql3, $conn) or die(mysql_error());
			$array3 = mysql_fetch_array($result3);
			$sector_name = $array3['sector_name'];

			// Select Relationship
			$sql4 = "SELECT * FROM contacts_relationlist WHERE relation_id = '$contact_relation' LIMIT 1";
			$result4 = mysql_query($sql4, $conn) or die(mysql_error());
			$array4 = mysql_fetch_array($result4);
			$relation_color = $array4['relation_color'];

			print "<tr><td width=\"50%\"><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">$contact_namefirst $contact_namesecond</a> ($contact_prefix)</td>";

// Insert company details

if ($company_name != NULL ) {
print "<td bgcolor=\"#$relation_color\"><a href=\"index2.php?page=company_view&amp;company_id=$company_id\">$company_name</a></td>";
} else {
print "<td bgcolor=\"#$relation_color\">Private</td>";
}
print "</tr>";




}

print "</table>";

}

?>
