<?php include "database/database.php"; ?>
<?php include "process.php"; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FontAwesome -->
    <link type="text/css" rel="stylesheet" href="css/fontawesome-free/css/all.css">
    <!-- toastr -->
    <link type="text/css" rel="stylesheet" href="css/toastr/toastr.css">
    <!-- ingweb -->
    <link rel="stylesheet" type="text/css" href="css/ingweb.css">
    <title>Kelime Öğren - Kelime Ekle, Klasik Test Başlat</title>
</head>
<body onload="changeWords()">

    <h1 class="text-center">Learn English Words</h1>
    <div class="screen">
        <div class="left">
            <div class="myWordsButton"><a href="myWords.php">My Words</a></div>
            <!-- <form action="" method="POST"> -->
                <div class="form">
                    <h3>Write the English word</h3>
                    <input type="text" name="en_word" id="en_word" placeholder="Love.." required>
                    <h3>Write the meaning of the word</h3>
                    <input type="text" name="tr_word" id="tr_word" onkeypress="if(event.key === 'Enter') save();" placeholder="Aşk, Sevmek.." required>
                    <input type="submit" name="saveWords" id="saveWords" value="Save">
                </div>
                <div id="success"></div>
            <!-- </form> -->
        </div>
        <div class="right">
            <div class="test">
                <!-- <h3>Klasik Testler</h3> -->
                <button class="button" type="submit" id="start" onclick="renderQuestion()">Classic Exam</button>
                <button class="button" type="submit" id="quiz">10 Question Test</button>
                <button class="button" type="submit" id="quizallwords">All Words - Test</button>
                <button class="button" type="submit" id="noqtest">Choose your question yourself - Test</button>
                <button class="button" type="submit" id="learnedwords">Learned Words (to remember) - Test</button>
                <div class="enWord" id="enwords"></div>
                <div class="trWord" id="trwords">
                    <div class="numberOfQuestion" id="numberOfQuestion"></div>
                    <div class="form">
                        <div id="question"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- Toastr.js -->
    <script src="js/toastr.js"></script>
    <script src="js/toastr-animation.js"></script>
    <!-- Main.js -->
    <script src="js/main.js"></script>
    <script type="text/javascript">
        function save() {
            setTimeout(function() {
                $('#saveWords').trigger('click');
            }, 200);
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#quiz').click(function() {
                window.location.href = "test.php";
            });
            $('#quizallwords').click(function() {
                window.location.href = "testallwords.php";
            });
            $('#noqtest').click(function() {
                window.location.href = "noqtest.php";
            });
            $('#learnedwords').click(function() {
                window.location.href = "learnedwords.php";
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#en_word').focus(); // sayfa açıldığında en_word inputboxa focus atar.
            $('#saveWords').click(function() {
                var EN_WORDS = $('#en_word').val();
                var TR_WORDS = $('#tr_word').val();
                if(EN_WORDS != "" && TR_WORDS != "") {
                    $.ajax({
                        url: 'process.php',
                        type: 'POST',
                        data: {
                            action: 'saveWords',
                            EN_WORDS:EN_WORDS,
                            TR_WORDS:TR_WORDS
                        },
                        success: function(data) {
                            $('#en_word').val("");
                            $('#tr_word').val("");
                            $('#success').html("Kelime hazineye eklendi kaptan.");
                            $('#success').css("padding", "20px");
                            setTimeout( function() {
                                $('#success').html("");
                                $('#success').css("padding", "0");
                            }, 2000);
                            $('#en_word').focus();
                        }
                    });
                } else {
                    Command: toastr["error"]("What will you record, the void?");
                }
            });
        });
    </script>
    <script type="text/javascript">
 
        <?php
            $data = fetchWords();
            foreach($data as $row)
            {
                $id[] = $row['ID'];
                $en[] = $row['EN_WORDS'];
                $tr[] = $row['TR_WORDS'];
                $learning[] = $row['LEARNING'];
                $learned[] = $row['LEARNED'];
            }
            $sizeId = sizeof($id);
        ?>
        var pos = 0, question, Qanswer, correct = 0;
        var words = <?php echo json_encode($en); ?>; // ingilizce kelimeleri dizi içine alıyor.
        var answer = <?php echo json_encode($tr); ?>; // kelimelerin türkçe karşılığını alıyor.
        var sizeId = <?php echo json_encode($sizeId); ?>; // kaç kelime var veri olarak alıyor.
        var random = 0;
        var answerArray = [];

        function _(x) {
            return document.getElementById(x);
        }

        function renderQuestion() {
            $('.test').css('justify-content', 'left');
            var x = document.querySelectorAll('.button');
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            } /* Quiz başladığında butonları gizler. */

            _("numberOfQuestion").innerHTML = pos+1 + " / " + words.length;
            var enWordClass = _("enwords");
            var trWordClass = _("question");
            
            question = words[pos];
            Qanswer = answer[pos];
            var answerSplit = answer[pos];
            answerArray = answerSplit.split(', ');
            enWordClass.innerHTML = "<span>"+question+"</span>";
            trWordClass.innerHTML = `<input type="text" name="answer" id="answer" onkeypress="if(event.key === 'Enter') checkAnswer()" placeholder="TR karşılığını giriniz.">` +
                                    '<input type="submit" id="checkAnswer" onclick="checkAnswer()" value="Cevapla">';
        }
        function checkAnswer() {
            var cevap = document.getElementById('answer');
            cevap = duzenle(cevap.value);
            Qanswer = duzenle(Qanswer);

            for (var i = 0; i < answerArray.length; i++) {
                if(answerArray[i] == cevap.toString())
                {
                    Qanswer = answerArray[i];
                }
            }

            if(Qanswer.toString() == cevap.toString()) {
                correct++;

                var deleteClient = function(question) {
                    $.ajax({
                        url: 'process.php',
                        type: 'POST',
                        data: {question:question},
                        success: function(data) {
                            console.log(data);
                        }
                    });
                };
                deleteClient(question);
            }

            if(pos+1 < words.length) {
                pos++;
                renderQuestion();
                $('#answer').focus();
            } else {
                var enWordClass = _("enwords");
                var trWordClass = _("question");
                enWordClass.innerHTML = "";
                trWordClass.innerHTML = words.length + " Sorudan " + correct + " doğru.";
                // setTimeout(function () {
                //     window.location.href= 'index.php';
                // }, 5000);
                var start = _('start');
                start.style.display = "block"; // Start butonu göster
                start.setAttribute("onclick", "reloadPage()");
                start.innerHTML = "Sayfayı Yenile";
                pos = 0;
                correct = 0;
            }
        }
        function reloadPage() {
            location.reload();
        }

        function duzenle(value) {
            return value.toLowerCase();
        }

        function changeWords() {
            var en = document.getElementById('en_word');
            var tr = document.getElementById('tr_word');
            var rand = Math.floor(Math.random() * 10);
            var enWords = ["jewel", "souvenirs", "appear", "except", "rather", "marble", "discussion", "arrives", "hometown", "sour"];
            var trWords = ["mücevher", "hatıra", "görünmek", "dışında", "oldukça, daha doğrusu", "mermer", "tartışma", "geldiğinde, varmak", "memleket", "ekşi"];
            en.placeholder = enWords[rand];
            tr.placeholder = trWords[rand];
        }

    </script>
</body>
</html>