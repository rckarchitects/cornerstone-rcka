<?php

print "<p class=\"heading_side\">Settings</p>";

if ($_GET[listtype] == "list") { $showlist = "list"; }
elseif ($_GET[listtype] == "address") { $showlist = "address"; }
elseif ($_COOKIE[listtype] == "list") { $showlist = "list"; }
elseif ($_COOKIE[listtype] == "address") { $showlist = "address"; }
else { $showlist = "address"; }

$currentpage = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
$currentpage = CleanUp($currentpage);

if ($showlist == "list") {

print "<p>Contacts per page</p>";
print "<form action=\"$currentpage\" method=\"post\">";
print "<select class=\"inputbox\" name=\"listmax\">";
print "<option value=\"5\""; if ($listmax == 5) { print " selected"; } print ">5</option>";
print "<option value=\"10\""; if ($listmax == 10) { print " selected"; } print ">10</option>";
print "<option value=\"20\""; if ($listmax == 20) { print " selected"; } print ">20</option>";
print "<option value=\"30\""; if ($listmax == 30) { print " selected"; } print ">30</option>";
print "<option value=\"40\""; if ($listmax == 40) { print " selected"; } print ">40</option>";
print "</select>";
print "&nbsp;<input type=\"submit\" value=\"Update\" class=\"inputbox\" />";
print "<input type=\"hidden\" name=\"action\" value=\"contact_listmax\" />";
print "</form>";

} elseif ($showlist == "address") {

print "<p>Contacts per page</p>";
print "<form action=\"$currentpage\" method=\"post\">";
print "<select class=\"inputbox\" name=\"listmax\">";
print "<option value=\"2\""; if ($listmax == 2) { print " selected"; } print ">2</option>";
print "<option value=\"4\""; if ($listmax == 4) { print " selected"; } print ">4</option>";
print "<option value=\"6\""; if ($listmax == 6) { print " selected"; } print ">6</option>";
print "<option value=\"8\""; if ($listmax == 8) { print " selected"; } print ">8</option>";
print "<option value=\"10\""; if ($listmax == 10) { print " selected"; } print ">10</option>";
print "</select>";
print "&nbsp;<input type=\"submit\" value=\"Update\" class=\"inputbox\" />";
print "<input type=\"hidden\" name=\"action\" value=\"contact_listmax\" />";
print "</form>";

}

?>
