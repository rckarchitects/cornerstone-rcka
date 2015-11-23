<?php

function PushOverAlert($input) {

	$token = hkfcZkjAP4nDFWAOd3wsLHAmlbyxcn;

	$user = lpnuzCnCRuY5i1Slvkupo9dWONBgRc;

	$message = $input;

	$address = "https://api.pushover.net/1/messages.json";
	
// The following code is from here: http://stackoverflow.com/questions/8638984/send-post-data-to-php-without-using-an-html-form	
	
// 	var theForm, newInput1, newInput2, newInput3;
	  // Start by creating a <form>
// 	  theForm = document.createElement('form');
// 	  theForm.action = $address;
// 	  theForm.method = 'post';
// 	  // Next create the <input>s in the form and give them names and values
// 	  newInput1 = document.createElement('input');
//  	  newInput1.type = 'hidden';
//   newInput1.name = 'token';
// 	  newInput1.value = $token;
// 	  newInput2 = document.createElement('input');
// 	  newInput2.type = 'hidden';
// 	  newInput2.name = 'user';
// 	  newInput2.value = $user;
// 	  newInput3 = document.createElement('input');
// 	  newInput3.type = 'hidden';
// 	  newInput3.name = 'message';
// 	  newInput3.value = $message;
// 	  // Now put everything together...
// 	  theForm.appendChild(newInput1);
// 	  theForm.appendChild(newInput2);
// 	  theForm.appendChild(newInput3);
// 	  // ...and it to the DOM...
// 	  document.getElementById('hidden_form_container').appendChild(theForm);
// 	  // ...and submit it
// 	  theForm.submit();

}


// Check that the required values have been entered, and alter the page to show if these values are invalid

if ($_GET[tasklist_id] == "" OR $_GET[tasklist_percent] == "") { $alertmessage = "Incorrect values entered."; $page = $_SERVER[QUERY_STRING]; }

else {

// This determines the page to show once the form submission has been successful

$page = "tasklist_view";

// Construct the MySQL instruction to add these entries to the database

$sql_edit = "UPDATE intranet_tasklist SET
tasklist_percentage = '$_GET[tasklist_percent]' WHERE tasklist_id = '$_GET[tasklist_id]' LIMIT 1
";

$result = mysql_query($sql_edit, $conn) or die(mysql_error());

// Now set the task complete if tasklist_percent = 100


	if ($_GET[tasklist_percent] == 100) {
	
		$nowtime = time();
	
		$sql_edit2 = "UPDATE intranet_tasklist SET
tasklist_completed = '$nowtime' WHERE tasklist_id = '$_GET[tasklist_id]' LIMIT 1
";
		$result2 = mysql_query($sql_edit2, $conn) or die(mysql_error());
		
		$task_description = "Task Completed";
		
		PushOverAlert($task_description);
		
	} else {
	
		$sql_edit2 = "UPDATE intranet_tasklist SET
tasklist_completed = '' WHERE tasklist_id = '$_GET[tasklist_id]' LIMIT 1
";
		$result2 = mysql_query($sql_edit2, $conn) or die(mysql_error());
	
	}

$actionmessage = "The task was updated successfully.";

}

?>
