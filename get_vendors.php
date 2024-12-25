<?php
include "koneksi.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $packageID = $_POST['packageID'];

    // Memanggil prosedur tersimpan untuk mendapatkan nama vendor
    $stmt = $conn->prepare("CALL GetVendorsByPackageID(?)");
    $stmt->bind_param("i", $packageID);
    $stmt->execute();
    $result = $stmt->get_result();

    $vendorNames = "";
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $vendorNames = $row['VendorNames'];
    }

    $stmt->close();
    header('Content-Type: application/json'); // tambahkan ini
    echo json_encode(['vendorNames' => $vendorNames]);
}
?>
