<?php

echo "<h1 class=\"heading_side\">Contact Activities</h1>";

echo "
<ul class=\"button_left\">
<li><a href=\"index2.php?page=contacts_edit&amp;status=add\">Add Contact</a></li>
<li><a href=\"index2.php?page=contacts_company_edit&amp;status=add\">Add Company</a></li>
<li><a href=\"index2.php?page=contacts_add_title\">Add Title</a></li>
<li><a href=\"index2.php?page=contacts_add_sector\">Add Sector</a></li>";

if ($user_usertype_current > 3) { echo "<li><a href=\"index2.php?page=contacts_company_merge\">Merge Companies</a></li>";  }

echo "</ul>";

		
?>
