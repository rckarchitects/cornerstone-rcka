<?php

function ProjectReviewAdd() {
	
	global $conn;


				// Construct the MySQL instruction to add these entries to the database

				$sql = "INSERT INTO intranet_project_reviews (
				review_id,
				review_proj,
				review_stage,
				review_date,
				review_user,
				review_notes,
				review_type,
				review_complete,
				review_timestamp
				) values (
				NULL,
				" . intval($_POST['review_proj']) . ",
				" . intval($_POST['review_stage']) . ",
				'" . $_POST['review_date'] . "',
				" . intval($_COOKIE['user']) . ",
				'" . addslashes($_POST['review_notes']) . "',
				'" . addslashes($_POST['review_type']) . "',
				" . intval($_POST['review_complete']) . ",
				" . time() . "
				)";
				
				//echo "<p>" . $sql . "</p>";

				$result = mysql_query($sql, $conn) or die(mysql_error());

				$id_added = mysql_insert_id();

				$actionmessage = "<p>Review <a href=\"index2.php?page=project_review_detail&amp;review_id=" . $id_added . "&amp;proj_id=" . intval($_POST['review_proj']) . "\">\" " . addslashes($_POST['review_type']) . "\"</a> was added successfully.</p>";

				AlertBoxInsert($_COOKIE[user],"Review Added",$actionmessage,$id_added,0,0,intval($_POST['review_proj']));

				
		return $id_added;

}

function ProjectReviewEdit($review_id) {
	
	global $conn;
	
		if (intval($review_id) > 0) {

				// Construct the MySQL instruction to add these entries to the database

				$sql = "UPDATE intranet_project_reviews SET
				review_proj = " . intval($_POST['review_proj']) . ",
				review_stage = " . intval($_POST['review_stage']) . ",
				review_date = '" . $_POST['review_date'] . "',
				review_user = " . intval($_COOKIE['user']) . ",
				review_notes = '" . addslashes($_POST['review_notes']) . "',
				review_type = '" . addslashes($_POST['review_type']) . "',
				review_complete = " . intval($_POST['review_complete']) . ",
				review_timestamp = " . time() . "
				WHERE review_id = " . intval($review_id) . " LIMIT 1";
				
				//echo "<p>" . $sql . "</p>";

				$result = mysql_query($sql, $conn) or die(mysql_error());

				$actionmessage = "<p>Review <a href=\"index2.php?page=project_review_detail&amp;review_id=" . $id_added . "&amp;proj_id=" . intval($_POST['review_proj']) . "\">\" " . addslashes($_POST['review_type']) . "\"</a> was added successfully.</p>";

				AlertBoxInsert($_COOKIE[user],"Review Added",$actionmessage,$id_added,0,0,intval($_POST['review_proj']));

				
		return intval($review_id);
		
	}

}

if (intval($_POST['review_id']) > 0) { $review_id = ProjectReviewEdit($_POST['review_id']); } else { $review_id = ProjectReviewAdd(); }