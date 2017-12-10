<?php

// Page title

echo "<h1>Contacts</h1>";

// Establish where to start from GET, and then define it as 0 if none given

echo "<div class=\"menu_bar\">";

// Check the order from the GET information, and define as second name if none given

if (!$_GET[startletter]) { $startletter = "a"; } else { $startletter = $_GET[startletter];}
if (!$_GET[listorder]) { $listorder = "contact_namefirst"; } else { $listorder = "company_name";}

if (!$_GET[desc_order]) { unset($desc_order); echo "<a href=\"index2.php?page=contacts_view&amp;startletter=$startletter&amp;desc_order=1&amp;listorder=$listorder\" class=\"menu_tab\">Descending Order</a>"; } else { $desc_order = " DESC "; echo "<a href=\"index2.php?page=contacts_view&amp;startletter=$startletter&amp;desc_order=0&amp;listorder=$listorder\" class=\"menu_tab\">Ascending Order</a>"; }

if (!$_GET[listorder] OR $_GET[listorder] == "contact") { unset($listorder); echo "<a href=\"index2.php?page=contacts_view&amp;startletter=$startletter&amp;desc_order=1&amp;listorder=company\" class=\"menu_tab\">By Company</a>"; } else { $listorder = "company_name"; echo "<a href=\"index2.php?page=contacts_view&amp;startletter=$startletter&amp;desc_order=1&amp;listorder=contact\" class=\"menu_tab\">By Name</a>"; }

echo "</div>";



// Now present the filter information as a list of the alphabet

AlphabetFilter($current_letter,$listorder,$_GET[desc_order]);


// Now include the contacts database in the chosen format type

ListContacts($listorder,$startletter,$_GET[desc_order],$listbegin,$listmax);






