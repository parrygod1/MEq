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
    $info_user = $this->model->getProfileInfo($id_user);
    $view = new VProfile($info_user);
    $view->viewProfile();
  }

  /*public function uploadAvatar($id_user, $avatar_user) {
    $this->model->uploadAvatar($id_user, $avatar_user);
  }*/

}
?>
