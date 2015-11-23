<?php

if ($_POST[listmax] != "" ) {
setcookie("listmax",CleanUp($_POST[listmax]));
$listmax = CleanUp($_POST[listmax]);
} elseif ($_COOKIE[listmax] != "") {
$listmax = $_COOKIE[listmax];
} else { $listmax = 5; }

?>
