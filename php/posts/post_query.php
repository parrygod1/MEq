<?php
    require_once '../config.php';
    require_once '../db_utils/database_conn.php';
    require_once 'MPostContent.php';

    class jsonData {
        public function __construct($postCount, $array) {
            $this->postCount = $postCount;
            $this->array = $array;
        } 

        public $postCount;
        public $array;
    };
    $model = new MPostContent();

    $postTitle = null;
    $postCount = 0;
    
    if(isset($_GET['title']))
        $postTitle = $_GET['title'];
    
    $maxPerPage = 6;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per-page']) && $_GET['per-page'] <= 6 ? (int)$_GET['per-page'] : $maxPerPage;
    $start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

    $like = '%' . strtolower(trim(htmlspecialchars($postTitle))) . '%';
    $sql = null;

    if($_GET['title'] == '*new*')
        $sql = 'SELECT ID, NAME, DESCRIPTION, VIEWS, CREATED_AT from documents where public=true order by UPDATED_AT DESC limit 7';
    else if($_GET['title'] == '*top*')
        $sql = 'SELECT ID, NAME, DESCRIPTION, VIEWS, CREATED_AT from documents where public=true order by VIEWS DESC limit 7';
    else {
        $postCount = $model->getPostCountByTitle($like);
        $sql = "SELECT ID, NAME, DESCRIPTION, VIEWS, CREATED_AT from documents where lower(NAME) like :name and public=true
        LIMIT {$start}, {$perPage}";
    }

    $stmt = BD::obtine_conexiune()->prepare($sql);
    $stmt -> execute ([
        'name' => $like
    ]);
    
    $found = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $data = new jsonData($postCount, $found);
    echo json_encode($data); //sent to js
    exit();

?>