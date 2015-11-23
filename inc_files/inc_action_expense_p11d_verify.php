<?php

$date_verified = time();
$counter = 0;
$total = 0;

$array_id = $_POST['ts_expense_id'];

echo "<table>";

while ($counter < count($array_id)) {
		
				$sql = "SELECT * FROM intranet_timesheet_expense WHERE ts_expense_id = ".$array_id[$counter]." ORDER BY ts_expense_date ";
				// $sql2 = "UPDATE intranet_timesheet_expense SET ts_expense_verified = '$date_verified' WHERE ts_expense_id = ".$array_id[$counter]." LIMIT 1";
				$result = mysql_query($sql, $conn) or die(mysql_error());
				// $result2 = mysql_query($sql2, $conn) or die(mysql_error());
				$array_sql = mysql_fetch_array($result);
				$ts_expense_id = $array_sql['ts_expense_id'];
				$ts_expense_desc = $array_sql['ts_expense_desc'];
				$ts_expense_date = $array_sql['ts_expense_date'];
				$ts_expense_vat = $array_sql['ts_expense_vat'];
				$project_id = $array_sql['proj_id'];
				$project_num = $array_sql['proj_num'];
				$project_name = $array_sql['proj_name'];
				
				$total = $total + $ts_expense_vat;
				
				print "<tr><td>$ts_expense_id</td><td>".$ts_expense_desc."</td><td style=\"text-align: right;\">".MoneyFormat($ts_expense_vat)."</td></tr>";

$counter++;
		
}

echo "</table>";

?>
