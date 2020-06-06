<?php
require_once __DIR__ . "/../config.php";
require_once "MAdmin.php";
require_once "VAdmin.php";
require_once "CAdmin.php";

if(isset($_GET['action'])){
    session_start();
    $controller = new CAdmin($_GET['action'], null, null);
}   
else {
    $controller = new CAdmin('searchUsers', null, null);
}

BD::opreste_conexiune();
?>