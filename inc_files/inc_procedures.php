<?php
print "<h1>Procedures</h1>";
$file = "library/expenses.txt";
$file_contents = file_get_contents($file);
$file_title = explode("\n", $file_contents);
print "<h2>".$file_title[0]."</h2>";
$file_title = array_slice($file_title, 1);
$file_contents = implode("<br />", $file_title);
echo "<p>".$file_contents."</p>";
?>
