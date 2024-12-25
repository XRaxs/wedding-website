<?php
include 'koneksi.php';

$view = isset($_GET['view']) ? htmlspecialchars($_GET['view']) : '';

$response = array();

// Mendefinisikan kueri berdasarkan nilai view yang diterima
if ($view == 'CustomerDetails') {
    $query = "SELECT * FROM Customers";
} else if ($view == 'OrderDetails') {
    $query = "SELECT * FROM Orders";
}  else if ($view == 'CustomerEventDetails') {
    $query = "SELECT * FROM CustomerEventDetails";
} else if ($view == 'EventPackageDetails') {
    $query = "SELECT * FROM NearestEvents";
} else if ($view == 'VendorDetails') {
    $query = "SELECT * FROM CustomerOrderCount";
} else if ($view == 'AnalysisReports') {
    $query = "SELECT * FROM AnalysisReports";
} else if ($view == 'UnpaidEvents') {
    $query = "SELECT * FROM UnpaidEvents";
}

// Eksekusi kueri yang sesuai dengan view
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

// Mengirim respons dalam format JSON untuk view lainnya
header('Content-Type: application/json');
echo json_encode($response);

// Tutup koneksi database
$conn->close();
?>
