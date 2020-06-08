<?php 
require_once __DIR__ . "/../db_utils/database_conn.php";
require_once __DIR__ . "/../userAccount/UserRoles.php";
ob_start();

class MPostContent{
    public function getPostContent($id_document){ 
        $sql = 'SELECT d.ID, d.ID_USER, d.CONTENT, d.NAME, d.PUBLIC, q.ID as QUIZID, q.CONTENT as QUIZCONTENT from documents d 
        left join quizzes q on q.id_document = d.id where d.id =:id';
        $stmt1 = BD::obtine_conexiune()->prepare($sql);
        $stmt1 -> execute ([
            'id' => $id_document
        ]);
        
        if($stmt1->rowcount() > 0){
            $sql2 = 'UPDATE documents SET VIEWS=(select views from documents where id=:id)+1 where id = :id';
            $stmt2 = BD::obtine_conexiune()->prepare($sql2);
            $stmt2 -> execute ([
                'id' => $id_document
            ]);
        }

        return $stmt1;
    } 

    public function insertDocument($title, $content, $quiz, $user_id)
    {
        $id_document = null;
        $sql = 'INSERT INTO documents (name, id_user, description, content) values (:name, :user_id, :description, :content)';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        if ($stmt->execute([
            'name' => $title,
            'user_id' => $user_id,
            'description' => strtok(strip_tags(str_replace("\r\n", "", $content)), '.'),
            'content' => $content
        ])) {
            $sql1 = 'SELECT max(id) as maxi from documents';
            $stmt1 = BD::obtine_conexiune()->prepare($sql1);
            if ($stmt1->execute()) {
                $row = $stmt1->fetch(PDO::FETCH_ASSOC);
                $id_document = $row['maxi'];
            }
            if($quiz != '[]'){
                $sql2 = 'INSERT INTO quizzes (id_document, quiz_title, content) values (:id_document, :title, :content)';
                $stmt2 = BD::obtine_conexiune()->prepare($sql2);
                $stmt2->execute([
                    'id_document' => $id_document,
                    'content' => $quiz,
                    'title' => $title
                ]);
            }
            header("location: postpage.php?id=" . $id_document);
        }
    }

    public function getPostCount(){
        $sql = 'select count(*) as postCount from documents';
        $request = BD::obtine_conexiune()->prepare($sql);
        $request->execute();
        return $request->fetch(PDO::FETCH_ASSOC)['postCount'];
    }

    public function getPostCountByTitle($name){
        $sql = 'select count(*) as postCount from documents where lower(NAME) like :name and public=true';
        $request = BD::obtine_conexiune()->prepare($sql);
        $request->execute([
            'name' => $name
        ]);
        return $request->fetch(PDO::FETCH_ASSOC)['postCount'];
    }
}
?>