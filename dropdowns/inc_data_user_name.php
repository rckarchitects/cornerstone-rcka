<?php

	$sql = "SELECT user_name_first, user_name_second, user_id FROM intranet_user_details WHERE user_id = '$data_user_id' LIMIT 1";
	$result_data = mysql_query($sql, $conn) or die(mysql_error());

		$array_data = mysql_fetch_array($result_data);

		$user_id = $array_data['user_id'];
		$user_name_first = $array_data['user_name_first'];
		$user_name_second = $array_data['user_name_second'];
	
	print "<a href=\"index2.php?page=user_view&amp;user_id=$user_id\">$user_name_first&nbsp;$user_name_second</a>";

?>
