<?php 
    include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="signup.css">
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="POST">
                    <h2 class="login100-form-title p-b-26">
                        Create Account
                    </h2>
                    <div class="wrap-input100 validate-input" data-validate="Enter Name">
                        <input class="input100" type="text" name="nama" placeholder="Insert Your Name">
                        <span class="focus-input100" data-placeholder="Insert Your Name"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Valid email is: a@b.c">
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

                    <div class="wrap-input100 validate-input" data-validate="Enter Phone Number">
                        <input class="input100" type="tel" name="phone" placeholder="Insert Your Phone Number">
                        <span class="focus-input100" data-placeholder="Insert Your Phone Number"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter Address">
                        <input class="input100" type="text" name="alamat" placeholder="Insert Your Address">
                        <span class="focus-input100" data-placeholder="Insert Your Address"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" name="submit">
                            Sign Up
                        </button>
                    </div>
                </form>
                <?php 
                if (isset($_POST['submit'])) {
                    $nama = mysqli_escape_string($conn, $_POST['nama']);
                    $email = mysqli_escape_string($conn, $_POST['email']);
                    $password = mysqli_escape_string($conn, $_POST['password']);
                    $phone = mysqli_escape_string($conn, $_POST['phone']);
                    $alamat = mysqli_escape_string($conn, $_POST['alamat']);

                    $password = md5($password);
                    $validasi = $conn->query("SELECT * FROM Customers WHERE Email='$email'");
                    $q_validasi = $validasi->fetch_assoc();
                    if ($nama == '' || $email == '' || $password == '' || $phone == '' || $alamat == '') {
                        echo "<script>alert('Please fill in all data');</script>";
                    }
                    else if ($q_validasi) {
                        echo "<script>alert('Email has been registered');</script>";
                    }
                    else {
                        $query = $conn->query("INSERT INTO Customers (Email, password, Name, Phone, Address) VALUES ('$email', '$password', '$nama', '$phone', '$alamat')");
                        if ($query) {
                            echo "<br>";
                            echo "<div class='alert alert-info'>Sign Up Succeeded</div>";
                            echo "<meta http-equiv='refresh' content='1;url=login.php'>";
                        } else {
                            echo "<br>";
                            echo "<div class='alert alert-danger'>Sign Up Failed</div>";
                            echo "<meta http-equiv='refresh' content='1;url=signup.php'>";
                        }
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
