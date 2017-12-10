<?php

echo "<h1>Contact Database</h1>";

if (intval($_GET[contact_id]) > 0) {

	$contact_id = intval($_GET[contact_id]);
	ListDuplicates($contact_id);

} else {

	echo "<p>No contact found.</p>";

}