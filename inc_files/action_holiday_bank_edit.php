<?php

function ActionBankHolidayDelete($bankholiday_delete) {
	
	global $conn;
	
	$bankholiday_delete = intval($bankholiday_delete);

	$sql = "DELETE FROM intranet_user_holidays_bank WHERE bankholidays_id = " . intval($bankholiday_delete) . " LIMIT 1";

	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);

}

function ActionBankHolidayAdd($date,$description) {
	
	global $conn;

	$sql = "INSERT INTO intranet_user_holidays_bank
			(
			bankholidays_id,
			bankholidays_description,
			bankholidays_day,
			bankholidays_month,
			bankholidays_year,
			bankholidays_datestamp,
			bankholiday_timestamp
			)
			VALUES
			(
			NULL,
			'" . addslashes(ucwords(trim($description))) . "',
			'" . explode("-",$date)[2] . "',
			'" . explode("-",$date)[1] . "',
			'" . explode("-",$date)[0] . "',
			'" . $date . "',
			" . DisplayDate($date) . "
			)
			";
			

	$result = mysql_query($sql, $conn);
	$array = mysql_fetch_array($result);

}

if ($_POST['action'] == "holiday_bank_edit" && intval($_POST['bankholiday_delete']) > 0) {
	
	ActionBankHolidayDelete($_POST['bankholiday_delete']);
	
} elseif ($_POST['action'] == "holiday_bank_edit" && intval($_POST['bankholiday_delete']) == 0) {
	
	ActionBankHolidayAdd($_POST['bankholiday_date'],$_POST['bankholiday_description']);
	
}