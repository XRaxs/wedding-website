<?php
// File: report.php

include 'koneksi.php';

// Handle POST requests (add/edit actions)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Extract customer data from POST
        $CustomerID = isset($_POST['CustomerID']) ? $_POST['CustomerID'] : null;
        $CustomerName = $_POST['CustomerName'];
        $Email = $_POST['Email'];
        $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT); // Hash password securely
        $Phone = $_POST['Phone'];
        $Address = $_POST['Address'];
        $Status = $_POST['Status'];

        if ($action === 'add') {
            // Insert into Customers table
            $sql = "INSERT INTO Customers (Name, Email, Password, Phone, Address, Status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $CustomerName, $Email, $Password, $Phone, $Address, $Status);
            if ($stmt->execute()) {
                echo "<script>alert('Customer added successfully');</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } elseif ($action === 'edit') {
            // Update Customers table
            $sql = "UPDATE Customers SET Name = ?, Email = ?, Password = ?, Phone = ?, Address = ?, Status = ? WHERE CustomerID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $CustomerName, $Email, $Password, $Phone, $Address, $Status, $CustomerID);
            if ($stmt->execute()) {
                echo "<script>alert('Customer updated successfully');</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }

        header('Location: customer.php'); // Redirect after handling POST request
        exit();
    }
}

// Handle GET requests (delete and fetch actions)
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $deleteQuery = "DELETE FROM Customers WHERE CustomerID = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "<script>alert('Customer deleted successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } elseif ($_GET['action'] == 'get' && isset($_GET['id'])) {
        $id = $_GET['id'];
        error_log("Fetching data for ID: $id");
        $result = $conn->query("SELECT * FROM Customers WHERE CustomerID = '$id'");
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
    <title>Customers</title>
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

        .modal-body select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .modal-footer {
            margin-top: 20px;
            text-align: right;
        }

        .btn {
            background-color: #AFC2AE;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            margin-left: 10px;
        }

        .btn:hover {
            background-color: #7e947d;
        }

        .close-btn {
            background-color: #ccc;
            color: black;
        }

        .close-btn:hover {
            background-color: #aaa;
        }

        .overlay {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
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
            <li><a href="admin1.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="customer.php" class="active"><i class="fas fa-users"></i> Customer</a></li>
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
            <li><a href="log_riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
        </ul>
    </div>

    <div class="container">
     

        <table id="customers-table">
            <thead>
                <tr>
                    <th>CustomerID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM Customers";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['CustomerID'] . "</td>";
                        echo "<td>" . $row['Name'] . "</td>";
                        echo "<td>" . $row['Email'] . "</td>";
                        echo "<td>" . $row['Phone'] . "</td>";
                        echo "<td>" . $row['Address'] . "</td>";
                        echo "<td>" . $row['Status'] . "</td>";
                        echo "<td>";


                        echo '<a href="#" onclick="openEditModal(' . $row['CustomerID'] . ')"><i class="fas fa-edit"></i></a>';
                        echo '<a href="#" onclick="confirmDelete(' . $row['CustomerID'] . ')"><i class="fas fa-trash-alt"></i></a>';


                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No customers found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button class="add-btn" onclick="openModal('add')">Add Customer</button>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Add Customer</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <input type="hidden" id="customer-id" name="customer-id">
                <input type="text" id="customer-name" name="customer-name" placeholder="Name" required><br><br>
                <input type="email" id="customer-email" name="customer-email" placeholder="Email" required><br><br>
                <input type="password" id="customer-password" name="customer-password" placeholder="Password" required><br><br>
                <input type="text" id="customer-phone" name="customer-phone" placeholder="Phone"><br><br>
                <textarea id="customer-address" name="customer-address" placeholder="Address"></textarea><br><br>
                <select id="customer-status" name="customer-status" required>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="modal-footer">
                <div class="btn-container">
                    <button type="button" class="btn close-btn" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn" onclick="saveCustomer()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div id="overlay" class="overlay"></div>

    <script>
        function openModal(action, id) {
            var modal = document.getElementById('modal');
            var overlay = document.getElementById('overlay');
            var title = document.getElementById('modal-title');
            var nameInput = document.getElementById('customer-name');
            var emailInput = document.getElementById('customer-email');
            var passwordInput = document.getElementById('customer-password');
            var phoneInput = document.getElementById('customer-phone');
            var addressInput = document.getElementById('customer-address');
            var statusSelect = document.getElementById('customer-status');

            if (action === 'add') {
                title.innerText = 'Add Customer';
                nameInput.value = '';
                emailInput.value = '';
                passwordInput.value = '';
                phoneInput.value = '';
                addressInput.value = '';
                statusSelect.value = 'Pending';
                document.getElementById('customer-id').value = '';
            } else if (action === 'edit') {
                title.innerText = 'Edit Customer';
                fetch('report.php?action=get&id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        nameInput.value = data.Name;
                        emailInput.value = data.Email;
                        phoneInput.value = data.Phone;
                        addressInput.value = data.Address;
                        statusSelect.value = data.Status;
                        document.getElementById('customer-id').value = id;
                    })
                    .catch(error => console.error('Error:', error));
            }

            modal.style.display = 'block';
            overlay.style.display = 'block';
        }

        function closeModal() {
            var modal = document.getElementById('modal');
            var overlay = document.getElementById('overlay');
            modal.style.display = 'none';
            overlay.style.display = 'none';
        }

        function saveCustomer() {
            var id = document.getElementById('customer-id').value;
            var action = id ? 'edit' : 'add';
            var formData = new FormData();
            formData.append('action', action);
            formData.append('CustomerID', id);
            formData.append('CustomerName', document.getElementById('customer-name').value);
            formData.append('Email', document.getElementById('customer-email').value);
            formData.append('Password', document.getElementById('customer-password').value);
            formData.append('Phone', document.getElementById('customer-phone').value);
            formData.append('Address', document.getElementById('customer-address').value);
            formData.append('Status', document.getElementById('customer-status').value);

            fetch('report.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    console.log(result);
                    closeModal();
                    window.location.reload(true); // Reload page to show updated data
                })
                .catch(error => console.error('Error:', error));
        }

        function deleteCustomer(id) {
            if (confirm('Are you sure you want to delete this customer?')) {
                fetch('report.php?action=delete&id=' + id)
                    .then(response => response.text())
                    .then(result => {
                        console.log(result);
                        window.location.reload(true); // Reload page to show updated data
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    </script>

</body>

</html>