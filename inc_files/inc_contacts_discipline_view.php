<?php

function DisciplineTitle($discipline_id) {
	
	global $conn;
	$discipline_id = intval($discipline_id);

	echo "<h1>Contacts</h1>";

	$sql_disc = "SELECT discipline_name FROM contacts_disciplinelist WHERE discipline_id = $discipline_id LIMIT 1";
	$result_disc = mysql_query($sql_disc, $conn) or die(mysql_error());
	$array_disc = mysql_fetch_array($result_disc);
	$discipline_name = $array_disc['discipline_name'];

	echo "<h2>$discipline_name</h2>";

}

DisciplineTitle($_GET[discipline_id]);

ProjectSubMenu(NULL,$user_usertype_current,"contacts_admin",1);

DisciplineProject($_GET[discipline_id]);

DisciplineNonProject($_GET[discipline_id]);