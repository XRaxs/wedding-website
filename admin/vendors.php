<?php
include 'koneksi.php';

function uploadImage($imageFile) {
    $target_dir = "uploud/";
    $target_file = $target_dir . basename($imageFile["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($imageFile["tmp_name"]);
    if($check === false) {
        return null;
    }
    
    // Check file size (5MB limit)
    if ($imageFile["size"] > 5000000) {
        return null;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        return null;
    }
    
    // Try to upload file
    if (move_uploaded_file($imageFile["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        return null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        $VendorName = $_POST['VendorName'];
        $Contact = $_POST['Contact'];
        $Description = $_POST['Description'];
        $ContractDetails = $_POST['ContractDetails'];
        $imagePath = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imagePath = uploadImage($_FILES['image']);
        }

        if ($action === 'add') {
            $sql = "INSERT INTO Vendors (VendorName, Contact, Description, ContractDetails, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $VendorName, $Contact, $Description, $ContractDetails, $imagePath);
            $stmt->execute();
            $stmt->close();

        } elseif ($action === 'edit') {
            $VendorID = $_POST['VendorID'];
            if ($imagePath) {
                $sql = "UPDATE Vendors SET VendorName = ?, Contact = ?, Description = ?, ContractDetails = ?, image = ? WHERE VendorID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssi", $VendorName, $Contact, $Description, $ContractDetails, $imagePath, $VendorID);
            } else {
                $sql = "UPDATE Vendors SET VendorName = ?, Contact = ?, Description = ?, ContractDetails = ? WHERE VendorID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $VendorName, $Contact, $Description, $ContractDetails, $VendorID);
            }
            $stmt->execute();
            $stmt->close();
        }
    }

    header('Location: vendors.php');
    exit();
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $deleteQuery = "DELETE FROM Vendors WHERE VendorID = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil dihapus');</script>";
            echo "<script>window.location.href = 'vendors.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } elseif ($_GET['action'] == 'get' && isset($_GET['id'])) {
        $id = $_GET['id'];
        error_log("Fetching data for ID: $id");
        $result = $conn->query("SELECT * FROM Vendors WHERE VendorID = '$id'");
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
    <title>Vendors</title>
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
                    <li><a href="vendors.php" class="active"><i class="fas fa-store-alt"></i> Vendors</a></li>
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
        <table id="vendorTable">
            <tr>
                <th>ID</th>
                <th>Vendor Name</th>
                <th>Contact</th>
                <th>Description</th>
                <th>Contract Details</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM Vendors");
            while ($row = $result->fetch_assoc()) :
            ?>
                <tr id="row-<?php echo $row['VendorID']; ?>">
                    <td><?php echo $row['VendorID']; ?></td>
                    <td><?php echo $row['VendorName']; ?></td>
                    <td><?php echo $row['Contact']; ?></td>
                    <td><?php echo $row['Description']; ?></td>
                    <td><?php echo $row['ContractDetails']; ?></td>
                    <td>
                        <?php if ($row['image']): ?>
                            <img src="<?php echo $row['image']; ?>" alt="Vendor Image" style="width: 100px;">
                        <?php else: ?>
                            No image
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="#" onclick="openEditModal(<?php echo $row['VendorID']; ?>)"><i class="fas fa-edit"></i></a> |
                        <a href="#" onclick="confirmDelete(<?php echo $row['VendorID']; ?>)"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <button class="add-btn" onclick="openAddModal()">Add Vendor</button>
    </div>

    <!-- Add Vendor Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeAddModal()">&times;</span>
                <h2>Add Vendor</h2>
            </div>
            <div class="modal-body">
                <form id="addForm" action="vendors.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                    <label for="VendorName">Vendor Name:</label>
                    <input type="text" id="addVendorName" name="VendorName" required>
                    <label for="Contact">Contact:</label>
                    <input type="text" id="addContact" name="Contact">
                    <label for="Description">Description:</label>
                    <textarea id="addDescription" name="Description"></textarea>
                    <label for="ContractDetails">Contract Details:</label>
                    <textarea id="addContractDetails" name="ContractDetails"></textarea>
                    <label for="image">Image:</label>
                    <input type="file" id="addImage" name="image" accept="image/*">
                    <div class="modal-footer">
                        <button type="button" onclick="closeAddModal()">Cancel</button>
                        <button type="submit">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Vendor Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h2>Edit Vendor</h2>
            </div>
            <div class="modal-body">
                <form id="editForm" action="vendors.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" id="editVendorID" name="VendorID">
                    <label for="editVendorName">Vendor Name:</label>
                    <input type="text" id="editVendorName" name="VendorName" required>
                    <label for="editContact">Contact:</label>
                    <input type="text" id="editContact" name="Contact">
                    <label for="editDescription">Description:</label>
                    <textarea id="editDescription" name="Description"></textarea>
                    <label for="editContractDetails">Contract Details:</label>
                    <textarea id="editContractDetails" name="ContractDetails"></textarea>
                    <label for="editImage">Image:</label>
                    <input type="file" id="editImage" name="image" accept="image/*">
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
            document.getElementById("addModal").style.display = "flex";
        }

        function closeAddModal() {
            document.getElementById("addModal").style.display = "none";
        }

        function openEditModal(id) {
            fetch(`vendors.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("editVendorID").value = data.VendorID;
                    document.getElementById("editVendorName").value = data.VendorName;
                    document.getElementById("editContact").value = data.Contact;
                    document.getElementById("editDescription").value = data.Description;
                    document.getElementById("editContractDetails").value = data.ContractDetails;
                    document.getElementById("editModal").style.display = "flex";
                });
        }

        function closeEditModal() {
            document.getElementById("editModal").style.display = "none";
        }

        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this vendor?")) {
                window.location.href = `vendors.php?action=delete&id=${id}`;
            }
        }
    </script>
</body>
</html>
