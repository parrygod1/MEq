<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../userAccount/CUser.php";

require_once "MProfile.php";
require_once "VProfile.php";
require_once "CProfile.php";

  //Session is started in navbar.php

  if(!isset($_GET['action']))
  {
    $controller = new CProfile();
    $controller->showProfileInfo($_GET['id']);
  }
  else if ($_GET['action'] == 'sendDelMail' && isset($_SESSION['userid']))
  {
    $controller = new CUser($_SESSION['userid'], "sendDelEmail");
  }
  else if ($_GET['action'] == 'confirmMail'){
    $controller = new CProfile();
    $controller -> showMailConfirmation();
  }
  BD::opreste_conexiune();
?>