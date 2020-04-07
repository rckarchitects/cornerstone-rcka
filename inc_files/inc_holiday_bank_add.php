<?php

echo "<h1>Holiday Calendar</h1>";

echo "<h2>Bank Holidays</h2>";

function ListRecentBankHolidays($number, $time) {
	
	global $conn;
	
	if (!$time) { $time = time(); } else { $time = intval($time); }
	if (!$number) { $number = 6; } else { $number = intval($number); }
	
	$sql = "SELECT * FROM intranet_user_holidays_bank WHERE bankholiday_timestamp ORDER BY bankholiday_timestamp DESC LIMIT " . $number;
	$result = mysql_query($sql, $conn);
	
		
		echo "<div class=\"page\"><table><tr><th>Date</th><th colspan=\"2\">Description</th></tr>";
		
		echo "<form method=\"post\" action=\"index2.php?page=holiday_bank_add\"><tr><td><input type=\"date\" value=\"\" name=\"bankholiday_date\" required=\"required\" /></td><td><input type=\"text\" value=\"\" name=\"bankholiday_description\" required=\"required\" list=\"bankholidays\" /></td><td><input type=\"submit\" value=\"Add\" /><input type=\"hidden\" name=\"action\" value=\"holiday_bank_edit\" /></td></tr></form>";
		
		
		
		if (mysql_num_rows($result) > 0) {
		
			while ($array= mysql_fetch_array($result)) {
				
				echo "<tr><td>" . TimeFormatDay($array['bankholiday_timestamp'])  . "</td><td>" . $array['bankholidays_description'] . "</td><td><form method=\"post\" action=\"index2.php?page=holiday_bank_add\"><input type=\"submit\" value=\"Delete\" /><input type=\"hidden\" name=\"action\" value=\"holiday_bank_edit\" /><input type=\"hidden\" name=\"bankholiday_delete\" value=\"" . $array['bankholidays_id'] . "\" /></form></td></tr>";
				
			}
		
		} else {
			
			echo "<tr><td colspan=\"3\">Nothing found.</td></tr>";
			
		}
		
			
		
		echo "</table></div>";
		
		BankHolidayDropdown();

}

function BankHolidayDropdown() {

	global $conn;
	
	$sql = "SELECT bankholidays_description FROM intranet_user_holidays_bank GROUP BY bankholidays_description ORDER BY bankholidays_description";
	$result = mysql_query($sql, $conn);
	
	if (mysql_num_rows($result) > 0) {
	
		echo "<datalist id=\"bankholidays\">";
		
			while ($array= mysql_fetch_array($result)) {
			
				echo "<option value=\"" . $array['bankholidays_description'] . "\"></option>";
				
			}		
		
		echo "</datalist>";
	
	}
	

}

ListRecentBankHolidays(12, time());