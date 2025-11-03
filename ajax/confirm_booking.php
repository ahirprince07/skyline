<?php

    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');
    require("../inc/sendgrid/sendgrid-php.php");

    date_default_timezone_set('Asia/Kolkata');

    if(isset($_POST['check_availability']))
    {
        $frm_data = filteration($_POST);
        $status = "";
        $result = "";

        //check in and out validation

        $today_date = new DateTime(date('Y-m-d'));
        $checkin_date = new DateTime($frm_data['check_in']);
        $checkout_date = new DateTime($frm_data['check_out']);

        if($checkin_date == $checkout_date)
        {
            $status = 'check_in_out_equal';
            $result = json_encode(['status'=>$status]);
        }
        else if($checkout_date < $checkin_date)
        {
            $status = 'check_out_earlier';
            $result = json_encode(['status'=>$status]);
        }
        else if($checkin_date < $today_date)
        {
            $status = 'check_in_earlier';
            $result = json_encode(['status'=>$status]);
        }

        // check booking availability if status is blank else return the error
        if($status!='')
        {
            echo $result;
        }
        else
        {
            session_start();

            $room_id = $frm_data['room_id'];

            // Get room quantity
            $room_q = select("SELECT quantity FROM rooms WHERE id=?", [$room_id], 'i');
            $room_quantity = mysqli_fetch_assoc($room_q)['quantity'];

            // Check if all rooms of this type are already booked on the requested dates
            $overlap_count_q = select("SELECT COUNT(*) as count FROM bookings WHERE room_id=? AND status='confirmed' AND checkin < ? AND checkout > ?", [$room_id, $checkout_date->format('Y-m-d'), $checkin_date->format('Y-m-d')], 'iss');
            $overlap_count = mysqli_fetch_assoc($overlap_count_q)['count'];

            if($overlap_count >= $room_quantity)
            {
                $status = 'unavailable';
                $result = json_encode(['status'=>$status]);
                echo $result;
            }
            else
            {
                // Check user's last booking check-out
                $user_last_checkout_q = select("SELECT MAX(checkout) as last_checkout FROM bookings WHERE user_id=? AND status='confirmed'", [$_SESSION['uId']], 'i');
                $user_last_checkout_res = mysqli_fetch_assoc($user_last_checkout_q);

                if($user_last_checkout_res['last_checkout'] && $checkin_date <= new DateTime($user_last_checkout_res['last_checkout']))
                {
                    $status = 'unavailable';
                    $result = json_encode(['status'=>$status]);
                    echo $result;
                }
                else
                {
                    $_SESSION['room'];

                    // rub query check to check room is available or not

                    $count_days = date_diff($checkin_date,$checkout_date)->days;
                    $payment = $_SESSION['room']['price'] * $count_days;

                    $_SESSION['room']['payment'] = $payment;
                    $_SESSION['room']['available'] = true;

                    $result = json_encode(["status"=>'available',"days"=>$count_days,"payment"=>$payment]);
                    echo $result;
                }
            }
        }
    }

?>