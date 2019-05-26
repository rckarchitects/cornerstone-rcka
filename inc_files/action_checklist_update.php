<?php

function CheckListUpdate() {
	
	
			global $conn;


			$array_item_id = $_POST['item_id'];
			
			$array_item_counter = $_POST['item_counter'];
			
			$array_checklist_id = $_POST['checklist_id'];
			$array_checklist_required = $_POST['checklist_required'];
			$array_checklist_date = $_POST['checklist_date'];
			$array_checklist_timestamp = $_POST['checklist_timestamp'];
			$array_checklist_project = $_POST['checklist_project'];
			$array_checklist_comment = $_POST['checklist_comment'];
			$array_checklist_user = $_POST['checklist_user'];
			$array_checklist_link = $_POST['checklist_link'];
			$array_checklist_deadline = $_POST['checklist_deadline'];
			$array_item_title = $_POST['item_title'];
			
			//print_r($array_checklist_comment);

		$array_update = array();


		foreach ($array_item_counter AS $counter) {

			$item_id = $array_item_id[$counter];
			$checklist_id = $array_checklist_id[$counter];
			$checklist_required = $array_checklist_required[$counter];
			$checklist_date = $array_checklist_date[$counter];
			$checklist_timestamp = $array_checklist_timestamp[$counter];
			$checklist_project = $array_checklist_project[$counter];
			$checklist_comment = addslashes ( $array_checklist_comment[$counter] );
			$checklist_user = $array_checklist_user[$counter];
			$checklist_link = addslashes ( $array_checklist_link[$counter] );
			$checklist_deadline = $array_checklist_deadline[$counter];
			$item_title = $array_item_title[$counter] . " (" . GetProjectName($checklist_project) . ")";
			
			$checklist_upload = $_FILES['checklist_upload'];
						
				$fileName = $checklist_upload['name'][$counter];
				$fileSize = $checklist_upload['size'][$counter];
				$fileTmpName = $checklist_upload['tmp_name'][$counter];
				$fileError = $checklist_upload['error'][$counter];
				
				//echo "<p>" . $checklist_id . ", error: " . $fileError . ", filename: " . $fileName .  "</p>";
				
				
			if ($fileError == 0 && $checklist_upload) {
				
				$array_checklist_comment = addslashes($array_checklist_comment);

				$checklist_link = FileUploadChecklist($item_title,$checklist_project,"Checklist",$fileName,$fileSize,$fileTmpName,$checklist_comment);
				
			}
			
			if ($checklist_id > 0) {

						$sql_checklist_update = "
						
						UPDATE intranet_project_checklist SET
						checklist_item = $item_id,
						checklist_required = $checklist_required,
						checklist_date = '$checklist_date',
						checklist_timestamp = '$checklist_timestamp',
						checklist_project = '$checklist_project',
						checklist_comment = '$checklist_comment',
						checklist_user = '$checklist_user',
						checklist_link = '$checklist_link',
						checklist_deadline = '$checklist_deadline'
						WHERE checklist_id = $checklist_id AND checklist_project = $checklist_project
						LIMIT 1";
					
						
			} else {
			
						$sql_checklist_update = "
						INSERT INTO intranet_project_checklist (
						checklist_id,
						checklist_item,
						checklist_required,
						checklist_date,
						checklist_timestamp,
						checklist_project,
						checklist_comment,
						checklist_user,
						checklist_link,
						checklist_deadline
						) VALUES (
						NULL,
						$item_id,
						$checklist_required,
						'$checklist_date',
						'$checklist_timestamp',
						'$checklist_project',
						'$checklist_comment',
						'$checklist_user',
						'$checklist_link',
						'$checklist_deadline'
						)";
			
			}
			
			
			
			//echo "<p><strong>$counter:</strong> $sql_checklist_update</p>";
			
			
						
						if ($checklist_required != NULL) {				
							$result_checklist_update = mysql_query($sql_checklist_update, $conn) or die(mysql_error());
							if (mysql_affected_rows() > 0) {
								$array_update[] = mysql_insert_id();
							}
						}
					
		}

		$actionmessage = "<p>Checklist for <a href=\"index2.php?page=project_checklist&proj_id=" . $checklist_project . "\">" . GetProjectName($checklist_project)  . "</a> updated.</p>";

		AlertBoxInsert($_COOKIE[user],"Project Checklist Updated",$actionmessage,0,0,0,$checklist_project);
		

}

CheckListUpdate();
