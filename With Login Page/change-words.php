<?php

    include "database/database.php";
    $sql = "SELECT COUNT(`ID`) ID_COUNT FROM `words` WHERE `LEARNED` = 1"; // LEARNED = 1 olan id sayısını alır.
    $query = mysqli_query($dbconnect, $sql);
    $data = mysqli_fetch_assoc($query);
    $id_count = $data['ID_COUNT'];


        if(isset($_REQUEST['submit'])) {
            include "database/database.php";
            $id = $_REQUEST['id'];
            $sql = "UPDATE `words` SET `LEARNING` = 0, `LEARNED` = 0 WHERE `ID` = $id";
            mysqli_query($dbconnect, $sql);
            header("Refresh:0; url=myWords.php");
        }

    
?>