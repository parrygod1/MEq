<?php 
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../db_utils/database_conn.php";

session_start();

$pageid = null;

if(isset($_GET["id"])){
        $pageid = $_GET["id"];
        $quizcontent = '';
        $sql = 'SELECT CONTENT, QUIZ_TITLE from quizzes where id = :id';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
            'id' => $pageid
        ]);
        $quizcontent = $stmt->fetch(PDO::FETCH_ASSOC);         
        echo json_encode($quizcontent);
}
BD::opreste_conexiune();

?>