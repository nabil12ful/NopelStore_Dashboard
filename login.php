<?php
    session_start();
    if(isset($_SESSION['id'])){
        header("Location: index.php");
    }
    include('connect.php');
    include('includes/funcs/func.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashPass = sha1($password);
        // Validation
        $errors = array();
        if(empty($username)){ // check Username Has Been Empty
            $errors[] = "Please Enter Your Username.";
        }elseif(strlen($username) < 4){ // check Username if Less Than 5 chars
            $errors[] = "Username Must be More Than 3 Chars.";
        }elseif(strlen($username) > 20){ // check Username if More Than 20 chars
            $errors[] = "Username Must be Less Than 21 Chars.";
        }
        if(empty($password)){ // check Password Has Been Empty
            $errors[] = "Please Enter Your Password.";
        }elseif(strlen($password) < 8){ // check Password if Less Than 8 chars
            $errors[] = "Password Must be More Than 7 Chars.";
        }elseif(strlen($password) > 30){ // check Password if More Than 30 chars
            $errors[] = "Password Must be Less Than 31 Chars.";
        }
        // check empty errors And Check Username And Passowrd Is Right In Database
        if(empty($errors)){
            $stmt = $con->prepare("SELECT * FROM employee WHERE Username = ? AND Password = ? AND RegStatu = 1 AND Disabled = 0");
            $stmt->execute(array($username, $hashPass));
            $emp = $stmt->fetch();
            $count = $stmt->rowCount();
            if($count > 0){
                $_SESSION['id'] = $emp['ID'];
                $check = checkItem($emp['ID'], "Emp_ID", "settings");
                if($check > 0){
                    header("Location: index.php");
                    exit();
                }else{
                    $stmt1 = $con->prepare("INSERT INTO settings(Emp_ID) VALUE(?)");
                    $stmt1->execute(array($emp['ID']));
                    header("Location: index.php");
                    exit();
                }
            }else{
                $errors[] = "Not Found This Username And Password";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign In & Sign Up</title>

        <link rel="stylesheet" href="layout/css/all.min.css">
        <link rel="stylesheet" href="layout/css/style.css?v=<?php echo time(); ?>">
    </head>
    <body>
        
        <div class="container login-page">
            <div class="form-container">
                <div class="signin-up">
                    <form action="" method="POST" class="login-form">
                        <h2 class="title-form">Sign in</h2>
                        <?php
                            if(!empty($errors)){
                                foreach($errors as $err){
                                    echo "<span class='red'>" . $err . "</span>";
                                }
                            }
                        ?>
                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" name="username" placeholder="Username">
                        </div>
                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Password">
                        </div>
                        <input type="submit" value="Login" class="btn-form solid">

                        <p class="social-text">Or Sign in with social platforms</p>
                        <div class="social-media">
                            <a href="#" class="social-icon">
                                <i class="fab fa-facebook-f" aria-hidden="true"></i>
                            </a>
                            <a href="#" class="social-icon">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon">
                                <i class="fab fa-google"></i>
                            </a>
                            <a href="#" class="social-icon">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </form>

                    <form action="" class="signup-form">
                        <h2 class="title-form">Sign up</h2>
                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" name="" placeholder="Username">
                        </div>
                        <div class="input-field">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="" placeholder="Email">
                        </div>
                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="" placeholder="Password">
                        </div>
                        <input type="submit" value="Sign up" class="btn-form solid">

                        <p class="social-text">Or Sign up with social platforms</p>
                        <div class="social-media">
                            <a href="#" class="social-icon">
                                <i class="fab fa-facebook-f" aria-hidden="true"></i>
                            </a>
                            <a href="#" class="social-icon">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon">
                                <i class="fab fa-google"></i>
                            </a>
                            <a href="#" class="social-icon">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>


            <div class="panels-container">
                <div class="panel left-panel">
                    <div class="content">
                        <h3>New here ?</h3>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Tempore ipsum reprehenderit quasi?</p>
                        <button class="btn-form transparent" id="sign-up-btn">Sign up</button>
                    </div>

                    <img src="layout/images/login.svg" alt="" class="image">
                </div>

                <div class="panel right-panel">
                    <div class="content">
                        <h3>One of us ?</h3>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Tempore ipsum reprehenderit quasi?</p>
                        <button class="btn-form transparent" id="sign-in-btn">Sign in</button>
                    </div>

                    <img src="images/register.svg" alt="" class="image">
                </div>
            </div>
        </div>

        <!-- Argon Scripts -->
        <!-- Core -->
        <script src="layout/js/jquery.min.js"></script>
        <!-- Optional JS -->
        <script src="layout/js/main.js?v=<?php echo time(); ?>"></script>
    </body>
</html>
