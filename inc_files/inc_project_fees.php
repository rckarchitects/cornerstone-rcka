<?php

function InvoiceLineItems ($ts_fee_id, $highlight, $stage_fee) {
	
	global $conn;
	
	$highlight = $highlight . " font-size: 75%;";
	
	$ts_fee_id = intval($ts_fee_id);
	
	$invoice_total = 0;
	$invoice_paid_total = 0;
	$invoice_paid_remaining = 0;
	
	$sql = "SELECT * FROM intranet_timesheet_invoice_item, intranet_timesheet_invoice WHERE invoice_id = invoice_item_invoice AND invoice_item_stage = $ts_fee_id ORDER BY invoice_date, invoice_ref";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	if (mysql_num_rows($result) > 0) {
	
				while ($array = mysql_fetch_array($result)) {
					
					$invoice_id = $array['invoice_id'];
					$invoice_item_id = $array['invoice_item_id'];
					$invoice_item_invoice = $array['invoice_item_invoice'];
					$invoice_date = $array['invoice_date'];
					$invoice_paid = $array['invoice_paid'];
					$invoice_ref = $array['invoice_ref'];
					$invoice_item_novat = $array['invoice_item_novat'];
					$invoice_project = $array['invoice_project'];
					
					$invoice_total = $invoice_total + $invoice_item_novat;
					
					if ($invoice_paid) { $invoice_paid_total = $invoice_paid_total + $invoice_item_novat; }
					
					echo "	<tr>
							<td style=\"" . $highlight . "\" colspan=\"2\"></td>
							<td style=\"" . $highlight . "\"><a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">" . $invoice_ref . "</a>";
					
					if (!$invoice_date_paid) { echo "&nbsp;<a href=\"index2.php?page=timesheet_invoice_items_edit&amp;proj_id=" . $invoice_project . "&amp;invoice_item_id=" . $invoice_item_id . "\"><img src=\"images/button_edit.png\" alt=\"Edit\" />"; }
					
					echo "</td>
							<td style=\"" . $highlight . "\">" . TimeFormat($invoice_date) . "</td>
							<td style=\"" . $highlight . "text-align: right;" . "\" colspan=\"3\">" . MoneyFormat($invoice_item_novat) . "</td>
							<td style=\"" . $highlight . "\" colspan=\"2\"></td>
							</tr>
						";
					}
					
					$stage_fee_remaining = $stage_fee - $invoice_total;
					
					if ($stage_fee_remaining > 0) { $stage_fee_remaining = "<span style=\"color: red; font-weight: bold;\">" . MoneyFormat($stage_fee_remaining) . "</span>"; }
					else { $stage_fee_remaining = MoneyFormat($stage_fee_remaining); }
					
					
					echo "	<tr>
							<td style=\"" . $highlight . "\" colspan=\"2\"></td>
							<td style=\"" . $highlight . "\" colspan=\"2\">Remaining to invoice</td>
							<td style=\"" . $highlight . "text-align: right;" . "\" colspan=\"3\">" . $stage_fee_remaining . "</td>
							<td style=\"" . $highlight . "\" colspan=\"2\"></td>
							</tr>
						";
				
				$invoice_paid_remaining = $invoice_total - $invoice_paid_total;
				
				if ($invoice_paid_remaining > 0) { $invoice_paid_remaining_print = "<span style=\"color: red;\">" . MoneyFormat($invoice_paid_remaining) . "</span>"; }
				else { $invoice_paid_remaining_print = MoneyFormat($invoice_paid_remaining); }
						
				if ($invoice_paid_remaining > 0) {
						
					echo "	<tr>
							<td style=\"" . $highlight . "\" colspan=\"2\"></td>
							<td style=\"" . $highlight . "\" colspan=\"2\">Remaining to be paid</td>
							<td style=\"" . $highlight . "text-align: right;" . "\" colspan=\"3\">" . $invoice_paid_remaining_print . "</td>
							<td style=\"" . $highlight . "\" colspan=\"2\"></td>
							</tr>
					";
					
				}
	
	}
	
	$output = array();
	$output[] = $invoice_total;
	$output[] = $invoice_paid_total;
	
	return $output;
	
}

if ($module_fees = 1) {
					


				// Check if we're updating the current fee stage

				if ($_POST[fee_stage_current] > 0) { 

					$fee_stage_current = CleanNumber($_POST[fee_stage_current]);
					$sql_update = "UPDATE intranet_projects SET proj_riba = '$fee_stage_current' WHERE proj_id = '$proj_id' LIMIT 1";
					$result_update = mysql_query($sql_update, $conn) or die(mysql_error());

				}


				// Item Sub Menu
				echo "<div class=\"submenu_bar\">";

					if ($user_usertype_current > 3 OR $user_id_current == $proj_rep_black) {
						echo "<a href=\"index2.php?page=project_edit&amp;status=edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Edit</a>";
						echo "<a href=\"index2.php?page=project_hourlyrates_view&amp;proj_id=$proj_id\" class=\"submenu_bar\">Hourly Rates</a>";
						echo "<a href=\"index2.php?page=project_timesheet_view&amp;proj_id=$proj_id\" class=\"submenu_bar\">Expenditure</a>";
						echo "<a href=\"index2.php?page=timesheet_fees_edit&amp;proj_id=$proj_id\" class=\"submenu_bar\">Add Fee Stage</a>";
						echo "<a href=\"pdf_fee_drawdown.php?proj_id=$proj_id\" class=\"submenu_bar\"><img src=\"images/button_pdf.png\" alt=\"PDF\" />&nbsp;Fee Drawdown</a>";
						echo "<a href=\"pdf_fee_drawdown.php?proj_id=$proj_id&amp;showinvoices=yes\" class=\"submenu_bar\"><img src=\"images/button_pdf.png\" alt=\"PDF\" />&nbsp;Fee Drawdown (with invoices)</a>";
						
					}


				echo "</div>";

				echo "<h2>Fee Stages</h2>";

				ProjectSwitcher ("project_fees",$proj_id);

				$sql = "SELECT * FROM intranet_projects, intranet_timesheet_fees LEFT JOIN intranet_timesheet_group ON group_id = ts_fee_group WHERE ts_fee_project = '$proj_id' AND proj_id = ts_fee_project ORDER BY ts_fee_commence, ts_fee_text";
				$result = mysql_query($sql, $conn) or die(mysql_error());


						if (mysql_num_rows($result) > 0) {
							
						echo "<table summary=\"Lists the fees for the selected project\">";
						
						echo "<form method=\"post\" action=\"index2.php?page=project_fees&amp;proj_id=$proj_id\">";
						
						echo "<tr><th colspan=\"3\">Stage</th><th>Begin Date</th><th>End Date</th><th>Likelihood</th><th";
						if ($user_usertype_current > 2) { echo " colspan=\"3\""; }
						echo ">Fee for Stage</th></tr>";
						

						$fee_total = 0;
						$invoice_total = 0;
						$counter = 0;
						$prog_begin = $proj_date_commence;
						
						$target_cost_total = 0;
						
						$invoice_total = 0;
						$invoice_paid_total = 0;
						
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
												$ts_fee_pre = $array['ts_fee_pre'];
												$ts_fee_stage = $array['ts_fee_stage'];
												$group_code = $array['group_code'];
												if ($group_code == NULL) { $group_code = "-"; }
												$ts_fee_target = 1 / $array['ts_fee_target'];
												$ts_fee_prospect = $array['ts_fee_prospect'];
												$ts_fee_pre_lag = $array['ts_fee_pre_lag']; 
												$proj_value = $array['proj_value'];
												$proj_fee_percentage = $array['proj_fee_percentage'];
												$proj_riba = $array['proj_riba'];
												if ($array['proj_date_start'] != 0) { $proj_date_start = $array['proj_date_start']; } else { $proj_date_start = time(); }
												
												if ($ts_fee_comment != NULL) { $ts_fee_text = $ts_fee_text . "<span class=\"minitext\"><br />". $ts_fee_comment . "</span>"; }
												
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
														
														$fee_period_length = intval(($prog_end - $prog_begin) / 604800);
												
												if ($prog_begin > 0) { $prog_begin_print = "<a href=\"index2.php?page=datebook_view_day&amp;time=$prog_begin\">".TimeFormat($prog_begin)."</a>"; } else { $prog_begin_print = "-"; }
												if ($prog_end > 0) { $prog_end_print = "<a href=\"index2.php?page=datebook_view_day&amp;time=$prog_end\">".TimeFormat($prog_end)."</a>"; } else { $prog_end_print = "-"; }
												
												if ($prog_end > 0 && $fee_period_length > 0) { $prog_end_print = $prog_end_print . "<br /><span class=\"minitext\">Length: "  . $fee_period_length . " wks</span>"; }
									
												if ($ts_fee_pre) { $prog_begin_print_add = $ts_fee_pre ; }
												
												if ($ts_fee_pre_lag > 0) { $prog_begin_print_add = $prog_begin_print_add . " + " . round($ts_fee_pre_lag / 604800) . " weeks"; }
												
												if ($prog_begin_print_add) { $prog_begin_print = $prog_begin_print . "<br /><span class=\"minitext\"  onmouseover=\"ChangeBackgroundColor(\"stage_" . $prog_begin_print_add . "\")\">[" . $prog_begin_print_add . "]</span>"; }
												

												
												
												$proj_duration_print = "Complete: " . $percent_complete . "%</span>";
												
												if ( $percent_complete < 100) { $bg_color = "rgba(255,0,0,0.5)"; } else { $bg_color = "rgba(150,200,25,1)"; }
												
												$proj_duration_print = $proj_duration_print . "<div style=\"margin: 5px 0 0 0; background: $bg_color; height: 3px; width:" . $percent_complete . "%\"></div>";
												
												if ($ts_fee_id == $proj_riba) { $ts_fee_id_selected = " checked=\"checked\""; $highlight = " background: rgba(200,200,200,0.5);"; } else { unset($ts_fee_id_selected); unset($highlight); }
												
												if ($prog_end < time()) { $highlight = $highlight . " background: rgba(175,213,0,0.3);"; } elseif ( $ts_fee_id == $proj_riba ) { $highlight = $highlight . " background: rgba(255,175,0,0.3);"; } else { $highlight = $highlight . " background: rgba(255,0,0,0.3);"; }
												
												
												$fee_factored = $ts_fee_calc * $ts_fee_target; $fee_target = "<br /><span class=\"minitext\">Cumulative: "  . MoneyFormat($fee_total) . "<br />Target Cost: " . MoneyFormat($fee_factored). " + " .  number_format(((1 / $ts_fee_target) * 100) - 100 ) . "% profit</span>"; $target_cost_total = $target_cost_total + $fee_factored;
												
												if ($ts_fee_prospect == 0) { $ts_fee_likelihood = "Dead"; }
												elseif ($ts_fee_prospect == 10) { $ts_fee_likelihood = "Unlikely"; }
												elseif ($ts_fee_prospect == 25) { $ts_fee_likelihood = "Possible"; }
												elseif ($ts_fee_prospect == 50) { $ts_fee_likelihood = "Neutral"; }
												elseif ($ts_fee_prospect == 75) { $ts_fee_likelihood = "Probable"; }
												else { $ts_fee_likelihood = "Definite"; }
												
												$ts_fee_prospect = $ts_fee_likelihood . "&nbsp;(" . $ts_fee_prospect . "%)";
												
												
												echo "<tr id=\"stage_$ts_fee_id\"><td style=\"$highlight\"><input type=\"radio\" name=\"fee_stage_current\" value=\"$ts_fee_id\" $ts_fee_id_selected /> </td><td style=\"$highlight\">$group_code<br /><span class=\"minitext\">[$ts_fee_id]</span></td><td style=\"$highlight\">$ts_fee_text</td><td style=\"$highlight\">".$prog_begin_print."</td><td style=\"$highlight\">".$prog_end_print."</td><td style=\"$highlight\">".$ts_fee_prospect."</td><td  style=\"$highlight; text-align: right;\">".MoneyFormat($ts_fee_calc) . $fee_target ."</td>\n";
												echo "<td style=\"$highlight\">".$proj_duration_print."</td>";
												if ($user_usertype_current > 2) { echo "<td style=\"$highlight\"><a href=\"index2.php?page=timesheet_fees_edit&amp;ts_fee_id=$ts_fee_id\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a></td>"; }
												echo "</tr>";
												
												$totals_array = InvoiceLineItems($ts_fee_id,$highlight,$ts_fee_calc);

												$invoice_total = $invoice_total + $totals_array[0];
												$invoice_paid_total = $invoice_paid_total + $totals_array[1];				
												
												// Include a line if the invoice has been issued
												
												if ($invoice_id > 0) {
												
												echo "<tr>";
												if ($user_usertype_current > 2) { echo "<td colspan=\"5\">"; } else { echo "<td colspan=\"4\">"; }
													echo "Invoice Ref: <a href=\"index2.php?page=timesheet_invoice_view&amp;invoice_id=$invoice_id\">$invoice_ref</a>, issued: ".TimeFormat($invoice_date);
														if ($invoice_paid > 0) { echo ", paid: ".TimeFormat($invoice_paid); }
													echo "</td></tr>";
												}
												
												$counter++;
												$prog_begin = $prog_begin + $ts_fee_time_end;
												
												unset($highlight);
												
											}
					
						unset($highlight);
						
						if ($user_usertype_current > 3) { 
						
								echo "<tr><td colspan=\"6\"><strong>Total Fee for All Stages</strong></td><td style=\"text-align: right;\"><strong>". MoneyFormat($fee_total) . "</strong></td><td colspan=\"2\"></td></tr>";
								
								$profit = (( $fee_total / $target_cost_total ) - 1) * 100;
								
								$target_fee_percentage = number_format ($profit,2);
								
								echo "<tr><td colspan=\"6\"><strong>Target Cost for All Stages</strong></td><td style=\"text-align: right;\"><strong>".MoneyFormat($target_cost_total). "</strong></td><td colspan=\"2\">" . $target_fee_percentage . "% profit overall</td></tr>";

								if ($invoice_total > 0) {
									echo "<tr><td colspan=\"6\">Invoice Total</td><td style=\"text-align: right;\">".MoneyFormat($invoice_total). "</td><td colspan=\"2\"></td></tr>";
								}
								
								if ($invoice_paid_total > 0) {
									echo "<tr><td colspan=\"6\">Invoice Paid Total</td><td style=\"text-align: right;\">".MoneyFormat($invoice_paid_total). "</td><td colspan=\"2\"></td></tr>";
								}
						
						}
						
						echo "<tr><td colspan=\"9\"><input type=\"submit\" value=\"Update Current Fee Stage\" /></td></tr>";
						
						echo "</form>";
						
						echo "</table>";
						
						$sql = "SELECT ts_fee_id, ts_fee_text FROM intranet_timesheet_fees WHERE ts_fee_project = $proj_id ORDER BY ts_fee_text, ts_fee_time_begin";
						$result = mysql_query($sql, $conn) or die(mysql_error());
						
						$sql_count = "SELECT ts_project FROM intranet_timesheet WHERE ts_project = $proj_id AND ts_stage_fee = 0";
						$result_count = mysql_query($sql_count, $conn) or die(mysql_error());
						$null_rows = mysql_num_rows($result_count);
						
						
						if ($user_usertype_current > 3 && mysql_num_rows($result) > 0 && $null_rows > 0) { 
						
									echo "<fieldset><legend>Reconcile Unassigned Hours</legend>";
									
											echo "<p>Move all unassigned hours ($null_rows entries) to this fee stage:</p>";
											
											echo "<p><form action=\"index2.php?page=project_fees&amp;proj_id=$proj_id\" method=\"post\">";
											echo "<input type=\"hidden\" name=\"action\" value=\"fee_move_unassigned\" />";
											
											echo "<select name=\"ts_fee_id\">";
											
											while ($array = mysql_fetch_array($result)) {
												
												$ts_fee_id = $array['ts_fee_id'];
												$ts_fee_text = $array['ts_fee_text'];
												
												if ($proj_riba == $ts_fee_id) { $selected = "selected = \"selected\""; } else { unset($selected); }
												
												echo "<option value=\"$ts_fee_id\" $selected>$ts_fee_text</option>";
												
											
											}
											
											echo "</select>";
											echo "&nbsp;<input type=\"hidden\" name=\"proj_id\" value=\"$proj_id\" />";
											echo "<input type=\"submit\"  onclick=\"return confirm('Are you sure you want to move all unallocated hours to this fee stage?')\">";
											
											echo "</form></p>";
											
											echo "<p>Alternatively, <a href=\"index2.php?page=timesheet_fee_reconcile&amp;proj_id=$proj_id\">click here</a> to undertake detailed reconciliation.</p>";
									
									echo "</fieldset>";
						
						}
						
				} else {

					echo "<p>There are no fee stages on the system for this project.</p>";
					
				}


} else {
	
	echo "<p>Module not enabled</p>";
	
}
