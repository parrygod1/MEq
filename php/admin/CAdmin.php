<?php

class CAdmin {
    private $model;

    public function __construct($action, $decision, $idDoc) {
        if (isset($_SESSION['role']) && $_SESSION['role'] == UserRoles::ADMIN){
            $this->model = new MAdmin();
            
            if($action === 'showDocuments') {
                if($decision !== null && $idDoc !== null) {

                    $this->model->handleDocument($decision, $idDoc);
                }
                $docs = $this->model->showDocuments();
                $row_count = $this->model->docCount();
                $param = array($this->model->maxPerPage, $row_count);
                $view = new VAdmin($docs, $param);
                $view->display();
            }
            else {
                $view = new VAdmin(null, array(null, null));
                $view->displayUserSearch();
            }
        }
        else {
            header("location: index.html");

        }
    }
    
}