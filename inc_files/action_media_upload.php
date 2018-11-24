<?php

function MediaUpload() {

	unset($actionmessage);
	
	global $conn;
	
    $currentDir = getcwd();
    $uploadDirectory = "/uploads/";
	

    $fileExtensions = ['jpeg','jpg','png','pdf']; // Get all the file extensions

    $fileName = $_FILES['media_file']['name'];
    $fileSize = $_FILES['media_file']['size'];
    $fileTmpName  = $_FILES['media_file']['tmp_name'];
    $fileType = $_FILES['media_file']['type'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
	
	$new_file_name = "media_" . date("Y-m-d",time()) . "_" . $_COOKIE[user] . "_" . time() . "." . $fileExtension;
	
	if (!$_POST[media_title]) { $media_title = addslashes($fileName); } else { $media_title = addslashes($_POST[media_title]); }

    $uploadPath = $currentDir . $uploadDirectory . $new_file_name;
	

    if (isset($uploadPath)) {
		
		

			if (! in_array($fileExtension,$fileExtensions)) {
				$actionmessage = $actionmessage . "This file extension is not allowed.<br />";
				//echo "<p>$actionmessage</p>";
			}

			if ($fileSize > 20000000) {
				$actionmessage = $actionmessage . "This file is more than 10MB. Sorry, it has to be less than or equal to 10MB.<br />";
				//echo "<p>$actionmessage</p>";
			}

			if (empty($actionmessage)) {
				
				
				
				$didUpload = move_uploaded_file($fileTmpName, $uploadPath);

				if ($didUpload) {
					$actionmessage = $actionmessage . "The file " . $fileName . " has been uploaded to " . $currentDir . $uploadDirectory . " and renamed to " . $new_file_name . ".<br />";
					
					$media_path = addslashes ($uploadDirectory);
					$media_name = $new_file_name;
					$media_timestamp = time();
					$media_user = $_COOKIE[user];
					$media_type = $fileExtension;
					$media_file = addslashes($new_file_name);
					$media_size = $fileSize;
					$media_category = addslashes($_POST[media_category]);
					$media_description = addslashes($_POST[media_description]);
					$media_checklist = intval($_POST[media_checklist]);
					
					$sql_add = "INSERT INTO intranet_media (
								media_id,
								media_path,
								media_title,
								media_timestamp,
								media_user,
								media_type,
								media_file,
								media_category,
								media_size,
								media_description,
								media_checklist
								) values (
								'NULL',
								'$media_path',
								'$media_title',
								'$media_timestamp',
								'$media_user',
								'$media_type',
								'$media_file',
								'$media_category',
								'$media_size',
								'$media_description',
								'$media_checklist'
								)";
								
								$result = mysql_query($sql_add, $conn) or die(mysql_error());
								$id_added = mysql_insert_id();
					
				} else {
					
					
					$actionmessage = $actionmessage . "An error occurred somewhere. Try again or contact the administrator.<br />";
					
					//echo "<p>$actionmessage from $fileTmpName to $uploadPath </p>";
					
				}
			}
		} else {
			
			$actionmessage = $actionmessage . "An unknown error occurred.<br />";

			
		}
		
		$actionmessage = "<p>" . rtrim($actionmessage,"<br />") .  "</p>";
	
		AlertBoxInsert($_COOKIE[user],"Media Upload",$actionmessage,$id_added,0);
		
}

MediaUpload();