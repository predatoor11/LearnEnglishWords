<?php
    include "database/database.php";
    include "process.php";
    include "change-words.php";

    include "database/database.php";
    $sql = "SELECT COUNT(`ID`) AS `OGRENILDI` FROM `words` WHERE `LEARNED` = 0";
    $query = mysqli_query($dbconnect, $sql);
    $learnedDataFetch = mysqli_fetch_assoc($query);
    $sql = "SELECT COUNT(`ID`) AS `OGRENILEN` FROM `words` WHERE `LEARNED` != 0";
    $query = mysqli_query($dbconnect, $sql);
    $learningDataFetch = mysqli_fetch_assoc($query);
    $learningSize = $learnedDataFetch['OGRENILDI'];
    $learnedSize = $learningDataFetch['OGRENILEN'];
    /* $size = getLearnedWord(); // öğrenilen ve öğrenilmeyen kelimelerin sayılarını alır.
    $learningSize = substr($size, 0, strpos($size, "/"));
    $learnedSize = substr($size, strpos($size, "/")+1); */
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FontAwesome -->
    <link type="text/css" rel="stylesheet" href="css/fontawesome-free/css/all.css">
    <!-- toastr -->
    <link type="text/css" rel="stylesheet" href="css/toastr/toastr.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- ingweb -->
    <link type="text/css" rel="stylesheet" href="css/ingweb.css">
    <title>Kelime Öğren - Kelime Hazinesi</title>
</head>
<body>

    <div class="back"><a href="index.php"><i class="far fa-arrow-alt-circle-left"></i></a></div>
    <div class="buttonControl"><button id="ban"><i class="fas fa-ban"></i></button></div>
    <h1 class="text-center">Word Treasure</h1>
    <div class="screen">
        <div class="left">
            <h2>Unlearned Words</h2>
            <div class="word-count"><span><?php echo $learningSize; ?></span></div>
            <div class="myWords">
                <span class="showhide"><i id="show" class='far fa-eye-slash'></i></span>
                <span class="refresh"><i class="fas fa-redo-alt"></i></span>
                <table id="tablo1" border="0" cellspacing="0" cellpadding="0">
                    <?php    
                        $sql = "SELECT * FROM `words` WHERE `LEARNED` != 1";
                        $query = mysqli_query($dbconnect, $sql);
                        if(mysqli_num_rows($query) > 0) {
                            while($data = mysqli_fetch_assoc($query))
                            {
                                print <<<yaz
                                <tr>
                                    <td><button id="edit" type="button" data-toggle="modal" data-target="#exampleModal" disabled="disabled" data-whatever="{$data['ID']}_{$data['EN_WORDS']}_{$data['TR_WORDS']}"><i class="fas fa-edit"></i></button></td>
                                    <td>{$data['EN_WORDS']}</td>
                                    <td class="hide hiden">{$data['TR_WORDS']}</td>
                                </tr>
                                yaz;
                            }
                        }
                    ?>
                </table>
            </div>
        </div>
        <div class="right">
            <h2>Learned Words</h2>
            <div class="word-count"><span><?php echo $learnedSize; ?></span></div>
            <div class="myWords">
                <table border="0" cellspacing="0" cellpadding="0">
                    <?php
                        $Lsql = "SELECT * FROM `words` WHERE `LEARNED` = 1";
                        $queryL = mysqli_query($dbconnect, $Lsql);
                        if(mysqli_num_rows($queryL) > 0) {
                            while($dataL = mysqli_fetch_assoc($queryL))
                            {
                                print <<<yaz
                                    <tr><td><form method="POST"><input type="text" name="id" value="{$dataL['ID']}" hidden><button id="submit" name="submit" type="submit" disabled="disabled"><i class="fas fa-long-arrow-alt-left"></i></button></form></td><td>{$dataL['EN_WORDS']}</td><td>{$dataL['TR_WORDS']}</td></tr>
                                yaz;
                            }
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>

<!-- MODALS -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit your Words</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="text" class="form-control" id="WORD_ID" hidden readonly>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">English Word</label>
                        <input type="text" class="form-control" id="EN_WORD">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Turkish Word</label>
                        <!-- <textarea class="form-control" id="message-text"></textarea> -->
                        <input type="text" class="form-control" id="TR_WORD">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-close" data-dismiss="modal">Close</button>
                <button type="button" id="update_the_word" class="btn btn-success">Update</button>
            </div>
        </div>
    </div>
</div>
<!-- ./MODALS -->

    <!-- Command: toastr["warning"]("Boş alan bırakmayınız!") -->
    <!-- FontAwesome -->
    <!-- <script src="https://kit.fontawesome.com/9ff000c9ce.js"></script> -->
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- Main.js -->
    <script src="js/main.js"></script>
    <!-- Toastr.js -->
    <script src="js/toastr.js"></script>
    <script src="js/toastr-animation.js"></script>
    <!-- Bootstrap.js -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('span.refresh').click(function() {
                $('#tablo1').load('editWords.php');
                $('#show').removeClass("fa-eye");
                $('#show').addClass("fa-eye-slash");
            });
        });

    </script>
    <script type="text/javascript">
    $('#exampleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('whatever')
        var substr = recipient.split('_');
        var modal = $(this)
        modal.find('.modal-body input#WORD_ID').val(substr[0])
        modal.find('.modal-body input#EN_WORD').val(substr[1])
        modal.find('.modal-body input#TR_WORD').val(substr[2])
    });

    $(document).ready(function() {
        $('#update_the_word').click(function() {
            var WORD_ID = $('#WORD_ID').val();
            var EN_WORD = $('#EN_WORD').val();
            var TR_WORD = $('#TR_WORD').val();
            if(WORD_ID != "" && EN_WORD != "" && TR_WORD != "") {
                    $.ajax({
                        url: 'process.php',
                        type: 'POST',
                        data: {
                            action: 'updateWords',
                            WORD_ID:WORD_ID,
                            EN_WORD:EN_WORD,
                            TR_WORD:TR_WORD
                        },
                        success: function(data) {
                            Command: toastr["success"]("Updated successfully!");
                            $('#exampleModal').modal('hide');
                        }
                    });
            } else {
                Command: toastr["error"]("Don't leave blank space!");
            }
        });
    });
    
        $(document).ready(function() {
            $('#show').click(function() {
                if($('#show').hasClass('fa-eye')) {
                    $('#show').removeClass("fa-eye");
                    $('#show').addClass("fa-eye-slash");
                    $('.hide').addClass('hiden');
                } else {
                    $('#show').removeClass("fa-eye-slash");
                    $('#show').addClass("fa-eye");
                    $('.hide').removeClass('hiden');
                }
            });

            $('#ban').click(function() {
                var a = $('button#submit').prop('disabled');
                var b = $('button#edit').prop('disabled');
                if(a == true || b == true) {
                    $('#ban').addClass('btnActive');
                    $('button#submit, button#edit').prop('disabled', false);
                } else {
                    $('#ban').removeClass('btnActive');
                    $('button#submit, button#edit').prop('disabled', true);
                }
                
            });
        });

    </script>
</body>
</html>