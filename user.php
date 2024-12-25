<?php
include "koneksi.php";
$uploadDirectory = "admin/uploud/";
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}

$user = $_SESSION['login'];

$packages = [];
$sql = "SELECT * FROM Packages";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $packages[] = $row;
  }
} else {
  echo "No packages available.";
}

$notifications = [];
$sql = "SELECT * FROM Notifications WHERE CustomerID = ? ORDER BY NotificationDate DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user['CustomerID']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
  }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wedding Organizer</title>
  <link rel="stylesheet" href="user.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <nav class="navbar">
    <div class="container">
      <span class="brand">WOHO</span>
      <ul class="nav-links">
      <ul class="nav-links">
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
        <div class="notification-icon"></div>
<div id="notification-container" class="hidden">
    <div id="notification-header">
        <button id="close-notification">X</button>
    </div>
          <div class="notification-content">
            <?php if (!empty($notifications)) : ?>
              <?php foreach ($notifications as $notification) : ?>
                <div class="notification-item" data-notificationid="<?php echo htmlspecialchars($notification['NotificationID']); ?>">
                  <div class="notification-content">
                    <p><?php echo htmlspecialchars($notification['Message']); ?></p>
                    <?php if (isset($notification['Description']) && $notification['Description'] == 'Pembayaran Diterima') : ?>
                      <form class="feedback-form" style="display: none;">
                        <input type="hidden" name="notificationID" value="<?php echo htmlspecialchars($notification['NotificationID']); ?>">
                        <input type="number" name="feedbackScore" min="1" max="5" placeholder="Masukkan rating (1-5)" required>
                        <button type="submit" class="submit-feedback">Beri Rating</button>
                      </form>
                    <?php endif; ?>
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

  <header class="hero">
    <div class="containerr">
        <div class="hero-text">
            <p class="top-text">Your Perfect Wedding Awaits</p>
            <div class="hero-line"></div>
            <p class="bottom-text">
            Crafting Dreams into Reality</p>
        </div>
        <div class="hero-image">
            <img src="admin/baru.jpg" alt="Wedding Image">
        </div>
    </div>
</header>


  <section class="gallery">
    <div class="containergal">
      <p class="h12">Gallery</p>
      <p class="description-text">Step into our gallery and witness the beauty of our cherished moments captured in time</p>
      <div class="gallery-grid">
        <div class="gallery-item item1 "><img src="admin/item1.jpg" alt="Gallery Image 1"></div>
        <div class="gallery-item item2 "><img src="admin/item2.png" alt="Gallery Image 2"></div>
        <div class="gallery-item item3 "><img src="admin/item3.jpg" alt="Gallery Image 3"></div>
        <div class="gallery-item item4 "><img src="admin/item4.jpg" alt="Gallery Image 4"></div>
      </div>
    </div>
  </section>

  <section class="packages">
    <p class="h12">Wedding Packages</p>
    <p class="description-text">Explore our exclusive wedding packages designed to make your special day unforgettable. Choose the perfect package that suits your style and budget, and let us take care of the rest. Your dream wedding awaits!</p>
    <div class="package-container">
      <?php if (!empty($packages)) : ?>
        <div class="package-container">
        <?php foreach ($packages as $package) : ?>
          <div class="package-card" data-packageid="<?php echo htmlspecialchars($package['PackageID']); ?>" data-image="<?php echo htmlspecialchars($package['image']); ?>" data-package="<?php echo htmlspecialchars($package['PackageName']); ?>" data-price="<?php echo number_format($package['Price'], 0, ',', '.'); ?>">
              <!-- Display package image -->
              <img src="admin/<?php echo htmlspecialchars($package['image']); ?>" alt="Package Image" class="packimg">
              <!-- Other package details -->
              <p class="namapaket"><?php echo htmlspecialchars($package['PackageName']); ?></p>
              <p class="price"><?php echo number_format((float)$package['Price'], 0, ',', '.'); ?></p>
              <a href="#booking" class="btn order-btn">Order now</a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else : ?>
        <p>No packages available at the moment.</p>
      <?php endif; ?>
    </div>
  </section>

  <section id="booking" class="booking">
        <p class="h12">Checkout</p>
        <p class="description-text">Proceed to Checkout to Complete Your Order</p>
        <div class="container">
            <div class="checkout-info"></div>
            <form id="order-form">
                <div class="form-row">
                    <div class="form-column">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-column">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-column">
                        <label for="Phone">Phone:</label>
                        <input type="text" id="Phone" name="Phone" required>
                    </div>
                    <div class="form-column">
                        <label for="Location">Location:</label>
                        <input type="text" id="Location" name="Location" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-column">
                        <label for="package-name">Package:</label>
                        <input type="text" id="package-name" name="package" required readonly>
                    </div>
                    <div class="form-column">
                        <label for="price">Price:</label>
                        <input type="text" id="price" name="price" required readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-column">
                        <label for="payment-method">Payment Method:</label>
                        <select id="payment-method" name="payment-method" required>
                            <option value="credit-card">Credit Card</option>
                            <option value="bank-transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="form-column">
                        <label for="date">Wedding Date:</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-column full-width">
                        <label for="vendor">Vendor:</label>
                        <input type="text" id="vendor" name="vendor" readonly>
                    </div>
                </div>
                <button type="submit">Checkout</button>
            </form>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="social-icons">
                    <a><i class="fab fa-instagram"></i> woho.offi</a>

                    <a><i class="fab fa-whatsapp"></i> chat me☺️</a>

                    <a><i class="far fa-envelope"></i>wohoweddingorganizer@gmail.com</a>

                    <a><i class="fas fa-globe"></i>www.wohoweddingorganizer</a>
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
    <script>
    document.querySelectorAll('.order-btn').forEach(button => {
      button.addEventListener('click', function (event) {
        event.preventDefault();
        const packageCard = this.closest('.package-card');
        const packageID = packageCard.getAttribute('data-packageid');
        const packageName = packageCard.getAttribute('data-package');
        const price = packageCard.getAttribute('data-price');

        document.getElementById('package-name').value = packageName;
        document.getElementById('price').value = price;

        fetch('get_vendors.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `packageID=${packageID}`
        })
        .then(response => response.json())
        .then(data => {
          document.getElementById('vendor').value = data.vendorNames;
        })
        .catch(error => console.error('Error:', error));

        document.getElementById('booking').scrollIntoView({
          behavior: 'smooth'
        });
      });
    });

    document.getElementById('order-form').addEventListener('submit', function(event) {
      event.preventDefault();

      const formData = new FormData(this);

      fetch('submit_order.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        alert('Order placed successfully!');
        window.location.href = 'user.php';
      })
      .catch(error => console.error('Error:', error));
    });

    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function() {
          const feedbackForm = this.querySelector('.feedback-form');
          if (feedbackForm) {
            feedbackForm.style.display = 'block';
          }
        });
      });

      document.querySelectorAll('.submit-feedback').forEach(button => {
        button.addEventListener('click', function(event) {
          event.preventDefault();
          const feedbackForm = this.closest('.feedback-form');
          const notificationID = feedbackForm.querySelector('input[name="notificationID"]').value;
          const feedbackScore = feedbackForm.querySelector('input[name="feedbackScore"]').value;

          fetch('process_feedback.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `notificationID=${notificationID}&feedbackScore=${feedbackScore}`
          })
          .then(response => response.text())
          .then(data => {
            alert('Thank you for your feedback!');
            feedbackForm.style.display = 'none';
          })
          .catch(error => console.error('Error:', error));
        });
      });
    });
  </script>
</body>
</html>