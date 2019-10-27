<?php

function DrawingDetail($drawing_id) {
	
		global $conn;
	
		$drawing_id = intval($drawing_id);

		if ($drawing_id > 0) {

		$sql = "SELECT * FROM intranet_drawings, intranet_drawings_scale, intranet_drawings_paper, intranet_projects, intranet_user_details WHERE drawing_id = '$_GET[drawing_id]' AND drawing_scale = scale_id AND drawing_paper = paper_id AND proj_id = drawing_project AND drawing_author = user_id LIMIT 1";
		$result = mysql_query($sql, $conn) or die(mysql_error());


				if (mysql_num_rows($result) > 0) {
				
				$array = mysql_fetch_array($result);
				$drawing_number = $array['drawing_number'];
				$drawing_id = $array['drawing_id'];
				$scale_desc = $array['scale_desc'];
				$paper_size = $array['paper_size'];
				$drawing_title = $array['drawing_title'];
				$drawing_author = $array['drawing_author'];
				$drawing_date = $array['drawing_date'];
				$drawing_status = $array['drawing_status'];
				$proj_id = $array['proj_id'];
				$proj_num = $array['proj_num'];
				$proj_name = $array['proj_name'];
				$drawing_author = $array['user_name_first']."&nbsp;".$array['user_name_second'];
				
				echo "<h2>$drawing_number</h2>";
				
				ProjectSubMenu($proj_id,$user_usertype_current,"project_drawings",1);
				
				if (!$drawing_status) { $drawing_status = "-"; }
				
						// Drawing issue menu
							echo "<div class=\"submenu_bar\">";
							echo "<a href=\"index2.php?page=drawings_list&amp;proj_id=$proj_id\" class=\"submenu_bar\"><img src=\"images/button_list.png\" alt=\"Drawing List\" />&nbsp;Drawing List</a>";
							echo "<a href=\"index2.php?page=drawings_issue&proj_id=$proj_id\" class=\"submenu_bar\"><img src=\"images/button_list.png\" alt=\"Issue Drawings\" />&nbsp;Issue Drawings</a>";
							echo "<a href=\"index2.php?page=drawings_edit&amp;drawing_id=$drawing_id&amp;proj_id=$proj_id&amp;drawing_edit=yes\" class=\"submenu_bar\"><img src=\"images/button_edit.png\" alt=\"Edit Drawing\" />&nbsp;Edit Drawing</a>";
							echo "<a href=\"index2.php?page=drawings_revision_edit&amp;drawing_id=$drawing_id&amp;proj_id=$proj_id\" class=\"submenu_bar\"><img src=\"images/button_new.png\" alt=\"Add new revision\" />&nbsp;Add new revision</a>";
							
							// Allow this drawing to be deleted if it has not already been issued (in which case, it's too late)
							
							$sql_drawing_delete = "SELECT issue_id FROM intranet_drawings_issued WHERE issue_drawing = $drawing_id";
							$result_drawing_delete = mysql_query($sql_drawing_delete, $conn) or die(mysql_error());
							$drawing_issue_count = mysql_num_rows($result_drawing_delete);
							if ($drawing_issue_count == 0) {
							
								echo "<a href=\"index2.php?page=drawings_list&amp;drawing_id=$drawing_id&amp;proj_id=$proj_id&amp;action=drawing_delete\" class=\"submenu_bar\"  onClick=\"javascript:return confirm('Are you sure you want to delete this drawing? Deleted drawings (and any revisions) will be permanently deleted and cannot be recovered. There are currently $drawing_count revisions of this drawing on the system.')\"><img src=\"images/button_delete.png\" alt=\"Delete Drawing\" />&nbsp;Delete Drawing</a>";
							
							}
							
							
					echo "</div>";
				
				
				
				echo "<h3>Drawing Information</h3>";
				

				echo "<table summary=\"Lists the details for drawing $drawing_number\">";
				
				echo "<tr><td style=\"width: 25%;\"><strong>Project</strong></td><td>$proj_num $proj_name</td></tr>";
				
				echo "<tr><td><strong>Drawing Number</strong></td><td>$drawing_number";
						if ($drawing_author == $_COOKIE[user] OR $user_usertype_current > 1) {
						echo "&nbsp;<a href=\"index2.php?page=drawings_edit&amp;drawing_id=$drawing_id&amp;proj_id=$proj_id&amp;drawing_edit=yes\"><img src=\"images/button_edit.png\" alt=\"Edit this drawing\" /></a>";
				}
				echo "</td></tr>";
				
				echo "<tr><td><strong>Title</strong></td><td>".nl2br($drawing_title)."</td></tr>";
				
				echo "<tr><td><strong>Status</strong></td><td>$drawing_status</td></tr>";
				
				echo "<tr><td><strong>Scale</strong></td><td>$scale_desc</td></tr>";
				
				echo "<tr><td><strong>Paper</strong></td><td>$paper_size</td></tr>";
				
				echo "<tr><td><strong>Author</strong></td><td>$drawing_author</td></tr>";
				
				echo "<tr><td><strong>Date</strong></td><td>" . TimeFormat($drawing_date) . "</td></tr>";

				echo "</table>";
				

				
				
				echo "<h3>Revision History</h3>";
				
				
				$sql_rev = "SELECT * FROM intranet_drawings_revision, intranet_user_details WHERE revision_drawing = '$_GET[drawing_id]' AND revision_author = user_id ORDER BY revision_letter DESC";
				$result_rev = mysql_query($sql_rev, $conn) or die(mysql_error());
				
				if (mysql_num_rows($result_rev) > 0) {
					
					

				echo "<table desc=\"Revision list for drawing $drawing_number\">
		<tr><th>Rev.</th><th>Date</th><th>Description</th><th>Author</th></tr>";
				
				while ($array_rev = mysql_fetch_array($result_rev)) {
				$revision_id = $array_rev['revision_id'];
				$revision_letter = strtoupper($array_rev['revision_letter']);
				$revision_desc = nl2br($array_rev['revision_desc']);
				$revision_time = $array_rev['revision_date'];
				$revision_date = TimeFormat($revision_time);
				$revision_author = $array_rev['revision_author'];
				$revision_author_name = $array_rev['user_name_first']."&nbsp;".$array_rev['user_name_second'];
				
				echo "<tr><td>$revision_letter";
				
				if ($revision_author == $_COOKIE[user] OR $user_usertype_current > 1) {
						echo "&nbsp;<a href=\"index2.php?page=drawings_revision_edit&amp;drawing_id=$drawing_id&amp;revision_id=$revision_id\"><img src=\"images/button_edit.png\" alt=\"Edit this revision\" /></a>";
				}
				
				
				echo "</td><td><a href=\"index2.php?page=datebook_view_day&amp;time=$revision_time\">$revision_date</a></td><td>$revision_desc</td><td>$revision_author_name</td></tr>";
				
				}
				
				print "</table>";
				
				} else {
				
				echo "<p>There are no revisions for this drawing.</p>";
				
				}

				// Drawing Issues
				
				
				
				//$sql_issued = "SELECT * FROM intranet_drawings_issued, intranet_drawings_issued_set, intranet_drawings LEFT JOIN intranet_drawings_revision ON revision_drawing = drawing_id WHERE issue_drawing = $drawing_id AND set_id = issue_set AND drawing_id = issue_drawing ORDER BY set_date DESC";
				
				$sql_issued = "SELECT * FROM intranet_drawings_issued_set, intranet_drawings, intranet_drawings_issued LEFT JOIN intranet_drawings_revision ON revision_id = issue_revision WHERE drawing_id = $drawing_id AND set_id = issue_set AND drawing_id = issue_drawing GROUP BY set_id ORDER BY set_date DESC, issue_revision DESC, set_id DESC";
				
				$result_issued = mysql_query($sql_issued, $conn) or die(mysql_error());
				
				echo "<h3>Drawing Issues</h3>";
				
				
				
				if (mysql_num_rows($result_issued) > 0) {
					
					echo "<table>";
					
					echo "<tr><th>Issue Date</th><th>Issue Set</th><th>Revision</th><th colspan=\"2\">Issue Status</th></tr>";
					
					while ($array_issued = mysql_fetch_array($result_issued)) {
					
						$set_date = $array_issued['set_date'];
						$revision_letter = strtoupper($array_issued['revision_letter']);
						$issue_set = $array_issued['issue_set'];
						$set_reason = $array_issued['set_reason'];
						$set_id = $array_issued['set_id'];
						
						if ($revision_letter == NULL) { $revision_letter = "-"; }
						
							echo "<tr><td><a href=\"index2.php?page=datebook_view_day&amp;timestamp=$set_date\">" . TimeFormat($set_date) . "</a></td><td><a href=\"index2.php?page=drawings_issue_list&amp;set_id=$issue_set&amp;proj_id=$proj_id\">$set_id</a></td><td>$revision_letter</td><td><a href=\"index2.php?page=drawings_issue_list&amp;set_id=$issue_set&amp;proj_id=$proj_id\">$set_reason</a></td><td style=\"width: 20px;\">&nbsp;<a href=\"pdf_drawing_issue.php?issue_set=$issue_set&amp;proj_id=$proj_id\"><img src=\"images/button_pdf.png\" alt=\"PDF drawing issue sheet\" /></a></td></tr>";
					
							}
							
					echo "</table>";
					
				} else { echo "<p>This drawing has not been issued.</p>"; }  
					
				
				
				
						// Drawing issue history
						
						
						/* $sql_history = "SELECT * FROM intranet_drawings_issued_set, intranet_user_details, intranet_drawings_issued LEFT JOIN intranet_drawings_revision ON revision_id = issue_revision WHERE issue_set = set_id AND user_id = set_user AND issue_drawing = $_GET[drawing_id] ORDER BY set_date DESC";
						$result_history = mysql_query($sql_history, $conn) or die(mysql_error());
					
						echo "<h2>Issue History</h2>";
						
						if (mysql_num_rows($result_history) > 0) {
							
							
								
								
								echo "<table desc=\"Issue history for drawing $drawing_number\"><tr><th>Date</th><th>Revision</th><th>Reason</th><th>Issued by</th></tr>";
								
								
						
								while ($array_history = mysql_fetch_array($result_history)) {
									
									if ( $array_history['revision_id'] > 0) { $revision_letter = strtoupper ($array_history['revision_letter'] ); } else { $revision_letter = "-"; }
									
									echo "<tr><td>" . TimeFormat($array_history['set_date']) . "</td><td>" . $revision_letter . "</td><td><a href=\"index2.php?page=drawings_issue_list&amp;set_id=" . $array_history['set_id'] . "&amp;proj_id=" . $array_history['issue_project'] . "\">" . $array_history['set_reason'] . "</a></td><td>" . $array_history['user_initials'] . "</td></tr>";
									
								}
							
								
								echo "</table>";
						
						
						} else { echo "<p>This drawing has not been issued.</p>"; } */
				
				

				} else {

				echo "<p>This drawing does not exist.</p>";

				}
			
		} else {

		echo "<p>No project selected.</p>";

		}

}
			
function DrawingFilterOLD($page, $proj_id) {
	
				$drawing_class = $_POST[drawing_class];
				$drawing_type = $_POST[drawing_type];
				echo "<div><h3>Filter</h3>";
				echo "<form method=\"post\" action=\"index2.php?page=" . $page. "&amp;proj_id=" . $proj_id . "&amp;drawing_class=$drawing_class&amp;drawing_type=$drawing_type\" >";
				$array_class_1 = array("","SK","PL","TD","CN","CT","FD","DR","M3","PP","SH","SP");
				$array_class_2 = array("- All -","Sketch","Planning","Tender","Contract","Construction","Final Design","2d Drawing", "3d Model File","Presentation","Schedule","Specification");
				ClassList($array_class_1,$array_class_2,"drawing_class");
				echo "&nbsp;";
				$array_class_1 = array("","SV","ST","GA","AS","DE","DOC","SCH");
				$array_class_2 = array("- All -","Survey","Site Location","General Arrangement","Assembly","Detail","Document","Schedule");
				ClassList($array_class_1,$array_class_2,"drawing_type");
				echo "</form><p>Please note that by using this filter you will clear any entries you may have previously entered below.</p></div>";
	
}

function DrawingFilter($page, $proj_id) {
	
				if ($_POST[drawing_class]) { $drawing_class = $_POST[drawing_class]; }
				if ($_POST[drawing_type]) { $drawing_type = $_POST[drawing_type]; }
				
				$drawing_class = trim(trim($drawing_class,"-"));
				$drawing_type = trim(trim($drawing_type,"-"));
				
				echo "<div><h3>Filter</h3>";
				echo "<form method=\"post\" action=\"index2.php?page=" . $page. "&amp;proj_id=" . $proj_id . "\">";
				echo "<div style=\"float: left; margin-right: 15px;\"><span class=\"minitext\">Filter 1</span><br /><input type=\"text\" name=\"drawing_class\" value=\"$drawing_class\" /></div>";
				echo "<div style=\"float: left; margin-right: 15px;\"><span class=\"minitext\">Filter 2</span><br /><input type=\"text\" name=\"drawing_type\" value=\"$drawing_type\" /></div>";
				echo "&nbsp;";
				echo "<div style=\"float: left; margin-right: 15px;\"><br /><input type=\"submit\" /></div>";
				echo "</form></div><p>Please note that by using this filter you will clear any entries you may have previously entered below.</p>";
	
}
	
function DrawingStatusDropdown ($current_status,$variable_name) {
	
	$drawing_status_array = array("","S0","S1","S2","S3","S4");
	sort($drawing_status_array);


	echo "<select name=\"$variable_name\">";
			foreach ($drawing_status_array AS $drawing_status_list) {
			if ($drawing_status_list == $current_status) { $select = "selected=\"selected\""; } else { unset($select); }
			echo "<option value=\"$drawing_status_list\" $select>$drawing_status_list</option>";
		}
	echo "</select>";

	
}

function DrawingIssueFormat() {
	
	
						
						echo "<fieldset><legend>Issue Details</legend>";
						$issue_reason_list = array("Draft","Comment","Planning","Building Control","Information","Tender","Contract","Preliminary","Coordination","Construction","Client Issue","Final Design","As Instructed");
						echo "<p>Reason for Issue<br /><select name=\"issue_reason\">";
						$count = 0;
						$total = count($issue_reason_list);
						while ($count < $total) {		
							echo "<option value=\"$issue_reason_list[$count]\">$issue_reason_list[$count]</option>";
							$count++;
						}
						echo "</select></fieldset>";
						
						echo "<fieldset><legend>Issue Method</legend>";
						
						$issue_method_list = array("Email","CD / USB", "Post", "Basecamp", "Woobius", "Planning Portal", "Google Drive","Dropbox","FTP","4Projects","WeTransfer","By Hand","Sharefile", "Sharepoint");
						sort($issue_method_list);
						
						$issue_format_list = array("PDF", "DGN", "DWG", "DXF", "Hard Copy","RVT","SKT");
						sort($issue_method_list);
						
						if (count($issue_method_list) > count($issue_format_list)) { $total = count($issue_method_list); } else { $total = count($issue_format_list); }
						
						$count = 0;
						
						echo "<table style=\"width: 50%;\"><tr><th colspan=\"2\">Issue Method</th><th colspan=\"2\">Issue Format</th></tr>";
						$total = count($issue_method_list);
						while ($count < $total) {		
							echo "<tr><td style=\"width: 20px; text-align: center\">";
							
							if (count($issue_method_list) > $count) {
								echo "<input type=\"radio\" name=\"issue_method\" id=\"$issue_method_list[$count]\" value=\"$issue_method_list[$count]\" required=\"required\" />";
							}
							
							echo "<td>$issue_method_list[$count]</td><td style=\"width: 20px; text-align: center\">";
							
							if (count($issue_format_list) > $count) {
								echo "<input type=\"radio\" name=\"issue_format\" id=\"$issue_format_list[$count]\" value=\"$issue_format_list[$count]\" required=\"required\" />";
								
							}
							
							echo "<td>$issue_format_list[$count]</td></tr>";
							
							

							$count++;
						}
						
						echo "</table></fieldset>";
						
	
}

function DrawingSelectUser($user_id) {
	
	$user_id - intval($user_id);
	
	echo "<fieldset><legend>Checked By</legend>";
						
		global $conn;
						
						$sql_checked = "SELECT user_name_first, user_name_second, user_id FROM intranet_user_details WHERE user_id != " . $user_id . "  AND user_active = 1 ORDER BY user_name_second";

						$result_checked = mysql_query($sql_checked, $conn) or die(mysql_error());
						
						echo "<select name=\"set_checked\">";
						
						echo "<option value=\"\">-- None --</option>";
						
						while ($array_checked = mysql_fetch_array($result_checked)) {
						

							echo "<option value=\"" . $array_checked['user_id'] . "\"/>" . $array_checked['user_name_first'] . "&nbsp;" . $array_checked['user_name_second'] . "</option>";
						
						
						}
						
	echo "</select></fieldset>";
	
}

function DrawingIssuedTo($proj_id) {
	
	global $conn;
	
	$proj_id = intval($proj_id);
	
					// Drawing issued to
						
						echo "<fieldset><legend>Issued To</legend>";
						
						$sql_issued_to = "
						SELECT * FROM contacts_disciplinelist, contacts_contactlist, intranet_contacts_project
						LEFT JOIN contacts_companylist ON company_id = contact_proj_company
						WHERE contact_proj_contact = contact_id
						AND contact_proj_project = $proj_id
						AND contact_proj_role = discipline_id
						AND contact_proj_contact = contacts_contactlist.contact_id
						ORDER BY discipline_order, contact_namesecond";
						$result_issued_to = mysql_query($sql_issued_to, $conn) or die(mysql_error());
					
					echo "<table summary=\"Lists the contacts related to this project\">";
					
					$count = 0;
					
					while ($array_issued_to = mysql_fetch_array($result_issued_to)) {	
						
					$contact_id = $array_issued_to['contact_id'];
					$contact_namefirst = $array_issued_to['contact_namefirst'];
					$contact_namesecond = $array_issued_to['contact_namesecond'];
					$company_name = $array_issued_to['company_name'];
					$company_id = $array_issued_to['company_id'];
					$discipline_name = $array_issued_to['discipline_name'];
					
						echo "<tr><td><input type=\"checkbox\" name=\"issue_to[$count]\" value=\"yes\" /><input type=\"hidden\" name=\"contact_id[$count]\" value=\"$contact_id\" /></td><td>$contact_namefirst $contact_namesecond</td><td>$company_name<input type=\"hidden\" name=\"company_id[$count]\" value=\"$company_id\" /></td><td>$discipline_name</td></tr>\n";
						
						$count++;
					
					}
					
					echo "</table></fieldset>";
	
}

function ClassList2($array_class_1,$array_class_2,$type) {
		GLOBAL $proj_id;
		GLOBAL $drawing_class;
		GLOBAL $drawing_type;

		echo "<select name=\"$type\" onchange=\"this.form.submit()\">";
		$array_class_count = 0;
		foreach ($array_class_1 AS $class) {
			echo "<option value=\"$class\"";
			
			if ($drawing_class == $class && $type == "drawing_class" ) { echo " selected=\"selected\" "; }
			elseif ($drawing_type == $class && $type == "drawing_type" ) { echo " selected=\"selected\" "; }
			
			echo ">";		
			echo $array_class_2[$array_class_count];
			echo "</option>";
			$array_class_count++;
			}
			echo "</select>";
	
}
		
function DrawingIssueSetup($proj_id) {
	
	global $conn;
	
			$proj_id = intval($proj_id);
	

			if ($proj_id > 0) {


				if ($_GET[drawing_packages] != NULL) {
					$drawing_packages = CleanUp($_GET[drawing_packages]);
					
					// Not sure why this statement doesn't work...
						$sql_drawing_packages = " AND drawing_packages LIKE '%" . $drawing_packages ."%'";
					} else {
						unset($sql_drawing_packages);
					}
									
					
					$drawing_class = $_POST[drawing_class];
					$drawing_type = $_POST[drawing_type];
					
					DrawingFilter($page, $proj_id);
					
									if ($_GET[drawing_class]) { $drawing_class = $_GET[drawing_class]; } elseif ($_POST[drawing_class]) { $drawing_class = $_POST[drawing_class]; }
									if ($_GET[drawing_type]) { $drawing_type = $_GET[drawing_type]; } elseif ($_POST[drawing_type]) { $drawing_type = $_POST[drawing_type]; }
									
									if ($drawing_class != NULL) { $drawing_class = " AND drawing_number LIKE '%-" . $drawing_class . "-%' "; } else { unset($drawing_class); }
									if ($drawing_type != NULL) { $drawing_type = " AND drawing_number LIKE '%-" . $drawing_type . "-%' "; } else { unset($drawing_type); }

						$sql = "SELECT * FROM intranet_drawings, intranet_drawings_scale, intranet_drawings_paper, intranet_projects WHERE proj_id = '$proj_id' AND drawing_project = '$_GET[proj_id]' AND drawing_scale = scale_id AND drawing_paper = paper_id " . $sql_drawing_packages . " $drawing_class $drawing_type ORDER BY drawing_number";
						$result = mysql_query($sql, $conn) or die(mysql_error());
												
						echo "<fieldset><legend>Drawings to Issue</legend>";


						if (mysql_num_rows($result) > 0) {
						
						echo "<form action=\"index2.php?page=drawings_list&amp;proj_id=$_GET[proj_id]\" method=\"post\">";

						echo "<table summary=\"Lists all of the drawings for the project\">";
						echo "<tr><td><strong>Drawing Number</strong></td><td><strong>Title</strong></td><td><strong>Rev.</strong></td><td><strong>Status</strong></td><td><strong>Issue</strong></td></tr>";
						
						$counter = 0;

						while ($array = mysql_fetch_array($result)) {
						$drawing_id = $array['drawing_id'];
						$drawing_number = $array['drawing_number'];
						$scale_desc = $array['scale_desc'];
						$paper_size = $array['paper_size'];
						$drawing_title = $array['drawing_title'];
						$drawing_author = $array['drawing_author'];
						$drawing_status = $array['drawing_status'];

						echo "<tr><td><a href=\"index2.php?page=drawings_detailed&amp;drawing_id=$drawing_id\">$drawing_number</a>";

										echo "</td><td>".nl2br($drawing_title)."</td><td>\n";
										
										$sql_2 = "SELECT * FROM intranet_drawings_revision WHERE revision_drawing = '$drawing_id' order by revision_letter DESC";
										$result_2 = mysql_query($sql_2, $conn) or die(mysql_error());
										if (mysql_num_rows($result_2) > 0) {
										echo "<select name=\"revision_id[$counter]\">";
										while ($array_2 = mysql_fetch_array($result_2)) {
											$revision_id = $array_2['revision_id'];
											$revision_letter = $array_2['revision_letter'];
											$revision_date = $array_2['revision_date'];
												echo "<option value=\"$revision_id\">" . strtoupper($revision_letter) . " - ".TimeFormat($revision_date)."</option>";
										}
										echo "<option value=\"\">- No Revision -</option>\n";
										echo "</select>";
										} else {
											echo "- No Revision -";
										}
										
						
						
						echo "<td>";
						DrawingStatusDropdown ($drawing_status,"drawing_status[$counter]");
						echo "</td><td><input type=\"hidden\" value=\"$drawing_id\" name=\"drawing_id[$counter]\" /><input type=\"checkbox\" value=\"yes\" name=\"drawing_issued[$counter]\" /></td>";


						echo "</tr>\n";
						
						$counter++;

						}

						echo "</table></fieldset>";
						
						
					DrawingIssuedTo($proj_id);
						
						
					DrawingIssueFormat();
						
						
						// Add dropdown to select user that is checking drawings (excludes the current user)
						
						DrawingSelectUser($_COOKIE['user']);

						
						echo "<fieldset><legend>Comment</legend><textarea name=\"issue_comment\" cols=\"36\" rows=\"6\"></textarea></fieldset>";
						
						echo "<fieldset><legend>Issue Date</legend>";
						
						$issue_date_value = date("Y",time()) . "-" . date("m",time()) . "-" . date("d",time());
						
						echo "<input type=\"date\" value=\"$issue_date_value\" name=\"set_date\" /></fieldset>";
						
					
						echo "<p><input type=\"submit\" value=\"Issue Drawings\" /></p>";
						
						
						echo "<input type=\"hidden\" name=\"action\" value=\"drawing_issue\" /><input type=\"hidden\" name=\"issue_date\" value=\"".time()."\" /><input type=\"hidden\" value=\"$proj_id\" name=\"issue_project\" />";
						
						echo "</form>";

						} else {

						echo "<p>There are no drawings for this project.</p>";

						}
					
				} else {

					echo "<p>No project selected.</p>";

				}

}
