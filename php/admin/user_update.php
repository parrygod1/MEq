<?php
    require_once '../config.php';
    require_once '../db_utils/database_conn.php';
    require_once '../userAccount/UserRoles.php';

    session_start();
    
    $action = null;
    $id = null;
   
    if(isset($_POST['action']) && isset($_POST['id']) && isset($_SESSION['loggedin']) && $_SESSION['role'] == UserRoles::ADMIN){
        $action = $_POST['action'];
        $id = $_POST['id'];

        $sql = null;
        $stmt = null;

        if($action == "ban") {   
            $sql = 'update users set role =' . UserRoles::BANNED . ' where id = :id';
            $stmt = BD::obtine_conexiune()->prepare($sql);
            if($stmt -> execute ([
            'id' => $id
            ])){echo 'Banned user ID=' . $id;};
        }
        else if($action == "unban") {
            $sql = 'update users set role =' . UserRoles::USER . ' where id = :id';
            $stmt = BD::obtine_conexiune()->prepare($sql);
            if($stmt -> execute ([
            'id' => $id
            ])){echo 'Set normal user user ID=' . $id;};
        } 
        else if($action == "admin") {
            $sql = 'update users set role =' . UserRoles::ADMIN . ' where id = :id';
            $stmt = BD::obtine_conexiune()->prepare($sql);
            if($stmt -> execute ([
            'id' => $id
            ])){echo 'Gave admin to user ID=' . $id;};
        }

        BD::opreste_conexiune();
        exit();
    }
?>