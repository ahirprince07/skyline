<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title>THE SKYLINE - BILL</title>
    <style>
        .bill-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            background: #fff;
        }
        .bill-header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .bill-title {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
        }
        .bill-section {
            margin-bottom: 20px;
        }
        .bill-section h5 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .bill-table {
            width: 100%;
            border-collapse: collapse;
        }
        .bill-table th, .bill-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .bill-table th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
            background-color: #e9ecef;
        }
        @media print {
            body * { visibility: hidden; }
            .bill-container, .bill-container * { visibility: visible; }
            .bill-container { position: absolute; left: 0; top: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-light">

    <?php
        if(!isset($_GET['booking_id']) || !is_numeric($_GET['booking_id'])){
            redirect('rooms.php');
        }

        $booking_id = $_GET['booking_id'];

        // Fetch booking details with joins
        $query = "SELECT b.*, u.name, u.phonenum, u.address, r.name as room_name, r.price
                  FROM bookings b
                  JOIN user_cred u ON b.user_id = u.id
                  JOIN rooms r ON b.room_id = r.id
                  WHERE b.id = ?";

        $res = select($query, [$booking_id], 'i');

        if(mysqli_num_rows($res) == 0){
            echo "<script>alert('Booking not found!'); window.close();</script>";
            exit;
        }

        $booking_data = mysqli_fetch_assoc($res);

        // Calculate number of days
        $checkin = new DateTime($booking_data['checkin']);
        $checkout = new DateTime($booking_data['checkout']);
        $days = $checkin->diff($checkout)->days;
    ?>

    <div class="container mt-5">
        <div class="bill-container">
            <div class="bill-header">
                <h1 class="bill-title">THE SKYLINE</h1>
                <p>Hotel Booking Invoice</p>
                <p>Booking ID: <?php echo $booking_data['id']; ?></p>
                <p>Booking Date: <?php echo date('d-m-Y H:i', strtotime($booking_data['booking_date'])); ?></p>
            </div>

            <div class="bill-section">
                <h5>Customer Details</h5>
                <table class="bill-table">
                    <tr>
                        <th>Name</th>
                        <td><?php echo $booking_data['name']; ?></td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td><?php echo $booking_data['phonenum']; ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $booking_data['address']; ?></td>
                    </tr>
                </table>
            </div>

            <div class="bill-section">
                <h5>Booking Details</h5>
                <table class="bill-table">
                    <tr>
                        <th>Room</th>
                        <td><?php echo $booking_data['room_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Check-in Date</th>
                        <td><?php echo date('d-m-Y', strtotime($booking_data['checkin'])); ?></td>
                    </tr>
                    <tr>
                        <th>Check-out Date</th>
                        <td><?php echo date('d-m-Y', strtotime($booking_data['checkout'])); ?></td>
                    </tr>
                    <tr>
                        <th>Number of Days</th>
                        <td><?php echo $days; ?></td>
                    </tr>
                    <tr>
                        <th>Room Price per Night</th>
                        <td>₹<?php echo $booking_data['price']; ?></td>
                    </tr>
                    <tr class="total-row">
                        <th>Total Amount</th>
                        <td>₹<?php echo $booking_data['amount']; ?></td>
                    </tr>
                </table>
            </div>

            <div class="bill-section">
                <h5>Payment Details</h5>
                <table class="bill-table">
                    <tr>
                        <th>Payment Method</th>
                        <td><?php echo ucfirst(str_replace('_', ' ', $booking_data['payment_method'])); ?></td>
                    </tr>
                    <tr>
                        <th>Payment Status</th>
                        <td><?php echo ucfirst($booking_data['status']); ?></td>
                    </tr>
                    <tr>
                        <th>Amount Paid</th>
                        <td>₹<?php echo $booking_data['amount']; ?></td>
                    </tr>
                </table>
            </div>

            <div class="text-center mt-4 no-print">
                <button onclick="window.print()" class="btn btn-primary me-2">Print Bill</button>
                <button onclick="window.close()" class="btn btn-secondary">Close</button>
            </div>

            <div class="text-center mt-3">
                <p>Thank you for choosing THE SKYLINE!</p>
                <p>For any queries, please contact us.</p>
            </div>
        </div>
    </div>

</body>
</html>
