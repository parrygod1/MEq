<?php 
require_once __DIR__ . "/../db_utils/database_conn.php";
ob_start();

class MPostContent{
    public function getPostContent($id_document){ 
        $sql = 'SELECT d.ID, d.CONTENT, d.NAME, d.PUBLIC, q.CONTENT as QUIZCONTENT from documents d 
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
        $sql = 'INSERT INTO documents (name, id_user, description, content) values (:name, :user_id, :description, :content)';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        if ($stmt->execute([
            'name' => $title,
            'user_id' => $user_id,
            'description' => strtok(strip_tags($content), '.'),
            'content' => $content
        ])) {
            $sql1 = 'SELECT max(id) as maxi from documents';
            $stmt1 = BD::obtine_conexiune()->prepare($sql1);
            $id_document = null;
            if ($stmt1->execute()) {
                $row = $stmt1->fetch(PDO::FETCH_ASSOC);
                $id_document = $row['maxi'];
            }
            $sql2 = 'INSERT INTO quizzes (id_document, quiz_title, content) values (:id_document, "Quiz", :content)';
            $stmt2 = BD::obtine_conexiune()->prepare($sql2);
            $test = false;
            if ($stmt2->execute([
                'id_document' => $id_document,
                'content' => $quiz
            ])) {
                $test = true;
                header("location: search.php");
            }
        }
    }
}
?>