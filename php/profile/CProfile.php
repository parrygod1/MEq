<?php
class CProfile
{
  private $model;
  public function __construct()
  {
    $this->model = new MProfile();
  }

  public function showProfileInfo($id_user) {
    if(isset($_POST["subButton"])) {
      $this->model->uploadAvatar($id_user, $_POST["subButton"]);
    }
    if(isset($_POST["deleteAcc"])) {
      $this->model->deleteUser($_SESSION['userid']);
    }
    $info_user = $this->model->getProfileInfo($id_user);
    $publications = $this->model->getUserPublications($id_user);
    $view = new VProfile(array($info_user, $id_user, $publications, $this->model->maxPerPage));
    $view->viewProfile();
  }

  /*public function uploadAvatar($id_user, $avatar_user) {
    $this->model->uploadAvatar($id_user, $avatar_user);
  }*/

}
?>
