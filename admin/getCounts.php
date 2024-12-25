<?php 
include 'koneksi.php';

// Query untuk mengambil jumlah Customer dari database
$sqlCustomer = "SELECT COUNT(*) as totalCustomers FROM Customers";
$resultCustomer = $conn->query($sqlCustomer);

// Query untuk mengambil jumlah Order dari database
$sqlOrder = "SELECT COUNT(*) as totalOrders FROM Orders";
$resultOrder = $conn->query($sqlOrder);

// Menampilkan hasil dalam format JSON
$response = array();
if ($resultCustomer->num_rows > 0) {
    $rowCustomer = $resultCustomer->fetch_assoc();
    $response['totalCustomers'] = $rowCustomer['totalCustomers'];
} else {
    $response['totalCustomers'] = 0;
}

if ($resultOrder->num_rows > 0) {
    $rowOrder = $resultOrder->fetch_assoc();
    $response['totalOrders'] = $rowOrder['totalOrders'];
} else {
    $response['totalOrders'] = 0;
}

// Mengirim respon dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);

// Menutup koneksi
$conn->close();
?>
