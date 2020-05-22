<?php 
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../db_utils/database_conn.php";

session_start();

if(isset($_SESSION['loggedin']) && isset($_SESSION["userid"]) && isset($_SESSION['currentquizid'])){
        $sql = 'select id_user, id_quiz from completedquizzes where id_user = :iduser and id_quiz = :idquiz';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
            'iduser' => $_SESSION["userid"],
            'idquiz' => $_SESSION["currentquizid"]
        ]);
        if($stmt->rowCount() == 0){
            $sql = 'insert into completedquizzes values (NULL, :iduser, :idquiz)';
            $stmt = BD::obtine_conexiune()->prepare($sql);
            if ($stmt -> execute ([
                'iduser' => $_SESSION["userid"],
                'idquiz' => $_SESSION["currentquizid"]
            ]));

            $sql = 'update users set score = score + 1 where id = :iduser';
            $stmt = BD::obtine_conexiune()->prepare($sql);
            if ($stmt -> execute ([
                'iduser' => $_SESSION["userid"]
            ])){
                //header("location: postpage.php?id=" . $_SESSION['currentpageid']);
            }
            echo "Score updated!";   
        }
        else {
            echo "";
        }
}
BD::opreste_conexiune();

?>