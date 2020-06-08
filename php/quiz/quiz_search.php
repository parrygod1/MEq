<?php
    require_once '../config.php';
    require_once '../db_utils/database_conn.php';

    $quizTitle = $_GET['title'];
   
    $like = '%' . strtolower(trim(htmlspecialchars($quizTitle))) . '%';

    $sql = null;
    if($quizTitle == '*new*')
        $sql = 'SELECT ID, QUIZ_TITLE, CONTENT, CREATED_AT from quizzes where LENGTH(CONTENT) > 5 order by CREATED_AT DESC limit 7';
    //else if($postTitle == '*top*')
        //$sql = 'SELECT ID, NAME, DESCRIPTION, VIEWS, CREATED_AT from documents where public=true order by VIEWS DESC limit 10';
    else
        $sql = 'SELECT ID, QUIZ_TITLE, CONTENT, CREATED_AT from quizzes where lower(QUIZ_TITLE) like :name and LENGTH(CONTENT) > 5 ';

    $stmt = BD::obtine_conexiune()->prepare($sql);
    $stmt -> execute ([
        'name' => $like
    ]);

    $found = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    echo json_encode($found); //this is sent as json to ajaxsearch
    exit();

?>