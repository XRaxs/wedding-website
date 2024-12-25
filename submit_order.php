<?php
include "koneksi.php";
session_start();

// Verify Database Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
error_log("Database connected successfully");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $packageName = $conn->real_escape_string($_POST['package']);
    $price = floatval($_POST['price']);
    $paymentMethod = $conn->real_escape_string($_POST['payment-method']);
    $eventDate = $conn->real_escape_string($_POST['date']);
    $phone = $conn->real_escape_string($_POST['Phone']);
    $location = $conn->real_escape_string($_POST['Location']);
    $vendors = $conn->real_escape_string($_POST['vendor']);

    // Verify Form Data
    error_log("Form Data: Name=$name, Email=$email, Package=$packageName, Price=$price, Payment Method=$paymentMethod, Event Date=$eventDate, Phone=$phone, Location=$location, Vendors=$vendors");

    // Check if customer is logged in and get CustomerID from the session
    if (isset($_SESSION['login']) && isset($_SESSION['login']['CustomerID'])) {
        $customerID = $_SESSION['login']['CustomerID'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Customer not logged in.']);
        error_log("Error: Customer not logged in.");
        exit;
    }

    // Check Session Data
    error_log("Session Data: " . print_r($_SESSION, true));

    // Retrieve the PackageID based on the package name
    $sql = "SELECT PackageID FROM Packages WHERE PackageName = '$packageName'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $package = $result->fetch_assoc();
        $packageID = $package['PackageID'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Package not found.']);
        error_log("Error: Package not found.");
        exit;
    }

    error_log("Retrieved PackageID: " . $packageID);

    // Insert the order into the Orders table
    $sql = "INSERT INTO Orders (CustomerID, PackageID, CustomerName, Email, Package, Price, PaymentMethod, EventDate, Phone, Location, Vendors) 
    VALUES ('$customerID', '$packageID', '$name', '$email', '$packageName', '$price', '$paymentMethod', '$eventDate', '$phone', '$location', '$vendors')";

if ($conn->query($sql) === TRUE) {
echo "Terima kasih atas pesanan Anda. Silakan periksa pemberitahuan Anda.";

        // Prepare to call stored procedure
        if ($stmt = $conn->prepare("CALL BookEvent(?, ?, ?, ?, ?, ?, ?, ?, @bookingStatus)")) {
            $stmt->bind_param(
                "isssisss",
                $customerID,
                $name,
                $email,
                $phone,
                $packageID,
                $eventDate,
                $location,
                $paymentMethod
            );

            // Execute stored procedure
            if ($stmt->execute()) {
                // Retrieve the output parameter
                $result = $conn->query("SELECT @bookingStatus AS bookingStatus");
                if ($result) {
                    $row = $result->fetch_assoc();
                    $bookingStatus = $row['bookingStatus'];
                    echo json_encode(['success' => true, 'message' => $bookingStatus]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal mengambil status booking']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menjalankan prosedur tersimpan']);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyiapkan statement']);
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Metode request tidak valid']);
}
?>
