<?php
require_once __DIR__ . "/../db_utils/database_conn.php";

class MAdmin {

    public $maxPerPage = 7;

    public function docCount() {
        $sql = "SELECT count(*) AS docCount
        FROM documents 
        WHERE PUBLIC = 0";
        $request = BD::obtine_conexiune()->prepare($sql);
        $request->execute();
        return $request;
    }

    public function showDocuments() {
        // $sql = 'SELECT d.ID, d.NAME, d.DESCRIPTION from documents d WHERE d.PUBLIC = 0';
        // $stmt = BD::obtine_conexiune()->prepare($sql);
        // $stmt -> execute ();
        // return $stmt;

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per-page']) && $_GET['per-page'] <= 7 ? (int)$_GET['per-page'] : $this->maxPerPage;
        $start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

        $sql = "SELECT d.ID, d.NAME, d.DESCRIPTION
        FROM documents d
        WHERE d.PUBLIC = 0
        ORDER BY updated_at DESC
        LIMIT {$start}, {$perPage}";
        $request = BD::obtine_conexiune()->prepare($sql);
        $request->execute();
        return $request;
    }

    public function handleDocument($decision, $idDoc) {
        if($decision === 'accepted') {
            $sql = 'UPDATE documents d  SET d.PUBLIC = 1 WHERE d.ID = ' . $idDoc;
            $stmt = BD::obtine_conexiune()->prepare($sql);
            $stmt -> execute ();

        }
        elseif ($decision === 'refused') {
            $sql = 'DELETE FROM documents WHERE ID = ' . $idDoc;
            $stmt = BD::obtine_conexiune()->prepare($sql);
            $stmt -> execute ();
        }
    }

}
