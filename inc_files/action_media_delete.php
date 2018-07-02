<?php


function MediaUpload($media_id, $media_user) {
	

	global $conn;
	
	$sql = "DELETE FROM intranet_media WHERE media_id = " . intval($media_id) . " LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	$actionmessage . "<p>Media with ID reference " . intval($media_id) . " has been deleted successfully. The uploaded file has been retained for archival purposes.</p>";
	AlertBoxInsert(intval($media_user),"Media Deleted",$actionmessage,$media_id,0);
	
}

MediaUpload(intval($_POST[media_id], intval($_POST[media_deleted_by])));