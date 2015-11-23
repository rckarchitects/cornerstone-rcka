<?php

//function InsertPage($input) {}

include "inc_files/inc_checkcookie.php";

$remove_symbols = array("Â","Ã");
$swap_1 = array("â‚¬", "\n", "&amp;");
$replace_1 = array("€", ", ", "&");

$format_bg_r = "0";
$format_bg_g = "0";
$format_bg_b = "0";

if ($settings_pdffont != NULL) {
$format_font = $settings_pdffont;
$format_font_2 = $settings_pdffont.".php";
} else {
$format_font = "franklingothicbook";
$format_font_2 = "franklingothicbook.php";
}

if ($_GET[invoice_id] != NULL) { $invoice_id = CleanNumber($_GET[invoice_id]); $viewall = ""; }
elseif ($_POST[viewall] != NULL) { $viewall = "yes"; }
else { header ("Location: ../index2.php"); }

if ($_POST[order_by] != NULL) { $order_by = CleanUp($_POST[order_by]); } else { $order_by = "invoice_id"; }

if ($_POST[account_id] != NULL) { $account_test = "AND invoice_account = ".CleanNumber($_POST[account_id]); }

//  Use FDPI to get the template

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdi.php');

$pdf= new fpdi();

$pagecount = $pdf->setSourceFile("pdf/template.pdf");
$tplidx = $pdf->ImportPage(1);

$pdf->AddFont($format_font,'',$format_font_2);

$counter = 1;

if ($viewall == "yes") {
			$nowtime = time();
			$sql_complete = "SELECT invoice_id FROM intranet_timesheet_invoice WHERE invoice_date < $nowtime $account_test ORDER BY $order_by";
			$result_complete = mysql_query($sql_complete, $conn) or die(mysql_error());
			
} else {
			$nowtime = time();
			$sql_complete = "SELECT invoice_id FROM intranet_timesheet_invoice WHERE invoice_id = $invoice_id LIMIT 1";
			$result_complete = mysql_query($sql_complete, $conn) or die(mysql_error());
}

while ($array_complete = mysql_fetch_array($result_complete)) {
			
			$invoice_id = $array_complete['invoice_id'];
			
			

$pdf->addPage();
$pdf->useTemplate($tplidx, 0, 0, 210, 297);


// Begin creating the page

$project_counter = 1;
$page_count = 1;

$pdf->SetTextColor($format_bg_r, $format_bg_g, $format_bg_b);

// Get the relevant infomation from the Invoice Database

	$sql_invoice = "SELECT * FROM intranet_timesheet_invoice WHERE invoice_id = '$invoice_id' LIMIT 1";
	$result_invoice = mysql_query($sql_invoice, $conn) or die(mysql_error());
	$array_invoice = mysql_fetch_array($result_invoice);
	$invoice_ref = $array_invoice['invoice_ref'];
	$invoice_date = $array_invoice['invoice_date'];
	$invoice_due = $array_invoice['invoice_due'];
	$invoice_project = $array_invoice['invoice_project'];
	$invoice_paid = $array_invoice['invoice_paid'];
	$invoice_notes = $array_invoice['invoice_notes'];
	$invoice_account = $array_invoice['invoice_account'];
	$invoice_client = $array_invoice['invoice_client'];
	$invoice_purchase_order = $array_invoice['invoice_purchase_order'];
	$invoice_text = InvoiceDueDays($array_invoice['invoice_text'], $invoice_due, $invoice_date);
	
		$invoice_due_days = $invoice_due - $invoice_date;
		$invoice_due_days = $invoice_due_days / 86400;
		settype($invoice_due_days, "integer");
	
	$invoice_ref_print = strtoupper($invoice_ref);
	
	if ($viewall == "yes") { $invoice_ref_print = $invoice_ref_print." (ID: ".$invoice_id.")"; }
	
	$invoice_date_print = TimeFormat($invoice_date);
	
// Account name

	if ($invoice_account > 0) {
	
		$sql_account = "SELECT * FROM intranet_account WHERE account_id = '$invoice_account' LIMIT 1";
		$result_account = mysql_query($sql_account, $conn) or die(mysql_error());
		$array_account = mysql_fetch_array($result_account);
		$account_name = $array_account['account_name'];
		
	}
	
// Get the relevant infomation from the Project

	$sql_project = "SELECT proj_num, proj_name, proj_client_contact_id FROM intranet_projects WHERE proj_id = '$invoice_project' LIMIT 1";
	$result_project = mysql_query($sql_project, $conn) or die(mysql_error());
	$array_project = mysql_fetch_array($result_project);
	$proj_num = $array_project['proj_num'];
	$proj_name = $array_project['proj_name'];
	$proj_client_contact_id = $array_project['proj_client_contact_id'];
	$invoice_project_print = $proj_num." ".$proj_name;
	
	if ($invoice_client > 0) { $proj_client_contact_id = $invoice_client; }
	
// Get the client name and address

	$sql_client = "SELECT * FROM contacts_contactlist WHERE contact_id = '$proj_client_contact_id' LIMIT 1";
	$result_client = mysql_query($sql_client, $conn) or die(mysql_error());
	$array_client = mysql_fetch_array($result_client);
	$contact_prefix = $array_client['contact_prefix'];
	$contact_namefirst = trim(DeCode($array_client['contact_namefirst']));
	$contact_namesecond = trim(DeCode($array_client['contact_namesecond']));
	$contact_company = DeCode($array_client['contact_company']);
	$contact_address = DeCode($array_client['contact_address']);
	$contact_city = $array_client['contact_city'];
	$contact_county = $array_client['contact_county'];
	$contact_postcode = $array_client['contact_postcode'];
	$contact_country = $array_client['contact_country'];
	
// Get contact prefix

	$sql_prefix = "SELECT * FROM contacts_prefixlist WHERE prefix_id = '$contact_prefix' LIMIT 1";
	$result_prefix = mysql_query($sql_prefix, $conn) or die(mysql_error());
	$array_prefix = mysql_fetch_array($result_prefix);
	if (mysql_num_rows > 0) {
	$prefix_name = DeCode($array_prefix['prefix_name'])." ";
	} else { $prefix_name = NULL; }
	
	if ($contact_company > 0) {
	
	$sql_company = "SELECT * FROM contacts_companylist WHERE company_id = '$contact_company' LIMIT 1";
	$result_company = mysql_query($sql_company, $conn) or die(mysql_error());
	$array_company = mysql_fetch_array($result_company);
	$contact_companyname = DeCode($array_company['company_name']);
	$contact_address = $array_company['company_address'];
	$contact_city = $array_company['company_city'];
	$contact_county = $array_company['company_county'];
	$contact_postcode = $array_company['company_postcode'];
	$contact_country = $array_company['company_country'];
		
	} else {
	$contact_companyname = NULL;
	}
	
	if ($company_country != NULL) { $contact_country = $company_country; }
	
		// Determine the country
		$sql_country = "SELECT country_printable_name FROM intranet_contacts_countrylist where country_id = '$contact_country' LIMIT 1";
		$result_country = mysql_query($sql_country, $conn);
		$array_country = mysql_fetch_array($result_country);
		$country_printable_name = $array_country['country_printable_name'];

if ($contact_companyname) { $contact_address_print = $contact_companyname; }
	
		else { $contact_address_print = $prefix_name.$contact_namefirst." ".$contact_namesecond; }
		
		if ($contact_address) { $contact_address_print = $contact_address_print."\n".$contact_address; }
		if ($contact_city) { $contact_address_print = $contact_address_print."\n".$contact_city; }
		if ($contact_county) { $contact_address_print = $contact_address_print."\n".$contact_county; }
		if ($contact_postcode) { $contact_address_print = $contact_address_print."\n".$contact_postcode; }
		if ($country_printable_name) { $contact_address_print = $contact_address_print."\n".$country_printable_name; }

		



//Invoice Title
	$pdf->SetXY(10,45);
	$pdf->SetFont($format_font,'',26);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(0,10,$invoice_ref_print);
	
//Invoice Title
	$pdf->SetXY(10,55);
	$pdf->SetFont($format_font,'',20);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(0,10,$invoice_project_print);

//Invoice Date
	$pdf->SetXY(10,62);
	$pdf->SetFont($format_font,'',20);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(0,10,$invoice_date_print);

//Heading Titles Heading
	$pdf->SetFont($format_font,'',9);
	$pdf->SetTextColor(0,0, 0);
	
	$pdf->SetXY(10,80);
	$pdf->Cell(0,4,"from");

	$pdf->SetXY(57.5,80);
	$pdf->Cell(0,4,"to");

	$pdf->SetXY(105,80);
	$pdf->Cell(0,4,"invoice no.");
	
	$pdf->SetXY(152.5,80);
	$pdf->Cell(0,4,"invoice date");
	
	if ($invoice_purchase_order != NULL) {
	
		$pdf->SetXY(105,100);
		$pdf->Cell(0,4,"purchase order no.");
	
	}
	
// If paid

if ($invoice_paid > 0 AND $viewall == "yes") {
	$paid_text = "Paid ".TimeFormat($invoice_paid);
	$pdf->SetFont('Helvetica','B',30);
	$pdf->SetTextColor(204, 0, 0);
	$pdf->SetXY(10,10);
	$pdf->MultiCell(190,15,$paid_text,'',R);
}

elseif ($viewall == "yes") {
	$pdf->SetFont('Helvetica','B',30);
	$pdf->SetTextColor(204, 0, 0);
	$pdf->SetXY(10,10);
	$pdf->MultiCell(190,15,"Unpaid",'',R);
}

if ($viewall == "yes") {
	$pdf->SetFont('Helvetica','B',16);
	$pdf->SetTextColor(204, 0, 0);
	$pdf->SetXY(10,20);
	$pdf->MultiCell(190,15,$account_name,'',R);
}
	
//Heading Titles Text
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont($format_font,'',9);
	
	$pdf->SetXY(10,88);
	$company_address = $settings_companyname."\n".$settings_companyaddress;
	$pdf->MultiCell(0,4,$company_address);

	$pdf->SetXY(57.5,88);
	if (trim($contact_address_print) == "") { $pdf->SetTextColor(255, 0, 0); $contact_address_print = "Client Details Required"; }
	$pdf->MultiCell(45,4,$contact_address_print,0,L);
	$pdf->SetTextColor(0, 0, 0);
	$y = $pdf->GetY();

	$pdf->SetXY(105,88);
	$pdf->Cell(0,4,$invoice_ref);
	
	$pdf->SetXY(152.5,88);
	$pdf->Cell(0,4,$invoice_date_print);
	
	//if ($invoice_purchase_order != NULL) {
		$pdf->SetXY(105,108);
		$pdf->Cell(0,4,$invoice_purchase_order);
	//}
	
	$y = $y + 10;
	

	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(0.1);
	$pdf->SetXY(40,$y);
	$pdf->SetFont($format_font,'',10);
	$pdf->Cell(150,6,'Description of Work Undertaken','B','','',false);
	
	$x = 40;
	$y = $pdf->GetY() + 7;
	
	$pdf->SetXY($x,$y);
	
	$invoice_novat_total = 0;
	$invoice_vat_total = 0;
	$invoice_novat_total = 0;
	$invoice_total = 0;

	$ts_expense_total_vat = 0;
	$ts_disbursement_total = 0;
	
	$grand_total = 0;
	
// Pull the corresponding results from the Invoice Item list
			$sql2 = "SELECT * FROM intranet_timesheet_invoice_item WHERE invoice_item_invoice = '$invoice_id' ORDER BY invoice_item_desc";
			$result2 = mysql_query($sql2, $conn) or die(mysql_error());
			
			if (mysql_num_rows($result2) > 0) {
			
			while ($array2 = mysql_fetch_array($result2)) {
					$invoice_item_id = $array2['invoice_item_id'];
					$invoice_item_desc = RemoveShit($array2['invoice_item_desc']);
					$invoice_item_novat = $array2['invoice_item_novat'];
					$invoice_item_vat = $array2['invoice_item_vat'];
					
					$invoice_item_novat_print = CashFormat($invoice_item_novat);
					$invoice_item_vat_print = CashFormat($invoice_item_vat);
					
					$invoice_novat_total = $invoice_novat_total + $invoice_item_novat;
					$invoice_vat_total = $invoice_vat_total + $invoice_item_vat;
					
				$pdf->MultiCell(100,4,$invoice_item_desc,0,'L');
				$y_new = $pdf->GetY();
				$pdf->SetXY(140,$y);
				
				if ( $invoice_item_vat - $invoice_item_novat == 0 ) { $include_vat_line = 1; } else { $include_vat_line = 0; }
				
				if ($invoice_item_novat == 0) { unset($invoice_item_novat_print); unset($invoice_item_vat_print); }
				
				if ($include_vat_line != 1) {
						$pdf->MultiCell(50,4,$invoice_item_novat_print,'',R);
				} else {
						$pdf->MultiCell(50,4,$invoice_item_vat_print,'',R);
				}
				$y = $y_new +1;
				
				$pdf->SetXY($x,$y);
				
			}
			
			}
			
			if ($invoice_item_novat == 0 AND $invoice_item_desc == NULL) {
				$pdf->MultiCell(100,8,"- None -");
				$y_new = $pdf->GetY();
				$pdf->SetXY(140,$y);
				$pdf->MultiCell(50,8,"",'',R);
				$y = $y_new;
				//InsertPage();
			}
			
			$grand_total = $grand_total + $invoice_vat_total;

	
		// InsertPage();
		

		

	
	// Expenses
		$pdf->SetXY(40,$y);
		$pdf->Cell(150,5,'Expenses','B','','',false);
		
		$ts_expense_total = 0;
		$ts_expense_total_vat = 0;
	
		
// Pull the corresponding results from the Expenses
			$y = $y + 8;
			$x = 40;
			$pdf->SetXY($x,$y);
			$sql3 = "SELECT * FROM intranet_timesheet_expense WHERE ts_expense_invoiced = '$invoice_id' AND ts_expense_disbursement = '1' ORDER BY ts_expense_date";
			$result3 = mysql_query($sql3, $conn) or die(mysql_error());
			while ($array3 = mysql_fetch_array($result3)) {
					$ts_expense_desc = html_entity_decode($array3['ts_expense_desc']);
					
					
					$ts_expense_desc = RemoveShit($array3['ts_expense_desc']);
					
					if ($viewall == "yes") {
					$ts_expense_desc = $ts_expense_desc." (".TimeFormat($array3['ts_expense_date']).")";
					}
					
					if ($viewall == "yes") { $ts_expense_desc = $array3['ts_expense_id'].". ".$ts_expense_desc = $ts_expense_desc; }
					
					$ts_expense_value = $array3['ts_expense_value'];
					$ts_expense_print = CashFormat($ts_expense_value);
					$ts_expense_total = $ts_expense_total + $ts_expense_value;
					
					$ts_expense_vat = $array3['ts_expense_vat'];
					$ts_expense_total_vat = $ts_expense_total_vat + $ts_expense_vat;
					
					
				$pdf->MultiCell(100,5,$ts_expense_desc,0,'L');
				$y_new = $pdf->GetY();
				$pdf->SetXY(140,$y);
				$pdf->MultiCell(50,5,$ts_expense_print,'',R);
				$y = $y_new;
				//InsertPage();
				$pdf->SetXY($x,$y);
				
			}
			
			$grand_total = $grand_total + $ts_expense_total_vat;
			
			
			if ($ts_expense_total == 0) {
				$pdf->MultiCell(100,4,"- None -");
				$y_new = $pdf->GetY();
				$pdf->SetXY(140,$y);
				$pdf->MultiCell(50,4,"",'',R);
				$y = $y_new;
				//InsertPage();
			}
		
		
		
		
		
		
	// Now the VAT Line
	
	if ( ($invoice_vat_total != $invoice_novat_total) OR ($ts_expense_total_vat != $ts_expense_total) ) { $include_vat_line = 1; } else { $include_vat_line = 0; }
	
	$invoice_vat_total_integer = abs($invoice_vat_total);
	$invoice_novat_total_integer = abs($invoice_novat_total);
	
	$ts_expense_total_vat_integer = abs($ts_expense_total_vat);
	$ts_expense_total_integer = abs($ts_expense_total);
	
	if (($invoice_vat_total - $invoice_novat_total) != 0) {
		$vat_percent = round(((($invoice_vat_total_integer / $invoice_novat_total_integer) * 100 ) - 100),2); }
	elseif (($ts_expense_total_vat - $ts_expense_total) != 0) {
		$vat_percent = round(((($ts_expense_total_vat_integer / $ts_expense_total_integer) * 100 ) - 100),2);
	}
	
	if ($include_vat_line == 1) {
	
		$y = $pdf->GetY();
		$y = $y + 4;
		
		$vat_percent_print = "VAT "; if ($vat_percent > 100 and $vat_percent < 100) { $vat_percent_print = $vat_percent_print . " (at ".$vat_percent."%)"; }
	
		$pdf->SetXY(40,$y);
		$pdf->Cell(150,5,$vat_percent_print,'B','','',false);
		$y = $y + 8;
		$pdf->SetXY($x,$y);
		
		$vat_total = ($invoice_vat_total - $invoice_novat_total) + $ts_expense_total_vat - $ts_expense_total;
		$vat_total_print = CashFormat($vat_total);
		$pdf->SetXY(40,$y);
		$pdf->Cell(100,4,'');
		$pdf->SetXY(140,$y);
		$pdf->MultiCell(50,4,$vat_total_print,'',R);
		
		//InsertPage();
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		// Disbursements
		$y = $y + 6;
		$pdf->SetXY(40,$y);
		$pdf->Cell(150,5,'Disbursements','B','','',false);
		
		$ts_expense_total = 0;
		$ts_expense_total_vat = 0;
		//InsertPage();
		
// Pull the corresponding results from the Expenses (Disbursements only)
			$y = $y + 8;
			$x = 40;
			$pdf->SetXY($x,$y);
			$sql3 = "SELECT * FROM intranet_timesheet_expense LEFT JOIN intranet_user_details ON user_id = ts_expense_user WHERE ts_expense_invoiced = '$invoice_id' AND ts_expense_disbursement <> '1' ORDER BY ts_expense_date";
			$result3 = mysql_query($sql3, $conn) or die(mysql_error());
			
			$count_disbursement = 0;
			
			while ($array3 = mysql_fetch_array($result3)) {
					$ts_expense_desc = html_entity_decode($array3['ts_expense_desc']);
					$user_initials = $array3['user_initials'];
					$ts_expense_reimburse = $array3['ts_expense_reimburse'];
					
					
					$ts_expense_desc = RemoveShit($array3['ts_expense_desc']);
					
					if ($ts_expense_reimburse == 1) { $ts_expense_desc = $ts_expense_desc . " (" . $user_initials . ") "; }
					
					if ($viewall == "yes") {
					$ts_expense_desc = $ts_expense_desc." (".TimeFormat($array3['ts_expense_date']).")";
					}
					
					if ($viewall == "yes") { $ts_expense_desc = $array3['ts_expense_id'].". ".$ts_expense_desc = $ts_expense_desc; }
					
					$ts_disbursement_vat = $array3['ts_expense_vat'];
					$ts_disbursement_print = CashFormat($ts_disbursement_vat);
					$ts_disbursement_total = $ts_disbursement_total + $ts_disbursement_vat;
	
					
				$pdf->MultiCell(100,4,$ts_expense_desc,0,'L');
				$y_new = $pdf->GetY();
				$pdf->SetXY(140,$y);
				$pdf->MultiCell(50,4,$ts_disbursement_print,'',R);
				$y = $y_new + 1;
				//InsertPage();
				$pdf->SetXY($x,$y);
				
				$count_disbursement++;
				
			}
			
			if ($count_disbursement == 0) { $pdf->MultiCell(0,4,"- None -",'','L'); }
			
		
		$y = $pdf->GetY();
		$y = $y + 2;
				
		//InsertPage();
		
		$grand_total = $grand_total + $ts_disbursement_total;
				
	// Total
		
		$y = $y + 2;
	
		$pdf->SetLineWidth(0.1);
		$pdf->SetXY(40,$y);
		$pdf->SetFont($format_font,'',11);
		$pdf->Cell(150,6,'TOTAL NOW DUE','B','','',false);
			
		$invoice_total = CashFormat($grand_total);
		$y = $pdf->GetY();
		$y = $y + 6;
		$pdf->SetXY(140,$y);
		$pdf->MultiCell(50,5,$invoice_total,'',R);
		

		// And the smallprint
		
	$y = $y + 12;
	
	// if ($y > 230) { $y = $y + 20; $pdf->SetY($y); $pdf->SetFont($format_font,'',9); $pdf->MultiCell(180,5,"To next page...",'','R'); $pdf->addPage(); $y = 20; $pdf->SetXY(40,$y); $pdf->MultiCell(0,5,"...from previous page"); $y = $y + 15; } 
		
	$pdf->SetXY(40,$y);
	$pdf->SetFont($format_font,'',9);
	$pdf->MultiCell(140,3.75,$invoice_text);

$counter++;
}


// and send to output

if ($viewall != NULL) {

$file_name = "Invoice_Complete.pdf";

} else {

$file_name = "Invoice_".$invoice_ref."_".Date("Y",$invoice_date)."-".Date("m",$invoice_date)."-".Date("d",$invoice_date).".pdf";

}

$pdf->Output($file_name,I);
?>
