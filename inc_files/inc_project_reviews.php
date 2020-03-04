<?php

if (intval($_GET[proj_id]) > 0) { $proj_id = $_GET[proj_id]; } elseif (intval($_POST[proj_id]) > 0) { $proj_id = $_POST[proj_id]; }

ProjectSwitcher ("project_reviews",$proj_id,1,1);

echo "<h2>Project Reviews</h2>";

	ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
	ProjectSubMenu($proj_id,$user_usertype_current,"project_reviews",2);

if ($_GET[item] > 0 && $user_usertype_current > 2) { $item = $_GET[item]; } else { unset($item); }

$proj_id = intval($_GET[proj_id]);


if ($_GET['review_id']) { $review_id = intval($_GET['review_id']); } elseif ($_POST['review_id']) { $review_id = intval($_POST['review_id']); }

ReviewList($proj_id,$review_id);

ReviewAdd($proj_id,$current_stage,$review_id);