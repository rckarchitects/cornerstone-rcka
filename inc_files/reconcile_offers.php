<?php

$list = 5;
$start = $_GET[start];

if ($start == NULL) { $start = 0; }

	include_once("../secure/database.inc");
	
	$array_offers = $_POST['detail_offers'];
	$array_detail_id = $_POST['detail_id'];
	$array_confirm = $_POST['confirm'];
	
	$counter = 0;
	
	$count = count ( $array_offers ) ;
	
	
	echo "<h3>Rows: $count</h3><p>";
	print_r($array_detail_id);
	echo "</p>";
	echo "<p>";
	print_r($array_offers);
	echo "</p>";
	echo "<p>";
	print_r($array_confirm);
	echo "</p>";
	

		while ($counter < $count) {
		
			$offers = $array_offers[$counter];
			$notice = $detail_id[$counter];
			$confirm = $array_confirm[$counter];
		
			echo "<p>$counter: $notice - $offers - $confirm</p>";
		
					if ($notice > 0 && $offers > 0 && $confirm == 1) {
					
						$sql_offers = "UPDATE tenderfeed_details SET detail_offers = '$offers' WHERE detail_id = '$notice'";
						
						echo "<blockquote>$sql_offers</blockquote>";
						
						mysql_query($sql_offers, $conn) or die(mysql_error());
						
					} elseif ($confirm == 2)  {
					
						$sql_offers = "UPDATE tenderfeed_details SET detail_offers = NULL WHERE detail_id = '$notice'";
						
						echo "<blockquote>$sql_offers</blockquote>";
						
						mysql_query($sql_offers, $conn) or die(mysql_error());
					
					}
		
					
			
				$counter++;
		
		}
	
	
	
	
	
	
	
	$nextpage = $start + $list;
	
	
	
	

echo "<h1>Enter offers to reconcile</h1>";

	$sql_offers = "SELECT detail_id, detail_notice, detail_offers FROM tenderfeed_details WHERE detail_offers IS NOT NULL ORDER BY detail_id DESC LIMIT $start,$list";
	$result_offers = mysql_query($sql_offers, $conn) or die(mysql_error());
	
	echo "<h2>$sql_offers</h2><hr />";
	
	echo "<form action=\"reconcile_offers.php?start=$nextpage\" method=\"post\">";
	
	$counter = 0;
	
	while ($array_offers = mysql_fetch_array($result_offers)) {
	
	$detail_id = $array_offers['detail_id'];
	$detail_notice = $array_offers['detail_notice'];
	$detail_offers = $array_offers['detail_offers'];
	
	$link = "../index.php?page=sesame&amp;sub=notice&amp;tf_id=$detail_notice";
	
	if ( $detail_offers != NULL) {
	$detail_offers = preg_replace("/[^0-9,.]/", "", $detail_offers);
	$message = $detail_offers;
	} else {
	$message = "None";
	}
	
	//echo "<h3>$message - $detail_value_adjusted</h3>";
	
	//if ( strlen ( $detail_offers ) != NULL ) {
	
	if ( $detail_offers != NULL ) {

	if ( is_numeric ($detail_offers) == TRUE) { $checked1 = "checked=\"checked\""; unset($checked2); } else { unset($checked1); $checked2 = "checked=\"checked\""; }
	
	if ($message != $detail_offers) { $background = " style=\"background-color:yellow;\""; } else { unset($background); }
	
	echo "<p $background>$counter: <a href=\"$link\">$detail_notice</a> [$detail_id]: $message</p>&nbsp;<input type=\"radio\" $checked2 name=\"confirm[$counter]\" value=\"0\" /><input type=\"radio\" $checked1 name=\"confirm[$counter]\" value=\"1\" /><input type=\"hidden\" value=\"$detail_id\" name=\"detail_id[$counter]\" /><input type=\"text\" name=\"detail_offers[$counter]\" value=\"$detail_offers\" /> - Field: $message&nbsp;<input type=\"radio\" name=\"confirm[$counter]\" value=\"2\" />&nbsp;Null?</p><hr />";
	
	//echo "<p><a href=\"$link\">$detail_notice</a> [$detail_id]: $detail_value</p>&nbsp;<input type=\"checkbox\" $checked2 name=\"detail_id[$counter]\" value=\"$detail_id\" /><input type=\"text\" name=\"detail_value_adjusted[$counter]\" value=\"$detail_value_adjusted\" /></p><hr />";

	$counter++;
	
	} else { echo "<p>ID: " . $detail_id . ", Field: NULL</p>"; }


	
	}
	
	echo "<input type=\"submit\" />";
	
	echo "</form>";

echo "<hr />";



echo "<p><a href=\"reconcile_offers?start=$nextpage\">Next $list</a></p>";


?>