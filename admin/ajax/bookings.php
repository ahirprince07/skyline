<?php

    require('../inc/db_config.php');
    require('../inc/essentials.php');
    adminLogin();

    if(isset($_POST['get_bookings']))
    {
        $query = "SELECT b.*, u.name as user_name, u.email as user_email, r.name as room_name, r.price, b.access
                  FROM `bookings` b
                  INNER JOIN `user_cred` u ON b.user_id = u.id
                  INNER JOIN `rooms` r ON b.room_id = r.id
                  ORDER BY b.booking_date DESC";

        $res = mysqli_query($con, $query);
        $i = 1;

        $data = "";

        while($row = mysqli_fetch_assoc($res))
        {
            $checkin = date("d-m-Y", strtotime($row['checkin']));
            $checkout = date("d-m-Y", strtotime($row['checkout']));
            $booking_date = date("d-m-Y H:i", strtotime($row['booking_date']));

            $status_bg = "";
            $status_btn = "";

            if($row['status'] == 'confirmed')
            {
                $status_bg = "bg-success";
                $status_btn = "<button onclick='toggle_status($row[id], \"cancelled\")' class='btn btn-warning btn-sm shadow-none'>Cancel</button>";
            }
            else if($row['status'] == 'cancelled')
            {
                $status_bg = "bg-danger";
                $status_btn = "<button onclick='toggle_status($row[id], \"confirmed\")' class='btn btn-success btn-sm shadow-none'>Confirm</button>";
            }
            else
            {
                $status_bg = "bg-warning";
                $status_btn = "<button onclick='toggle_status($row[id], \"confirmed\")' class='btn btn-success btn-sm shadow-none'>Confirm</button>
                               <button onclick='toggle_status($row[id], \"cancelled\")' class='btn btn-danger btn-sm shadow-none ms-1'>Cancel</button>";
            }

            $access_btn = "";
            if($row['access'] == 1)
            {
                $access_btn = "<button onclick='toggle_access($row[id], 0)' class='btn btn-success btn-sm shadow-none'>Yes</button>";
            }
            else
            {
                $access_btn = "<button onclick='toggle_access($row[id], 1)' class='btn btn-danger btn-sm shadow-none'>No</button>";
            }

            $data .= "
                <tr>
                    <td>$i</td>
                    <td>
                        <b>$row[user_name]</b><br>
                        $row[user_email]
                    </td>
                    <td>
                        <b>$row[room_name]</b><br>
                        ₹$row[price] per night
                    </td>
                    <td>$checkin</td>
                    <td>$checkout</td>
                    <td>₹$row[amount]</td>
                    <td>$row[payment_method]</td>
                    <td><span class='badge $status_bg'>$row[status]</span></td>
                    <td>$booking_date</td>
                    <td>$status_btn</td>
                    <td>$access_btn</td>
                </tr>
            ";
            $i++;
        }
        echo $data;
    }

    if(isset($_POST['toggle_status']))
    {
        $frm_data = filteration($_POST);
        $q = "UPDATE `bookings` SET `status`=? WHERE `id`=?";
        $v = [$frm_data['value'], $frm_data['toggle_status']];

        if(update($q, $v, 'si'))
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }

    if(isset($_POST['cancel_booking']))
    {
        $frm_data = filteration($_POST);
        $q = "UPDATE `bookings` SET `status`=? WHERE `id`=?";
        $v = ['cancelled', $frm_data['cancel_booking']];

        if(update($q, $v, 'si'))
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }

    if(isset($_POST['toggle_access']))
    {
        $frm_data = filteration($_POST);
        $q = "UPDATE `bookings` SET `access`=? WHERE `id`=?";
        $v = [$frm_data['value'], $frm_data['toggle_access']];

        if(update($q, $v, 'si'))
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }

    if(isset($_POST['search_booking']))
    {
        $frm_data = filteration($_POST);
        $query = "SELECT b.*, u.name as user_name, u.email as user_email, r.name as room_name, r.price, b.access
                  FROM `bookings` b
                  INNER JOIN `user_cred` u ON b.user_id = u.id
                  INNER JOIN `rooms` r ON b.room_id = r.id
                  WHERE u.name LIKE ? OR r.name LIKE ?
                  ORDER BY b.booking_date DESC";

        $search_term = "%".$frm_data['search']."%";
        $res = select($query, [$search_term, $search_term], 'ss');
        $i = 1;

        $data = "";

        while($row = mysqli_fetch_assoc($res))
        {
            $checkin = date("d-m-Y", strtotime($row['checkin']));
            $checkout = date("d-m-Y", strtotime($row['checkout']));
            $booking_date = date("d-m-Y H:i", strtotime($row['booking_date']));

            $status_bg = "";
            $status_btn = "";

            if($row['status'] == 'confirmed')
            {
                $status_bg = "bg-success";
                $status_btn = "<button onclick='toggle_status($row[id], \"cancelled\")' class='btn btn-warning btn-sm shadow-none'>Cancel</button>";
            }
            else if($row['status'] == 'cancelled')
            {
                $status_bg = "bg-danger";
                $status_btn = "<button onclick='toggle_status($row[id], \"confirmed\")' class='btn btn-success btn-sm shadow-none'>Confirm</button>";
            }
            else
            {
                $status_bg = "bg-warning";
                $status_btn = "<button onclick='toggle_status($row[id], \"confirmed\")' class='btn btn-success btn-sm shadow-none'>Confirm</button>
                               <button onclick='toggle_status($row[id], \"cancelled\")' class='btn btn-danger btn-sm shadow-none ms-1'>Cancel</button>";
            }

            $access_btn = "";
            if($row['access'] == 1)
            {
                $access_btn = "<button onclick='toggle_access($row[id], 0)' class='btn btn-success btn-sm shadow-none'>Granted</button>";
            }
            else
            {
                $access_btn = "<button onclick='toggle_access($row[id], 1)' class='btn btn-danger btn-sm shadow-none'>Denied</button>";
            }

            $data .= "
                <tr>
                    <td>$i</td>
                    <td>
                        <b>$row[user_name]</b><br>
                        $row[user_email]
                    </td>
                    <td>
                        <b>$row[room_name]</b><br>
                        ₹$row[price] per night
                    </td>
                    <td>$checkin</td>
                    <td>$checkout</td>
                    <td>₹$row[amount]</td>
                    <td>$row[payment_method]</td>
                    <td><span class='badge $status_bg'>$row[status]</span></td>
                    <td>$booking_date</td>
                    <td>$status_btn</td>
                    <td>$access_btn</td>
                </tr>
            ";
            $i++;
        }
        echo $data;
    }
?>
