<?php

echo "<h1>Change Password</h1>";

echo "<form action=\"index2.php\" method=\"post\">";

echo "<p>Enter your current password<br /><input type=\"password\" name=\"user_password\" size=\"40\" maxlength=\"24\" /></p>";

echo "<p>Enter your new password<br /><input type=\"password\" name=\"user_password_new1\" size=\"40\" maxlength=\"24\" /></p>";

echo "<p>Confirm new password<br /><input type=\"password\" name=\"user_password_new2\" size=\"40\" maxlength=\"24\" /></p>";

echo "<p><input type=\"hidden\" value=\"user_password_edit\" name=\"action\" /><input type=\"Submit\" /></p>";

echo "</form>";



?>