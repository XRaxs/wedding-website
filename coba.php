<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0a0b1a;
            color: #fff;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto auto auto auto;
            gap: 20px;
            width: 100%;
            max-width: 1200px;
        }
        .info-card {
            background-color: #1e1f31;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #fff;
        }
        .info-card h3 {
            margin: 0 0 10px 0;
        }
        .info-card.split {
            grid-column: span 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .details-table th, .details-table td {
            padding: 10px;
            border: 1px solid #444;
        }
        .status {
            color: #4caf50;
        }
        .manage {
            background-color: #0a74da;
            border: none;
            border-radius: 5px;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
        }
        .manage:hover {
            background-color: #0a66c2;
        }
        .progress-bar {
            height: 20px;
            width: 100%;
            background-color: #333;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 10px;
        }
        .progress {
            height: 100%;
            width: 83%;
            background-color: #4caf50;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="info-card">
            <h3>Order Details</h3>
            <!-- Add Order Details content here -->
        </div>
        <div class="info-card">
            <h3>Customer Details</h3>
            <!-- Add Customer Details content here -->
        </div>
        <div class="info-card split">
            <div>
                <h3>Expense Details</h3>
                <!-- Add Expense Details content here -->
            </div>
            <div>
                <h3>Income Details</h3>
                <!-- Add Income Details content here -->
            </div>
        </div>
        <div class="info-card">
            <h3>Customer Event Details</h3>
            <table class="details-table">
                <!-- Add Customer Event Details content here -->
            </table>
        </div>
        <div class="info-card">
            <h3>Event Package Details</h3>
            <table class="details-table">
                <!-- Add Event Package Details content here -->
            </table>
        </div>
        <div class="info-card">
            <h3>Vendor Details</h3>
            <table class="details-table">
                <!-- Add Vendor Details content here -->
            </table>
        </div>
        <div class="info-card">
            <h3>Analysis Reports</h3>
            <table class="details-table">
                <!-- Add Analysis Reports content here -->
            </table>
        </div>
        <div class="info-card">
            <h3>Unpaid Events</h3>
            <table class="details-table">
                <!-- Add Unpaid Events content here -->
            </table>
        </div>
    </div>
</body>
</html>
