<?php

    function fetchWords() {
        include "database/database.php";
        $sql = "SELECT * FROM `words` WHERE `LEARNED` = 0 and `USERS_ID` = {$_SESSION['id']}";
        $query = mysqli_query($dbconnect, $sql);
        while($data = mysqli_fetch_array($query, MYSQLI_ASSOC))
        {
            $datas[] = $data;
        }
        return $datas;
    }
    
    function learnedWords() {
        include "database/database.php";
        $sql = "SELECT * FROM `words` WHERE `LEARNED` = 1 and `USERS_ID` = {$_SESSION['id']}";
        $query = mysqli_query($dbconnect, $sql);
        while($data = mysqli_fetch_array($query, MYSQLI_ASSOC))
        {
            $datas[] = $data;
        }
        return $datas;
    }
    // $data = fetchWords();
    // foreach($data as $row)
    // {
    //   $id[] = $row['ID'];
    //   $en[] = $row['EN_WORDS'];
    //   $tr[] = $row['TR_WORDS'];
    //   $learning[] = $row['LEARNING'];
    //   $learned[] = $row['LEARNED'];
    // }
    // $sizeEn = $id[sizeof($id) - 1];
    // for($j = 0; $j < $sizeEn; $j++) { $en[$j]; }
    // $sizeId = sizeof($id);

    /* function getLearnedWord() { // öğrenilen ve öğrenilmeyen kelimelerin sayılarını alır.
        include "database/database.php";
        $sql = "SELECT COUNT(`ID`) AS `OGRENILDI` FROM `words` WHERE `LEARNED` = 0";
        $query = mysqli_query($dbconnect, $sql);
        $learnedDataFetch = mysqli_fetch_assoc($query);
        $sql = "SELECT COUNT(`ID`) AS `OGRENILEN` FROM `words` WHERE `LEARNED` != 0";
        $query = mysqli_query($dbconnect, $sql);
        $learningDataFetch = mysqli_fetch_assoc($query);
        
        return $learnedDataFetch['OGRENILDI']."/".$learningDataFetch['OGRENILEN'];
    } */

    function kelimeKontrol($kelime) {
        include "database/database.php";
        $sql = "SELECT * FROM `words` WHERE `EN_WORDS` IN ('$kelime')";
        $query = mysqli_query($dbconnect, $sql);
        $data = mysqli_fetch_assoc($query);
        $learning = $data['LEARNING'];
        $learned = $data['LEARNED'];
        if($learning == 2) {
            $sql = "UPDATE `words` SET `LEARNED` = 1 WHERE `EN_WORDS` = '$kelime'";
            mysqli_query($dbconnect, $sql);
            mysqli_close($dbconnect);
            exit();
        }
        if($learned == 0) {
            if($learning == 0) { $sql = "UPDATE `words` SET `LEARNING` = 1 WHERE `EN_WORDS` = '$kelime'"; mysqli_query($dbconnect, $sql); exit(); }
            if($learning == 1) { $sql = "UPDATE `words` SET `LEARNING` = 2 WHERE `EN_WORDS` = '$kelime'"; mysqli_query($dbconnect, $sql); exit(); }
            // if($learning == 2) { $sql = "UPDATE `words` SET `LEARNING` = 3 WHERE `EN_WORDS` = '$kelime'"; mysqli_query($dbconnect, $sql); exit(); }
        }
    }
    if(isset($_POST['question'])) {
        kelimeKontrol($_POST['question']);
    }
    // kelimeKontrol("");


    if(isset($_POST['action']) && !empty($_POST['action'])) { /* AJAXTAN GELEN FUNCTION SEÇİMİ YAPAN İŞLEM */
        $action = $_POST['action'];
        switch($action) {
            case 'saveWords' : saveWords();break;
            case 'updateWords' : updateWords();break;
            // ...etc...
        }
    }


    function saveWords() {
        include "database/database.php";
        $en_word = $_POST['EN_WORDS'];
        $tr_word = $_POST['TR_WORDS'];
        $user_id = $_POST['user_id'];
        if(!empty($en_word) && !empty($tr_word))
        {
            $sql = "INSERT INTO `words` (`EN_WORDS`, `TR_WORDS`, `LEARNING`, `LEARNED`, `USERS_ID`)
                    VALUES ('$en_word', '$tr_word', 0, 0, $user_id)";
            mysqli_query($dbconnect, $sql);
            mysqli_close($dbconnect);
            // $success = "<div class='success'>Başarılı</div>";
        } else {
            print "<script>alert('Boş kutu bırakılamaz!');</script>";
        }
    }

    function updateWords() {
        include "database/database.php";
        $word_id = $_POST['WORD_ID'];
        $en_word = $_POST['EN_WORD'];
        $tr_word = $_POST['TR_WORD'];
        $user_id = $_POST['user_id'];
        print "<script>alert('$update_word_id');</script>";
        if(!empty($word_id) && !empty($en_word) && !empty($tr_word))
        {
        $sql = "UPDATE `words` SET `EN_WORDS` = '$en_word', `TR_WORDS` = '$tr_word' WHERE `ID` = $word_id AND `USERS_ID` = $user_id";
            mysqli_query($dbconnect, $sql);
            mysqli_close($dbconnect);
            // $success = "<div class='success'>Başarılı</div>";
        } else {
            print "<script>alert('Boş kutu bırakılamaz!');</script>";
        }
    }
    
?>