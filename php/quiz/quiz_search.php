<?php
    require_once '../config.php';
    require_once '../db_utils/database_conn.php';

    $quizTitle = $_GET['title'];
   
    $like = '%' . strtolower(trim(htmlspecialchars($quizTitle))) . '%';

    $sql = null;
    if($quizTitle == '*new*')
        $sql = 'SELECT q.ID, q.QUIZ_TITLE, q.CONTENT, q.CREATED_AT from quizzes q join documents d on 
        q.id_document = d.id
        where LENGTH(q.CONTENT) > 5 and d.public = 1 order by q.UPDATED_AT DESC limit 7';
    else
        $sql = 'SELECT q.ID, q.QUIZ_TITLE, q.CONTENT, q.CREATED_AT from quizzes q join documents d on 
        q.id_document = d.id
        where q.QUIZ_TITLE like :name and LENGTH(q.CONTENT) > 5 and d.public = 1 order by q.UPDATED_AT DESC limit 7';

    $stmt = BD::obtine_conexiune()->prepare($sql);
    $stmt -> execute ([
        'name' => $like
    ]);

    $found = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    echo json_encode($found); //this is sent as json to ajaxsearch
    exit();

?>