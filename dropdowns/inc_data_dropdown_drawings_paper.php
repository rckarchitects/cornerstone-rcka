<?php

	$sql = "SELECT * FROM intranet_drawings_paper ORDER BY paper_size";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	print "<select class=\"inputbox\" name=\"$result_data\">";
	$option_current = $$result_data;

	while ($array = mysql_fetch_array($result)) {

		$paper_id = $array['paper_id'];
		$paper_size = $array['paper_size'];
		$paper_width = $array['paper_width'];
		$paper_height = $array['paper_height'];
		$paper_default = $array['paper_default'];
		
            print "<option value=\"$paper_id\"";
            if ($paper_id == $option_current) { echo " selected"; } elseif ($paper_default == 1) { echo " selected"; } 
            print ">".$paper_size." - (".$paper_width."mm x ".$paper_height."mm)</option>";
		}

	print "</select>";

?>
