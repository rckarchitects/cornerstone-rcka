<?php

	if (intval($contact_id) > 0) {  $contact_id = intval($contact_id); }
	elseif (intval($_GET[contact_id]) > 0) { $contact_id = intval($_GET[contact_id]); }
	elseif (intval($_POST[contact_id]) > 0) {  $contact_id = intval($_POST[contact_id]); }
	
	echo "<h1>Contacts</h1>";
	

$company_id = intval( ContactViewDetailed($contact_id) );


if ($company_id) {
	
	CompanyViewDetailed($company_id);	
	
	ContactRelatedContacts($company_id,$contact_id);
	
}

ContactClient($contact_id);

ContactNotes($contact_id);

//ContactPostalAddress($contact_id);

ContactDrawingList($contact_id);

ContactProjects($contact_id);

