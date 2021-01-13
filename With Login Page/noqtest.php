<?php include "database/database.php"; ?>
<?php include "process.php"; ?>
<?php include "accessFolder.php"; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FontAwesome -->
    <link type="text/css" rel="stylesheet" href="css/fontawesome-free/css/all.css">
    <!-- ingweb -->
    <link type="text/css" rel="stylesheet" href="css/ingweb.css">
    <!-- logo -->
    <link rel="shortcut icon" href="./img/logo.png" type="image/x-icon">
    <title>Kelime Öğren - NOQ Test</title>
</head>
<body>
    <div class="menu">
        <div class="box">
            <p><a href=""><?php echo $_SESSION['username']; ?></a></p>
            <p><a href="access/logout.php">Logout</a></p>
        </div>
        <span id="menubar"><i class="fas fa-bars"></i></span>
    </div>

    <div class="back"><a href="index.php"><i class="far fa-arrow-alt-circle-left"></i></a></div>
    <div class="screen">
        <div class="mid">
            <div class="div-start">
                <input class="noq" type="number" id="numberofquestion" onkeyup="noqcont()" required>
                <button class="start" type="button" id="startBtn">Quiz Başlat</button>
            </div>
            <div id="demo"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- Main.js -->
    <script src="js/main.js"></script>
    <?php include "words.php"; ?>
    
    <script type="text/javascript">
        var val = "";
        $('#startBtn').click(function() {
            if(val == "") {
                return false;
            } else {
                $('#numberofquestion').hide();
                quizStart();
            }
        });

        $('#numberofquestion').attr('placeholder', 'Max Kelime: ' + sizeId);
        $('#numberofquestion').attr('minlength', "1");
        $('#numberofquestion').attr('maxlength', sizeId);
        function noqcont() {
            val = $('#numberofquestion').val();
            if(val > sizeId) {
                $('#numberofquestion').val(sizeId);
            } else if(val < 1) {
                $('#numberofquestion').val("");
            }
        }
    </script>
    <script type="text/javascript">
        var pos = 0, question, answer, choice, choices, chA, chB, chC, chD, correct = 0;
        var random = 0;
        var randArray = [];
        var randChoices = [];
        var count = 0;
        var wrongAnswer = [], wrongChoice = [];

        function _(x) {
            return document.getElementById(x);
        }
        
        function rndmChoices() {
            var r = Math.floor(Math.random() * sizeId);
            return r;
        }

        function quizStart() {
            _('startBtn').style.display = "none";

/*             for(var j = 0; j < 10; j++) { //random function çağırma
                randArray[j] = rndm();
            }
            for(var j = 0; j < 3; j++) { //random function çağırma
                randChoices[j] = rndmChoices();
            } */

            while(randArray.length < sizeId){ // soruları random olarak sıralamasını sağlayan random komutu
                var r = Math.floor(Math.random() * sizeId);
                if(randArray.indexOf(r) === -1) {
                    randArray.push(r);
                }
            }
            /* console.log(randArray); */
            randChoices = 0;
            randChoices = [];
            while(randChoices.length < 3){ // cevapları random olarak çağırmasını sağlayan random komutu
                
                var r = rndmChoices();
                if(randChoices.indexOf(r) === -1 && r !== randArray[count]) {
                    randChoices.push(r);
                }
            }
            
            pos = randArray[count]; // rastgele sayıları pos içinde kullanıyor.
            question = enWords[pos];
            answer = trWords[pos];
            var test = Math.floor(Math.random() * 8); // şıkları rastgele ayarlamak için yapılan random işlemi
            if(test == 0 || test == 4) { // cevap A şıkkında
                chA = trWords[pos];
                chB = trWords[randChoices[0]];
                chC = trWords[randChoices[1]];
                chD = trWords[randChoices[2]];
            }
            if(test == 1 || test == 5) { // cevap B şıkkında
                chA = trWords[randChoices[0]];
                chB = trWords[pos];
                chC = trWords[randChoices[1]];
                chD = trWords[randChoices[2]];
            }
            if(test == 2 || test == 6) { // cevap C şıkkında
                chA = trWords[randChoices[1]];
                chB = trWords[randChoices[0]];
                chC = trWords[pos];
                chD = trWords[randChoices[2]];
            }
            if(test == 3 || test == 7) { // cevap D şıkkında
                chA = trWords[randChoices[2]];
                chB = trWords[randChoices[1]];
                chC = trWords[randChoices[0]];
                chD = trWords[pos];
            }

            _('demo').innerHTML = `
                <div class="frame">
                    <div class="rest-div"><div onclick="reloadPage()" class="rest"><i class="fas fa-sync-alt"></i></div></div>
                    <div class="question">
                        `+ question +`
                    </div>
                    <div class="choices">
                        <div class="choice"><input type="radio" name="choices" id="choice" value="`+chA+`">&nbsp;<label for="choice">`+chA+`</label></div>
                        <div class="choice"><input type="radio" name="choices" id="choice2" value="`+chB+`">&nbsp;<label for="choice2">`+chB+`</label></div>
                        <div class="choice"><input type="radio" name="choices" id="choice3" value="`+chC+`">&nbsp;<label for="choice3">`+chC+`</label></div>
                        <div class="choice"><input type="radio" name="choices" id="choice4" value="`+chD+`">&nbsp;<label for="choice4">`+chD+`</label></div>
                    </div>
                    <div class="answer">
                        <button type="button" onclick="checkChoice()">Cevapla</button>
                    </div>
                    <div class="noq"><span id="noq"></span></div>
                </div>
            `;
            _('noq').innerHTML = count+1 + " / " + val;
            
        }

        function checkChoice() {
            choices = document.getElementsByName('choices');
            for(var j = 0; j < choices.length; j++) {
                if(choices[j].checked) {
                    choice = choices[j].value;
                }
            }

            if(choice == answer) {
                correct++;

                var deleteClient = function(question) {
                    $.ajax({
                        url: 'process.php',
                        type: 'POST',
                        data: {question:question},
                        /* success: function(data) {
                            console.log(data);
                        } */
                    });
                };
                deleteClient(question);
            } else {
                wrongAnswer.push(question); // Hatalı kelimeleri içinde barındıran array.
                wrongChoice.push(answer);
                //console.log(wrongAnswer);
            }

            if(count+1 < val) {
                count++;
                quizStart();
            } else {
                if(correct < sizeId*40/100) {
                    _('demo').innerHTML = `
                        <div class="frame">
                            <div class="rest-div"><div onclick="reloadPage()" class="rest"><i class="fas fa-sync-alt"></i></div></div>
                            <div class="fail">Başarısız<span>`+val+` sorudan `+correct+` soru doğru</span></div>
                        </div>
                    `;
                } else if(correct > sizeId*40/100 && correct < sizeId*85/100) {
                    _('demo').innerHTML = `
                        <div class="frame">
                            <div class="rest-div"><div onclick="reloadPage()" class="rest"><i class="fas fa-sync-alt"></i></div></div>
                            <div class="medium">Başarılı<span>`+val+` sorudan `+correct+` soru doğru</span></div>
                        </div>
                    `;
                } else if(correct > sizeId*85/100) {
                    _('demo').innerHTML = `
                        <div class="frame">
                            <div class="rest-div"><div onclick="reloadPage()" class="rest"><i class="fas fa-sync-alt"></i></div></div>
                            <div class="success">Tam Puan Tebrikler<span>`+val+` sorudan `+correct+` soru doğru</span></div>
                        </div>
                    `;
                }
                $('.screen').after(`
                    <div class="wrongWords">
                        <div class="words">
                            <h1 id="wrongwords">Wrong Words</h1>
                        </div>
                    </div>
                `); // WrongWords leri gösteren komut.
                for(var j = 0; j < wrongAnswer.length; j++)
                $('#wrongwords').after(`<p>`+wrongAnswer[j]+` = `+wrongChoice[j]+`</p>`);
            }
        }
        function reloadPage() {
            location.reload();
        }
    </script>
</body>
</html>