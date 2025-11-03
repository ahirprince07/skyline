<?php

    session_start();
    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');

    if(isset($_POST['clear_cancelled']))
    {
        // Delete all cancelled bookings for the current user
        $delete_query = "DELETE FROM `bookings` WHERE `status`=? AND `user_id`=?";
        $delete_res = update($delete_query, ['cancelled', $_SESSION['uId']], 'si');

        if($delete_res)
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
