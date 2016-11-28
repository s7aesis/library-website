<!DOCTYPE html>
<html lang="en">
    <?php include "php-inc/head.inc";
    ?>  
    <script src="javascript/dynamics.js"></script>

    <body>
        <?php include "php-inc/header.inc";
        ?>  
        
        <div class="content">
            <!--<div id="banner">-->
                <!-- The two following forms will appear on the left and right sides of the page. -->
                <!--<div class="form vertical-form vertical-form-left">
                    <h1 class="main-header">Login</h1>
                    <form method="post" name="search" onsubmit="return validateLogin();">
                        <input type="email" id="login-email" placeholder="Email" name="login-email">
                        <p id="login-email-error"></p>
                        <input type="password" id="login-password" placeholder="Password" name="login-password">
                        <p id="login-password-error"></p>
                        <input type="submit" value="Login">
                    </form>
                </div>
                <div class="form vertical-form vertical-form-right">
                    <h1 class="main-header">Register</h1>
                    <form action="search.html" method="get" name="search" onsubmit="return validateRegistration();">
                        <input type="email" id="registration-email" placeholder="Email" name="registration-username"> 
                        <p id="registration-email-error"></p>
                        <input type="password" id="registration-password" placeholder="Password" name="registration-password">
                        <p id="registration-password-error"></p>
                        <input type="password" id="registration-password-confirm" placeholder="Confirm Password" name="registration-password-confirm">
                        <p id="registration-password-confirm-error"></p>
                        <input type="submit" value="Register">
                    </form>
                </div>
            </div>-->
            <?php 
                require_once 'php-inc/database.php';
                require_once 'php-inc/validate.inc';
                require_once 'php-inc/accounts.php';
                $login = false;
                $errors = array();
                $numErrors = 0;

                session_start();
                if(isset($_POST['logout'])) {
                    session_unset();
                    session_destroy();
                }
                else if(isset($_SESSION['login-email'])) {
                    $login = true; 
                }
                else if (!isset($_SESSION['login-email']) && isset($_POST['login-email']) && isset($_POST['login-password'])) {
                    if (!validatePattern($errors, $_POST, 'login-email', '/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,})+$/')) {
                        $numErrors++;
                    }
                    if(!validatePattern($errors, $_POST, 'login-password', '/^([a-zA-Z0-9_\.\-])/')) {
                        $numErrors++;
                    }
                    print_r($errors);
                    print_r($numErrors);
                    if($numErrors == 0) {
                        try {
                            $pdo = new PDO($connection,$username,$password);
                            $stmt = $pdo->prepare('SELECT count(*) as count FROM `users` where `email`=:email and `password`=:password');
                            $stmt->bindValue(':email', $_POST['login-email']);
                            $stmt->bindValue(':password', $_POST['login-password']);
                            $stmt->execute();  
                            //$errors = $stmt->errorInfo();
                            if (!isset($errors['login-email']))
                            foreach ($stmt as $row) {
                                if ($row["count"] == 1) {
                                    $login = true;
                                    $_SESSION['login-email'] = $_POST['login-email'];
                                    echo "\nLogin valid";
                                } else {
                                    $errors['login-email'] = "User/password combo doesn't exist";
                                    echo "\nLogin invalid";
                                }
                            }
                        } catch (PDOException $e) {
                            die ("Database error. ".$e);
                        }
                    }
                } 
            
                generateForms($errors, $login);
            ?>
        </div>
        

        <?php include "php-inc/footer.inc";
        ?>  
    </body>
</html>
