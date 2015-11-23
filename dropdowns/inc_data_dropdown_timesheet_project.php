<script type="text/javascript">
<!--

function comboItemSelected(oList1,oList2){
	if (oList2!=null){
		clearComboOrList(oList2);
			if (oList1.selectedIndex == -1){
		oList2.options[oList2.options.length] = new Option('Please make a selection from the list', '');
		} else {
			fillCombobox(oList2, oList1.name + '=' + oList1.options[oList1.selectedIndex].value);
		}
	}
}

function clearComboOrList(oList){
	for (var i = oList.options.length - 1; i >= 0; i--){
		oList.options[i] = null;
	}
		oList.selectedIndex = -1;
	if (oList.onchange)	oList.onchange();
}

function fillCombobox(oList, vValue){

	if (vValue != '') {
		if (assocArray[vValue]){
			oList.options[0] = new Option('-- Current Stage --', '');
			var arrX = assocArray[vValue];
			for (var i = 0; i < arrX.length; i = i + 2){
				if (arrX[i] != 'EOF') oList.options[oList.options.length] = new Option(arrX[i + 1].split('&amp;').join('&'), arrX[i]);
			}
			if (oList.options.length == 1){
				oList.selectedIndex=0;
				if (oList.onchange) oList.onchange();
			}
		} else {
			oList.options[0] = new Option('-- None --', '');
		}
	}
}

//-->
</script>

<?php if ($TSFormat != "popup") { echo "<h3>Project</h3><p>"; } ?>


<select name="ts_project"  onchange="comboItemSelected(this,this.form.ts_stage_fee);">

<?php

	if ($ts_project > 0) {
		$sql = "SELECT * FROM intranet_projects WHERE proj_active = '1' order by proj_num";
	} else {
		$sql = "SELECT * FROM intranet_projects WHERE proj_active = '1' order by proj_num";
	}
	$result = mysql_query($sql, $conn) or die(mysql_error());
	while ($array = mysql_fetch_array($result)) {
	$proj_num = $array['proj_num'];
	$proj_name = $array['proj_name'];
	$proj_id = $array['proj_id'];
	print "<option value=\"$proj_id\"";
		if ($proj_id == $ts_project) { print " selected "; }
	print ">$proj_num $proj_name</option>";
	}
	
?>

</select>

<?php if ($TSFormat != "popup") { echo "</p><h3>Stage</h3><p>"; } else { echo "&nbsp;"; } ?>

<select name="ts_stage_fee">

<?php

if ($ts_fee_id > 0) {
print "<option value=\"$ts_fee_id\">$ts_fee_text</option>";
} else {
print "<option value=\"\">-- None --</option>";
}


?>

<script type="text/javascript">


if (!assocArray) var assocArray = new Object();

<?php

	$fee_repeat = NULL;
	$sql2 = "SELECT * FROM intranet_timesheet_fees, intranet_projects WHERE ts_fee_project = proj_id ORDER BY proj_num, ts_fee_time_begin";
	$result2 = mysql_query($sql2, $conn) or die(mysql_error());
	while ($array2 = mysql_fetch_array($result2)) {
	$ts_fee_text = $array2['ts_fee_text'];
	$ts_fee_id = $array2['ts_fee_id'];
	$ts_fee_stage = $array2['ts_fee_stage'];
		if ($ts_fee_stage > 0) {
				$sql3 = "SELECT riba_letter, riba_desc FROM riba_stages WHERE riba_id = '$ts_fee_stage' LIMIT 1";
				$result3 = mysql_query($sql3, $conn) or die(mysql_error());
				$array3 = mysql_fetch_array($result3);
				$ts_fee_text = $array3['riba_letter']." - ".$array3['riba_desc'];
		}
	$proj_id = $array2['proj_id'];
	$proj_num = $array2['proj_nume'];
	$proj_name = $array2['proj_name'];
		if ($fee_repeat != $proj_id AND $fee_repeat != NULL) { print " \"EOF\"); \n"; }
		if ($fee_repeat != $proj_id) { print "\nassocArray[\"ts_project=$proj_id\"] = new Array("; }
		print "\"$ts_fee_id\",\"$ts_fee_text\",";
		$fee_repeat = $proj_id;
	}
	print "	\"EOF\");	\n"; 
	
?>
</script>
</select>

<?php if ($TSFormat != "popup") { echo "</p>"; }










