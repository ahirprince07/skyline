<?php
    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');

    if(isset($_POST['review_site']))
    {
        $frm_data = filteration($_POST);

        $ins_q = "INSERT INTO `user_reviews` (`user_id`, `booking_id`, `rating`, `review`) VALUES (?,?,?,?)";
        $ins_v = [$frm_data['user_id'], $frm_data['booking_id'], $frm_data['rating'], $frm_data['review']];
        $ins_res = insert($ins_q, $ins_v, 'iiis');

        if($ins_res == 1){
            echo 1;
        }
        else{
            error_log("Review insert failed: " . mysqli_error($con));
            echo 0;
        }
    }
?>
