<?php

	$array_item_id = $_POST['item_id'];
	$array_checklist_id = $_POST['checklist_id'];
	$array_checklist_required = $_POST['checklist_required'];
	$array_checklist_date = $_POST['checklist_date'];
	$array_checklist_timestamp = $_POST['checklist_timestamp'];
	$array_checklist_project = $_POST['checklist_project'];
	$array_checklist_comment = $_POST['checklist_comment'];
	$array_checklist_user = $_POST['checklist_user'];
	$array_checklist_link = $_POST['checklist_link'];
	

$counter = 0;

$array_update = array();


while ($counter < count($array_item_id)) {

	$item_id = $array_item_id[$counter];
	$checklist_id = $array_checklist_id[$counter];
	$checklist_required = $array_checklist_required[$counter];
	$checklist_date = $array_checklist_date[$counter];
	$checklist_timestamp = $array_checklist_timestamp[$counter];
	$checklist_project = $array_checklist_project[$counter];
	$checklist_comment = addslashes ( $array_checklist_comment[$counter] );
	$checklist_user = $array_checklist_user[$counter];
	$checklist_link = addslashes ( $array_checklist_link[$counter] );
	
	if ($checklist_id == NULL) { $checklist_id = "0"; }

	//Check whether this is an existing entry
	
	$sql_check = "SELECT checklist_id FROM intranet_project_checklist WHERE checklist_id = $checklist_id";
	$result_check = mysql_query($sql_check, $conn) or die(mysql_error());
	
	if (mysql_num_rows($result_check) > 0) { 

				$sql_checklist_update = "
				
				UPDATE intranet_project_checklist SET
				checklist_item = $item_id,
				checklist_required = $checklist_required,
				checklist_date = '$checklist_date',
				checklist_timestamp = '$checklist_timestamp',
				checklist_project = '$checklist_project',
				checklist_comment = '$checklist_comment',
				checklist_user = '$checklist_user',
				checklist_link = '$checklist_link'
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
				checklist_link
				) VALUES (
				NULL,
				$item_id,
				$checklist_required,
				'$checklist_date',
				'$checklist_timestamp',
				'$checklist_project',
				'$checklist_comment',
				'$checklist_user',
				'$checklist_link'
				)";
	
	}
	
	
				
				if ($checklist_required != NULL) {				
					$result_checklist_update = mysql_query($sql_checklist_update, $conn) or die(mysql_error());
					if (mysql_affected_rows() > 0) {
						$array_update[] = mysql_insert_id();
					}
				}
				
				
$counter++;	
}


?>