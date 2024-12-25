<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        $CustomerID = $_POST['CustomerID'];
        $EventID = $_POST['EventID'];
        $PaymentMethod = $_POST['PaymentMethod'];
        $TotalPayment = $_POST['TotalPayment'];
        $PaymentDate = $_POST['PaymentDate'];

        if ($action === 'add') {
            $sql = "INSERT INTO Payments (CustomerID, EventID, PaymentMethod, TotalPayment, PaymentDate) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisds", $CustomerID, $EventID, $PaymentMethod, $TotalPayment, $PaymentDate); // Corrected the binding types
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'edit') {
            $PaymentID = $_POST['PaymentID'];
            $sql = "UPDATE Payments SET CustomerID = ?, EventID = ?, PaymentMethod = ?, TotalPayment = ?, PaymentDate = ? WHERE PaymentID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisdsi", $CustomerID, $EventID, $PaymentMethod, $TotalPayment, $PaymentDate, $PaymentID); // Corrected the binding types
            $stmt->execute();
            $stmt->close();
        }
    }

    header('Location: payment.php');
    exit();
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $deleteQuery = "DELETE FROM Payments WHERE PaymentID = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil dihapus');</script>";
            echo "<script>window.location.href = 'payment.php';</script>"; // Corrected the redirection URL
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } elseif ($_GET['action'] == 'get' && isset($_GET['id'])) {
        $id = $_GET['id'];
        error_log("Fetching data for ID: $id");
        $result = $conn->query("SELECT * FROM Payments WHERE PaymentID = '$id'");
        $data = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
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
            background-color: #fff;
            padding: 20px;
            border: 1px solid #888;
            width: 500px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-body {
            margin-top: 20px;
        }

        .modal-body input,
        .modal-body textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
        }

        .modal-footer button {
            background-color: #AFC2AE;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .modal-footer button:hover {
            background-color: #d5e4cf;
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

        #resultContainer {
            margin-top: 20px;
            display: none;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #resultContainer h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        #resultContainer table {
            width: 100%;
            border-collapse: collapse;
        }

        #resultContainer table th,
        #resultContainer table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
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
            <li><a href="admin1.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
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
            <li><a href="payment.php" class="active"><i class="fas fa-credit-card"></i> Payments</a></li>
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
        <table id="paymentTable">
            <tr>
                <th>ID</th>
                <th>CustomerID</th>
                <th>EventID</th>
                <th>PaymentMethod</th>
                <th>TotalPayment</th>
                <th>PaymentDate</th>
                <th>Action</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM Payments");
            while ($row = $result->fetch_assoc()) :
            ?>
                <tr id="row-<?php echo $row['PaymentID']; ?>">
                    <td><?php echo $row['PaymentID']; ?></td>
                    <td><?php echo $row['CustomerID']; ?></td>
                    <td><?php echo $row['EventID']; ?></td>
                    <td><?php echo $row['PaymentMethod']; ?></td>
                    <td><?php echo $row['TotalPayment']; ?></td>
                    <td><?php echo $row['PaymentDate']; ?></td>
                    <td>
                        <a href="#" onclick="openEditModal(<?php echo $row['PaymentID']; ?>)"><i class="fas fa-edit"></i></a> |
                        <a href="#" onclick="confirmDelete(<?php echo $row['PaymentID']; ?>)"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <button class="add-btn" onclick="openAddModal()">Add</button>
        <div style="margin-top: 20px; display: flex; align-items: center;">
            <label for="customerIDInput" style="margin-right: 10px;">Lihat Total Pembayaran Customer:</label>
            <input type="number" id="customerIDInput" name="customerID" placeholder="ID Customer" style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
            <button onclick="fetchTotalPayment()" style="padding: 8px 20px; background-color: #AFC2AE; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">Lihat</button>
        </div>


        <div id="resultContainer" style="margin-top: 20px; display: none;">
            <h3>Total Pembayaran Pending:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Total Pending Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="resultCustomerID"></td>
                        <td id="resultTotalPayment"></td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Add Payment Modal -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" onclick="closeAddModal()">&times;</span>
                    <h2>Add Payment</h2>
                </div>
                <div class="modal-body">
                    <form id="addPaymentForm" action="process_payment.php" method="POST">
                        <label for="customerID">Customer ID:</label>
                        <input type="text" id="addCustomerID" name="customerID" required>
                        <label for="eventID">Event ID:</label>
                        <input type="text" id="addEventID" name="eventID" required>
                        <label for="paymentMethod">Payment Method:</label>
                        <input type="text" id="addPaymentMethod" name="paymentMethod" required>
                        <label for="totalPayment">Total Payment:</label>
                        <input type="text" id="addTotalPayment" name="totalPayment" required>
                        <label for="paymentDate">Payment Date:</label>
                        <input type="date" id="addPaymentDate" name="paymentDate" required>
                        <div class="modal-footer">
                            <button type="button" onclick="closeAddModal()">Cancel</button>
                            <button type="submit">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Payment Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" onclick="closeEditModal()">&times;</span>
                    <h2>Edit Payment</h2>
                </div>
                <div class="modal-body">
                    <form id="editPaymentForm" action="process_payment.php" method="POST">
                        <input type="hidden" id="editPaymentID" name="paymentID">
                        <label for="editCustomerID">Customer ID:</label>
                        <input type="text" id="editCustomerID" name="customerID" required>
                        <label for="editEventID">Event ID:</label>
                        <input type="text" id="editEventID" name="eventID" required>
                        <label for="editPaymentMethod">Payment Method:</label>
                        <input type="text" id="editPaymentMethod" name="paymentMethod" required>
                        <label for="editTotalPayment">Total Payment:</label>
                        <input type="text" id="editTotalPayment" name="totalPayment" required>
                        <label for="editPaymentDate">Payment Date:</label>
                        <input type="date" id="editPaymentDate" name="paymentDate" required>
                        <div class="modal-footer">
                            <button type="button" onclick="closeEditModal()">Cancel</button>
                            <button type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function openAddModal() {
                document.getElementById('addModal').style.display = 'flex';
            }

            function closeAddModal() {
                document.getElementById('addModal').style.display = 'none';
            }

            function openEditModal(paymentID) {
                const row = document.getElementById('row-' + paymentID);
                const customerID = row.cells[1].innerText;
                const eventID = row.cells[2].innerText;
                const paymentMethod = row.cells[3].innerText;
                const totalPayment = row.cells[4].innerText;
                const paymentDate = row.cells[5].innerText;

                document.getElementById('editPaymentID').value = paymentID;
                document.getElementById('editCustomerID').value = customerID;
                document.getElementById('editEventID').value = eventID;
                document.getElementById('editPaymentMethod').value = paymentMethod;
                document.getElementById('editTotalPayment').value = totalPayment;
                document.getElementById('editPaymentDate').value = paymentDate;

                document.getElementById('editModal').style.display = 'flex';
            }

            function closeEditModal() {
                document.getElementById('editModal').style.display = 'none';
            }

            function confirmDelete(paymentID) {
                if (confirm('Are you sure you want to delete this payment?')) {
                    window.location.href = 'payment.php?action=delete&id=' + paymentID; // Ensure this URL matches your script location
                }
            }


            function fetchTotalPayment() {
                const customerID = document.getElementById('customerIDInput').value;
                if (!customerID) {
                    alert('Please enter a valid customer ID.');
                    return;
                }

                fetch('fetch_total_payment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'customerID': customerID
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('resultCustomerID').innerText = data.customerID;
                        document.getElementById('resultTotalPayment').innerText = data.totalPending;
                        document.getElementById('resultContainer').style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error fetching total payment:', error);
                    });
            }
        </script>
</body>

</html>