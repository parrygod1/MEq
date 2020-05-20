<?php
    require_once '../config.php';
    require_once '../db_utils/database_conn.php';

    $postTitle = $_GET['title'];
   
    $like = '%' . strtolower(trim(htmlspecialchars($postTitle))) . '%';

    $sql = null;
    if($postTitle == '*new*')
        $sql = 'SELECT ID, NAME, DESCRIPTION, VIEWS, CREATED_AT from documents where public=true order by UPDATED_AT ASC limit 10';
    else if($postTitle == '*top*')
        $sql = 'SELECT ID, NAME, DESCRIPTION, VIEWS, CREATED_AT from documents where public=true order by VIEWS DESC limit 10';
    else
        $sql = 'SELECT ID, NAME, DESCRIPTION, VIEWS, CREATED_AT from documents where lower(NAME) like :name and public=true';

    $stmt = BD::obtine_conexiune()->prepare($sql);
    $stmt -> execute ([
        'name' => $like
    ]);

    $found = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    echo json_encode($found); //this is sent as json to ajaxsearch.js
    exit();

?>