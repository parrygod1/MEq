<?php
require_once __DIR__ . "/../db_utils/database_conn.php";
require_once "User.php";
    class CUser extends User {

        private $model;
        private $view;
        private $username = "", $password = "", $confirm_password = "", $photo = "";
        private $username_err = "", $password_err = "", $confirm_password_err = "";
        public function __construct($param)
        {
            parent::__construct();
            $this->model = new MUser();
            if($param === "register")
                $this->adaugaUser();
            else if($param === "login")
                $this->autentificaUser();
            else if($param === "reset")
                $this->resetPassword();
        }

        private function adaugaUser() {
            if($_SERVER["REQUEST_METHOD"] == "POST") {

                if(empty(trim($_POST["username"]))){
                    $username_err = "Please enter a username.";
                }
                else {
                    $sql = "SELECT id FROM users WHERE username = :username";

                    $stmt = BD::obtine_conexiune()->prepare($sql);

                    $param_username = trim($_POST["username"]);

                    if($stmt -> execute ([ 'username' => $param_username ])) {

                        if($stmt->rowCount()){
                            $username_err = "This username is already taken.";
                        }
                        else {
                            $username = trim($_POST["username"]);
                        }
                    }
                    else echo "Oops! Something went wrong. Please try again later.";
                }
            }

            if(empty(trim($_POST["password"]))){
                $password_err = "Please enter a password.";
            }
            else if(strlen(trim($_POST["password"])) < 6) {
                $password_err = "Password must have at least 6 characters.";
            }
            else {
                $password = trim($_POST["password"]);
            }

            if(empty(trim($_POST["confirm_password"]))) {
                $confirm_password_err = "Please confirm password.";
            }

            else {
                $confirm_password = trim($_POST["confirm_password"]);
                if(empty($password_err) && ($password != $confirm_password)) {
                    $confirm_password_err = "Password did not match.";
                }
            }


            if(empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
                $this->model->adaugaUser($username, $password);
            }

            $this->view = new VUser($username_err, $password_err, $confirm_password_err);
            $this->view->oferaVizualizareRegister();
        }


        public function autentificaUser() {
            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                header("location: ../../index.html");
                exit;
            }
            //if($_SERVER["REQUEST_METHOD"] == "POST"){

            if(isset($_GET["code"]))
            {
                $token = $this->google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
                if(!isset($token['error']))
                {
                    $this->google_client->setAccessToken($token['access_token']);

                    $google_service = new Google_Service_Oauth2($this->google_client);

                    $data = $google_service->userinfo->get();

                    if(!empty($data['given_name']) && !empty($data['family_name']))
                    {
                        $username = $data['family_name'].$data['given_name'];
                    }

                    if(!empty($data['picture']))
                    {
                        $photo = $data['picture'];
                    }
                }
                else {
                    $access_token = $this->facebook_helper->getAccessToken();

                    $this->facebook->setDefaultAccessToken($access_token);

                    $graph_response = $this->facebook->get("/me?fields=name,email", $access_token);

                    $facebook_user_info = $graph_response->getGraphUser();

                    if(!empty($facebook_user_info['id']))
                    {
                        $photo = 'http://graph.facebook.com/'.$facebook_user_info['id'].'/picture';
                    }

                    if(!empty($facebook_user_info['name']))
                    {
                        $username = str_replace(' ', '', $facebook_user_info['name']);
                    }
                }
                $this->model->autentificaSocial($username, $photo);
            }
            else {
                if (empty(trim($_POST["username"]))) {
                    $username_err = "Please enter username.";
                } else {
                    $username = trim($_POST["username"]);
                }

                if (empty(trim($_POST["password"]))) {
                    $password_err = "Please enter your password.";
                } else {
                    $password = trim($_POST["password"]);
                }


                if (empty($username_err) && empty($password_err)) {
                    $valid = $this->model->autentificaUser($username, $password);
                }
                //}
                if (!empty($valid))
                    $valid === "password" ? $password_err = "The password you entered was not valid." : $username_err = "No account found with that username.";

                $this->view = new VUser($username_err, $password_err);
                $this->view->oferaVizualizareLogin();
            }
            
        }

        public function resetPassword() {
            if(empty(trim($_POST["password"]))){
                $password_err = "Please enter new password.";     
            } 
            else if(strlen(trim($_POST["password"])) < 6) {
                $password_err = "Password must have at least 6 characters.";
            }
            else {
                $password = trim($_POST["password"]);
            }
            
            if(empty(trim($_POST["confirm_password"]))) {
                $confirm_password_err = "Please confirm new password.";     
            } 

            else {
                $confirm_password = trim($_POST["confirm_password"]);
                if(empty($password_err) && ($password != $confirm_password)) {
                    $confirm_password_err = "Password did not match.";
                }
            }


            if(empty($username_err) && empty($confirm_password_err)) {
                $this->model->resetPassword($password);
            }
            
            $this->view = new VUser($username_err, null, $confirm_password_err);
            $this->view->oferaVizualizareReset();
        }

    }

?>