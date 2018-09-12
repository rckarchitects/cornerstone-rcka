<?php

function DateBookForm($date_id) {

    global $conn;

    if (intval($date_id) > 0) {
        $date_id = intval($date_id);
        $sql = "SELECT * FROM intranet_datebook WHERE date_id = " . $date_id . " LIMIT 1";
        $result = mysql_query($sql, $conn);
    }
    

    if (mysql_num_rows($result) > 0) {
        
        $array = mysql_fetch_array($result);
        $date_id = $array['date_id'];
        $date_day = $array['date_day'];
        $date_user = $array['date_user'];
		$date_notes = $array['date_notes'];
        $date_description = $array['date_description'];
        $date_warning = $array['date_warning'];
        $date_project = $array['date_project'];
        $date_category = $array['date_category'];

        echo "<h2>Edit Important Date</h2>";

    } else {

        unset($date_id);
        $date_day = date("Y-m-d",time());
        $date_user = $_COOKIE[user];
        echo "<h2>Add Important Date</h2>";
    }

    echo "<form action=\"index2.php?page=date_list\" method=\"post\">";

    echo "<p>Description<br />";
    echo "<input type=\"text\" value=\"" . $date_description . "\" name=\"date_description\" class=\"inputbox\" required=\"required\" maxlength=\"250\" /></p>";

    echo "<p>Date<br />";
    echo "<input type=\"date\" value=\"" . $date_day . "\" name=\"date_day\" class=\"inputbox\" required=\"required\" /></p>";

    echo "<p>Notifcation (weeks before)<br />";
    echo "<input type=\"number\" value=\"" . $date_warning . "\" name=\"date_warning\" class=\"inputbox\" /></p>";

    echo "<p>Project<br />";
    ProjectSelect($date_project,"date_project");
    echo "</p>";

    echo "<p>Category<br />";
    DataList("date_category","intranet_datebook");
    echo "<input type=\"text\" value=\"" . $date_category . "\" name=\"date_category\" list=\"date_category\" class=\"inputbox\" required=\"required\" /></p>";

    echo "<p>Notes<br />";
    TextAreaEdit();
    echo "<textarea name=\"date_notes\" style=\"width: 95%; height: 200px;\">" . $date_notes . "</textarea></p>";

    echo "<p>";
    echo "<input type=\"hidden\" name=\"date_user\" value=\"$date_user\" />";
    echo "<input type=\"hidden\" name=\"action\" value=\"date\" />";
    echo "<input type=\"hidden\" name=\"date_id\" value=\"" . $date_id . "\" />";
    echo "<input type=\"submit\" /></p>";

    echo "</form>";


}

echo "<h1>Datebook</h1>";


DateBookForm($_GET[date_id]);