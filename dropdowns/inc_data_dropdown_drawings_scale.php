<?php

	$sql = "SELECT * FROM intranet_drawings_scale ORDER BY scale_desc";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	print "<select class=\"inputbox\" name=\"$result_data\">";
	$option_current = $$result_data;

	while ($array = mysql_fetch_array($result)) {

		$scale_id = $array['scale_id'];
		$scale_desc = $array['scale_desc'];
		
            print "<option value=\"$scale_id\"";
            if ($scale_id == $option_current) { print " selected"; }
            print ">".$scale_desc."</option>";
		}

	print "</select>";

?>
