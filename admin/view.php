<?php
// Include file koneksi.php untuk menghubungkan ke database
include 'koneksi.php';

// Pastikan parameter view telah diset dan tidak kosong
if (isset($_GET['view'])) {
    // Sanitasi input untuk mencegah injeksi SQL (jika diperlukan)
    $view = htmlspecialchars($_GET['view']);

    // Mendefinisikan query berdasarkan nilai view yang diterima
    switch ($view) {
        case 'CustomerDetails':
            $query = "SELECT * FROM Customers";
            break;
        case 'OrderDetails':
            $query = "SELECT * FROM Orders";
            break;
        case 'IncomeDetails':
            // Query untuk mengambil total pembayaran dari prosedur CalculateTotalPayment
            $query = "CALL CalculateTotalPayment(@p_totalPayment)";
            break;
        case 'CustomerEventDetails':
            $query = "SELECT * FROM CustomerEventDetails";
            break;
        case 'EventPackageDetails':
            $query = "SELECT * FROM NearestEvents";
            break;
        case 'VendorDetails':
            $query = "SELECT * FROM CustomerOrderCount";
            break;
        case 'AnalysisReports':
            $query = "SELECT * FROM AnalysisReports";
            break;
        case 'UnpaidEvents':
            $query = "SELECT * FROM UnpaidEvents";
            break;
        default:
            $query = ""; // Jika view tidak valid, kosongkan query
            break;
    }

    // Jika query tidak kosong, eksekusi query dan kirimkan respons
    if (!empty($query)) {
        $result = $conn->query($query);

        $response = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        }

        // Mengirim respons dalam format JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Jika view tidak valid, kirim respons error
        echo json_encode(array('error' => 'Invalid view parameter.'));
    }
} else {
    // Jika parameter view tidak diset, kirim respons error
    echo json_encode(array('error' => 'View parameter not specified.'));
}

// Menutup koneksi database
$conn->close();
?>
