<?php

$array_id = $_POST['ts_expense_id'];
$array_p11d = $_POST['ts_expense_p11d'];
$ts_expense_user = $_POST['ts_expense_user'];

$counter = 0;


while ($counter <= count($array_id)) {

				$sql2 = "UPDATE intranet_timesheet_expense SET ts_expense_p11d = '$array_p11d[$counter]' WHERE ts_expense_id = '$array_id[$counter]' AND ts_expense_user = $ts_expense_user LIMIT 1";
				$result = mysql_query($sql2, $conn) or die(mysql_error());
				
				//  echo "<p>$counter: $sql2</p>";
			
				
$counter++;		
}



?>