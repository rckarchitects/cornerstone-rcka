<?php

print "<h1 class=\"heading_side\">Search</h1>";
print "<form action=\"index2.php?page=search\" method=\"post\">";

if ($_POST[tender_search] == "yes") { $checked = " checked = \"checked\" "; } else { unset($checked) ; }

print "<p><input type=\"search\" name=\"keywords\" value=\"$_POST[keywords]\" id=\"txtfld\" onClick=\"SelectAll('txtfld');\" />&nbsp;<input type=\"submit\" value=\"Go\" /><br /><input type=\"checkbox\" name=\"tender_search\" value=\"yes\" $checked />&nbsp;<span class=\"minitext\">Search tenders?</span></p>";
print "</form>";

?>