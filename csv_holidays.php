<?php

include "inc_files/inc_checkcookie.php";

if ($user_usertype_current <= 2) { header ("Location: index2.php"); } else {

echo "<?xml version=\"1.0\" encoding=\"iso-8859-15\"?>
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>RCKa Intranet System - P11d Schedule</title>
<link rel=\"shortcut icon\" href=\"favicon.ico\" />
<meta http-equiv=\"pragma\" content=\"no-cache\" />
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-15\" />
<style type=\"text/css\">
<!--
  body {font-family: Arial, Helvetica, sans-serif; margin: 20px; padding: 0px; background-color: #fff; color: #000;}
  table { border-style:solid; border-width:1px; border-color: #ccc }
  p { font-size: 9pt;}
  h1 { font-size: 13pt;}
  th { font-size: 8pt; padding: 2px; font-weight: bold; color: #666; text-align: left; background-color: #ddd; border-style:solid; border-width: 1px}
  td { font-size: 9pt; padding: 2px; border-style:dotted; border-width: 1px; margin: 0;  border-color: #ccc; vertical-align: top }
  td.null { text-decoration: line-through }
  tr:hover { background-color: #cedae9; }
  td.total {font-family: Arial, Helvetica, sans-serif; color: #000; font-size: 0.7em; padding: 2px; font-weight: bold; text-align: left}
//-->
</style>
</head><body>

";


echo "<h1>Holidays</h1>";


$sql = "SELECT user_name_first,user_name_second, user_id,holiday_timestamp,holiday_length,proj_name, holiday_paid FROM intranet_user_holidays, intranet_user_details LEFT JOIN intranet_timesheet ON user_id = ts_user LEFT JOIN intranet_projects ON ts_project = proj_id WHERE ts_day = holiday_date AND ts_month = holiday_month AND ts_year = holiday_year AND user_id = holiday_user AND holiday_year >= 2012  ORDER BY holiday_user, holiday_timestamp, proj_num DESC";

$sql = "SELECT * FROM intranet_user_holidays, intranet_user_details WHERE user_id = holiday_user AND holiday_paid = 1 AND holiday_year >= 2012 ORDER BY holiday_user, holiday_timestamp";

$result = mysql_query($sql, $conn) or die(mysql_error());

	echo "<table>";

$current_user = 0;
$holiday_total = 0;
$unpaid_total = 0;
	
while ($array = mysql_fetch_array($result)) {

if ($array['holiday_length'] == 1) {$holiday_paid = "Paid"; $holiday_total = $holiday_total + $array['holiday_length']; } else { $holiday_paid = "Unpaid"; $unpaid_total = $unpaid_total + $array['holiday_length'];  }

if ($current_user > 0 AND $current_user != $array['user_id']) { echo "<tr><td><strong>TOTAL UNPAID HOLIDAY</strong></td><td></td><td><strong>$unpaid_total</strong></td><td></td><td></td></tr>"; echo "<tr><td><strong>TOTAL PAID HOLIDAY</strong></td><td></td><td><strong>$holiday_total</strong></td><td></td><td></td></tr>"; $holiday_total = 0; $unpaid_total = 0; }


echo "<tr>";


echo "<td>" . $array['user_name_first'] . " " . $array['user_name_second'] . "</td>";

echo "<td>" . date ( "d M Y" , $array['holiday_timestamp']  ) . "</td>";

echo "<td>" .  $array['holiday_length'] . "</td>";

echo "<td>" .  $holiday_paid . "</td>";

echo "<td>" .  $array['proj_name'] . "</td>";

echo "</tr>";

$current_user = $array['user_id'];


}

echo "<tr><td><strong>TOTAL UNPAID HOLIDAY</strong></td><td></td><td><strong>$unpaid_total</strong></td><td></td><td></td></tr>";
echo "<tr><td><strong>TOTAL PAID HOLIDAY</strong></td><td></td><td><strong>$holiday_total</strong></td><td></td><td></td></tr>";



echo "</table>";


echo "</body>";

}


?>