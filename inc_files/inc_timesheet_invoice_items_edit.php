<?php

if ($_GET[proj_id] > 0 OR $_POST[proj_id] > 0) {

	//	if ($_GET[proj_id] > 0) { $proj_id = $_GET[proj_id] } elseif ($_POST[proj_id] > 0) { $proj_id = $_POST[proj_id];	}
		
$invoice_selected_id = $_GET[invoice_id];

echo "<h1>Invoices</h1>";

// Determine whether we are adding a new invoice or editing an existing one

if ($_GET[invoice_item_id] != NULL) {
	$sql = "SELECT * FROM intranet_timesheet_invoice_item WHERE invoice_item_id = '$_GET[invoice_item_id]' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$array = mysql_fetch_array($result);
		$invoice_item_id = $array['invoice_item_id'];
		$invoice_item_invoice = $array['invoice_item_invoice'];
		$invoice_item_stage = $array['invoice_item_stage'];
		$invoice_item_desc = $array['invoice_item_desc'];
		$invoice_item_novat = $array['invoice_item_novat'];
		$invoice_item_vat = $array['invoice_item_vat'];
		
	echo "<h2>Edit Items</h2>";
	ProjectSubMenu($proj_id,$user_usertype_current,"invoice_admin",1);
	echo "<form action=\"index2.php?page=timesheet_invoice_view\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"proj_id\" value=\"proj_id\" />";
	echo "<input type=\"hidden\" name=\"action\" value=\"invoice_item_edit\" />";
	echo "<input type=\"hidden\" name=\"invoice_item_id\" value=\"$invoice_item_id\" />";
		
}	else	{

		$nowtime = time();
		$thentime = $nowtime + $invoice_time_by;

		$invoice_item_invoice = $_POST[invoice_item_invoice];
		$invoice_item_stage = $_POST[invoice_item_stage];
		$invoice_item_desc = $_POST[invoice_item_desc];
		$invoice_item_novat = $_POST[invoice_item_novat];
		$invoice_item_vat = $_POST[invoice_item_vat];
		$invoice_item_stage = $_POST[invoice_item_stage];
	
	echo "<h2>Add Items</h2>";
	ProjectSubMenu($proj_id,$user_usertype_current,"invoice_admin",1);
	echo "<p>This form allows you to add items of work or expenses to an invoice already registered on the system.</p>";
	echo "<form action=\"index2.php?page=timesheet_invoice_list\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"action\" value=\"invoice_item_edit\" />";
	echo "<input type=\"hidden\" name=\"proj_id\" value=\"$proj_id\" />";

}

	echo "<table summary=\"Form to add or edit a new invoice item\">";
	
	// Project list

	echo "<tr><td>Invoice</td><td colspan=\"2\">";

		echo "<select name=\"invoice_item_invoice\">";
		$sql = "SELECT * FROM intranet_timesheet_invoice WHERE invoice_project = '$proj_id' order by invoice_ref DESC";
		$result = mysql_query($sql, $conn) or die(mysql_error());
		echo "<option value=\"\" class=\"inputbox\">-- None --</option>";
		while ($array = mysql_fetch_array($result)) {
		$invoice_ref = $array['invoice_ref'];
		$invoice_id = $array['invoice_id'];
		echo "<option value=\"$invoice_id\" class=\"inputbox\"";
		if ($invoice_item_invoice == $invoice_id OR $invoice_selected_id == $invoice_id) { echo " selected";}
		echo ">$invoice_ref</option>";
		}
		echo "</select></td></tr>";


	// Text field


		echo "<tr><td>Description</td><td colspan=\"2\"><textarea name=\"invoice_item_desc\" rows=\"12\" cols=\"38\">$invoice_item_desc</textarea></td></tr>";
		
	echo "
	<tr>
	<td>Item Value<br /><font class=\"minitext\">(excluding VAT)</font></td>
	<td colspan=\"2\"><input type=\"text\" name=\"invoice_item_novat\" class=\"inputbox\" size=\"24\" value=\"$invoice_item_novat\" /></td>
	</tr>
	";
	
	echo "
	<tr>
	<td>VAT</td>
	<td colspan=\"2\"><input type=\"checkbox\" name=\"invoice_item_vat\" value=\"1\"";
	if ($invoice_item_vat > $invoice_item_novat) { echo " checked "; }
	echo "/></td>
	</tr>
	";

// Fee Stage

	echo "<tr><td>Fee Stage</td><td colspan=\"2\">";

		echo "<select name=\"invoice_item_stage\">";
		$sql = "SELECT ts_fee_id, ts_fee_stage, ts_fee_text FROM intranet_timesheet_fees WHERE ts_fee_project = '$proj_id' order by ts_fee_time_begin";
		$result = mysql_query($sql, $conn) or die(mysql_error());

			if ($invoice_fee_stage == NULL) { echo "<option value=\"\""; echo " selected"; echo ">-- None --</option>"; }
		
		while ($array = mysql_fetch_array($result)) {
				$ts_fee_id = $array['ts_fee_id'];
				$ts_fee_stage = $array['ts_fee_stage'];
				$ts_fee_text = $array['ts_fee_text'];
				echo "<option value=\"$ts_fee_id\"";
				
						if ($ts_fee_stage > 0) {
									$sql_riba = "SELECT * FROM riba_stages WHERE riba_id = '$ts_fee_stage' LIMIT 1";
									$result_riba = mysql_query($sql_riba, $conn) or die(mysql_error());
									$array_riba = mysql_fetch_array($result_riba);
									$riba_letter = $array_riba['riba_letter'];
									$riba_desc = $array_riba['riba_desc'];
									$ts_fee_text = $riba_letter." - ".$riba_desc;
						}
				
				if ($invoice_item_stage == $ts_fee_id) { echo " selected";}
				echo ">$ts_fee_text</option>";
		}
		echo "</select></td></tr>";
		
	// Close the table

	echo "</table>";
	echo "<p><input type=\"submit\" value=\"Submit\" /></p>";
	echo "</form>";
	
} else {

echo "<h1>Invoices</h1><h2>Add Item</h2>";
ProjectSubMenu($proj_id,$user_usertype_current,"invoice_admin",1);
echo "<p>You need to specify a project before you can view this page</p>";

}
