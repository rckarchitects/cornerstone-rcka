<?php

echo "<h1>Project Stages</h1>";

if ($_GET[item] == "fee_stage" && intval($_GET[group_id]) > 0) {
	
	echo "<h2>Edit Project Stage</h2>";
	
	ProjectSubMenu(NULL,$user_usertype_current,"fee_stage_list",1);
	
	EdtFeeStages(intval($_GET[item]));
	
} elseif ($_GET[item] == "fee_group" && intval($_GET[book_id]) > 0) {
	
	echo "<h2>Edit Project Book</h2>";
	
	ProjectSubMenu(NULL,$user_usertype_current,"fee_stage_list",1);

	EditFeeGroup(intval($_GET[book_id]));
	
} elseif ($_GET[item] == "fee_group") {
	
	echo "<h2>Add Project Book</h2>";
	
	ProjectSubMenu(NULL,$user_usertype_current,"fee_stage_list",1);

	EditFeeGroup();
	
} else {
	
	echo "<h2>List All</h2>";
	
	ProjectSubMenu(NULL,$user_usertype_current,"fee_stage_list",1);

	ListFeeStages($_GET[group_id]);
	
}






