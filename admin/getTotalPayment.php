<?php
include 'koneksi.php'; // Pastikan koneksi ke database sudah benar

// Menyimpan respons dalam array
$response = array();

try {
    // Memanggil stored procedure untuk mengisi nilai p_totalPayment
    $stmt = $conn->prepare("CALL CalculateTotalPayment(@p_totalPayment)");
    $stmt->execute();
    $stmt->close();

    // Mengambil nilai p_totalPayment dari MySQL session
    $selectResult = $conn->query("SELECT @p_totalPayment AS totalPayment");
    $row = $selectResult->fetch_assoc();
    $totalPayment = $row['totalPayment'];

    // Menyimpan hasil dalam respons
    $response['totalPayment'] = $totalPayment;
} catch (Exception $e) {
    // Menangani error jika terjadi
    $response['error'] = 'Error: ' . $e->getMessage();
}

// Mengirim respons dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);

// Tutup koneksi database
$conn->close();
?>
