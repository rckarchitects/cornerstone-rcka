<?php

function GetLoadedExtensions() {

	global $user_usertype_current;
	
	if ($user_usertype_current > 3) {
	
		$array_extensions = get_loaded_extensions();
		$count = 1;
		
		echo "<h1>System Information</h1>";
		
		echo "<h2>PHP Version " . phpversion() . "</h2>";
		
		echo "<table>";
		echo "<tr><th>#</th><th>Extension</th></tr>";
		
		foreach ($array_extensions AS $extension) {
			
				echo "<tr><td>" . $count . "</td><td>" . $extension . "</td></tr>";
				
				$count++;
			
		}
		
		echo "</table>";
	
	}
	
}

function ShowDetailedServerInformation() {

	global $user_usertype_current;
	
	if ($user_usertype_current > 3) {


			echo "<h2>Server Information</h2>";
			echo "<p>Server IP Address:<br /><strong>&nbsp;".CleanUp($_SERVER["SERVER_ADDR"])."</strong></p>";
			echo "<p>Server Name:<br /><strong>&nbsp;".CleanUp($_SERVER["SERVER_NAME"])."</strong></p>";
			echo "<p>Client IP Address:<br /><strong>&nbsp;".CleanUp($_SERVER["REMOTE_ADDR"])."</strong></p>";
			echo "<p>PHP Version:<br /><strong>&nbsp;".phpversion ()."</strong></p>";
			echo "<p>Server Software:<br /><strong>&nbsp;".CleanUp($_SERVER["SERVER_SOFTWARE"])."</strong></p>";
	
	}

}

GetLoadedExtensions();

ShowDetailedServerInformation();