<?php
include 'koneksi.php';

// Fetch notifications from the 'notifications' table
$sql = "SELECT Message FROM notifications";
$result = $conn->query($sql);

$notifications = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
} else {
    $notifications = [];
}

$conn->close();
?>