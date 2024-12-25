<?php
// db_connection.php

$conn = mysqli_connect("localhost", "root", "", "esembede");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
