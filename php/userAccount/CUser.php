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

                if(isset($_POST["username"]) && empty(trim($_POST["username"]))){
                    $this->username_err = "Please enter a username.";
                }
                else {
                    $sql = "SELECT id FROM users WHERE username = :username";

                    $stmt = BD::obtine_conexiune()->prepare($sql);

                    $param_username = trim($_POST["username"]);

                    if($stmt -> execute ([ 'username' => $param_username ])) {

                        if($stmt->rowCount()){
                            $this->username_err = "This username is already taken.";
                        }
                        else {
                            $this->username = trim($_POST["username"]);
                        }
                    }
                    else echo "Oops! Something went wrong. Please try again later.";
                }
            }

            if(isset($_POST["password"]) && empty(trim($_POST["password"]))){
                $this->password_err = "Please enter a password.";
            }
            else if(isset($_POST["password"]) && strlen(trim($_POST["password"])) < 6) {
                $this->password_err = "Password must have at least 6 characters.";
            }
            else if(isset($_POST["password"])) {
                $this->password = trim($_POST["password"]);
            }

            if(isset($_POST["confirm_password"]) && empty(trim($_POST["confirm_password"]))) {
                $this->confirm_password_err = "Please confirm password.";
            }
            else if(isset($_POST["confirm_password"])){
                $this->confirm_password = trim($_POST["confirm_password"]);
                if(empty($this->password_err) && ($this->password != $this->confirm_password)) {
                    $this->confirm_password_err = "Password did not match.";
                }
            }


            if(empty($this->username_err) && empty($this->password_err) && empty($this->confirm_password_err)) {
                $this->model->adaugaUser($this->username, $this->password);
            }

            $this->view = new VUser($this->username_err, $this->password_err, $this->confirm_password_err);
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
                        $this->username = $data['family_name'].$data['given_name'];
                    }

                    if(!empty($data['picture']))
                    {
                        $this->photo = $data['picture'];
                    }
                }
                else {
                    $access_token = $this->facebook_helper->getAccessToken();

                    $this->facebook->setDefaultAccessToken($access_token);

                    $graph_response = $this->facebook->get("/me?fields=name,email", $access_token);

                    $facebook_user_info = $graph_response->getGraphUser();

                    if(!empty($facebook_user_info['id']))
                    {
                        $this->photo = 'http://graph.facebook.com/'.$facebook_user_info['id'].'/picture';
                    }

                    if(!empty($facebook_user_info['name']))
                    {
                        $this->username = str_replace(' ', '', $facebook_user_info['name']);
                    }
                }
                $this->model->autentificaSocial($this->username, $this->photo);
            }
            else {
                if (isset($_POST["username"]) && empty(trim($_POST["username"]))) {
                    $this->username_err = "Please enter username.";
                } else if (isset($_POST["username"])) {
                    $this->username = trim($_POST["username"]);
                }

                if (isset($_POST["password"]) && empty(trim($_POST["password"]))) {
                    $this->password_err = "Please enter your password.";
                } else if (isset($_POST["password"])) {
                    $this->password = trim($_POST["password"]);
                }


                if (empty($this->username_err) && empty($this->password_err)) {
                    $valid = $this->model->autentificaUser($this->username, $this->password);
                }
                //}
                if (!empty($valid) && (isset($_POST["username"]) || isset($_POST["password"])))
                    $valid === "password" ? $this->password_err = "The password you entered was not valid." : $this->username_err = "No account found with that username.";

                $this->view = new VUser($this->username_err, $this->password_err, null);
                $this->view->oferaVizualizareLogin();
            }
            
        }

        public function resetPassword() {
            if(isset($_POST["password"]) && empty(trim($_POST["password"]))){
                $this->password_err = "Please enter new password.";     
            } 
            else if(isset($_POST["password"]) && strlen(trim($_POST["password"])) < 6) {
                $this->password_err = "Password must have at least 6 characters.";
            }
            else if(isset($_POST["password"])) {
                $this->password = trim($_POST["password"]);
            }
            
            if(isset($_POST["confirm_password"]) && empty(trim($_POST["confirm_password"]))) {
                $this->confirm_password_err = "Please confirm new password.";     
            } 

            else if(isset($_POST["confirm_password"])) {
                $this->confirm_password = trim($_POST["confirm_password"]);
                if(empty($this->password_err) && ($this->password != $this->confirm_password)) {
                    $this->confirm_password_err = "Password did not match.";
                }
            }


            if(empty($this->username_err) && empty($this->confirm_password_err)) {
                $this->model->resetPassword($this->password);
            }
            
            $this->view = new VUser($this->username_err, $this->password_err, $this->confirm_password_err);
            $this->view->oferaVizualizareReset();
        }

    }

?>