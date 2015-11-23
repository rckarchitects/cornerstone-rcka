<?php

// Page title

print "<h1>Contacts Database</h1>";

// Establish where to start from GET, and then define it as 0 if none given

if ($_GET[listbegin] != NULL ) {
	$listbegin = $_GET[listbegin];
} else {
	$listbegin = 0;
}

if ($listbegin <= 0) {
	$listbegin = 0;
}

// Check the order from the GET information, and define as second name if none given

if ($_GET[listorder] != NULL) {
	$listorder = $_GET[listorder];
} else {
	$listorder = "contact_namesecond";
}

if ($_GET[listtype] != NULL) {
    // setcookie("listtype", $_GET[listtype]);
    $listtype = $_GET[listtype];
} elseif ($_COOKIE[listtype] != NULL) {
    $listtype = $_COOKIE[listtype];
} else {
    $listtype = "list";
}

// Item Sub Menu
print "<p class=\"menu_bar\">";


if ($_GET[listorder] == NULL) { $listhi = "none"; } else { $listhi = $_GET[listorder]; }

if ($listhi != "none") { print "<a href=\"index2.php?page=contacts_view&amp;listbegin=$listbegin\" class=\"menu_tab\">None</a>"; }

if ($listhi != "contact_namefirst") { print "<a href=\"index2.php?page=contacts_view&amp;listbegin=$listbegin&amp;listorder=contact_namefirst&amp;filterletter=$_GET[filterletter]\" class=\"menu_tab\">First Name</a>"; }

if ($listhi != "contact_namesecond") { print "<a href=\"index2.php?page=contacts_view&amp;listbegin=$listbegin&amp;listorder=contact_namesecond&amp;filterletter=$_GET[filterletter]\" class=\"menu_tab\">Surname</a>"; }

print "</p>";


// Now present the filter information as a list of the alphabet

if ($_GET[listorder] != "contact_id" AND $_GET[listorder] != "contact_added" AND $_GET[listorder] != "contact_prefix" ) {

$letter_array = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");

print "<table><tr>";

if ($_GET[filterletter] == NULL ) { print "<td style=\"text-align: center; background-color: #ccc; font-weight: bold;\">All</td>"; } else { print "<td  style=\"text-align: center;\"><a href=\"index2.php?page=contacts_view&amp;listorder=$listorder\" style=\"padding: 2px 8px 2px 8px;\">All</a></td>"; }

	$array_count = 0; while ($array_count < count($letter_array)) {
		if ($_GET[filterletter] == $letter_array[$array_count]) { print "<td style=\"text-align: center; background-color: #ccc; font-weight: bold;\">".strtoupper($letter_array[$array_count])."</td>"; }
			else { print "<td style=\"text-align: center;\"><a href=\"index2.php?page=contacts_view&amp;listorder=$listorder&amp;filterletter=$letter_array[$array_count]\" style=\"padding: 2px 8px 2px 8px;\">".strtoupper($letter_array[$array_count])."</a></td>"; }
		$array_count++;
		if ($array_count == 13) { print "</tr><tr><td></td>"; }
	}
	
print "</tr></table>";

} else {

print "<p>Filters not available.</p>";
		
}

echo "<h2>".strtoupper($filterletter)."</h2>";

// Check for number of contacts to be listed

if ($listmax != "") {
} elseif ($_COOKIE[listmax] != "") {
$listmax = $_COOKIE[listmax];
} else {
$listmax = 500;
}

// Now include the contacts database in the chosen format type

include("inc_files/inc_data_contacts_view_$listtype.php");

// And if there are no results to return, print a message

if ($sql_num < 1) { print "<p>There are no results to display.</p>"; }


if ($listbegin <$listmax) {
	$listprev = NULL;
} else {
	$listprev = $listbegin-$listmax;
	$linkprev = "<a href=\"contactlist.php?listbegin=$listprev&amp;listorder=$listorder&amp;filterletter=$_GET[filterletter]\"><img src=\"images/button_prev.gif\" alt=\"Previous Page\" /></a>";
}

$listnext = $listbegin+$listmax;



// Check for links to next
if ($sql_num <= $listcount) {
	

	$listnext = NULL;
} else {
	$listnext = $listbegin+$listmax;
	$linknext = "<a href=\"contactlist.php?listbegin=$listnext&amp;listorder=$listorder&amp;filterletter=$_GET[filterletter]\"><img src=images/button_next.gif border=0 alt=\"Next Page\" /></a>";
}
	

print $linkprev;


?>






