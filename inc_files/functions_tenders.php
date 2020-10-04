<?php


function TenderGetDetails($field, $tender_id) {
	
	global $conn;
	$sql = "SELECT " . $field . " FROM intranet_tender WHERE tender_id = " . intval($tender_id) . " LIMIT 1";
	
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
	
	$output = addslashes($array[$field]);
	
	return $output;
	
}

function TenderList() {

		GLOBAL $conn;
		GLOBAL $user_usertype_current ;
		
		$submitted_total = 0;
		$successful_total = 0;
		
		$submitted_total_year = 0;
		$successful_total_year = 0;
		
		$current_year = date("Y",time());

		$nowtime = time();

		if ($_GET['detail'] == "yes") { $detail = "yes"; }
		
		
		if (urldecode($_GET['tender_filter']) == "tender_type" && intval($_GET['tender_id']) > 0) { $filter = "tender_type = '" . TenderGetDetails("tender_type",$_GET['tender_id']) . "'";  }
		elseif (urldecode($_GET['tender_filter']) == "tender_procedure" && intval($_GET['tender_id']) > 0) { $filter = "tender_procedure = '" . TenderGetDetails("tender_procedure",$_GET['tender_id']) . "'"; }
		elseif (urldecode($_GET['tender_filter']) == "tender_client" && intval($_GET['tender_id']) > 0) { $filter = "tender_client = '" . TenderGetDetails("tender_client",$_GET['tender_id']) . "'"; $analysis_client = " to " . TenderGetDetails("tender_client",$_GET['tender_id']) . ", "; }

		if (intval($_GET[tender_submitted]) == 1) {
			if ($filter) { $filter = "WHERE " . $filter; }
			$sql = "SELECT * FROM intranet_tender $filter ORDER BY tender_date DESC";
			echo "<h2>List of all tenders</h2>";
		} elseif (intval($_GET[tender_pending]) == 1) {
			if ($filter) { $filter = "AND " . $filter; }
			$sql = "SELECT * FROM intranet_tender WHERE tender_submitted = 1 AND (tender_result = 0 OR tender_result IS NULL) $filter ORDER BY tender_date DESC";
			echo "<h2>List of all pending tenders</h2>";
		} else {
			if ($filter) { $filter = "AND " . $filter; }
			$sql = "SELECT * FROM intranet_tender WHERE (tender_submitted = 1 OR (tender_date > " . time() . ") AND tender_result != 3) $filter ORDER BY tender_date DESC";
			echo "<h2>List of all submitted and future tenders</h2>";
			
		}
		
		$result = mysql_query($sql, $conn) or die(mysql_error());

				echo "<div class=\"submenu_bar\">";
							
					if (intval($_GET[tender_submitted]) == 0 OR intval($_GET[tender_pending]) == 1) {
						echo "<a href=\"index2.php?page=tender_list&amp;tender_submitted=1\" class=\"submenu_bar\">List All Tenders</a>";
					}
				
				
					if (intval($_GET[tender_submitted]) != 0) {
						echo "<a href=\"index2.php?page=tender_list\" class=\"submenu_bar\">List Only Submitted Tenders</a>";
					}
					
					if (intval($_GET[tender_pending]) != 1) {
						echo "<a href=\"index2.php?page=tender_list&amp;tender_pending=1\" class=\"submenu_bar\">List Only Pending Tenders</a>";
					}
					
					if ($user_usertype_current > 3) {
						echo "<a href=\"index2.php?page=tender_edit\" class=\"submenu_bar\">Add Tender <img src=\"images/button_new.png\" alt=\"Add New Tender\" /></a>";
					}

					
				echo "</div>";
				
				if (mysql_num_rows($result) > 0) {
					
					echo "<div>";
				
				$time_line = NULL;

			
				while ($array = mysql_fetch_array($result)) {
					
				$tender_id = $array['tender_id'];
					
					
				
				
				
				$tender_name = $array['tender_name'];
				if ($array['tender_type']) { $tender_type = "<br /><a href=\"index2.php?page=tender_list&amp;tender_filter=tender_type&amp;tender_id=" . $tender_id . "\">". $array['tender_type'] . "</a>"; }
				if ($array['tender_procedure']) { $tender_type = $tender_type . "<br /><span class=\"minitext\"><a href=\"index2.php?page=tender_list&amp;tender_filter=tender_procedure&amp;tender_id=" . $tender_id . "\">". $array['tender_procedure'] . "</a></span>"; }
				$tender_date = $array['tender_date'];
				$tender_client = $array['tender_client'];
				$tender_description = nl2br($array['tender_description']);
				$tender_keywords = $array['tender_keywords'];
				$tender_submitted = $array['tender_submitted'];
				$tender_result = $array['tender_result'];
				$tender_responsible = $array['tender_responsible'];
				$tender_linked = $array['tender_linked'];
				
				if (date("Y",$tender_date) != $current_year) { TenderResultSummary($successful_total_year,$submitted_total_year,$current_year); $current_year = date("Y",$tender_date); $successful_total_year = 0; $submitted_total_year = 0;  }
				
				// This checks to see if there were any previous stages to disregard
				$sql_linked = "SELECT tender_id, tender_type FROM intranet_tender WHERE tender_linked = " . intval($array['tender_id'])  . " " . $filter . " ORDER BY tender_date DESC LIMIT 1";
				$result_linked = mysql_query($sql_linked, $conn) or die(mysql_error());
				if (mysql_num_rows($result_linked) > 0) { $array_previous = mysql_fetch_array($result_linked); $previous_stage = $array_previous['tender_id']; $previous_stage_type = $array_previous['tender_type']; } else { $previous_stage = 0; unset($previous_stage_type); }
				
				// This checks to see if there were any subsequent stages to disregard
				$sql_subsequent = "SELECT tender_id, tender_type FROM intranet_tender WHERE tender_id = " . intval($array['tender_linked'])  . " " . $filter . " ORDER BY tender_date DESC LIMIT 1";
				$result_subsequent = mysql_query($sql_subsequent, $conn) or die(mysql_error());
				if (mysql_num_rows($result_subsequent) > 0) { $array_subsequent = mysql_fetch_array($result_subsequent); $subsequent_stage_type = $array_subsequent['tender_type']; } else { unset($subsequent_stage_type); }
				
				
				if ($tender_submitted == 1 && ($tender_result == 1 OR $tender_result == 2)) { $submitted_total++; $submitted_total_year++; }
				if ($tender_result == 1 ) { $successful_total++; $successful_total_year++; }
				
				
				if (($tender_date > time()) && $tender_submitted == 1) {
					$style = "style=\"background: rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.8);\"";
				} elseif ((($tender_date - $nowtime) < 86400) && (($tender_date - $nowtime) > 0)  && $tender_result != 3) {
					$style = "style=\"background: rgba(255,130,0,0.5); border: solid 1px rgba(255,130,0,0.8);\"";
				} elseif ((($tender_date - $nowtime) < 604800) && (($tender_date - $nowtime) > 0) && $tender_result != 3) {
					$style = "style=\"background: rgba(255,130,0,0.5); border: solid 1px rgba(255,130,0,0.8);\"";
				} elseif ($tender_date > time() && $tender_result != 3) {
					$style = "style=\"background: rgba(175,213,0,0.3); border: solid 1px rgba(175,213,0,0.8);\"";
				} elseif ($tender_submitted == 1 && $tender_result == 1 && $tender_result != 3) {
					$style = "style=\"background: rgba(0,0,255,0.3); border: solid 1px rgba(0,0,255,0.8);\"";
				} elseif ($tender_submitted == 1 && $tender_result == 2 && $tender_result != 3) {
					$style = "style=\"background: rgba(255,0,0,0.3); border: 1px solid rgba(255,0,0,0.8);\"";
				} elseif ($tender_submitted == 1 && $tender_result == 0 && $tender_result != 3) {
					$style = "style=\"background: rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.8);\"";
				} elseif (($tender_date < time()) OR $tender_result == 3) {
					$style = "style=\"background: rgba(0,0,0,0.1); border: solid 1px rgba(0,0,0,0.25); color: #ccc;\"";
				} else {
					$style = "style=\"background: rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.8);\"";
				}
				
				if ($tender_date > time()) {
					$deadline = " (" . DeadlineTime($tender_date - $nowtime) . ")";
				} else {
					unset($deadline);			
				}
				
				
				
				if (($nowtime > $tender_date) && ($nowtime < $time_line)) { echo "<div class=\"bodybox\" style=\"background: white; color: rgba(255,0,0,1); border: solid 1px rgba(255,0,0,0.8); font-size: 2em;\"><strong><span class=\"minitext\">Today is</span><br />" . TimeFormat($nowtime) . "</strong></div>"; }
										
				
				echo "<div class=\"bodybox\" $style id=\"" . $tender_id . "\"><a href=\"index2.php?page=tender_edit&tender_id=$tender_id\" style=\"float: right; margin: 0 0 5px 5px;\"><img src=\"images/button_edit.png\" alt=\"Edit Tender\" /></a><p><strong><a href=\"index2.php?page=tender_view&amp;tender_id=$tender_id\">$tender_name</a></strong>" . $tender_type . "</p>";
				echo "<p>Deadline: ". date("d M Y",$tender_date) . $deadline . "<br /><span class=\"minitext\"><a href=\"index2.php?page=tender_list&amp;tender_filter=tender_client&amp;tender_id=" . $tender_id . "\">". $tender_client . "</a></span></p>";
				
				if ($tender_responsible) { echo "<p>Responsible: " . UserDetails($tender_responsible) . "</p>"; }
				
				$time_line = $tender_date;
				
				//echo "<p>Submitted (Year): " . $successful_total_year . "/" . $submitted_total_year . "</p>";
				//echo "<p>Submitted (All): " . $successful_total . "/" . $submitted_total . "</p>";
				
				if ($previous_stage > 0) { echo "<p class=\"minitext\">Subsequent Stage: <a href=\"#" . $previous_stage . "\">" . $previous_stage_type . "</a></p>"; }
				
				if ($tender_linked > 0) { echo "<p class=\"minitext\">Previous Stage: <a href=\"#" . $tender_linked . "\">" . $subsequent_stage_type . "</a></p>"; }
				
				echo "</div>";

				}
				
				

				} else {

				echo "There are no tenders on the system.";

				}
				
				if ($submitted_total > 0 && (intval($_GET[tender_pending]) != 1)) {
				
					TenderResultSummary($successful_total,$submitted_total);
					
				}
				
				echo "</div>";
				
}

function TenderResultSummary($successful_total,$submitted_total,$year) {
	
	if ($year > 0) { $year_intro = "In " . $year . " you submitted "; } else { $year_intro = "To date, you have submitted "; }
	
					if ($submitted_total > 0 && (intval($_GET[tender_pending]) != 1)) {
				
					$success_rate = number_format ( 100 * ($successful_total / $submitted_total), 0 );
					
					echo "<div class=\"bodybox\" style=\"border: 1px solid;\"><p><strong>Statistics</strong></p><p>" . $year_intro . " $submitted_total tenders $analysis_client with a " . $success_rate . "% success rate.</p></div>";
					
					}
	
}

function TenderWords($input) {
		$input = str_replace(" & "," and ",$input);
		$keyword_array = 
			"housing standard,hca,quality standard,quality management,design standard,communit,consultant,consultation,value,communication,customer service,customer satisfaction,partnering,collaboration,experience,resident involvement,participation,environmental,structure,training,development,turnover,accreditation,achievement,award,competition,budget constraint,contract,certification,innovation,personnel,improvement,design team,approach,diverse,stakeholder,design and build,SMART,cabe,detailing,construction,kpis,scale,performance,tenures,geographical area,multi-use,mixed-use,new-build,new build,good design,special needs,complaint,sustainab,refurb,engage,planner,resident,planning,communicate,decent homes,collaborative,lifetime homes,building for life,standards,diversity,equality";
		$keyword_explode = explode(",",$keyword_array);
		$counter = 0;
		$total = count($keyword_explode);
				while ($counter < $total) {
				$keyword_explode_padded = $keyword_explode[$counter];
				$replace = "<a href=\"index2.php?page=tender_search&amp;tender_keyword=$keyword_explode[$counter]\">".$keyword_explode[$counter]."</a>";
				$input = str_replace($keyword_explode_padded,$replace,$input);
				$counter++;
				}

		echo $input;

}