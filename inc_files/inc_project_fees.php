<?php

if ($_POST[proj_id] > 0) { print "<h1>Project Fee Stages</h1>"; $proj_id = $_POST[proj_id]; }
elseif ($_GET[proj_id] > 0) { $proj_id = $_GET[proj_id]; }
elseif ($_POST[ts_fee_id] > 0) { $proj_id = $_POST[ts_fee_id]; }

// Check if we're updating the current fee stage

if ($_POST[fee_stage_current] > 0) { 

	$fee_stage_current = CleanNumber($_POST[fee_stage_current]);
	$sql_update = "UPDATE intranet_projects SET proj_riba = '$fee_stage_current' WHERE proj_id = '$proj_id' LIMIT 1";
	$result_update = mysql_query($sql_update, $conn) or die(mysql_error());

}


// Item Sub Menu
print "<p class=\"submenu_bar\">";

	if ($user_usertype_current > 2) {
		print "<a href=\"index2.php?page=project_hourlyrates_view&amp;proj_id=$proj_id\" class=\"submenu_bar\">Hourly Rates</a>";
		print "<a href=\"index2.php?page=project_timesheet_view&amp;proj_id=$proj_id\" class=\"submenu_bar\">Expenditure</a>";
		print "<a href=\"index2.php?page=timesheet_fees_edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add Fee Stage</a>";
	}
	if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
		print "<a href=\"index2.php?page=project_edit&amp;status=edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Edit</a>";
	}

print "</p>";

print "<h2>Project Fee Stages</h2>";

$sql = "SELECT * FROM intranet_timesheet_fees, intranet_projects WHERE ts_fee_project = '$proj_id' AND proj_id = ts_fee_project ORDER BY ts_fee_commence, ts_fee_text";
$result = mysql_query($sql, $conn) or die(mysql_error());


		if (mysql_num_rows($result) > 0) {
		
		print "<table summary=\"Lists the fees for the selected project\">";
		
		echo "<form method=\"post\" action=\"index2.php?page=project_fees&amp;proj_id=$proj_id\">";
		
		print "<tr><th colspan=\"2\">Stage</th><th>Begin Date</th><th>End Date</th><th";
		if ($user_usertype_current > 2) { print " colspan=\"3\""; }
		print ">Fee for Stage</th></tr>";
		

		$fee_total = 0;
		$invoice_total = 0;
		$counter = 0;
		$prog_begin = $proj_date_commence;
		
		$target_cost_total = 0;
		
								while ($array = mysql_fetch_array($result)) {
								
								$ts_fee_id = $array['ts_fee_id'];
								$ts_fee_time_begin = $array['ts_fee_time_begin'];
								$ts_fee_time_end = $array['ts_fee_time_end'];
								$prog_end = $prog_begin + $ts_fee_time_end;
								$ts_fee_value = $array['ts_fee_value'];
								$ts_fee_text = $array['ts_fee_text'];
								$ts_fee_comment = $array['ts_fee_comment'];
								$ts_fee_commence = $array['ts_fee_commence'];
								$ts_fee_percentage = $array['ts_fee_percentage'];
								$ts_fee_invoice = $array['ts_fee_invoice'];
								$ts_fee_project = $array['ts_fee_project'];
								$ts_fee_stage = $array['ts_fee_stage'];
								$ts_fee_target = 1 / $array['ts_fee_target'];
								// $proj_id = $array['proj_id']; 					We don't need this, do we?
								$proj_value = $array['proj_value'];
								$proj_fee_percentage = $array['proj_fee_percentage'];
								$proj_riba = $array['proj_riba'];
								if ($array['proj_date_start'] != 0) { $proj_date_start = $array['proj_date_start']; } else { $proj_date_start = time(); }
								
								if ($ts_fee_comment != NULL) { $ts_fee_text = $ts_fee_text . "<span class=\"minitext\"><br />". $ts_fee_comment . "</span>"; }

								if ($ts_fee_stage > 0) {
										$sql_riba = "SELECT * FROM riba_stages WHERE riba_id = '$ts_fee_stage' LIMIT 1";
										$result_riba = mysql_query($sql_riba, $conn) or die(mysql_error());
										$array_riba = mysql_fetch_array($result_riba);
										$riba_letter = $array_riba['riba_letter'];
										$riba_desc = $array_riba['riba_desc'];
										$ts_fee_text = $riba_letter." - ".$riba_desc;
								}
								
								//  Pull any invoices from the system which relate to this fee stage
									$sql2 = "SELECT * FROM intranet_timesheet_invoice WHERE invoice_id = '$ts_fee_invoice' LIMIT 1";
									$result2 = mysql_query($sql2, $conn) or die(mysql_error());
									$array2 = mysql_fetch_array($result2);
									$invoice_id = $array2['invoice_id'];
									$invoice_ref = $array2['invoice_ref'];
									$invoice_date = $array2['invoice_date'];
								
								$proj_fee_total = $proj_value * ($proj_fee_percentage / 100);
								
								if ($ts_fee_percentage > 0) { $ts_fee_calc = ($proj_fee_total * ($ts_fee_percentage / 100)); } else { $ts_fee_calc = $ts_fee_value; }
								
								$fee_total = $fee_total + $ts_fee_calc;
								
								if ($proj_riba == $riba_id) { $highlight = "background-color: #$settings_alertcolor;"; } else { unset($highlight); }
								
								//  This bit needs re-writing to cross out any completed stages	
								// if ($proj_riba > $riba_order) { $highlight = $highlight."text-decoration: line-through;"; }
								
								$prog_begin = AssessDays ($ts_fee_commence);
								if ($prog_begin > 0) { $prog_end = $prog_begin + $ts_fee_time_end; } else { $prog_begin = time(); }
								
								// Calculate the time we are through the stage
										if (time() > $prog_begin && time() < $prog_end) {
										
											$percent_complete = time() - $prog_begin;
											$percent_complete = $percent_complete / $ts_fee_time_end;
										
										}
										elseif (time() > $prog_end) { $percent_complete = 1; }
										else { $percent_complete = 0; }
										$percent_complete = $percent_complete * 100;
										
										$percent_complete = round ($percent_complete,0);
								
								if ($prog_begin > 0) { $prog_begin_print = "<a href=\"index2.php?page=datebook_view_day&amp;time=$prog_begin\">".TimeFormat($prog_begin)."</a>"; } else { $prog_begin_print = "-"; }
								if ($prog_end > 0) { $prog_end_print = "<a href=\"index2.php?page=datebook_view_day&amp;time=$prog_end\">".TimeFormat($prog_end)."</a>"; } else { $prog_end_print = "-"; }
								
								$proj_duration = $prog_end - $prog_begin;
								if ($proj_duration > 0) { $proj_duration_print = round($proj_duration / 604800)." weeks<br />(" . $percent_complete . "%)"; } else { $proj_duration_print = " - "; }
								
								if ($ts_fee_id == $proj_riba) { $ts_fee_id_selected = " checked=\"checked\""; } else { unset($ts_fee_id_selected); }
								
								
								$fee_factored = $ts_fee_calc * $ts_fee_target; $fee_target = "<br />(Target Cost: " . MoneyFormat($fee_factored). " / " .  number_format(((1 / $ts_fee_target) * 100) - 100 ) . "%)"; $target_cost_total = $target_cost_total + $fee_factored;
								
								
								print "<tr><td><input type=\"radio\" name=\"fee_stage_current\" value=\"$ts_fee_id\" $ts_fee_id_selected /> </td><td style=\"$highlight\">$ts_fee_text</td><td style=\"$highlight\">".$prog_begin_print."</td><td style=\"$highlight\">".$prog_end_print."</td><td  style=\"$highlight; text-align: right;\">".MoneyFormat($ts_fee_calc) . $fee_target ."</td>\n";
								echo "<td>".$proj_duration_print."</td>";
								if ($user_usertype_current > 2) { print "<td style=\"$highlight\"><a href=\"index2.php?page=timesheet_fees_edit&amp;ts_fee_id=$ts_fee_id\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a></td>"; }
								print "</tr>";
								
								// Include a line if the invoice has been issued
								
								if ($invoice_id > 0) {
								
								print "<tr>";
								if ($user_usertype_current > 2) { print "<td colspan=\"5\">"; } else { print "<td colspan=\"4\">"; }
									print "Invoice Ref: <a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">$invoice_ref</a>, issued: ".TimeFormat($invoice_date);
										if ($invoice_paid > 0) { print ", paid: ".TimeFormat($invoice_paid); }
									print "</td></tr>";
								}
								
								$counter++;
								$prog_begin = $prog_begin + $ts_fee_time_end;
								
							}
	
		unset($highlight);
		
		if ($user_usertype_current > 3) { 
		
				print "<tr><td colspan=\"4\"><strong>Total Fee for All Stages</strong></td><td style=\"text-align: right;\" colspan=\"3\"><strong>".MoneyFormat($fee_total)."</strong></td></tr>";
				
				$profit = (( $fee_total / $target_cost_total ) - 1) * 100;
				
				$target_fee_percentage = number_format ($profit,2);
				
				print "<tr><td colspan=\"4\"><strong>Target Cost for All Stages</strong></td><td style=\"text-align: right;\" colspan=\"3\"><strong>".MoneyFormat($target_cost_total). " (" . $target_fee_percentage . "% Profit Overall)</strong></td></tr>";

		
		}
		
		echo "<tr><td colspan=\"7\"><input type=\"submit\" value=\"Update Current Fee Stage\" /></td></tr>";
		
		echo "</form>";
		
		print "</table>";
		
		$sql = "SELECT ts_fee_id, ts_fee_text FROM intranet_timesheet_fees WHERE ts_fee_project = $proj_id ORDER BY ts_fee_text, ts_fee_time_begin";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		
		$sql_count = "SELECT ts_project FROM intranet_timesheet WHERE ts_project = $proj_id AND ts_stage_fee = 0";
		$result_count = mysql_query($sql_count, $conn) or die(mysql_error());
		$null_rows = mysql_num_rows($result_count);
		
		
		if ($user_usertype_current > 3 && mysql_num_rows($result) > 0 && $null_rows > 0) { 
		
					echo "<fieldset><legend>Reconcile Unassigned Hours</legend>";
					
							echo "<p>Move all unassigned hours ($null_rows entries) to this fee stage:</p>";
							
							echo "<form action=\"index2.php?page=project_fees&amp;proj_id=$proj_id\" method=\"post\">";
							echo "<input type=\"hidden\" name=\"action\" value=\"fee_move_unassigned\" />";
							
							echo "<select name=\"ts_fee_id\">";
							
							while ($array = mysql_fetch_array($result)) {
								
								$ts_fee_id = $array['ts_fee_id'];
								$ts_fee_text = $array['ts_fee_text'];
								
								if ($proj_riba == $ts_fee_id) { $selected = "selected = \"selected\""; } else { unset($selected); }
								
								echo "<option value=\"$ts_fee_id\" $selected>$ts_fee_text</option>";
								
							
							}
							
							echo "</select>";
							echo "<p><input type=\"hidden\" name=\"proj_id\" value=\"$proj_id\" />";
							echo "<input type=\"submit\"  onclick=\"return confirm('Are you sure you want to move all unallocated hours to this fee stage?')\"></p>";
							
							echo "</form>";
					
					echo "</fieldset>";
		
		}
		
} else {

	print "<p>There are no fee stages on the system for this project.</p>";
	
}


?>