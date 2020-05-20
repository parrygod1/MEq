<?php
    require_once '../config.php';
    require_once '../db_utils/database_conn.php';
    require_once '../userAccount/UserRoles.php';

    $action = null;
    $id = null;

    if(isset($_POST['action']))
        $action = $_POST['action'];
    if(isset($_POST['id']))
        $id = $_POST['id'];

    $sql = null;
    $stmt = null;

    if($action == "ban") {
        $sql = 'update users set role =' . UserRoles::BANNED . 'where id = :id';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
        'id' => $id
        ]);
    }

    else if($action == "unban") {
        $sql = 'update users set role =' . UserRoles::USER . 'where id = :id';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
        'id' => $id
        ]);
    }

    else if($action == "admin") {
        $sql = 'update users set role =' . UserRoles::ADMIN . 'where id = :id';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
        'id' => $id
        ]);
    }

    $found = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    echo json_encode($found); //this is sent as json to js/admin/user_ajaxsearch.js
    exit();
?>