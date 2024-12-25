<?php
include 'koneksi.php';

$sql = "SELECT VendorName, Description, image FROM Vendors";
$result = $conn->query($sql);

$packages = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
}

$conn->close();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($packages);
?>
