<?php

// Page title

echo "<h1>Contacts</h1>";
echo "<h2>My Contacts</h2>";

ProjectSubMenu(NULL,$user_usertype_current,"contacts_admin",1);

// Establish where to start from GET, and then define it as 0 if none given

echo "<div class=\"menu_bar\"><a class=\"menu_tab\" href=\"index2.php?page=contacts_view\">List All Contacts</a></div>";

echo "<div class=\"submenu_bar\">";

// Check the order from the GET information, and define as second name if none given

if (!$_GET[startletter]) { $startletter = "a"; } else { $startletter = $_GET[startletter];}
if (!$_GET[listorder]) { $listorder = "contact_namefirst"; } else { $listorder = "company_name";}

if (!$_GET[desc_order]) { unset($desc_order); echo "<a href=\"index2.php?page=contacts_view&amp;startletter=$startletter&amp;desc_order=1&amp;listorder=$listorder\" class=\"submenu_bar\">Descending Order</a>"; } else { $desc_order = " DESC "; echo "<a href=\"index2.php?page=contacts_view&amp;startletter=$startletter&amp;desc_order=0&amp;listorder=$listorder\" class=\"submenu_bar\">Ascending Order</a>"; }

if (!$_GET[listorder] OR $_GET[listorder] == "contact") { unset($listorder); echo "<a href=\"index2.php?page=contacts_view&amp;startletter=$startletter&amp;desc_order=1&amp;listorder=company\" class=\"submenu_bar\">By Company</a>"; } else { $listorder = "company_name"; echo "<a href=\"index2.php?page=contacts_view&amp;startletter=$startletter&amp;desc_order=1&amp;listorder=contact\" class=\"submenu_bar\">By Name</a>"; }

echo "</div>";



// Now include the contacts database in the chosen format type

ListUserTargetsColumns($_COOKIE[user]);






