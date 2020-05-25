<?php
require_once __DIR__ . "/../db_utils/database_conn.php";
require_once __DIR__ . "/../userAccount/UserRoles.php";

class MComment{
    public function adaugaComment($id_document, $id_user, $text){ //create
        $sql = 'select role from users where id = :id';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt->execute([
            'id' => $id_user,
        ]);
        $found = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if(intval($found['role']) != UserRoles::BANNED){
            $sql = "INSERT INTO comments (id_user, id_document, text, created_at, updated_at) VALUES (:id_user, :id_document, :text, :created_at, :updated_at)";
            $cerere = BD::obtine_conexiune()->prepare($sql);
            return $cerere -> execute ([
                'id_user' => $id_user,
                'id_document' => $id_document,
                'text' => $text,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function obtineComments($id_document){ // read
        $sql = "SELECT USERNAME, ID_USER, IMAGE_PATH, TEXT from comments c 
                    join users u on u.id = c.id_user where id_document = :id_document";
        $cerere = BD::obtine_conexiune()->prepare($sql);
        $cerere->execute([
            'id_document' => $id_document
        ]);
        return $cerere;
    }
}

?>