<?php
require_once __DIR__ . "/../db_utils/database_conn.php";
require_once __DIR__ . "/UserRoles.php";
require_once __DIR__ . "/../configDomain.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer\Exception.php';
require 'PHPMailer\PHPMailer.php';
require 'PHPMailer\SMTP.php';


class MUser {

    public function adaugaUser($username_, $email_, $password_) {
        if(isset($_POST)){
            $sql = 'INSERT INTO users (id, username, email, password, role, created_at, updated_at) VALUES (:id, :username, :email, :password, 0, sysdate(), sysdate())';
            $query = 'select max(id) as maxid from users';
            
            $stmt = BD::obtine_conexiune()->prepare($sql);
            $stmt2 = BD::obtine_conexiune()->prepare($query);
            
            $stmt2 -> execute();
	    	$response = $stmt2->fetch();
            
	    	$newid = $response["maxid"] + 1;
            $param_password = password_hash($password_, PASSWORD_DEFAULT); 
            
            if($stmt -> execute ([ 
                'id' => $newid,
                'username' => $username_,
                'email' => $email_,
                'password' => $param_password ])) {
                
                $_SESSION['role'] = UserRoles::USER;
                $_SESSION["loggedin"] = true;
                $_SESSION["userid"] = $newid;
                $_SESSION["username"] = $username_; 
                $_SESSION['start'] = time();
                $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);   
                
                header("location: ../../index.html");
            } 
            else {
                echo "Something went wrong. Please try again later.";
            }
        }
    }


    public function autentificaUser($username_, $password_) {
        $sql = 'SELECT id, username, password, role FROM users WHERE username = :username OR email = :username';
        
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
                'username' => $username_
            ]);  

        $array = $stmt->fetch(PDO::FETCH_ASSOC);
        $temp_username = $array['username'];
        $temp_id = $array['id'];
        $temp_pass = $array['password'];
          
        // Check if username exists, if yes then verify password
        if($temp_username != null){                 
            if(password_verify($password_, $temp_pass)){
                    session_start();
                    
                    $_SESSION['role'] = $array['role'];
                    $_SESSION['loggedin'] = true;
                    $_SESSION['userid'] = $temp_id;
                    $_SESSION['username'] = $temp_username;
                    $_SESSION['start'] = time();
                    $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);    
                    
                    header("location: ../../index.html");
            } else{
                //$password_err = "The password you entered was not valid.";
                return "password";
            }

        } else{
            return "username.";
        }  
        return;
    }

    public function resetPassword($token, $password_) {
        $sql = 'UPDATE users SET password = :password where token = :token';
        $stmt = BD::obtine_conexiune()->prepare($sql);

        $param_password = password_hash($password_, PASSWORD_DEFAULT);

        if ($stmt->execute([
            'password' => $param_password,
            'token' => $token
        ])){
            header("location: login.php");
        }

    }

    public function autentificaSocial($username, $photo, $email) {
        $sql = 'SELECT id FROM users WHERE email like :email';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
            'email' => $email
        ]);
        if($stmt->fetchColumn() == 0) {
            $query = 'select max(id) as maxid from users';
            $stmt2 = BD::obtine_conexiune()->prepare($query);

            $stmt2 -> execute();
            $response = $stmt2->fetch();

            $newid = $response["maxid"] + 1;

            $sql = 'INSERT INTO users (id, username, email, image_path, created_at, updated_at) VALUES (:id, :username, :email, :image_path, sysdate(), sysdate())';
            $stmt = BD::obtine_conexiune()->prepare($sql);

            if($stmt -> execute ([
                'id' => $newid,
                'username' => $username,
                'image_path' => $photo,
                'email' => $email ])) {

                $_SESSION['role'] = 0;
                $_SESSION["loggedin"] = true;
                $_SESSION["userid"] = $newid;
                $_SESSION["username"] = $username;
                $_SESSION['start'] = time();
                $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);

                header("location: ../../index.html");
            }
            else {
                echo "Something went wrong. Please try again later." . $photo;
            }
        }
        else {
            $sql = 'SELECT id, username, role FROM users WHERE email like :email';
            $stmt = BD::obtine_conexiune()->prepare($sql);
            $stmt -> execute ([
                'email' => $email
            ]);

            $array = $stmt->fetch(PDO::FETCH_ASSOC);
            $temp_username = $array['username'];
            $temp_id = $array['id'];

            if($temp_username != null){
                    session_start();

                    $_SESSION['role'] = $array['role'];
                    $_SESSION['loggedin'] = true;
                    $_SESSION['userid'] = $temp_id;
                    $_SESSION['username'] = $temp_username;
                    $_SESSION['start'] = time();
                    $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);

                    header("location: ../../index.html");
            }
        }
    }

    public function sendEmail($email) {
        $token = bin2hex(random_bytes(50));

        $sql = 'UPDATE users SET token = :token WHERE email = :email';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
            'token' => $token,
            'email' => $email
        ]);

        $sql = 'SELECT username from users WHERE email = :email';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
            'email' => $email
        ]);
        $array = $stmt->fetch(PDO::FETCH_ASSOC);


        $mail = new PHPMailer(TRUE);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tw.meq2020@gmail.com';
            $mail->Password = 'tehnologiiweb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('tw.meq2020@gmail.com');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Reset your password - MEqX';
            $msg = "Hello, " . $array['username'] . "! Click on this <a href=\"" . DomainPath::DOMAINPROTOCOL . "://" . DomainPath::MAINDOMAIN . "/php/userAccount/reset.php?action=resetPass&token=" . $token . "\">link</a> to reset your password on MEqX. <br><br><br><br> Have a good day! <br><br>  Regards, <br>MEqX team.";
            $msg = wordwrap($msg, 70);

            $mail->Body = $msg;

            $mail->send();

        }
        catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function sendDeletionEmail($id_user) {
        $token = bin2hex(random_bytes(50));

        $sql = 'select email from users where id = :id';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
            'id' => $id_user,
        ]);
        $email = $stmt->fetch(PDO::FETCH_ASSOC)['email'];

        $sql = 'UPDATE users SET token = :token WHERE email like :email and email is not null';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
            'token' => $token,
            'email' => $email
        ]);
        $sql = 'SELECT username from users WHERE email = :email';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
            'email' => $email
        ]);
        $array = $stmt->fetch(PDO::FETCH_ASSOC);


        $mail = new PHPMailer(TRUE);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tw.meq2020@gmail.com';
            $mail->Password = 'tehnologiiweb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('tw.meq2020@gmail.com');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Delete your account - MEqX';
            $msg = "Hello, " . $array['username'] . "! Click on this <a href=\"" . DomainPath::DOMAINPROTOCOL . "://" . DomainPath::MAINDOMAIN . "/php/userAccount/delete.php?token=" . $token . "\">link</a> to delete your account on MEqX. <br><br><br><br> If you didn't request this account deletion please reset your password. <br><br>  Regards, <br>MEqX team.";
            $msg = wordwrap($msg, 70);
            $mail->Body = $msg;
            $mail->send();
            header("location: profilepage.php?action=confirmMail");
        }
        catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function deleteUser($token) {
        $sql = "select id from users
        WHERE token = :token";
        $request = BD::obtine_conexiune()->prepare($sql);
        $request->execute([
            'token' => $token
        ]);
        $id_user = $request->fetch(PDO::FETCH_ASSOC)['id'];
        if($id_user > 0){
            $sql = "DELETE from users
            WHERE token = :token";
            $request = BD::obtine_conexiune()->prepare($sql);
            $request->execute([
                'token' => $token
            ]);

            $sql = "UPDATE documents
            SET id_user = -1, public = 0
            WHERE id_user = :id_user";
            $request = BD::obtine_conexiune()->prepare($sql);
            $request->execute([
                'id_user' => $id_user
            ]);

            header("location: logout.php");
        }
        else {
            echo 'Invalid token';
        }
    }
}



?>