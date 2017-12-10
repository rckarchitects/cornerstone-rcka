<?php


// Menu - Search



SearchPanel($user_usertype_current);


// Menu - Web Feeds

	$array_pages = array("index2.php?page=feeds&type=news","index2.php?page=feeds&type=competitions");
	$array_title = array("BD News","BD Competitions");
	$array_images = array("button_news.png","button_news.png");
	$array_access = array(0,0);
				
	SideMenu ("News Feeds", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images, "r");

// Menu - Journal

	$array_pages = array("index2.php?page=project_blog_edit&amp;status=add");
	$array_title = array("Add Journal Entry");
	$array_images = array("button_new.png");
	$array_access = array(1);
				
	SideMenu ("Journal", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images, "r");
	
// Menu - Address

	$settings_companyname = htmlentities($settings_companyname) . "<br />" . nl2br(htmlentities($settings_companyaddress));

	$array_pages = array("");
	$array_title = array($settings_companyname);
	$array_images = array("");
	$array_access = array(1);
	
	if($settings_companytelephone) { $array_title[] = "T&nbsp;".$settings_companytelephone; $array_pages[] = ""; }
	if($settings_companyfax) { $array_title[] = "F&nbsp;". $settings_companyfax; $array_pages[] = ""; }
	if($settings_companyweb) { $array_title[] = "W&nbsp;" . $settings_companyweb; $array_pages[] = ""; }
				
	SideMenu ("Address", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images, "r");	
	
// Menu - Team

	$sql = "SELECT * FROM intranet_user_details WHERE user_active = 1 order by user_name_second";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	$array_pages = array();
	$array_title = array();
	$array_access = array();
	$array_images = array();

	while ($array = mysql_fetch_array($result)) {

		$user_name_first = $array['user_name_first'];
		$user_name_second = $array['user_name_second'];
		$user_num_mob = $array['user_num_mob'];
		$user_num_home = $array['user_num_home'];
		$user_num_extension = $array['user_num_extension'];
		$user_email = $array['user_email'];
		$user_id = $array['user_id'];
		$user_usertype = $array['user_usertype'];
		
		$user_name = $user_name_first . " " . $user_name_second;
		if ($user_num_mob) { $user_name = $user_name . "<br />" . $user_num_mob; }
		
		if ($user_usertype_current > 4) { $user_name = $user_name . "&nbsp;[" . $user_usertype . "]"; }

		$array_pages[] = "index2.php?page=user_view&amp;user_id=" . $user_id;
		$array_title[] = $user_name;
		$array_images[] = "";
		$array_access[] = 1;
				
	}

		
		$array_pages[] = "index2.php?page=user_list";
		$array_title[] = "List All Users";
		$array_images[] = "button_list.png";
		$array_access[] = 4;
		
		$array_pages[] = "index2.php?page=user_edit&amp;user_add=true";
		$array_title[] = "Add New User";
		$array_images[] = "button_new.png";
		$array_access[] = 4;
		
				
	SideMenu ("Team", $array_pages, $array_title, $array_access, $user_usertype_current, $array_images, "r");


