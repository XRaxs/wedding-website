<?php 
include 'koneksi.php';   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="loginadmin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="POST">
                    <h2 class="login100-form-title p-b-26">
                        Sign in Admin
                    </h2>
                    <div class="wrap-input100 validate-input" data-validate="Enter Username">
                        <input class="input100" type="text" name="username" placeholder="Insert Your Username" required>
                        <span class="focus-input100" data-placeholder="Insert Your Username"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        <input class="input100" type="password" name="password" placeholder="Insert Your Password" required>
                        <span class="focus-input100" data-placeholder="Insert Your Password"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" name="submit">
                            Sign In
                        </button>
                    </div>

                    <div class="text-center p-t-115">
                        <div class="link-container">
                            <span class="txt1">Login as User?</span>
                            <a class="txt2" href="../login.php">Click here</a>
                        </div>
                    </div>
                </form>
                <?php 
                if (isset($_POST['submit'])) {
                    $username = mysqli_escape_string($conn, $_POST['username']);
                    $password = mysqli_escape_string($conn, $_POST['password']);

                    $password = md5($password);
                    $query = $conn->query("SELECT * FROM admin WHERE username='$username' AND password='$password'");
                    $result = $query->num_rows;
                    if ($result == 1) {
                        session_start();
                        $_SESSION['admin'] = $query->fetch_assoc();
                        echo "<br>";
                        echo "<div class='alert alert-info'><center>Login Succeeded</center></div>";
                        echo "<meta http-equiv='refresh' content='1;url=admin1.php'>";
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

    <div id="dropDownSelect1"></div>

</body>
</html>
