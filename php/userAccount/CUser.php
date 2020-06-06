<?php
require_once __DIR__ . "/../db_utils/database_conn.php";
require_once "User.php";
require_once "MUser.php";
require_once "VUser.php";

    class CUser extends User {

        private $model;
        private $view;
        private $username = "", $password = "", $confirm_password = "", $photo = "", $email= "";
        private $username_err = "", $password_err = "", $confirm_password_err = "", $email_err = "";
        public function __construct($param, $action)
        {

            parent::__construct();
            $this->model = new MUser();

            if($param === "register")
                $this->adaugaUser();
            else if($param === "login")
                $this->autentificaUser();
            else if($param === "reset")
                $this->resetPassword($action);
            else if($action == "sendDelEmail")
                $this->sendDeletionEmail($param);
            else if($action == "delete")
                $this->deleteAccount($param);
        }

        private function adaugaUser() {
            if($_SERVER["REQUEST_METHOD"] == "POST") {

                if(isset($_POST["username"]) && empty(trim($_POST["username"]))){
                    $this->username_err = "Please enter a username.";
                }
                elseif (isset($_POST["email"]) && empty(trim($_POST["email"]) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
                    $this->email_err = "Please enter a valid email.";
                }
                else {
                    $sql = "SELECT id FROM users WHERE username = :username";

                    $stmt = BD::obtine_conexiune()->prepare($sql);

                    $sql2 = "SELECT id FROM users WHERE email = :email";

                    $stmt2 = BD::obtine_conexiune()->prepare($sql2);

                    $param_username = trim($_POST["username"]);
                    $param_email = trim($_POST["email"]);

                    if($stmt -> execute ([ 'username' => $param_username]) && $stmt2 -> execute ([ 'email' => $param_email])) {

                        if($stmt->rowCount()){
                            $this->username_err = "This username is already taken.";
                        }
                        elseif ($stmt2->rowCount()) {
                            $this->email_err = "This email is already used.";
                        }
                        else {
                            $this->username = trim($_POST["username"]);
                            $this->email = trim($_POST["email"]);
                        }
                    }
                    else echo "Oops! Something went wrong. Please try again later.";
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


                if(empty($this->username_err) && empty($this->password_err) && empty($this->confirm_password_err) && empty($this->email_err)) {
                    $this->model->adaugaUser($this->username, $this->email, $this->password);
                    unset($_POST);
                }
            }

            $this->view = new VUser($this->username_err, $this->email_err, $this->password_err, $this->confirm_password_err);
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
                    $this->username_err = "Please enter username or email.";
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

                if (!empty($valid) && (isset($_POST["username"]) || isset($_POST["password"])))
                    $valid === "password" ? $this->password_err = "The password you entered was not valid." : $this->username_err = "No account found with that username or email.";

                $this->view = new VUser($this->username_err, $this->password_err, null, null);
                $this->view->oferaVizualizareLogin();
            }
            
        }

        public function resetPassword($action) {
            if($action == "resetPass") {
                if (isset($_POST["password"]) && empty(trim($_POST["password"])) || !isset($_POST["password"])) {
                    $this->password_err = "Please enter new password.";
                } else if (isset($_POST["password"]) && strlen(trim($_POST["password"])) < 6) {
                    $this->password_err = "Password must have at least 6 characters.";
                } else if (isset($_POST["password"])) {
                    $this->password = trim($_POST["password"]);
                }

                if ((isset($_POST["confirm_password"]) && empty(trim($_POST["confirm_password"]))) || !isset($_POST["confirm_password"])) {
                    $this->confirm_password_err = "Please confirm new password.";
                } else if (isset($_POST["confirm_password"])) {
                    $this->confirm_password = trim($_POST["confirm_password"]);
                    if (empty($this->password_err) && ($this->password != $this->confirm_password)) {
                        $this->confirm_password_err = "Password did not match.";
                    }
                }


                if (empty($this->password_err) && empty($this->confirm_password_err)) {
                    $this->model->resetPassword($_GET['token'] ,$this->password);
                }
            }
            elseif ($action == "emailSent") {
                if (isset($_POST["email"]) && empty(trim($_POST["email"])) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->email_err = "Please enter your email.";
                } else if (isset($_POST["email"])) {
                    $this->email = trim($_POST["email"]);
                }
                $this->model->sendEmail($this->email);
            }
            
            $this->view = new VUser($this->username_err, $this->password_err, $this->confirm_password_err, $this->email_err);
            $this->view->oferaVizualizareReset($action);
        }

        public function sendDeletionEmail($id_user){
            $this->model->sendDeletionEmail($id_user);
        }
        
        public function deleteAccount($token){
            $this->model->deleteUser($token);
        }
    }

?>