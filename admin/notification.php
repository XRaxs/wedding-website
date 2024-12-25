<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        $CustomerID = $_POST['CustomerID'];
        $Message = $_POST['Message'];
        $Description = $_POST['Description'];
        

        if ($action === 'add') {
            $sql = "INSERT INTO Notifications (CustomerID, Message, Description, NotificationDate) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $CustomerID, $Message, $Description);
            $stmt->execute();
            $stmt->close();

        } elseif ($action === 'edit') {
            $NotificationID = $_POST['NotificationID'];
            $sql = "UPDATE Notifications SET CustomerID = ?, Message = ?, Description = ?, NotificationDate = NOW(),  WHERE NotificationID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssi", $CustomerID, $Message, $Description, $NotificationID);
            $stmt->execute();
            $stmt->close();
        }
    }

    header('Location: notification.php');
    exit();
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $deleteQuery = "DELETE FROM Notifications WHERE NotificationID = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil dihapus');</script>";
            echo "<script>window.location.href = 'notification.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } elseif ($_GET['action'] == 'get' && isset($_GET['id'])) {
        $id = $_GET['id'];
        error_log("Fetching data for ID: $id");
        $result = $conn->query("SELECT * FROM Notifications WHERE NotificationID = '$id'");
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
    <title>Notification</title>
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
                <a href="notification.php" class="active"><i class="fas fa-bell"></i> Notification</a>
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
                    <th>Notification ID</th>
                    <th>Customer ID</th>
                    <th>Message</th>
                    <th>Description</th>
                    <th>Notification Date</th>
    
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'koneksi.php';
                $result = $conn->query("SELECT * FROM Notifications");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['NotificationID'] . "</td>";
                        echo "<td>" . $row['CustomerID'] . "</td>";
                        echo "<td>" . $row['Message'] . "</td>";
                        echo "<td>" . $row['Description'] . "</td>";
                        echo "<td>" . $row['NotificationDate'] . "</td>";
                        echo "<td>";
                        echo '<a href="#" onclick="openEditModal(' . $row['NotificationID'] . ')"><i class="fas fa-edit"></i></a>';
                        echo '<a href="#" onclick="confirmDelete(' . $row['NotificationID'] . ')"><i class="fas fa-trash-alt"></i></a>';

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No notifications found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
        <button class="add-btn" onclick="showModal('add')">Send message</button>
    </div>
    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Add Notification</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="notification-form" method="POST" action="notification.php">
                    <input type="hidden" id="action" name="action" value="add">
                    <input type="hidden" id="NotificationID" name="NotificationID">
                    <label for="CustomerID">Customer ID:</label>
                    <input type="number" id="CustomerID" name="CustomerID" required>
                    <label for="Message">Message:</label>
                    <textarea id="Message" name="Message" required></textarea>
                    <label for="Description">Description:</label>
                    <textarea id="Description" name="Description" required></textarea>
                   
                    <div class="modal-footer">
                        <button type="button" onclick="closeModal()">Cancel</button>
                        <button type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function showModal(action, id = null) {
            document.getElementById('modal').style.display = 'flex';
            document.getElementById('action').value = action;
            document.getElementById('modal-title').textContent = action === 'add' ? 'Add Notification' : 'Edit Notification';

            if (action === 'edit' && id) {
                fetchNotification(id);
            } else {
                document.getElementById('NotificationID').value = '';
                document.getElementById('CustomerID').value = '';
                document.getElementById('Message').value = '';
                document.getElementById('Description').value = '';
               
            }
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function editNotification(id) {
            showModal('edit', id);
        }

        function deleteNotification(id) {
            if (confirm('Are you sure you want to delete this notification?')) {
                window.location.href = `notification.php?action=delete&id=${id}`;
            }
        }

        function fetchNotification(id) {
            fetch(`notification.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('NotificationID').value = data.NotificationID;
                    document.getElementById('CustomerID').value = data.CustomerID;
                    document.getElementById('Message').value = data.Message;
                    document.getElementById('Description').value = data.Description;
                    
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
