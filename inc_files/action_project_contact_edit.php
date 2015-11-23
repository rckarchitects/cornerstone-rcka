<?php

		$contact_proj_id = $_POST[contact_proj_id];
		$contact_proj_role = CleanNumber($_POST[contacts_discipline]);
		$contact_proj_contact = CleanNumber($_POST[contact_proj_contact]);
		$contact_proj_note = CleanUp($_POST[contact_proj_note]);
		$contact_proj_company = CleanNumber($_POST[contact_proj_company]);
		
if ($contact_proj_id > 0) {

		$sql_edit = "UPDATE intranet_contacts_project SET
		contact_proj_role = '$contact_proj_role',
		contact_proj_contact = '$contact_proj_contact',
		contact_proj_note = '$contact_proj_note',
		contact_proj_company = '$contact_proj_company'
		WHERE contact_proj_id = '$contact_proj_id' LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Project contact updated successfully.";
		$techmessage = $sql_edit;
		
}
		
?>