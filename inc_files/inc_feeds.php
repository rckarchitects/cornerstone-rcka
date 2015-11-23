<?php


if ($_GET[type] == "lad") {
$feed_address = "http://www.londonarchitecturediary.com/rss";
$feed_title = "Upcoming Events from <a href=\"http://www.londonarchitecturediary.com\">London Architecture Diary</a>";
} elseif ($_GET[type] == "competitions") {
$feed_address = "http://www.bdonline.co.uk/XmlServers/navsectionRSS.aspx?navsectioncode=682";
$feed_title = "UK Competitions from <a href=\"http://www.bdonline.co.uk/practice-and-it/competitions/uk/\">Building Design</a>";
} elseif ($_GET[type] == "ojeu") {
$feed_address = "http://ted.europa.eu/TED/rss/CustomRSSFeedGenerator/5273/en";
$feed_title = "Latest OJEUs from <a href=\"http://ted.europa.eu\">TED</a>";
} else {
$feed_address = "http://www.bdonline.co.uk/XmlServers/navsectionRSS.aspx?navsectioncode=890";
$feed_title = "News from <a href=\"http://www.bdonline.co.uk/news/\">Building Design</a>";
}

echo "<h1>Internet Feeds</h1>";

echo "<p class=\"submenu_bar\"><a href=\"index2.php?page=feeds&amp;type=news\" class=\"submenu_bar\">News</a><a href=\"index2.php?page=feeds&amp;type=competitions\" class=\"submenu_bar\">Competitions</a><a href=\"index2.php?page=feeds&amp;type=ojeu\" class=\"submenu_bar\">OJEU Notices</a><a href=\"index2.php?page=feeds&amp;type=lad\" class=\"submenu_bar\">London Architecture Diary</a></p>";

echo "<h2>$feed_title</h2>";

//FUNCTION TO PARSE RSS IN PHP 4 OR PHP 4
	function parseRSS($url) { 
 
	//PARSE RSS FEED
        $feedeed = implode('', file($url));
        $parser = xml_parser_create();
        xml_parse_into_struct($parser, $feedeed, $valueals, $index);
        xml_parser_free($parser);
 
	//CONSTRUCT ARRAY
        foreach($valueals as $keyey => $valueal){
            if($valueal['type'] != 'cdata') {
                $item[$keyey] = $valueal;
			}
        }
 
        $i = 0;
 
        foreach($item as $key => $value){
 
            if($value['type'] == 'open') {
 
                $i++;
                $itemame[$i] = $value['tag'];
 
            } elseif($value['type'] == 'close') {
 
                $feed = $values[$i];
                $item = $itemame[$i];
                $i--;
 
                if(count($values[$i])>1){
                    $values[$i][$item][] = $feed;
                } else {
                    $values[$i][$item] = $feed;
                }
 
            } else {
                $values[$i][$value['tag']] = $value['value'];  
            }
        }
 
	//RETURN ARRAY VALUES
        return $values[0];
	} 
 
 
	/******************************************************************************************************************
	  SAMPLE USAGE OF FUNCTION
	******************************************************************************************************************/
 
	//PARSE THE RSS FEED INTO ARRAY
	$xml = parseRSS($feed_address);
 
	//SAMPLE USAGE OF
	echo "<table>";
	
	function CleanFeed($input) {
	$stupid_array = array("‘","’");
	$output = str_replace($stupid_array,"'",$input);
	$output = strip_tags($output,"<br>");
	$output = str_replace("<br /><br />","<br />",$output);
	$output = trim($output, "<br />");
	$output = mb_convert_encoding($output, 'HTML-ENTITIES', "UTF-8");
	return $output;
	}
	
	
	foreach($xml['RSS']['CHANNEL']['ITEM'] as $item) {
			$feed_title = CleanFeed($item['TITLE']) ;
			$feed_link = $item['LINK'] ;
			$feed_description = CleanFeed($item['DESCRIPTION']) ;
			$feed_date = CleanFeed($item['PUBDATE']) ;
	        echo("<tr><td style=\"font-weight: bold;\"><a href=\"$feed_link\" target=\"_blank\">$feed_title</a></td><td><p>$feed_description<br /><a href=\"$feed_link\">[more]</a></p><p class=\"minitext\" style=\"text-align: right; color: #999;\">Updated: $feed_date</p></td></tr>");
	}
	echo "</table>";
		
?>