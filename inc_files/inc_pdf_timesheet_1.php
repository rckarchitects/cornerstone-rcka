<?php


// Begin the array through all the projects

	$sql = "SELECT * FROM intranet_projects ORDER BY proj_num";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	while ($array = mysql_fetch_array($result)) {


			if (strlen($array['proj_name']) > 25) { $proj_name = substr($array['proj_name'], 0, 22)."..."; } else { $proj_name = $array['proj_name']; }

			$proj_num = $array['proj_num']." ".$proj_name;
			$proj_id = $array['proj_id'];
			$proj_active = $array['proj_active'];
			$proj_riba = $array['proj_riba'];
			$proj_client_contact_id = $array['proj_client_contact_id'];
			$proj_account_track = $array['proj_account_track'];
			
			if ($proj_client_contact_id > 0) {
			
				$sql_client = "SELECT contact_namefirst, contact_namesecond FROM contacts_contactlist WHERE contact_id = '$proj_client_contact_id' LIMIT 1";
				$result_client = mysql_query($sql_client, $conn) or die(mysql_error());
				$array_client = mysql_fetch_array($result_client);
				$contact_namefirst = $array_client['contact_namefirst'];
				$contact_namesecond = $array_client['contact_namesecond'];
				
				$client_name = $contact_namefirst." ".$contact_namesecond;
				
			} else {
				
				unset($client_name);
				
			}
			
			if (strlen($client_name) > 37) { $client_name = substr($client_name, 0, 34)."..."; }
			
			
			// The RIBA work stages:
			
			
					$sql_riba = "SELECT riba_letter FROM riba_stages WHERE riba_id = '$proj_riba' LIMIT 1";
					$result_riba = mysql_query($sql_riba, $conn) or die(mysql_error());
					$array_riba = mysql_fetch_array($result_riba);
					$riba_letter = $array_riba['riba_letter'];
					if ($riba_letter != NULL) { $riba_letter = " [".$riba_letter."]"; } else { unset($riba_letter); }
					
					$proj_num = $proj_num.$riba_letter;
			
			
			
			
			if ($proj_active == "2") {			
			$pdf->SetFillColor(225, 225, 225);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetTextColor('0','0','0');
			} elseif ($proj_active == "1") {
			$pdf->SetFillColor(200, 200, 200);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetTextColor('0','0','0');
			} else {
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetTextColor('100','100','100');
			}
			
			
			
			$pdf->Cell(49,3.5,$proj_num,0, 0, L, 1);
			$pdf->Cell(1,3.5,'',0, 0);
			$pdf->SetFont('Helvetica','',8);
			
			// Collect the figures for hours for this project
			
						$ts_count_months = $ts_then_month;
						$ts_count_year = $ts_then_year;
						$project_prior_cost = 0;
						$project_prior_totalcost = 0;
						$project_prior_expense = 0;
						$hours_counter = 0;
		
						
						// Get the results (costs and expenses) from before the beginning date
						
						$project_month_hours = 0;
						$project_month_cost = 0;
						$project_month_expense = 0;
						$project_datum_cost = 0;
						
						$month_begin_time = mktime(0,0,0,$ts_count_months,1,$ts_count_year);
						
						$sql4 = "SELECT * FROM intranet_timesheet WHERE ts_project = '$proj_id' AND 'ts_entry' < '$month_begin_time' ";
						
						$sql5 = "SELECT * FROM intranet_timesheet_expense WHERE ts_expense_project = '$proj_id' AND 'ts_expense_date' < '$month_begin_time' AND ts_expense_verified = 1 ";
						
						$sql6 = "SELECT * FROM intranet_timesheet_datum WHERE ts_datum_project = '$proj_id' ";
						
						$result4 = mysql_query($sql4, $conn) or die(mysql_error());
						$result5 = mysql_query($sql5, $conn) or die(mysql_error());
						$result6 = mysql_query($sql6, $conn) or die(mysql_error());
						
						while ($array4 = mysql_fetch_array($result4)) {
						$project_array_cost = ($array4['ts_rate'] + $array4['ts_overhead']) * $array4['ts_hours'];
						$project_prior_cost = $project_prior_cost + $project_array_cost;
						}
						
						while ($array5 = mysql_fetch_array($result5)) {
						$project_array_expense = $array5['ts_expense_value'];
						$project_prior_expense = $project_prior_expense + $project_array_expense;
						}

						while ($array6 = mysql_fetch_array($result6)) {
						$project_array_datum = ($array6['ts_datum_rate'] + $array6['ts_datum_overhead']) * $array6['ts_datum_hours'];
						$project_datum_cost = $project_datum_cost + $project_array_datum;
						}

						
						$project_prior_totalcost = $project_prior_cost + $project_prior_expense + $project_datum_cost;
						
						if ($project_prior_totalcost > 0) { $project_prior_totalcost_print = "£".number_format($project_prior_totalcost); } else { $project_prior_totalcost_print = NULL; }
						
						$pdf->Cell(14,3.5,$project_prior_totalcost_print,0, 0, R, 1);
						$pdf->Cell(1,3.5,'',0, 0);
						
						
						
						
						
								// Get the results for each month in turn
				
									$project_year_cost_total = 0;
									$project_month_cost_total = 0;
									$project_month_array = 0;
																		
									while ($hours_counter <= 12) {
										
									$project_month_expense = 0;
									$project_month_cost = 0;
									$project_month_cost_total = 0;
									
										
									$month_begin_time = mktime(0,0,0,$ts_count_months,1,$ts_count_year);
									$ts_count_monthend = $ts_count_months + 1;
									$month_end_time = mktime(0,0,0,$ts_count_monthend,1,$ts_count_year);
									
									$sql2 = "SELECT * FROM intranet_timesheet WHERE ts_project = '$proj_id' AND ts_entry BETWEEN '$month_begin_time' AND '$month_end_time' ";
									$sql3 = "SELECT * FROM intranet_timesheet_expense WHERE ts_expense_project = '$proj_id' AND ts_expense_verified = 1 AND ts_expense_date BETWEEN '$month_begin_time' AND '$month_end_time' ";
									$result2 = mysql_query($sql2, $conn) or die(mysql_error());
									$result3 = mysql_query($sql3, $conn) or die(mysql_error());
									
									
										$project_month_hours = 0;
										$project_month_cost = 0;
										$project_month_expense = 0;
									
										// Check hours and calculate costs
										
										$project_array_cost = 0;
										
										while ($array2 = mysql_fetch_array($result2)) {
											$project_array_cost = ($array2['ts_rate'] + $array2['ts_overhead']) * $array2['ts_hours'];
											$project_month_cost = $project_month_cost + $project_array_cost;
											}
										
										// Check expenses and calculate costs
											
										$project_month_expense = 0;
										
										while ($array3 = mysql_fetch_array($result3)) {
											$project_array_expense = $array3['ts_expense_value'];
											$project_month_expense = $project_month_expense + $project_array_expense;
											}
											
										$project_month_cost_total = $project_month_cost_total + $project_month_cost + $project_month_expense;
										
										$project_month_array = $project_month_array + $project_month_cost_total;
									
									if ($project_month_cost_total > 0) { $project_month_cost_total_print = "£".number_format($project_month_cost_total); } else { $project_month_cost_total_print = NULL; }
									$pdf->Cell(14,3.5,$project_month_cost_total_print,0, 0, R, 1);
									$pdf->Cell(1,3.5,'',0, 0);
									$hours_counter++;
									$ts_count_months++;
									if ($ts_count_months > 12) {$ts_count_months = 1; $ts_count_year++; }
									
									if ($hours_counter == 12) { $pdf->SetFont('Helvetica','',8); $pdf->SetTextColor(150,150,150); } else {$pdf->SetFont('Helvetica','',8); $pdf->SetTextColor(0,0,0); }
									}
									$pdf->Cell(1,3.5,'',0, 0);
									
									$project_year_cost = $project_month_array + $project_prior_totalcost;
									
									$project_year_cost = $project_year_cost - $project_month_cost_total;
									

									
									if ($project_year_cost != NULL) {$project_year_cost_print = "£".number_format($project_year_cost); } else { $project_year_cost_print = NULL; }
									
									// Grand Total so far
									
									if ($proj_account_track == 1) {
										$grand_total_costs = $project_year_cost	+ $grand_total_costs;
									$pdf->SetFont('Helvetica','',8);
									} else {
									$pdf->SetFont('Helvetica','',8);
									}
									
									$pdf->Cell(0,3.5,$project_year_cost_print,0, 1, R, 1);
									$pdf->SetFont('Helvetica','',8);
						
						
						
						
						
						
						
						
						
						
						
						
						
						
	
		
// Now begin the invoicing schedule	











			$invoice_title = $client_name;

			if ($proj_active == "2") {			
			$pdf->SetFillColor(225, 225, 225);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetTextColor('0','0','0');
			} elseif ($proj_active == "1") {
			$pdf->SetFillColor(200, 200, 200);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetTextColor('0','0','0');
			} else {
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetFont('Helvetica','',8);
			$pdf->SetTextColor('100','100','100');
			}
			
			$pdf->SetFont('Helvetica','',6);
			$pdf->Cell(49,2.75,$invoice_title,0, 0, L, 1);
			$pdf->Cell(1,2.75,'',0, 0);
			$pdf->SetFont('Helvetica','',7);
			
			// Collect the figures for hours for this project
			
						$ts_count_months = $ts_then_month;
						$ts_count_year = $ts_then_year;
						$project_prior_cost = 0;
						$project_prior_totalcost = 0;
						$project_prior_expense = 0;
						$hours_counter = 0;
		
						
						// Get the results (costs and expenses) from before the beginning date
						
						$project_year_cost = 0;
						
						$project_month_expense = 0;
						
						$month_begin_time = mktime(0,0,0,$ts_count_months,1,$ts_count_year);
						
						
						$sql5 = "SELECT * FROM intranet_timesheet_invoice WHERE invoice_project = '$proj_id' AND invoice_date < '$month_begin_time' ";
						
						unset($result5, $project_prior_expense, $project_array_expense, $project_prior_expense_print);

						$result5 = mysql_query($sql5, $conn) or die(mysql_error());
				
						
						
						while ($array5 = mysql_fetch_array($result5)) {
						$project_array_expense = $array5['invoice_value_novat'];
						$project_prior_expense = $project_prior_expense + $project_array_expense;
						}

					
						if ($project_prior_expense > 0) { $project_prior_expense_print = "£".number_format($project_prior_expense); } else { $project_prior_expense_print = $project_prior_expense; }
						
						$pdf->Cell(14,2.75,$project_prior_expense_print,0, 0, R, 1);
						$pdf->Cell(1,2.75,'',0, 0);
						
						
						
						
						
								// Get the results for each month in turn
				
									
									$project_month_cost_total = 0;
									$project_month_array = 0;
																		
									while ($hours_counter <= 12) {
										
									$project_month_expense = 0;
									$project_month_cost = 0;
									$project_month_cost_total = 0;
									
										
									$month_begin_time = mktime(0,0,0,$ts_count_months,1,$ts_count_year);
									$ts_count_monthend = $ts_count_months + 1;
									$month_end_time = mktime(0,0,0,$ts_count_monthend,1,$ts_count_year);
									
									$sql3 = "SELECT * FROM intranet_timesheet_invoice WHERE invoice_project = '$proj_id' AND invoice_date BETWEEN '$month_begin_time' AND '$month_end_time' ";
									$result3 = mysql_query($sql3, $conn) or die(mysql_error());
									
									
										$project_month_expense = 0;
									
										
										// Check expenses and calculate costs
											
										$project_month_expense = 0;
										
										while ($array3 = mysql_fetch_array($result3)) {
											$project_array_expense = $array3['invoice_value_novat'];
											$project_month_expense = $project_month_expense + $project_array_expense;
											}
											
										$project_month_cost_total = $project_month_expense;
										
										$project_month_array = $project_month_array + $project_month_cost_total;
									
									if ($project_month_cost_total > 0) { $project_month_cost_total_print = "£".number_format($project_month_cost_total); } else { $project_month_cost_total_print = NULL; }
									$pdf->Cell(14,2.75,$project_month_cost_total_print,0, 0, R, 1);
									$pdf->Cell(1,2.75,'',0, 0);
									$hours_counter++;
									$ts_count_months++;
									if ($ts_count_months > 12) {$ts_count_months = 1; $ts_count_year++; }
									
									if ($hours_counter == 12) { $pdf->SetFont('Helvetica','',7); $pdf->SetTextColor(150,150,150); } else {$pdf->SetFont('Helvetica','',7); $pdf->SetTextColor(0,0,0); }
									}
									$pdf->Cell(1,2.75,'',0, 0);
									
									$project_year_cost = $project_month_array + $project_prior_expense;
									
									$project_year_cost = $project_year_cost - $project_month_cost_total;
									

									
									if ($project_year_cost != NULL) {$project_year_cost_print = "£".number_format($project_year_cost); } else { $project_year_cost_print = NULL; }
									
									
									// Grand total invoices
									
										if ($proj_account_track == 1) {
										$grand_total_invoice = $project_year_cost + $grand_total_invoice;
										$pdf->SetFont('Helvetica','',7);
										} else {
										$pdf->SetFont('Helvetica','',7);
										}
																			
									
									$pdf->Cell(0,2.75,$project_year_cost_print,0, 1, R, 1);
									$pdf->Cell(0,1,'',0, 1);
									$pdf->SetFont('Helvetica','',7);
						
						

									
	if ($project_counter > 15) {
		
		$page_count++;
		$project_counter = 0;
		
$pdf->AddPage();

// $pdf->Image('fpdf/logo_black.png',10,10,40);

$pdf->SetY(30);

$pdf->SetFont('Helvetica','',15);

// Print the title details

	$ts_print_title = "Summary for all projects (Page $page_count)";
		
	$pdf->SetFillColor(161, 213, 166);
	
	$pdf->Cell(0,10,$ts_print_title,0, 1, L, 1);

	// Printed by, and on...

		$pdf->SetFont('Helvetica','',12);
	
		$pdf->Cell(0,10,$printed_on,0, 1, L, 1);
	
		$pdf->SetFillColor(202, 159, 245);

		$pdf->Cell(190,2,'',0, 1);
		
// Column Headings

			$pdf->SetFont('Helvetica','',8);
			$pdf->SetFillColor(202, 159, 245);
			$pdf->Cell(49,3.5,'Project',0, 0, L, 1);
			$pdf->Cell(1,3.5,'',0, 0);
			$pdf->SetFont('Helvetica','',8);
			
			$pdf->Cell(14,3.5,'Baseline*',0, 0, C, 1);
			$pdf->Cell(1,3.5,'',0, 0);
			
			$ts_count_months = $ts_then_month;
			$ts_count_year = $ts_then_year;
			$counter = 0;
			
			while ($counter <= 12) {
			$count_date = $ts_count_months.".".$ts_count_year;
			$print_date = mktime(0,0,0,$ts_count_months,1,$ts_count_year);
			$print_date = date("M, y",$print_date);
			if ($counter == 12) { $print_date = $print_date."†"; }
			$pdf->Cell(14,3.5,$print_date,0, 0, C, 1);
			$pdf->Cell(1,3.5,'',0, 0);
			$counter++;
			$ts_count_months++;
			if ($ts_count_months > 12) {$ts_count_months = 1; $ts_count_year++; }
			}
			$pdf->Cell(1,3.5,'',0, 0);
			
			$pdf->Cell(0,3.5,'TOTAL',0, 1, C, 1);
			
			$pdf->Cell(0,2,'',0, 1);
				
		}

		
		
		
		$project_counter++;
		
}

// Grand totals at bottom of last page
			
			$pdf->Cell(0,4,'',0, 1);

			$pdf->SetFont('Helvetica','',11);
			$pdf->SetFillColor(217, 232, 246);

			$pdf->Cell(244,6,'TOTAL COSTS TO DATE',0, 0, L, 1);
			$pdf->Cell(2,6,'',0, 0);
	
			$grand_total_costs_print ="£".number_format($grand_total_costs);	
					
			$pdf->Cell(0,6,$grand_total_costs_print,0, 1, R, 1);
			
			$pdf->Cell(0,2,'',0, 1);
			
			$pdf->Cell(244,6,'TOTAL INVOICES TO DATE',0, 0, L, 1);
			$pdf->Cell(2,6,'',0, 0);
			
			$grand_total_invoice_print ="£".number_format($grand_total_invoice);
			
			$pdf->Cell(0,6,$grand_total_invoice_print,0, 1, R, 1);
			
			$pdf->SetFont('Helvetica','',11);
			$pdf->SetFillColor(177, 207, 237);
			
			$grand_total_difference =  $grand_total_invoice - $grand_total_costs;
			
			if ($grand_total_difference < 0) {
			$pdf->SetTextColor('255','51','0');
			}
			
			$pdf->Cell(0,2,'',0, 1);
			
			$pdf->Cell(244,6,'',0, 0, L, 0);
			$pdf->Cell(2,6,'',0, 0);
			
			$grand_total_difference_print ="£".number_format($grand_total_difference);
			
			$pdf->Cell(0,6,$grand_total_difference_print,0, 1, R, 1);


?>
