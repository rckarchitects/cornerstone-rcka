<?php

$proj_id = $_GET[proj_id];

	$sql_proj = "SELECT proj_num, proj_name, proj_value, proj_fee_percentage  FROM intranet_projects where proj_id = $proj_id";
	$result_proj = mysql_query($sql_proj, $conn);
	$array_proj = mysql_fetch_array($result_proj);
	$proj_num = $array_proj['proj_num'];
	$proj_name = $array_proj['proj_name'];
	$proj_value = $array_proj['proj_value'];
	$proj_fee_percentage = $array_proj['proj_fee_percentage'];

print "<h1>Project Expenditure, $proj_num&nbsp;$proj_name</h1>";

	print "<table summary=\"Schedule of expenditure\">";
	print "<tr><td><strong>Stage</strong></td><td><strong>Fee for Stage</strong></td><td><strong>Time Expenditure</strong></td><td><strong>Invoiced</strong></td></tr>";
	
	$stage_total = 0;
	$fee_total = 0;
	$project_total = 0;
	$project_fee_total = 0;
	$invoice_item_total = 0;
	$project_invoiced_total = 0;

	$sql = "SELECT * FROM intranet_timesheet_fees WHERE ts_fee_project = '$proj_id' order by ts_fee_time_begin";
	
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
	
					$ts_fee_id = $array['ts_fee_id'];
					$ts_fee_stage = $array['ts_fee_stage'];
					$ts_fee_text = $array['ts_fee_text'];
					$ts_fee_value = $array['ts_fee_value'];
					$ts_fee_percentage = $array['ts_fee_percentage'];
					
								if ($ts_fee_stage > 0) {
											$sql_riba = "SELECT * FROM riba_stages WHERE riba_id = '$ts_fee_stage' LIMIT 1";
											$result_riba = mysql_query($sql_riba, $conn) or die(mysql_error());
											$array_riba = mysql_fetch_array($result_riba);
											$riba_letter = $array_riba['riba_letter'];
											$riba_desc = $array_riba['riba_desc'];
											$ts_fee_text = $riba_letter." - ".$riba_desc;
								}
					
						$sql2 = "SELECT * FROM intranet_timesheet where ts_stage_fee = '$ts_fee_id' AND ts_project = '$proj_id' ";
						$result2 = mysql_query($sql2, $conn) or die(mysql_error());
						while ($array2 = mysql_fetch_array($result2)) {
							$ts_rate = $array2['ts_rate'];
							$ts_overhead = $array2['ts_overhead'];
							$ts_projectrate = $array2['ts_projectrate'];
							$ts_hours = $array2['ts_hours'];
							$stage_total = $stage_total + ($ts_hours * ($ts_rate + $ts_overhead + $ts_projectrate));
							}
							
							
						// Work out how much has been invoiced for each stage
						
						$sql_invoice = "SELECT invoice_item_novat FROM intranet_timesheet_invoice_item, intranet_timesheet_invoice WHERE invoice_item_invoice = invoice_id AND invoice_project = '$proj_id' AND invoice_item_stage = '$ts_fee_id' AND invoice_date < ".time()."";
						$result_invoice = mysql_query($sql_invoice, $conn) or die(mysql_error());
						while ($array_invoice = mysql_fetch_array($result_invoice)) {
							$invoice_item_novat = $array_invoice['invoice_item_novat'];
							$invoice_item_total = $invoice_item_total + $invoice_item_novat;
							}
							
						
						// Calculate the Fee Stages for this project

							if ($ts_fee_percentage > 0) { $ts_fee_value = $proj_value * ( $ts_fee_percentage / 100) * ( $proj_fee_percentage / 100); }
							$fee_total = $fee_total + $ts_fee_value;

						
							if ($stage_total > $fee_total AND $ts_fee_value > 0) { $highlight = " style=\"background-color: #$settings_alertcolor;\""; $highlight2 = "background-color: #$settings_alertcolor;"; } else { $highlight = NULL; $highlight2 = NULL; }
							if ($fee_total < 1) { $fee_total_print = "Hourly Rate"; } else { $fee_total_print = MoneyFormat($fee_total); }
							print "<tr><td $highlight>$ts_fee_text</td><td style=\"text-align: right;$highlight2\">".$fee_total_print."</td><td style=\"text-align: right;$highlight2\">".MoneyFormat($stage_total)."</td><td style=\"text-align: right;$highlight2\">".MoneyFormat($invoice_item_total)."</td></tr>";
							$project_total = $project_total + $stage_total;
							$stage_total = 0;
					
						
						$project_fee_total = $project_fee_total + $fee_total;
						$project_invoiced_total = $project_invoiced_total + $invoice_item_total;
						$invoice_item_total = 0;
					
						
						$fee_total = 0;
	
	}
	
		$sql3 = "SELECT * FROM intranet_timesheet where ts_project = '$proj_id' AND ts_stage_fee < 1 ";
		$result3 = mysql_query($sql3, $conn) or die(mysql_error());
		while ($array3 = mysql_fetch_array($result3)) {
			$ts_rate = $array3['ts_rate'];
			$ts_overhead = $array3['ts_overhead'];
			$ts_projectrate = $array3['ts_projectrate'];
			$ts_hours = $array3['ts_hours'];
			$stage_total = $stage_total + ($ts_hours * ($ts_rate + $ts_overhead + $ts_projectrate));
			}

		if ($stage_total > 0) {
			print "<tr><td colspan=\"2\">Not Assigned</td><td style=\"text-align: right;\">".MoneyFormat($stage_total)."</td><td></td></tr>";
		}
		
		$project_total = $project_total + $stage_total;
	
	print "<tr><td><strong>TOTAL</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($project_fee_total)."</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($project_total)."</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($project_invoiced_total)."</strong></td></tr>";

	print "</table>";

?>