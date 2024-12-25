<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerID = $_POST['customerID'];
    
    $stmt = $conn->prepare("CALL HitungTotalPemesananPending(?, @totalPending)");
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $stmt->close();
    
    $result = $conn->query("SELECT @totalPending as totalPending");
    $row = $result->fetch_assoc();
    
    echo json_encode(['customerID' => $customerID, 'totalPending' => $row['totalPending']]);
}
?>
