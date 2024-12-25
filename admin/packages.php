<?php
// Include file koneksi
include 'koneksi.php';
$uploadDirectory = "uploud/"; // Gunakan path relatif yang benar

// Cek jika metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil nilai dari formulir dengan validasi
    $PackageName = isset($_POST['PackageName']) ? $_POST['PackageName'] : '';
    $Description = isset($_POST['Description']) ? $_POST['Description'] : '';
    $Price = isset($_POST['Price']) ? $_POST['Price'] : '';

    // Escape string untuk keamanan
    $PackageName = $conn->real_escape_string($PackageName);
    $Description = $conn->real_escape_string($Description);
    $Price = $conn->real_escape_string($Price);

    // Upload gambar
    if (isset($_FILES['ImageFile']) && $_FILES['ImageFile']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['ImageFile']['name'];
        $tmp = $_FILES['ImageFile']['tmp_name'];
        $uploadPath = $uploadDirectory . basename($image); // Gunakan path relatif

        // Pindahkan file yang diunggah ke direktori tujuan
        if (move_uploaded_file($tmp, $uploadPath)) {
            echo "File valid dan berhasil diunggah.\n";
            // Simpan path relatif di database
            $imagePath = $uploadPath;
        } else {
            echo "Kemungkinan serangan pengunggahan file!\n";
            $imagePath = '';
        }

        // Tampilkan path file yang diunggah
        echo "File yang diunggah: " . htmlspecialchars($imagePath) . "\n";
    } else {
        $imagePath = ''; // Atau nilai default lain yang Anda inginkan
    }

    // Cek nilai action
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'add') {
        // Query untuk menambahkan data promo
        $sql = "INSERT INTO Packages (image, PackageName, Description, Price) VALUES ('$imagePath', '$PackageName', '$Description', '$Price')";

        // Eksekusi query
        if ($conn->query($sql) === TRUE) {
            // Jika berhasil, arahkan ke halaman packages.php
            header('Location: packages.php');
            exit; // Penting untuk menghentikan eksekusi script setelah mengarahkan halaman
        } else {
            // Jika terjadi kesalahan, tampilkan pesan kesalahan
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($action === 'edit') {
        $PackageID = $_POST['PackageID'];
        if ($imagePath) {
            $sql = "UPDATE Packages SET PackageName = ?, Description = ?, Price = ?, image = ? WHERE PackageID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $PackageName, $Description, $Price, $imagePath, $PackageID);
        } else {
            $sql = "UPDATE Packages SET PackageName = ?, Description = ?, Price = ? WHERE PackageID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $PackageName, $Description, $Price, $PackageID);
        }
        $stmt->execute();
        $stmt->close();

        // Jika berhasil, arahkan ke halaman packages.php
        header('Location: packages.php');
        exit; // Penting untuk menghentikan eksekusi script setelah mengarahkan halaman
    }
}

// Saat menghapus paket
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $deleteQuery = "DELETE FROM Packages WHERE PackageID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Saat mengambil data paket
if (isset($_GET['action']) && $_GET['action'] == 'get' && isset($_GET['id'])) {
    $id = $_GET['id'];
    error_log("Fetching data for ID: $id");
    $result = $conn->query("SELECT * FROM Packages WHERE PackageID = '$id'");
    $data = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f6f6f6;
            font-size: 14px;
        }

        .topbar {
            width: 100%;
            background-color: #FFFFFF;
            color: #AFC2AE;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        .topbar .left {
            display: flex;
            align-items: center;
        }

        .topbar .logo {
            font-size: 1.5em;
            margin-right: 20px;
            color: #AFC2AE;
            font-weight: bold;
        }

        .topbar .profile {
            color: #AFC2AE;
            margin-right: auto;
        }

        .topbar .logout {
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 14px;
            background-color: #AFC2AE;
            margin-right: 50px;
            width: 100px;
            height: 30px;
            border-radius: 20px;
            padding-left: 15px;
        }

        .topbar .logout i {
            margin-right: 5px;
            color: white;
        }

        .navbar {
            width: 100%;
            background-color: black;
            position: fixed;
            top: 55px;
            z-index: 999;
            padding: 10px 0;
        }

        .navbar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            font-size: 12px;
            justify-content: flex-start;
        }

        .navbar ul li {
            margin: 0 10px;
        }

        .navbar ul li a {
            color: #AFC2AE;
            text-decoration: none;
            padding: 8px 15px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s, color 0.3s;
            border-radius: 20px;
        }

        .navbar ul li a i {
            margin-right: 10px;
            color: #AFC2AE;
        }

        .navbar ul li a:hover,
        .navbar ul li a.active {
            background-color: #fff;
            color: black;
            border-radius: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 130px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: #fff;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        a:hover {
            text-decoration: underline;
        }

        .add-btn {
            background-color: #AFC2AE;
            padding: 10px 20px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 20px;
            display: inline-block;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .modal-header h2 {
            margin: 0;
        }

        .close {
            color: #aaa;
            cursor: pointer;
            font-size: 28px;
            font-weight: bold;
            border: none;
            background: none;
        }

        .close:hover,
        .close:focus {
            color: black;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .form-group input[type="file"] {
            padding: 3px;
        }

        .form-group input[type="submit"] {
            background-color: #AFC2AE;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #4CAF50;
        }

        .actions i {
            cursor: pointer;
        }

        .image-cell img {
            max-width: 150px;
            height: auto;
        }
        .navbar ul {
            color: #ffffff;
            list-style-type: none;
            padding: 0;
        }

        .navbar ul li {
            position: relative;
        }

        .navbar ul li a {
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            color: #fff;
        }

        .navbar ul li a:hover {
            color: black;
            background-color: #fff;
        }

        .navbar ul li .dropdown {
            border-radius: 5px;
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            color: black;
            background-color: #fff;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        }

        .navbar ul li:hover .dropdown {
            display: block;
        }

        .navbar ul li .dropdown li {
            width: 150px;
            border-radius: 5px;
        }

        .navbar ul li .dropdown li a {
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            color: black;
            /* Ubah warna teks menjadi hitam */
        }
    </style>
</head>

<body>
    <div class="topbar">
        <div class="left">
            <div class="logo">WOHO</div>
        </div>
        <div class="profile">
            <span>Hi, Youuu</span>
        </div>
        <button onclick="location.href='login.php'" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>
    <div class="navbar">
        <ul>
            <li><a href="admin1.php" ><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="customer.php"><i class="fas fa-users"></i> Customer</a></li>
            <li>
                <a href="order.php"><i class="fas fa-shopping-cart"></i> Order</a>
                <ul class="dropdown">
                    <li><a href="event.php"><i class="fas fa-gift"></i> Event</a></li>
                </ul>
            </li>
            <li><a href="report.php"><i class="fas fa-chart-bar"></i> Report</a></li>
            <li>
                <a href="packagesvendor.php"><i class="fas fa-boxes"></i> Packages Vendor</a>
                <ul class="dropdown">
                    <li><a href="packages.php" class="active"><i class="fas fa-box"></i> Packages</a></li>
                    <li><a href="vendors.php"><i class="fas fa-store-alt"></i> Vendors</a></li>
                </ul>
            </li>
            <li><a href="payment.php"><i class="fas fa-credit-card"></i> Payments</a></li>
            <li>
                <a href="notification.php"><i class="fas fa-bell"></i> Notification</a>
                <ul class="dropdown">
                    <li><a href="feedback.php"><i class="fas fa-comment"></i> Notif Feedback</a></li>
                </ul>
            </li>
            <li><a href="log_riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
        </ul>
    </div>
    <div class="container">
        
        <table>
            <thead>
                <tr>
                <th>ID</th>
                    <th>Image</th>
                    <th>Package Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data dari database
                $result = $conn->query("SELECT * FROM Packages");

                // Loop melalui data dan tampilkan dalam tabel
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['PackageID']. "</td>"; 
                    echo "<td class='image-cell'><img src='" . $row['image'] . "' alt='Package Image'></td>";
                    echo "<td>" . $row['PackageName'] . "</td>";
                    echo "<td>" . $row['Description'] . "</td>";
                    echo "<td>" . $row['Price'] . "</td>";
                    echo "<td class='actions'>";
                    echo "<i class='fas fa-edit' onclick='openEditModal(" . $row['PackageID'] . ")'></i>";
                    echo "<i class='fas fa-trash' onclick='deletePackage(" . $row['PackageID'] . ")'></i>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
           
        </table>
        <button class="add-btn" onclick="openAddModal()">Add</button>
    </div>

    <!-- Modal untuk Tambah/Edit Paket -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Add Package</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form id="package-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="PackageName">Package Name</label>
                    <input type="text" id="PackageName" name="PackageName" required>
                </div>
                <div class="form-group">
                    <label for="Description">Description</label>
                    <textarea id="Description" name="Description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="Price">Price</label>
                    <input type="number" id="Price" name="Price" required>
                </div>
                <div class="form-group">
                    <label for="ImageFile">Image</label>
                    <input type="file" id="ImageFile" name="ImageFile" accept="image/*">
                </div>
                <div class="form-group">
                    <input type="hidden" id="PackageID" name="PackageID">
                    <input type="hidden" id="action" name="action">
                    <input type="submit" value="Save">
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('modal-title').innerText = 'Add Package';
            document.getElementById('PackageID').value = '';
            document.getElementById('PackageName').value = '';
            document.getElementById('Description').value = '';
            document.getElementById('Price').value = '';
            document.getElementById('ImageFile').value = '';
            document.getElementById('action').value = 'add';
            document.getElementById('modal').style.display = 'flex';
        }

        function openEditModal(id) {
            fetch('packages.php?action=get&id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-title').innerText = 'Edit Package';
                    document.getElementById('PackageID').value = data.PackageID;
                    document.getElementById('PackageName').value = data.PackageName;
                    document.getElementById('Description').value = data.Description;
                    document.getElementById('Price').value = data.Price;
                    document.getElementById('action').value = 'edit';
                    document.getElementById('modal').style.display = 'flex';
                })
                .catch(error => console.error('Error:', error));
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function deletePackage(id) {
            if (confirm('Are you sure you want to delete this package?')) {
                window.location.href = 'packages.php?action=delete&id=' + id;
            }
        }
    </script>
</body>

</html>
