<?php

function ReconcileTimesheets($update_ts_entry_id ,$update_ts_entry_fee ) {
	
	GLOBAL $conn;

				if ($update_ts_entry_id != NULL && $update_ts_entry_fee != NULL) {
					
					$counter = 0;
					
					foreach ($update_ts_entry_id AS $ts_id) {
						
						if ($update_ts_entry_fee[$counter] > 0) {
							$sql = "UPDATE intranet_timesheet SET ts_stage_fee = $update_ts_entry_fee[$counter] WHERE ts_id = $ts_id LIMIT 1";
							$result = mysql_query($sql, $conn) or die(mysql_error());
							//echo "<p>$counter. $sql</p>";
						} else {
							//echo "<p>$counter. NO CHANGE</p>";
						}
							$counter++;
						
					}
					
					
				}


}

ReconcileTimesheets($_POST[update_ts_entry_id],$_POST[update_ts_entry_fee]);
