<?php
require_once __DIR__ . "/../config.php";
require_once "MComment.php";
require_once "VComment.php";
require_once "CComment.php";

//session is already started in php/posts/content_query.php

$controller = "CComment";
$actiune = null;
if (isset($_POST["actiune"])) $actiune = $_POST["actiune"];
    
if ($actiune == "Post Comment"){
    if(isset($_SESSION["userid"]) && $_SESSION["loggedin"] === true && isset($_SESSION["currentpageid"])){
        $parametri = array($_SESSION['currentpageid'], $_SESSION["userid"], $_POST["comment"]);
        $control = new $controller("adaugaComment", $parametri);
        unset($_POST);
        header("location: " . $_SERVER['HTTP_REFERER']);
    } else {
        header("location: php/userAccount/login.php");
        unset($_POST);
    }
    
}

if(isset($_GET['id']))
    $control = new $controller("afiseazaComments", array($_GET['id']));

$conexiune_bd = null;