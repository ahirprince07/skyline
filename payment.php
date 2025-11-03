<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - PAYMENT</title>
</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <?php
        if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
            redirect('rooms.php');
        }

        if(!isset($_GET['checkin']) || !isset($_SESSION['room']['available']) || $_SESSION['room']['available']!=true || !isset($_SESSION['booking'])){
            redirect('rooms.php');
        }

        $user_id = $_SESSION['uId'];
        $room_id = $_SESSION['room']['id'];
        $checkin = $_GET['checkin'];
        $checkout = $_GET['checkout'];
        $amount = $_SESSION['room']['payment'];
        $name = $_SESSION['booking']['name'];
        $phonenum = $_SESSION['booking']['phonenum'];
        $address = $_SESSION['booking']['address'];

if(isset($_POST['pay'])){
            $payment_method = $_POST['payment_method'];
            // Insert booking
            $query = "INSERT INTO `bookings` (user_id, room_id, checkin, checkout, amount, payment_method, status, booking_date) VALUES (?, ?, ?, ?, ?, ?, 'confirmed', NOW())";
            $values = [$user_id, $room_id, $checkin, $checkout, $amount, $payment_method];
            $datatypes = 'iissds';
            $res = insert($query, $values, $datatypes);
            if($res > 0){
                // Success
                $booking_id = mysqli_insert_id($con);
                echo "<script>
                    alert('Payment successful! Booking confirmed.');
                    window.open('bill.php?booking_id=$booking_id', '_blank');
                </script>";
            } else {
                echo "<script>alert('Payment failed! Please try again.');</script>";
            }
        }
    ?>

    <div class="container">
        <div class="row">

            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">PAYMENT</h2>
                <div style="font-size:14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary">></span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">ROOMS</a>
                    <span class="text-secondary">></span>
                    <a href="confirm_booking.php?id=<?php echo $room_id ?>" class="text-secondary text-decoration-none">CONFIRM</a>
                    <span class="text-secondary">></span>
                    <a href="#" class="text-secondary text-decoration-none">PAYMENT</a>
                </div>
            </div>

            <div class="col-lg-8 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <h6 class="mb-3">BOOKING SUMMARY</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" value="<?php echo $name ?>" class="form-control shadow-none" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="number" value="<?php echo $phonenum ?>" class="form-control shadow-none" readonly>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control shadow-none" rows="1" readonly><?php echo $address ?></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Check-in</label>
                                <input type="date" value="<?php echo $checkin ?>" class="form-control shadow-none" readonly>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Check-out</label>
                                <input type="date" value="<?php echo $checkout ?>" class="form-control shadow-none" readonly>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Total Amount</label>
                                <input type="text" value="₹<?php echo $amount ?>" class="form-control shadow-none" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <h6 class="mb-3">PAYMENT OPTIONS</h6>
                        <form method="post">
                            <input type="hidden" name="name" value="<?php echo $name ?>">
                            <input type="hidden" name="phonenum" value="<?php echo $phonenum ?>">
                            <input type="hidden" name="address" value="<?php echo $address ?>">
                            <input type="hidden" name="checkin" value="<?php echo $checkin ?>">
                            <input type="hidden" name="checkout" value="<?php echo $checkout ?>">

                            <div class="mb-3">
                                <label class="form-label">Select Payment Method</label>
                                <select name="payment_method" class="form-select shadow-none" required>
                                    <option value="">Choose...</option>
                                    <option value="phone_pay">Phone Pay</option>
                                    <option value="card">Credit/Debit Card</option>
                                    <option value="bank_account">Bank Account</option>
                                </select>
                            </div>

                            <div id="phone_pay_fields" class="payment-fields d-none">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="number" name="phone_pay_num" class="form-control shadow-none" placeholder="Enter phone number">
                                </div>
                            </div>

                            <div id="card_fields" class="payment-fields d-none">
                                <div class="mb-3">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" name="card_num" class="form-control shadow-none" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expiry Date</label>
                                        <input type="text" name="expiry" class="form-control shadow-none" placeholder="MM/YY">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">CVV</label>
                                        <input type="text" name="cvv" class="form-control shadow-none" placeholder="123">
                                    </div>
                                </div>
                            </div>

                            <div id="bank_account_fields" class="payment-fields d-none">
                                <div class="mb-3">
                                    <label class="form-label">Account Number</label>
                                    <input type="text" name="acc_num" class="form-control shadow-none" placeholder="Enter account number">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">IFSC Code</label>
                                    <input type="text" name="ifsc" class="form-control shadow-none" placeholder="Enter IFSC code">
                                </div>
                            </div>

                            <button name="pay" type="submit" class="btn w-100 text-white custom-bg shadow-none mb-1">Pay Now</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script>
        document.querySelector('select[name="payment_method"]').addEventListener('change', function() {
            document.querySelectorAll('.payment-fields').forEach(el => el.classList.add('d-none'));
            const selected = this.value;
            if(selected) {
                document.getElementById(selected + '_fields').classList.remove('d-none');
            }
        });
    </script>

</body>
</html>
