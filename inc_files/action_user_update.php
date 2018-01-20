<?php

if ($_POST[user_id] > 0) { $user_id = $_POST[user_id]; } else { unset($user_id); }

UpdateUser($user_id);
