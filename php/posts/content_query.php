<?php
    require_once 'php/config.php';
    require_once 'CPostContent.php';
    require_once 'MPostContent.php';
    require_once 'VPostContent.php';
            
    if(isset($_GET['id'])){
        $_SESSION['currentpageid'] = $_GET['id']; //used for comments do not remove this
        $id = $_GET['id'];
        $controller = new CPostContent('showPostContent', array($id));
        BD::opreste_conexiune();
    }
    else {
        header("location: /../../search.php");
    }
?>