<?php

$proj_id = intval ($_GET[proj_id]);

function ProjectTimesheetView($proj_id) {
	
	global $conn;

	$sql_proj = "SELECT proj_num, proj_name, proj_value, proj_fee_percentage  FROM intranet_projects where proj_id = $proj_id";
	$result_proj = mysql_query($sql_proj, $conn);
	$array_proj = mysql_fetch_array($result_proj);
	$proj_num = $array_proj['proj_num'];
	$proj_name = $array_proj['proj_name'];
	$proj_value = $array_proj['proj_value'];
	$proj_fee_percentage = $array_proj['proj_fee_percentage'];
	
	echo "<h2>Project Expenditure</h2>";
	
	ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
	ProjectSubMenu($proj_id,$user_usertype_current,"project_timesheet_view",4);



echo "<p>The table below shows each project stage with confirmed fees, profit target and timesheet costs at this moment in time.<br />Stages highlighted in <span style=\"background-color: rgba(255,126,0,0.5); padding: 0 5px 0 5px;\">orange</span> have exceeded their profit target, those in <span style=\"background-color: rgba(255,0,0,0.5); padding: 0 5px 0 5px;\">red</span> are losing money.</p>";

	print "<table summary=\"Schedule of expenditure\">";
	print "<tr><th>Stage</th><th style=\"text-align: right;\">Fee for Stage</th><th style=\"text-align: right;\">Profit</th><th style=\"text-align: right;\">Target Cost</th><th style=\"text-align: right;\">Cost Expended</th><th style=\"text-align: right;\">Invoiced</th></tr>";
	
	$stage_total = 0;
	$fee_total = 0;
	$project_total = 0;
	$project_fee_total = 0;
	$invoice_item_total = 0;
	$project_invoiced_total = 0;
	$profit_total = 0;
	$cost_total = 0;

	$sql = "SELECT * FROM intranet_timesheet_fees WHERE ts_fee_project = '$proj_id' AND ts_fee_prospect = 100 order by ts_fee_time_begin";
	
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
	
					$ts_fee_id = $array['ts_fee_id'];
					$ts_fee_stage = $array['ts_fee_stage'];
					$ts_fee_text = $array['ts_fee_text'];
					$ts_fee_value = $array['ts_fee_value'];
					$ts_fee_percentage = $array['ts_fee_percentage'];
					$ts_fee_profit = $ts_fee_value - ( $array['ts_fee_value'] / $array['ts_fee_target'] ); $profit_total = $profit_total + $ts_fee_profit;
					$ts_fee_cost = $array['ts_fee_value'] / $array['ts_fee_target']; $cost_total = $cost_total + $ts_fee_cost;
					
								if ($ts_fee_stage > 0) {
											$sql_riba = "SELECT * FROM riba_stages WHERE riba_id = '$ts_fee_stage' LIMIT 1";
											$result_riba = mysql_query($sql_riba, $conn) or die(mysql_error());
											$array_riba = mysql_fetch_array($result_riba);
											$riba_letter = $array_riba['riba_letter'];
											$riba_desc = $array_riba['riba_desc'];
											$ts_fee_text = $riba_letter." - ".$riba_desc;
								}
					
						$sql2 = "SELECT SUM(ts_cost_factored) FROM intranet_timesheet where ts_stage_fee = '$ts_fee_id' AND ts_project = '$proj_id' ";
						$result2 = mysql_query($sql2, $conn) or die(mysql_error());
						$array2 = mysql_fetch_array($result2);
							$stage_cost = $array2['SUM(ts_cost_factored)'];
							$stage_total = $stage_total + $stage_cost;						
							
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

						
							if ($stage_total > $fee_total AND $ts_fee_value > 0) { $highlight = " background-color: rgba(255,0,0,0.5);\""; }
							elseif ($stage_total > $ts_fee_cost AND $ts_fee_value > 0) { $highlight = " background-color: rgba(255,126,0,0.5);\"";  }
							else { $highlight = NULL; }
							
							if ($fee_total < 1) { $fee_total_print = "Hourly Rate"; } else { $fee_total_print = MoneyFormat($fee_total); }
							print "<tr><td style=\"$highlight\">" . $ts_fee_text . "&nbsp;<a href=\"http://intranet.rcka.co/index2.php?page=timesheet_fees_edit&amp;ts_fee_id=$ts_fee_id\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a></td><td style=\"text-align: right; $highlight\">".$fee_total_print."</td><td style=\"text-align: right;$highlight\">" . MoneyFormat($ts_fee_profit) . "</td><td style=\"text-align: right;$highlight\">" . MoneyFormat($ts_fee_cost) . "</td><td style=\"text-align: right;$highlight\">".MoneyFormat($stage_total)."</td>";
							
							if (($invoice_item_total < $fee_total) OR ($invoice_item_total < $stage_total)) { 
								echo "<td style=\"text-align: right; $highlight\"><span style=\"color: red;\"><strong>".MoneyFormat($invoice_item_total)."</strong> </span></td></tr>";
							} else {
								echo "<td style=\"text-align: right; $highlight\"><strong>".MoneyFormat($invoice_item_total)."</strong></td></tr>";
							}
							
							
							$project_total = $project_total + $stage_total;
							$stage_total = 0;
					
						
						$project_fee_total = $project_fee_total + $fee_total;
						$project_invoiced_total = $project_invoiced_total + $invoice_item_total;
						$invoice_item_total = 0;
					
						
						$fee_total = 0;
	
	}
	
		$sql3 = "SELECT SUM(ts_cost_factored) FROM intranet_timesheet WHERE ts_stage_fee = '$ts_fee_stage' AND ts_project = '$proj_id'";
		
		$result3 = mysql_query($sql3, $conn) or die(mysql_error());
		$array3 = mysql_fetch_array($result3);
		
		if ($array3['SUM(ts_cost_factored)'] > 0) {
			$highlight = " background-color: rgba(255,0,0,0.5);\"";
			echo "<tr><td colspan=\"4\" style=\"$highlight\"><a href=\"index2.php?page=timesheet_fee_reconcile&amp;proj_id=$proj_id\">Not Assigned</a></td><td style=\"text-align: right; $highlight;\">".MoneyFormat($array3['SUM(ts_cost_factored)'])."</td><td style=\"$highlight\"></td></tr>";
			$project_total = $project_total + $array3['SUM(ts_cost_factored)'];
		}
	
	print "<tr><td><strong>TOTAL</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($project_fee_total)."</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($profit_total)."</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($cost_total)."</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($project_total)."</strong></td><td style=\"text-align: right; $redtext\">";
	
	if (($project_invoiced_total < $project_fee_total) OR ($project_invoiced_total < $project_total)) { 
		echo "<span style=\"color: red;\"><strong>".MoneyFormat($project_invoiced_total)."</strong></span></td></tr>";
	} else {
		echo "<strong>".MoneyFormat($project_invoiced_total)."</strong></td></tr>";
	}

	print "</table>";

}

ProjectTimesheetView($proj_id);