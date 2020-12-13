<?php
    session_start();

    if(!$_SESSION["login"])
    {
        header("location: access/login.php");
        exit;
    }
?>