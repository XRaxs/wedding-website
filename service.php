<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f6f6f6;
            padding-top: 70px;
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

        .topbar .logo {
            font-size: 1.5em;
            margin-right: 20px;
            color: #AFC2AE;
            font-weight: bold;
        }

        .topbar .profile {
            color: #AFC2AE;
        }

        .notification-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .notification {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .notification h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .notification p {
            margin: 5px 0 0 0;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="topbar">
        <div class="logo">WOHO</div>
        <div class="profile">Hi, User</div>
    </div>

    <div class="notification-container">
        <!-- Notifications will be inserted here by PHP -->
        <?php
        // Koneksi ke database
        include 'koneksi.php';

        // Query untuk mendapatkan data notifikasi/pesan
        $result = $conn->query("SELECT * FROM Notifications WHERE NotificationID = 'NotificationID'");
        while ($row = $result->fetch_assoc()) :
        ?>
            <div class="notification">
                <h3><?php echo $row['Title']; ?></h3>
                <p><?php echo $row['Message']; ?></p>
                <small><?php echo $row['DateSent']; ?></small>
            </div>
        <?php endwhile; ?>
    </div>
</body>

</html>

