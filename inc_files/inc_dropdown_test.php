<form name="firstexample" action="something.php" method="post">

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
			oList.options[0] = new Option('Please make a selection', '');
			var arrX = assocArray[vValue];
			for (var i = 0; i < arrX.length; i = i + 2){
				if (arrX[i] != 'EOF') oList.options[oList.options.length] = new Option(arrX[i + 1].split('&amp;').join('&'), arrX[i]);
			}
			if (oList.options.length == 1){
				oList.selectedIndex=0;
				if (oList.onchange) oList.onchange();
			}
		} else {
			oList.options[0] = new Option('None found', '');
		}
	}
}

//-->
</script>

<p>
<select name='example2_list1' size='1' style='width:200;' onchange='comboItemSelected(this,this.form.example2_list2);'>
<option>-- Choose Project --</option>
<option value="1">Algodata Infosystems</option>
<option value="2">Binnet and Hardley</option>
<option value="3">Five Lakes Publishing</option>
<option value="4">GGGG</option>
<option value="5">Lucerne Publishing</option>
<option value="6">New Moon Books</option>
<option value="7">Ramona Publishers</option>
<option value="8">Scootney Books</option>

</select>

</p><p>

<select name="example2_list2" style="width:400px;">
<option>-- n/a -- </option>
<script type="text/javascript">

if (!assocArray) var assocArray = new Object();
assocArray["example2_list1=3"] = new Array(
    "PS7777","Emotional Security: A New Algorithm",
    "PS2091","Is Anger the Enemy?",
    "PS2106","Life Without Fear",
    "PS3333","Prolonged Data Deprivation: Four Case Studies",
    "BU2075","You Can Combat Computer Stress!",
    "EOF");
assocArray["example2_list1=2"] = new Array(
    "PS1372","Computer Phobic &amp; Non-Phobic Individuals: Behavior Variations",
    "TC4203","Fifty Years in Buckingham Palace Kitchens",
    "TC3218","Onions, Leeks, and Garlic: Cooking Secrets of the Mediterranean",
    "MC2222","Silicon Valley Gastronomic Treats",
    "TC7777","Sushi, Anyone?",
    "MC3021","The Gourmet Microwave",
    "MC3026","The Psychology of Computer Cooking",
    "EOF");
assocArray["example2_list1=1"] = new Array(
    "PC1035","But Is It User Friendly?",
    "BU1111","Cooking with Computers: Surreptitious Balance Sheets",
    "PC9999","Net Etiquette",
    "PC8888","Secrets of Silicon Valley",
    "BU7832","Straight Talk About Computers",
    "BU1032","The Busy Executive's Database Guide",
    "EOF");
</script>
</select>

</p>

</form>










