<?php

if ($_GET[proj_id] != NULL) { $proj_id = intval($_GET[proj_id]); } elseif ($_POST[proj_id] != NULL) { $proj_id = intval($_POST[proj_id]); }



if ($proj_id == NULL) {

	echo"<h1>Error</h1><p>No project selected.</p>";

} else {
	

	ProjectDrawingList($proj_id);
	
}

//include_once("inc_drawings_edit.php");


		
?>
