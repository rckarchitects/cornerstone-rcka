<?php


$contact_proj_id = CleanNumber($_GET[contact_proj_id]);

if ($contact_proj_id > 0) {

						$sql_edit = "DELETE from intranet_contacts_project
						WHERE contact_proj_id = '$contact_proj_id' LIMIT 1";

			$result = mysql_query($sql_edit, $conn) or die(mysql_error());
			$actionmessage = "Contact deleted successfully.";
			$techmessage = $sql_edit;

			$actionmessage = "Project contact deleted successfully.";
} else {

	$errormessage = "Project contact deletion failed.";


}
		

?>