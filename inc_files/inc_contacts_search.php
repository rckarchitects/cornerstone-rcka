<?php

// Page title

print "<h1>Search Contacts Database</h1>";

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
    setcookie("listtype", $_GET[listtype]);
    $listtype = $_GET[listtype];
} elseif ($_COOKIE[listtype] != NULL) {
    $listtype = $_COOKIE[listtype];
} else {
    $listtype = "address";
}

// Now include the contacts database in the chosen format type

include("inc_files/inc_data_contacts_search.php");

// And if there are no results to return, print a message

if ($result_num < 1) { print "<p>There are no results to display.</p>"; }

?>






