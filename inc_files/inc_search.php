<?php


function SearchTerms($search_text,$search_field) {
		$counter = 0;
		$max_count = count($search_text);
		while($counter < $max_count) {
		if ($counter > 0) { $searching_blog = $searching_blog." AND " . $search_field . " LIKE "; }
		$searching_blog = $searching_blog." LOWER ('%".$search_text[$counter]."%' )";
		$counter++;
		}
		$searching_blog = "LOWER (" . $search_field . ") LIKE ".$searching_blog;
		return($searching_blog);
}

function SearchFeeStage($keywords_array) {
	
	global $conn;

		$sql = "SELECT * FROM intranet_timesheet_fees LEFT JOIN intranet_projects ON proj_id = ts_fee_project WHERE " . SearchTerms($keywords_array, "ts_fee_text") . " OR " . SearchTerms($keywords_array, "ts_fee_comment")."  ORDER BY proj_num DESC, ts_fee_stage";
		
		
		$result = mysql_query($sql, $conn) or die(mysql_error());
			if (mysql_num_rows($result) > 0) {
				
				
					echo "<h3>Fee Stages</h3>";
					echo "<table>";
				
					while ($array = mysql_fetch_array($result)) {
						
					$search_results = $search_results + mysql_num_rows($result);

					echo "
					<tr><td style=\"width: 30%;\"><a href=\"index2.php?page=project_view&amp;proj_id=" . $array['proj_id'] . "\">" . $array['proj_num'] . " " . $array['proj_name'] . "</a></td><td>" . $array['ts_fee_text'] . "</td>";
					echo "</tr>";
			}
			
			echo "</table>";
		}

}


echo "<h1>Search Results</h1>";

// Construct search terms

if ($_GET[keywords]) { $keywords = $_GET[keywords]; } elseif ($_POST[keywords]) { $keywords = $_POST[keywords]; }

if ($keywords != NULL && $_POST[search_phrase] != "yes") {$keywords = $keywords; $keywords_array = explode(" ", $keywords); }
elseif ($keywords != NULL && $_POST[search_phrase] != "yes") { $keywords = CleanUp($keywords); $keywords_array = explode(" ", $keywords); }
else { $keywords_array = array(); $keywords_array[] = $keywords;  }

if (strlen($keywords) > 2 ) {	

// Begin printing the results tables

echo "<h2>Search for : $keywords</h2>";

SearchPanel($user_usertype_current,"search_02");

echo "<div class=\"page\">";

// Projects


$sql = "SELECT * FROM intranet_projects WHERE ".SearchTerms($keywords_array, "proj_num")." OR ".SearchTerms($keywords_array, "proj_name")." OR ".SearchTerms($keywords_array, "proj_address_1")." OR ".SearchTerms($keywords_array, "proj_address_2")." OR ".SearchTerms($keywords_array, "proj_address_3")." ORDER BY proj_num DESC";
$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		
		//echo "<tr><td colspan=\"2\">$sql</td></tr>";
		//echo "<tr><td colspan=\"2\">" . SearchTerms($keywords_array, "contact_namesecond") . "</td></tr>";
		
			echo "<h3>Projects</h3>";
			echo "<table>";
		
			while ($array = mysql_fetch_array($result)) {
				
			$search_results = $search_results + mysql_num_rows($result);

			echo "
			<tr><td style=\"width: 30%;\"><a href=\"index2.php?page=project_view&amp;proj_id=" . $array['proj_id'] . "\">" . $array['proj_num'] . " " . $array['proj_name'] . "</a></td>";
			echo "</tr>";
	}
	
	echo "</table>";
}

// Fee Stage

SearchFeeStage($keywords_array);


// Journal Entries

if ($_POST[tender_search] != "yes") {
	


$sql = "SELECT blog_id, blog_title, blog_date FROM intranet_projects_blog WHERE ".SearchTerms($keywords_array, "blog_text")." OR ".SearchTerms($keywords_array, "blog_title")." AND blog_view != 1 AND (blog_access <= $user_usertype_current OR blog_access IS NULL) ORDER BY blog_date DESC";
$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		
		$search_results = $search_results + mysql_num_rows($result);
		
			echo "<h3>Journal Entries</h2>";
	
			echo "<table>";

			while ($array = mysql_fetch_array($result)) {
			$blog_id = $array['blog_id'];
			$blog_title = $array['blog_title'];	
			$blog_date = $array['blog_date'];
			echo "<tr><td style=\"width: 30%;\"><a href=\"index2.php?page=datebook_view_day&amp;time=$blog_date\">".TimeFormat($blog_date)."</a></td><td style=\"width: 75%;\"><a href=\"index2.php?page=project_blog_view&amp;blog_id=$blog_id\">$blog_title</a></td></tr>";
	}
	
	echo "</table>";
}





// Contacts


$sql = "SELECT contact_id, contact_namefirst, contact_namesecond, contact_company FROM contacts_contactlist WHERE ".SearchTerms($keywords_array, "contact_namefirst")." OR ".SearchTerms($keywords_array, "contact_namesecond")." OR ".SearchTerms($keywords_array, "contact_reference")." ORDER BY contact_namesecond";
$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		
		//echo "<tr><td colspan=\"2\">$sql</td></tr>";
		//echo "<tr><td colspan=\"2\">" . SearchTerms($keywords_array, "contact_namesecond") . "</td></tr>";
		
			echo "<h3>Contacts</h3>";
			echo "<table>";
		
			while ($array = mysql_fetch_array($result)) {
				
			$search_results = $search_results + mysql_num_rows($result);
				
			$contact_id = $array['contact_id'];
			$contact_namefirst = $array['contact_namefirst'];
			$contact_namesecond = $array['contact_namesecond'];
			$contact_company = $array['contact_company'];
			echo "
			<tr><td style=\"width: 30%;\"";
			if ($contact_company == NULL OR $contact_company == 0) { echo " colspan=\"2\" "; }
			echo "><a href=\"index2.php?page=contacts_view_detailed&amp;contact_id=$contact_id\">$contact_namefirst&nbsp;$contact_namesecond</a></td>";
			if ($contact_company > 0) { echo "<td>";$id = $contact_company; include("dropdowns/inc_data_contact_company.php"); echo "</td>"; }
			echo "</tr>";
	}
}

echo "</table>";

// Company Entries



$sql = "SELECT company_id, company_name, company_postcode FROM contacts_companylist WHERE ".SearchTerms($keywords_array, "company_name")." OR ".SearchTerms($keywords_array, "company_address")." OR ".SearchTerms($keywords_array, "company_web")." OR ".SearchTerms($keywords_array, "company_notes")." OR ".SearchTerms($keywords_array, "company_web")." OR ".SearchTerms($keywords_array, "company_notes")." ORDER BY company_name";
$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		
		$search_results = $search_results + mysql_num_rows($result);
		
		echo "<h3>Companies</h3>";
		echo "<table>";

			while ($array = mysql_fetch_array($result)) {
			$company_id = $array['company_id'];
			$company_name = $array['company_name'];
			$company_postcode = $array['company_postcode'];
			
			if ($company_postcode) {
				echo "<tr><td colspan=\"2\" style=\"width: 30%;\">";
				echo "<a href=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">$company_name</a>";
				echo "</td>";
				echo "<td>$company_postcode</td>";
				echo "</tr>";			
			}	else	{
				echo "<tr><td colspan=\"3\">";
				echo "<a href=\"index2.php?page=contacts_company_view&amp;company_id=$company_id\">$company_name</a>";
				echo "</td></tr>";
			}
	}
}

echo "</table>";


$sql = "SELECT * FROM intranet_qms WHERE " . SearchTerms($keywords_array, "qms_text") . "ORDER BY qms_toc1, qms_toc2, qms_toc3, qms_toc4";
$result = mysql_query($sql, $conn) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
		
		$search_results = $search_results + mysql_num_rows($result);

		echo "<h3>Quality Management System</h3>";
		echo "<table>";

			while ($array = mysql_fetch_array($result)) {
			$qms_id = $array['qms_id'];
			$qms_text = $array['qms_text'];
			$qms_toc1 = $array['qms_toc1'];
			$qms_toc2 = $array['qms_toc2'];
			$qms_toc3 = $array['qms_toc3'];
			$qms_toc4 = $array['qms_toc4'];

			echo "<tr><td style=\"width: 30%;\">$qms_toc1.$qms_toc2.$qms_toc3.$qms_toc4</td><td><a href=\"index2.php?page=qms_view&amp;s1=$qms_toc1&amp;s2=$qms_toc2&amp;qms_id=$qms_id#$qms_id\">$qms_text</a></td></tr>";
	}
}

echo "</table>";

// Manual

function SearchManual($keywords_array) {
	
	global $conn;

		
		
			$sql = "SELECT manual_id, manual_title FROM intranet_stage_manual WHERE " . SearchTerms($keywords_array, "manual_title") . " OR ". SearchTerms($keywords_array, "manual_text") . " ORDER BY manual_title";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			
			
			if (mysql_num_rows($result) > 0) {
				
				echo "<h3>Office Manual</h3>";
				echo "<table>";
				while ($array = mysql_fetch_array($result)) { echo "<tr><td><a href=\"index2.php?page=manual_page&amp;manual_id=" . $array['manual_id'] . "\">" . $array['manual_title'] . "</a></td></tr>"; }
				echo "</table>";
			}

		return mysql_num_rows($result);

}

$search_results = $search_results + SearchManual($keywords_array);

// Media Library

function SearchMedia($keywords_array) {
	
	global $conn;


			$sql = "SELECT * FROM intranet_media WHERE " . SearchTerms($keywords_array, "media_title") . " OR ". SearchTerms($keywords_array, "media_description") . " ORDER BY media_timestamp DESC";
			$result = mysql_query($sql, $conn) or die(mysql_error());
			if (mysql_num_rows($result) > 0) {
				echo "<h3>Media Library</h3>";
				echo "<table>";
				while ($array = mysql_fetch_array($result)) { echo "<tr><td style=\"width: 30%;\"><a href=\"" . $array['media_path'] . $array['media_file'] . "\">" . $array['media_title'] . "</a></td><td>" . $array['media_description'] . "</td><td style=\"text-align: right;\">" . MediaSize($array['media_size']) . "</td></tr>"; }
				echo "</table>";
			}

		return mysql_num_rows($result);

}

$search_results = $search_results + SearchMedia($keywords_array);


// Checklist



$sql = "SELECT checklist_comment, checklist_project, proj_id, proj_num, proj_name, item_name FROM intranet_project_checklist, intranet_projects, intranet_project_checklist_items WHERE checklist_project = proj_id AND checklist_item = item_id AND " . SearchTerms($keywords_array, "checklist_comment") . " GROUP BY checklist_comment ORDER BY proj_num";
$result = mysql_query($sql, $conn) or die(mysql_error());

	if (mysql_num_rows($result) > 0) {
		
		$search_results = $search_results + mysql_num_rows($result);
			echo "<h3>Project Checklists</h3>";
			echo "<table>";
			while ($array = mysql_fetch_array($result)) {
				$checklist_comment = $array['checklist_comment'];
				$checklist_project = $array['checklist_project'];
				$proj_id = $array['proj_id'];
				$proj_num = $array['proj_num'];
				$proj_name = $array['proj_name'];
				$item_name = $array['item_name'];
				echo "<tr><td style=\"width: 30%;\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">$proj_num $proj_name</a></td><td>$item_name</td><td><a href=\"index2.php?page=project_checklist&amp;proj_id=$checklist_project\">$checklist_comment</a></td></tr>";
			}
}

echo "</table>";

// Tasks



if ($user_usertype_current > 3) {

$sql = "SELECT tasklist_id, tasklist_notes, tasklist_person, tasklist_percentage, proj_id, proj_num FROM intranet_tasklist, intranet_projects WHERE tasklist_project = proj_id AND ".SearchTerms($keywords_array, "tasklist_notes")." OR tasklist_project = proj_id AND ".SearchTerms($keywords_array, "tasklist_comment")." ORDER BY tasklist_due";

} else {

$sql = "SELECT tasklist_id, tasklist_notes, tasklist_person, tasklist_percentage, proj_id, proj_num FROM intranet_tasklist, intranet_projects WHERE tasklist_person = $user_id AND ( tasklist_project = proj_id AND ".SearchTerms($keywords_array, "tasklist_notes")." ) OR ( tasklist_project = proj_id AND ".SearchTerms($keywords_array, "tasklist_comment")." ) ORDER BY tasklist_due";

}

$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		
		$search_results = $search_results + mysql_num_rows($result);
		
		echo "<h3>Tasks</h3>";

		echo "<table>";

			while ($array = mysql_fetch_array($result)) {
			$tasklist_id = $array['tasklist_id'];
			$tasklist_notes = $array['tasklist_notes'];
			$tasklist_percentage = $array['tasklist_percentage'];
			$tasklist_due = $array['tasklist_due'];
			$proj_id = $array['proj_id'];
			$proj_num = $array['proj_num'];
			echo "<tr><td style=\"width: 30%;\"><a href=\"index2.php?page=project_view&amp;proj_id=$proj_id\">";
			echo $proj_num;
			echo "</a></td><td><a href=\"index2.php?page=tasklist_detail&amp;tasklist_id=$tasklist_id\">";
			if ($tasklist_percentage == 100) { echo "<span style=\"text-decoration: line-through;\">"; }
			elseif ($tasklist_due < time()) { echo "<span style=\"background-color: #$settings_alertcolor;\">"; }
			echo $tasklist_notes;
			if ($tasklist_percentage == 100) { echo "</span>"; }
			elseif ($tasklist_due < time()) { echo "</span>"; }
			echo "</a></td></tr>";
	}
}

echo "</table>";


// Tender (Details only)



$sql = "SELECT * FROM intranet_tender WHERE ".SearchTerms($keywords_array, "tender_name")." OR ".SearchTerms($keywords_array, "tender_description")." ORDER BY tender_date DESC";


$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		
		$search_results = $search_results + mysql_num_rows($result);

			echo "<h3>Tenders</h3>";

			echo "<table>";
			while ($array = mysql_fetch_array($result)) {
			$tender_id = $array['tender_id'];
			$tender_name = $array['tender_name'];
			$tender_client = $array['tender_client'];
			$tender_date = $array['tender_date'];
				echo "<tr><td style=\"width: 30%;\"><a href=\"index2.php?page=tender_view&amp;tender_id=$tender_id\">". $tender_name ."</a></td><td>$tender_client</td><td>Deadline: ". TimeFormatDetailed ( $tender_date ) . "</td></tr>";
	}
}

echo "</table>";

// Expenses



if ($user_usertype_current > 3) {

$sql = "SELECT * FROM intranet_timesheet_expense LEFT JOIN intranet_timesheet_expense_category ON ts_expense_category = expense_cat_id WHERE ".SearchTerms($keywords_array, "ts_expense_desc")." OR ".SearchTerms($keywords_array, "ts_expense_notes")." OR ".SearchTerms($keywords_array, "ts_expense_desc")." ORDER BY ts_expense_date DESC";

} else {

$sql = "SELECT * FROM intranet_timesheet_expense LEFT JOIN intranet_timesheet_expense_category ON ts_expense_category = expense_cat_id WHERE ( ".SearchTerms($keywords_array, "ts_expense_desc")." OR ".SearchTerms($keywords_array, "ts_expense_notes")." OR ".SearchTerms($keywords_array, "ts_expense_desc")." ) AND ts_expense_user = $user_id ORDER BY ts_expense_date DESC";

}

$result = mysql_query($sql, $conn) or die(mysql_error());
	if (mysql_num_rows($result) > 0) {
		
		$search_results = $search_results + mysql_num_rows($result);
		
		echo "<h3>Expenses</h3>";

		echo "<table>";

			while ($array = mysql_fetch_array($result)) {
			$ts_expense_id = $array['ts_expense_id'];
			$ts_expense_desc = $array['ts_expense_desc'];
			$ts_expense_date = $array['ts_expense_date'];
			$ts_expense_notes = $array['ts_expense_notes'];
			$ts_expense_vat = $array['ts_expense_vat'];
			$expense_cat_clearance = $array['expense_cat_clearance'];
			if ($user_usertype_current >= $expense_cat_clearance) {
				echo "<tr><td style=\"width: 30%;\"><a href=\"index2.php?page=datebook_view_day&amp;time=$ts_expense_date\">".TimeFormat($ts_expense_date)."</a><td style=\"width: 75%;\"><a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\">$ts_expense_desc</a> [ID: $ts_expense_id]";
				if ($ts_expense_notes != NULL) { echo "<br />($ts_expense_notes)"; }
				echo "</tr>";
			}
	}
}

echo "</table>";

// Expenses (by value)

					$value_lower = floatval($_POST[keywords]) - 0.01;
					$value_upper = floatval($_POST[keywords]) + 0.01;
					
					if (floatval($value_lower) > 0) {

							if ($user_usertype_current > 3 && $value_upper ) {
							$sql = "SELECT * FROM  intranet_user_details, intranet_timesheet_expense WHERE (ts_expense_vat BETWEEN $value_lower AND $value_upper) AND ts_expense_vat > 0 AND user_id = ts_expense_user ORDER BY ts_expense_date DESC";

							// echo $sql;

							} elseif ($value_upper) {

							$sql = "SELECT * FROM intranet_user_details, intranet_timesheet_expense WHERE (ts_expense_vat BETWEEN `$value_lower` AND `$value_upper`) AND ts_expense_vat > 0 AND user_id = ts_expense_user AND ts_expense_user = $user_id  ORDER BY ts_expense_date DESC";

							}

							$result = mysql_query($sql, $conn) or die(mysql_error());
								if (mysql_num_rows($result) > 0) {
									
									$search_results = $search_results + mysql_num_rows($result);
									
										echo "<h3>Expenses with this value</h3>";
										echo "<table>";
										echo "<tr><th style=\"width: 30%;\">Date</th><th>Verified</th><th>Description</th><th>Value</th><th>User</th></tr>";
										while ($array = mysql_fetch_array($result)) {
										$ts_expense_id = $array['ts_expense_id'];
										$ts_expense_desc = $array['ts_expense_desc'];
										$ts_expense_date = $array['ts_expense_date'];
										$ts_expense_notes = $array['ts_expense_notes'];
										$ts_expense_vat = $array['ts_expense_vat'];
										$ts_expense_verified = $array['ts_expense_verified'];
										$ts_expense_p11d = $array['ts_expense_p11d'];
										if ($ts_expense_verified > 0) {
										$ts_expense_verified = "<a href=\"index2.php?page=datebook_view_day&amp;time=$ts_expense_verified\">".TimeFormat($ts_expense_verified)."</a>"; } else { $ts_expense_verified = "--"; }
										$user_initials = $array['user_initials'];
										echo "<tr><td><a href=\"index2.php?page=datebook_view_day&amp;time=$ts_expense_date\">".TimeFormat($ts_expense_date)."</a></td><td>$ts_expense_verified</td><td style=\"width: 50%;\"><a href=\"index2.php?page=timesheet_expense_view&amp;ts_expense_id=$ts_expense_id\">$ts_expense_desc</a>";
										if ($ts_expense_notes != NULL) { echo "<br />Notes: $ts_expense_notes"; }
										echo "</td><td>".MoneyFormat($ts_expense_vat)." [ID: $ts_expense_id]";
										echo "<td>" . $user_initials;
										if ($ts_expense_p11d != 0) { echo "&nbsp; [P11d]"; }
										echo "</td></tr>";
								}
								echo "</table>";
							}


					}




} else {

// Tender submissions

function SearchResultsTenders($keywords_array) {
	
			global $conn;

					$sql = "SELECT answer_id, answer_question, answer_response, answer_tender_id, answer_ref, tender_name, tender_date FROM intranet_tender_answers, intranet_tender WHERE ( ".SearchTerms($keywords_array, "answer_response" ) . " AND tender_id = answer_tender_id ) OR ( " . SearchTerms($keywords_array, "answer_question" ) . " AND tender_id = answer_tender_id ) AND answer_complete = 1 ORDER BY tender_date DESC, tender_name LIMIT 20 ";
					$result = mysql_query($sql, $conn) or die(mysql_error());
					$results = mysql_num_rows($result);
							if ($results > 0) {
								
								echo "<table>";
								echo "<h3>Tender submissions (only answers marked as complete are shown below)</h3>";
											while ($array = mysql_fetch_array($result)) {
											$answer_id = $array['answer_id'];
											$answer_response = strip_tags($array['answer_response'],"<p>,<br>");
											foreach ($keywords_array AS $keywords_replace) { $keywords_replace_highlight = "<span style=\"background-color: yellow;\">" . $keywords_replace . "</span>"; $answer_response = str_replace($keywords_replace,$keywords_replace_highlight,$answer_response); }
											$answer_tender_id = $array['answer_tender_id'];
											$answer_question = strip_tags($array['answer_question'],"<p><br><ul><li>");
											$answer_question = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $answer_question);
											$answer_question = preg_replace('/(<[^>]+) class=".*?"/i', '$1', $answer_question);
											$answer_question = preg_replace('/(<[^>]+) align=".*?"/i', '$1', $answer_question);
											$answer_ref = $array['answer_ref'];
											$tender_name = $array['tender_name'];
											$tender_date = $array['tender_date'];
											echo "<tr><td style=\"width: 50%;\">$answer_question</td><td>";
											echo $answer_response . "...</a><br /><a href=\"index2.php?page=tender_view&amp;tender_id=$answer_tender_id&amp;answer_id=$answer_id\"><span class=\"minitext\">From $tender_name, " . TimeFormat($tender_date) . ", question $answer_ref</span></a>";
											echo "</td></tr>";
											}

								
						}

					echo "</table>";

					return $results;

	}

	$search_results = SearchResultsTenders($keywords_array);

	
					
}



} elseif ($keywords != NULL) {

	echo "<p>Invalid Search Term</p>";

}

//if (intval($search_results) > 0 ) { echo "<p>" . intval($search_results) . " results found.</p>";} else { echo "<p>No results found.</p>"; }
if (intval($search_results) == 0 ) { echo "<p>No results found.</p>"; }

echo "</div>";