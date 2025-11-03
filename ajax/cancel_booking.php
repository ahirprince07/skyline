<?php

    session_start();
    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');

    if(isset($_POST['cancel_booking']))
    {
        $frm_data = filteration($_POST);

        // Update the booking status to cancelled
        $update_booking = "UPDATE `bookings` SET `status`=? WHERE `id`=? AND `user_id`=?";
        $update_booking_res = update($update_booking, ['cancelled', $frm_data['cancel_booking'], $_SESSION['uId']], 'sii');

        if($update_booking_res)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
    else
    {
        echo 0;
    }

?>
