<?php

if ($user_usertype_current == 5) {

		print "<h1>PHP Configuration</h2>";
		
		print phpinfo();
		
} else {

		print "<p>You do not have sufficient rights to view this page.</p>";
		
}