<?php 
require_once "MUser.php";
require_once "CUser.php";
require_once 'google/vendor/autoload.php';
require_once "User.php";
    class VUser extends User {
        private $username_err, $password_err, $confirm_password_err;

        public function __construct() {
            parent::__construct();
            $this->username_err = func_get_arg(0);
            $this->password_err = func_get_arg(1);
            $this->confirm_password_err = func_get_arg(2);


        }

    
        public function oferaVizualizareRegister() {
            ?>

            <!DOCTYPE html>
            <html lang="en">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Register</title>

            <link rel="stylesheet" href="../../css/style_signup.css">
            </head>
            <body>
            <div class="login-container">
                <h1>Sign Up</h1>
                <form action="" method="post">
                <p>Username</p>
                <input type="text" name="username" placeholder="Enter Username" required="required">
                <span class="help-block"><?php echo $this->username_err; ?></span>
                <p>Password</p>
                <input type="password" name="password" placeholder="Enter Password" required="required">
                <span style="font-size:10px; color: red;" class="help-block"><?php echo $this->password_err; ?></span>
                <p>Confirm your Password</p>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required="required">
                <span class="help-block"><?php echo $this->confirm_password_err; ?></span>

                <input type="submit" name="" value="Sign Up">
                <a href="login.php">Already have an account?</a>
                </form>


                <h4 style="text-align: center">OR</h4>
                <div class="social">

                <a href="<?php echo $this->google_client->createAuthUrl()?>"><img src="google/google.png" style="border-radius: 8px; float: left; width: 125px; padding-right: 3px"/></a>
                <a href="<?php echo $this->facebook_login_url?>"><img src="facebook/facebook2.jpg" style="border-radius: 8px; float: left; width: 125px"></a>

                </div>
            </div>
            </body>
            </html>

    <?php }  

        public function oferaVizualizareLogin() {
            ?>

            <!DOCTYPE html>
            <html lang="en">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login</title>

            <link rel="stylesheet" href="../../css/style_signup.css">
            </head>
            <body>
            <div class="login-container">
                <h1>Log In</h1>
                <form action="" method="post">
                <p>Username</p>
                <input type="text" name="username" placeholder="Enter Username" required="required">
                <span class="help-block"><?php echo $this->username_err; ?></span>
                <p>Password</p>
                <input type="password" name="password" placeholder="Enter Password" required="required">
                <span class="help-block"><?php echo $this->password_err; ?></span>
                <input type="submit" name="" value="Login">
                
                <a href="#">Forgot your password?</a><br>
                <a href="register.php">Don't have an account?</a>
                </form>

                <h4 style="text-align: center">OR</h4>
                <div class="social">

                    <a href="<?php echo $this->google_client->createAuthUrl()?>"><img src="google/google.png" style="border-radius: 8px; float: left; width: 125px; padding-right: 3px"/></a>
                    <a href="<?php echo $this->facebook_login_url?>"><img src="facebook/facebook2.jpg" style="border-radius: 8px; float: left; width: 125px"></a>

                </div>
            </div>
            </body>
            </html>

    <?php }

    public function oferaVizualizareReset() {
        ?>

    <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password</title>

        <link rel="stylesheet" href="../../css/style_signup.css">
        </head>
        <body>
        <div class="login-container">
            <h1>Reset Password</h1>
            <form action="" method="post">
            <p>New password</p>
            <input type="password" name="password" placeholder="Enter new password" required="required">
            <span style="font-size:10px; color: red;" class="help-block"><?php echo $this->password_err; ?></span>
            <p>Confirm your new password</p>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required="required">
            <span class="help-block"><?php echo $this->confirm_password_err; ?></span>
            <input type="submit" name="" value="Reset password">
            </form>
        </div>
        </body>
        </html>

    <?php }

    } ?>