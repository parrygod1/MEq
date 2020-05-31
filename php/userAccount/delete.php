<?php
require_once __DIR__ . "/../config.php";
require_once "MUser.php";
require_once "VUser.php";
require_once "CUser.php";

  session_start();

  if(isset($_GET['token']))
  {
    $controller = new CUser($_GET['token'], "delete");
  }

  BD::opreste_conexiune();
?>