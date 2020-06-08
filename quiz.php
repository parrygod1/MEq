<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="project, infoiasi, web">
    <title>Quiz</title>
    <link rel="stylesheet" type = "text/css" href="css/quiz.css" />
    <link rel="stylesheet" type = "text/css" href="css/global.css" />
    <link rel="stylesheet" href="css/navbar.css" />
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
    <script type="text/javascript" src="https://latex.codecogs.com/latexit.js"></script>    
</body>
</html>