<?php
include "koneksi.php";
$uploadDirectory = "admin/uploud/";
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}

$user = $_SESSION['login'];

$vendors = [];
$sql = "SELECT * FROM Vendors";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $vendors[] = $row;
  }
} else {
  echo "No vendors available.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wedding Organizer - Vendors</title>
  <link rel="stylesheet" href="user.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    
    .vendor-container {
      margin-top: 20px;
      margin-left: 50px;
      margin-right: 50px;
      padding: 20px;
      text-align: center;
      margin-bottom: 50px;
    }

    .vendor-list {
      margin-left: 70px;
      margin-right: 70px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .vendor-card {
      padding: 10px;
      display: flex;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
      align-items: center;
    }

    .vendor-card:hover {
      transform: translateY(-10px);
    }

    .vendor-img {
      width: 200px;
      height: 200px;
      object-fit: cover;
      flex-shrink: 0;
    }

    .vendor-info {
      padding: 20px;
      text-align: left;
    }

    .vendor-name {
      font-size: 22px;
      margin: 10px 0;
      color:#8d8b8b ;
      font-family: 'Poppins', sans-serif;
    }

    .vendor-category {
      color: #777;
      margin-bottom: 10px;
    }

    .vendor-description {
      font-size: 14px;
      color: #555;
      margin-bottom: 15px;
    }

    .view-details-btn {
      display: inline-block;
      padding: 10px 20px;
      background: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      transition: background 0.3s, color 0.3s;
    }

    .view-details-btn:hover,
    .view-details-btn:focus,
    .view-details-btn:active {
      background: #0056b3;
      text-decoration: underline;
      color: #fff;
    }

  </style>
</head>

<body>
  <nav class="navbar">
    <div class="container">
      <span class="brand">WOHO </span>
      <ul class="nav-links">
        <<ul class="nav-links">
  <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'user.php') echo 'class="active"'; ?> href="user.php">Home</a></li>
  <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'about.php') echo 'class="active"'; ?> href="#">About</a></li>
  <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'services.php') echo 'class="active"'; ?> href="#">Services</a></li>
  <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'uservendor.php') echo 'class="active"'; ?> href="uservendor.php">Vendor</a></li>
  <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'contact.php') echo 'class="active"'; ?> href="#">Contact</a></li>
</ul>

      </ul>
      <div class="search-container">
        <input type="text" placeholder="Search...">
        <i class="fas fa-search"></i>
      </div>
      <div class="notification-icon">
        <i class="fas fa-bell"></i>
        <div id="notification-container" class="notification-container">
          <div class="notification-header">
            <h4>Notifications</h4>
            <button class="close-btn" id="close-notification">&times;</button>
          </div>
          <div class="notification-content">
            <?php include 'tabnotifications.php'; ?>
            <?php if (!empty($notifications)) : ?>
              <?php foreach ($notifications as $notification) : ?>
                <div class="notification-item" onclick="toggleDescription(this)">
                  <div class="notification-content">
                    <tr>
                      <td><?php echo htmlspecialchars($notification['Message']); ?></td>
                    </tr>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else : ?>
              <p>No notifications available.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="profile-icon">
        <span>Hi, <?php echo htmlspecialchars($user['Name']); ?></span>
      </div>
    </div>
  </nav>

 

  <section class="vendors">
    <div class="containervendor">
      <br>
      <br>
      <br>
      <p class="h12">Our Trusted Vendors</p>
      <p class="description-text">Explore our list of trusted vendors who can help make your wedding day unforgettable. From florists to photographers, we have everything you need.</p>
      <br>
      <div class="vendor-list">
        <?php if (!empty($vendors)) : ?>
          <?php foreach ($vendors as $vendor) : ?>
            <div class="vendor-card">
              <img src="admin/<?php echo htmlspecialchars($vendor['image']); ?>" alt="Vendor Image" class="vendor-img">
              <div class="vendor-info">
                <h3 class="vendor-name"><?php echo htmlspecialchars($vendor['VendorName']); ?></h3>
                <p class="vendor-description"><?php echo htmlspecialchars($vendor['Description']); ?></p>
                
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <p>No vendors available at the moment.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <br>
    <br>
  <footer>
  
  <div class="container">
    <div class="footer-content">
      <div class="social-icons">
        <a ><i class="fab fa-instagram"></i> woho.offi</a>
        
        <a ><i class="fab fa-whatsapp"></i> chat me☺️</a>
        
        <a ><i class="far fa-envelope"></i>wohoweddingorganizer@gmail.com</a>
        
        <a ><i class="fas fa-globe"></i>www.wohoweddingorganizer</a>
      </div>
      <div class="logo">
        WOHO
      </div>
    </div>
    <div class="copyright">
      <p>&copy; 2024 Wedding Organizer. All rights reserved.</p>
    </div>
  </div>
</footer>



  <script src="user.js"></script>
</body>

</html>
