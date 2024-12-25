<?php
// Cek jika ada parameter "thankyou" dan bernilai "true"
if (isset($_GET['thankyou']) && $_GET['thankyou'] == "true") {
    echo "<script>alert('Terima kasih sudah menggunakan jasa kami!');</script>";
}
?>
