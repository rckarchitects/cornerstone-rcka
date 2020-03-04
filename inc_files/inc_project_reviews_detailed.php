<?php

if (intval($_GET[proj_id]) > 0) { $proj_id = $_GET[proj_id]; } elseif (intval($_POST[proj_id]) > 0) { $proj_id = $_POST[proj_id]; }
if ($_GET['review_id']) { $review_id = intval($_GET['review_id']); } elseif ($_POST['review_id']) { $review_id = intval($_POST['review_id']); }

ProjectSwitcher ("project_reviews",$proj_id,1,1);

ReviewDetails($review_id);
ReviewList($proj_id);



