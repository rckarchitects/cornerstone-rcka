<?php

	$sql = "SELECT * FROM intranet_projects WHERE proj_active = '1' order by proj_num DESC";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	print "<select name=\"$result_data\">";
	while ($array = mysql_fetch_array($result)) {
	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$proj_id = $array['proj_id'];
	
	print "<option value=\"$proj_id\"";
	if ( $proj_id == $data_project) {
	print " selected ";
	}
	print ">$proj_num&nbsp;$proj_name$fee_option</option>";
	}
	print "</select>";
	
?>
