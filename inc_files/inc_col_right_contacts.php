<?php

SearchPanel();

include_once("inc_files/inc_menu_search.php");

// Contact Admin

if ($module_contacts == 1) {


	$array_pages = array("index2.php?page=contacts_edit&amp;status=add","index2.php?page=contacts_company_edit&amp;status=add","index2.php?page=contacts_company_edit&amp;status=add","index2.php?page=contacts_add_sector","index2.php?page=contacts_company_merge");
	$array_title = array("Add Contact","Add Company","Add Title","Add Sector","Merge Companies");
	$array_images = array();
	$array_access = array(1,1,1,1,4);
	
	SideMenu ("Contact Administration", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images);

}

?>
