<?php 
    include 'koneksi.php';
    session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign In</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="POST">
                    <h2 class="login100-form-title p-b-26">
                        Sign in 
                    </h2>
                    <div class="wrap-input100 validate-input" data-validate = "Valid email is: a@b.c">
                        <input class="input100" type="text" name="email" placeholder="Insert Your Email">
                        <span class="focus-input100" data-placeholder="Insert Your Email"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        <input class="input100" type="password" name="password" placeholder="Insert Your Password">
                        <span class="focus-input100" data-placeholder="Insert Your Password"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" name="submit">
                            Sign In
                        </button>
                    </div>
                    <div class="text-center p-t-115">
					<div class="link-container">
                            <span class="txt1">Don’t have an account?</span>
                            <a class="txt2" href="register.php">Sign Up</a>
                        </div>
                        <div class="link-container">
                            <span class="txt1">Login as Admin?</span>
                            <a class="txt2" href="admin/login.php">Click here</a>
                        </div>
                    </div>
                </form>
                
                <?php 
                if (isset($_POST['submit'])) {
                    $email = mysqli_escape_string($conn, $_POST['email']);
                    $password = mysqli_escape_string($conn, $_POST['password']);

                    $password = md5($password); // Encrypt password
                    $query = $conn->query("SELECT * FROM Customers WHERE Email='$email' AND password='$password'");
                    $result = $query->num_rows;
                    if ($result == 1) {
                        session_start();
                        $_SESSION['login'] = $query->fetch_assoc();
                        echo "<br>";
                        echo "<div class='alert alert-info'><center>Login Succeeded</center></div>";
                        echo "<meta http-equiv='refresh' content='1;url=user.php'>";
                    } else {
                        echo "<br>";
                        echo "<div class='alert alert-danger'><center>Login Failed</center></div>";
                        echo "<meta http-equiv='refresh' content='1;url=login.php'>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <script src="asset/login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="asset/login/vendor/animsition/js/animsition.min.js"></script>
    <script src="asset/login/vendor/bootstrap/js/popper.js"></script>
    <script src="asset/login/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="asset/login/vendor/select2/select2.min.js"></script>
    <script src="asset/login/vendor/daterangepicker/moment.min.js"></script>
    <script src="asset/login/vendor/daterangepicker/daterangepicker.js"></script>
    <script src="asset/login/vendor/countdowntime/countdowntime.js"></script>
    <script src="asset/login/js/main.js"></script>

</body>
</html>
