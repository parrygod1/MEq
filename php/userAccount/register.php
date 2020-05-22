<?php
session_start();

require_once __DIR__ . "/../config.php";
require_once '../db_utils/database_conn.php';
require_once "MUser.php";
require_once "VUser.php";
require_once "CUser.php";

$controller = new CUser("register", null);

?>