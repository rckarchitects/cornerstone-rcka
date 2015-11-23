<?php


	if ($listorder == "contact_added") { $desc_order = "DESC"; } else { $desc_order = NULL; }


		if ($_GET[filterletter] != NULL) {
  			$sql = "SELECT * FROM contacts_contactlist, contacts_relationlist WHERE contact_relation = relation_id AND $listorder LIKE '$_GET[filterletter]%' AND $listorder > '0' AND $listorder != '' order by $listorder,contact_namesecond LIMIT $desc_order $listbegin,$listmax ";
		} else {
			$sql = "SELECT * FROM contacts_contactlist, contacts_relationlist WHERE contact_relation = relation_id AND $listorder != '' order by $listorder $desc_order LIMIT $listbegin,$listmax ";
		}
			$sql_num = "SELECT * FROM contacts_contactlist, contacts_companylist";

			$sql_num_total = "SELECT contact_id FROM contacts_contactlist";
			$result_num_total = mysql_query($sql_num_total, $conn) or die(mysql_error());
			$sql_num_total = mysql_num_rows($result_num_total);

			$result_num = mysql_query($sql_num, $conn) or die(mysql_error());
			$result = mysql_query($sql, $conn) or die(mysql_error());

			$sql_num = mysql_num_rows($result_num);

			$count = 1;
			$listcount = $listbegin+1;

            print "<table>";

			while ($array = mysql_fetch_array($result)) {
			$contact_id = $array['contact_id'];
			$contact_prefix = $array['contact_prefix'];
			$contact_namefirst = $array['contact_namefirst'];
			$contact_namesecond = $array['contact_namesecond'];
			$contact_title = $array['contact_title'];
			$contact_company = $array['contact_company'];
			$contact_telephone = $array['contact_telephone'];
			$contact_fax = $array['contact_fax'];
			$contact_email = $array['contact_email'];
			$contact_sector = $array['contact_sector'];
			$contact_reference = $array['contact_reference'];
			$contact_department = $array['contact_department'];
			$contact_added = $array['contact_added'];
			$contact_relation = $array['contact_relation'];
			
			$relation_color = $array['relation_color'];
			
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

			// Select Prefix
			$sql5 = "SELECT * FROM contacts_prefixlist WHERE prefix_id = '$contact_prefix' LIMIT 1";
			$result5 = mysql_query($sql5, $conn) or die(mysql_error());
			$array5 = mysql_fetch_array($result5);
			$prefix_name = $array5['prefix_name'];
			
			if ($contact_prefix > 0) { $contact_prefix = "(".$prefix_name.")"; }
			
			if ($company_id > 0) { $colspan = NULL; } else { $colspan = "colspan=\"2\""; }

			if ($_GET[listorder] == "contact_namefirst") {
				print "<tr><td width=\"35%\" $colspan><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">$contact_namefirst $contact_namesecond</a> $contact_prefix</td>";
			} else {
				print "<tr><td width=\"35%\" $colspan><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">$contact_namesecond, $contact_namefirst</a> $contact_prefix</td>";
			}
// Insert company details

if ($company_id > 0 ) {
print "<td><a href=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">$company_name</a></td>";
}
print "</tr>\n";




}

print "</table>";

?>
