<?php

if ($_GET[proj_id] != NULL) { $proj_id = intval($_GET[proj_id]); }

SearchPanel();


if ($proj_id != NULL && $module_phonemessages == 1) {
	
	// General drawing actions
	
	$array_pages = array("index2.php?page=drawings_list&amp;proj_id=$proj_id","index2.php?page=drawings_edit&amp;proj_id=$proj_id","index2.php?page=drawings_issue&amp;proj_id=$proj_id");
	$array_title = array("Drawing List","Add New Drawing","Drawing Issue");
	$array_images = array("");
	$array_access = array(2,2);

	SideMenu ("Drawing Actions", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);

	// Drawing Issues
	
	unset($array_pages);
	unset($array_title);
	unset($array_access);
	unset($array_images);
	
	$sql_issue_list = "SELECT set_id, set_date, set_reason FROM intranet_drawings_issued_set WHERE set_project = $proj_id order by set_date DESC, set_timestamp DESC LIMIT 20";
	
	$result_issue_list = mysql_query($sql_issue_list, $conn) or die(mysql_error());
	
		while ($array_issue_list = mysql_fetch_array($result_issue_list)) {
			$set_id = $array_issue_list['set_id'];
			$set_reason = $array_issue_list['set_reason'];
			$set_date = TimeFormat($array_issue_list['set_date']);
			
			if ($set_id != $_GET[set_id]) { 			
				$array_pages[] = "index2.php?page=drawings_issue_list&amp;set_id=$set_id&amp;proj_id=$proj_id";
				$array_title[] = $set_date . " - " . $set_reason;
			} else {
				$array_pages[] = "";
				$array_title[] = "<strong>" . $set_date . " - " . $set_reason . "</strong>";
			}
			
		}
		
	
	
	SideMenu ("Drawing Issues", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
	
	
	
	// Drawing List
	
	
	unset($array_pages);
	unset($array_title);
	unset($array_access);
	unset($array_images);
	


	if ($_GET[page] != "drawings_list") {
				
				$sql_drawing_list = "SELECT drawing_number, drawing_project, drawing_id, revision_letter FROM intranet_drawings LEFT JOIN intranet_drawings_revision ON revision_drawing = drawing_id WHERE drawing_project = $proj_id order by drawing_number, revision_letter DESC";
				$result_drawing_list = mysql_query($sql_drawing_list, $conn) or die(mysql_error());
				
				$current_drawing = 0;
				
					while ($array_drawing_list = mysql_fetch_array($result_drawing_list)) {
						$drawing_id = $array_drawing_list['drawing_id'];
						$drawing_number = $array_drawing_list['drawing_number'];
						$drawing_project = $array_drawing_list['drawing_project'];
						$revision_letter = $array_drawing_list['revision_letter'];
						if ($revision_letter != NULL) { $revision_letter = " Rev. " . strtoupper($revision_letter); } else { unset($revision_letter); }
						
						if ($drawing_id != $current_drawing) { 
						
											if ($drawing_id != $_GET[drawing_id]) { 
												$array_pages[] = "index2.php?page=drawings_detailed&amp;drawing_id=$drawing_id&amp;proj_id=$drawing_project";$array_title[] = $drawing_number . $revision_letter;
											} else {
												$array_pages[] = "";
												$array_title[] = "<strong>" . $drawing_number . $revision_letter . "</strong>";
											}
											
						}
						
						$current_drawing = $drawing_id;
					
					}
				
				SideMenu ("Drawing List", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);
		
		
	}


}

?>
