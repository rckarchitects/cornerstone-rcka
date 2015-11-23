<?php

$user_id = CleanUp($_GET[user_id]);

$sql = "SELECT * FROM intranet_user_details WHERE user_id = $user_id LIMIT 1";
$result = mysql_query($sql, $conn);
$array = mysql_fetch_array($result);

$user_name_first = $array['user_name_first'];
$user_name_second = $array['user_name_second'];
$user_num_mob = $array['user_num_mob'];
$user_email = $array['user_email'];

print "<h1>".$user_name_first."&nbsp;".$user_name_second."</h1>";


// Project Page Menu
print "<p class=\"menu_bar\">";
	if ($user_usertype_current > 3 OR $user_id_current == $user_id) {
		print "<a href=\"index2.php?page=user_edit&amp;status=edit&amp;user_id=$user_id\">Edit</a> | ";
	}
	
	print "<a href=\"index2.php?page=phonemessage_edit&amp;status=new&amp;user_id=$user_id\">New Telephone Message</a>";
	
print "</p>";

print "<fieldset><legend>Contact Details</legend>";

	print 	"<table>
			<tr><td>E</td><td><a href=\"mailto:$user_email\">$user_email</a></td></tr>
			<tr><td>M</td><td>$user_num_mob</td></tr>
			</table>";



print "</fieldset>";


?>



