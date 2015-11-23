<?php

// Get the list of projects from the database

$nowtime = time();

if ($_GET[detail] == "yes") { $detail = "yes"; }

$sql = "SELECT * FROM intranet_tender ORDER BY tender_date DESC";
$result = mysql_query($sql, $conn) or die(mysql_error());

		print "<p class=\"submenu_bar\">";
			if ($user_usertype_current > 3) {
				print "<a href=\"index2.php?page=project_edit&amp;status=add\" class=\"submenu_bar\">Add Tender</a>";
			}
		if ($detail != "yes") {
			print "<a href=\"index2.php?page=tender_list&amp;detail=yes\" class=\"submenu_bar\">View Details</a>";
		} else {
			print "<a href=\"index2.php?page=tender_list\" class=\"submenu_bar\">Hide Details</a>";
		}
			
		print "</p>";
		
		print "<h2>Tender List</h2>";


		if (mysql_num_rows($result) > 0) {
		
		$time_line = NULL;

		print "<table summary=\"Lists of tenders\">";
			
		while ($array = mysql_fetch_array($result)) {
		
		$tender_id = $array['tender_id'];
		$tender_name = $array['tender_name'] . "&nbsp;(". $array['tender_type'] .")";
		$tender_date = $array['tender_date'];
		$tender_client = $array['tender_client'];
		$tender_description = nl2br($array['tender_description']);
		$tender_keywords = $array['tender_keywords'];
		
		if (($nowtime > $tender_date) && ($nowtime < $time_line)) { echo "<tr><td colspan=\"2\" style=\"background: red; color: white; text-align: right;\">Today is ".TimeFormat($nowtime)."</td></tr>"; }
		
		if ((($tender_date - $nowtime) < 86400) && (($tender_date - $nowtime) > 0)) { $style = "style=\"background: orange;\""; } else { unset($style); }
		
		echo "<tr><th colspan=\"2\" $style ><a href=\"index2.php?page=tender_view&amp;tender_id=$tender_id\">$tender_name</a></th></tr>";
		echo "<tr><td $style >".date("d M Y",$tender_date)."</td><td $style>$tender_client</td></tr>";
		if ($detail == "yes") { echo "<tr><td colspan=\"2\" $style >$tender_description</td></tr>"; }
		if ($detail == "yes") { 
			echo "<tr><td colspan=\"2\" $style ><span class=\"minitext\">Keywords:&nbsp;";
			KeyWords($tender_keywords);
			echo "</span></td></tr>";
		}
		
		$time_line = $tender_date;

		}

		print "</table>";

		} else {

		print "There are no tenders on the system.";

		}
		
?>