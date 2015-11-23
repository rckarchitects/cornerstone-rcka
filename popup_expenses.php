<?
// Include the cookie check information

include("inc_files/inc_checkcookie_logincheck.php");

// Array details = 1. Description, 2.VAT Status, 3. Receipt, 4. Disbursement,  5. P11d, Invoice, Category, Notes, User

$array_expenses =
array("0" =>
	array(
		0 => "Commuting on Public Transport (Paid on Credit Card)",			 	// description
		1 => "vat_none",									// VAT
		2 => "1",											// Receipt
		3 => "",											// Disbursement
		4 => "1",											// P11d Item
		5 => "",											// Invoice
		6 => "1",											// Category
		7 => "",											// Notes
		8 => $_COOKIE[user],								// User
		9 => "Transport - Commuting - P11d - Credit Card (VAT Exempt)"
		),
	"1" =>
	array(
		0 => "Commuting on Public Transport (Reimbursable)",			 	// description
		1 => "vat_none",									// VAT
		2 => "1",											// Receipt
		3 => "",											// Disbursement
		4 => "1",											// P11d Item
		5 => "",											// Invoice
		6 => "1",											// Category
		7 => "",											// Notes
		8 => $_COOKIE[user],								// User
		9 => "Transport - Commuting - P11d - Personal Card or Cash (VAT Exempt)"
		),
	"2" =>
	array(
		0 => "Food &amp; Drink for Office (Paid in Cash)",	// description
		1 => "vat_none",									// VAT
		2 => "1",											// Receipt
		3 => "",											// Disbursement
		4 => "0",											// P11d Item
		5 => "",											// Invoice
		6 => "6",											// Category
		7 => "",											// Notes
		8 => $_COOKIE[user],								// User
		9 => "Food &amp; Drink for Office - Cash (VAT Exempt)"
		),
	"3" =>
	array(
		0 => "Fuel (Paid on Company Credit Card)",			// description
		1 => "vat_inc",										// VAT
		2 => "1",											// Receipt
		3 => "",											// Disbursement
		4 => "1",											// P11d Item
		5 => "",											// Invoice
		6 => "1",											// Category
		7 => "",											// Notes
		8 => $_COOKIE[user],								// User
		9 => "Transport - Fuel - P11d - Credit Card (VAT Exempt)"
		),
	"4" =>
	array(
		0 => "Office Meal (Paid on Company Credit Card)",	// description
		1 => "vat_inc",										// VAT
		2 => "1",											// Receipt
		3 => "",											// Disbursement
		4 => "0",											// P11d Item
		5 => "",											// Invoice
		6 => "6",											// Category
		7 => "",											// Notes
		8 => $_COOKIE[user],								// User
		9 => "Office Meal - Credit Card (VAT Included)"
		),
	"5" =>
	array(
		0 => "Taxi - Paid in cash",	// description
		1 => "vat_none",										// VAT
		2 => "1",											// Receipt
		3 => "",											// Disbursement
		4 => "1",											// P11d Item
		5 => "",											// Invoice
		6 => "1",											// Category
		7 => "",											// Notes
		8 => $_COOKIE[user],								// User
		9 => "Transport - Taxi - P11d - Cash (VAT Exempt)"
		),
	"6" =>
	array(
		0 => "Home Broadband",	// description
		1 => "vat_inc",										// VAT
		2 => "1",											// Receipt
		3 => "",											// Disbursement
		4 => "1",											// P11d Item
		5 => "",											// Invoice
		6 => "7",											// Category
		7 => "",											// Notes
		8 => $_COOKIE[user],								// User
		9 => "Services - Broadband (Home) - P11d (VAT Included)"
		),
	"7" =>
	array(
		0 => "M6 Toll",	// description
		1 => "vat_inc",										// VAT
		2 => "",											// Receipt
		3 => "1",											// Disbursement
		4 => "1",											// P11d Item
		5 => "",											// Invoice
		6 => "1",											// Category
		7 => "Paid on company credit card",					// Notes
		8 => $_COOKIE[user],								// User
		9 => "Transport - Travel - P11d - M6 Toll - Credit Card (VAT Exempt)"
		),
	"8" =>
	array(
		0 => "M6 Toll",	// description
		1 => "vat_inc",										// VAT
		2 => "",											// Receipt
		3 => "1",											// Disbursement
		4 => "",											// P11d Item
		5 => "",											// Invoice
		6 => "1",											// Category
		7 => "Paid on company credit card",					// Notes
		8 => $_COOKIE[user],								// User
		9 => "Transport - Travel - Work Expense - M6 Toll - Credit Card (VAT Exempt)"
		),
	"9" =>
	array(
		0 => "Google Advertising",							// description
		1 => "vat_inc",										// VAT
		2 => "1",											// Receipt
		3 => "",											// Disbursement
		4 => "",											// P11d Item
		5 => "",											// Invoice
		6 => "11",											// Category
		7 => "Paid by Direct Debit",						// Notes
		8 => $_COOKIE[user],								// User
		9 => "Advertising - Google Adwords - Direct Debit (VAT Included)"
		),
	);

// Add to database

if ($_POST[action] == "add") {

	// echo "<p>Adding</p>";

	$ts_expense_array = $_POST[ts_expense_array];
		$ts_expense_desc = $array_expenses[$ts_expense_array]['0'];

	$ts_expense_project = 9;
	$ts_expense_value = $_POST[ts_expense_value];
		$ts_expense_day = $_POST[ts_expense_day];
		$ts_expense_month = $_POST[ts_expense_month];
		$ts_expense_year = $_POST[ts_expense_year];
		$ts_expense_date = mktime ( 12, 0, 0, $ts_expense_month, $ts_expense_day, $ts_expense_year );
		
		$vat_status = $array_expenses[$ts_expense_array]['1'];
		
	if ($vat_status == "vat_inc") { $ts_expense_vat = $ts_expense_value; $ts_expense_value = ($ts_expense_value * (1/1.15)); }
	elseif ($vat_status == "vat_none") { $ts_expense_vat = $ts_expense_value; }
	else { $ts_expense_vat = $ts_expense_value * 1.15; }
	$ts_expense_verified = 0;
	$ts_expense_invoiced = $array_expenses[$ts_expense_array]['5'];
	$ts_expense_receipt = $array_expenses[$ts_expense_array]['2'];
	$ts_expense_reimburse = $_POST[ts_expense_reimburse];
	$ts_expense_notes = $array_expenses[$ts_expense_array]['7'];
	$ts_expense_category = $array_expenses[$ts_expense_array]['6'];
	$ts_expense_p11d = $array_expenses[$ts_expense_array]['4'];
	$ts_expense_disbursement = $array_expenses[$ts_expense_array]['3'];
	$ts_expense_user = $array_expenses[$ts_expense_array]['8'];
	
	
	if (checkdate($ts_expense_month, $ts_expense_day, $ts_expense_year) == TRUE AND $ts_expense_value > 0) {
	
		// echo "<p>Date check OK</p>";


		$sql_add = "INSERT INTO intranet_timesheet_expense (
		ts_expense_id,
		ts_expense_project,
		ts_expense_value,
		ts_expense_date,
		ts_expense_desc,
		ts_expense_user,
		ts_expense_vat,
		ts_expense_invoiced,
		ts_expense_verified,
		ts_expense_receipt,
		ts_expense_reimburse,
		ts_expense_notes,
		ts_expense_category,
		ts_expense_disbursement,
		ts_expense_p11d
		) values (
		'NULL',
		'$ts_expense_project',
		'$ts_expense_value',
		'$ts_expense_date',
		'$ts_expense_desc',
		'$ts_expense_user',
		'$ts_expense_vat',
		'$ts_expense_invoiced',
		'$ts_expense_verified',
		'$ts_expense_receipt',
		'$ts_expense_reimburse',
		'$ts_expense_notes',
		'$ts_expense_category',
		'$ts_expense_disbursement',
		'$ts_expense_p11d'
		)";
		
		$result = mysql_query($sql_add, $conn) or die(mysql_error());
		$id_num = mysql_insert_id();
		// echo $sql_add;
		
		$ts_expense_id = mysql_insert_id();
		
	} // else { echo "<p>Date check failed</p>"; }
		
}

if ($_POST[ts_expense_day] == NULL) { $ts_expense_day = date("j",time()); } else { $ts_expense_day = $_POST[ts_expense_day]; }
if ($_POST[ts_expense_month] == NULL) { $ts_expense_month = date("n",time()); } else { $ts_expense_month = $_POST[ts_expense_month]; }
if ($_POST[ts_expense_year] == NULL) { $ts_expense_year = date("Y",time()); } else { $ts_expense_year = $_POST[ts_expense_year]; }

// Check for previously entered expenses

if ($_GET[startid] != NULL) { $startid = $_GET[startid]; } else { $startid = $id_num; }

// Include the header information

include("inc_files/inc_header.php");

		
$total_lines = count($array_expenses);
$counter = 0;	

// Header

print "<body>";

print "<div id=\"pagewrapper\">";

echo "<h1>Quick Add Expenses</h1>";

echo "<h2><a href=\"index2.php\"><< Return to intranet</a></h2>";

echo "<form action=\"popup_expenses.php?startid=$startid\" method=\"post\">";

echo "<table style=\"width: 100%;\">";
echo "<tr><td><strong>ID</strong></td><td><strong>Amount</strong></td><td><strong>Reimburse?</strong></td><td><strong>Details</strong></td><td colspan=\"3\"><strong>Date</strong></td></tr>";

echo "
<tr><td></td><td>&pound;<input type=\"text\" name=\"ts_expense_value\" maxlength=\"12\" /></td>
<td><input type=\"checkbox\" name=\"ts_expense_reimburse\" value=\"1\" /></td>
<td>";

	echo "<select name=\"ts_expense_array\">";
	
	while($counter < $total_lines) {
	if ($_POST[$ts_expense_array] == $counter) { $checked = "selected=\"selected\""; } else { $checked = NULL; }
	echo "<option value=\"$counter\" $checked>";
	echo $array_expenses[$counter]['9'];
	echo "</option>";
	$counter++;
	}

echo "</select></td>
<td>Day:&nbsp;<input type=\"text\" name=\"ts_expense_day\" value=\"$ts_expense_day\" maxlength=\"2\" size=\"6\" /></td>
<td>Month:&nbsp;<input type=\"text\" name=\"ts_expense_month\" value=\"$ts_expense_month\" maxlength=\"2\" size=\"6\" /></td>
<td>Year:&nbsp;<input type=\"text\" name=\"ts_expense_year\" value=\"$ts_expense_year\" maxlength=\"4\" size=\"6\" /></td></tr>
<tr><td colspan=\"7\"><input type=\"hidden\" name=\"action\" value=\"add\" /><input type=\"submit\" /></td></tr>
";

// List the entries so far

if ($startid > 0) {

$sql = "SELECT * FROM intranet_timesheet_expense WHERE ts_expense_id >= $startid AND ts_expense_user = $_COOKIE[user] OR ts_expense_id = $startid ORDER BY ts_expense_id DESC";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($array = mysql_fetch_array($result)) {
  
		$ts_expense_project = $array['ts_expense_project'];
		$ts_expense_value = $array['ts_expense_value'];
		$ts_expense_date = $array['ts_expense_date'];
		$ts_expense_desc = htmlspecialchars($array['ts_expense_desc']);
		$ts_expense_user = UserDetails($array['ts_expense_user']);
		$ts_expense_verified = $array['ts_expense_verified'];
		$ts_expense_vat = $array['ts_expense_vat'];
		$ts_expense_id = $array['ts_expense_id'];
		$ts_expense_invoiced = $array['ts_expense_invoiced'];
		$ts_expense_reimburse = $array['ts_expense_reimburse'];
		$ts_expense_notes = $array['ts_expense_notes'];
		$ts_expense_p11d = $array['ts_expense_p11d'];

		
		if ($ts_expense_p11d == 1) { $ts_expense_desc = $ts_expense_desc." - P11d Item"; }
		if ($ts_expense_reimburse == 1) { $ts_expense_desc = $ts_expense_desc." (To be reimbursed)"; }
		
		echo "<tr><td>$ts_expense_id</td><td colspan=\"2\">".MoneyFormat($ts_expense_vat)."</td><td>$ts_expense_desc</td><td colspan=\"2\">".TimeFormat($ts_expense_date)."</td><td><a href=\"index2.php?page=timesheet_expense_edit&amp;status=edit&amp;ts_expense_id=$ts_expense_id&amp;startid=$startid\"><img src=\"images/button_edit.png\" alt=\"Edit\" /></a></td></tr>";
		
		}
		
}

echo "</table></form>";

// echo "<p>$sql</p>";

print "</div>";

print "</body>";
print "</html>";

?>
