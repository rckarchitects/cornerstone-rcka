<?php

include("inc_files/inc_checkcookie.php");

/***************************************************************************

PHP vCard class v2.0
(c) Kai Blankenhorn
www.bitfolge.de/en
kaib@bitfolge.de


This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

***************************************************************************/


function encode($string) {
	return escape(quoted_printable_encode($string));
}

function escape($string) {
	return str_replace(";","\;",$string);
}

// taken from PHP documentation comments
function quoted_printable_encode($input, $line_max = 76) {
	$hex = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
	$lines = preg_split("/(?:\r\n|\r|\n)/", $input);
	$eol = "\r\n";
	$linebreak = "=0D=0A";
	$escape = "=";
	$output = "";

	for ($j=0;$j<count($lines);$j++) {
		$line = $lines[$j];
		$linlen = strlen($line);
		$newline = "";
		for($i = 0; $i < $linlen; $i++) {
			$c = substr($line, $i, 1);
			$dec = ord($c);
			if ( ($dec == 32) && ($i == ($linlen - 1)) ) { // convert space at eol only
				$c = "=20"; 
			} elseif ( ($dec == 61) || ($dec < 32 ) || ($dec > 126) ) { // always encode "\t", which is *not* required
				$h2 = floor($dec/16); $h1 = floor($dec%16); 
				$c = $escape.$hex["$h2"].$hex["$h1"]; 
			}
			if ( (strlen($newline) + strlen($c)) >= $line_max ) { // CRLF is not counted
				$output .= $newline.$escape.$eol; // soft line break; " =\r\n" is okay
				$newline = "    ";
			}
			$newline .= $c;
		} // end of for
		$output .= $newline;
		if ($j<count($lines)-1) $output .= $linebreak;
	}
	return trim($output);
}

class vCard {
	var $properties;
	var $filename;
	
	function setPhoneNumber($number, $type="") {
	// type may be PREF | WORK | HOME | VOICE | FAX | MSG | CELL | PAGER | BBS | CAR | MODEM | ISDN | VIDEO or any senseful combination, e.g. "PREF;WORK;VOICE"
		$key = "TEL";
		if ($type!="") $key .= ";".$type;
		$key.= ";ENCODING=QUOTED-PRINTABLE";
		$this->properties[$key] = quoted_printable_encode($number);
	}
	
	// UNTESTED !!!
	function setPhoto($type, $photo) { // $type = "GIF" | "JPEG"
		$this->properties["PHOTO;TYPE=$type;ENCODING=BASE64"] = base64_encode($photo);
	}
	
	function setFormattedName($name) {
		$this->properties["FN"] = quoted_printable_encode($name);
	}
	
	function setName($family="", $first="", $additional="", $prefix="", $suffix="") {
		$this->properties["N"] = "$family;$first;$additional;$prefix;$suffix";
		$this->filename = "$first $family .vcf";
		$name = $prefix.$first.$additional.$family.$suffix;
		if ($this->properties["FN"]=="") $this->setFormattedName(trim($name));
	}
	
	function setBirthday($date) { // $date format is YYYY-MM-DD
		$this->properties["BDAY"] = $date;
	}
	
	function setAddress($postoffice="", $extended="", $street="", $city="", $region="", $zip="", $country="", $type="WORK;POSTAL") {
	// $type may be DOM | INTL | POSTAL | PARCEL | HOME | WORK or any combination of these: e.g. "WORK;PARCEL;POSTAL"
		$key = "ADR";
		if ($type!="") $key.= ";$type";
		$key.= ";ENCODING=QUOTED-PRINTABLE";
		$this->properties[$key] = encode($name).";".encode($extended).";".encode($street).";".encode($city).";".encode($region).";".encode($zip).";".encode($country);
		
		if ($this->properties["LABEL;$type;ENCODING=QUOTED-PRINTABLE"] == "") {
			//$this->setLabel($postoffice, $extended, $street, $city, $region, $zip, $country, $type);
		}
	}
	
	function setLabel($postoffice="", $extended="", $street="", $city="", $region="", $zip="", $country="", $type="HOME;POSTAL") {
		$label = "";
		if ($postoffice!="") $label.= "$postoffice\r\n";
		if ($extended!="") $label.= "$extended\r\n";
		if ($street!="") $label.= "$street\r\n";
		if ($zip!="") $label.= "$zip ";
		if ($city!="") $label.= "$city\r\n";
		if ($region!="") $label.= "$region\r\n";
		if ($country!="") $country.= "$country\r\n";
		
		$this->properties["LABEL;$type;ENCODING=QUOTED-PRINTABLE"] = quoted_printable_encode($label);
	}
	
	function setEmail($address) {
		$this->properties["EMAIL;INTERNET"] = $address;
	}
	
	function setCompany($company) {
		$this->properties["ORG"] = $company;
	}
	
	function setTitle($title) {
		$this->properties["TITLE"] = $title;
	}

	function setNote($note) {
		$this->properties["NOTE;ENCODING=QUOTED-PRINTABLE"] = quoted_printable_encode($note);
	}
	
	function setURL($url) {
	// $type may be WORK | HOME
		$key = "URL;WORK";
		$this->properties[$key] = $url;
	}
	
	function getVCard() {
		$text = "BEGIN:VCARD\r\n";
		$text.= "VERSION:2.1\r\n";
		foreach($this->properties as $key => $value) {
			$text.= "$key:$value\r\n";
		}
		$text.= "REV:".date("Y-m-d")."T".date("H:i:s")."Z\r\n";
		$text.= "MAILER:PHP vCard class by Kai Blankenhorn\r\n";
		$text.= "END:VCARD\r\n";
		return $text;
	}
	
	function getFileName() {
		return $this->filename;
	}
}

// GET THE SPECIFIC DETAILS FOR THE CONTACT IN QUESTION

	$sql = "SELECT * FROM contacts_contactlist WHERE contact_id = '$_GET[contact_id]' LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());


$array = mysql_fetch_array($result);

$contact_id = $array['contact_id'];
$contact_prefix = $array['contact_prefix'];
$contact_namefirst = $array['contact_namefirst']." ";
$contact_namesecond = $array['contact_namesecond']." ";
$contact_title = $array['contact_title'];
$contact_company = $array['contact_company'];
$contact_telephone = $array['contact_telephone'];
$contact_telephone_home = $array['contact_telephone_home'];
$contact_mobile = $array['contact_mobile'];
$contact_fax = $array['contact_fax'];
$contact_email = $array['contact_email'];
$contact_address = $array['contact_address'];
$contact_city = $array['contact_city'];
$contact_postcode = $array['contact_postcode'];
$contact_sector = $array['contact_sector'];
$contact_reference = $array['contact_reference'];
$contact_department = $array['contact_department'];
$contact_added = $array['contact_added'];

if ($contact_company > 0) {

$sql_company = "SELECT * FROM contacts_companylist WHERE company_id = $contact_company LIMIT 1";
$result_company = mysql_query($sql_company, $conn) or die(mysql_error());
$array_company = mysql_fetch_array($result_company);

		$company_name = $array_company['company_name'];
		$company_address = $array_company['company_address'];
		$company_city = $array_company['company_city'];
		$company_county = $array_company['company_county'];
		$company_postcode = $array_company['company_postcode'];
		$company_phone = $array_company['company_phone'];
		$company_fax = $array_company['company_fax'];
		$company_web = $array_company['company_web'];
}

// RECONCILE THE INDIVIDUAL AND COMPANY TELEPHONE AND FAX NUMBERS

if ($contact_telephone == "") { $output_telephone = $company_phone; } else { $output_telephone = $contact_telephone; }
if ($contact_fax == "") { $output_fax = $company_fax; } else { $output_fax = $contact_fax; }

$display_name = $contact_namefirst." ".$contact_namesecond;

$contact_added = date("jS F Y", $contact_added);

// DETERMINE THE CONTACT JOB TITLE

$sql2 = "SELECT * FROM contacts_titlelist WHERE title_id = '$contact_title' LIMIT 1";
$result2 = mysql_query($sql2, $conn) or die(mysql_error());
$array2 = mysql_fetch_array($result2);
$title_name = $array2['title_name'];

// WRAP UP OTHER INFORMATION INTO A NOTE

if ($contact_reference != "") {$contact_reference = "Notes:\n".$contact_reference; }
$contact_reference = $contact_reference."\nContact Added to Database:\n".$contact_added;

//  USAGE EXAMPLE

$v = new vCard();

$v->setPhoneNumber($output_telephone, "TEL;PREF;WORK;VOICE");
$v->setPhoneNumber($contact_telephone_home, "TEL;HOME;VOICE");
$v->setPhoneNumber($output_fax, "TEL;WORK;FAX");
$v->setPhoneNumber($contact_mobile, "TEL;CELL;VOICE");
$v->setName($contact_namesecond, $contact_namefirst, "", "");
$v->setTitle($title_name);
$v->setBirthday("");
$v->setCompany($company_name);
$v->setLabel("", "", $contact_address, $contact_city, "", $contact_postcode);
$v->setAddress("", "", $company_address, $company_city, "", $company_postcode);
$v->setEmail($contact_email);
$v->setNote($contact_reference);
$v->setURL($company_web);

$output = $v->getVCard();
$filename = $v->getFileName();

//Header("Content-Disposition: attachment; filename=$filename");
//Header("Content-Length: ".strlen($output));
//Header("Connection: close");
//Header("Content-Type: text/x-vCard; name=$filename");

echo $output;
?>
