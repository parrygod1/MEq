<?php
    require_once 'php/config.php';
    require_once 'CPostContent.php';
    require_once 'MPostContent.php';
    require_once 'VPostContent.php';

    if(!$_SESSION)
        session_start();

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true 
    && isset($_SESSION['userid']) && intval($_SESSION['role']) !== UserRoles::BANNED)
    {
        $title = $content = $quiz = null;
        if(isset($_POST["docTitle"]))
            $title = $_POST["docTitle"];
        if(isset($_POST["docContent"]))
            $content = $_POST["docContent"];
        if(isset($_POST["docQuiz"]))
            $quiz = $_POST["docQuiz"];
        $controller = new CPostContent('insertContent', array($title, $content, $quiz, $_SESSION['userid']));

        BD::opreste_conexiune();
    } 
    else 
    {   
        header("location: search.php");
    }


?>