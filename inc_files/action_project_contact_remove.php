<?php


$contact_proj_id = intval($_GET[contact_proj_id]);

if ($contact_proj_id > 0) {

						$sql_edit = "DELETE from intranet_contacts_project
						WHERE contact_proj_id = " . $contact_proj_id . " LIMIT 1";

			$result = mysql_query($sql_edit, $conn) or die(mysql_error());
			$techmessage = $sql_edit;

			$actionmessage = "<p>Project contact " . $contact_proj_id . " deleted successfully.</p>";
			
			AlertBoxInsert($_COOKIE[user],"Project Contact Deleted",$actionmessage,$contact_proj_id,0,1,0);
}