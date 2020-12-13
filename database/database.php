<?php
    $dbconnect = mysqli_connect("localhost", "root", "") or die ("Could not connect to the server.");
    mysqli_select_db($dbconnect, "ingweb") or die ("The database could not be selected.");
    mysqli_set_charset($dbconnect, 'utf8');
?>