<?php

function ActionRiskEdit() {
	
	global $conn;
	$risk_id = intval($risk_id);
	

// Check that the required values have been entered, and alter the page to show if these values are invalid

		// Begin to clean up the $_POST submissions

		$risk_id = intval($_POST[risk_id]);
		$risk_title = addslashes(trim($_POST[risk_title]));
		$risk_project = intval($_POST[risk_project]);
		$risk_description = addslashes(trim($_POST[risk_description]));
		$risk_level = addslashes(trim($_POST[risk_level]));
		$risk_analysis = addslashes(trim($_POST[risk_analysis]));
		$risk_score = addslashes(trim($_POST[risk_score]));
		$risk_warnings = addslashes(trim($_POST[risk_warnings]));
		$risk_mitigation = addslashes(trim($_POST[risk_mitigation]));
		$risk_management = addslashes(trim($_POST[risk_management]));
		$risk_responsibility = intval($_POST[risk_responsibility]);
		$risk_timestamp = time();
		$risk_date = addslashes(trim($_POST[risk_date]));
		$risk_category = addslashes(trim($_POST[risk_category]));
		$risk_drawing = addslashes(trim($_POST[risk_drawing]));
		$risk_user = $_COOKIE[user];
		$risk_resolved = intval($_POST[risk_resolved]);

		
	if ($risk_id > 0) {
	
		$sql = "UPDATE intranet_project_risks SET
		risk_title = '$risk_title',
		risk_project = $risk_project,
		risk_description = '$risk_description',
		risk_level = '$risk_level',
		risk_analysis = '$risk_analysis',
		risk_score = '$risk_score',
		risk_warnings = '$risk_warnings',
		risk_mitigation = '$risk_mitigation',
		risk_management = '$risk_management',
		risk_responsibility = $risk_responsibility,
		risk_timestamp = $risk_timestamp,
		risk_date = '$risk_date',
		risk_category = '$risk_category',
		risk_user = $risk_user,
		risk_drawing = '$risk_drawing',
		risk_resolved = $risk_resolved
		WHERE risk_id = $risk_id LIMIT 1
		";
		
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$techmessage = $sql;
		$actionmessage = "<p>Risk \'<a href=\"index2.php?page=project_risks&amp;proj_id=$risk_project\">\"". $risk_description ."\"</a>\' edited successfully.</p>";
		AlertBoxInsert($_COOKIE[user],"Risk Updated",$actionmessage,$risk_id,0,0,$risk_project);
		
		if (urldecode($_POST[current_drawing]) != $_POST[risk_drawing] && $_POST[update_drawing] == 1) { $sql_update = "UPDATE intranet_project_risks SET risk_drawing = '" . $risk_drawing . "' WHERE risk_drawing = '" . addslashes($_POST[current_drawing]) . "' AND risk_project = " . $risk_project; $result_update = mysql_query($sql_update, $conn) or die(mysql_error()); }
	
	} else {

		// Construct the MySQL instruction to add these entries to the database

		$sql = "INSERT INTO intranet_project_risks (
		risk_id,
		risk_title,
		risk_project,
		risk_description,
		risk_level,
		risk_analysis,
		risk_score,
		risk_warnings,
		risk_mitigation,
		risk_management,
		risk_responsibility,
		risk_timestamp,
		risk_date,
		risk_category,
		risk_drawing,
		risk_user,
		risk_resolved
		) values (
		NULL,
		'$risk_title',
		$risk_project,
		'$risk_description',
		'$risk_level',
		'$risk_analysis',
		'$risk_score',
		'$risk_warnings',
		'$risk_mitigation',
		'$risk_management',
		$risk_responsibility,
		$risk_timestamp,
		'$risk_date',
		'$risk_category',
		'$risk_drawing',
		$risk_user,
		$risk_resolved
		)";
		
	
		$result = mysql_query($sql, $conn) or die(mysql_error());
		$risk_id = mysql_insert_id();
		
		$actionmessage = "<p>Risk \'<a href=\"index2.php?page=project_risks&amp;proj_id=$risk_project\">\"". $risk_title ."\"</a>\' added successfully.</p>";
		AlertBoxInsert($_COOKIE[user],"Risk Added",$actionmessage,$risk_id,0,0,$risk_project);
		
	}

	
}


ActionRiskEdit();