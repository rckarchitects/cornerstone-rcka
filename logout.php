<?php

setcookie("user", NULL, time()-60);
setcookie("name", NULL, time()-60);
setcookie("phonemessageview", NULL, time()-60);
header ("Location: login.php");
				
?>