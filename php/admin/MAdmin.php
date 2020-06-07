<?php
require_once __DIR__ . "/../db_utils/database_conn.php";
require_once __DIR__ . "/../configDomain.php";

class MAdmin
{

    public $maxPerPage = 6;


    public function usersCount()
    {
        $sql = "SELECT count(*) AS usersCount
        FROM users";
        $request = BD::obtine_conexiune()->prepare($sql);
        $request->execute();
        return $request;
    }

    public function usersCountByName($name) 
    {
        $sql = "SELECT count(*) AS usersCount
        FROM users
        WHERE username LIKE :name";
        $request = BD::obtine_conexiune()->prepare($sql);
        $request->execute([
            'name' => $name
        ]);
        return $request;
    }

    public function docCount()
    {
        $sql = "SELECT count(*) AS docCount
        FROM documents 
        WHERE PUBLIC = 0";
        $request = BD::obtine_conexiune()->prepare($sql);
        $request->execute();
        return $request;
    }

    public function showDocuments()
    {
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

    public function handleDocument($decision, $idDoc)
    {
        if ($decision === 'accepted') {
            $sql = 'UPDATE documents d  SET d.PUBLIC = 1, d.UPDATED_AT = now() WHERE d.ID = ' . $idDoc;
            $stmt = BD::obtine_conexiune()->prepare($sql);
            $stmt->execute();
        } elseif ($decision === 'refused') {
            $sql = 'DELETE FROM documents WHERE ID = ' . $idDoc;
            $stmt = BD::obtine_conexiune()->prepare($sql);
            $stmt->execute();
        }
    }

    function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }

    function download_send_headers($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
    
        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
    
        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    public function exportDocs() {
        $sql = 'select ID, ID_USER, NAME, DESCRIPTION, CREATED_AT, UPDATED_AT from documents';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        $this->download_send_headers("MEqX_docs_export_" . date("d-m-Y") . ".csv");
        echo $this->array2csv($data);
    }

    public function exportUsers() {
        $sql = 'select ID, USERNAME, EMAIL, ROLE, SCORE, CREATED_AT, UPDATED_AT from users';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        $this->download_send_headers("MEqX_users_export_" . date("d-m-Y") . ".csv");
        echo $this->array2csv($data);
    }
}

?>