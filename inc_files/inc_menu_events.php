<?

$time_current = time();

$sql = "SELECT * FROM events WHERE event_time > '$time_current' order by event_time LIMIT 6";
$result = mysql_query($sql, $conn) or die(mysql_error());
$counter = 1;

print "<h1 class=\"heading_side\">Events</h1>";
print "<p class=\"menu_bar\">";
if ($user_usertype_current > 1) {
	print "<a href=\"index2.php?page=event_add\" class=\"menu_tab\">Add New</a>";
}
print "<a href=\"index2.php?page=event_view\" class=\"menu_tab\">View All</a>";
print "</p>";


if (mysql_num_rows($result) > 0 ) {

print "<ul class=\"button_left\">";

while ($array = mysql_fetch_array($result)) {
$event_title = $array['event_title'];
$event_time = $array['event_time'];
$event_id = $array['event_id'];

$event_date_expanded = date("D, d F Y - g.i a", $event_time);

if ($event_time - $time_current <= 86400 ) {
print "<font class=\"highlight\">";
}

print "<li>".$event_title."<br />".$event_date_expanded."</li>";

}

print "</ul>";

} else {
print "<ul class=\"button_left\"><li>There are no future events on the system</li></ul>";
}

 ?>
