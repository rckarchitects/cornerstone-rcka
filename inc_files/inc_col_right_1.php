<?php

echo "<h1 class=\"heading_side\">Internet Feed</h1>";
echo "<ul class=\"button_left\"><li><a href=\"index2.php?page=feeds\">Internet Feeds</a></li></ul>";

echo "<h1 class=\"heading_side\">Journal</h1>";
echo "<ul class=\"button_left\"><li><a href=\"index2.php?page=project_blog_edit&amp;status=add\">Add Journal Entry</a></li></ul>";

include_once("inc_files/inc_menu_search.php");

print "
<p id=\"navigation\" class=\"menu_bar\">
<a href=\"#\" onclick=\"menuSwitch(1); return false;\">Team</a> |
<a href=\"#\" onclick=\"menuSwitch(2); return false;\">Office</a> |
<a href=\"#\" onclick=\"menuSwitch(3); return false;\">Info.</a> |
<a href=\"#\" onclick=\"menuSwitch(4); return false;\">Health &amp; Safety</a> |
<a href=\"#\" onclick=\"menuSwitch(5); return false;\">Travel</a>
</p>
";

print "<div id=\"page_element_2\">";
print "<h1 class=\"heading_side\">Office</h1>";
	include("inc_files/inc_menu_address.php");
print "</div>";

print "<div id=\"page_element_1\">";
print "<h1 class=\"heading_side\">Team</h1>";
include("inc_files/inc_menu_team.php");
print "</div>";




print "<div id=\"page_element_3\">";
print "<h1 class=\"heading_side\">Practice</h1>";
echo "<ul class=\"button_left\"><li><strong>Company Information</strong></li><li>Company Registration No.<br /><a href=\"http://wck2.companieshouse.gov.uk/fb40930ec39a0e425d571de440819d4c/compdetails\">05789948</a><br />Date of Registration:<br />21 April 2006</li><li>VAT No.<br />912 2703 59</li><li>RIBA Chartered Practice No.<br /><a href=\"http://www.rcka.co.uk/downloads/RCKa_Chartered_Practice_2010.pdf\">20000784</a></li><li>D&amp;B DUNS Number<br /><a href=\"http://www.dnb.co.uk/About/DUNS_Number.asp\">349949870</a></li></ul>";
echo "<ul class=\"button_left\"><li><strong>Emergencies</strong></li><li>Islington Police Station<br />2 Tolpuddle Street<br />London, N1 0YY<br />Tel. 0300 123 1212 </li><li>UCL Accident &amp; Emergency<br />235 Euston Road<br />London NW1 2BU<br />0845 155 5000</li></ul>";
print "</div>";


print "<div id=\"page_element_4\">";
print "<h1 class=\"heading_side\">H&amp;S</h1><p>RCKa's \"Competent Person\" is:</p><p>John Westby<br />JW-8 Ltd<br />35 The Paddock<br />East Keswick<br />West Yorkshire<br />LS17 9EN<br />M 07971 668 882<br />E john.westby@jw-8.com</p>"; 
echo "</div>";

print "<div id=\"page_element_5\">";
print "<h1 class=\"heading_side\">Travel</h1>";

//print "<h2>Journey Planner</h2>";
//include("inc_files/inc_tfl.php");

print "<h2>Departures</h2>";

echo "
<ul class=\"button_left\"><li>
<a href=\"http://www.livedepartureboards.co.uk/fcc/summary.aspx?T=OLD&R=1\">Trains from Old Street</a>
</li>
</ul>
";

echo "</div>";

print "
		<script type=\"text/javascript\">
		document.getElementById(\"page_element_1\").style.display = \"block\";
		document.getElementById(\"page_element_2\").style.display = \"none\";
		document.getElementById(\"page_element_3\").style.display = \"none\";
		document.getElementById(\"page_element_4\").style.display = \"none\";
		document.getElementById(\"page_element_5\").style.display = \"none\";
		</script>
";


?>


