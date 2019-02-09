<?php


// Check the IP address of the user

include("inc_files/inc_ipcheck.php");

// Perform the top-of-page security check

include "inc_files/inc_checkcookie.php";

if ($user_usertype_current > 4) {

	$DBUSER=$database_username;
	$DBPASSWD=$database_password;
	$DATABASE=$database_name;
	$SERVER=$database_location;

	$filename = "backup-" . date("Y-m-d-G-i-s") . ".sql.gz";
	$mime = "application/x-gzip";

	header( "Content-Type: " . $mime );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

	$cmd = "mysqldump --no-create-db=true -h $SERVER -u $DBUSER -p $DATABASE --password=$DBPASSWD | gzip --best";
	
	passthru( $cmd );

	exit(0);

}