<?php




echo "<h1>Telephone Messages</h1>";

if (intval($_GET[user_id]) > 0) { $user_id = intval($_GET[user_id]); } else { $user_id = intval($_COOKIE[user]); }

TelephoneMessage($user_id);


