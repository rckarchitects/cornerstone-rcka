<?php

function ActionDateEdit() {

    global $conn;

    if ($_POST[date_id]) { $date_id = intval($_POST[date_id]); } else { unset($date_id); }

    $date_day = $_POST[date_day];
    $date_user = intval($_POST[date_user]);
    $date_description = addslashes($_POST[date_description]);
    $date_warning = intval($_POST[date_warning]);
    $date_project = intval($_POST[date_project]);
    $date_category = addslashes($_POST[date_category]);
    $date_notes = addslashes($_POST[date_notes]);

    if ($date_id) {

        $sql = "UPDATE intranet_datebook SET
        date_day = '$date_day',
        date_user = '$date_user',
        date_description = '$date_description',
        date_warning = '$date_warning',
        date_project = '$date_project',
        date_category = '$date_category',
        date_notes = '$date_notes'
        WHERE date_id = '$date_id' LIMIT 1
        ";

       $result = mysql_query($sql, $conn) or die(mysql_error());
        $actionmessage = "<p>Date for <a href=\"index2.php?page=date_show&amp;date_id=$date_id\">" . $date_description . "</a> has been updated successfully.</p>";
        AlertBoxInsert($_COOKIE[user],"Date Updated",$actionmessage,$date_id,86400,0,$date_project);


    } else {

        $sql = "INSERT INTO intranet_datebook (
            date_id,
            date_day,
            date_user,
            date_description,
            date_warning,
            date_project,
            date_category,
            date_notes
            ) VALUES (
            NULL,
            '$date_day',
            '$date_user',
            '$date_description',
            '$date_warning',
            '$date_project',
            '$date_category',
            '$date_notes'
            )";

        
        $date_user = mysql_insert_id();
       $result = mysql_query($sql, $conn) or die(mysql_error());
        $actionmessage = "<p>Date for <a href=\"index2.php?page=date_show&amp;date_id=$date_id\">" . $date_description . "</a> has been added successfully.</p>";
        AlertBoxInsert($_COOKIE[user],"Date Added",$actionmessage,$date_id,86400,0,$date_project);


    }


}

//echo "<h1>SUBMIT</h1>";

ActionDateEdit();