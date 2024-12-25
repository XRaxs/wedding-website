<?php
// Include file koneksi
include 'koneksi.php';

// Cek jika metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil nilai dari formulir dengan validasi
    $OrderID = isset($_POST['OrderID']) ? $_POST['OrderID'] : '';
    $EventDate = isset($_POST['EventDate']) ? $_POST['EventDate'] : '';
    $CustomerID = isset($_POST['CustomerID']) ? $_POST['CustomerID'] : '';
    $PackageID = isset($_POST['PackageID']) ? $_POST['PackageID'] : '';
    $CustomerName = isset($_POST['CustomerName']) ? $_POST['CustomerName'] : '';
    $Email = isset($_POST['Email']) ? $_POST['Email'] : '';
    $Package = isset($_POST['Package']) ? $_POST['Package'] : '';
    $Price = isset($_POST['Price']) ? $_POST['Price'] : '';
    $PaymentMethod = isset($_POST['PaymentMethod']) ? $_POST['PaymentMethod'] : '';
    $Phone = isset($_POST['Phone']) ? $_POST['Phone'] : '';
    $Location = isset($_POST['Location']) ? $_POST['Location'] : '';
    $Vendors = isset($_POST['Vendors']) ? $_POST['Vendors'] : '';

    // Escape string untuk keamanan
    $OrderID = $conn->real_escape_string($OrderID);
    $EventDate = $conn->real_escape_string($EventDate);
    $CustomerID = $conn->real_escape_string($CustomerID);
    $PackageID = $conn->real_escape_string($PackageID);
    $CustomerName = $conn->real_escape_string($CustomerName);
    $Email = $conn->real_escape_string($Email);
    $Package = $conn->real_escape_string($Package);
    $Price = $conn->real_escape_string($Price);
    $PaymentMethod = $conn->real_escape_string($PaymentMethod);
    $Phone = $conn->real_escape_string($Phone);
    $Location = $conn->real_escape_string($Location);
    $Vendors = $conn->real_escape_string($Vendors);

    // Cek nilai action
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'add') {
        // Query untuk menambahkan data log riwayat
        $sql = "INSERT INTO log_riwayat (OrderID, EventDate, CustomerID, PackageID, CustomerName, Email, Package, Price, PaymentMethod, Phone, Location, Vendors) VALUES ('$OrderID', '$EventDate', '$CustomerID', '$PackageID', '$CustomerName', '$Email', '$Package', '$Price', '$PaymentMethod', '$Phone', '$Location', '$Vendors')";

        // Eksekusi query
        if ($conn->query($sql) === TRUE) {
            // Jika berhasil, arahkan ke halaman log_riwayat.php
            header('Location: log_riwayat.php');
            exit; // Penting untuk menghentikan eksekusi script setelah mengarahkan halaman
        } else {
            // Jika terjadi kesalahan, tampilkan pesan kesalahan
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($action === 'edit') {
        $LogID = $_POST['LogID'];
        $sql = "UPDATE log_riwayat SET OrderID = ?, EventDate = ?, CustomerID = ?, PackageID = ?, CustomerName = ?, Email = ?, Package = ?, Price = ?, PaymentMethod = ?, Phone = ?, Location = ?, Vendors = ? WHERE LogID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isissssdsssii", $OrderID, $EventDate, $CustomerID, $PackageID, $CustomerName, $Email, $Package, $Price, $PaymentMethod, $Phone, $Location, $Vendors, $LogID);
        $stmt->execute();
        $stmt->close();

        // Jika berhasil, arahkan ke halaman log_riwayat.php
        header('Location: log_riwayat.php');
        exit; // Penting untuk menghentikan eksekusi script setelah mengarahkan halaman
    }
}

// Saat menghapus log riwayat
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $deleteQuery = "DELETE FROM log_riwayat WHERE LogID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Saat mengambil data log riwayat
if (isset($_GET['action']) && $_GET['action'] == 'get' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM log_riwayat WHERE LogID = '$id'");
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
    <title>Log Riwayat</title>
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
            font-size: 14px;
            border-radius: 20px;
            margin-bottom: 10px;
        }

        .add-btn:hover {
            background-color: #90a18a;
        }

        th {
            background-color: #f2f2f2;
        }

        .form-container {
            display: none;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: calc(100% - 22px);
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .save-btn,
        .cancel-btn {
            padding: 10px 20px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 20px;
            margin-right: 10px;
        }

        .save-btn {
            background-color: #AFC2AE;
        }

        .save-btn:hover {
            background-color: #90a18a;
        }

        .cancel-btn {
            background-color: #d9534f;
        }

        .cancel-btn:hover {
            background-color: #c9302c;
        }

        .action-btn {
            cursor: pointer;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 5px;
        }

        .edit-btn {
            background-color: #f0ad4e;
            color: white;
        }

        .delete-btn {
            background-color: #d9534f;
            color: white;
        }

        .edit-btn:hover {
            background-color: #ec971f;
        }

        .delete-btn:hover {
            background-color: #c9302c;
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
                    <li><a href="packages.php"><i class="fas fa-box"></i> Packages</a></li>
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
            <li><a href="log_riwayat.php" class="active"><i class="fas fa-history"></i> Riwayat</a></li>
        </ul>
    </div>
    <div class="container">
      
        
        <div class="form-container" id="formContainer">
            <h3>Tambah/Edit Log Riwayat</h3>
            <form method="POST" id="logForm">
                <input type="hidden" name="LogID" id="LogID">
                <div class="form-group">
                    <label for="OrderID">OrderID:</label>
                    <input type="number" name="OrderID" id="OrderID" required>
                </div>
                <div class="form-group">
                    <label for="EventDate">Event Date:</label>
                    <input type="date" name="EventDate" id="EventDate" required>
                </div>
                <div class="form-group">
                    <label for="CustomerID">CustomerID:</label>
                    <input type="number" name="CustomerID" id="CustomerID" required>
                </div>
                <div class="form-group">
                    <label for="PackageID">PackageID:</label>
                    <input type="number" name="PackageID" id="PackageID" required>
                </div>
                <div class="form-group">
                    <label for="CustomerName">Customer Name:</label>
                    <input type="text" name="CustomerName" id="CustomerName" required>
                </div>
                <div class="form-group">
                    <label for="Email">Email:</label>
                    <input type="email" name="Email" id="Email" required>
                </div>
                <div class="form-group">
                    <label for="Package">Package:</label>
                    <input type="text" name="Package" id="Package" required>
                </div>
                <div class="form-group">
                    <label for="Price">Price:</label>
                    <input type="number" step="0.01" name="Price" id="Price" required>
                </div>
                <div class="form-group">
                    <label for="PaymentMethod">Payment Method:</label>
                    <input type="text" name="PaymentMethod" id="PaymentMethod" required>
                </div>
                <div class="form-group">
                    <label for="Phone">Phone:</label>
                    <input type="text" name="Phone" id="Phone" required>
                </div>
                <div class="form-group">
                    <label for="Location">Location:</label>
                    <input type="text" name="Location" id="Location" required>
                </div>
                <div class="form-group">
                    <label for="Vendors">Vendors:</label>
                    <input type="text" name="Vendors" id="Vendors" required>
                </div>
                <button type="button" class="cancel-btn" onclick="toggleForm()">Cancel</button>
                <button type="submit" class="save-btn">Save</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>OrderID</th>
                    <th>Event Date</th>
                    <th>CustomerID</th>
                    <th>PackageID</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Package</th>
                    <th>Price</th>
                    <th>Payment Method</th>
                    <th>Phone</th>
                    <th>Location</th>
                    <th>Vendors</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM log_riwayat";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['LogID']}</td>
                        <td>{$row['OrderID']}</td>
                        <td>{$row['EventDate']}</td>
                        <td>{$row['CustomerID']}</td>
                        <td>{$row['PackageID']}</td>
                        <td>{$row['CustomerName']}</td>
                        <td>{$row['Email']}</td>
                        <td>{$row['Package']}</td>
                        <td>{$row['Price']}</td>
                        <td>{$row['PaymentMethod']}</td>
                        <td>{$row['Phone']}</td>
                        <td>{$row['Location']}</td>
                        <td>{$row['Vendors']}</td>
                        <td class='action-buttons'>
                            <button class='edit-button' onclick='openFormEdit({$row['LogID']})'>Edit</button>
                            <button class='delete-button' onclick='deleteLog({$row['LogID']})'>Delete</button>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='14'>No data found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <button class="add-button" onclick="openForm()">Add New Log</button>
</div>

<div class="form-popup" id="logForm">
    <form method="post" class="form-container">
        <h3>Log Riwayat Form</h3>
        <input type="hidden" name="action" id="action" value="add">
        <input type="hidden" name="LogID" id="LogID">
        <div class="form-group">
            <label for="OrderID">Order ID</label>
            <input type="text" name="OrderID" id="OrderID" required>
        </div>
        <div class="form-group">
            <label for="EventDate">Event Date</label>
            <input type="date" name="EventDate" id="EventDate" required>
        </div>
        <div class="form-group">
            <label for="CustomerID">Customer ID</label>
            <input type="text" name="CustomerID" id="CustomerID" required>
        </div>
        <div class="form-group">
            <label for="PackageID">Package ID</label>
            <input type="text" name="PackageID" id="PackageID" required>
        </div>
        <div class="form-group">
            <label for="CustomerName">Customer Name</label>
            <input type="text" name="CustomerName" id="CustomerName" required>
        </div>
        <div class="form-group">
            <label for="Email">Email</label>
            <input type="email" name="Email" id="Email" required>
        </div>
        <div class="form-group">
            <label for="Package">Package</label>
            <input type="text" name="Package" id="Package" required>
        </div>
        <div class="form-group">
            <label for="Price">Price</label>
            <input type="number" name="Price" id="Price" required>
        </div>
        <div class="form-group">
            <label for="PaymentMethod">Payment Method</label>
            <input type="text" name="PaymentMethod" id="PaymentMethod" required>
        </div>
        <div class="form-group">
            <label for="Phone">Phone</label>
            <input type="tel" name="Phone" id="Phone" required>
        </div>
        <div class="form-group">
            <label for="Location">Location</label>
            <input type="text" name="Location" id="Location" required>
        </div>
        <div class="form-group">
            <label for="Vendors">Vendors</label>
            <input type="text" name="Vendors" id="Vendors" required>
        </div>
        <button type="submit" class="btn">Submit</button>
        <button type="button" class="btn cancel" onclick="closeForm()">Cancel</button>
    </form>
</div>

<script>
    function openForm() {
        document.getElementById("logForm").style.display = "block";
        document.getElementById("action").value = "add";
        document.getElementById("LogID").value = "";
    }

    function closeForm() {
        document.getElementById("logForm").style.display = "none";
    }

    function openFormEdit(logID) {
        document.getElementById("logForm").style.display = "block";
        document.getElementById("action").value = "edit";
        document.getElementById("LogID").value = logID;
        // Fetch existing data and fill the form
        fetch(`get_log.php?LogID=${logID}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("OrderID").value = data.OrderID;
                document.getElementById("EventDate").value = data.EventDate;
                document.getElementById("CustomerID").value = data.CustomerID;
                document.getElementById("PackageID").value = data.PackageID;
                document.getElementById("CustomerName").value = data.CustomerName;
                document.getElementById("Email").value = data.Email;
                document.getElementById("Package").value = data.Package;
                document.getElementById("Price").value = data.Price;
                document.getElementById("PaymentMethod").value = data.PaymentMethod;
                document.getElementById("Phone").value = data.Phone;
                document.getElementById("Location").value = data.Location;
                document.getElementById("Vendors").value = data.Vendors;
            })
            .catch(error => console.error('Error fetching log data:', error));
    }

    function deleteLog(logID) {
        if (confirm("Are you sure you want to delete this log?")) {
            fetch(`delete_log.php?LogID=${logID}`, {
                method: 'POST'
            })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        alert('Log deleted successfully');
                        location.reload();
                    } else {
                        alert('Error deleting log');
                    }
                })
                .catch(error => console.error('Error deleting log:', error));
        }
    }
</script>

</body>
</html>