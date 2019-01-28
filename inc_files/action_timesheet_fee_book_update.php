<?php

function FeeBookUpdate() {
	
	global $conn;

					// Begin to clean up the $_POST submissions

					$book_id = intval($_POST[book_id]);
					$book_title = addslashes($_POST[group_title]);
					$book_order = intval($_POST[book_order]);
					$book_description = addslashes($_POST[book_description]);
					$book_timestamp = intval($_POST[book_timestamp]);
					$book_user = intval($_POST[book_user]);
					$book_stage = intval($_POST[book_stage]);
					

					
					if (intval($_POST[book_update_titles]) == 1 && $book_id > 0) {
						$sql_oldtitle = "SELECT book_title FROM intranet_timesheet_group_jobbook WHERE book_id = $book_id LIMIT 1";
						$result_oldtitle = mysql_query($sql_oldtitle, $conn) or die(mysql_error());
						$array_oldtitle = mysql_fetch_array($result_oldtitle);
						//echo "<p>$sql_oldtitle</p>";
						$sql_updatetitles = "UPDATE intranet_timesheet_group_jobbook SET book_title = '" . $book_title . "' WHERE book_title = '" . addslashes($array_oldtitle['book_title']) . "'";
						//echo "<p>$sql_updatetitles</p>";
						$result_updatetitles = mysql_query($sql_updatetitles, $conn) or die(mysql_error());
					}
					
					if ($book_order == 0) { $book_order = RetrieveBookOrder($book_title); }
					
					ChangeBookTitleOrder($book_title, $book_order);
					ConsolidateOrders();
					
					// Construct the MySQL instruction to add these entries to the database

					if ($book_id > 0) {
					
						$sql_add = "UPDATE intranet_timesheet_group_jobbook SET
						book_title = '$book_title',
						book_order = $book_order,
						book_description = '$book_description',
						book_timestamp = $book_timestamp,
						book_user = $book_user,
						book_stage = $book_stage
						WHERE book_id = $book_id LIMIT 1
						";
					
					}

					$result = mysql_query($sql_add, $conn) or die(mysql_error());

					$actionmessage = "<p>Job book entry ref. $book_id was edited successfully.</p>";

					AlertBoxInsert($book_user,"Job Book Updated",$actionmessage,$book_id,0,1,NULL);
				

}

function ChangeBookTitleOrder($book_title, $book_order) {
	
	global $conn;
	$book_order = intval($book_order);
	$book_title = addslashes($book_title);
	$sql = "UPDATE intranet_timesheet_group_jobbook SET book_order = (book_order + 100) WHERE book_order > $book_order AND book_title != '$book_title'";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	
	$sql2 = "UPDATE intranet_timesheet_group_jobbook SET book_order = $book_order WHERE book_title = '$book_title'";
	$result2 = mysql_query($sql2, $conn) or die(mysql_error());
	

}

function ConsolidateOrders() {
	
	global $conn;
	
	$sql3 = "SELECT book_title FROM intranet_timesheet_group_jobbook GROUP BY book_order ORDER BY book_order, book_title";
	//echo "<p>$sql3</p>";
	$result3 = mysql_query($sql3, $conn) or die(mysql_error());
	$counter = 1;
	while ($array3 = mysql_fetch_array($result3)) {
		$sql4 = "UPDATE intranet_timesheet_group_jobbook SET book_order = $counter WHERE book_title = '" . addslashes($array3['book_title']) . "'";
		//echo "<p>$sql4</p>";
		$result4 = mysql_query($sql4, $conn) or die(mysql_error());
		$counter++;
	}
	
}

function RetrieveBookOrder($book_title) {
	
	global $conn;
	
						if ($book_order == 0) {
						$sql_checkorder = "SELECT book_order FROM intranet_timesheet_group_jobbook WHERE book_title = '$book_title' LIMIT 1";
						$result_checkorder = mysql_query($sql_checkorder, $conn) or die(mysql_error());
						$array_checkorder = mysql_fetch_array($result_checkorder);
						$book_checkorder = $array_checkorder['book_order'];
						//echo "<p>$sql_checkorder</p>";
						
						return $book_checkorder;
						
					} else {
						
						echo "<p>Book order: $book_order</p>";
						
					}
}

function FeeBookAdd() {
	
	global $conn;

					// Begin to clean up the $_POST submissions

					$book_title = addslashes($_POST[group_title]);
					$book_order = intval($_POST[book_order]);
					$book_description = addslashes($_POST[book_description]);
					$book_timestamp = intval($_POST[book_timestamp]);
					$book_user = intval($_POST[book_user]);
					$book_stage = intval($_POST[book_stage]);
					
					if ($book_order == 0) { $book_order = RetrieveBookOrder($book_title); }
					
					ChangeBookTitleOrder($book_title, $book_order);
					
					ConsolidateOrders();
					
					// Construct the MySQL instruction to add these entries to the database

				
						$sql_add = "INSERT INTO intranet_timesheet_group_jobbook (
						book_id,
						book_title,
						book_order,
						book_description,
						book_timestamp,
						book_user,
						book_stage
						) VALUES (
						NULL,
						'$book_title',
						$book_order,
						'$book_description',
						$book_timestamp,
						$book_user,
						$book_stage
						)
						";

					$result = mysql_query($sql_add, $conn) or die(mysql_error());
					
					$id_added = mysql_insert_id();

					$actionmessage = "<p>Job book entry ref. $id_added was added successfully.</p>";

					AlertBoxInsert($book_user,"Job Book Updated",$actionmessage,$book_id,0,1,NULL);
				

}

if (intval($_POST[book_id]) > 0) {

	FeeBookUpdate();

} else {
	
	FeeBookAdd();
	
}