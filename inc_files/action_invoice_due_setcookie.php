<?php

$nowtime = time();
$expiretime = $nowtime + 86400;

setcookie("invoiceduemessage",$nowtime,$expiretime);

?>