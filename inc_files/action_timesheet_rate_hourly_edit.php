<?php

if ($user_usertype_current < 3) { $alertmessage =  "Permission Denied - You do not have permission to view this page."; } else {

$counter = 0;

$array_id = $_POST[user_id];
$array_value = $_POST[rate_value];

while ($counter < count($array_id)) {

				$sql = "UPDATE intranet_timesheet_rate_user SET rate_value = '$array_value[$counter]' WHERE rate_user = '$array_id[$counter]' LIMIT 1";
				$result = mysql_query($sql, $conn) or die(mysql_error());

$counter++;

print $sql."<br />";
		
}

$actionmessage = "The user rates have been updated successfully.";

}

?>
