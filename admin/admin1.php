<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Add your existing CSS here */

        /* Dropdown CSS */
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
            <li><a href="admin1.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
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
            <li><a href="log_riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
        </ul>
    </div>
        <div class="main-content">
    <div class="column">
        <div class="info-card small" data-view="CustomerDetails">
            <i class="fas fa-user"></i>
            <h3>Customer Details</h3>
            <p id="customerCount"></p>
        </div>
        <div class="info-card small" data-view="OrderDetails">
            <i class="fas fa-shopping-cart"></i>
            <h3>Order Details</h3>
            <p id="orderCount"></p>
        </div>
        <div class="info-card small" >
            <i class="fas fa-money-bill-wave"></i>
            <h3>Income Details</h3>
            <div id="totalPayment"></div>
        </div>
 

               
            </div>
            <div class="column info-cardd-container">
                <div class="columnn">
                    <h3>Details</h3>
                    <div class="info-cardd medium" data-view="CustomerEventDetails">
                        <p>Customer Event Details</p>
                        <table id="CustomerEventDetailsTable" class="details-table">
                            <!-- Isi tabel -->
                        </table>
                    </div>
                    <div class="info-cardd medium" data-view="EventPackageDetails">
                        <p>Nearets Events</p>
                        <table id="EventPackageDetailsTable" class="details-table">
                            <!-- Isi tabel -->
                        </table>
                    </div>
                    <div class="info-cardd medium" data-view="VendorDetails">
                        <p>CustomerOrderCount</p>
                        <table id="VendorDetailsTable" class="details-table">
                            <!-- Isi tabel -->
                        </table>
                    </div>
                    <div class="info-cardd medium" data-view="AnalysisReports">
                        <p>Analysis Reports</p>
                        <table id="AnalysisReportsTable" class="details-table">
                            <!-- Isi tabel -->
                        </table>
                    </div>
                    <div class="info-cardd medium" data-view="UnpaidEvents">
                        <p>Unpaid Events</p>
                        <table id="UnpaidEventsTable" class="details-table">
                            <!-- Isi tabel -->
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="modalContent"></div>
            </div>
        </div>

        <script>
function showDetailView(view) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'admin.php?view=' + view, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    var table = '<table>';
                    if (data.length > 0) {
                        table += '<thead><tr>';
                        for (var key in data[0]) {
                            table += '<th>' + key + '</th>';
                        }
                        table += '</tr></thead><tbody>';
                        data.forEach(function(rowData) {
                            table += '<tr>';
                            for (var key in rowData) {
                                table += '<td>' + rowData[key] + '</td>';
                            }
                            table += '</tr>';
                        });
                        table += '</tbody>';
                    } else {
                        table += '<tr><td colspan="100%" class="no-data">No data available</td></tr>';
                    }
                    table += '</table>';
                    document.getElementById('modalContent').innerHTML = table;
                    showModal();
                }
            };
            xhr.send();
        }

        function showModal() {
           
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
        }

        document.getElementsByClassName("close")[0].onclick = function() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }

        document.querySelectorAll('.info-card.small, .info-cardd.medium').forEach(function(card) {
            card.addEventListener('click', function() {
                var view = card.getAttribute('data-view');
                showDetailView(view);
            });
        });

        // Function to fetch and update counts
        function updateCounts() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'getCounts.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    document.getElementById('customerCount').textContent = 'Total Customers: ' + data.totalCustomers;
                    document.getElementById('orderCount').textContent = 'Total Orders: ' + data.totalOrders;
                }
            };
            xhr.send();
        }

        // Function to fetch and update total payment
        function updateTotalPayment() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'getTotalPayment.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    document.getElementById('totalPayment').textContent = 'Total Payment: ' + data.totalPayment;
                }
            };
            xhr.send();
        }

        // Call updateCounts and updateTotalPayment on page load
        window.onload = function() {
            updateCounts();
            updateTotalPayment();
        };

</script>

</body>

</html>