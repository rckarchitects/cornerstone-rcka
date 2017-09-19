<?php

$holiday_id_array = $_POST['holiday_id'];

$holiday_counter = 0;

if ($_POST[approve] == "delete") {

		while ($holiday_counter < count($holiday_id_array)) {

						$sql2 = "DELETE FROM intranet_user_holidays WHERE holiday_id = $holiday_id_array[$holiday_counter] LIMIT 1";
						$result = mysql_query($sql2, $conn) or die(mysql_error());
			
						
		$holiday_counter++;		
		}
		
} elseif ($_POST[approve] == "approve") {


		while ($holiday_counter < count($holiday_id_array)) {

						$sql2 = "UPDATE intranet_user_holidays SET holiday_approved = $_COOKIE[user] WHERE holiday_id = $holiday_id_array[$holiday_counter] LIMIT 1";
						$result = mysql_query($sql2, $conn) or die(mysql_error());
					
						
		$holiday_counter++;		
		}
		
}  elseif ($_POST[approve] == "to_paid") {


		while ($holiday_counter < count($holiday_id_array)) {

						$sql2 = "UPDATE intranet_user_holidays SET holiday_paid = 1 WHERE holiday_id = $holiday_id_array[$holiday_counter] LIMIT 1";
						$result = mysql_query($sql2, $conn) or die(mysql_error());
					
						
		$holiday_counter++;		
		}
		
}	 elseif ($_POST[approve] == "to_unpaid") {


		while ($holiday_counter < count($holiday_id_array)) {

						$sql2 = "UPDATE intranet_user_holidays SET holiday_paid = 0 WHERE holiday_id = $holiday_id_array[$holiday_counter] LIMIT 1";
						$result = mysql_query($sql2, $conn) or die(mysql_error());
					
						
		$holiday_counter++;		
		}
		
}	 elseif ($_POST[approve] == "to_studyleave") {


		while ($holiday_counter < count($holiday_id_array)) {

						$sql2 = "UPDATE intranet_user_holidays SET holiday_paid = 2 WHERE holiday_id = $holiday_id_array[$holiday_counter] LIMIT 1";
						$result = mysql_query($sql2, $conn) or die(mysql_error());
					
						
		$holiday_counter++;		
		}

}	 elseif ($_POST[approve] == "to_juryservice") {


		while ($holiday_counter < count($holiday_id_array)) {

						$sql2 = "UPDATE intranet_user_holidays SET holiday_paid = 3 WHERE holiday_id = $holiday_id_array[$holiday_counter] LIMIT 1";
						$result = mysql_query($sql2, $conn) or die(mysql_error());
					
						
		$holiday_counter++;		
		}
		
}	 elseif ($_POST[approve] == "to_full") {


		while ($holiday_counter < count($holiday_id_array)) {

						$sql2 = "UPDATE intranet_user_holidays SET holiday_length = 1 WHERE holiday_id = $holiday_id_array[$holiday_counter] LIMIT 1";
						$result = mysql_query($sql2, $conn) or die(mysql_error());
					
						
		$holiday_counter++;		
		}
		
}	 elseif ($_POST[approve] == "to_half") {


		while ($holiday_counter < count($holiday_id_array)) {

						$sql2 = "UPDATE intranet_user_holidays SET holiday_length = 0.5 WHERE holiday_id = $holiday_id_array[$holiday_counter] LIMIT 1";
						$result = mysql_query($sql2, $conn) or die(mysql_error());
					
						
		$holiday_counter++;		
		}
		
}	 elseif ($_POST[approve] == "unapprove") {


		while ($holiday_counter < count($holiday_id_array)) {

						$sql2 = "UPDATE intranet_user_holidays SET holiday_approved = NULL WHERE holiday_id = $holiday_id_array[$holiday_counter] LIMIT 1";
						$result = mysql_query($sql2, $conn) or die(mysql_error());
					
						
		$holiday_counter++;		
		}
		
}	 elseif ($_POST[approve] == "to_toil") {


		while ($holiday_counter < count($holiday_id_array)) {

						$sql2 = "UPDATE intranet_user_holidays SET holiday_paid = 4, holiday_length = 1 WHERE holiday_id = $holiday_id_array[$holiday_counter] LIMIT 1";
						$result = mysql_query($sql2, $conn) or die(mysql_error());
					
						
		$holiday_counter++;		
		}

}	 elseif ($_POST[approve] == "compassionate") {


		while ($holiday_counter < count($holiday_id_array)) {

						$sql2 = "UPDATE intranet_user_holidays SET holiday_paid = 5 WHERE holiday_id = $holiday_id_array[$holiday_counter] LIMIT 1";
						$result = mysql_query($sql2, $conn) or die(mysql_error());
					
						
		$holiday_counter++;		
		}
		
}	 elseif (is_numeric($_POST[approve])) {

		$holiday_assigned = intval($_POST[approve]);


		while ($holiday_counter < count($holiday_id_array)) {

						$sql2 = "UPDATE intranet_user_holidays SET holiday_assigned = $holiday_assigned WHERE holiday_id = $holiday_id_array[$holiday_counter] LIMIT 1";
						$result = mysql_query($sql2, $conn) or die(mysql_error());
					
						
		$holiday_counter++;		
		}
		
}

?>
