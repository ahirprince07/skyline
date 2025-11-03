<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - BOOKINGS</title>
</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container">
        <div class="row">

            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">MY BOOKINGS</h2>
                <div style="font-size:14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary">></span>
                    <a href="bookings.php" class="text-secondary text-decoration-none">BOOKINGS</a>
                </div>
                <button type="button" onclick="clear_cancelled()" class="btn btn-outline-danger btn-sm shadow-none mt-2">Clear Cancelled</button>
            </div>

            <?php
                $query = "SELECT b.*, r.name as room_name, r.price FROM `bookings` b
                    INNER JOIN `rooms` r ON b.room_id = r.id
                    WHERE b.user_id = ?
                    ORDER BY b.booking_date DESC";

                $result = select($query,[$_SESSION['uId']],'i');

                while($data = mysqli_fetch_assoc($result))
                {
                    $date = date("d-m-Y",strtotime($data['booking_date']));
                    $checkin = date("d-m-Y",strtotime($data['checkin']));
                    $checkout = date("d-m-Y",strtotime($data['checkout']));

                    $status_bg = "";
                    $btn = "";

                    if($data['status']=='confirmed')
                    {
                        $status_bg = "bg-success";
                        $btn="<a href='bill.php?booking_id=$data[id]' class='btn btn-dark btn-sm shadow-none'>View Bill</a>";
                        $btn.="<button type='button' onclick='review_site($data[id],$_SESSION[uId])' data-bs-toggle='modal' data-bs-target='#reviewModal' class='btn btn-dark btn-sm shadow-none ms-2'>Rate & Review</button>";
                        $btn.="<button type='button' onclick='cancel_booking($data[id])' class='btn btn-danger btn-sm shadow-none ms-2'>Cancel</button>";
                    }
                    else if($data['status']=='cancelled')
                    {
                        $status_bg = "bg-danger";
                        $btn="<span class='badge bg-primary'>Cancelled</span>";
                    }
                    else
                    {
                        $status_bg = "bg-warning";
                        $btn="<span class='badge bg-warning'>Pending</span>";
                    }

                    echo<<<bookings
                        <div class='col-md-4 px-4 mb-4'>
                            <div class="bg-white p-3 rounded shadow-sm">
                                <h5 class="fw-bold">$data[room_name]</h5>
                                <p>₹$data[price] per night</p>
                                <p>
                                    <b>Check in: </b>$checkin<br>
                                    <b>Check out: </b>$checkout
                                </p>
                                <p>
                                    <b>Amount: </b>₹$data[amount]<br>
                                    <b>Payment: </b>$data[payment_method]<br>
                                    <b>Date: </b>$date
                                </p>
                                <p>
                                    <span class="badge $status_bg">$data[status]</span>
                                </p>
                                $btn
                            </div>
                        </div>
                    bookings;
                }
            ?>

        </div>
    </div>

    <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="review-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-chat-square-heart-fill fs-3 me-2"></i>Rate & Review
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select class="form-select shadow-none" name="rating">
                                <option value="5">Excellent</option>
                                <option value="4">Good</option>
                                <option value="3">Ok</option>
                                <option value="2">Poor</option>
                                <option value="1">Bad</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Review</label>
                            <textarea name="review" rows="3" class="form-control shadow-none" required></textarea>
                        </div>
                        <input type="hidden" name="booking_id">
                        <input type="hidden" name="user_id">
                        <div class="text-end">
                            <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script>
        function cancel_booking(id)
        {
            if(confirm('Are you sure to cancel this booking?'))
            {
                let xhr = new XMLHttpRequest();
                xhr.open("POST","ajax/cancel_booking.php",true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function(){
                    if(this.responseText==1){
                        window.location.href="bookings.php?cancel_status=true";
                    }
                    else{
                        alert('error','Cancellation Failed!');
                    }
                }

                xhr.send('cancel_booking='+id);
            }
        }

        function clear_cancelled()
        {
            if(confirm('Are you sure to clear all cancelled bookings?'))
            {
                let xhr = new XMLHttpRequest();
                xhr.open("POST","ajax/clear_cancelled.php",true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function(){
                    if(this.responseText==1){
                        window.location.href="bookings.php?clear_status=true";
                    }
                    else{
                        alert('error','Clear Cancelled Failed!');
                    }
                }

                xhr.send('clear_cancelled=1');
            }
        }

        function review_site(bid,uid){
            document.querySelector('#review-form input[name="booking_id"]').value = bid;
            document.querySelector('#review-form input[name="user_id"]').value = uid;
        }

        let review_form = document.getElementById('review-form');
        review_form.addEventListener('submit',function(e){
            e.preventDefault();

            let data = new FormData();

            data.append('review_site','');
            data.append('rating',review_form.elements['rating'].value);
            data.append('review',review_form.elements['review'].value);
            data.append('booking_id',review_form.elements['booking_id'].value);
            data.append('user_id',review_form.elements['user_id'].value);

            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/review_site.php",true);

            xhr.onload = function()
            {
                if(this.responseText == 1)
                {
                    window.location.href = 'bookings.php?review_status=true';
                }
                else{
                    alert('error','Rating & Review Failed!');
                }
            }

            xhr.send(data);
        });
    </script>

</body>
</html>
