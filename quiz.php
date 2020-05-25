<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type = "text/css" href="css/style_quiz.css" />
    <link rel="stylesheet" type = "text/css" href="css/style_global.css" />
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include "navbar.php"; 
        if(isset($_SESSION) && isset($_GET['id'])){
            $_SESSION['currentquizid'] = $_GET['id'];
        }
    ?>    

    <div class="content">
        <div id="quiz-title"></div>
        <hr class="section-divider-bar">
        <div id="question-title"></div>
        <div id="question-counter"></div>
        <div id="question-wrapper">
            <div id="question-desc"></div>
            <div id="graphic-wrapper">
                <img id="graphic" src="">
            </div>
            <div id="actual-question"></div>
            <div id="answer-wrapper">
                <div class="submit-box">
                    <input id="answer-text" type="text" value="">
                </div>
                <div class="button-wrapper">
                    <button class="button-regular" id="button-prev" type="button">&lt;</button>
                    <button class="button-regular" id="button-check" type="button">Check</button>
                    <button class="button-regular" id="button-next" type="button">&gt;</button>
                </div>
            </div>
        </div>
    </div>
    <script src="js/quiz/quiz_scroller.js"></script>
    <script src="js/navbar.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    <script type="text/javascript" src="http://latex.codecogs.com/latexit.js"></script>    
</body>