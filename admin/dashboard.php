<?php
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();

    // Queries for booking analytics
    $total_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM bookings"))['count'];
    $total_rupees = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(amount) AS sum FROM bookings WHERE status='confirmed'"))['sum'] ?? 0;
    $cancelled_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM bookings WHERE status='cancelled'"))['count'];
    $cancelled_rupees = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(amount) AS sum FROM bookings WHERE status='cancelled'"))['sum'] ?? 0;

    // Queries for user queries analytics
    $total_queries = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM user_queries"))['count'];
    $unread_queries = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM user_queries WHERE seen=0"))['count'];

    // Queries for review analytics
    $total_reviews = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM user_reviews"))['count'];
    $avg_rating = mysqli_fetch_assoc(mysqli_query($con, "SELECT AVG(rating) AS avg FROM user_reviews"))['avg'] ?? 0;

    // Queries for user analytics
    $total_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM user_cred"))['count'];
    $active_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM user_cred WHERE status=1"))['count'];
    $inactive_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM user_cred WHERE status=0"))['count'];
    $unverified_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM user_cred WHERE is_verified=0"))['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid " id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h4>Booking Analytics</h4>

                <div class="row mt-4">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Total Bookings</h6>
                            <h1><?php echo $total_bookings; ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Total Rupees</h6>
                            <h1>₹<?php echo number_format($total_rupees); ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-warning p-3">
                            <h6>Cancelled Bookings</h6>
                            <h1><?php echo $cancelled_bookings; ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-danger p-3">
                            <h6>Cancelled Rupees</h6>
                            <h1>₹<?php echo number_format($cancelled_rupees); ?></h1>
                        </div>
                    </div>
                </div>

                <h4>User Analytics</h4>

                <div class="row mt-4">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Total Users</h6>
                            <h1><?php echo $total_users; ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Active Users</h6>
                            <h1><?php echo $active_users; ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-warning p-3">
                            <h6>Inactive Users</h6>
                            <h1><?php echo $inactive_users; ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-danger p-3">
                            <h6>Unverified Users</h6>
                            <h1><?php echo $unverified_users; ?></h1>
                        </div>
                    </div>
                </div>

                <h4>Queries Analytics</h4>

                <div class="row mt-4">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-info p-3">
                            <h6>Total Queries</h6>
                            <h1><?php echo $total_queries; ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-secondary p-3">
                            <h6>Unread Queries</h6>
                            <h1><?php echo $unread_queries; ?></h1>
                        </div>
                    </div>
                </div>

                <h4>Review Analytics</h4>

                <div class="row mt-4">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-warning p-3">
                            <h6>Total Reviews</h6>
                            <h1><?php echo $total_reviews; ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Average Rating</h6>
                            <h1><?php echo number_format($avg_rating, 1); ?> <i class="bi bi-star-fill"></i></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
</body>
</html>