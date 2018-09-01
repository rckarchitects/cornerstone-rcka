<?php

function MediaLatestList() {
	
	global $conn;

	
	$sql = "SELECT * FROM intranet_media WHERE media_type = 'pdf' ORDER BY media_timestamp DESC LIMIT 5";
	$result = mysql_query($sql, $conn);
	if (mysql_num_rows($result) > 0) {
		echo "<h2>Latest Uploads</h2><table>";
		while ($array = mysql_fetch_array($result)) {
			
			if ($array['media_description']) { $description = trim($array['media_description'],". ") . ", uploaded " . TimeFormat($array['media_timestamp']); } else {
				$description = "Uploaded " . TimeFormat($array['media_timestamp']);
			}
			
			if ((time() - intval($array['media_timestamp'])) < 86400) { $class = "alert_warning"; }
			elseif ((time() - intval($array['media_timestamp'])) < 604800) { $class = "alert_careful"; }
			else { unset($class); }
				
			echo "<tr><td class=\"$class\"><a href=\"" . $array['media_path'] . $array['media_file'] . "\">" . $array['media_title'] . "</a></td><td class=\"$class HideThis\"><span class=\"minitext\">" . $description . "</span></td><td style=\"text-align: right;\" class=\"$class\"><span class=\"minitext\">" . $array['media_category'] . "</span></td></tr>";
		
		}
		
		
		
		echo "</table>";
		
		echo "<p><a href=\"index2.php?page=media&amp;filter=pdf\">[More]</a></p>";
		
	}
	
}


function MediaUploadForm() {
	
	
	
	echo "<h2>Upload Media</h2>";
	
	ProjectSubMenu(NULL,$user_usertype_current,"media",1);
	
	
	echo "<h3>Enter File Details</h3>";
	
	echo "<form method=\"post\" action=\"index2.php?page=media\" enctype=\"multipart/form-data\">";
	
	echo "<p>Title (if required)<br /><input type=\"text\" maxlength=\"200\" name=\"media_title\" style=\"width: 95%;\" required=\"required\" /></p>";
	
	echo "<p>File<br /><input type=\"file\" name=\"media_file\" required=\"required\" /></p>";
	
	echo "<p>Description<br /><textarea name=\"media_description\" style=\"width: 95%; height: 75px;\"></textarea>";
	
	echo "<p>Category<br/ >"; MediaCategory(); echo "</p>";
	
	echo "<p><input type=\"submit\" /></p>";

	echo "<input type=\"hidden\" name=\"action\" value=\"media_upload\" />";
	
	echo "</form>";
	
}

function MediaCategory() {
	
	global $conn;
	
	echo "<input type=\"text\" name=\"media_category\" list=\"media_category\" value=\"" . $media_category . "\" maxlength=\"200\" required=\"required\" />";
	echo "<datalist id=\"media_category\">";
	
	$sql = "SELECT DISTINCT media_category FROM intranet_media GROUP BY media_category ORDER BY media_category";
	$result = mysql_query($sql, $conn);
	while ($array = mysql_fetch_array($result)) {
		echo "<option value=\"" . $array['media_category'] . "\"></option>";
	}
	echo "</datalist>";
	
	
	
}

function MediaSize($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('', 'Kb', 'Mb', 'Gb', 'Tb');   

    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}

function MediaDelete($media_id, $media_user) {
	
	global $user_usertype_current;
	
	if ($user_usertype_current > 3 OR $_COOKIE[user] == $media_user) {	
		return "&nbsp;<form action=\"index2.php?page=media\" method=\"post\" style=\"float: right;\"><input type=\"image\" src=\"images/button_delete.png\" class=\"image\" onclick=\"return confirm('Are you sure you want to delete this item?')\"><input type=\"hidden\" value=\"$media_id\" name=\"media_id\" /><input type=\"hidden\" name=\"media_deleted_by\" /><input type=\"hidden\" name=\"action\" value=\"media_delete\" /></form>";
	}
	
}

function MediaTopMenu () {
	
	global $conn;
	
	$sql = "SELECT DISTINCT media_category FROM intranet_media GROUP BY media_category ORDER BY media_category";
	$result = mysql_query($sql, $conn);
	
	echo "<div class=\"submenu_bar\">";
	
	if ($_GET[category] == NULL) { $style = "style=\"background-color: white;\""; } else { unset($style); }
	
	echo "<a href=\"index2.php?page=media\" class=\"submenu_bar\" $style>All</a>";
	
	if (mysql_num_rows($result) > 0) {
		

		
			while ($array = mysql_fetch_array($result)) {
				if (trim($array['media_category']) != "") {
				$media_filter = htmlentities($array['media_category']);
					if ($media_filter == $_GET[category]) { $style = "style=\"background-color: white;\""; }
					else { unset($style); }
					echo "<a href=\"index2.php?page=media&amp;category=" . $media_filter . "\" class=\"submenu_bar\" $style>" . $array['media_category'] . "</a>";
				}
			}
			
		
			
	}
	
	echo "</div>";
	
}


function MediaBrowse($filter) {
	
	global $conn;
	global $user_usertype_current;
	
	if ($filter == "pdf") { $filter = " WHERE media_type = 'pdf' "; }
	else { unset($filter);}
	
	if ($_GET[category] && $filter) { $filter = $filter . " AND media_category = '" . html_entity_decode($_GET[category]) . "'"; }
	elseif ($_GET[category]) { $filter = " WHERE media_category = '" . html_entity_decode($_GET[category]) . "'"; }
	
	
	
	echo "<h2>Browse</h2>";
	
	ProjectSubMenu(NULL,$user_usertype_current,"media",1);
	
	MediaTopMenu ();

	$sql = "SELECT * FROM `intranet_media` $filter ORDER BY media_category, media_timestamp DESC";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	
	if (mysql_num_rows($result) > 0) {
	
			while ($array = mysql_fetch_array($result)) {
				
				if (!$current_category) { echo "<div><h3>" . $array['media_category'] . "</h3>"; $current_category = $array['media_category']; }
				elseif ($current_category != $array['media_category']) { echo "</div><div><h3>" . $array['media_category'] . "</h3>"; $current_category = $array['media_category']; }
				
				if ((time() - intval($array['media_timestamp'])) < 86400) { $class = "alert_warning"; }
				elseif ((time() - intval($array['media_timestamp'])) < 604800) { $class = "alert_careful"; }
				else { unset($class); }
				
				echo "<div class=\"bodybox imagecontainer $class\">";
				
				$image_files = array("png","gif","jpg");
				
				if ($array['media_size']) { $media_size = "<br /><span class=\"minitext\"><i>" . MediaSize($array['media_size']) . "</i></span>"; } else { unset($media_size); }
				
				if (in_array($array['media_type'],$image_files)) {
				
					echo "<p><a href=\"" . $array['media_path'] . $array['media_file'] . "\"><img src=\"" . $array['media_path'] . $array['media_file'] .  "\" alt=\"" . $array['media_title'] . "\" style=\"width: 100%;\" /></a><br /><span class=\"minitext\">" . $array['media_title'] . MediaDelete($array['media_id'], $array['media_user']) . "</span></p>";
				
				} elseif ($array['media_type'] == "pdf" && $array['media_description'] != "") { 
				
					echo "<p><img src=\"images/icon_pdf.png\" style=\"width: 60px; margin: 12px;\" /><br /><a href=\"" . $array['media_path'] . $array['media_file'] . "\"><strong><a href=\"" . $array['media_path'] . $array['media_file'] . "\">" . $array['media_title'] . "</a></strong><br /><span class=\"minitext\">" . $array['media_description'] . "</span></a>" . $media_size . MediaDelete($array['media_id'], $array['media_user']) . "</p>";
					
				} elseif ($array['media_type'] == "pdf" && $array['media_description'] == "") { 
				
					echo "<p><img src=\"images/icon_pdf.png\" style=\"width: 120px; margin: 12px;\" /><br /><strong><a href=\"" . $array['media_path'] . $array['media_file'] . "\">" . $array['media_title'] . "</a></strong>" . $media_size . MediaDelete($array['media_id'], $array['media_user']) . "</p>";
					
				} else { 
				
					echo "<p><a href=\"" . $array['media_path'] . $array['media_file'] . "\">" . $array['media_title'] . "</a>" . $media_size . MediaDelete($array['media_id'], $array['media_user']) . "</p>";
				}
				
				echo "</div>";
				
			}
			
			echo "</div>";
	
	} else {
		
			echo "<p>No files found.</p>";
		
	}
	
}
