<?php
    require_once '../config.php';
    require_once '../db_utils/database_conn.php';
    require_once 'MAdmin.php';

    class jsonData {
        public function __construct($usersCount, $array) {
            $this->usersCount = $usersCount;
            $this->array = $array;
        } 

        public $usersCount;
        public $array;
    };

    $model = new MAdmin();
    $username = null;
    $id = null;

    if(isset($_GET['name']))
        $username = $_GET['name'];
    if(isset($_GET['id']))
        $id = $_GET['id'];
   
    $like = '%' . strtolower(trim(htmlspecialchars($username))) . '%';

    $sql = null;
    $stmt = null;

    $maxPerPage = 6;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per-page']) && $_GET['per-page'] <= 6 ? (int)$_GET['per-page'] : $maxPerPage;
    $start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

    $usersCount = 0;

    if ($username != null && $id != null){
        $sql = "SELECT ID, USERNAME, SCORE, ROLE, CREATED_AT 
        FROM USERS
        WHERE USERNAME LIKE :name AND id = :id";
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
        'name' => $like,
        'id' => $id
        ]);
    }
    else if($username != null){
        if ($username == '*all*') {
            $usersCount = $model->usersCount()->fetch(PDO::FETCH_ASSOC)['usersCount'];
            $sql = "SELECT ID, USERNAME, SCORE, ROLE, CREATED_AT 
            FROM users
            LIMIT {$start}, {$perPage}";
            $stmt = BD::obtine_conexiune()->prepare($sql);
            $stmt -> execute (); 
        }
        else {
            $usersCount = $model->usersCountByName($like)->fetch(PDO::FETCH_ASSOC)['usersCount'];
            $sql = "SELECT ID, USERNAME, SCORE, ROLE, CREATED_AT 
            FROM USERS 
            WHERE USERNAME LIKE :name
            ORDER BY USERNAME
            LIMIT {$start}, {$perPage}";
            $stmt = BD::obtine_conexiune()->prepare($sql);
            $stmt -> execute ([
                'name' => $like
            ]);
        }
    }
    else if ($id != null){
        $sql = 'SELECT ID, USERNAME, SCORE, ROLE, CREATED_AT from users where id = :id';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
        'id' => $id
        ]);
    }

    $found = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $data = new jsonData($usersCount, $found);
    echo json_encode($data);
    exit();

?>