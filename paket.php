<?php
include 'koneksi.php';

// Ambil data paket dari database
$sql = "SELECT * FROM Packages";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data dari setiap baris
    while($row = $result->fetch_assoc()) {
        echo '<div class="package-card" data-package="' . $row["PackageName"] . '" data-price="' . $row["Price"] . '">';
        echo '<img src="' . $row["ImageURL"] . '" alt="' . $row["PackageName"] . '" class="packimg">';
        echo '<p class="namapaket">' . $row["PackageName"] . '</p>';
        // Tambahkan detail lainnya sesuai kebutuhan
        echo '<p class="price">' . $row["Price"] . '</p>';
        echo '<a href="#booking" class="btn order-btn">Order now</a>';
        echo '</div>';
    }
} else {
    echo "Tidak ada paket yang tersedia.";
}
$conn->close();
?>
