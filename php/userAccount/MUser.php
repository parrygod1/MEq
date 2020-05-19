<?php
require_once __DIR__ . "/../db_utils/database_conn.php";
require_once __DIR__ . "/UserRoles.php";


class MUser {

    public function adaugaUser($username_, $password_) {
        $sql = 'INSERT INTO users (id, username, password, created_at, updated_at) VALUES (:id, :username, :password, sysdate(), sysdate())';
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
            'password' => $param_password ])) {
            
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


    public function autentificaUser($username_, $password_) {
        $sql = 'SELECT id, username, password, role FROM users WHERE username = :username';
        
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

    public function resetPassword($password_) {
        $temp_id = $_SESSION["userid"];

        $sql = 'UPDATE users SET password = :password where id = :id';
        $stmt = BD::obtine_conexiune()->prepare($sql);

        $param_password = password_hash($password_, PASSWORD_DEFAULT); 

        if($stmt -> execute ([ 
            'id' => $temp_id,
            'password' => $param_password ])) {

                header("location: ../welcome.php");
        }
    }

    public function autentificaSocial($username, $photo) {
        $sql = 'SELECT id FROM users WHERE username = :username';
        $stmt = BD::obtine_conexiune()->prepare($sql);
        $stmt -> execute ([
            'username' => $username
        ]);
        if($number_of_rows = $stmt->fetchColumn() == 0) {
            $query = 'select max(id) as maxid from users';
            $stmt2 = BD::obtine_conexiune()->prepare($query);

            $stmt2 -> execute();
            $response = $stmt2->fetch();

            $newid = $response["maxid"] + 1;

            $sql = 'INSERT INTO users (id, username, image_path, created_at, updated_at) VALUES (:id, :username, :image_path, sysdate(), sysdate())';
            $stmt = BD::obtine_conexiune()->prepare($sql);

            if($stmt -> execute ([
                'id' => $newid,
                'username' => $username,
                'image_path' => $photo ])) {

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
            $sql = 'SELECT id, username, role FROM users WHERE username = :username';

            $stmt = BD::obtine_conexiune()->prepare($sql);
            $stmt -> execute ([
                'username' => $username
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
}



?>