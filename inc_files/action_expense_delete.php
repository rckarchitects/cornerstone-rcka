<?php


$ts_expense_id = CleanNumber($_GET[ts_expense_id]);

if ($ts_expense_id == NULL && $user_usertype_current < 3) {
	$page_redirect = "timesheet_expense_list";
	$alertmessage = "The expense item you tried to delete does not exist.";
} else {

$nowtime = TimeFormat(time()) . " by user " . $_COOKIE[user];

$sql_edit = "UPDATE intranet_timesheet_expense SET
		ts_expense_project = NULL,
		ts_expense_value = NULL,
		ts_expense_date = '$_POST[ts_expense_date]',
		ts_expense_desc = '- Deleted -',
		ts_expense_user = '$_POST[ts_expense_user]',
		ts_expense_vat = NULL,
		ts_expense_receipt = NULL,
		ts_expense_invoiced = NULL,
		ts_expense_reimburse = NULL,
		ts_expense_notes = 'Deleted $nowtime',
		ts_expense_category = NULL,
		ts_expense_disbursement = NULL,
		ts_expense_p11d = NULL,
		ts_expense_verified = NULL
		WHERE ts_expense_id = $ts_expense_id LIMIT 1";
		
		$result = mysql_query($sql_edit, $conn) or die(mysql_error());
		$actionmessage = "Expense <strong>$ts_expense_id</strong> deleted successfully.";
		//echo $sql_edit;
		
}

?>