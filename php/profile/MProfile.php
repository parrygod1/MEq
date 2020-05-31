<?php
  require_once __DIR__ . "/../db_utils/database_conn.php";

  class MProfile {

    public $maxPerPage = 2;

    public function getProfileInfo($id_user) {
        $sql = "SELECT u.ID, USERNAME, IMAGE_PATH, SCORE, ROLE, COUNT(ID_USER) as DOC from users u join documents d on d.ID_USER = u.ID where u.id = :id_user";
        $request = BD::obtine_conexiune()->prepare($sql);
        $request->execute([
        'id_user' => $id_user
        ]);
        return $request;
    }

    public function uploadAvatar($id_user, $avatar_user) {
        $sql = "UPDATE users
        SET IMAGE_PATH = :avatar_user
        WHERE id = :id_user";
        $request = BD::obtine_conexiune()->prepare($sql);
        $request->execute([
        'avatar_user' => $avatar_user,
        'id_user' => $id_user
        ]);
    }

    
    public function getUserPublications($id_user) {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per-page']) && $_GET['per-page'] <= 10 ? (int)$_GET['per-page'] : $this->maxPerPage;
        $start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

        $sql = "SELECT ID, NAME, DESCRIPTION, CONTENT, updated_at
        FROM documents
        WHERE id_user = :id_user
        ORDER BY updated_at DESC
        LIMIT {$start}, {$perPage}";
        $request = BD::obtine_conexiune()->prepare($sql);
        $request->execute([
            'id_user' => $id_user
        ]);
        return $request;
    }
}
?>